<?php
if(!empty($_SESSION['permissions'][0]))
{
  $idlist=$mysqli->real_escape_string($_SESSION['permissions'][0]);
  $sql="SELECT id, subdomain, url, url_part, menuorder, title, level, menutitle, active, membersonly FROM ".$config->dbprefix."pages WHERE id IN (".$idlist.") ORDER BY subdomain, level, menuorder";
}else{
  $sql="SELECT id, subdomain, url, url_part, menuorder, title, level, menutitle, active, membersonly FROM ".$config->dbprefix."pages ORDER BY subdomain, level, menuorder";
}
$result=$mysqli->query($sql);
$return='
<script src="'.$config->jquerysource.'" type="text/javascript"></script>
<script src="'.$config->jqueryuisource.'" type="text/javascript"></script>
<link href="'.$config->pluginsfolder.'dynatree/ui.dynatree.css" rel="stylesheet" type="text/css" id="skinSheet">
<script src="'.$config->pluginsfolder.'dynatree/jquery.dynatree.js" type="text/javascript"></script>';
$list=array();
if(isset($_POST['editNewId'])AND(preg_match('/([0-9]{1,10})/', $_POST['editNewId'])))
{
  $activeId=$_POST['editNewId'];
}else{
  $activeId=null;
}
                                                                      
$i=0;
$resultArr=array();
while ($row = $result->fetch_array())
{ 
  if(($row['membersonly']!='Yes')OR(isset($_SESSION['permissions'][$row['id']])))
  {
    $key=str_replace('/', '_', $row["url"]);
    if (is_numeric(substr($key, 0, 1)))
    {
      $key='a__'.$key;
    }
    if(!empty($row['menutitle'])){
      $title=$row['menutitle'];
    }else if(!empty($row['title']))
    {
      $title=$row['title'];
    }else if(!empty($row['url'])){
      $title=$row['url'];
    }else{
      $title='unknown title';
    }  
    $title=ltrim($row['subdomain'].'.'.$title, '.').'~('.$row['id'].')~'.$row['url'];
    $urlPart=$row['url_part'];
    if($row["subdomain"]!='')
    {
      $url=$row["subdomain"].'\/'.$row["url"];
      $url=trim($url, '/');
      $level=$row["level"]+1;
      if($row['url_part']=='')
      {
        $urlPart=$row['subdomain'].'\\';
      }
    }else{
      $url='^/'.$row["url"];
      $level=$row["level"];
    }  
    if($url=='^/')
    {  
      $urlPart='^'.$urlPart;
      $url='';
    }
    $resultArr[] = array(
      "url" => $url,
      "old_url" => $url,
      "url_part" => $urlPart,
      "treeorder" => $row["menuorder"],
      "key" => $key,
      "id" => $row['id'],
      "title" => $title,
      "tooltip" => $row['title'], 
      "level" => $level,  
      "active" => $row['active'],
      //"expand" => true,  
      //"isFolder" => 'true',  
    );
    $list[$row["url"]]=$i;
    $i++;
  }
}

function createTree($value)
  {
  if(strpos($value['url'], '/')==false)
    {
    $temparr['___'.$value['url_part']]=$value;
    }
  else  
    {
    $parentPart='___'.substr($value['url'],0, strpos($value['url'], '/') );
    $minusParentPart=substr($value['url'],strpos($value['url'], '/')+1 );
    if(strpos($minusParentPart, '/')==false)
      {
      $secondParentPart=$minusParentPart;
      }
    else
      {
      $secondParentPart=substr($minusParentPart,0 ,strpos($minusParentPart, '/') );
      }  
    $value['url']=substr($value['url'],strpos($value['url'], '/')+1 );
    $temparr[$parentPart]['children']=createTree($value);
    } 
  return $temparr; 
  }
$array=array();
foreach($resultArr as $key => $value)
{
  $array=array_merge_recursive($array, createTree($value));
}
function renameKey($array, $activeId)
{
  $array2=array();
  $i=0;
  foreach ($array as $key => &$value)
    {
    $array2[$i]=$value;
    if($array2[$i]["active"]=='No')
      {
      $array2[$i]["addClass"] = 'inactive';
      }
    if(isset($value['children']))
      {
      $array2[$i]["isFolder"] = true;
      $array2[$i]['children']=renameKey($value['children'], $activeId);
      }
    if($value['url_part']=='^')
      {
      $array2[$i]["expand"] = true;
      } 
      
    if($value['id']==$activeId) 
      {
      $array2[$i]["select"] = true;
      }  
      
    if(substr($value['url_part'], -1, 1)=='\\')
      {  
      $array2[$i]["addClass"] = 'subdomain';
      }
    $i++;
    }
  return $array2;
}    
$oldArray=$array;
$array=renameKey($array, $activeId);

$dynaConfig['children']=$array;
$dynaConfigJSON = htmlspecialchars(json_encode($dynaConfig));
$return.='
<style>                          
span.dynatree-empty,
span.dynatree-vline,
span.dynatree-expander,
span.dynatree-icon,
span.dynatree-checkbox,
span.dynatree-radio,
span.dynatree-drag-helper-img,
#dynatree-drop-marker{background-image: url("'.$config->weburl.'includes/plugins/dynatree/icons.gif");}
span.dynatree-folder a{font-weight: normal;}
ul.dynatree-container a{padding: 0 5 3 3;}
.custom1{background-color: maroon;color: yellow;}  
.inactive a{color: #888 !important; }  
.inactive span.dynatree-icon /* Default icon */{margin-left: 3px;background-position: -80px 0px;}
.inactive.dynatree-ico-cf span.dynatree-icon{margin-left: 3px;background-position: -15px -15px;}
.inactive.dynatree-ico-ef span.dynatree-icon{margin-left: 3px;background-position: -80px -15px;}
ul.dynatree-container span.td{position: relative; display: inline; border-size: 1px; overflow: hidden;padding: 0px 5px;}
ul.dynatree-container span.td:nth-child(1){position: static;}
ul.dynatree-container span.td:nth-child(2){position: relative; color: #aaa; padding: 0px 5px; width: 30px;}
ul.dynatree-container span.td:nth-child(3){position: relative; color: #0a0; padding: 0px 5px; width: 30px;}
.subdomain{border-top: 1px dashed silver;}
</style>';
$return.='<div id="tree" data-dyna="'.$dynaConfigJSON.'"></div>';
?>