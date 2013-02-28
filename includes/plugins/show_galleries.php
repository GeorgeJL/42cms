<?php
$galleries=explode(',',$pluginVars['pluginget']['id']);
$galleries=array_filter($galleries);
$galleries=array_slice($galleries, 0, 1000);

$sql='';
$sql2='';
foreach($galleries as $key=>$value)
{
  if($key==0)
    $sql.="(SELECT id, name, description, addedby, addeddate, url_part FROM ".$config->dbprefix."galleries WHERE active='Yes' AND id='".$mysqli->real_escape_string($value)."')";
  else
    $sql.=" UNION (SELECT id, name, description, addedby, addeddate, url_part FROM ".$config->dbprefix."galleries WHERE active='Yes' AND id='".$mysqli->real_escape_string($value)."')";

  if($key==0)
    $sql2.="(SELECT galleryid, file FROM ".$config->dbprefix."images WHERE galleryid='".$mysqli->real_escape_string($value)."' ORDER BY RAND() LIMIT 1)";
  else
    $sql2.=" UNION (SELECT galleryid, file FROM ".$config->dbprefix."images WHERE galleryid='".$mysqli->real_escape_string($value)."' ORDER BY RAND() LIMIT 1)";  
}
$result=$mysqli->query($sql);
$result2=$mysqli->query($sql2);

while($row2=$result2->fetch_assoc())
{
  $image[$row2['galleryid']]=$config->galleryfolder.$row2['galleryid'].'/th/'.$row2['file'];
}

$return.='
<style type="text/css">
.overgallerylink a
{
  color: black;
}

.overgallery
{
  position: relative;
  width: 575px;
  min-height: 200px;
  border: 1px solid #5ccc5c;
  margin: 10px 0px;
  padding: 10px;
}

.overgallery:hover
{
  box-shadow: 0px 0px 2px 1px #006300;
}

.gallerytext
{
  position: relative;
  width: 320px;
  float: left;
  margin-right: 15px;
}
</style>
';


while($row=$result->fetch_assoc())
{
  $return.='<div class="overgallerylink"><a href="'.$row['url_part'].'/"><div class="overgallery"><div class="gallerytext"><h3>'.$row['name'].'</h3><p>'.$row['description'].'</p></div><div class="gallerytemplate"><img src="'.$image[$row['id']].'"></div><span style="clear: left"></span></div></a></div>';
}

?>