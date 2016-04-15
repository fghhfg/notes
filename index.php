<?PHP

$content = "";

if(isset($_POST["content"])) {
    $handle = fopen ("data", "w");
    $content = fwrite($handle, stripslashes($_POST["content"]) );
    fclose ($handle);
	die(json_encode(
		array(
			"saved" => true
		)
	));
}

if(file_exists("data")) {
    $handle = fopen ("data", "r");
    if(filesize("data")>0)
        $content = fread($handle, filesize("data"));
    fclose ($handle);
}

?><!DOCTYPE html>
<html lang="en">
	<head>
		<title>aditu.de notes</title>
		<meta charset='utf-8'>
		<meta http-equiv='X-UA-Compatible' content='IE=edge'>
		<meta name='viewport' content='width=device-width, initial-scale=1'>
		<link href='favicon.ico' rel='icon'>
		<link rel="stylesheet" href="fonts/opensans_light_macroman/stylesheet.css"/>
		<link rel="stylesheet" href="style.css"/>
		
	</head>
	<body>

		<textarea class="editable">
			<?PHP echo $content; ?>
		</textarea>

		<script src="jquery-2.2.3.min.js"></script>
		<script src="tinymce/tinymce.min.js"></script>
		<script>

			tinymce.init({
			  selector: 'textarea',
			  menubar: false,
			  plugins: [
				'advlist autolink lists link image charmap print preview anchor',
				'searchreplace visualblocks code fullscreen',
				'insertdatetime media table contextmenu paste code',
				'textcolor',
				'fullscreen'
			  ],
			  toolbar: 'savebutton | formatselect | bold italic strikethrough forecolor | alignleft aligncenter alignright alignjustify | bullist numlist | link image code ',
			  content_css: [
				'codepen.min.css'
			  ],
			  
			  setup: function (editor) {
				editor.shortcuts.add('ctrl+s', '', function() {
					$('.mce-i-save').click();
				});
				editor.shortcuts.add('alt+s', '', function() {
					$('.mce-i-save').click();
				});


				editor.on('init', function(e) {
					setTimeout(function() {
						editor.execCommand('mceFullScreen');
					  
					}, 100);
				});

				editor.addButton('savebutton', {
				  text: false,
				  icon: 'mce-ico mce-i-save',
				  onclick: function (a) {
					var resetButton = function() {
						setTimeout(function() {
							a.target.style.color = "#000000";
						}, 5000);
					};
					
					$.ajax({
							method: "POST",
							data: { content : editor.getContent() },
							url: window.location.href, 
							success: function(result) {
								a.target.style.color = "#50AB3A";
								resetButton();
							},
							error: function() {
								a.target.style.color = "#FF0000";
								resetButton();
							}
						});
				  }
				});
			  },
			});
		</script>

	</body>
</html>
