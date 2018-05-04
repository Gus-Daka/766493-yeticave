<?php 
function renderTemplate($tpath, $tdata) {
 	$content = '';

 if (file_exists($tpath)) {
  	ob_start();
  	extract($tdata);
  	require($tpath);
  	$content = ob_get_clean();
 }

 return $content;
}

function rurNumberFormat(int $price) {
    return number_format($price, 0, '.', ' ') . '<b class="rub">р</b>';
}
?>