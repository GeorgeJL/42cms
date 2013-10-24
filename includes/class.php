<?php
class MainClass
{
  public $logfolder;
  public $newlogfiletime;
  
  function errorLog($errorId, $glob)
  {
    switch ($this->newlogfiletime){
      case 1:
        $logFile=date('y');
        break; 
      case 2:
        $logFile=date('y-m');
        break; 
      case 3:
        $logFile=date('y-').'w'.date('W');
        break; 
      case 4:
        $logFile=date('y-m-d');
        break; 
      case 5:
        $logFile=date('y-m-d-H');
        break; 
      default:
        $logFile='';
        break; 
    }
    $logFile=$this->logfolder.'/LOG-'.$logFile;
    require_once('includes/errorreporting.php');
    $errorReporting=new ErrorReporting;
    if($errorReporting->data[$errorId]['conf']['0'] == '1'){ //error to show (echo)
      require_once('includes/lang/'.$GLOBALS['langId'].'.errors.php');
      $errorText=new ErrorText;
      if($errorText->text[$errorId]=='--DEFAULT--')
      {
        $echo=$errorText->text['default'];
      }else{
        $echo=$errorText->text[$errorId];
      }  
      //$echo=$errorReporting->data[$errorId]['show'];
    }
    if($errorReporting->data[$errorId]['conf']['1'] == '1'){
      $fh=fopen($logFile, 'a+');
      foreach($errorReporting->data[$errorId]['varlist'] as $value)
      {
        if (is_array($glob[$value]))
        {
          $data.=' '.$value.': '.json_encode($glob[$value]).' <br> ';
        }else if(is_object($glob[$value])){
          $data.=' '.$value.': '.print_r($glob[$value], true).' <br> ';
        }else{
          $data.=' '.$value.': '.$glob[$value].' <br> ';
        }  
      }
      //$data=time().':'.$errorReporting->data[$errorId]['stringcode'].':'.$data.'<hr />';
      $data=date('d/m/Y H:i:s.u').':'.$errorReporting->data[$errorId]['stringcode'].':'.$data.'<hr />';
      fwrite($fh, $data);
    }
    return @$echo;
  }
  
  public function salt()
  {   //copy of this function is in lostpass.php   //create 20 charecters long salt for crypt()-blowfish  
    $salt='/././.'.base_convert(md5(mt_rand(0, 99999)), '10', '35');
    $salt=str_shuffle($salt);
    $salt.=strtoupper(base_convert(md5(mt_rand(0, 99999)), '10', '35'));
    $salt=str_shuffle($salt);
    $salt=substr($salt, 0, 22);
    return $salt;
  }
  
  public function getPermissions($mysqli, $userId, $groups=null)
  {
    $sqlGroups='';
    if(!empty($groups))
    {
      $groups=explode(',', $groups);
      foreach($groups as $value)
      {
        if (!empty($value))
        {$sqlGroups.=" OR groupid='".trim($value)."'";}
      }
    }
    $sql="SELECT permission, parameters FROM ".$mysqli->dbprefix."permissions WHERE userid='".$userId."'".$sqlGroups." OR userid='0' AND groupid='0' GROUP BY permission, parameters";
    $result=$mysqli->query($sql);
    //echo $mysqli->error;
    $rows=array();//this line is added to create empty array in case there are no permission for current user/usergroup - session hash function use 'serialize' so it need ALWAYS array, even if it is the empty one;
    while($row=$result->fetch_array())
    {
      if($row[0]==0)
      {
        if(isset($rows[$row[0]])AND($rows[$row[0]]!=='0'))
        {
          $rows[$row[0]].=','.$row[1];
          $multipleParams=true;
        }else{
          $rows[$row[0]]=$row[1];
        }
      }else{
        if(!isset($rows[$row[0]])OR($rows[$row[0]]<$row[1]))
        {
          $rows[$row[0]]=$row[1];
        }
      }  
    }
    if(!empty($multipleParams))
    {
      foreach($rows as &$value)
      {
        $temp=explode(',',$value);
        $temp=array_unique($temp);
        $value=trim(implode(',',$temp), ',');
      }
    }    
    return $rows;
  }
  
  public function reloadPermissions($mysqli, $config)
  {
    $sql="SELECT salt, cookieid, usergroups FROM ".$config->dbprefix."users WHERE id='".$mysqli->real_escape_string($_SESSION['userid'])."'";
    $result=$mysqli->query($sql);
    echo $mysqli->error;
    $row=$result->fetch_assoc();
    $_SESSION['permissions']=$this->getPermissions($mysqli, $_SESSION['userid'], $row['usergroups']);
    $hash='G2./.S9A77I1MKQItXkg/Vg6'.$_SESSION['userid'].$row['cookieid'].serialize($_SESSION['permissions']).$_SESSION['username'].$_SESSION['mail'].$_SESSION['lang'].$row['salt'];
    $hash=crypt(md5($hash).$hash, $config->crypt.$this->salt());
    $_SESSION['hash']=$hash;
  }

  public function pluginIncluder($data, $pluginVars, $config, $mysqli, $lang)
  {
    $explode=explode('?', $data);
    @$item=@explode("&", $explode[1]);
    $pluginVars['pluginget']=array();
    $class=$this;
    foreach($item as $value){
      if (strpos($value,'=')){
        $keyValue=explode("=", $value);
        if (is_array($keyValue)){
          $pluginVars['pluginget'][$keyValue[0]]=$keyValue[1];
        }
      }else if($value!==''){
        $pluginVars['pluginget'][]=$value;
      }  
    }
    $pluginName=$explode[0];
    unset($data, $explode, $item, $item, $value, $keyValue); //removes all variables except $pluginVars to prevent plugin conflict with this variables
    if($config->debuggingmode)
    {
      include ('includes/plugins/'.$pluginName.'.php');
    }else{
      @include ('includes/plugins/'.$pluginName.'.php');
    }  
    return @$return;   //use @ to prevent error if plugin returns no data
  }
  
  public function addonIncluder($addons, $pageText, $pluginVars, $debuggingMode=false)
  {
    $addons=array_filter(explode(',', $addons));
    foreach($addons as $addonId)
    {
      @include_once ('includes/addons/'.$addonId.'/index.php');
    }
    if($debuggingMode)
    {
      return $pageText;
    }else{
      return @$pageText;  //use @ to prevent error if some of the plugins removed $pageText variable
    } 
  }

  private function createTree($value)
  {
    if(strpos($value['url'], '/')==false)
      {
      $temparr['___'.$value['url_part']]=$value;
      }
    else  
      {
      $parentPart='___'.substr($value['url'],0, strpos($value['url'], '/') );
      $minusParentPart=substr($value['url'],strpos($value['url'], '/')+1 );
      if(strpos($minusParentPart, '/')==false)
        {
        $secondParentPart=$minusParentPart;
        }
      else
        {
        $secondParentPart=substr($minusParentPart,0 ,strpos($minusParentPart, '/') );
        }  
      if(!empty($value['subdomain']))
      {
        $value['subdomain']=$value['subdomain'].'.';
      }else{
        $value['subdomain']='';
      }  
      $value['url']=$subdomain.substr($value['url'],strpos($value['url'], '/')+1 );
      $temparr[$parentPart]['children']=$this->createTree($value);
      } 
    return $temparr; 
  }
  
  private function renameKey($array)
  {
    $array2=array();
    $i=0;
    foreach ($array as $key => $value)
    {
      $array2[$i]=$value;
      if(isset($value['children']))
      {
        $array2[$i]['children']=$this->renameKey($value['children']);
      }
      $i++;
    }
    if((!isset($array2[0]['url'])) )
      $array2=$array2[0]['children'];
    return $array2;
  }  
  
  private function arrayToList($menuArray,$weburl, $active=null)
  {
    $return='
<ul>';
    foreach($menuArray as $value)
    {
      if($value['id']==$active)
      {
        $return.='
<li class="active"><a href="'.$weburl.''.$value['old_url'].'/">'.$value['menutitle'].'</a>';
      }else{
        $return.='
<li><a href="'.$weburl.''.$value['old_url'].'/">'.$value['menutitle'].'</a>';
      }
      if(isset($value['children'])) 
      {
        $return.=$this->arrayToList($value['children'],$weburl, $active);
      }                                                                                                                                       
    $return.='</li>';
    }
    $return.='
</ul>
';
    return $return;
  }
  
  private function arrayToList2($menuArray, $weburl, $path=null, $flatten=false)
  {
    if($flatten)
    {
      $resultPath=$path;
    }else{
      $resultPath=explode('/', $path);
      $resultPath=array_slice($resultPath, 0, $menuArray[0]['level']);
      $Temp=$resultPath;
      $resultPath=implode($resultPath, '/');
    }
    $return='
<ul>';
    foreach($menuArray as $value)
    {
      if(empty($value['old_url']))
        $slash='';
      else
        $slash='/';
      
      if($value['old_url']==$resultPath)
      {
        $return.='
<li class="active"><a href="'.$weburl.''.$value['old_url'].$slash.'">'.$value['menutitle'].'</a>';
      }else{
        $return.='
<li><a href="'.$weburl.''.$value['old_url'].$slash.'">'.$value['menutitle'].'</a>';
      }
      if(isset($value['children'])) 
      {
        $return.=$this->arrayToList2($value['children'],$weburl, $path);
      }                                                                                                                                       
    $return.='</li>';
    }
    $return.='
</ul>
';
    return $return;
  }
  
  
  public function saveFile($tmpFile, $fileName, $folder)
  {
    if (!file_exists($folder))
    	@mkdir($folder);
    $ext = strrpos($fileName, '.');
    $fileName_a = substr($fileName, 0, $ext);
    $fileName_b = substr($fileName, $ext);
    
    $normFrom = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ',
               ' ','--','&quot;','!','@','#','$','%','^','&','*','(',')','_','+','{','}','|',':','"','<','>','?','[',']','\\',';',"'",',','.','/','*','+','~','`','=');
    $normTo = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o',
                '-','-','','','','','','','','','','','','','','','','','','','','','','','','');

    $fileName_a = str_replace('&', '-and-', $fileName_a);
    $fileName_a = str_replace(' ', '-', $fileName_a);
    $fileName_a = str_replace('--', '-', $fileName_a);
    $fileName_a = strtolower(str_replace($normFrom, $normTo, $fileName_a));
    $fileName_a = trim(preg_replace('/[^\w\d_ -]/si', '', $fileName_a));//remove all illegal chars
    $fileName = $fileName_a.$fileName_b;

    if(file_exists($folder.$fileName))
    {
      $count = 1;
  	  while (file_exists($folder.$fileName_a.'-'.$count.$fileName_b))
  		{
        $count++;
      }
      $fileName=$fileName_a.'-'.$count.$fileName_b;  
    }
    if(rename($tmpFile, $folder.$fileName)===true)
      return $fileName;
    else
      return false;  
  }
  
  public function cleanUrl($url)
  {
    $normFrom = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ',
               ' ','--','&quot;','!','@','#','$','%','^','&','*','(',')','_','+','{','}','|',':','"','<','>','?','[',']','\\',';',"'",',','.','/','*','+','~','`','=');
    $normTo = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o',
                '-','-','','','','','','','','','','','','','','','','','','','','','','','','');

    $url = str_replace('&', '-and-', $url);
    $url = str_replace(' ', '-', $url);
    $url = str_replace('--', '-', $url);
    $url = strtolower(str_replace($normFrom, $normTo, $url));
    $url = trim(preg_replace('/[^\w\d_ -]/si', '', $url));//remove all illegal chars
    return $url;
  }

  public function getContent($url)
  {
    $ch = curl_init();
    curl_setopt ($ch, CURLOPT_URL, $url);
    curl_setopt ($ch, CURLOPT_HEADER, 0);
    ob_start();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //BUT THIS IS A SECURITY ISSUE. See: http://stackoverflow.com/questions/6400300/php-curl-https-causing-exception-ssl-certificate-problem-verify-that-the-ca-cer
    curl_exec ($ch);
    curl_close ($ch);
    $string = ob_get_contents();
    ob_end_clean();
    return $string;     
  }
  public function deleteDir($dir) 
  {
    if (is_dir($dir)) 
    {
      $objects = scandir($dir);
      foreach ($objects as $object)
      {
        if ($object != "." && $object != "..")
        {
          if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object);
        }
      }
      reset($objects);
      rmdir($dir);
    }
  }
}
?>