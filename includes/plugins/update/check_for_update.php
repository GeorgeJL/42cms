<?php
$cookieLifetime=3600; //in seconds - recomended at least 3600
if(isset($_COOKIE['update']))
{
  $return=$_COOKIE['update'];  
}else{
  $return='v'.$config->localversion;
  $latestVersion=json_decode($class->getContent($config->versionserver),1);
  //include("local_version.php");
  //echo $config->localbuild.' - '.$latestVersion['build'];
  if($config->localbuild!=$latestVersion['build'])
  {
    switch($latestVersion['updatePriority'])
    {
      case 3:
        $return.=' - <a href="'.$config->updatepageurl.'" class="newversion crytical" title="'.$lang->cryticalupdatetitle.'">'.$lang->pleaseupdate.' ('.$latestVersion['version'].' - '.$lang->criticalupdate.')</a>';
        break;
      case 2:
        $return.=' - <a href="'.$config->updatepageurl.'" class="newversion important" title="'.$lang->importantupdatetitle.'">'.$lang->pleaseupdate.' ('.$latestVersion['version'].' - '.$lang->importantupdate.')</a>';
        break;
      case 1:
        $return.=' - <a href="'.$config->updatepageurl.'" class="newversion optional" title="'.$lang->optionalupdatetitle.'">'.$lang->pleaseupdate.' ('.$latestVersion['version'].' - '.$lang->optionalupdate.')</a>';
        break;
      case 0:
        break;
    }
  }
  setcookie("update", $return, time()+$cookieLifetime);
}

?>