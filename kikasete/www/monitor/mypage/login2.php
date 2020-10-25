<?
$top = './..';
$inc = "$top/../inc";
include("$inc/login_log.php");
$inc = "$top/inc";
$img = "$top/image";
include("$inc/mn_header.php");
include("$inc/mn_regist.php");
include("$inc/mn_chara.php");

function set_session($monitor_id, $type, $chara_name) {
	session_start();
	$_SESSION['ss_monitor_id'] = $monitor_id;
	$_SESSION['ss_monitor_type'] = $type;

	if ($chara_name == '')
		$chara_name = 'ぽいきゃら';
	$_SESSION['ss_chara_name'] = $chara_name;
	$_SESSION['ss_chara_image'] = get_chara_image($monitor_id);
}
if (isset($mail_addr)) {
	setcookie('ck_monitor_mail_addr', $mail_addr, COOKIE_EXPIRE, '/');
	$_COOKIE['ck_monitor_mail_addr'] = $mail_addr;

	if ($mail_addr == '') {
		redirect("login_error_main.php");
	}
	if ($password == '') {
		redirect("login_error_main.php");
	}
} else {
	redirect("login_error_main.php");
}

if (!$msg) {
	$sql = "SELECT mn_monitor_id,mn_type,mn_password,mp_chara_name"
			. " FROM t_monitor LEFT JOIN t_monitor_point ON mn_monitor_id=mp_monitor_id"
			. " WHERE mn_mail_addr_low=" . sql_char(strtolower($mail_addr)) . " AND mn_status<>9";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		redirect("login_error_main.php");
	} else {
		$fetch = pg_fetch_object($result, 0);
		if ($fetch->mn_password != $password) {
			redirect("login_error_main.php");
		} else {
			set_session($fetch->mn_monitor_id, $fetch->mn_type, $fetch->mp_chara_name);
			login_log(1, $mail_addr, $password, $fetch->mn_monitor_id);
			if ($url == '')
				$url = 'm_index.php';
			// きかせて･netモニターがアンケートに回答した場合、回答終了後はログインしたモニターのきかせて･netMyページに戻る 2006/03/30 BTI
			//			redirect($ssl ? https_url($url) : http_url($url));
			if($ok) {
?>
				<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Frameset//EN">
				<HTML>
				<HEAD>
					<TITLE>アンケートでポイントを[きかせて・net]モニターサイト</TITLE>
					<META http-equiv=Content-Type content="text/html; charset=EUC-JP">
				</HTML>
				<BODY onload="javascript:window.open('<?=$url?>', '_blank', 'resizable=yes, scrollbars=yes, toolbar=no, menubar=no');">

				</BODY>
<?
			}else {
				redirect("m_index_main.php");
			}
// 修正 END
		}
	}
}
login_log(1, $mail_addr, $password, null);
?>