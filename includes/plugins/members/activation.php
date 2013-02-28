<?php
$sql="SELECT salt FROM ".$mysqli->dbprefix."users WHERE id='".$mysqli->real_escape_string($_GET['id'])."' AND activated='No' ";
$result=$mysqli->query($sql);
if($result->num_rows>0)
{
  $result=$result->fetch_array();
  $hash=crypt(($_GET['id'].'@@@'.$result['salt']), $_GET['h']);
  if($hash=$_GET['h'])
  {
    $sql2="UPDATE ".$mysqli->dbprefix."users SET activated='Yes' WHERE id='".$mysqli->real_escape_string($_GET['id'])."'";
    $result=$mysqli->query($sql2);
    if (($mysqli->affected_rows)==1)
    {
      $return.=$lang->activatednow;
    }else{
      $return.=$this->errorLog('10', $GLOBALS);
    }
  }else{
    $return.=$this->errorLog('11', $GLOBALS);
  }
}else{
  $return.=$this->errorLog('12', $GLOBALS);
}                    



?>