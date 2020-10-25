<?
// 二重起動チェック
function dup_check($key) {
	global $fp_lock;

	umask(0);
	$filename = "/tmp/$key";
	if (($fp_lock = fopen($filename, 'w')) == false)
		return false;

	return flock($fp_lock, LOCK_EX | LOCK_NB);
}
?>