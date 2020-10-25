<?
$CRONTAB_NAME = "/var/spool/cron/postgres";

/* ---------------------------------------------------
' crontab読込み
'----------------------------------------------------*/
function read_crontab($fname, &$cron_list){

	if (!$fp = fopen($fname, "r"))
		return 0;
	for ($cnt=0 ; !feof($fp) ; $cnt++) {
		$ln = fgets($fp, 4096);
		if ( substr($ln,0,1) != "#" && trim($ln) != "" ) {
			$cron_list[$cnt] = trim($ln);
		}
	}
	fclose($fp);

	return;
}

/* ---------------------------------------------------
' crontabチェック
'----------------------------------------------------*/
function check_cron($cron_list, $sh_name, &$param){

	foreach ($cron_list as $ln) {
		$pos = strpos($ln, $sh_name);
		if ($pos > 0) {
			get_cron_param($ln, $param);
			return true;
		}
	}

	return 0;
}
/* ---------------------------------------------------
' crontab分解
'----------------------------------------------------*/
function get_cron_param($ln, &$param){

// param = * * * * * /usr/local/apache/htdocs/sh/xxxx.sh
	$ln = trim($ln);
	for ($i=0; $i<6 ;$i++) {
		$pos = strpos($ln, " ");
		$param[$i] = substr($ln, 0, $pos);
		$ln = trim(substr($ln, $pos));
	}
	$param[5] = $ln;

	return;
}

/* ---------------------------------------------------
' crontab書込み（内容変更）
'----------------------------------------------------*/
function write_crontab($fname, $schedule, $sh_name){

	$cron_list = array();
	// 内容取得
	$fp = fopen($fname, "r");
	for ($cnt=0 ; !feof($fp) ; $cnt++) {
		$ln = fgets($fp, 4096);
		$cron_list[$cnt] = trim($ln);
	}
	fclose($fp);

	// 書込み
	$fp = fopen($fname, "w+");
	flock($fp, 2);
	foreach ($cron_list as $ln) {
		if ( strpos($ln, $sh_name) > 0 && substr($ln, 0, 1) != "#" ) {
			$param = array();
			get_cron_param($ln, $param);
			fputs($fp, $schedule." ".$param[5]."\n");
		}else{
			fputs($fp, $ln."\n");
		}
	}

	fclose($fp);

	return;
}
