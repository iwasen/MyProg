<?
/******************************************************
' System :�������ơ�net������������ѥڡ���
' Content:�ץ������Ⱦ��󿷵���Ͽ
'******************************************************/

$top = '..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/define.php");

//�ᥤ�����
set_global2('project', '�ץ������ȴ���', '�ץ������ȿ�����Ͽ', BACK_TOP);

$sql = "SELECT cl_name,ts_name1,ts_name2"
		. " FROM m_client JOIN m_tantousha ON ts_client_id=cl_client_id"
		. " WHERE ts_tantousha_id=" . sql_char($tantousha_id);
$result = db_exec($sql);
if (pg_numrows($result) == 0)
	system_error('ô���ԣɣĤ�����', __FILE__);
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
	if (f.project_name.value == "") {
		alert("�ץ�������̾�����Ϥ��Ƥ���������");
		f.project_name.focus();
		return false;
	}
	return confirm("�ץ������Ȥ���Ͽ���ޤ���������Ǥ�����");
}
//-->
</script>
<? menu_script() ?>
</head>
<body onload="document.form1.project_name.focus()">

<? page_header() ?>
<? menu_start() ?>

<form method="post" name="form1" action="pj_update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width=700>
	<tr>
		<td align="center" class="m4"><?=$g_title?></td>
	</tr>
</table>
<table border=0 cellspacing=2 cellpadding=3 width=700>
	<tr>
		<td class="m0" colspan=2>���ץ������Ⱦ�������Ϥ��Ƥ���������
		</td>
	</tr>
</table>
<table border=0 cellspacing=2 cellpadding=3 width=700>
	<tr>
		<td class="m1" width=140>���̾</td>
		<td class="n1"><?=htmlspecialchars($fetch->cl_name)?></td>
	</tr>
	<tr>
		<td class="m1" width=140>���ô����</td>
		<td class="n1"><?=htmlspecialchars("$fetch->ts_name1 $fetch->ts_name2")?></td>
	</tr>
	<tr>
		<td class="m1" width=140>�ץ�������̾<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="project_name" size=70 maxlength=150>
		</td>
	</tr>
	<tr>
		<td class="m1">�ץ������ȳ���</td>
		<td class="n1">
			<textarea class="kanji" name="summary" cols=70 rows=20></textarea>
		</td>
	</tr>
</table>
<table width=700>
	<div align="center">
	<input type="hidden" name="tantousha_id" <?=value($tantousha_id)?>>
	<input type="hidden" name="next_action">
	<input type="submit" value="����Ͽ��" onclick="document.form1.next_action.value='new'">
	<input type="reset" value="�ꥻ�å�">
	<input type="button" value="����롡" onclick='location.href="list.php"'>
	</div>
</table>
</form>

<? menu_end() ?>
<? page_footer() ?>
</body>
</html>
