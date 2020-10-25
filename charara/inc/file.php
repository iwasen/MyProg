<?
/******************************************************
' System :「きゃららFactory」インクルードファイル
' Content:ファイル処理関数
'******************************************************/

define('DEFAULT_PATH', '/var/charara');

function save_photo_file(&$data, $filename, $mime_type = '') {
	if (($path = $_ENV['CHARARA_FILE']) == '')
		$path = DEFAULT_PATH;

	if ($fp = fopen("$path/photo/$filename", 'w')) {
		fwrite($fp, $data);
		fclose($fp);

		if (($path = $_ENV['CHARARA_FILE_BACKUP']) != '') {
			if ($fp = fopen("$path/photo/$filename", 'w')) {
				fwrite($fp, $data);
				fclose($fp);
			}
		}
		return true;
	} else
		return false;
}

function save_nigaoe_file($srcfile, $filename, $mime_type = '') {
	if (($path = $_ENV['CHARARA_FILE']) == '')
		$path = DEFAULT_PATH;

	if (copy($srcfile, "$path/nigaoe/$filename")) {
		if (($path = $_ENV['CHARARA_FILE_BACKUP']) != '') {
			copy($srcfile, "$path/nigaoe/$filename");
		}
		return true;
	} else
		return false;
}
/*
function output_photo_file($filename) {
	if (($path = $_ENV['CHARARA_FILE']) == '')
		$path = '/var/charara';

	header('Cache-control: no-cache');
	header('Content-type: ' . get_mime_type($filename));
	header("Content-disposition: inline; filename=\"$filename\"");
	readfile("$path/photo/$filename");
	exit;
}

function output_nigaoe_file($filename) {
	if (($path = $_ENV['CHARARA_FILE']) == '')
		$path = '/var/charara';

	header('Cache-control: no-cache');
	header('Content-type: ' . get_mime_type($filename));
	header("Content-disposition: inline; filename=\"$filename\"");
	readfile("$path/portrait/$filename");
	exit;
}

function get_mime_type($filename) {
	$path_parts = pathinfo($filename);
	switch (strtolower($path_parts['extension'])) {
	case 'gif':
		return 'image/gif';
	case 'jpg':
	case 'jpeg':
		return 'image/jpeg';
	case 'png':
		return 'image/png';
	case 'bmp':
		return 'image/bmp';
	default:
		return 'application/octet-stream';
	}
}
*/
?>