<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:�ݥ�������ϲ���
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/point.php");

set_global('monitor', '��˥�������', '�ݥ��������', BACK_TOP);
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
	if (f.point_file.value == '') {
		alert("�ݥ������Ϳ�ե��������ꤷ�Ƥ���������");
		f.point_file.focus();
		return false;
	}
	return true;
}
//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="import_point.php" enctype="multipart/form-data" onsubmit="return onsubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td colspan=2>
			<table border=0 cellspacing=0 cellpadding=0 width="100%">
				<tr>
					<td class="m0">���ݥ������Ϳ�ե��������ꤷ�Ƥ�������</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="m1" width="22%">�ݥ������Ϳ�ե�����</td>
		<td class="n1">
			<input type="file" name="point_file" size=60>
		</td>
	</tr>
  <tr>
    <td class="m1" width="22%">�ݥ���ȥ�����</td>
    <td class="n1">
    	<span class="small">
    	<input type="radio" value="<?=PT_KIRIN?>" name="point_type" checked>�����
    	<input type="radio" value="<?=PT_JIMUKYOKU?>" name="point_type">��̳����Ϳ
    	<input type="radio" value="<?=PT_ENQUETE?>" name="point_type">���󥱡��ȥݥ����
    	</span>
    </td>
  </tr>
	<tr>
		<td class="m1" width="22%">���ԤؤΥݥ������Ϳ</td>
		<td class="n1">
			<span class="small">
				<input type="radio" name="taikai_flag" value="1" checked>���Ԥˤϥݥ���Ȥ���Ϳ���ʤ��ʥ��顼������<br>
				<input type="radio" name="taikai_flag" value="2">���Ԥˤ�ݥ���Ȥ���Ϳ����
			</span>
		</td>
	</tr>
	<tr>
		<td class="m1" width="22%">���顼���ν���</td>
		<td class="n1">
			<span class="small">
				<input type="radio" name="error_flag" value="1" checked>���顼�����äƤ�ݥ������Ϳ��Ԥ�<br>
				<input type="radio" name="error_flag" value="2">���顼������Ǥ⤢��Хݥ������Ϳ��Ԥ�ʤ�
			</span>
		</td>
	</tr>
</table>
<br>
<input type="hidden" name="pset" value="1">
<input type="submit" value="���åץ���">
<input type="button" value="����롡" onclick="location.href='menu.php'">
</form>
</div>

<? page_footer() ?>
</body>
</html>
