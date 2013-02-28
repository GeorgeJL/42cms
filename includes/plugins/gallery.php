<?php
//$return=print_r($pluginVars, true);
$galleryId=$mysqli->real_escape_string($pluginVars['pluginget']['id']);
$sql="SELECT file, author, addeddate, title, description, galleryid FROM ".$config->dbprefix."images WHERE galleryid=".$galleryId;
$result=$mysqli->query($sql);
$i=1;
$return.='
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>

<link href="'.$config->pluginsfolder.'lightbox/css/lightbox.css" rel="stylesheet" />

<script type="text/javascript">
// Convert divs to queue widgets when the DOM is ready
$(function() {
  
    LightboxOptions = (function() {

    function LightboxOptions() {
      this.fileLoadingImage = \''.$config->pluginsfolder.'lightbox/images/loading.gif\';
      this.fileCloseImage = \''.$config->pluginsfolder.'lightbox/images/close.png\';
      this.resizeDuration = 300;
      this.fadeDuration = 300;
      this.labelImage = "Image";
      this.labelOf = "of";
    }

    return LightboxOptions;

  })();
      
  
    
})
</script>

<style>

.overimage{position: relative; display: inline-block;  width: 285px; vertical-align: middle; text-align: center; margin: 5px;}
.overimage img:hover{outline: 7px solid #999}
</style>

<script src="'.$config->pluginsfolder.'lightbox/js/lightbox.js"></script>
';
while($row=$result->fetch_array())
{
  $i++;                    //author, addeddate, title, description
  
  if(!empty($row['description']))
  {
    $description=$row['description'].'<br />';
  }else{
    $description='';
  }
  
  if(!empty($row['author']))
  {
    $author='<br />'.$lang->author.': '.$row['author'];
  }else{
    $author='';
  }
  
  @$return.='<span class="overimage"><a href="'.$config->galleryfolder.$row['galleryid'].'/'.$row['file'].'" rel="lightbox[roadtrip]" title="'.$row['title'].'" lightdata-desc="<b>'.$row['title'].'</b><br />'.$description.'Added: '.$row['addeddate'].$author.'<br/>"><img src="'.$config->galleryfolder.'/'.$row['galleryid'].'/th/'.$row['file'].'"></a></span>';
  //$return.='<a href="'.$config->galleryfolder.$row['file'].'" rel="lightbox[roadtrip]"  title="<b>'.$row['title'].'</b><br />'.$row['description'].'<br />Added: '.$row['addeddate'].'<br />Author: '.$row['author'].'<hr>"><img src="'.$config->galleryfolder.$row['file'].'" height="150px"></a> ';
  
  if(($i%2)>0)
    $return.='<br />';
  
  //$return.=$row['file'].'<br>';
}
if($i==1)
{
  $return.='<br /><b>'.$lang->noimages.'</b>';
}


//$return.=$sql;


?>