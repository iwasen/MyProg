<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:サブエージェント登録
'******************************************************/

$top = '..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/get_form.php");
include("$inc/check.php");

// 登録済みチェック
function check_regist($agent_id, $mail_addr) {
	$sql = "SELECT ag_mail_addr FROM t_agent WHERE ag_mail_addr_low=". sql_char(strtolower($mail_addr)) . " AND ag_status<>9";
	if ($agent_id)
		$sql .= " AND ag_agent_id<>$agent_id";
	$result = db_exec($sql);
	return pg_numrows($result);
}

//入力チェック
$name1 = get_name($name1);
if ($name1 == '')
	$msg[] = 'お名前（漢字）の姓が入力されていないようです。';

$name2 = get_name($name2);
if ($name2 == '')
	$msg[] = 'お名前（漢字）の名が入力されていないようです。';

$name1_kana = get_name_kana($name1_kana);
if ($name1_kana == '')
	$msg[] = 'お名前（フリガナ）の姓が入力されていないようです。';
elseif (!check_kana($name1_kana))
	$msg[] = 'お名前（フリガナ）の姓はカタカナで入力してください。';

$name2_kana = get_name_kana($name2_kana);
if ($name2_kana == '')
	$msg[] = 'お名前（フリガナ）の名が入力されていないようです。';
elseif (!check_kana($name2_kana))
	$msg[] = 'お名前（フリガナ）の名はカタカナで入力してください。';

$mail_addr = get_mail_addr($mail_addr);
if (!check_mail_addr($mail_addr))
	$msg[] = "メールアドレスが正しく入力されていないようです。";

$password = get_password($password);
if (strlen($password) < 6)
	$msg[] = 'パスワードは６文字以上を入力してください。';
elseif (strlen($password) > 20)
	$msg[] = 'パスワードは２０文字以内で入力してください。';
elseif (!check_password($password))
	$msg[] = 'パスワードは半角英数字で入力してください。';

$birthday_ok = true;
$birthday_y = get_birthday($birthday_y);
if ($birthday_y == '') {
	$msg[] = '生年月日の年が入力されていないようです。';
	$birthday_ok = false;
} elseif (!check_num($birthday_y, 1900, date('Y'))) {
	$msg[] = '生年月日の年が正しく入力されていないようです。';
	$birthday_ok = false;
}

$birthday_m = get_birthday($birthday_m);
if ($birthday_m == '') {
	$msg[] = '生年月日の月が入力されていないようです。';
	$birthday_ok = false;
} elseif (!check_num($birthday_m, 1, 12)) {
	$msg[] = '生年月日の月が正しく入力されていないようです。';
	$birthday_ok = false;
}

$birthday_d = get_birthday($birthday_d);
if ($birthday_d == ''){
	$msg[] = '生年月日の日が入力されていないようです。';
	$birthday_ok = false;
} elseif (!check_num($birthday_d, 1, 31)) {
	$msg[] = '生年月日の日が正しく入力されていないようです。';
	$birthday_ok = false;
}

if ($birthday_ok && !checkdate($birthday_m, $birthday_d, $birthday_y))
	$msg[] = '生年月日が正しく入力されていないようです。';

if (!$msg) {
	if (check_regist($subagent_id, $mail_addr))
		$msg[] = '入力したメールアドレスは既に登録されています。';
}

$back = 'history.back()';

//仮登録情報保存
if (!$msg) {
	switch ($next_action) {
	case 'new':
		$sql = "INSERT INTO t_agent (ag_status,ag_type,ag_name1,ag_name2,ag_name1_kana,ag_name2_kana,ag_birthday,ag_sex,ag_mail_addr,ag_mail_addr_low,ag_password,ag_kinmu_name,ag_kinmu_unit,ag_kinmu_post,ag_agent_unit,ag_disp_flag,ag_agent_disc,ag_main_agent) VALUES (0,2,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)";
		$sql = sprintf($sql,
			sql_char($name1),
			sql_char($name2),
			sql_char($name1_kana),
			sql_char($name2_kana),
			sql_date2($birthday_y,$birthday_m,$birthday_d),
			sql_number($sex),
			sql_char($mail_addr),
			sql_char(strtolower($mail_addr)),
			sql_char($password),
			sql_char($kinmu_name),
			sql_char($kinmu_unit),
			sql_char($kinmu_post),
			sql_char($agent_unit),
			sql_bool('f'),
			sql_number($agent_disc),
			sql_number($agent_id));
		db_exec($sql);

		$msg_ok = 'サブエージェントを登録しました。';
		$back = "location.href='list.php'";
		break;
	case 'update':
		$sql = "UPDATE t_agent SET ag_name1=%s,ag_name2=%s,ag_name1_kana=%s,ag_name2_kana=%s,ag_birthday=%s,ag_sex=%s,ag_mail_addr=%s,ag_mail_addr_low=%s,ag_password=%s,ag_kinmu_name=%s,ag_kinmu_unit=%s,ag_kinmu_post=%s,ag_agent_unit=%s,ag_agent_disc=%s,ag_update_date=%s WHERE ag_agent_id=$subagent_id";
		$sql = sprintf($sql,
			sql_char($name1),
			sql_char($name2),
			sql_char($name1_kana),
			sql_char($name2_kana),
			sql_date2($birthday_y,$birthday_m,$birthday_d),
			sql_number($sex),
			sql_char($mail_addr),
			sql_char(strtolower($mail_addr)),
			sql_char($password),
			sql_char($kinmu_name),
			sql_char($kinmu_unit),
			sql_char($kinmu_post),
			sql_char($agent_unit),
			sql_number($agent_disc),
			'current_timestamp');
		db_exec($sql);

		$msg_ok = 'サブエージェントを更新しました。';
		$back = "location.href='list.php'";
		break;
	case 'delete':
		$sql = "DELETE FROM t_agent WHERE ag_agent_id=$subagent_id";
		db_exec($sql);

		$msg_ok = 'サブエージェントを削除しました。';
		$back = "location.href='list.php'";
		break;
	}
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<? menu_script() ?>
</head>
<body>
<? page_header() ?>
<? menu_start() ?>

<div align="center">
<br>
<?
if (!$msg) {
?>
<p class="msg"><?=$msg_ok?></p>
<?
} else {
?>
<p>
<table border=0 cellspacing=3 cellpadding=0>
	<?=disp_msg($msg, '<tr><td class="msg">・', '</td></tr>')?>
</table>
</p>
<?
}
?>
<p><input type="button" id="ok" value="　戻る　" onclick="<?=$back?>"></p>
</div>

<? menu_end() ?>
<? page_footer() ?>
</body>
</html>
