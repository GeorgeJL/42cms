<?php /* v1.1.0 */     
      //   AUTOMATIC (len klikne v members area na automatic update a vsetko prebehne automaticky)  +  MANUAL (stiahne z netu potrebne subory, vlozi ich do urcenej zlozky a spusti manual update)
      //   EXCEPTIONS LIST/CUSTOM FILES LIST   -   dat moznost uzivatelom pridat zoznam customized files a tie sa neprepisu pri update (???)
      /*    
      *   To do:
      *     -nastavenie permissions uzivatelom-link na update len uzivatelom, ktory mozu updatovat      
      *     -updatovanie suborov (pridavat, vymazavat, prepisovat) - DONE
      *     -updatovanie zloziek (pridavat, vymazavat, prepisovat, menit permissions) - DONE  - permissions neotestovane (to sa na Win neda :-)     
      *     -updatovanie databazy (pridavat, vymazavat aj menit zapisy) - DONE
      *     -menit nastavenie (config.php)
      *     -zapisat aktualnu verziu po update
      *     -pridat moznost spustania stiahnuteho suboru (na nejake komplikovanejsie updaty, ktore vyzaduju funkciu, ktoru tento updater nema) - DONE
      *     -vymazanie docasnych suborov - DONE      
      */             
       
$latestVersion=json_decode($class->getContent($config->versionserver),1);
//include("local_version.php"); 
if($config->localbuild!=$latestVersion['build'])
{
  if(empty($_POST))
  {
    $return='      
      <script>
        function showDiv() {
           document.getElementById("formUpdate").style.display = "none";
           document.getElementById("updateProgress").style.display = "block";
        }
      </script>
      <div id="formUpdate">
        <form method="post">
          <button type="submit" name="startupdate" onclick="showDiv()">'.$lang->startupdate.'</button>
        </form>
      </div><br>
      <div id="updateProgress" style="display:none">'.$lang->updateProgress.'<br><br>
        <div style="width:214px;height:15px;background-image:url(data:image/gif;base64,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%3D%3D);}"></div>
      </div>';
  }else if(isset($_POST['startupdate'])){
    /* creating backup folder */
    $backupFolder='build-'.$localVersion['build'];
    $origBackupFolder=$backupFolder; 
    $i=1;
    do
    {
      if(!file_exists('backup/'.$backupFolder.'/'))
      {
        mkdir('backup/'.$backupFolder.'/', 0777, true); 
        $stop=true;
      }else{
        $i++;
        $backupFolder=$origBackupFolder.'-copy'.$i;
      }
    }while(!$stop);
    /* end of creating backup folder */
    $updateData=json_decode($class->getContent($config->updateserver),1);  //downloading update instructions
    if(!empty($updateData['files'])) // updating files
    {
      foreach($updateData['files'] as $row)
      {
        @mkdir(dirname('backup/'.$backupFolder.'/'.$row['local']), 0777, true);
        @rename($row['local'], 'backup/'.$backupFolder.'/'.$row['local']);
        if(!empty($row['source']))
        {
          $fileContent=$class->getContent($row['source']);
          @mkdir(dirname('./'.$row['local']), 0777, true);
          file_put_contents('./'.$row['local'], $fileContent);
        }  
      }
    }
    if(!empty($updateData['folders'])) // updating folders
    {
      foreach($updateData['folders'] as $row)
      {
        switch($row['action'])
        {
          case 'add':
            @mkdir($row['path'], $row['permissions'], true);
            break;
          case 'remove':
            @rename($row['path'], 'backup/'.$backupFolder.'/'.$row['path']);
            break;
        }
      }
    }
    if(!empty($updateData['mysqli'])) // updating database
    {
      foreach($updateData['mysqli'] as $row)
      {
        $row=str_replace("[[dbprefix]]", $config->dbprefix, $row);
        $mysqli->query($row);
      }  
    }
    if(!empty($updateData['run'])) // running scripts
    {
      foreach($updateData['run'] as $row)
      {
        @include_once($row);
      }
    }
    @$class->deleteDir('temp/updatetemp/'); // delete updatetemp directory
    
    if((!empty($updateData['removeConfig']))OR(!empty($updateData['removeConfig']))) // updating configuration file ( includes/config.php )
    {
      $configFile='<?php
class Config{
';  
      $tmpConfig=$config;
      if(!empty($updateData['removeConfig']))
      {
        foreach($updateData['removeConfig'] as $value)
        {
          unset($tmpConfig->$value);
        }
      }  
      if(empty($updateData['config']))
        $updateData['config']=array();
      foreach($tmpConfig as $key => $value)
      {
        if(isset($updateData['config'][$key]))
        {
          $tmpData=$updateData['config'][$key];
          unset($updateData['config'][$key]);
        }else
          $tmpData=$value;
        $configFile.='public $'.$key.'="'.addslashes($tmpData).'";
';
      }
      foreach($updateData['config'] as $key => $value)
      {
        $configFile.='public $'.$key.'="'.addslashes($value).'";
';
      }
      $configFile.='}
?'.'>';
      @mkdir('backup/'.$backupFolder.'/includes/', 0777, true);
      rename('includes/config.php', 'backup/'.$backupFolder.'/includes/config.php');
      file_put_contents('includes/config.php', $configFile);
    }  
    setcookie("update", '', time()+$cookieLifetime);
    $return.=$lang->successfulUpdate.$latestVersion['version'].'.';
  }
}else{
  $return.=$lang->havelatestversion;
}  
?>