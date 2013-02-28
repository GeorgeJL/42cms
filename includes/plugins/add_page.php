<?php
$return.='';
if($pluginVars['pluginget']['load']==1){
  $id=$mysqli->real_escape_string($pluginVars['afterpath']);
  $sql="SELECT url, level FROM ".$config->dbprefix."pages WHERE id=".$id;
  $result=$mysqli->query($sql);
  $result=$result->fetch_array();
  if(!empty($result['url']))
  {
    $newPage['url']=$result['url'].'/-';
  }else{
    $newPage['url']='-';
  }
  $newPage['level']=$result['level']+1;
  $newPage['url_part']='-';
  $sql2="SELECT id FROM ".$config->dbprefix."pages WHERE url='".$newPage['url']."'";
  $result2=$mysqli->query($sql2);
  if(($result2->num_rows)>0)
  {  //new subpage for this page was already created
    $newPage['id']=$result2->fetch_array();
    $newPage['id']=$newPage['id']['id'];
    $return.=$lang->pagealreadycreated;
    $return.='
      <form method="post" action="'.$config->editpageurl.'">
      <button type="submit" value="'.$newPage['id'].'" name="editNewId">'.$lang->editnewpage.'</button>
      </form>
    ';
  }else{
    $hash=crypt(($newPage['url'].$newPage['url_part'].$newPage['level']), $config->crypt.$class->salt());
    $return.='
    <form method="post">
      <input type="hidden" name="url" value="'.$newPage['url'].'">
      <input type="hidden" name="url_part" value="'.$newPage['url_part'].'">
      <input type="hidden" name="level" value="'.$newPage['level'].'">
      <input type="hidden" name="hash" value="'.$hash.'">
      <input type="submit" name="submit" value="'.$lang->addnewpagebutton.'">
    </form>   
    ';
  }
}else if(isset($_POST['submit'])){
  $newPage['url']=$_POST['url'];
  $newPage['url_part']=$_POST['url_part'];
  $newPage['level']=$_POST['level'];
  $newPage['hash']=$_POST['hash'];
  $hash=crypt(($newPage['url'].$newPage['url_part'].$newPage['level']), $newPage['hash']);
  //$return.='<hr />'.$_POST['hash'].'<br />'.$hash;
  if($hash==$newPage['hash'])
  {
    $sql="INSERT INTO ".$config->dbprefix."pages (url, url_part, level) VALUES ('".$mysqli->real_escape_string($newPage['url'])."', '".$mysqli->real_escape_string($newPage['url_part'])."', '".$mysqli->real_escape_string($newPage['level'])."')";
    $mysqli->query($sql);
    $id=$mysqli->insert_id;
    $return.=$lang->pagesuccessfullycreated.'<br />';
    $return.='
      <form method="post" action="'.$config->editpageurl.'">
      <button type="submit" value="'.$id.'" name="editNewId">'.$lang->editnewpage.'</button>
      </form>
    ';
  }else{
    $return=$class->errorLog('8', $GLOBALS);
  }
}else{
  $return.= '
  <h5>'.$lang->addnewpage.'</h5><br>
  <script type="text/javascript">
  $(function(){
     var dtConfig = $.parseJSON($("#tree").attr("data-dyna"));
     $("#tree").dynatree(dtConfig);
     $("#tree").dynatree({
        clickFolderMode: 1, // 1:activate, 2:expand, 3:activate and expand
  	    onActivate: function(node) {
          if( node.data.id ){
          $("#editor").text("'.$lang->loading.'").load("'.$config->weburl.'members/addpage/load/"+node.data.id);
          alert(datatosend);
          }
        },
        debugLevel: 1 // 0:quiet, 1:normal, 2:debug
        //checkbox: true
        });
  });
  </script>';
  
  $return.='<div id="editor"><br /></div>';
}

?>