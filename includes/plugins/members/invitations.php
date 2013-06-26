<?php
if($_POST['step']=='2'){
  $maxRank=max(explode(',',$_SESSION['permissions'][$config->pageid]));
  if(!empty($_POST['addgroups']))
  {
    $sql='SELECT id FROM '.$config->dbprefix.'usergroups WHERE rank<="'.$maxRank.'"';
    $result=$mysqli->query($sql);
    while($row=$result->fetch_array())
      $dbGroups[]=$row[0];
    $compare=array_diff($_POST['addgroups'], $dbGroups);
    if(empty($compare))
    {
      $error=false;
      $usergroups=json_encode($_POST['addgroups']);
    }  
    else{
      $return.=$this->errorLog('16', $GLOBALS);
      $error=true;
    }  
  }else{
    $usergroups='["100"]';
  }  
  if(!empty($_POST['addpage'])AND!$error)
  {
    $i=0;
    $sesPermissions=array_keys($_SESSION['permissions']);
    $permissions=array();
    foreach($_POST['addpage'] as $key => $value)
    {
      $rank=$_POST['parameters'][$key];
      if(isset($_SESSION['permissions'][$_POST['addpage'][$key]])AND($rank>$_SESSION['permissions'][$_POST['addpage'][$key]]))
      {
        $rank=$_SESSION['permissions'][$_POST['addpage'][$key]];
      } 
      if(in_array($value, $sesPermissions))
      {
        $permissions[$value]=$rank;
      }else{
        $notAllowed[]=$value;
      }  
      $i++;    
    }
    if(!empty($notAllowed))
    {
      $notAllowed="id='".implode($notAllowed,"' OR id='")."' ";
      $subSql="SELECT id FROM ".$config->dbprefix."pages WHERE (".$notAllowed.") AND membersonly='Yes'";
      $subResult=$mysqli->query($subSql);
      if($subResult->num_rows>0)
      {
        $return.=$this->errorLog('16', $GLOBALS);
        $error=true;
      }  
    }
    if(!$error)
      $permissions=json_encode($permissions);
  }
  if(!$error)
  {
    $invitationMail=$mysqli->real_escape_string($_POST['email']);
    $salt=$mysqli->real_escape_string($this->salt());    
    $sql="INSERT INTO ".$config->dbprefix."invitations (mail, groups, pages, salt, addedby, addedtime, status) VALUES ('".$invitationMail."', '".$usergroups."', '".$permissions."', '".$salt."', '".$_SESSION['userid']."', now(), 'Active')";
    $result=$mysqli->query($sql);
    $hash=crypt(($mysqli->insert_id.'@@@'.$invitationMail), $config->crypt.$salt);
    $link=$config->registerurl.'?m='.urlencode($invitationMail).'&id='.$mysqli->insert_id.'&h='.urlencode($hash);
    $mailBody="<h3>".$lang->invsent."</h3><a href='mailto:".$_POST['email']."'>".$_POST['email']."</a><br /><br /><h3>".$lang->invsent2."</h3><p id=\"mailbody\">".str_replace('[[invlink]]', $link, $config->invmailbody)."</p>";
    $return.=$mailBody;
    $mailBody="<html><head>".$config->invmailsubject."</head><body>".$mailBody."</body></html>";
    Mail($_POST['email'], $config->invmailsubject, $mailBody, "From: ".$config->invsendermail);
  }  
  if(empty($_POST['addpage'])AND(empty($_POST['addgroups'])))
    $return.=$lang->emptypermissions;
}else{
  $return.='<script type="text/javascript">
    $(function(){
       var dtConfig = $.parseJSON($("#tree").attr("data-dyna"));
       $("#tree").dynatree(dtConfig);
       $("#tree").dynatree({
          clickFolderMode: 1, // 1:activate, 2:expand, 3:activate and expand
    	    onClick: function(node) {
            if( node.data.id ){
            $("#editor").text("'.$lang->loading.'").load("'.$config->weburl.'members/permissions/load/"+node.data.id);
            //console.log(datatosend);
            }
          },
          debugLevel: 1 // 0:quiet, 1:normal, 2:debug
          //checkbox: true
          });
    });
    $(document).ready(function(){
      $(\'.button_remove\').on(\'click\', function (e) {
        var id=$(this).attr(\'value\')
        $(\'.wrapper_\'+id).slideUp(\'fast\',function(){
          $(\'.wrapper_\'+id).remove()
        })
      });
    })
  </script>                                    
  <style>
   #data{border: none; background-color: transparent;}
   #editor{display: none;}
   .wrappers {display: none}
   .wrappers .id{font-size: 0.8em; color: #aaa}
  </style>
  
  ';
  $maxRank=max(explode(',',$_SESSION['permissions'][$config->pageid]));
  $sql="SELECT id, name, displayname, description, rank FROM `".$config->dbprefix."usergroups` WHERE active='Yes' AND rank<=".$maxRank." ORDER BY id ASC";
  $result=$mysqli->query($sql);
  $return.='<form method="post">
  <label for="email">'.$lang->mail.'</label><input type="email" placeholder="'.$lang->mail.'" id="email" name="email">
  <fieldset id="usergroups"><legend>'.$lang->selectusergroups.'</legend>
    <table>
      <tr class="tbl-head"><td>'.$lang->select.'</td><td>'.$lang->groupid.'</td><td>'.$lang->groupname.'</td><td>'.$lang->groupdisplayname.'</td><td>'.$lang->description.'</td><td>'.$lang->grouprank.'</td></tr>
  ';
  while($row=$result->fetch_assoc())
  {
    $return.='<tr><td><input type="checkbox" name="addgroups[]" value="'.$row['id'].'"></td><td>'.$row['id'].'</td><td>'.$row['name'].'</td><td>'.$row['displayname'].'</td><td>'.$row['description'].'</td><td>'.$row['rank'].'</td></tr>';
  }  
  $return.='</table></fieldset><br>
    <fieldset id="pagespermissions"><legend>'.$lang->selectpagespermissions.'</legend>
      <div id="editor"></div>
      <div id="data"><br /></div>
    </fieldset>
  ';
  $return.='<input type="hidden" name="step" value="2"><input type="submit" value="'.$lang->submit.'">';
}
?>