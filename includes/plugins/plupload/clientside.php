<?php

$return='<style type="text/css">
	/*
  body {
		font-family:Verdana, Geneva, sans-serif;
		font-size:13px;
		color:#333;
		background:url(../bg.jpg);
	}*/
</style>
<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/themes/base/jquery-ui.css" type="text/css" />
<!--<link rel="stylesheet" href="'.$config->weburl.'includes/plugins/plupload/js/jquery.plupload.queue/css/jquery.plupload.queue.css" type="text/css" />-->
<link rel="stylesheet" href="'.$config->weburl.'includes/plugins/plupload/js/jquery.ui.plupload/css/jquery.ui.plupload.css" type="text/css" />

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/jquery-ui.min.js"></script>
<script type="text/javascript" src="http://bp.yahooapis.com/2.4.21/browserplus-min.js"></script>


<script type="text/javascript" src="'.$config->weburl.'includes/plugins/plupload/js/plupload.js"></script>
<script type="text/javascript" src="'.$config->weburl.'includes/plugins/plupload/js/plupload.gears.js"></script>
<script type="text/javascript" src="'.$config->weburl.'includes/plugins/plupload/js/plupload.silverlight.js"></script>
<script type="text/javascript" src="'.$config->weburl.'includes/plugins/plupload/js/plupload.flash.js"></script>
<script type="text/javascript" src="'.$config->weburl.'includes/plugins/plupload/js/plupload.browserplus.js"></script>
<script type="text/javascript" src="'.$config->weburl.'includes/plugins/plupload/js/plupload.html4.js"></script>
<script type="text/javascript" src="'.$config->weburl.'includes/plugins/plupload/js/jquery.ui.plupload/jquery.ui.plupload.js"></script>
<script type="text/javascript" src="'.$config->weburl.'includes/plugins/plupload/js/plupload.html5.js"></script>

<script type="text/javascript" src="'.$config->weburl.'includes/plugins/plupload/js/plupload.full.js"></script>
<!--<script type="text/javascript" src="'.$config->weburl.'includes/plugins/plupload/js/jquery.plupload.queue/jquery.plupload.queue.js"></script>-->
<script type="text/javascript" src="'.$config->weburl.'includes/plugins/plupload/js/jquery.ui.plupload/jquery.ui.plupload.js"></script>





</head>
<body>

<h1>jQuery UI Widget</h1>

<p>You can see this example with different themes on the <a href="http://plupload.com/example_jquery_ui.php">www.plupload.com</a> website.</p>

<form  method="post" action="dump.php">
	<textarea id="log" style="width: 100%; height: 150px; font-size: 11px" spellcheck="false" wrap="off"></textarea>
  <input type="text" id="imagelink">
 
  <div id="uploader">
		<p>You browser doesn\'t have Flash, Silverlight, Gears, BrowserPlus or HTML5 support.</p>
	</div>
  <input type="submit" value="Submit!" name="submit">
</form>
<script type="text/javascript">
// Convert divs to queue widgets when the DOM is ready
$(function() {
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
	}

	$("#uploader").pluploadQueue({
		// General settings
		runtimes : \'flash,gears,flash,silverlight,browserplus,html5\',
		url : \'upload.php\',
		max_file_size : \'10mb\',
		chunk_size : \'1mb\',
		unique_names : true,

		// Resize images on clientside if we can
		resize : {width : 320, height : 240, quality : 90},

		// Specify what files to browse for
		filters : [
			{title : "Image files", extensions : "jpg,gif,png"},
			{title : "Zip files", extensions : "zip"}
		],

		// Flash settings
		flash_swf_url : \''.$config->weburl.'includes/plugins/plupload/js/plupload.flash.swf\',

		// Silverlight settings
		silverlight_xap_url : \''.$config->weburl.'includes/plugins/plupload/js/plupload.silverlight.xap\',

		// PreInit events, bound before any internal events
		preinit : {
			Init: function(up, info) {
				log(\'[Init]\', \'Info:\', info, \'Features:\', up.features);
			},

			UploadFile: function(up, file) {
				log(\'[UploadFile]\', file);

				// You can override settings before the file is uploaded
				// up.settings.url = \''.$config->weburl.'includes/plugins/plupload/upload.php?id=\' + file.id;
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
});
</script>

    ';

?>