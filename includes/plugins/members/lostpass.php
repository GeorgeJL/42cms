<?php
if(!empty($pluginVars['afterpath'])AND empty($step3) )
{
  $pluginParameters=explode('-', $pluginVars['afterpath']);
  if(isset($pluginParameters[0])AND($pluginParameters[0]=='token'))
  {
    $tokenProcessing=true;
echo 'token processing';
    $token=$mysqli->real_escape_string($pluginParameters[2]);
    $sql="SELECT time, uniqueid FROM ".$config->dbprefix."passreset WHERE token='".$token."' LIMIT 1";
    $result=$mysqli->query($sql);
    $row=$result->fetch_array();
    if($row['uniqueid']=='N'.$pluginParameters[1])
    {
      $return.='
      <form method="post" action="./">
      <label for="pass1">'.$lang->newpass.'</label><input type="password" name="pass1" placeholder="'.$lang->password.'" autocomplete="off"><br />
      <label for="pass2">'.$lang->newpassagain.'</label><input type="password" name="pass2" placeholder="'.$lang->passwordagain.'" autocomplete="off"><br />
      <input type="hidden" name="token" value="'.$token.'">
      <input type="hidden" name="uid" value="'.$pluginParameters[1].'">
      <label for="save"></label><input type="submit" name="save" value="'.$lang->save.'">
      </form>
      ';
    }else{
      $return.=$this->errorLog('13',get_defined_vars());
    }                      
  }else{
    $return.=$this->errorLog('13',get_defined_vars());
  }
}

if(empty($tokenProcessing))
{
  if(isset($_POST['submit']))
  {
    $username=$mysqli->real_escape_string($_POST['username']);
    $mail=$mysqli->real_escape_string($_POST['mail']);
    $sql="SELECT salt, id FROM ".$config->dbprefix."users WHERE username='".$username."' AND mail='".$mail."'";
    $result=$mysqli->query($sql);
    $row=$result->fetch_array();
    if (empty($row))
    {
      $return.=$lang->wronguserormail.'<br /><a href="">'.$lang->tryagain.'</a>';
    }else{
      $token=sha1($this->salt().$row['salt'].$row['id']);
      $sql="INSERT INTO ".$config->dbprefix."passreset (userid, token, ip, uniqueid) VALUES ('".$row['id']."', '".$token."', '".$_SERVER['REMOTE_ADDR']."', 'N".$row['id']."') ON DUPLICATE KEY UPDATE token='".$token."', time=now()";  //do uniqueid prida N ako new a id uzivatela-aby bolo vzdy len 1 aktivne na uzivatela, ale zaroven aby drzalo v DB ktory uzivatelia uz obnovovali pass
      $mysqli->query($sql);
      $link=$config->lostpassurl.'token-'.$row['id'].'-'.$token;
      $mailBody=str_replace('[[recovlink]]', $link, $lang->lastpassmailbody);
      $mailBody=str_replace('[[webname]]', $config->webname, $mailBody); 
      $mailSubject=$lang->lastpassmailsubject;
      $mailSubject=str_replace('[[webname]]', $config->webname, $mailSubject); 
      Mail($mail, $mailSubject, $mailBody, "From: ".$config->recovsendermail);
      if($config->debuggingmode===true)
      {
        $return.='<br /><br />Following text is diplayed only if is enabled debugging mode.<hr />e:mail:<br />'.$mail.'<br />Subject:<br />'.$mailSubject.'<br />mail body:<br />'.$mailBody.'<br />Sender:<br />'.$config->recovsendermail.'<hr />';
      }
    }  
  }else if(isset($_POST['save'])){
    $step3=true;
    $uid=$mysqli->real_escape_string($_POST['uid']);
    $token=$mysqli->real_escape_string($_POST['token']);
    $sql="SELECT time, uniqueid FROM ".$config->dbprefix."passreset WHERE token='".$token."' LIMIT 1";
    $sql2="REPLACE INTO ".$config->dbprefix."passreset (userid, token, ip, uniqueid) VALUES ('".$uid."', '".$token."', '".$_SERVER['REMOTE_ADDR']."', 'U".$uid."')";
    $sql3="DELETE FROM ".$config->dbprefix."passreset WHERE `uniqueid`='N".$uid."' and token='".$token."'";
    $result=$mysqli->query($sql);
    $mysqli->query($sql2);
    $mysqli->query($sql3);
    $row=$result->fetch_array();
    if ($row['uniqueid']=='N'.$_POST['uid'])
    {
      /**
      *
      * add here javascript control before sending, because this do not offer multiple tryes-after each try requires new token
      *
      */
      if($_POST['pass1']==$_POST['pass2'])
      {
        include('passstrenght.php');
        $passMeter = new passStrenght();
        if ( ($passMeter->numeric($_POST['pass1']))>=$config->passstrenght )
        {
        $hash=crypt($_POST['pass1'], $config->crypt.$this->salt());
          $uid=$mysqli->real_escape_string($_POST['uid']);
          $sql="UPDATE ".$config->dbprefix."users SET pass='".$hash."' WHERE id='".$uid."'"; 
          $mysqli->query($sql);
          
          $sql="UPDATE ".$config->dbprefix."passreset SET successful='Yes' WHERE userid='".$uid."' AND token='".$token."'"; 
          $mysqli->query($sql);
          
          $return.=$lang->chengedpass.' <a href='.$config->membersurl.'>'.$lang->login.' !</a>';
        }else{
          $return=$lang->passtooweak.' <a href="'.$config->lostpassurl.'">'.$lang->tryagain.'</a>';
        }
      }else{
        $return=$lang->passdontmatch.' <a href="'.$config->lostpassurl.'">'.$lang->tryagain.'</a>';
      }  
    }else{
      $return=$lang->invalidlink.' <a href="'.$config->lostpassurl.'">'.$lang->tryagain.'</a>';
    }
  }else{
    $return.='
    <form method="post">
    <label for="username">'.$lang->username.':</label><input type="text" name="username" placeholder="'.$lang->username.'"><br />
    <label for="mail">'.$lang->mail.':</label><input type="text" name="mail" placeholder="'.$lang->mail.'"><br />
    <label for="submit"></label><input type="submit" name="submit" value="'.$lang->submit.'">
    ';
  }
}
?>