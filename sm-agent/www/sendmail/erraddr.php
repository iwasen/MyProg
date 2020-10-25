<?
/******************************************************
' System :�᡼���ۿ������ӥ�
' Content:�������ɥ쥹����
'******************************************************/

$top = '..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/header.php");
include("$inc/database.php");
include("$inc/list.php");

function get_subject($send_mail_id) {
	$sql = "SELECT sd_subject FROM t_send_mail_data WHERE sd_send_mail_id=$send_mail_id";
	return db_fetch1($sql);
}

//�ᥤ�����
set_global('�������ɥ쥹����', BACK_TOP);

$sql = "SELECT ml_sub_seq,ml_mail_addr"
		. " FROM t_mail_list"
		. " WHERE ml_send_mail_id=$send_mail_id AND ml_status=1"
		. " ORDER BY ml_mail_addr";
$result = db_exec($sql);
$nrow = pg_numrows($result);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">�������᡼�륢�ɥ쥹������<span class="note">�ʷ�̾��<?=get_subject($send_mail_id)?>��</span></td>
		<td class="lb">
			<input type="button" value="����롡" onclick="location.href='list.php'">
		</td>
	</tr>
</table>
</form>

<form method="post" name="form2" action="errupd.php">
<table <?=LIST_TABLE?> width="100%" class="small">
	<tr class="tch">
<?
		sort_header(1, '�᡼�륢�ɥ쥹');
		sort_header(2, '���');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$sub_seq = $fetch->ml_sub_seq;
?>
	<tr class="tc<?=$i % 2?>">
		<td><input type="text" name="edit[<?=$sub_seq?>]" size=80 <?=value($fetch->ml_mail_addr)?>></td>
		<td align="center"><input type="checkbox" name="delete[]" <?=value($sub_seq)?>></td>
	</tr>
<?
}
?>
</table>
<br>
<input type="submit" value="��������">
<input type="reset" value="�ꥻ�å�">
<input type="hidden" name="send_mail_id" value="<?=$send_mail_id?>">
</form>
</div>

<? page_footer() ?>
</body>
</html>
