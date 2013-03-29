<?php
if((!empty($_GET['m']))AND(!empty($_GET['id']))AND(!empty($_GET['h'])))
{
  $invitationData['mail']=$_GET['m'];
  $invitationData['id']=$_GET['id'];
  $invitationData['hash']=$_GET['h'];
  $hash=crypt(($invitationData['id'].'@@@'.$invitationData['mail']), $invitationData['hash']);
  if(($hash==$invitationData['hash'])AND(filter_var($invitationData['mail'], FILTER_VALIDATE_EMAIL)))
  { 
    $sql="SELECT mail, groups, pages, salt FROM ".$config->dbprefix."invitations WHERE id='".$mysqli->real_escape_string($invitationData['id'])."' AND mail='".$mysqli->real_escape_string($invitationData['mail'])."' AND status='Active'";
    $result=$mysqli->query($sql);
    $result=$result->fetch_array();
    $invitation=true;
    $temp=json_decode($result['groups']);
    $invitationData['groups']='';
    foreach($temp as $key=>$value)
    {
      $invitationData['groups'].=','.$key;
    }
    $invitationData['groups']=trim($invitationData['groups'],',');
    $invitationData['pages']=json_decode($result['pages']);
  }else{
    if($config->registration==2)
      $invitation=true;
    else
      $invitation=false;
    unset($invitationData);
    $return=$this->errorLog('9', $GLOBALS);
  } 
}else{
  if((!empty($_GET['m']))OR(!empty($_GET['id']))OR(!empty($_GET['h'])))
  {
    $return=$this->errorLog('9', $GLOBALS);
  }
  if($config->registration==2)
    $invitation=true;
  else
    $invitation=false;
}

if ( ($config->registration>=1) )
{
  if ( ($config->registration==1) OR (($invitation)AND(!empty($invitationData))) )
  { //registration is aloved, so keep going
    if(isset($_POST['submit']))
    {
      if(preg_match('/^([a-zA-Z0-9\.\-_]{5,16})$/', $_POST['username']))
      {
        if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
        {
          if ($_POST['pass']==$_POST['pass2'])
          {
            include('passstrenght.php');
            $passMeter = new passStrenght();
            if( (preg_match('/^([a-zA-Z0-9\.\-_]{5,16})$/', $_POST['pass'])) AND ( ($passMeter->numeric($_POST['pass']))>=$config->passstrenght ) )  
            {
              $username=$mysqli->real_escape_string($_POST['username']);
              $sql="SELECT id FROM ".$mysqli->dbprefix."users WHERE username='$username'";
              $result=$mysqli->query($sql);
              if($result->num_rows==0)
              {
                $email=$mysqli->real_escape_string($_POST['email']);
                $sql="SELECT id FROM ".$mysqli->dbprefix."users WHERE mail='$email'";
                $result=$mysqli->query($sql);
                if($result->num_rows==0)
                {
                  $password=$mysqli->real_escape_string($_POST['pass']);
                  $salt0=$class->salt();
                  $password=crypt($password, $config->crypt.$salt0);
                  $salt=$this->salt();
                  $salt2=$this->salt();
                  
                  $email=$mysqli->real_escape_string($_POST['email']);
                  if(($invitation)AND(!empty($invitationData)))
                  {
                    $usergroups=$mysqli->real_escape_string($invitationData['groups']);
                    $sql="INSERT INTO ".$mysqli->dbprefix."users (username, pass, salt, mail, registred, usergroups, lang) VALUES ('".$username."', '".$password."', '".$salt."', '".$email."', now(), '".$usergroups."', '".$config->lang."')";
                    $mysqli->query($sql);
                    $userId=$mysqli->insert_id;
                    if(!empty($invitationData['pages']))
                    {
                      $sql2="INSERT INTO ".$config->dbprefix."permissions (userid, groupid, permission, parameters) VALUES";
                      foreach($invitationData['pages'] as $key => $value)
                      {
                        $sql2.=" ('".$userId."', 0, '".$key."', '".$value."'),";
                      }
                      $sql2=trim($sql2, ',');
                      $mysqli->query($sql2);
                    }  
                    $sql3="UPDATE ".$config->dbprefix."invitations SET status='Used' WHERE id='".$invitationData['id']."' AND status='Active'";
                    $sql4="UPDATE ".$config->dbprefix."invitations SET status='Inactive' WHERE mail='".$invitationData['mail']."' AND status='Active'";
                    $mysqli->query($sql3);
                    $mysqli->query($sql4);
                  }else{
                    $sql="INSERT INTO ".$mysqli->dbprefix."users (username, pass, salt, mail, registred) VALUES ('$username', '$password', '".$class->salt()."', '$email', now())";
                    $mysqli->query($sql);
                    $userId=$mysqli->insert_id;
                  }
                  $hash=crypt(($userId.'@@@'.$salt), $config->crypt.$salt2);
                  $link=$config->membersurl.'activate/?id='.$userId.'&h='.urlencode($hash);
                  $mailBody=str_replace('[[activlink]]', $link, $lang->activmailbody);
                  $mailBody=str_replace('[[webname]]', $config->webname, $mailBody); 
                  $mailSubject=$lang->activmailsubject;
                  $mailSubject=str_replace('[[webname]]', $config->webname, $mailSubject); 
                  $return.="<h3>".$lang->activsent."</h3>".$_POST['email'];
                  Mail($_POST['email'], $mailSubject, $mailBody, "From: ".$config->activsendermail);
                  if($config->debuggingmode===true)
                    {
                      $return.='<br /><br />Following text is diplayed only if is enabled debugging mode.<hr />e:mail:<br />'.$_POST['email'].'<br />Subject:<br />'.$mailSubject.'<br />mail body:<br />'.$mailBody.'<br />Sender:<br />'.$config->activsendermail.'<hr />';
                      $return.='<hr />SQL : '.$sql.'<hr />SQL2: '.$sql2.'<hr />SQL3: '.$sql3.'<hr />SQL4: '.$sql4.'<hr />';
                    }
                }else{
                  $return.=$lang->emailused;
                }    
              }else{
                $return.=$lang->usernametaken;
              }
            }else{
              $return.=$lang->passtooweak;
            }        
          }else{
            $return.=$lang->passdontmatch;
          }
        }else{
          $return.=$lang->invalidmail;
        }        
      }else{
        $return.=$lang->invalidusername;
      }
    }else{
      if(($invitation)AND(!empty($invitationData)))
      {
        $return.='<form method="post">
        <label for="username">'.$lang->username.':</label><input type="text" name="username" id="username" placeholder="'.$lang->username.'"><br />
        <label for="email">'.$lang->mail.':</label><input type="hidden" name="email" id="email" value="'.$invitationData['mail'].'">'.$invitationData['mail'].'<br />
        <label for="pass">'.$lang->password.':</label><input type="password" name="pass" placeholder="'.$lang->password.'" autocomplete="off">
          <!--<div id="passmeter">
            <div class="passmeteritem" id="passmeter1"></div><div class="passmeteritem" id="passmeter2"></div><div class="passmeteritem" id="passmeter3"></div><div class="passmeteritem" id="passmeter4"></div><br />
            <div id="passmetertext">Password is OK</div>
          </div>-->
            <br />
        <label for="pass2">'.$lang->passwordagain.':</label><input type="password" name="pass2" id="pass2" placeholder="'.$lang->passwordagain.'" autocomplete="off"><br />
        <label for "submit"></label><input type="submit" name="submit" id="submit" value="'.$lang->register.'">
        </form>
        ';
      }else{
        $return.='<form method="post">
        <label for="username">'.$lang->username.':</label><input type="text" name="username" id="username" placeholder="'.$lang->username.'"><br />
        <label for="email">'.$lang->mail.':</label><input type="text" name="email" id="email" placeholder="'.$lang->mail.'"><br />
        <label for="pass">'.$lang->password.':</label><input type="password" name="pass" id="pass" placeholder="'.$lang->password.'" autocomplete="off">
          <!--<div id="passmeter">
            <div class="passmeteritem" id="passmeter1"></div><div class="passmeteritem" id="passmeter2"></div><div class="passmeteritem" id="passmeter3"></div><div class="passmeteritem" id="passmeter4"></div><br />
            <div id="passmetertext">Password is OK</div>
          </div>-->  
            <br />
        <label for="pass2">'.$lang->passwordagain.':</label><input type="password" name="pass2" id="pass2" placeholder="'.$lang->passwordagain.'" autocomplete="off" autocomplete="off"><br />
        <label for "submit"></label><input type="submit" name="submit" id="submit" value="'.$lang->register.'">
        </form>
        ';
      }  
    }
  }else{
    $return.='<h3>'.$lang->noinvitation.'</h3>';
  }                           
}else{
  $return.='<h3>'.$lang->disabledregistration.'</h3>';
}
?>