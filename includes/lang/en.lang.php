<?php                         
class Lang
{
  public $ok='OK';
  public $cancel='Cancel';
  public $yes='Yes';
  public $no='No';      
  
  public $submit='Submit';
  public $username='Username';
  public $password='Password';
  public $passwordagain='Re-type Password';
  public $register='Register';
  public $rememberme='Remember me';
  public $mail='Email';
  public $wronguserormail='You have entered incorrect username or email.';
  public $passdontmatch='The password do not match';
  public $passtooweak='The password is too weak';
  public $tryagain='Try again.';
  public $newpass='Enter your new password:';
  public $newpassagain='Enter your new password again:';
  public $save='Save';
  public $preview='Preview';
  public $lostpass='Lost password';
  
  public $lastpassmailsubject='Password recovery on [[webname]]';
  public $lastpassmailbody='You have requested password recovery on [[webname]]. <a href="[[recovlink]]">Click here to enter new password</a> or copy & paste the following link:<hr><a href="[[recovlink]]">[[recovlink]]</a><hr> Regards Admin';
  
  
  public $chengedpass='Password has been changed successfully. <br />Please'; //za tym bude pridany link na login
  public $passrecoverylink='A link to recover your password has been sent to your email';
  public $invalidlink='Invalid link.';
  public $login='Log In';
  public $notallowed='Access not allowed.';
  public $loggedout='You have been logged out.';
  
  /* add_page.php */
  public $addnewpagebutton='Add new page.';
  public $addnewpage='Click on any item in tree to add subpage for it.';
  public $pagealreadycreated='The page has been already created';
  public $pagesuccessfullycreated='New page was successfully created.';
  public $editnewpage='Edit new page';
  
  /*  edit_page.php  */
  public $pageid='Page ID';
  public $pageurl='Page URL';
  public $pagetitle='Page title';
  public $inmenu='Show in menu for';
  public $menutitle='Title in menu';
  public $membersonly='Only members page';
  public $h1='Main heading';
  public $pagetext='Body of the page';
  public $template='Template';
  public $menuorder='Position in menu';
  public $active='Active';
  public $previewpagecaption='This is just a preview :-)';
  public $showmce='Use TinyMCE editor';
  public $hidemce='Use text editor';
  public $pagesaved='The page was saved';
  public $pagetoedit='Select page you would like to edit';
  public $anotherpagetoedit='Select another page you would like to edit';
  public $pagecreated='The page was created.';
  public $clicktoedit='Click here to edit the page.';
  public $pluploaderror='You browser doesn\'t have Flash, Silverlight, Gears, BrowserPlus or HTML5 support.';
  public $pluploadtoogle='Show/Hide file uploader';
  public $pluploadlogtoogle='Show/Hide file uploader log';
  public $logged='logged users';
  public $nologged='non logged users';
  public $both='all users (recommended)';
  public $non='do not show in menu';
  public $loading='Loading';
  
  /*  invitations.php  */
  public $nopermtoassign='No user permissions to assign';
  public $nogroupstoassign='No user groups to assign';
  public $selectusergroups='Select usergroups for this user';
  public $selectpagespermissions='In addition select pages which this user will be allowed to access';
  public $invsent='The invitation has been send to folloving email address:';
  public $invsent2='The text of the email was as following:';
  
  /*  registration.php  */
  public $disabledregistration='Sorry, but registration is not alowed on this website.';
  public $noinvitation='Sorry, but registration is on invitation only.';
  public $activsent='The activation mail has been sent to the folloving email address:';
  public $activmailsubject='Activate your account at [[webname]]';
  public $activmailbody='You have successfully registered on [[webname]]. <a href="[[activlink]]">Click here to confirm your registration</a> or copy & paste the following link:<hr><a href="[[activlink]]">[[activlink]]</a><hr> Regards Admin';
  
  /*  activation.php  */
  public $activatednow='Your account has been activated successfully';
  
  /*  logout.php  */
  public $logoutok='You have been successfully logged out';
  public $returnhome='You can now return to ';
  public $homepage='home page.';
  
  /* add_images.php */
  public $nogallery='There are no galleries. To add new gallery select option <b>Add gallery</b> from your menu. If you do not have this option in your menu please contact page administrator.';
  public $insertedimages='Inserted %count% new image(s)';
  public $imagename='Name of the image';
  public $imagedescription='Description';
  public $imageauthor='Author';
  public $zerofiles='You must at least upload one file.';
  
  /* add_gallery.php */
  public $gallerycreated='The gallery has been created.'; 
  public $gallerycode='To show this gallery on your page insert following code into your page:';
  public $galleryname='Gallery name:';
  public $gallerydescription='Gallery description';
  
  /* gallery.php */
  public $author='Author';
  public $noimages='There are no images in this gallery';     
  
  /* view_database.php */
  public $dbname='Enter database name';
  public $goback='Go back!';
  public $emptytable='This table is empty';
  
  /* changepass.php */
  public $curpassword='Current password';                      
  public $newpassword='New password';                      
  public $newpasswordagain='New password again';                      
  public $passchanged='Your password has been successfully changed. Please <a href="[{weburl}]members/logout/">log out</a> and log in again.';


/*
  public $='';
  
  public $yes='Yes';
  public $no='No';
  public $cancel='Cancel';
  public $back='Back';
*/  
}
?>