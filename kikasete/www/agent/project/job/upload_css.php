<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:CSS�ե����륢�åץ���
'******************************************************/

$top = "../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

// �ᥤ�����
set_global2('project', '�ץ������ȴ����å᡼��ʸ��Ͽ', 'CSS�λ���', BACK_TOP);

?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function onsubmit_form1(f) {
	if (f.upload_file.value == "") {
		alert("CSS�ե�����̾�����Ϥ��Ƥ���������");
		f.upload_file.focus();
		return false;
	}
	return confirm("CSS�ե�����򥢥åץ��ɤ��ޤ���������Ǥ�����");
}
//-->
</script>
</head>
<body onload="document.form1.upload_file.focus()">
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="upload_css2.php" enctype="multipart/form-data" onsubmit="return onsubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>��CSS�ե���������Ϥ��Ƥ���������</td>
	</tr>
	<tr>
		<td class="m1" width="20%">CSS�ե����������</td>
		<td class="n1">
			<input type="file" name="upload_file" size=80>
		</td>
	</tr>
</table>
<br>
<input type="submit" value="���åץ���">
<input type="button" value="����롡" onclick="location.href='enq_mail.php?job_id=<?=$job_id?>'">
<input type="hidden" name="job_id" value="<?=$job_id?>">
</form>
</div>

<? page_footer() ?>
</body>
</html>
