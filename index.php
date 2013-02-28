<?php
ini_set('display_errors','On');
//error_reporting(E_ALL);
error_reporting(E_ERROR | E_WARNING | E_PARSE);
session_start();
$pageText='';
require_once('includes/config.php');
$config=new Config;
$langId=$config->lang;
require_once('includes/class.php');
$class=new MainClass;
$class->logfolder=$config->logfolder;
$class->newlogfiletime=$config->newlogfiletime;
$mysqli=new mysqli($config->dbserver, $config->dbuser, $config->dbpass, $config->dbname);
$sql="SET NAMES ".$config->dbnameset." COLLATE ".$config->dbcollate;
$mysqli->query($sql);
$mysqli->dbprefix=$config->dbprefix;  //required in class.php

if(isset($_POST['step'])AND($_POST['step']=='preview')) //preview Mode is used for previewing page without saving it. Script recieves page data by post(not from DB)
{ 
  $previewMode=true;
  $result['url']=$_POST['url'];
  $pageId=$_POST['id'];
  $result['title']=stripslashes($_POST['title']);
  $result['membersonly']=$_POST['membersonly'];
  $result['h1']=stripslashes($_POST['h1']);
  $rawPageText=stripslashes($_POST['text']);
  $result['template']=$_POST['template'];
  $addons='';//$_POST['addons'];
  $result['loadlang']='Yes';
  $path=explode('/',trim($result['url'],'/'));
}else{
  $url=$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];  //get whole url without http://, query (?q=1) and anchor (#) e.g.:   subdomain.domain.com/blablabla/blabla/bla
  $host=parse_url('http://'.$url);  //add http:// to make it work with parser
  $subDomain=array_diff(explode('.', $host['host']), explode('.', $config->domain));    // compares host part of url (subdomain.domain.com) with defined DOMAIN to find out if is there subdomain -returns array
  $subDomain=@$subDomain[0];  // makes string of the returned array
  if($subDomain==strtolower('www'))
    $subDomain='';
  $path=explode('/', trim($_SERVER['REQUEST_URI'], '/'));  // splits path into single elements in array
  $homePath=explode('/', $config->weburl); //  splits weburl into single elements in array 
  $path=array_diff($path, $homePath);  //compare path array with homePath array to find just ADDED elements in url (usefull for websites which have homepage url like e.g.: domain.com/shop/  

  foreach($path as $key => $value){      
    if(!preg_match("/^([a-zA-Z0-9\-\_\+\!\'\(\)\,]+)$/", $value)){     //alowed characters in url in MyCMS a-z A-Z 0-9 -_+!'(),
      unset($path[$key]);
    }
  }
  $stringPath=implode('/', $path);
  $path=array_slice($path, '0', '1000');  //changes array keys from e.g.:(2, 3, 7...) to (0, 1, 2...)
  if (count($path)>1){ //used for performance purposes
    $sql="SELECT url, id, title, membersonly, h1, text, template, addons, loadlang FROM ".$config->dbprefix."pages WHERE active='Yes' AND subdomain='$subDomain' AND concat( '$stringPath', '/' ) LIKE concat( `url` , '/%' ) ORDER BY LENGTH( url ) DESC LIMIT 1";
  } else {
    $sql="SELECT url, id, title, membersonly, h1, text, template, addons, loadlang FROM ".$config->dbprefix."pages WHERE active='Yes' AND subdomain='$subDomain' AND url='$stringPath' LIMIT 1";
  }
  $result=$mysqli->query($sql);
  if(($result->num_rows)==0)
  {
    $sql="SELECT url, id, title, membersonly, h1, text, template, addons, loadlang FROM ".$config->dbprefix."pages WHERE id='".$config->id404."'";
    $result=$mysqli->query($sql);
  }
  $result=$result->fetch_array(MYSQLI_ASSOC);
  $addons=$result['addons'];
  $pageId=$result['id'];
  $rawPageText=$result['text'];
}

if ($pageId==$config->idlogout)
{
  session_destroy();
  unset($_SESSION);
  setcookie($config->cookieprefix.'-userid', '', time()-100, '/');  
  setcookie($config->cookieprefix.'-username', '', time()-100, '/');  
  setcookie($config->cookieprefix.'-hash', '', time()-100, '/'); 
}

if($result['membersonly']=='Yes')
{
  $membersOnly=true;
  $loadLang=true;
}else{
  $membersOnly=false;
  if($result['loadlang']=='Yes')
  {
    $loadLang=true;
  }else{
    $loadLang=false;
  }
}

//template insertion
if ($result['template']>0)
{
  @$template=file_get_contents('includes/templates/'.$result['template'].'/index.html');
  if ($template==false)
  {
    $template='<!DOCTYPE HTML><html><head><title>[[title]]</title></head><body><h1>[[h1]]</h1>[[body]]</body></html>';
    $pageText.=$class->errorLog('1', $GLOBALS);  //NoTemplate error
  }
}else if($result['template']==0){
  $template='<!DOCTYPE HTML><html><head><title>[[title]]</title></head><body><h1>[[h1]]</h1>[[body]]</body></html>';
}else{
  $template='[[body]]';
}

$template=str_replace('[{template_folder}]', $config->weburl.'includes/templates/'.$result['template'].'/', $template);
//end of template insertion

//user authentication
if(isset($_SESSION['userid'], $_SESSION['username'], $_SESSION['permissions'], $_SESSION['mail'], $_SESSION['hash']))
{ 
  $userId=$mysqli->real_escape_string($_SESSION['userid']);
  $sql="SELECT salt, cookieid FROM ".$config->dbprefix."users WHERE id='".$userId."'";
  $result2=$mysqli->query($sql);
  $row=$result2->fetch_array();
  $hash=$config->sessionsalt.$_SESSION['userid'].$row['cookieid'].serialize($_SESSION['permissions']).$_SESSION['username'].$_SESSION['mail'].$_SESSION['lang'].$row['salt'];
  unset($row);
  $hash=crypt(md5($hash).$hash, $_SESSION['hash']);
  if($_SESSION['hash']==$hash)
  {
    $loggedIn=true;   //SUCCESSFULLY LoggedIn
  }else{
    $pageText.=$class->errorLog('2', $GLOBALS); //SessionDoNotMatch error
    $loggedIn=false;
    unset($_SESSION['userid'], $_SESSION['username'], $_SESSION['permissions'], $_SESSION['mail'], $_SESSION['hash'], $_SESSION['lang']);
  }
}
if(@$loggedIn!==true)
{ 
  if(isset($_POST['login'])) 
  {
    if($config->attemptsBeforeCaptcha>=0)
    {
      $sql="INSERT INTO ".$config->dbprefix."login_attempts (ip) VALUES (INET_ATON('".$_SERVER["REMOTE_ADDR"]."')) ON DUPLICATE KEY UPDATE attempt=LAST_INSERT_ID(attempt+1)";
      $result=$mysqli->query($sql);
      if(mysqli_insert_id($mysqli) >= $config->attemptsBeforeCaptcha)
      {
        $useCaptcha=true;
      }else{
        $useCaptcha=false;
      }
    }  
    
    if($useCaptcha)
    {
      if($_POST["recaptcha_response_field"])
      {
        require_once('includes/recaptcha/recaptchalib.php');
        $resp = recaptcha_check_answer ($config->recaptchaprivate,
                                        $_SERVER["REMOTE_ADDR"],
                                        $_POST["recaptcha_challenge_field"],
                                        $_POST["recaptcha_response_field"]);
        if($resp->is_valid) 
        {
          $captchaOk=true;
        }else{
          $captchaOk=false;
        }
      }else{
        $captchaOk=false;
      }
    }else{
      $captchaOk=true;
    }  
    
    if($captchaOk)
    {    
      $username=$mysqli->real_escape_string($_POST['username']);
      if($config->requireverification==1)
      {
        $sql="SELECT id, username, usergroups, mail, salt, pass, cookieid, lang, activated FROM ".$config->dbprefix."users WHERE username='".$username."'";
      }else{
        $sql="SELECT id, username, usergroups, mail, salt, pass, cookieid, lang FROM ".$config->dbprefix."users WHERE username='".$username."'";
      }
      $result2=$mysqli->query($sql);
      $row=$result2->fetch_array();
      if( ($config->requireverification==0) OR (($config->requireverification==1)AND($row['activated']=='Yes')) )
      {  //account is activated or activation is not required
        if($row['pass']==(crypt($_POST['password'], $row['pass'])))
        { //password is OK
          $cookieId=mt_rand(1000, 65535);
          $_SESSION['userid']=$row['id'];    
          $_SESSION['username']=$_POST['username'];    
          $_SESSION['permissions']=$class->getPermissions($mysqli, $row['id'], $row['usergroups']);
          $_SESSION['mail']=$row['mail'];   
          $_SESSION['lang']=$row['lang'];
          $hash=$config->sessionsalt.$_SESSION['userid'].$cookieId.serialize($_SESSION['permissions']).$_SESSION['username'].$_SESSION['mail'].$_SESSION['lang'].$row['salt'];
          $hash=crypt(md5($hash).$hash, $config->crypt.$class->salt());
          $_SESSION['hash']=$hash;
          $sql="UPDATE ".$config->dbprefix."users SET cookieid='".$cookieId."' WHERE id='".$_SESSION['userid']."'";
          $result2=$mysqli->query($sql);
          if(isset($_POST['rememberme'])AND($_POST['rememberme']=='on'))
          {
            $cookieLife=time()+$config->rememberme;
            setcookie($config->cookieprefix.'-userid', $_SESSION['userid'], $cookieLife, '/');  
            setcookie($config->cookieprefix.'-username', $_SESSION['username'], $cookieLife, '/');  
            $cookieHash=$config->cookiesalt.$cookieId.$_SESSION['userid'].$row['salt'].$row['pass'].$_SESSION['username'];
            $salt=$class->salt();
            $cookieHash=crypt(md5($cookieHash).$cookieHash, $config->crypt.$salt);
            setcookie($config->cookieprefix.'-hash', $cookieHash, $cookieLife, '/'); 
          }
          unset($row);
          $loggedIn=true;
          //echo 'Just logged in';
          if($config->attemptsBeforeCaptcha>=0)
          {
            $sql="DELETE FROM ".$config->dbprefix."login_attempts WHERE ip=INET_ATON('".$_SERVER["REMOTE_ADDR"]."')";
            $mysqli->query($sql);
          }
        }else{
          $pageText.=$class->errorLog('6', $GLOBALS); //WrongPassOrUsername error
        }      
      }else{
        $accountActivated=false;
        $pageText.=$class->errorLog('7', $GLOBALS); //InactiveAccount error
      }
    }else{
      if($_POST["recaptcha_response_field"]){
        $pageText.=$class->errorLog('4', $GLOBALS); //reCaptcha error
      }else{
        $pageText.=$class->errorLog('5', $GLOBALS); //NoCaptcha error
      }
    }
  } else if(isset($_COOKIE[$config->cookieprefix.'-userid'],$_COOKIE[$config->cookieprefix.'-username'],$_COOKIE[$config->cookieprefix.'-hash'])) {
    $userId=$mysqli->real_escape_string($_COOKIE[$config->cookieprefix.'-userid']);
    $sql="SELECT cookieid, pass, salt, id, username, usergroups, mail FROM ".$config->dbprefix."users WHERE id='".$userId."'";
    $result2=$mysqli->query($sql);
    $row=$result2->fetch_array();
    $cookieId=$row['cookieid'];
    $hash=$config->cookiesalt.$cookieId.$_COOKIE[$config->cookieprefix.'-userid'].$row['salt'].$row['pass'].$_COOKIE[$config->cookieprefix.'-username'];
    $hash=crypt(md5($hash).$hash, $_COOKIE[$config->cookieprefix.'-hash']);
    if ($_COOKIE[$config->cookieprefix.'-hash']==$hash)
    { //hash is OK
      $_SESSION['userid']=$row['id'];    
      $_SESSION['username']=$row['username'];    
      $_SESSION['permissions']=$class->getPermissions($mysqli, $row['id'], $row['usergroups']);
      $_SESSION['mail']=$row['mail'];    
      $hash=$config->sessionsalt.$_SESSION['userid'].$row['cookieid'].serialize($_SESSION['permissions']).$_SESSION['username'].$_SESSION['mail'].$_SESSION['lang'].$row['salt'];
      $hash=crypt(md5($hash).$hash, $config->crypt.$class->salt());
      $_SESSION['hash']=$hash;
      $loggedIn=true;
    }else{
      $pageText.=$class->errorLog('0', $GLOBALS); //CookieDoNotMatch error
      $loggedIn=false;
      setcookie($config->cookieprefix.'-userid', '', time()-100, '/');  
      setcookie($config->cookieprefix.'-username', '', time()-100, '/');  
      setcookie($config->cookieprefix.'-hash', '', time()-100, '/'); 
    }
    unset($row);
  }      
}

if($loadLang)
{
  /*
  if ( (isset($_SESSION['lang'])) AND (preg_match('/^[a-z]{2}$/', $_SESSION['lang'])) ) 
    {
      $langId=$_SESSION['lang'];
    }else{
      $langId=$config->lang;
    }
  */
    $langId=$config->lang;
    
    include_once('includes/lang/'.$langId.'.lang.php');
    $langData=new Lang;
} 

if ($membersOnly){
  if (@$loggedIn===true){
    if(isset($_SESSION['permissions'][$pageId]))
    { //user is allowed to access this page
      $pageText.=$rawPageText;
    } else {
      $pageText.=$langData->noallowed;
    }
  } else if($accountActivated===false){  //login attempt without activating account (email confirmation)

  }else{
    if(@$loggedIn===false){
      $incorrect=' class="incorrect"';
    }else{
      $incorrect='';
    }
    if($config->attemptsBeforeCaptcha>=0)
    {
      $sql="SELECT attempt FROM ".$config->dbprefix."login_attempts WHERE ip=INET_ATON('".$_SERVER["REMOTE_ADDR"]."')";
      $result=$mysqli->query($sql);
      $result=$result->fetch_array();
      if($result['attempt'] >= $config->attemptsBeforeCaptcha)
      {
        $useCaptcha=true;
      }else{
        $useCaptcha=false;
      }
    }else{
      $useCaptcha=false;
    } 
    $pageText.='<form method="post">
            <label for="username">'.$langData->username.': </label><input type="text" name="username"'.$incorrect.' id="username"><br />
            <label fpr="password">'.$langData->password.': </label><input type="password" name="password"'.$incorrect.'><br />
            ';
    if($useCaptcha)
    {        
      require_once('includes/recaptcha/recaptchalib.php');
      $pageText.=$class->errorLog('4', $GLOBALS); //reCaptcha error
    }
    $pageText.='<label for="rememberme"></label><input type="checkbox" name="rememberme" id="rememberme"> '.$langData->rememberme.'<br />
            <label for="login"></label><input type="submit" value="'.$langData->submit.'" name="login" id="login"><br />
            <label></label><a href="'.$config->weburl.'members/lostpass">'.$langData->lostpass.'</a>            
            </form>';
  }
} else {  //page is visible for all visitors (not only members)
  $pageText.=$rawPageText;
}

//overwriting html elements (<title>, <h1> and main content of the page) into TEMPLATE 
$pageText=str_replace(array('[[title]]', '[[h1]]', '[[body]]', '[[user]]'), array($result['title'], $result['h1'], $pageText, '<a href="'.$config->membersurl.'">'.@$_SESSION['username'].'</a>'), $template);

/*insertion of plugins-beginning -insertion of plugin return on the place of placeholder    e.g.    [(pluginname)]   or  [(pluginname?param1=val1&param2=val2)]*/
preg_match_all('/(\[\()(.+?)(\)\])/', $pageText, $matches);
$placeholder=$matches[0];
$matches=$matches[2];
$dbUrlPath=explode('/', trim($result['url'], '/'));
$dbUrlPath=array_filter($dbUrlPath);  //removes empty values
$partPath=implode('/', array_diff($path, $dbUrlPath));

$partConfig->domain=$config->domain;
$partConfig->webname=$config->webname;
$partConfig->weburl=$config->weburl;
$partConfig->membersurl=$config->membersurl;
$partConfig->registerurl=$config->registerurl;
$partConfig->lostpassurl=$config->lostpassurl;
$partConfig->imagesfolder=$config->imagesfolder;
$partConfig->datafolder=$config->datafolder;
$partConfig->filesfolder=$config->filesfolder;
$partConfig->galleryfolder=$config->galleryfolder;
$partConfig->dbprefix=$config->dbprefix;
$partConfig->lang=$langId;
$partConfig->cookieprefix=$config->cookieprefix;
$partConfig->crypt=$config->crypt;
$partConfig->passstrenght=$config->passstrenght;
$partConfig->registration=$config->registration;
$partConfig->cookieprefix=$config->cookieprefix;
$partConfig->editpageurl=$config->editpageurl;
$partConfig->noticeboardurl=$config->noticeboardurl;
$partConfig->pluginsfolder=$config->pluginsfolder;
$partConfig->debuggingmode=$config->debuggingmode;
$partConfig->jquerysource=$config->jquerysource;
$partConfig->jqueryuisource=$config->jqueryuisource;
$partConfig->jqueryuithemesource=$config->jqueryuithemesource;
$partConfig->maxfilesize=$config->maxfilesize;
$partConfig->maxfilecount=$config->maxfilecount;
$partConfig->maxchunksize=$config->maxchunksize;
$partConfig->invmailsubject=$config->invmailsubject;
$partConfig->invmailbody=$config->invmailbody;
$partConfig->invsendermail=$config->invsendermail;
$partConfig->activsendermail=$config->activsendermail;
$partConfig->recovsendermail=$config->recovsendermail;                                               
//$partConfig->=$config->;

$partConfig->pageid=$pageId;

$pluginVars=array('weburl'=>$config->weburl, 'loggedin'=>@$loggedIn, 'membersonly'=>$membersOnly, 'afterpath'=>$partPath, 'salt'=>$class->salt(), 'stringPath'=>$stringPath); //this variables will be passed to plugins and addons 
unset($pluginVars['salt']);
if (isset($matches[0]))
{//there are some plugins
  foreach($matches as $key => $value){
    $thisPluginPath=explode('?', $value);
    $thisPluginFolder=explode('/', $thisPluginPath[0]);
    //$thisPluginFolder=array_reverse($thisPluginFolder);
    $thisPluginFolder=rtrim($thisPluginPath[0], end($thisPluginFolder)); 
    $pluginVars['thispluginfolder']=$config->weburl.'includes/plugins/'.$thisPluginFolder;
    $replaceWith=$class->pluginIncluder($value, $pluginVars, $partConfig, $mysqli, @$langData);
    $pos = strpos($pageText,$placeholder[$key]);
    if ($pos !== false) {
        $pageText = substr_replace($pageText,$replaceWith,$pos,strlen($placeholder[$key]));
    }
  }
}
/*insertion of plugins-end*/

/*insertion of chunks-text from DB (html, CSS, js...)           [{textchunk}]   do not use any paramater because it is not active   WARNING!!! [{}]can conflict with JSON  */
preg_match_all('/(\[\{)([^\{\[\}\]]+)(\}\])/', $pageText, $matches);
$placeholder=$matches[0];
$matches=$matches[2];
if(isset($matches[0]))
{
  $sql='';
  foreach($matches as $key => $value){
    $value=$mysqli->real_escape_string($value);
    $sql.="(SELECT name, body FROM ".$config->dbprefix."chunks WHERE `name`='$value' AND `active`='1' LIMIT 1)
    UNION";
  } 
  $sql=trim($sql, 'UNION');
  $result=$mysqli->query($sql);
  $resultName=array();
  $resultBody=array();
  while($row=$result->fetch_array(MYSQLI_ASSOC)){
    array_push($resultName, "[{".$row['name']."}]");
    array_push($resultBody, $row['body']);
  }
  $pageText=str_replace($resultName, $resultBody, $pageText);
}  
/*insertion of chunks from DB-end*/

/*insertion of ADDONS-beginning*/
$pageText=$class->addonIncluder($addons, $pageText, $pluginVars, $config->debuggingmode);
/*insertion of ADDONS-end*/

if(@$previewMode){
  $pageText=str_ireplace('<body>', '<div style="position: relative; top: 0px; left: 0px; width: 100%; height: 50px; margin: 0px; padding: 0px;"></div>', $pageText);
  $pageText.='<div style="position: fixed; top: 0px; left: 0px; width: 100%; height: 50px; border-bottom: 1px solid black; background-color: rgba(255,0,0,0.8); text-align: center; color: white; font-size: 2em">'.$_POST['previewpagecaption'].'</div>';
}

//$pageText.=$class->errorLog('0',get_defined_vars());
//$this->errorLog('0',get_defined_vars());
echo $pageText;
//echo '<pre>'.print_r(get_included_files(),true).'</pre><b>Page text:</b><hr style="height: 10px; background-color: black">';
$mysqli->close();
?>