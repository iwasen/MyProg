<?
/******************************************************
' System :�������ʪ�Υ��󥱡��ȥ�˥�����̳���ѥڡ���
' Content:���󥱡��ȥƥ�ץ졼��
'******************************************************/

$top = "../../..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

// �ᥤ�����
set_global('etc', '����¾�����åޥ������ƥʥ�', 'Web���󥱡�������ʸ�ƥ�ץ졼��', BACK_TOP);

$sql = "SELECT * FROM m_web_template WHERE wt_template_id='$template_id'";
$result = db_exec($sql);
if (pg_num_rows($result) == 0)
	system_error("template_id������", __FILE__);
$fetch = pg_fetch_object($result, 0);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function onSubmit_form1(f) {
	return confirm("Web���󥱡�������ʸ�ƥ�ץ졼�Ȥ򹹿����ޤ���������Ǥ�����");
}
//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>��Web���󥱡�������ʸ�ƥ�ץ졼�Ⱦ�������Ϥ��Ƥ���������</td>
	</tr>
	<tr>
		<td class="m1">����ʸ</td>
		<td class="n1"><textarea class="np" name="description" cols=78 rows=20><?=htmlspecialchars($fetch->wt_description)?></textarea></td>
</table>
<br>
<input type="submit" value="��������">
<input type="button" value="����롡" onclick="history.back()">
<input type="hidden" name="template_id" value="<?=$template_id?>">
</form>
</div>

<? page_footer() ?>
</body>
</html>
