<!DOCTYPE html>
<html>
<title>42cms instalation</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<head>
<?php
$installDBsteps=21; //required for progress bar
$totalSteps=6;      //required to show "Step X of Y"  
?>

<style type="text/css">
  body{background-color: #111; color: #5f0; font-family: Arial, Helvetica, sans-serif}
  
  
  label{display: inline-block; width: 400px;text-align: right; padding: 5px}
  textarea, input{width:500px;}
  textarea{height:100px}
  input[type=radio]{position: relative; margin-left: 0px; width: 20px}       
  #log{position: relative; width: 500px; height: 200px; border: 1px solid #555; overflow: auto}
  .error, .noproblem{font-weight: bold}
  .error{color: red}
  .noproblem{color: green}
  #advancedBox{position: relative; overflow: hidden}
  #advancedTitle{font-size: 1.2em; font-weight: bold}
</style>  
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/jquery-ui.min.js"></script>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.1/themes/trontastic/jquery-ui.css" />
<script>
$(document).ready(function(){
  $('#advancedBox').before('<label for="toggleAdvanced" class="label"></label><button id="toggleAdvanced" type="button">Hide advanced options</button>');
  var hash = location.hash;
  if(hash!='#advanced'){
    $('#advancedBox').hide();
    $('#toggleAdvanced').text('Show advanced options');
  }
  $('#toggleAdvanced').click(function(){
    $('#advancedBox').toggle(); 
    var hash = location.hash;
    if(hash!='#advanced'){
      location.hash = 'advanced';
      $('#toggleAdvanced').text('Hide advanced options');
    }else{
      location.hash = '';
      $('#toggleAdvanced').text('Show advanced options');
    }  
  });
});
</script>
<head>
<body>
<h1>42cms instalation</h1>
<?php
require_once('config_data.php');
$configData=new ConfigData;
$config=$configData->data;
$return='<form method="post">';

if((isset($_POST['step']))AND($_POST['step']==2)){
  $return.='<input type="hidden" name="step" value="3">';
  $step=2;
}else if((isset($_POST['step']))AND($_POST['step']==3)){
  $return.='<input type="hidden" name="step" value="4">';
  $step=3;
}else if((isset($_POST['step']))AND($_POST['step']==4)){
  $step=4;
  $dontShowStep=true;
  $return.='<input type="hidden" name="step" value="4">';
  @$subStep=$_POST['subStep'];
  if($subStep==2){
    $mysqli= new mysqli($_POST['dbserver'], $_POST['dbuser'], $_POST['dbpass']);
    if($mysqli->connect_errno)
    {
      $return.='<h2>There was an error while trying to connect to MySQL server</h2>
      Check entered data and try again
    <input type="hidden" name="subStep" value="2">
    <input type="hidden" name="step" value="4">
    <label for="dbserver">Database server: </label><input type="text" id="dbserver" name="dbserver" value="'.$_POST['dbserver'].'"><br />
    <label for="dbuser">Database username: </label><input type="text" id="dbuser" name="dbuser" value="'.$_POST['dbuser'].'"><br />
    <label for="dbpass">Database password: </label><input type="dbpass" id="dbpass" name="dbpass"><br />
    <label for="dbprefix">Database prefix: </label><input type="dbprefix" id="dbprefix" name="dbprefix"><br />';  
    }else{
      $return.='<h2>Connection to your MySQL server was successfull</h2>
      <input type="hidden" name="subStep" value="3">
      <input type="hidden" name="step" value="4">
      <input type="hidden" id="dbserver" name="dbserver" value="'.$_POST['dbserver'].'">
      <input type="hidden" id="dbuser" name="dbuser" value="'.$_POST['dbuser'].'">
      <input type="hidden" id="dbpass" name="dbpass" value="'.$_POST['dbpass'].'">
      <input type="hidden" id="dbprefix" name="dbprefix" value="'.$_POST['dbprefix'].'">';
    }   
  }else if(($subStep==3)OR(($subStep==4)AND( (empty($_POST['createorcon']))OR(empty($_POST['dbname'])) ) )){
    $return.='<h2>Do you want to create new database or connect to existing one?</h2>';
    if ($subStep==4){$return.='<h2>You must select one of the options and fill up the database name</h2>';}
      
    $return.='<input type="hidden" name="subStep" value="4">
      <input type="hidden" name="step" value="4">
      <input type="hidden" id="dbserver" name="dbserver" value="'.$_POST['dbserver'].'">
      <input type="hidden" id="dbuser" name="dbuser" value="'.$_POST['dbuser'].'">
      <input type="hidden" id="dbpass" name="dbpass" value="'.$_POST['dbpass'].'">
      <input type="hidden" id="dbprefix" name="dbprefix" value="'.$_POST['dbprefix'].'">
      <label for="createorcon">Create new database</label><input type="radio" id="createorcon" name="createorcon" value="create"><br />
      <label for="createorcon">Connect to existing database</label><input type="radio" id="createorcon" name="createorcon" value="connect"><br />
      <label for="dbname">Database name: </label><input type="text" id="dbname" name="dbname"><br />';
  }else if($subStep==4){
    if($_POST['createorcon']=='create')
    {
      $mysqli= new mysqli($_POST['dbserver'], $_POST['dbuser'], $_POST['dbpass']);
      $sql='CREATE DATABASE '.$_POST['dbname'];
      if($mysqli->query($sql))
      {
        $return.='<h2>Database was created successfully</h2>
      <input type="hidden" name="subStep" value="5">
      <input type="hidden" name="step" value="4">
      <input type="hidden" id="dbserver" name="dbserver" value="'.$_POST['dbserver'].'">
      <input type="hidden" id="dbuser" name="dbuser" value="'.$_POST['dbuser'].'">
      <input type="hidden" id="dbpass" name="dbpass" value="'.$_POST['dbpass'].'">
      <input type="hidden" id="dbname" name="dbname" value="'.$_POST['dbname'].'">
      <input type="hidden" id="dbprefix" name="dbprefix" value="'.$_POST['dbprefix'].'">';
      }else{
        $return.='<h2>Database was not created.</h2>
        You should probably create new database using your hosting administration.<br />
      <input type="hidden" name="subStep" value="4">
      <input type="hidden" name="step" value="4">
      <input type="hidden" id="dbserver" name="dbserver" value="'.$_POST['dbserver'].'">
      <input type="hidden" id="dbuser" name="dbuser" value="'.$_POST['dbuser'].'">
      <input type="hidden" id="dbpass" name="dbpass" value="'.$_POST['dbpass'].'">
      <input type="hidden" id="dbprefix" name="dbprefix" value="'.$_POST['dbprefix'].'">
      <label for="createorcon">Try to create new database again</label><input type="radio" id="createorcon" name="createorcon" value="create"><br />
      <label for="createorcon">Connect to existing database</label><input type="radio" id="createorcon" name="createorcon" value="connect"><br />
      <label for="dbname">Database name: </label><input type="text" id="dbname" name="dbname"><br />';
      }
    }else{
      $mysqli= new mysqli($_POST['dbserver'], $_POST['dbuser'], $_POST['dbpass'], $_POST['dbname']);
      if($mysqli->connect_errno)
      {
        $return.='<h2>Connection to <u>'.$_POST['dbname'].'</u> was NOT successfull</h2>
        Check if you have entered correct database name<br>
        <input type="hidden" name="subStep" value="4">
        <input type="hidden" name="step" value="4">
        <input type="hidden" id="dbserver" name="dbserver"Create new database>
        <input type="hidden" id="dbuser" name="dbuser" value="'.$_POST['dbuser'].'">
        <input type="hidden" id="dbpass" name="dbpass" value="'.$_POST['dbpass'].'">
        <input type="hidden" id="dbprefix" name="dbprefix" value="'.$_POST['dbprefix'].'">
        <label for="createorcon">Create new database</label><input type="radio" id="createorcon" name="createorcon" value="create"><br />
        <label for="createorcon">Connect to existing database</label><input type="radio" id="createorcon" name="createorcon" value="connect" checked="checked"><br />
        <label for="dbname">Database name: </label><input type="text" id="dbname" name="dbname" value="'.$_POST['dbname'].'"><br />';
      }else{
        $return.='<h2>Connection to <u>'.$_POST['dbname'].'</u> was successfull</h2>
      <input type="hidden" name="subStep" value="5">
      <input type="hidden" name="step" value="4">
      <input type="hidden" id="dbserver" name="dbserver" value="'.$_POST['dbserver'].'">
      <input type="hidden" id="dbuser" name="dbuser" value="'.$_POST['dbuser'].'">
      <input type="hidden" id="dbpass" name="dbpass" value="'.$_POST['dbpass'].'">
      <input type="hidden" id="dbname" name="dbname" value="'.$_POST['dbname'].'">
      <input type="hidden" id="dbprefix" name="dbprefix" value="'.$_POST['dbprefix'].'">';
      }
    }
  }else if($subStep==5){
    $return.="<script>
      $(document).ready(function(){
        $('#submit').attr('disabled', 'disabled');
        var i=0;
        function loader(i){
          $('#temp').load('db-ajax-loader.php?i='+i+'&server=".$_POST['dbserver']."&username=".$_POST['dbuser']."&password=".$_POST['dbpass']."&dbname=".$_POST['dbname']."&dbprefix=".urlencode($_POST['dbprefix'])."&weburl=".urlencode($_POST['weburl'])."', function(){
            var data = $('#temp').html();
            $('#temp').append(' '+Math.round(((i)*".(100/$installDBsteps)."))+' %');
            if(data=='done')
            {
              var done=true;
              $('#submit').removeAttr('disabled');
              die;
            }else  
              $('#log').append(data);
          $('#log').scrollTop('10000');
          $('#progressbar').progressbar({value: (i+1)*".(100/$installDBsteps)."});
          loader(i+1);    
          });
        }
      loader(i);
      });
      </script>
      <div id=\"log\"></div>
      <br />
      <div id=\"temp\"></div>
      <div id=\"progressbar\"></div>
      <input type=\"hidden\" name=\"step\" value=\"5\">
      <input type=\"hidden\" id=\"dbserver\" name=\"dbserver\" value=\"".$_POST['dbserver']."\">
      <input type=\"hidden\" id=\"dbuser\" name=\"dbuser\" value=\"".$_POST['dbuser']."\">
      <input type=\"hidden\" id=\"dbpass\" name=\"dbpass\" value=\"".$_POST['dbpass']."\">
      <input type=\"hidden\" id=\"dbname\" name=\"dbname\" value=\"".$_POST['dbname']."\">
      <input type=\"hidden\" id=\"dbprefix\" name=\"dbprefix\" value=\"".$_POST['dbprefix']."\">";
  }else{
    $return.='<input type="hidden" name="subStep" value="2">';
    $dontShowStep=false;
  } 
}else if((isset($_POST['step']))AND($_POST['step']==5)){
  $return.='<input type="hidden" name="step" value="6">
            <input type="hidden" id="dbname" name="dbname" value="'.$_POST['dbname'].'">';

  $step=5;
}else if((isset($_POST['step']))AND($_POST['step']==6)){
  $return.='<input type="hidden" name="step" value="7">';
  $i=0;
  $write='<?php'.PHP_EOL.'class Config{'.PHP_EOL;
  foreach($_POST as $key=>$value)
  {
    if($key!='step')
    {
      $value=str_replace('"', '\"', $value);
      $write.='public $'.$key.'="'.$value.'";'.PHP_EOL;
      $i++;
    }  
  }
  $fh=fopen('../includes/config.php', 'w');
  fwrite($fh, $write.'}'.PHP_EOL.'?'.'>');
  $step=6;
  $finished=true;
  $return.='<h2>Finished</h2>
  You should be up and running.
  <br /><br />
  <h3>Now just follow these 3 simple but <u>IMPORTANT</u> steps:</h3>
  <b>1.</b>Delete "install" folder.<br /><br />
  <b>2.</b>Login to <a href="'.$_POST['membersurl'].'" target="_blank">Members area</a><br /> 
	&nbsp;&nbsp;&nbsp; Your default login details are:<br />
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Username:	admin<br />
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Password:	42cmsadmin<br /><br />
  <b>3.</b>Change your password.


  
  ';
  
}else{
  $return.='<input type="hidden" name="step" value="2">';
  $step=1;
}
$advanced='';
$advancedCount=0;
$notAdvancedCount=0;
foreach($config as $key=>$value)
{
  $subReturn='';
  if($value['step']==$step)
  {
    $sessionSalt=substr(str_shuffle('//////......'),0,6).base_convert(md5(mt_rand(999, 99999)), '10', '35');
    $sessionSalt=str_shuffle($sessionSalt);
    $sessionSalt.=strtoupper(base_convert(md5(mt_rand(999, 99999)), '10', '35'));
    $sessionSalt=str_shuffle($sessionSalt);
    $sessionSalt=substr($sessionSalt, 0, mt_rand(22,24));
    
    $cookieSalt=substr(str_shuffle('///***---+++...,,,:::((()))___'),0,10).base_convert(md5(mt_rand(999, 99999)), '10', '35');
    $cookieSalt=str_shuffle($cookieSalt);
    $cookieSalt.=strtoupper(base_convert(md5(mt_rand(999, 99999)), '10', '35'));
    $cookieSalt=str_shuffle($cookieSalt);
    $cookieSalt=substr($cookieSalt, 0, mt_rand(22,24));
    
    if (@$_SERVER["HTTPS"] == "on") {$weburl .= "https://";}else{$weburl = 'http://';}
    $weburl.=$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    $pos = strrpos($weburl, 'install/');
    if($pos !== false)
      $weburl = substr_replace($weburl, '', $pos, 8);
    @$valueData=htmlentities($value['prefix']).htmlentities($value['value']).htmlentities($value['suffix']);
    $from=array('--domain--', '--weburl--', '--sessionsalt--', '--cookiesalt--');
    @$to=array($_SERVER["SERVER_NAME"], $weburl, $sessionSalt, $cookieSalt);
    $valueData=str_replace($from, $to, $valueData);
  }  
  if((!empty($value['step']))AND($value['step']<$step))
  {  
    $valueData=htmlentities($_POST[$value['name']]);
    //$valueData=str_replace('&quot;', '\&quot;', $_POST[$value['name']]);
    //$valueData=str_replace('"', '\"', $_POST[$value['name']]);
    $subReturn.='<input type="hidden" name="'.htmlentities($value['name']).'" value="'.$valueData.'">';
  }
  
  if(($value['step']==$step)AND(@!$dontShowStep))
  {
    $subReturn.='<label for="id-'.$key.'">'.$value['label'].':</label>';
    if($value['type']=='text')
      $subReturn.='<input id="id-'.$key.'" type="text" value="'.$valueData.'" name="'.htmlentities($value['name']).'" autocomplete="off"><br />'; 
    else if($value['type']=='password')
      $subReturn.='<input id="id-'.$key.'" type="password" value="'.$valueData.'" name="'.htmlentities($value['name']).'" autocomplete="off"><br />'; 
    else if($value['type']=='textarea')
      $subReturn.='<textarea id="id-'.$key.'" name="'.htmlentities($value['name']).'">'.$valueData.'</textarea><br />'; 
    else if($value['type']=='onoff'){
      if($value['value'])
        $subReturn.='<input id="id-'.$key.'" type="radio" value="true" name="'.htmlentities($value['name']).'" checked="checked">On<br /><label for="id-'.$key.'-2"></label><input id="id-'.$key.'-2" type="radio" value="false" name="'.htmlentities($value['name']).'">Off<br />';
      else
        $subReturn.='<input id="id-'.$key.'" type="radio" value="true" name="'.htmlentities($value['name']).'">On<br /><label for="id-'.$key.'-2"></label><input id="id-'.$key.'-2" type="radio" value="false" name="'.htmlentities($value['name']).'" checked="checked">Off<br />';
    }else if($value['type']=='yesno'){
      if($value['value'])
        $subReturn.='<input id="id-'.$key.'" type="radio" value="true" name="'.htmlentities($value['name']).'" checked="checked">Yes<br /><label for="id-'.$key.'-2"></label><input id="id-'.$key.'-2" type="radio" value="false" name="'.htmlentities($value['name']).'">No<br />'; 
      else
        $subReturn.='<input id="id-'.$key.'" type="radio" value="true" name="'.htmlentities($value['name']).'">Yes<br /><label for="id-'.$key.'-2"></label><input id="id-'.$key.'-2" type="radio" value="false" name="'.htmlentities($value['name']).'" checked="checked">No<br />'; 
    }else if($value['type']=='number'){
      $min='';
      $max='';
      if(!empty($value['min']))
        $min='min="'.$value['min'].'"';
      if(!empty($value['max']))
        $max='max="'.$value['max'].'"';
      $subReturn.='<input id="id-'.$key.'" type="number" value="'.$valueData.'" name="'.htmlentities($value['name']).'" '.$min.' '.$max.'><br />'; 
    }else if($value['type']=='select'){
      $formData=json_decode($value['formdata'],1);
      $subReturn.='<select id="id-'.$key.'" name="'.htmlentities($value['name']).'">';
      foreach($formData as $subKey=>$subValue)
      {
        if($value['value']==$subKey)
          $subReturn.='<option value="'.$subKey.'" selected="selected">'.$subValue.'</option>';
        else
          $subReturn.='<option value="'.$subKey.'">'.$subValue.'</option>';
      }
      $subReturn.='</select><br />';
    }
  }
  if(($value['advanced'])AND($value['step']==$step))
  {
    $advanced.=$subReturn;
    if((!empty($subReturn))AND($value['step']==$step))$advancedCount++;
  }else{
    $return.=$subReturn;
    if((!empty($subReturn))AND($value['step']==$step))$notAdvancedCount++;
  }    
}

if(($notAdvancedCount==0)AND($step!=4)AND($step!=$totalSteps))
  $return.='<h2>Continue</h2>';

if($advancedCount>0)                                                                                                                                                                                                                      
  $return.='<br><div id="advancedBox"><label for="advH3"></label><span id="advancedTitle">Advanced options</span><br><br>'.$advanced.'</div>';

if(@!$finished)
  $return.='<br><label for="submit"></label><input type="submit" id="submit" value="Continue">';

if(empty($subStep))
  $subStep='';
else
  $subStep='.'.$subStep;
    
echo 'Step: '.$step.$subStep.' of '.$totalSteps.'<br>'.$return;
?>
</body>
</html>