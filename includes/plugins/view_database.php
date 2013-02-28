<?php

//   SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA LIKE 'your_database';
//$sql="SHOW TABLES WHERE `Tables_in_42cms-aleksince01` LIKE '42cms_users' ";
//$dbname='42cms-aleksince01';
$dbname='';

if(empty($dbname)AND(empty($_POST['dbname'])))
{
  $return.='<form method="post"><label for="dbname">'.$lang->dbname.':</label><input type="text" name="dbname" id="dbname"><br /><label for="submit"></label><input type="submit" name="submit" id="submit" value="'.$lang->submit.'">';  
}else{
  $dbname=$_POST['dbname'];
  if(!empty($_POST['table']))
  {
    $repeatHeadEvery=10;
    //$return.='<hr /><pre>'.print_r($_POST, true).'</pre>';
    $return.='<h2>'.$_POST['table'].'</h2>';
    $return.='<form method="post"><input type="hidden" name="dbname" id="dbname" value="'.$dbname.'"><input type="submit" name="submit" id="submit" value="'.$lang->goback.'"></form><br />';
  
    if(!empty($_POST['order']))
      $sql="SELECT * FROM ".$mysqli->real_escape_string($config->dbprefix.$_POST['table'])." ORDER BY ".$mysqli->real_escape_string($_POST['order']);
    else
      $sql="SELECT * FROM ".$mysqli->real_escape_string($config->dbprefix.$_POST['table']);
    $result=$mysqli->query($sql);
    $return.='<form method="post"><input type="hidden" name="table" value="'.$_POST['table'].'"><input type="hidden" name="dbname" value="'.$dbname.'">';
    $i=1;
    while($row=$result->fetch_assoc())
    {
      //$return.='<pre>'.print_r($row, true).'</pre><br />';
      if( ($i!=1) AND (($i-1)%$repeatHeadEvery==0) )
        $data.='<tr class="tbl-head"><td>#</td>'.$head.'<tr>';
      
      $data.='<tr><td>'.$i.'</td>';
      foreach($row as $key=>$value)
      {
        if($i==1)   
          $head.='<td>'.$key.' <button type="submit" name="order" value="'.$key.' ASC" class="ord-button">&uarr;</button><button type="submit" name="order" value="'.$key.' DESC"  class="ord-button">&darr;</button></td>';
        $stripped=strip_tags($value);
        if(strlen($stripped)>200)
          $data.='<td>'.substr($stripped,0,200).'...</td>';
        else
          $data.='<td>'.$stripped.'</td>';        
      }
      $data.='<tr>';
      $i++;
    }
    if($i>1)
      $return.='<table><tr class="tbl-head"><td>#</td>'.$head.'</tr>'.$data.'</form></table>';  
    else
      $return.='<br />'.$lang->emptytable;
    
  }else{
    $sql="SHOW TABLES WHERE `Tables_in_".$dbname."`";
    $result=$mysqli->query($sql);
    $return='<form method="post">';
    while($row=$result->fetch_array())
    {
      //$return.='<pre>'.print_r($row, true).'</pre>';
      $return.='<input type="submit" name="table" value="'.substr($row[0], strlen($config->dbprefix)).'"><br />';
    }
    $return.='<input type="hidden" name="dbname" value="'.$dbname.'"></form>';
  }
}


?>