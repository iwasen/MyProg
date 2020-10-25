#!/usr/local/bin/php4
<?
/******************************************************
' System :fletteスタッフページ
' Content:スタッフ情報更新処理
'******************************************************/

$top = '..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");

$inc = "$top/inc";
include("$inc/common.php");
include("$inc/login_check.php");
include("$inc/header.php");

switch ($next_action) {
case 'new':
	$sql = "SELECT st_staff_id FROM m_staff WHERE st_mail_addr='$mail_addr'";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$rec['st_mail_addr'] = sql_char($mail_addr);
		$rec['st_password'] = sql_char($password);
		$rec['st_auth'] = sql_number($auth);
		$rec['st_status'] = $status;
		$rec['st_name1'] = sql_char($name1);
		$rec['st_name2'] = sql_char($name2);
		$rec['st_name1_kana'] = sql_char($name1_kana);
		$rec['st_name2_kana'] = sql_char($name2_kana);
		$rec['st_section'] = sql_char($section);
		db_insert('m_staff', $rec);

		$msg = 'スタッフを登録しました。';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '入力したメールアドレスは既に登録されています。';
		$ret = 'history.back()';
	}
	break;
case 'update':
	$sql = "SELECT st_staff_id FROM m_staff WHERE st_mail_addr='$mail_addr' AND st_staff_id<>$staff_id AND st_status<>9";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$rec['st_mail_addr'] = sql_char($mail_addr);
		if (str_replace('*', '', $password) != '')
			$rec['st_password'] = sql_char($password);
		if (isset($auth))
			$rec['st_auth'] = sql_number($auth);
		$rec['st_status'] = $status;
		$rec['st_name1'] = sql_char($name1);
		$rec['st_name2'] = sql_char($name2);
		$rec['st_name1_kana'] = sql_char($name1_kana);
		$rec['st_name2_kana'] = sql_char($name2_kana);
		$rec['st_section'] = sql_char($section);
		$rec['st_update_date'] = 'current_timestamp';
		db_update('m_staff', $rec, "st_staff_id=$staff_id");

		$msg = 'スタッフ情報を更新しました。';

		if ($s_staff_auth == 1)
			$ret = 'location.href=\'list.php\'';
		else
			$ret = 'location.href=\'../index.php\'';
	} else {
		$msg = '入力したメールアドレスは既に登録されています。';
		$ret = 'history.back()';
	}
	break;
case 'delete':
	db_delete('m_staff', "st_staff_id=$staff_id");

	$msg = 'スタッフを削除しました。';
	$ret = 'location.href=\'list.php\'';
	break;
default:
	redirect('list.php');
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>flette</title>
<link rel="stylesheet" type="text/css" href="/css/staff.css">
</head>
<body onLoad="document.all.ok.focus()">

<? page_header('スタッフ情報更新完了', true, true) ?>

<div align="center">
<p class="msg"><?=$msg?></p>
<p><input type="button" id="ok" value="　戻る　" onclick="<?=$ret?>"></p>
</div>

<? page_footer() ?>

</body>
</html>
