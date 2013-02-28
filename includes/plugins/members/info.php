<?php
//session_start();
$return.='<hr /><pre>SERVER: <br />'.print_r($_SERVER, true).'</pre>';
$return.='<hr /><pre>PLUGIN config: <br />'.print_r($config, true).'</pre>';
$return.='<hr /><pre>PLUGIN Vars: <br />'.print_r($pluginVars, true).'</pre>';
$return.='<hr /><pre>_POST: <br />'.print_r($_POST, true).'</pre>';
$return.='<hr /><pre>Session:<br />'.print_r($_SESSION, true).'</pre>';
$return.='<hr /><pre>Cookie:<br />'.print_r($_COOKIE, true).'</pre>';
//phpinfo();

?>