<?php 
function renderTemplate($templatePath, $templateData) {
 	$content = '';
 	extract($templateData);

 if (file_exists($templatePath)) {
  	ob_start();
  	require($templatePath);
  	$content = ob_get_contents();
  	ob_end_clean();
 }

 return $content;
}
?>