<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:���󥱡����ֿ�Ψ�ޥ���ɽ������
'******************************************************/

$top = "../../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/decode.php");

// �ᥤ�����
set_global('etc', '����¾�����åޥ������ƥʥ�', '���󥱡����ֿ�Ψ�ޥ���', BACK_TOP);

$sql = "SELECT rep_age_cd,rep_sex_cd,rep_reply_rate FROM m_reply_rate";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$reply_rate[$fetch->rep_age_cd][$fetch->rep_sex_cd] = $fetch->rep_reply_rate;
}
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
<form method="post" name="form1" action="edit.php">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0">�����󥱡����ֿ�Ψ����ñ�̤ϡ��</td>
	</tr>
	<tr>
		<td width="100%">
			<table border=1 cellspacing=1 cellpadding=3 width="100%" frame="box">
				<tr align="center" class="m1">
					<td width="10%"></td>
					<td width="10%">����</td>
					<td width="10%">10��̤��</td>
					<td width="10%">10��</td>
					<td width="10%">20��</td>
					<td width="10%">30��</td>
					<td width="10%">40��</td>
					<td width="10%">50��</td>
					<td width="10%">60��</td>
					<td width="10%">70��ʾ�</td>
				</tr>
<?
for ($sex_cd = 0; $sex_cd <= 2; $sex_cd++) {
?>
				<tr align="center">
					<td class="m1"><?=decode_sex($sex_cd, '����')?></td>
<?
	for ($age_cd = 0; $age_cd <= 8; $age_cd++) {
?>
					<td><?=number_format($reply_rate[$age_cd][$sex_cd])?></td>
<?
	}
?>
				</tr>
<?
}
?>
			</table>
		</td>
	</tr>
</table>
<br>
<input type="hidden" name="next_action">
<input type="submit" value="��������">
<input type="button" value="����롡" onclick="location.href='../menu.php'">
</form>
</div>

<? page_footer() ?>
</body>
</html>
