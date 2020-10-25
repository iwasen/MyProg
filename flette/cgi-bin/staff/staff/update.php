#!/usr/local/bin/php4
<?
/******************************************************
' System :flette�����åեڡ���
' Content:�����åվ��󹹿�����
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

		$msg = '�����åդ���Ͽ���ޤ�����';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '���Ϥ����᡼�륢�ɥ쥹�ϴ�����Ͽ����Ƥ��ޤ���';
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

		$msg = '�����åվ���򹹿����ޤ�����';

		if ($s_staff_auth == 1)
			$ret = 'location.href=\'list.php\'';
		else
			$ret = 'location.href=\'../index.php\'';
	} else {
		$msg = '���Ϥ����᡼�륢�ɥ쥹�ϴ�����Ͽ����Ƥ��ޤ���';
		$ret = 'history.back()';
	}
	break;
case 'delete':
	db_delete('m_staff', "st_staff_id=$staff_id");

	$msg = '�����åդ������ޤ�����';
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

<? page_header('�����åվ��󹹿���λ', true, true) ?>

<div align="center">
<p class="msg"><?=$msg?></p>
<p><input type="button" id="ok" value="����롡" onclick="<?=$ret?>"></p>
</div>

<? page_footer() ?>

</body>
</html>
