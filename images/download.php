<?php
// Force download of image file specified in URL query string and which
// is in the same directory as the download.php script.
/*
if(empty($_GET['img'])) {
   header("HTTP/1.0 404 Not Found");
	echo $filename;

   return;
}
*/
echo '12345';
$basename = basename($_GET['img']);
echo $basename;
$filename = __DIR__ . "\\" . $basename; // don't accept other directories
echo $filename;
$mime = ($mime = getimagesize($filename)) ? $mime['mime'] : $mime;
$size = filesize($filename);
$fp   = fopen($filename, "rb");
if (!($mime && $size && $fp)) {
  // Error.
  return;
}

header("Content-type: " . $mime);
header("Content-Length: " . $size);
// NOTE: Possible header injection via $basename
header("Content-Disposition: attachment; filename=" . $basename);
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
fpassthru($fp);
?>