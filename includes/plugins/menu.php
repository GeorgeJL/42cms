<?php
$level='';
if(!empty($pluginVars['pluginget']['levelfrom']))
{
  $level.=" AND level>=".$mysqli->real_escape_string($pluginVars['pluginget']['levelfrom']);
}

if(!empty($pluginVars['pluginget']['levelto']))
{
  $level.=" AND level<=".$mysqli->real_escape_string($pluginVars['pluginget']['levelto']);
}

if(!empty($pluginVars['pluginget']['level']))
{
  $level=$mysqli->real_escape_string($pluginVars['pluginget']['level']);
  $level=" AND level=".$level;
}

$subDomain=$mysqli->real_escape_string($pluginVars['subdomain']);

if(empty($pluginVars['pluginget']['dontshow']))
{
  $dontShow='';
}else{
  $dontShow=$mysqli->real_escape_string($pluginVars['pluginget']['dontshow']);
  $dontShow="AND url NOT LIKE '".$dontShow."%'";
}

if(empty($pluginVars['pluginget']['showpart']))
{
  $sql="SELECT url, id, subdomain, url_part, level, menuorder, menutitle, id, inmenu FROM ".$config->dbprefix."pages WHERE subdomain='".$subDomain."' AND active='Yes' AND inmenu!='non' ".$level." ORDER BY level, menuorder, title";
}else{
  $showPart=$mysqli->real_escape_string($pluginVars['pluginget']['showpart']);
  $sql="SELECT url, id, subdomain, url_part, level, menuorder, menutitle, id, inmenu FROM ".$config->dbprefix."pages WHERE subdomain='".$subDomain."' AND active='Yes' AND inmenu!='non' ".$level." AND url LIKE '".$showPart."%' ORDER BY level, menuorder, title";
}  

$result=$mysqli->query($sql);
$list=array();
$i=0;
while ($row = $result->fetch_array()) 
{ 
  $key=str_replace('/', '_', $row["url"]);
  if (is_numeric(substr($key, 0, 1))){
    $key='a__'.$key;
  }else{
    $key=$key;
  }
  if ( ($row['inmenu']=='both') OR ((!$pluginVars['loggedin'])AND($row['inmenu']=='nologged')) OR (($pluginVars['loggedin'])AND(($row['inmenu']=='logged')AND(isset($_SESSION['permissions'][$row['id']])))) ) 
  {
    $url=$row['url'];
    $urlPart=$row["url_part"];
    if(empty($url))
    {
      $url='###';
      $urlPart='###';
    }else{
      $url='###/'.$url;
    }
    if((!empty($pluginVars['pluginget']['flatten']))AND($pluginVars['pluginget']['flatten']==1))
    {
      $flatten=true;
      $level=0;
      $url=$urlPart;
    }else{
      $flatten=false;
      $level=$row['level'];       
    }
    $resultArr[] = array(
      "url" => $url,
      "subdomain" => $row['subdomain'],
      "url_part" => $urlPart,
      "treeorder" => $row["menuorder"],
      "menutitle" => $row['menutitle'],
      "level" => $level,
      "old_url" => $row["url"],
      "id" => $row['id']
    );
  }
  $list[$row["url"]]=$i;
  $i++;
}

$menuArray=array();
if(is_array($resultArr))
{
  foreach($resultArr as $key => $value)
  {
    $menuArray=array_merge_recursive($menuArray, $this->createTree($value));
  }
}             
$menuArray=$this->renameKey($menuArray, false);

foreach($menuArray as $key=>&$value)
{
  if(!isset($value['url']))
  {
    $value=$value['children'];
  }
}

if(!isset($menuArray[0]['url']))
{
  $menuArray=$menuArray[0];
}

foreach($menuArray as $key2=>$value2)
{
  if(!isset($value2['url']))
  {
    unset($menuArray[$key2]);
  }  
}

if(empty($pluginVars['subdomain']))
  $weburl=$config->weburl;
else{
  $weburl=str_replace('://www.', '://', $config->weburl);
  $weburl=str_replace('://', '://'.$pluginVars['subdomain'].'.', $weburl);
}
$return.=$this->arrayToList2($menuArray, $weburl, $pluginVars['stringPath'], $flatten);


?>


