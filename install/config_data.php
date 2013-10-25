<?php
class ConfigData{
  public $data=array(
                    array(
                          'name'=>'webname',   
                          'label'=>'Name of your web (e.g. MyBestWeb or Example.com)',
                          'value'=>'42cms',
                          'type'=>'text',
                          'description'=>'This is the name of the web e.g. MyBestWeb',
                          'step'=>'1',
                          'advanced'=>false
                         ),
                    array(
                          'name'=>'domain', 
                          'label'=>'Your domain (without http://)',
                          'value'=>'--domain--',
                          'type'=>'text',
                          'description'=>'used to check if requested url (current url) contains subdomain  (if whole site is on subdomain set like this:  \'http://www.example.com\'',
                          'step'=>'1',
                          'advanced'=>true
                         ),
                    array(
                          'name'=>'weburl', 
                          'label'=>'Complete URL of your web',
                          'value'=>'--weburl--',
                          'type'=>'text',
                          'description'=>'',
                          'step'=>'2',
                          'advanced'=>true
                         ),
                    array(
                          'name'=>'membersurl', 
                          'label'=>'URL of members area',
                          'value'=>'members',
                          'type'=>'text',
                          'prefix'=>'--weburl--',
                          'suffix'=>'/',
                          'description'=>'',
                          'step'=>'3',
                          'advanced'=>true
                         ),
                    array(
                          'name'=>'registerurl', 
                          'label'=>'Registration URL',
                          'value'=>'register',
                          'type'=>'text',
                          'prefix'=>'--weburl--',
                          'suffix'=>'/',
                          'description'=>'',
                          'step'=>'3',
                          'advanced'=>true
                         ),
                    array(
                          'name'=>'lostpassurl', 
                          'label'=>'Forgotten password recovery link',
                          'value'=>'members/lostpass',
                          'type'=>'text',
                          'prefix'=>'--weburl--',
                          'suffix'=>'/',
                          'description'=>'',
                          'step'=>'3',
                          'advanced'=>true
                         ),
                    array(
                          'name'=>'editpageurl', 
                          'label'=>'URL to your page editor',
                          'value'=>'members/edit',
                          'type'=>'text',
                          'prefix'=>'--weburl--',
                          'suffix'=>'/',
                          'description'=>'',
                          'step'=>'3',
                          'advanced'=>true
                         ),
                    array(
                          'name'=>'updatepageurl', 
                          'label'=>'URL to your update page',
                          'value'=>'members/update',
                          'type'=>'text',
                          'prefix'=>'--weburl--',
                          'suffix'=>'/',
                          'description'=>'',
                          'step'=>'3',
                          'advanced'=>true
                         ),
                    array(
                          'name'=>'imagesfolder', 
                          'label'=>'Folder where will be stored images',
                          'value'=>'images',
                          'type'=>'text',
                          'prefix'=>'--weburl--',
                          'suffix'=>'/',
                          'description'=>'',
                          'step'=>'3',
                          'advanced'=>true
                         ),
                    array(
                          'name'=>'filesfolder', 
                          'label'=>'Folder where will be stored other files (except images)',
                          'value'=>'files',
                          'type'=>'text',
                          'prefix'=>'--weburl--',
                          'suffix'=>'/',
                          'description'=>'',
                          'step'=>'3',
                          'advanced'=>true
                         ),
                    array(
                          'name'=>'galleryfolder', 
                          'label'=>'Folder where will be stored gallery images',
                          'value'=>'gallery',
                          'type'=>'text',
                          'prefix'=>'--weburl--',
                          'suffix'=>'/',
                          'description'=>'',
                          'step'=>'3',
                          'advanced'=>true
                         ),
                    array(
                          'name'=>'datafolder', 
                          'label'=>'Other data folder (often used by plugins)',
                          'value'=>'data',
                          'type'=>'text',
                          'prefix'=>'--weburl--',
                          'suffix'=>'/',
                          'description'=>'',
                          'step'=>'3',
                          'advanced'=>true
                         ),
                    array(
                          'name'=>'pluginsfolder', 
                          'label'=>'Plugins folder',
                          'value'=>'includes/plugins',
                          'type'=>'text',
                          'autoconfig'=>1,
                          'prefix'=>'--weburl--',
                          'suffix'=>'/',
                          'description'=>'',
                          'step'=>'5',
                          'advanced'=>true
                         ),
                    array(
                          'name'=>'jquerysource', 
                          'label'=>'Source of jQuery file',
                          'value'=>'http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js',
                          'type'=>'text',
                          'description'=>'',
                          'step'=>'3',
                          'advanced'=>true
                         ),
                    array(
                          'name'=>'jqueryuisource', 
                          'label'=>'Source of jQuery UI file',
                          'value'=>'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/jquery-ui.min.js',
                          'type'=>'text',
                          'description'=>'',
                          'step'=>'3',
                          'advanced'=>true
                         ),
                    array(
                          'name'=>'jquerythemesource', 
                          'label'=>'Source of jQuery UI theme file',
                          'value'=>'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/themes/vader/jquery-ui.css',
                          'type'=>'text',
                          'description'=>'check: http://blog.jqueryui.com/2012/11/jquery-ui-1-9-2/',
                          'step'=>'3',
                          'advanced'=>true
                         ),
                    array(
                          'name'=>'versionserver', 
                          'label'=>'URL used for automatic version check',
                          'value'=>'http://update.42cms.com/current-version/',
                          'type'=>'text',
                          'description'=>'Do not change!',
                          'step'=>'3',
                          'advanced'=>true
                         ),
                    array(
                          'name'=>'updateserver', 
                          'label'=>'URL used for automatic updates',
                          'value'=>'http://update.42cms.com/data/',
                          'type'=>'text',
                          'description'=>'Do not change!',
                          'step'=>'3',
                          'advanced'=>true
                         ),
                    array(
                          'name'=>'debuggingmode', 
                          'label'=>'Debugging mode',
                          'value'=>true,
                          'type'=>'onoff',
                          'description'=>'set true while debugging, otherwise set false',
                          'step'=>'5',
                          'advanced'=>true
                         ),
                    array(
                          'name'=>'id404', 
                          'label'=>'Page id (in database) of ERROR 404 page',
                          'value'=>'1',
                          'type'=>'number',
                          'min'=>'1',
                          'description'=>'id of ERROR 404 in pages table (you can use also id of homepage instead of 404 page, but it is not the best practice',
                          'step'=>'5',
                          'advanced'=>true
                         ),
                    array(
                          'name'=>'idlogout', 
                          'label'=>'Page id (in database) of logout page',
                          'value'=>'19',
                          'type'=>'number',
                          'min'=>'1',
                          'description'=>'id of LogOut in pages table',
                          'step'=>'5',
                          'advanced'=>true
                         ),
                    array(
                          'name'=>'idupdate', 
                          'label'=>'Page id (in database) of update page',
                          'value'=>'3',
                          'type'=>'number',
                          'min'=>'1',
                          'description'=>'id of update in pages table',
                          'step'=>'5',
                          'advanced'=>true
                         ),
                    array(
                          'name'=>'logfolder', 
                          'label'=>'Log files folder',
                          'value'=>'log',
                          'type'=>'text',
                          'description'=>'',
                          'step'=>'5',
                          'advanced'=>true
                         ),
                    array(
                          'name'=>'newlogfiletime', 
                          'label'=>'How often could be changed log file',
                          'value'=>'3',
                          'type'=>'select',
                          'formdata'=>'{"0": "Keep one file for always (not recommended)","1": "New file every year","2": "New file every month","3": "New file every week (recommended)","4": "New file every day","5": "Add new file every hour"}',
                          'description'=>'1-change every year, 2-change every month (recommended), 3-change every week, 4-change every day, 5-change every hour, 0(or anything else)-keep one file for always',
                          'step'=>'5',
                          'advanced'=>true
                         ),
                    array(
                          'name'=>'dbserver', 
                          'label'=>'Database server',
                          'value'=>'--domain--',
                          'type'=>'text',
                          'description'=>'',
                          'step'=>'4',
                          'advanced'=>false
                         ),
                    array(
                          'name'=>'dbuser', 
                          'label'=>'Database username',
                          'value'=>'',
                          'type'=>'text',
                          'description'=>'',
                          'step'=>'4',
                          'advanced'=>false
                         ),
                    array(
                          'name'=>'dbpass', 
                          'label'=>'Database password',
                          'value'=>'',
                          'type'=>'password',
                          'description'=>'',
                          'step'=>'4',
                          'advanced'=>false
                         ),
                    array(
                          'name'=>'dbname', 
                          'label'=>'Database name',
                          'value'=>'',
                          'type'=>'text',
                          'description'=>'',
                          'step'=>'0',
                          'advanced'=>false
                         ),
                    array(
                          'name'=>'dbprefix', 
                          'label'=>'Database prefix',
                          'value'=>'42cms_',
                          'type'=>'text',
                          'description'=>'//prefix used inside the database for table name e.g.: "SELECT * FROM ".$config->dbprefix."pages" ',
                          'step'=>'4',
                          'advanced'=>true
                         ),
                    array(
                          'name'=>'dbnameset', 
                          'label'=>'Database nameset',
                          'value'=>'utf8',
                          'type'=>'text',
                          'autoconfig'=>1,
                          'description'=>'',
                          'step'=>'5',
                          'advanced'=>true
                         ),
                    array(
                          'name'=>'dbcollate', 
                          'label'=>'Database collation',
                          'value'=>'utf_unicode_ci',
                          'type'=>'text',
                          'autoconfig'=>1,
                          'description'=>'',
                          'step'=>'5',
                          'advanced'=>true
                         ),
                    array(
                          'name'=>'crypt', 
                          'label'=>'Crypting prefix',
                          'value'=>'$2y$09$',
                          'type'=>'text',
                          'description'=>'//use prefix to set crypt method. See:   http://php.net/manual/en/function.crypt.php',
                          'step'=>'5',
                          'advanced'=>true
                         ),
                    array(
                          'name'=>'sessionsalt', 
                          'label'=>'Session salt',
                          'value'=>'--sessionsalt--',
                          'type'=>'text',
                          'description'=>'//20 characters long from the alphabet "./0-9A-Za-z"',
                          'step'=>'5',
                          'advanced'=>true
                         ),
                    array(
                          'name'=>'cookiesalt', 
                          'label'=>'Cookie salt',
                          'value'=>'--cookiesalt--',
                          'type'=>'text',
                          'description'=>'//at least 15 characters long salt (any random characters containing digits, lower and uppercase letters and other special characters)',
                          'step'=>'5',
                          'advanced'=>true
                         ),
                    array(
                          'name'=>'cookieprefix', 
                          'label'=>'Cookie prefix',
                          'value'=>'42cms-',
                          'type'=>'text',
                          'description'=>'default cookie prefix is the same as domain',
                          'step'=>'5',
                          'advanced'=>true
                         ),
                    array(
                          'name'=>'rememberme', 
                          'label'=>'Cookie life time',
                          'value'=>'31536000',
                          'type'=>'number',
                          'min'=>'1',
                          'description'=>'how long should stay cookie set for remembering password, default is 1 year (31536000 seconds)',
                          'step'=>'5',
                          'advanced'=>true
                         ),
                    array(
                          'name'=>'registration', 
                          'label'=>'Registration types',
                          'value'=>'1',
                          'type'=>'select',
                          'formdata'=>'{"0": "Registrations are disabled","1": "Registrations are allowed","2": "Registration is allowed only on invitation"}',
                          'description'=>'alowed values 0(or anything smaller than 1)-disabled registration, 1-alowed registration, 2-registration only on invitations',
                          'step'=>'5',
                          'advanced'=>false
                         ),
                    array(
                          'name'=>'activsendermail', 
                          'label'=>'Activation sender mail',
                          'value'=>'register@example.com',
                          'type'=>'text',
                          'description'=>'this e-mail address will be used as a sender of activations',
                          'step'=>'5',
                          'advanced'=>false
                         ),
                    array(
                          'name'=>'recovsendermail', 
                          'label'=>'Password recovery sender mail',
                          'value'=>'register@example.com',
                          'type'=>'text',
                          'description'=>'this e-mail address will be used as a sender of password recovery links',
                          'step'=>'5',
                          'advanced'=>false
                         ),
                    array(
                          'name'=>'requireverification', 
                          'label'=>'User account verification requirement',
                          'value'=>true,
                          'type'=>'yesno',
                          'description'=>'defines if user account requires email verification 0-verification is not required (accoun is active right after registration-NOT RECOMMENDED) 1-require e-mail verification (STRONGLY ROCOMMENDED!!!)',
                          'step'=>'5',
                          'advanced'=>true
                         ),
                    array(
                          'name'=>'passstrength', 
                          'label'=>'Password strenght',
                          'value'=>'-100',
                          'type'=>'number',
                          'min'=>'-100',
                          'max'=>'9999', 
                          'description'=>'default use about 30, pass strenght provided by class te-edu Password Meter (http://www.phpclasses.org/package/6290-PHP-Check-whether-a-password-is-strong.html)',
                          'step'=>'5',
                          'advanced'=>true
                         ),
                    array(
                          'name'=>'attemptsBeforeCaptcha', 
                          'label'=>'False logins before captcha',
                          'value'=>'5',
                          'type'=>'number',
                          'min'=>'-1',
                          'max'=>'255',
                          'description'=>'0-use captcha always, any number n>0 -show captcha after n wrong login attempts, any number n<0 -newer show captcha(!!!not recommended)',
                          'step'=>'5',
                          'advanced'=>true
                         ),
                    array(
                          'name'=>'recaptchapublic', 
                          'label'=>'Public key for <a href="http://www.google.com/recaptcha" target="_blank">reCAPTCHA library</a>',
                          'value'=>'',
                          'type'=>'text',
                          'description'=>'public key for reCAPTCHA library  See: http://www.google.com/recaptcha',
                          'step'=>'5',
                          'advanced'=>false
                         ),
                    array(
                          'name'=>'recaptchaprivate', 
                          'label'=>'Private key for <a href="http://www.google.com/recaptcha" target="_blank">reCAPTCHA library</a>',
                          'value'=>'',
                          'type'=>'text',
                          'description'=>'private key for reCAPTCHA library  See: http://www.google.com/recaptcha',
                          'step'=>'5',
                          'advanced'=>false
                         ),
                    array(
                          'name'=>'maxfilesize', 
                          'label'=>'Maximum uploaded file size',
                          'value'=>'100',
                          'type'=>'text',
                          'suffix'=>'mb',
                          'description'=>'',
                          'step'=>'5',
                          'advanced'=>true
                         ),
                    array(
                          'name'=>'maxfilecount', 
                          'label'=>'Maximum uploaded files count per one upload',
                          'value'=>'30',
                          'type'=>'number',
                          'min'=>'0',
                          'max'=>'255',
                          'description'=>'',
                          'step'=>'5',
                          'advanced'=>true
                         ),
                    array(
                          'name'=>'maxchunksize', 
                          'label'=>'Meximum chunk size during upload',
                          'value'=>'1mb',
                          'type'=>'text',
                          'description'=>'',
                          'step'=>'5',
                          'advanced'=>true
                         ),
                    array(
                          'name'=>'lang', 
                          'label'=>'Language',
                          'value'=>'en',
                          'type'=>'select',
                          'formdata'=>'{"en": "English"}',
                          'description'=>'',
                          'step'=>'5',
                          'advanced'=>false
                         ),
                    array(
                          'name'=>'invmailsubject', 
                          'label'=>'Invitation mail subject',
                          'value'=>'Invitation to register at my page',
                          'type'=>'text',
                          'description'=>'',
                          'step'=>'5',
                          'advanced'=>false
                         ),
                    array(
                          'name'=>'invmailbody', 
                          'label'=>'Invitation mail body',
                          'value'=>'You have been invited to register at my page. <a href="[[invlink]]">Click to register</a> or copy paste following link: <hr><a href="[[invlink]]">[[invlink]]</a><hr> Regards Admin',
                          'type'=>'textarea',
                          'description'=>'',
                          'step'=>'5',
                          'advanced'=>false
                         ),
                    array(
                          'name'=>'invsendermail', 
                          'label'=>'Invitations sender mail',
                          'value'=>'register@example.com',
                          'type'=>'text',
                          'description'=>'this e-mail address will be used as a sender of invitations',
                          'step'=>'5',
                          'advanced'=>false
                         ),
                    array(
                          'name'=>'installedversion', 
                          'label'=>'Originally installed version of 42cms',
                          'value'=>'1.1.1',
                          'type'=>'text',
                          'description'=>'',
                          'step'=>'5',
                          'advanced'=>true
                         ),
                    array(
                          'name'=>'installedbuild', 
                          'label'=>'Originally installed build of 42cms',
                          'value'=>'3',
                          'type'=>'text',
                          'description'=>'',
                          'step'=>'5',
                          'advanced'=>true
                         ),
                    array(
                          'name'=>'localversion', 
                          'label'=>'Currently installed version of 42cms',
                          'value'=>'1.1.1',
                          'type'=>'text',
                          'description'=>'',
                          'step'=>'5',
                          'advanced'=>true
                         ),
                    array(
                          'name'=>'localbuild', 
                          'label'=>'Currently installed build of 42cms',
                          'value'=>'3',
                          'type'=>'text',
                          'description'=>'',
                          'step'=>'5',
                          'advanced'=>true
                         )
                         
                    /*
                    array(
                          'name'=>'', 
                          'label'=>'',
                          'value'=>'',
                          'type'=>'select',
                          'formdata'=>'{"": "","": ""}',
                          'description'=>'',
                          'step'=>'',
                          'advanced'=>true
                         ),
                         
                         //'autoconfig'=>1,
                    */
                    );
}
?>