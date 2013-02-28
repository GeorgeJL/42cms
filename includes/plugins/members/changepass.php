<?
if(isset($_POST['submit']))
{
  
  
  
  $sql="SELECT pass FROM ".$config->dbprefix."users WHERE id='".$mysqli->real_escape_string($_SESSION['userid'])."'";
  $result=$mysqli->query($sql);
  $row=$result->fetch_assoc();
  if($row['pass']==(crypt($_POST['curpass'], $row['pass'])))
  { //current password is OK
    if ($_POST['newpass']==$_POST['newpass2'])
    {
      include('passstrenght.php');
      $passMeter = new passStrenght();
      if( (preg_match('/^([a-zA-Z0-9\.\-_]{5,16})$/', $_POST['newpass'])) AND ( ($passMeter->numeric($_POST['newpass']))>=$config->passstrenght ) )  
      {  //new password is ok
        $return.='New pass is ok<br />';
        
        $salt=$this->salt();
        $newpass=crypt($_POST['newpass'], $config->crypt.$salt);
        $sql="UPDATE ".$config->dbprefix."users SET pass='".$newpass."' WHERE id='".$mysqli->real_escape_string($_SESSION['userid'])."'";
        $result=$mysqli->query($sql);
        $return.=$lang->passchanged;
      }else{ //new password is too weak
        $return.=$lang->passtooweak;
        $return.='. <a href="">'.$lang->tryagain.'</a>';
      }        
    }else{  //new passwords do not match
      $return.=$lang->passdontmatch;
      $return.='. <a href="">'.$lang->tryagain.'</a>';
    }
  }else{
    $return.=$class->errorLog('15', $GLOBALS); //WrongPass error
    $return.='. <a href="">'.$lang->tryagain.'</a>';
  }
}else{
  $return.='<form method="post">
        <label for="curpass">'.$lang->curpassword.':</label><input type="password" name="curpass" placeholder="'.$lang->curpassword.'" autocomplete="off"><br />
        <label for="newpass">'.$lang->newpassword.':</label><input type="password" name="newpass" id="newpass" placeholder="'.$lang->newpassword.'" autocomplete="off"><br />
        <label for="newpass2">'.$lang->newpasswordagain.':</label><input type="password" name="newpass2" id="newpass2" placeholder="'.$lang->newpasswordagain.'" autocomplete="off"><br />
        <label for "submit"></label><input type="submit" name="submit" id="submit" value="'.$lang->save.'">
        </form>
        ';
}
?>