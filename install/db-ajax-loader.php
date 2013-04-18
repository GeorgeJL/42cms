<?php
$mysqli= new mysqli($_GET['server'], $_GET['username'], $_GET['password'], $_GET['dbname']);
$dbprefix=urldecode($_GET['dbprefix']);
$weburl=urldecode($_GET['weburl']);
require_once('db-data.php');

if(empty($sql[$_GET['i']]))
  echo 'done';
else{
  if($mysqli->query($sql[$_GET['i']]['data']))
    echo '<div class="noproblem">'.$_GET['i'].' OK: '.$sql[$_GET['i']]['desc'].'</div>'; 
  else
    echo '<div class="error">'.$_GET['i'].' ERROR: '.$sql[$_GET['i']]['desc'].'</div>';
}
usleep(100000);
//sleep(1);
//echo 'done';
?>