<?php
class ErrorReporting{ //each item should have 4 values: conf(00-do not show or log error, 10-just show, 01-just log, 11-show and log), stringcode-this will be written in the log), description-this is just description for admin/developer,  varlist-array with names of variables which should be logged
  public $data=array(
    '0'      =>array(
                      'conf'=>'11',   //if not debugging recommended 00 or 01
                      'stringcode'=>'NoErrorJustTest',
                      'description'=>'Error code for testing error reporting',
                      'varlist'=>array('userId', 'loggedIn', '_SESSION', 'langId')
                      ),
    '1'      =>array(
                      'conf'=>'11',   //recomended value 01
                      'stringcode'=>'NoTemplate',
                      'description'=>'The template was not found',
                      'varlist'=>array('pageId', 'stringPath', 'result')
                      ),
    '2'      =>array(
                      'conf'=>'11',   //recommended to use 01, because it seems to be !!!HACK ATTEMPT!!!
                      'stringcode'=>'SessionDoNotMatch',
                      'description'=>'IMPORTANT-Session hash do not match(HACKING ATTEMPT?)',
                      'varlist'=>array('pageId', 'stringPath', '_SESSION')
                      ),
    '3'      =>array(
                      'conf'=>'11',   //recommended to use 01, because it seems to be !!!HACK ATTEMPT!!!
                      'stringcode'=>'CookieDoNotMatch',
                      'description'=>'IMPORTANT-Cookie hash do not match(HACKING ATTEMPT?)',
                      'varlist'=>array('pageId', 'stringPath', '_COOKIE')
                      ),
    '4'      =>array(
                      'conf'=>'11',  //recomended value 11 or 01
                      'stringcode'=>'reCaptchaError',
                      'description'=>'reCAPTCA respond with error',
                      'varlist'=>array('_POST')
                      ),
    '5'      =>array(
                      'conf'=>'11',  //recomended value 10 or 00
                      'stringcode'=>'NoCaptcha',
                      'description'=>'There was no captcha entered',
                      'varlist'=>array('_POST')
                      ),
    '6'      =>array(
                      'conf'=>'11',  //recomended value 10 or 00
                      'stringcode'=>'WrongPassOrUsername',
                      'description'=>'Wrong password or username',
                      'varlist'=>array('_POST')
                      ),
    '7'      =>array(
                      'conf'=>'10',  //recomended value 10 or 00
                      'stringcode'=>'InactiveAccount',
                      'description'=>'The account was not activated yet.',
                      'varlist'=>array('_POST')
                      ),
    '8'      =>array(
                      'conf'=>'11',   //recommended to use 01, because it seems to be !!!HACK ATTEMPT!!!
                      'stringcode'=>'AddPageHashError',
                      'description'=>'There is something wrong with the hash in add_page.php',
                      'varlist'=>array('_POST', 'newPage')
                      ),
    '9'      =>array(
                      'conf'=>'11',   //recommended to use 11, because it might be !!!HACK ATTEMPT!!! or invited user just copied incomplete invitation link
                      'stringcode'=>'InvitationHashError',
                      'description'=>'There is something wrong with the hash in register.php',
                      'varlist'=>array('_POST', '_GET', 'newPage')
                      ),
    '10'     =>array(
                      'conf'=>'11',   //recommended to use 11
                      'stringcode'=>'ActivationDbError',
                      'description'=>'Thero was some DB error in activation.php',
                      'varlist'=>array('sql', 'mysqli', 'result')
                      ),
    '11'     =>array(
                      'conf'=>'11',   //recommended to use 11 because it seems to be !!!HACK ATTEMPT!!! or the user just copied incomplete activation link
                      'stringcode'=>'ActivationHashError',
                      'description'=>'There was some DB error in activation.php',
                      'varlist'=>array('_GET', 'result')
                      ),
    '12'     =>array(
                      'conf'=>'11',   //recommended to use 10 
                      'stringcode'=>'ActivationDone',
                      'description'=>'The selected inactive user was not found in DB probably because the account was already activated',
                      'varlist'=>array('_GET', 'result', 'mysqli')
                      ),
    '13'     =>array(
                      'conf'=>'11',   //recommended to use 11, because it seems to be !!!HACK ATTEMPT!!! or the user just copied incomplete password recovery link
                      'stringcode'=>'LostPassLinkError',
                      'description'=>'Something is wrong with password recovery link',
                      'varlist'=>array('result', 'token')
                      ),
    '14'     =>array(
                      'conf'=>'11',   
                      'stringcode'=>'AddImagesMysqlError',
                      'description'=>'Error while inserting images into DB',
                      'varlist'=>array('_POST', 'sql')
                      ),
    '15'     =>array(
                      'conf'=>'11',   
                      'stringcode'=>'WrongPass',
                      'description'=>'Inserted wrong password (on some other than login page)',
                      'varlist'=>array('_POST', '_SESSION')
                      ),
     '16'     =>array(
                      'conf'=>'11',   //recommended to use 11, because it seems to be !!!HACK ATTEMPT!!! from one of the admins (user alowed manipulate permissions)  
                      'stringcode'=>'PermissionEditorManipulation',
                      'description'=>'User manipulated POST while using permissions.php',
                      'varlist'=>array('_POST', 'dbGroups', 'compare', 'notAllowed', '_SESSION')
                      ),
    '404'     =>array(
                      'conf'=>'01',
                      'stringcode'=>'Error 404',
                      'description'=>'Error 404 - The page was not found',
                      'varlist'=>array('_SERVER', '_POST')
                      ),






    ''     =>array(
                      'conf'=>'11',   
                      'stringcode'=>'',
                      'description'=>'',
                      'varlist'=>array('_POST', 'newPage')
                      ),


  );
  
}  //$return.=$this->errorLog('', $GLOBALS);
?>