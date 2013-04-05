<?php
class Config{
  //urls
  public $webname='42cms'; //This is the name of the web e.g. MyBestWeb.com
  public $domain='http://www.example.com';   //used to check if requested url (current url) contains subdomain  (if whole site is on subdomain set like this:  public $domain='sub.something.com'
  public $weburl='http://www.example.com/42cms/develop/';  //insert with '/' at the end
  public $membersurl='http://www.example.com/42cms/develop/members/';  //insert with '/' at the end
  public $registerurl='http://www.example.com/42cms/develop/register/';  //insert with '/' at the end
  public $lostpassurl='http://www.example.com/42cms/develop/members/lostpass/';  //insert with '/' at the end
  public $editpageurl='http://www.example.com/42cms/develop/members/edit/';  //insert with '/' at the end
  public $imagesfolder='http://www.example.com/42cms/develop/images/';  //insert with '/' at the end
  public $filesfolder='http://www.example.com/42cms/develop/files/';  //insert with '/' at the end
  public $galleryfolder='http://www.example.com/42cms/develop/gallery/';  //insert with '/' at the end
  public $datafolder='http://www.example.com/42cms/develop/data/';  //insert with '/' at the end
  public $pluginsfolder='http://www.example.com/42cms/develop/includes/plugins/';  //insert with '/' at the end
  public $jquerysource='http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js';    //or use  http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js for latest version
  public $jqueryuisource='https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/jquery-ui.min.js';   //or use http://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js for latest version
  public $jqueryuithemesource='http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/themes/vader/jquery-ui.css'; //check: http://blog.jqueryui.com/2012/11/jquery-ui-1-9-2/    or use http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/vader/jquery-ui.css for latest version
  
  //other main config
  public $debuggingmode=true;  //set true while debugging, otherwise set false
  
  //errors
  public $id404=1; //id of ERROR 404 in pages table (you can use also id of homepage instead of 404 page, but it is not the best practice
  public $idlogout=19; //id of LogOut in pages table
  public $logfolder='log';
  public $newlogfiletime=3;  //1-change every year, 2-change every month (recommended), 3-change every week, 4-change every day, 5-change every hour, 0(or anything else)-keep one file for always
  
  //database
  public $dbserver='localhost';
  public $dbuser='root';
  public $dbpass='';
  public $dbname='42cms-v1-0';
  public $dbprefix='42cms_';  //prefix used inside the database for table name e.g.: "SELECT * FROM ".$config->dbprefix."pages" 
  public $dbnameset='utf8';
  public $dbcollate='utf8_unicode_ci';


  //cookie & session
  public $crypt='$2y$09$';  //use prefix to set crypt method. See:   http://php.net/manual/en/function.crypt.php
  
  public $sessionsalt='';     //20 characters long from the alphabet "./0-9A-Za-z"
  public $cookiesalt='';           //at least 15 characters long salt (any random characters containing digits, lower and uppercase letters and other specia characters)  
  public $cookieprefix='42cms-develop01';  //default cookie prefix is the same as domain
  public $rememberme=31536000;  //how long shoul stay set cookie for remembering password, default is 1 year (31536000 seconds)
  
  //registred users
  public $registration=1;  //alowed values 0(or anything smaller than 1)-disabled registration, 1-alowed registration, 2-registration only on invitations
  public $activsendermail='register@example.com';  //this e-mail address will be used as a sender of activations
  public $recovsendermail='register@example.com';  //this e-mail address will be used as a sender of password recovery links
  
  public $requireverification=1;  //defines if user account requires email verification 0-verification is not required (accoun is active right after registration-NOT RECOMMENDED) 1-require e-mail verification (STRONGLY ROCOMMENDED!!!)
  public $badlogins=3;   //amount of unsuccessful logins attempts before showing captcha 
  public $passstrenght=-100;  //default use about 30, pass strenght provided by class te-edu Password Meter (http://www.phpclasses.org/package/6290-PHP-Check-whether-a-password-is-strong.html)
  public $attemptsBeforeCaptcha='5';  //0-use captcha always, any number n>0 -show captcha after n wrong login attempts, any number n<0 -newer show captcha(!!!not recommended) 
  public $recaptchapublic='';  //public key for reCAPTCHA library  See: http://www.google.com/recaptcha
  public $recaptchaprivate=''; //private key for reCAPTCHA library 
  
  
  
  
  
  //plupload config
  public $maxfilesize='100mb';
  public $maxfilecount='30';
  public $maxchunksize='1mb';
  
  //language
  public $lang='en';
  public $invmailsubject='Invitation to register at my page';
  public $invmailbody='You have been invited to register at my page. <a href="[[invlink]]">Click to register</a> or copy paste following link: <hr><a href="[[invlink]]">[[invlink]]</a><hr> Regards Admin';
  public $invsendermail='register@example.sk';  //this e-mail address will be used as a sender of invitations
  
}
?>