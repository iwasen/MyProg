<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:���󥱡����ֿ�Ψ�ޥ�����Ͽ�ѹ�����
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
<script type="text/javascript">
<!--
function num_chk(s) {
	for (i = 0; i < s.length; i++) {
		if (s.charAt(i) < "0" || s.charAt(i) > "9") {
			return false;
		}
	}
	return true;
}
function onSubmit_form1(f) {
	for (var sex_cd = 0; sex_cd <= 2; sex_cd++) {
		for (var age_cd = 0; age_cd <= 8; age_cd++) {
			var c = f["reply_rate[" + age_cd + "][" + sex_cd + "]"];
			if (c.value == "") {
				alert("���󥱡����ֿ�Ψ�����Ϥ��Ƥ���������");
				c.focus();
				return false;
			}
			if (!num_chk(c.value)) {
				alert("���󥱡����ֿ�Ψ��Ⱦ�ѿ��������Ϥ��Ƥ���������");
				c.focus();
				return false;
			}
		}
	}
	return confirm("���󥱡����ֿ�Ψ�ޥ�������򹹿����ޤ���������Ǥ�����");
}
//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
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
					<td class="n1"><input type="text" name="reply_rate[<?=$age_cd?>][<?=$sex_cd?>]" <?=value($reply_rate[$age_cd][$sex_cd])?> size=4 laxlength=3></td>
<?
	}
?>
				<tr>
<?
}
?>
			</table>
		</td>
	</tr>
</table>
<br>
<input type="hidden" name="sys_id" <?=value($sys_id)?>>
<input type="submit" value="��������">
<input type="button" value="����롡" onclick="history.back()">
</form>
</div>

<? page_footer() ?>
</body>
</html>
