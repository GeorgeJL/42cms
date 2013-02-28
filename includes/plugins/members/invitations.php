<?php
if(empty($_POST['submit']))
  {
    $maxRank=explode(',',$_SESSION['permissions'][$config->pageid]);
    rsort($maxRank);
    $maxRank=$maxRank[0];
    $sql="SELECT id, name, description FROM `".$config->dbprefix."usergroups` WHERE active='Yes' AND rank<=".$maxRank." ORDER BY id ASC";
    $result=$mysqli->query($sql);
    $userGroups='';
    $pagesPermissions='';
    $userId=$mysqli->real_escape_string($_SESSION['userid']);
    $sql3="SELECT permission FROM `".$config->dbprefix."permissions` WHERE userid=".$userId."";
    $result3=$mysqli->query($sql3);
    $pagesPermissionsSession=array();
    while($row=$result3->fetch_array())
    {
      $pagesPermissionsSql.=" OR id='".$row['permission']."'";
    }
    if(!empty($pagesPermissionsSql))
    {
      $pagesPermissionsSql=substr($pagesPermissionsSql, 4);
      $sql2="SELECT id, menutitle, url FROM `".$config->dbprefix."pages` WHERE ".$pagesPermissionsSql." ORDER BY id ASC";
      $result2=$mysqli->query($sql2);
      $pagesPermissions='';
      while($row2=$result2->fetch_array())
      {
        $pagesPermissions.='<label for="pagepermissions_'.$row2['id'].'"><p id="pagetitle">'.$row2['menutitle'].'</p><p id="pageurl">'.$row2['url'].'</p></label><input type="checkbox" id="pagepermissions_'.$row2['id'].'"  name="pagepermissions['.$row2['id'].']">';
        if($_SESSION['permissions'][$row2['id']]!=0)
        {
          $pagesPermissions.='<input type="text" id="pagepermissionsparams_'.$row2['id'].'"  name="pagepermissionsparams_'.$row2['id'].'"[params]>';
        }
      }
    }else{
      $pagesPermissions.='<br />'.$lang->nopermtoassign.'<br />';
    }
      
    while($row=$result->fetch_array())
    {
      $userGroups.='<label for="usergroup_'.$row['id'].'"><p id="usergroupname">'.$row['name'].'</p>'.$row['description'].'</label><input type="checkbox" id="usergroup_'.$row['id'].'"  name="usergroup['.$row['id'].']"><br>';  
    }
    if(empty($userGroups))
    {
      $userGroups.='<br />'.$lang->nogroupstoassign.'<br />';
    }
    $return.='
    <form method="post">
      <label for="email">'.$lang->mail.'</label><input type="email" placeholder="'.$lang->mail.'" id="email" name="email">
      <fieldset id="usergroups"><legend>'.$lang->selectusergroups.'</legend>'.$userGroups.'</fieldset>
      <fieldset id="pagespermissions"><legend>'.$lang->selectpagespermissions.'</legend>'.$pagesPermissions.'</fieldset>
      <input type="submit" id="submit" name="submit" value="'.$lang->submit.'">
    </form>
    ';  
  }else{
    
    $maxRank=explode(',',$_SESSION['permissions'][$config->pageid]);
    rsort($maxRank);
    $maxRank=$mysqli->real_escape_string($maxRank[0]);
    $sql="SELECT id FROM `".$config->dbprefix."usergroups` WHERE active='Yes' AND rank<='".$maxRank."' ORDER BY id ASC";
    $result=$mysqli->query($sql);
    $userGroups=array();
    while($row=$result->fetch_array())
    {
      if(isset($_POST['usergroup'][$row[0]]))
      {
        $userGroups[$row[0]]='0';
      }    
    }
    $pagesPermissions=array();
    if(!empty($_POST['pagepermissions']))
    {
      foreach($_POST['pagepermissions'] as $key => $value)
      {
        if(isset($_SESSION['permissions'][$key]))
        {
          $pagesPermissions[$key]=0;
          if(isset($_POST['pagepermissionsparams_'.$key]))
          {
            if($_POST['pagepermissionsparams_'.$key]>$_SESSION['permissions'][$key])
            {
              $pagesPermissions[$key]=$_SESSION['permissions'][$key];
            }else{
              $pagesPermissions[$key]=$_POST['pagepermissionsparams_'.$key];
            }
          }
        }
      }
    }
    if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
      {
      $invitation['mail']=$mysqli->real_escape_string($_POST['email']);
      }
    
    if(empty($userGroups))
    {
      $invitation['groups']=$mysqli->real_escape_string('{"1":"0"}');
    }else{
      $invitation['groups']=$mysqli->real_escape_string(json_encode($userGroups));
    }  
    
    if(empty($pagesPermissions))
    {
      $invitation['pages']='';
    }else{
      $invitation['pages']=$mysqli->real_escape_string(json_encode($pagesPermissions));
    }  
    $salt=$this->salt();              
    $sql="INSERT INTO ".$config->dbprefix."invitations (mail, groups, pages, salt, addedby, addedtime, status) VALUES ('".$invitation['mail']."', '".$invitation['groups']."', '".$invitation['pages']."', '".$salt."', '".$_SESSION['userid']."', now(), 'Active')";
    $result=$mysqli->query($sql);
    $hash=crypt(($mysqli->insert_id.'@@@'.$_POST['email']), $config->crypt.$salt);
    $link=$config->registerurl.'?m='.urlencode($invitation['mail']).'&id='.$mysqli->insert_id.'&h='.urlencode($hash);
    $mailBody=str_replace('[[invlink]]', $link, $config->invmailbody);
    $return.="<html><head>".$config->invmailsubject."</head><body><h3>".$lang->invsent."</h3><a href='mailto:".$_POST['email']."'>".$_POST['email']."</a><br /><br /><h3>".$lang->invsent2."</h3><p id=\"mailbody\">".$mailBody."</p></body></html>";
    Mail($_POST['email'], $config->invmailsubject, $mailBody, "From: ".$config->invsendermail);
  }
?>  