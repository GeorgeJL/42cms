<?php
if(isset($_POST['editNewId'])AND(preg_match('/([0-9]{1,10})/', $_POST['editNewId'])))
{
  $activeId=$_POST['editNewId'];
  $editThisId='$("#editor").text("'.$lang->loading.'").load("'.$config->editpageurl.'load/'.$activeId.'")';
}

$return.= '
<script type="text/javascript" src="'.$config->jqueryuisource.'"></script>
<script type="text/javascript" src="'.$config->pluginsfolder.'tinymce/tiny_mce.js"></script> 
<script type="text/javascript">
$(function(){
   var dtConfig = $.parseJSON($("#tree").attr("data-dyna"));
   $("#tree").dynatree(dtConfig);
   $("#tree").dynatree({
      clickFolderMode: 1, // 1:activate, 2:expand, 3:activate and expand
	    onActivate: function(node) {
        if( node.data.id ){
         $("#editor").text("'.$lang->loading.'").load("'.$config->editpageurl.'load/"+node.data.id);
        }
      },
    });  
'.$editThisId.'
});
</script>';

if( (isset($pluginVars['afterpath'])AND(preg_match('/^[0-9]{1,5}$/', $pluginVars['afterpath']))) OR (isset($activeId2)))                       
{ 
  if(isset($activeId2))
  {
    $id=$mysqli->real_escape_string($activeId);
  }else{
    $id=$mysqli->real_escape_string($pluginVars['afterpath']);
  }  
  $sql="SELECT id, subdomain, url, level, title, inmenu, menutitle, membersonly, h1, text, template, menuorder, active FROM ".$config->dbprefix."pages WHERE id='".$id."'";
  $result=$mysqli->query($sql);
  $result=$result->fetch_array();
  preg_match('/([a-zA-Z0-9_\-]+)$/', $result['url'], $urlEnd);
  if($urlEnd[0]=='-')
    $urlEnd[0]='';  
    
    
    
  preg_match('*^(([a-zA-Z0-9_\-/]+)([/]{1}))*', $result['url'], $urlBeginning);
  $urlBeginning=@$urlBeginning[0];
  switch($result['inmenu'])
  {
    case 'logged':
      $inMenu='<select id="inmenu" name="inmenu"><option value="logged" selected="">'.$lang->logged.'</option><option value="nologged">'.$lang->nologged.'</option><option value="both">'.$lang->both.'</option><option value="non">'.$lang->non.'</option></select>';
      break;
    case 'nologged':
      $inMenu='<select id="inmenu" name="inmenu"><option value="logged">'.$lang->logged.'</option><option value="nologged" selected="">'.$lang->nologged.'</option><option value="both">'.$lang->both.'</option><option value="non">'.$lang->non.'</option></select>';
      break;
    case 'both':
      $inMenu='<select id="inmenu" name="inmenu"><option value="logged">'.$lang->logged.'</option><option value="nologged">'.$lang->nologged.'</option><option value="both" selected="">'.$lang->both.'</option><option value="non">'.$lang->non.'</option></select>';
      break;
    case 'non':
      $inMenu='<select id="inmenu" name="inmenu"><option value="logged">'.$lang->logged.'</option><option value="nologged">'.$lang->nologged.'</option><option value="both">'.$lang->both.'</option><option value="non" selected="">'.$lang->non.'</option></select>';
      break;
  }
  if($result['membersonly']=='Yes')
  {
    $membersOnly='<input type="checkbox" id="membersonly" name="membersonly" checked="checked">';
  }else{
    $membersOnly='<input type="checkbox" id="membersonly" name="membersonly">';
  }
  $sql='SELECT id, name FROM '.$config->dbprefix.'templates';
  $result2=$mysqli->query($sql);
  $template='<select id="template" name="template">';
  while($row=$result2->fetch_array())
  {
    if($result['template']==$row['id'])
    {
      $template.='<option value="'.$row['id'].'" selected="">'.$row['name'].' ('.$lang->nr.$row['id'].')</option>';
    }else{
      $template.='<option value="'.$row['id'].'">'.$row['name'].' ('.$lang->nr.$row['id'].')</option>';
    }
  }
  $template.='</select>';
  if($result['active']=='Yes')
  {
    $active='<input type="checkbox" id="active" name="active" checked="checked">';
  }else{
    $active='<input type="checkbox" id="active" name="active">';
  }
  if(empty($result['subdomain']))
    $weburl=$config->weburl;
  else{
    $weburl=str_replace('://www.', '://', $config->weburl);
    $weburl=str_replace('://', '://'.$result['subdomain'].'.', $weburl);
  }
  $return.='
  <!-- TinyMCE -->
    <script type="text/javascript">
  	tinyMCE.init({
  		// General options
  		language : "'.$config->lang.'",
      entity_encoding: "raw", 
      mode : "exact",
      elements : "text",
      convert_urls : false,
      
      noneditable_regexp: /((\[\()(.+&*\/*\?*=*)(\)\]))/,
      valid_children : "+body[style]",
      
      apply_source_formatting : true,
      remove_linebreaks : false,
      
      inline_styles : false,
      theme : "advanced",
  		skin : "default",
      resizable: "true",
      plugins : "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,inlinepopups,autosave",
  
  		theme_advanced_buttons1 : "fullscreen,|,undo,redo,|,search,replace,|,formatselect",
      theme_advanced_buttons2 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,forecolor,|,bullist,numlist,|,outdent,indent,blockquote,|,link,unlink,anchor,image",
  		theme_advanced_buttons3 : "tablecontrols,|,removeformat,visualaid,visualchars,|,sub,sup,charmap,nonbreaking,hr,template,|,insertdate,inserttime",
  		//theme_advanced_buttons4 : "help,|,styleprops,cite,del,ins,pagebreak,|,restoredraft,cleanup,|,fontselect,|,fontsizeselect,",
  		
      theme_advanced_toolbar_location : "top",
  		theme_advanced_toolbar_align : "left",
  		theme_advanced_statusbar_location : "bottom",
  		theme_advanced_resizing : false,
  
  		// Example word content CSS (should be your site CSS) this one removes paragraph margins
  		//content_css : "css/word.css",
      theme_advanced_font_sizes: "10px,12px,13px,14px,16px,18px,20px",
      font_size_style_values : "10px,12px,13px,14px,16px,18px,20px",
  
  		// Drop lists for link/image/media/template dialogs
  		template_external_list_url : "lists/template_list.js",
  		external_image_list_url : "lists/image_list.js",
  		media_external_list_url : "lists/media_list.js",
      
      table_styles : "Default table style=default-table",   
  	});
    tinyMCE.execCommand("mceInsertContent",false,\'whatever\');
    </script>
  <!-- /TinyMCE -->
  
  <form method="post" id="editor_form">
    <fieldset>
    <input type="hidden" id="step" name="step" value="2">
    <input type="hidden" id="id" name="id" value="'.$result['id'].'">
    <input type="hidden" id="level" name="level" value="'.$result['level'].'">
    <input type="hidden" id="previewpagecaption" name="previewpagecaption" value="'.$lang->previewpagecaption.'">
    <input type="hidden" id="membersonlybefore" name="membersonlybefore" value="'.$result['membersonly'].'">

    <label for="id">'.$lang->pageid.':</label>                <span id="id">'.$result['id'].'</span><br />
    <label for="active">'.$lang->active.':</label>            '.$active.'<br />
    <label for="url">'.$weburl.$urlBeginning.'</label>     <input type="hidden" id="urlbeginning" name="urlbeginning" value="'.$urlBeginning.'">         <input type="text" id="url" name="urlend" value="'.@$urlEnd[0].'"><br />
    <label for="title">'.$lang->pagetitle.':</label>          <input type="text" id="title" name="title" value="'.$result['title'].'"><br />
    <label for="inmenu">'.$lang->inmenu.':</label>            '.$inMenu.'<br />
    <label for="menutitle">'.$lang->menutitle.':</label>      <input type="text" id="menutitle" name="menutitle" value="'.$result['menutitle'].'"><br />
    <label for="membersonly">'.$lang->membersonly.':</label>  '.$membersOnly.'<br />
    <label for="h1">'.$lang->h1.':</label>                    <input type="text" id="h1" name="h1" value="'.$result['h1'].'"><br />
    </fieldset>
                <input type="button" id="showmce" onmousedown="tinymce.get(\'text\').show();
                    document.getElementById(\'htmledit-p\').style.display = \'none\';
                    document.getElementById(\'htmledit-s\').style.display = \'none\';
                    document.getElementById(\'mceedit-p\').style.display = \'inline\';
                    document.getElementById(\'mceedit-s\').style.display = \'inline\';
                    document.getElementById(\'showmce\').style.display = \'none\';
                    document.getElementById(\'hidemce\').style.display = \'inline\';
                    document.getElementById(\'uploadedimages\').style.display = \'block\';
                    " value="'.$lang->showmce.'" style="display: none">
                
                <input type="button" id="hidemce" onmousedown="tinymce.get(\'text\').hide(); 
                    document.getElementById(\'htmledit-p\').style.display = \'inline\';
                    document.getElementById(\'htmledit-s\').style.display = \'inline\';
                    document.getElementById(\'mceedit-p\').style.display = \'none\';
                    document.getElementById(\'mceedit-s\').style.display = \'none\';
                    document.getElementById(\'showmce\').style.display = \'inline\';
                    document.getElementById(\'hidemce\').style.display = \'none\';
                    document.getElementById(\'uploadedimages\').style.display = \'none\';
                    " value="'.$lang->hidemce.'">
        
                <br />
                <textarea id="text" name="text" cols="100" rows="20">'.$result['text'].'</textarea><br />
    <fieldset>
    <label for="template">'.$lang->template.':</label>        '.$template.'<br />
    <label for="menuorder">'.$lang->menuorder.':</label>      <input type="number" id="menuorder" name="menuorder" value="'.$result['menuorder'].'" max="999"><br />
    <input id="htmledit-p" type="button" onclick="editor_instance = tinymce.EditorManager.getInstanceById(\'text\'); editor_instance.load(); document.getElementById(\'step\').value=\'preview\'; var e = document.getElementById(\'editor_form\'); e.action=\''.$config->weburl.'\'; e.target=\'_blank\';e.submit(); " value="'.$lang->preview.'" style="display: none">
    <input id="htmledit-s" type="button" onclick="editor_instance = tinymce.EditorManager.getInstanceById(\'text\'); editor_instance.load(); document.getElementById(\'step\').value=\'2\'; var e = document.getElementById(\'editor_form\'); e.action=\''.$config->editpageurl.'\'; e.target=\'_self\'; e.submit();" value="'.$lang->save.'" style="display: none">

    <input id="mceedit-p" type="button" onclick="document.getElementById(\'step\').value=\'preview\'; var e = document.getElementById(\'editor_form\'); e.action=\''.$config->weburl.'\'; e.target=\'_blank\';e.submit(); " value="'.$lang->preview.'">
    <input id="mceedit-s" type="button" onclick="document.getElementById(\'step\').value=\'2\'; var e = document.getElementById(\'editor_form\'); e.action=\''.$config->editpageurl.'\'; e.target=\'_self\'; e.submit();" value="'.$lang->save.'">
    </fieldset>
    <fieldset>
    <div id="uploaderwrapper">
      <div id="uploadedimages" ></div>
      <div id="uploadedfiles" ></div>
      <button type="button" id="toogleuploader" >'.$lang->pluploadtoogle.'</button>
      <div id="overuploader">
        <div id="uploader">
      		<p>'.$lang->pluploaderror.'</p>
      	</div><br />
        <button type="button" id="toogleuploaderlog" >'.$lang->pluploadlogtoogle.'</button>
        <textarea id="log" spellcheck="false" wrap="off"></textarea>
      </div>  
      <br />
    </div>
  </form>

<link rel="stylesheet" href="'.$config->jqueryuithemesource.'" type="text/css" />
<link rel="stylesheet" href="'.$config->pluginsfolder.'plupload/js/jquery.ui.plupload/css/jquery.ui.plupload.css" type="text/css" />


<script type="text/javascript" src="http://bp.yahooapis.com/2.4.21/browserplus-min.js"></script>

<script type="text/javascript" src="'.$config->pluginsfolder.'plupload/js/plupload.js"></script>
<script type="text/javascript" src="'.$config->pluginsfolder.'plupload/js/plupload.gears.js"></script>
<script type="text/javascript" src="'.$config->pluginsfolder.'plupload/js/plupload.silverlight.js"></script>
<script type="text/javascript" src="'.$config->pluginsfolder.'plupload/js/plupload.flash.js"></script>
<script type="text/javascript" src="'.$config->pluginsfolder.'plupload/js/plupload.browserplus.js"></script>
<script type="text/javascript" src="'.$config->pluginsfolder.'plupload/js/plupload.html4.js"></script>
<script type="text/javascript" src="'.$config->pluginsfolder.'plupload/js/plupload.html5.js"></script>
<script type="text/javascript" src="'.$config->pluginsfolder.'plupload/js/jquery.ui.plupload/jquery.ui.plupload.js"></script>';

if($_SESSION['lang']!=='en')
  $return.='<script type="text/javascript" src="'.$config->pluginsfolder.'plupload/i18n/'.$_SESSION['lang'].'.js"></script>';


$return.='
  <!--<script type="text/javascript" src="http://getfirebug.com/releases/lite/1.2/firebug-lite-compressed.js"></script>-->

<script type="text/javascript">
// Convert divs to queue widgets when the DOM is ready
$(function() {
	$( "#overuploader" ).hide();
  $( "#log" ).hide();

  function bytesToSize(bytes) {
    var sizes = [\'Bytes\', \'KB\', \'MB\', \'GB\', \'TB\'];
    if (bytes == 0) return \'n/a\';
    var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
    return (bytes / Math.pow(1024, i)).toFixed(1) + \' \' + sizes[i];
  };

  function log() {
		var str = "";
		plupload.each(arguments, function(arg) {
			var row = "";
			if (typeof(arg) != "string") {
				plupload.each(arg, function(value, key) {
					// Convert items in File objects to human readable form
					if (arg instanceof plupload.File) {
						// Convert status to human readable
						switch (value) {
							case plupload.QUEUED:
								value = \'QUEUED\';
								break;

							case plupload.UPLOADING:
								value = \'UPLOADING\';
								break;

							case plupload.FAILED:
								value = \'FAILED\';
								break;

							case plupload.DONE:
								value = \'DONE\';
								break;
						}
					}

					if (typeof(value) != "function") {
						row += (row ? \', \' : \'\') + key + \'=\' + value;
					}
				});

				str += row + " ";
			} else { 
				str += arg + " ";
			}
		});
		$(\'#log\').append(str + "\n");
	};

  $( "#toogleuploader" ).click(function() {
      $( "#overuploader" ).toggle( "slide", {"direction" : "up"} );
  });
  
  $( "#toogleuploaderlog" ).click(function() {
      $( "#log" ).toggle( "slide", {"direction" : "up"} );
  });

  $("#uploader").plupload({
		// General settings
		runtimes : \'html5,flash,browserplus,silverlight,gears,html4\',
		url : \''.$config->editpageurl.'upload\',
		max_file_size : \''.$config->maxfilesize.'\',
		max_file_count: '.$config->maxfilecount.', // user can add no more then 20 files at a time
		chunk_size : \''.$config->maxchunksize.'\',
		unique_names : false,
		multiple_queues : true,

		// Resize images on clientside if we can
		//resize : {width : 320, height : 240, quality : 90},
		
		// Rename files by clicking on their titles
		rename: true,
		
		// Sort files
		sortable: true,

		// Specify what files to browse for
		/*
    filters : [
			{title : "Image files", extensions : "jpg,jpeg,png,bmp,tiff,gif"},
			{title : "Zip files", extensions : "zip,avi"}
		],
    */
		// Flash settings
		flash_swf_url : \''.$config->pluginsfolder.'plupload/js/plupload.flash.swf\',

		// Silverlight settings
		silverlight_xap_url : \''.$config->pluginsfolder.'plupload/js/plupload.silverlight.xap\',
    
    preinit : {
			Init: function(up, info) {
				log(\'[Init]\', \'Info:\', info, \'Features:\', up.features);
			},

			UploadFile: function(up, file) {
				log(\'[UploadFile]\', file);
        up.settings.url = \''.$config->editpageurl.'upload/?caller=edit_article\';
				// You can override settings before the file is uploaded
				// up.settings.url = \'upload.php?id=\' + file.id;
				// up.settings.multipart_params = {param1 : \'value1\', param2 : \'value2\'};
			}
		},

		// Post init events, bound after the internal events
		init : {
			Refresh: function(up) {
				// Called when upload shim is moved
				log(\'[Refresh]\');
			},

			StateChanged: function(up) {
				// Called when the state of the queue is changed
				log(\'[StateChanged]\', up.state == plupload.STARTED ? "STARTED" : "STOPPED");
			},

			QueueChanged: function(up) {
				// Called when the files in queue are changed by adding/removing files
				log(\'[QueueChanged]\');
			},

			UploadProgress: function(up, file) {
				// Called while a file is being uploaded
				log(\'[UploadProgress]\', \'File:\', file, "Total:", up.total);
			},

			FilesAdded: function(up, files) {
				// Callced when files are added to queue
				log(\'[FilesAdded]\');

				plupload.each(files, function(file) {
					log(\'  File:\', file);
				});
			},
      
      FilesRemoved: function(up, files) {
				// Called when files where removed from queue
				log(\'[FilesRemoved]\');
				plupload.each(files, function(file) {
					log(\'  File:\', file);
				});
			},
      
      FileUploaded: function(up, file, info) {
				// Called when a file has finished uploading
				log(\'[FileUploaded] File:\', file, "Info:", info);
        var obj = $.parseJSON(info.response);
        file[\'name\']= obj.name;   //this will rename name for name recieved from serverside.php
        var imageName = file[\'name\'];  
        var extension = imageName.substr( (imageName.lastIndexOf(".") +1) );
          switch(extension){
            case "jpg":
            case "jpeg":
            case "png":
            case "bmp":
            case "tiff":
            case "gif":
              var newimage = new Image();
              newimage.src = "'.$config->imagesfolder.'"+imageName; 
              newimage.onload = function()
              {
                var width = this.naturalWidth;
                var height = this.naturalHeight;
                if (width>height)
                {
                  var imgclass="imagebutton landscape";
                }else{
                  var imgclass="imagebutton portrait";
                }
                var data = "'.$config->imagesfolder.'"+imageName;                 /*document.getElementById(\'imagelink\').value*/
                //alert(data);
                data2= "<img src=\'"+data+"\'>";
                data = "<img src=\'"+data+"\' width=\'"+width+"\' height=\'"+height+"\'>";
                data = "<div class=\"buttonwrapper\"><div class=\"imagesize\">"+width+" x "+height+"</div><button  type=\"button\" class=\""+imgclass+"\" onclick=\"tinyMCE.get(\'text\').execCommand(\'mceInsertContent\',false, $(this).attr(\'data\')   );\" data=\""+data+"\">"+data2+"</button></div>";
                $(\'#uploadedimages\').append(data);
              }
              break;
            
            default:
              var data = "'.$config->filesfolder.'"+imageName;                 /*document.getElementById(\'imagelink\').value*/
              var fileSize = bytesToSize(file[\'size\']);
                data = "<a href=\'"+data+"\' title=\'"+imageName+"\'>"+imageName+"</a> [."+extension+", "+fileSize+"]";
                data = "<div class=\"buttonwrapper\"><div class=\"imagesize\">"+fileSize+"</div>          <button type=\"button\" class=\"otherfilebutton button_"+extension+"\" onclick=\"tinyMCE.get(\'text\').execCommand(\'mceInsertContent\',false, $(this).attr(\'data\')   );\" data=\""+data+"\"></button><div class=\"filename\">"+imageName+"</div></div>";
                $(\'#uploadedfiles\').append(data);
          }
      },

			ChunkUploaded: function(up, file, info) {
				// Called when a file chunk has finished uploading
				log(\'[ChunkUploaded] File:\', file, "Info:", info);
			},

			Error: function(up, args) {
				// Called when a error has occured
				log(\'[error] \', args);
			}
		}
	});

	// Client side form validation
	$(\'form\').submit(function(e) {
        var uploader = $(\'#uploader\').plupload(\'getUploader\');
        // Files in queue upload them first
        if (uploader.files.length > 0) {
            // When all files are uploaded submit form
            uploader.bind(\'StateChanged\', function() {
                if (uploader.files.length === (uploader.total.uploaded + uploader.total.failed)) {
                    $(\'form\')[0].submit();
                }
            });
            uploader.start();
        } else
            alert(\'You must at least upload one file.\');
        return false;
    });
});
</script>';
  
}else if((isset($_POST['step']))AND($_POST['step']=='2')){
  $return.='<div id="editor"><h3>'.$lang->pagesaved.'</h3>'.$lang->anotherpagetoedit.'</div>';
  if(isset($_POST['title'],$_POST['inmenu'],$_POST['menutitle'],$_POST['urlbeginning'],$_POST['urlend'],$_POST['h1'],$_POST['text'],$_POST['template'],$_POST['menuorder']))  //nemontroluje 'active' ani 'membersonly' lebo to su check boxes a tie ked niesu zaskrtnute, tak sa neodosielaju
  { 
    if(isset($_POST['active'])AND($_POST['active']=='on')){
      $sqlData['active']='Yes';
    }else{
      $sqlData['active']='No';
    }
    if(isset($_POST['membersonly'])AND($_POST['membersonly']=='on'))
    {
      $sqlData['membersonly']='Yes';
      if(!isset($_SESSION['permissions'][$_POST['id']]))
      {
        if($_POST['membersonlybefore']=='No')
        {
          $subSql='INSERT INTO '.$config->dbprefix.'permissions (userid, permission) VALUES ("'.$mysqli->real_escape_string($_SESSION['userid']).'", "'.$mysqli->real_escape_string($_POST['id']).'" )';
          $mysqli->query($subSql);
          $class->reloadPermissions($mysqli, $config);
        }
      }
    }else{
      $sqlData['membersonly']='No';
    }
    
    if( (empty($_POST['urlend']))AND($_POST['level']!=0) )
    {
      $_POST['urlend']=$this->cleanUrl($_POST['title']);
    }else{
      $_POST['urlend']=$this->cleanUrl($_POST['urlend']);
    }
    
    
    
    $sqlData['text']=$mysqli->real_escape_string(stripslashes($_POST['text']));
    $sqlData['text']=str_replace('[&lt;', '[<', $sqlData['text']);
    $sqlData['text']=str_replace('&gt;]', '>]', $sqlData['text']);
    $sqlData['text']=str_replace($config->weburl, '[{weburl}]', $sqlData['text']);
    
    $sqlData['title']=$mysqli->real_escape_string(stripslashes($_POST['title']));
    $sqlData['url']=$mysqli->real_escape_string($_POST['urlbeginning'].$_POST['urlend']);
    $sqlData['urlpart']=$mysqli->real_escape_string($_POST['urlend']);
    $sqlData['inmenu']=$mysqli->real_escape_string($_POST['inmenu']);
    $sqlData['menutitle']=$mysqli->real_escape_string(stripslashes($_POST['menutitle']));
    $sqlData['h1']=$mysqli->real_escape_string(stripslashes($_POST['h1']));
    $sqlData['template']=$mysqli->real_escape_string($_POST['template']);
    $sqlData['menuorder']=$mysqli->real_escape_string($_POST['menuorder']);
    $sqlData['id']=$mysqli->real_escape_string($_POST['id']);
      
    $sql="UPDATE ".$config->dbprefix."pages SET 
    `url`='".$sqlData['url']."', 
    `url_part`='".$sqlData['urlpart']."', 
    `title`='".$sqlData['title']."', 
    `inmenu`='".$sqlData['inmenu']."', 
    `menutitle`='".$sqlData['menutitle']."', 
    `membersonly`='".$sqlData['membersonly']."', 
    `h1`='".$sqlData['h1']."', 
    `text`='".$sqlData['text']."', 
    `template`='".$sqlData['template']."', 
    `menuorder`='".$sqlData['menuorder']."', 
    `active`='".$sqlData['active']."' 
    WHERE `id`='".$sqlData['id']."' LIMIT 1";
    $result=$mysqli->query($sql);
  } 
}else{
  $return.='<div id="editor"><h3>'.$lang->pagetoedit.'</h3></div>';
}
?>