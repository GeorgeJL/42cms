<?php
$galleryPageUrl=$config->weburl.'members/addimages/';
if(isset($_POST['image']))
{
  //$return='<pre>'.print_r($_POST, true).'</pre>';
  $galleryId=$mysqli->real_escape_string($_POST['galleryId']);
  $addedBy=$mysqli->real_escape_string($_SESSION['username']);
  $addedById=$mysqli->real_escape_string($_SESSION['userid']);
  $sql='';
  foreach($_POST['image'] as $key=>$value)
  {
    $file=$mysqli->real_escape_string($value);
    $title=$mysqli->real_escape_string($_POST['name'][$key]);
    $author=$mysqli->real_escape_string($_POST['author'][$key]);
    $description=$mysqli->real_escape_string($_POST['description'][$key]);
    if(empty($sql))
    {
      $sql.="INSERT INTO ".$config->dbprefix."images (galleryid, file, author, addedby, addedbyid, addeddate, title, description) VALUES ";
    }else{
      $sql.=",";
    }
    $sql.="('".$galleryId."', '".$file."', '".$author."', '".$addedBy."', '".$addedById."', now(), '".$title."', '".$description."' ) ";
  }
  $result=$mysqli->query($sql);
  if(($mysqli->errno)=='0')
    $return.=str_replace('%count%', $key+1, $lang->insertedimages).'<br /><br />'; 
  else
    $return=$class->errorLog('14', $GLOBALS);
  
}else if(isset($_POST['galleryId'])){
  $sql="SELECT name, description FROM ".$config->dbprefix."galleries WHERE id=".$mysqli->real_escape_string($_POST['galleryId']);
  $result=$mysqli->query($sql);
  $row=$result->fetch_array();
  $return.='<h3>'.$row['name'].'</h3><p>'.$row['description'].'</p>';
  $galleryFolder=$config->weburl.'gallery/'.$_POST['galleryId'].'/';
  $return.= '
  <style type="text/css">
  #log{width: 99%; margin-bottom: 10px; height: 150px; font-size: 11px; background-color: black; color: #ccc; border-color: #5f0}
  .landscape {position: relative; max-width: 200px;} 
  .portrait {position: relative; max-height: 200px;}
  .uploaderwrapper{position: relative; padding: 25px 10px;}
  .uploadedimages{position: relative; padding: 25px 10px;}

  .imagewrapper{position: relative; padding: 10px 10px 0px 10px; clear:both; border: 1px solid #3c0; margin: 10px;}  
  .subimagewrapper{position: relative; width: 220px;float: left;} 
  label{position; relative; width: 200px; color: #ccc}  
      
  </style>
  
  <script type="text/javascript" src="'.$config->jquerysource.'"></script>
  <script type="text/javascript" src="'.$config->jqueryuisource.'"></script>
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
  	//$( "#overuploader" ).hide();
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
  		url : \''.$galleryPageUrl.'\',
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
  		
      filters : [
  			{title : "Image files", extensions : "jpg,jpeg,png,bmp,tiff,gif"}
  			//{title : "Zip files", extensions : "zip,avi"}
  		],
      
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
          up.settings.url = \''.$galleryPageUrl.'upload/?gid='.$_POST['galleryId'].'\';
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
          var justName = imageName.replace(/\.[^/.]+$/, "")
          
          var extension = imageName.substr( (imageName.lastIndexOf(".") +1) );
          var newimage = new Image();
          newimage.src = "'.$galleryFolder.'"+imageName; 
          newimage.onload = function()
          {
            var width = this.naturalWidth;
            var height = this.naturalHeight;
            if (width>height)
            {
              var imgclass="landscape";
            }else{
              var imgclass="portrait";
            }
            var data = "'.$galleryFolder.'"+imageName;                 /*document.getElementById(\'imagelink\').value*/
            //alert(data);
            data2= "<div class=\"imagewrapper\"><div class=\"subimagewrapper\"><img src=\'"+data+"\' class=\""+imgclass+"\"></div>";
            data=data2+"<input type=\"hidden\" name=\"image[]\" value=\""+imageName+"\"><label for=\"name[]\">'.$lang->imagename.':</label><input class=\"imagename\" type=\"text\" name=\"name[]\" value=\""+justName+"\"><br><label for=\"description[]\">'.$lang->imagedescription.':</label><input class=\"imagedescription\" type=\"text\" name=\"description[]\" value=\"\"><br><label for=\"author[]\">'.$lang->imageauthor.':</label><input class=\"author\" type=\"text\" name=\"author[]\" value=\"\"><br style=\"clear: both\"></div>";
            $(\'#uploadedimages\').append(data);
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
              alert(\''.$lang->zerofiles.'\');
          return false;
      });
  });
  </script>
  
  
  
  
  <div id="uploaderwrapper">
    <form method="post">
      <input type="hidden" value="'.$_POST['galleryId'].'" name="galleryId"><br />
      <div id="uploadedimages"  >
      
      </div>
      <input type="submit" value="'.$lang->submit.'" name="submit">
    </form>  
    
    
    <div id="overuploader">
      <div id="uploader">
    		<p>'.$lang->pluploaderror.'</p>
    	</div><br />
      <button type="button" id="toogleuploaderlog" >'.$lang->pluploadlogtoogle.'</button>
      <textarea id="log" spellcheck="false" wrap="off"></textarea>
    </div>  
    <br />
  </div>
  
  
  
  
  
  ';
}else{
  $sql="SELECT id, name, LEFT(description, '15') FROM ".$config->dbprefix."galleries WHERE active='Yes'";
  $result=$mysqli->query($sql);
  if($result->num_rows>0)
  {
    $return='<form method="post"><select id="galleryId" name="galleryId">';
    while($row=$result->fetch_array())
    {
      //$return.=$row['id'].'-'.$row['name'].'-'.$row['description'].'<br />';
      $return.='<option value="'.$row['id'].'">'.$row['id'].' - '.$row['name'].' - '.$row["LEFT(description, '15')"].'...</option>';
      
    }
    $return.='</select><br /><input type="submit" name="submit" value="'.$lang->submit.'"></form>';
  }else{
    $return.=$lang->nogallery;
  }
}  




?>