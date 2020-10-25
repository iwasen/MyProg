<?
/******************************************************
' System :�᡼���ۿ������ӥ�
' Content:�᡼�륢�ɥ쥹ɽ��
'******************************************************/

$top = '..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/header.php");
include("$inc/database.php");

function decode_status($status) {
	switch ($status) {
	case 0:
		return '̤����';
	case 1:
		return '����';
	case 2:
		return '�����Ѥ�';
	case 3:
		return '��ã';
	case 4:
		return '���';
	}
}

function get_subject($send_mail_id) {
	$sql = "SELECT sd_subject FROM t_send_mail_data WHERE sd_send_mail_id=$send_mail_id";
	return db_fetch1($sql);
}

//�ᥤ�����
set_global('�᡼�륢�ɥ쥹ɽ��', BACK_TOP);

$sql = "SELECT ml_mail_addr"
		. " FROM t_mail_list"
		. " WHERE ml_send_mail_id=$send_mail_id AND ml_status=$status"
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
		<td class="lt">��<?=decode_status($status)?>�᡼�륢�ɥ쥹������<span class="note">�ʷ�̾��<?=get_subject($send_mail_id)?>��</span></td>
		<td class="lb">
			<input type="button" value="���������" onclick="location.href='download.php?send_mail_id=<?=$send_mail_id?>&status=<?=$status?>'">
			<input type="button" value="����롡" onclick="location.href='list.php'">
		</td>
	</tr>
</table>
</form>
</div>
<hr class="header">
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	echo $fetch->ml_mail_addr, "<br>\n";
}
?>

<? page_footer() ?>
</body>
</html>
