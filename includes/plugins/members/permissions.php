<?php

if($pluginVars['pluginget']['load'])
{
  $id=$mysqli->real_escape_string($pluginVars['afterpath']);
  $sql='SELECT menutitle, title, url, subdomain, membersonly FROM '.$config->dbprefix.'pages WHERE id="'.$id.'" LIMIT 1';
  $result=$mysqli->query($sql);
  $row=$result->fetch_assoc();
  
   if(!empty($row['menutitle'])){
      $title=$row['menutitle'];
    }else if(!empty($row['title']))
    {
      $title=$row['title'];
    }else if(!empty($row['url'])){
      $title=$row['url'];
    }else{
      $title='unknown title';
    }  
    $input='<div class="wrapper_'.$id.' wrappers"><input type="hidden" name="addpage[]" value="'.$id.'"><input type="hidden" name="title[]" value="'.$title.'"><input type="hidden" name="membersonly[]" value="'.$row['membersonly'].'"><label><span title="'.$row['url'].'">'.$title.' <span class="id">('.$id.')</span></span></label><input type="text" name="parameters[]" value="0"> <button name="remove" type="button" value="'.$id.'" class="button_remove">'.$lang->remove.'</button></div>';
  $return.="
  <script>
    $(document).ready(function(){
      if($('.wrapper_".$id."').length==0)
      {
        $('#data').append('".$input."');
        $('.button_remove').on('click', function (e) {
          var id=$(this).attr('value')
          $('.wrapper_'+id).slideUp('fast',function(){
            $('.wrapper_'+id).remove()
          })
        });
        $('.wrapper_".$id."').slideDown('fast')
      }else{
        $('.wrapper_".$id." span').animate({'color':'black','background-color':'white'},'1000',function(){
          $('.wrapper_".$id." span').animate({'color':'white','background-color':'transparent'},'1000')
        })
      }
    })
  </script>
  ";                      
}else if($_POST['step']=='2'){
  $return.='
  <style>
   #data{border: 1px solid yellow; background-color: black; color: white; padding: 30px}
   #editor{display: none;}
   .wrappers {display: none}
   .wrappers .id{font-size: 0.8em; color: #aaa}
  </style>
  ';
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
  </script>';
  $return.='<form method="post">';
  $id=trim($_POST['selectedid'], $_POST['selectedid'][0]);
  if($_POST['selectedid'][0]=='u')
  {
    $sql="SELECT username, usergroups FROM `".$config->dbprefix."users` WHERE id='".$id."'";
    $result=$mysqli->query($sql);
    $row=$result->fetch_assoc();
    $username=$row['username']; 
    $maxRank=explode(',',$_SESSION['permissions'][$config->pageid]);
    rsort($maxRank);
    $maxRank=$maxRank[0];
    $sql2="SELECT id, name, displayname, description, rank FROM `".$config->dbprefix."usergroups` WHERE active='Yes' AND rank<=".$maxRank." ORDER BY id ASC";
    $result2=$mysqli->query($sql2);
    $return.='<form method="post"><table>
      <tr class="tbl-head"><td>'.$lang->select.'</td><td>'.$lang->groupid.'</td><td>'.$lang->groupname.'</td><td>'.$lang->groupdisplayname.'</td><td>'.$lang->description.'</td><td>'.$lang->grouprank.'</td></tr>
    ';
    $groups=explode(',',$row['usergroups']);
    $groups=array_flip($groups);
    $groups = array_fill_keys(array_keys($groups), 'checked');
    $return.='<h3>'.$lang->permissionsforuser.' '.$username.'</h3>';
    while($row2=$result2->fetch_assoc())
    {
      @$return.='<tr><td><input type="checkbox" name="addgroups[]" value="'.$row2['id'].'" '.$groups[$row2['id']].'></td><td>'.$row2['id'].'</td><td>'.$row2['name'].'</td><td>'.$row2['displayname'].'</td><td>'.$row2['description'].'</td><td>'.$row2['rank'].'</td></tr>';
    }  
    $return.='</table><br>';  
    $sql3="SELECT permission, parameters FROM `".$config->dbprefix."permissions` WHERE userid='".$id."'";
    $result3=$mysqli->query($sql3);
    
    $i=0;
    $permissions=array();
    while($row3=$result3->fetch_assoc())
    {
      if($i==0)
        $idlist='id="'.$row3['permission'].'"';
      else
        $idlist.=' OR id="'.$row3['permission'].'"';
      $permissions[$row3['permission']]=$row3['parameters'];  
      $i++;    
    }
    $sql4='SELECT id, menutitle, title, url, subdomain, membersonly FROM '.$config->dbprefix.'pages WHERE '.$idlist;
    $result4=$mysqli->query($sql4);
    $input='';
    if($result4->num_rows)
    {
      while($row4=$result4->fetch_assoc())
      {
        if(!empty($row4['menutitle'])){
          $title=$row4['menutitle'];
        }else if(!empty($row4['title'])){
          $title=$row4['title'];
        }else if(!empty($row4['url'])){
          $title=$row4['url'];
        }else{
          $title='unknown title';
        }  
        $input.='<div class="wrapper_'.$row4['id'].' wrappers" style="display: block"><input type="hidden" name="addpage[]" value="'.$row4['id'].'"><input type="hidden" name="title[]" value="'.$title.'"><input type="hidden" name="membersonly[]" value="'.$row4['membersonly'].'"><label><span title="'.$row4['url'].'">'.$title.' <span class="id">('.$row4['id'].')</span></span></label><input type="text" name="parameters[]" value="'.$permissions[$row4['id']].'"> <button name="remove" type="button" value="'.$row4['id'].'" class="button_remove">'.$lang->remove.'</button></div>';
      }
    }
  }else if($_POST['selectedid'][0]=='g'){
    if($id!='new')
    {
    //$sql="SELECT username, usergroups FROM `".$config->dbprefix."users` WHERE id='".$id."'";
      
      $sql="SELECT id, name, displayname, description, rank FROM `".$config->dbprefix."usergroups` WHERE id='".$id."'"; 
      $result=$mysqli->query($sql);
      $row=$result->fetch_assoc();
      $groupname=$row['name'].' '.$row['displayname']; 
      
      $return.='<form method="post">';
      
      $return.='<h3>'.$lang->permissionsforgroup.' '.$groupname.'</h3>';
      $sql3="SELECT permission, parameters FROM `".$config->dbprefix."permissions` WHERE groupid='".$id."'";
      $result3=$mysqli->query($sql3);
      $i=0;
      $permissions=array();
      while($row3=$result3->fetch_assoc())
      {
        if($i==0)
          $idlist='id="'.$row3['permission'].'"';
        else
          $idlist.=' OR id="'.$row3['permission'].'"';
        $permissions[$row3['permission']]=$row3['parameters'];  
        $i++;    
      }
      $sql4='SELECT id, menutitle, title, url, subdomain, membersonly FROM '.$config->dbprefix.'pages WHERE '.$idlist;
      $result4=$mysqli->query($sql4);
      $input='';
      while($row4=$result4->fetch_assoc())
      {
        if(!empty($row4['menutitle'])){
          $title=$row4['menutitle'];
        }else if(!empty($row4['title'])){
          $title=$row4['title'];
        }else if(!empty($row4['url'])){
          $title=$row4['url'];
        }else{
          $title='unknown title';
        }  
        $input.='<div class="wrapper_'.$row4['id'].' wrappers" style="display: block"><input type="hidden" name="addpage[]" value="'.$row4['id'].'"><input type="hidden" name="title[]" value="'.$title.'"><input type="hidden" name="membersonly[]" value="'.$row4['membersonly'].'"><label><span title="'.$row4['url'].'">'.$title.' <span class="id">('.$row4['id'].')</span></span></label><input type="text" name="parameters[]" value="'.$permissions[$row4['id']].'"> <button name="remove" type="button" value="'.$row4['id'].'" class="button_remove">'.$lang->remove.'</button></div>';
      }
    }
  }
  if(!empty($_POST['gid']))
    $return.='<input type="hidden" name="gid" value="'.$_POST['gid'].'">';
  $return.='<div id="data"><br />'.$input.'</div><input type="hidden" name="step" value="3"><input type="submit" value="'.$lang->submit.'"><input type="hidden" name="selectedid" value="'.$_POST['selectedid'].'"> </form>';
  $return.='<div id="editor"><br /></div>';
  
}else if($_POST['step']=='3'){
  if(!empty($_POST['selectedid'])){
    $maxRank=max(explode(',',$_SESSION['permissions'][$config->pageid]));
    $id=trim($_POST['selectedid'], $_POST['selectedid'][0]);
    if($id=='new')
      $id=$_POST['gid'];
    if($_POST['selectedid'][0]=='u')
    {
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
          $usergroups=implode($_POST['addgroups'],',');
          $sql2='UPDATE '.$config->dbprefix.'users SET usergroups="'.$mysqli->real_escape_string($usergroups).'", cookieid="1" WHERE id="'.$mysqli->real_escape_string($id).'"';
          $mysqli->query($sql2);
        }  
        else{
          $return.=$this->errorLog('16', $GLOBALS);
          $error=true;
        }  
      }  
      if(!empty($_POST['addpage'])AND!$error)
      {
        $i=0;
        $sesPermissions=array_keys($_SESSION['permissions']);
        foreach($_POST['addpage'] as $key => $value)
        {
          $rank=$_POST['parameters'][$key];
          if(isset($_SESSION['permissions'][$_POST['addpage'][$key]])AND($rank>$_SESSION['permissions'][$_POST['addpage'][$key]]))
          {
            $rank=$_SESSION['permissions'][$_POST['addpage'][$key]];
          } 
          
          
          if(in_array($value, $sesPermissions))
          {
            if($i==0)
              $permissions='("'.$mysqli->real_escape_string($id).'", "'.$mysqli->real_escape_string($value).'", "'.$mysqli->real_escape_string($rank).'" )';
            else
              $permissions.=',("'.$mysqli->real_escape_string($id).'", "'.$mysqli->real_escape_string($value).'", "'.$mysqli->real_escape_string($rank).'" )';
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
        {
          
          $sql3='DELETE FROM '.$config->dbprefix.'permissions WHERE userid="'.$mysqli->real_escape_string($id).'"';
          $mysqli->query($sql3);
          $sql4='INSERT INTO '.$config->dbprefix.'permissions (userid, permission, parameters) VALUES '.$permissions;
          $mysqli->query($sql4);
          $return.=$lang->permissionsupdated;
        }
      }
      if(empty($_POST['addpage'])AND(empty($_POST['addgroups'])))
      {
        $return.=$lang->emptypermissions;
      }
    }else if($_POST['selectedid'][0]=='g'){
      $i=0;
      foreach($_POST['addpage'] as $key => $value)
      {
        $rank=$_POST['parameters'][$key];
        if(isset($_SESSION['permissions'][$_POST['addpage'][$key]])AND($rank>$_SESSION['permissions'][$_POST['addpage'][$key]]))
        {
          $rank=$_SESSION['permissions'][$_POST['addpage'][$key]];
          $return.=$lang->wrongpermparam.'<br>';
        }  
        if($i==0)
          $permissions='("'.$mysqli->real_escape_string($id).'", "'.$mysqli->real_escape_string($value).'", "'.$mysqli->real_escape_string($rank).'" )';
        else
          $permissions.=',("'.$mysqli->real_escape_string($id).'", "'.$mysqli->real_escape_string($value).'", "'.$mysqli->real_escape_string($rank).'" )';
        $i++;    
      }
      $sql2='DELETE FROM '.$config->dbprefix.'permissions WHERE groupid="'.$mysqli->real_escape_string($id).'"';
      $mysqli->query($sql2);
      $sql3='INSERT INTO '.$config->dbprefix.'permissions (groupid, permission, parameters) VALUES '.$permissions;
      $mysqli->query($sql3);
      $return.=$lang->permissionsupdated;
    }
  }else{
    $lang->noselectedpages;
  }
}else{
  $maxRank=max(explode(',',$_SESSION['permissions'][$config->pageid]));
  $sql="SELECT id, name, displayname, description, rank FROM `".$config->dbprefix."usergroups` WHERE active='Yes' AND rank<=".$maxRank." ORDER BY id ASC";
  $result=$mysqli->query($sql);
  $return.='<form method="post"><table>
    <tr class="tbl-head"><td>'.$lang->select.'</td><td>'.$lang->groupid.'</td><td>'.$lang->groupname.'</td><td>'.$lang->groupdisplayname.'</td><td>'.$lang->description.'</td><td>'.$lang->grouprank.'</td></tr>
  ';
  
  while($row=$result->fetch_assoc())
  {
    $return.='<tr><td><input type="radio" name="selectedid" value="g'.$row['id'].'"></td><td>'.$row['id'].'</td><td>'.$row['name'].'</td><td>'.$row['displayname'].'</td><td>'.$row['description'].'</td><td>'.$row['rank'].'</td></tr>';
  }  
  $return.='<tr><td><input type="radio" name="selectedid" value="gnew"></td><td><input type="text" name="gid"></td><td><input type="text" name="gname"></td><td><input type="text" name="gdisplayname"></td><td><input type="text" name="gdescription"></td><td><input type="text" name="grank"></td></tr>';
  $return.='</table><br>';  
  
  $sql2="SELECT id, username, usergroups FROM `".$config->dbprefix."users` ORDER BY id ASC";
  $result2=$mysqli->query($sql2);
  $return.='<table>
    <tr class="tbl-head"><td>'.$lang->select.'</td><td>'.$lang->userid.'</td><td>'.$lang->username.'</td><td>'.$lang->groups.'</td></tr>';
  while($row=$result2->fetch_assoc())
  {
    $rank=$class->getPermissions($mysqli, $row['id'], $row['usergroups']);
    if(empty($rank[$config->pageid]))
      $rank=0;
    else
      $rank=max(explode(',',$rank[$config->pageid]));
    if($rank<=$maxRank)
      $return.='<tr><td><input type="radio" name="selectedid" value="u'.$row['id'].'"></td><td>'.$row['id'].'</td><td>'.$row['username'].'</td><td>'.$row['usergroups'].'</td></tr>';
  }  
  $return.='</table>';
  $return.='<input type="hidden" name="step" value="2"><input type="submit" value="'.$lang->submit.'"></form>';
}
?>