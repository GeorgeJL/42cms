<?php
if(!empty($_POST['submit'])AND!empty($_POST['name']))
{
  $name=$mysqli->real_escape_string($_POST['name']);
  $description=$mysqli->real_escape_string($_POST['description']);
  $sql="INSERT INTO ".$config->dbprefix."galleries (name, description, addedby, addedbyid, addeddate, active) 
                                            VALUES ('".$name."', '".$description."', '".$_SESSION['username']."', '".$_SESSION['userid']."', now(), 'Yes' )";
  
  $result=$mysqli->query($sql);
  
  $return='<h3>'.$lang->gallerycreated.'</h3><br />
    <label for="code">'.$lang->gallerycode.'</label><input type="text" value="[(show_gallery?id='.$mysqli->insert_id.')]">';
}else{
  $return.='
  <form method="post">
  <label for="name">'.$lang->galleryname.'</label><input type="text" name="name"><br> 
  <label for="description">'.$lang->gallerydescription.'</label><input type="text" name="description"><br>
  <label for="submit"></label><input type="submit" name="submit" value="'.$lang->submit.'"><br>
   </form>
  ';
}
?>