<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:�ץ������ȿʹԴ����ꥹ��ɽ��
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

set_global2('project', '�ץ������ȴ���', '�ץ��������⥸��ְ���', BACK_TOP);

$sql = "SELECT jb_job_id,jb_job_name,jb_search_id"
		.	" FROM t_job"
		.	" WHERE jb_project_id=$project_id"
		.	" ORDER BY jb_job_id";
$result = db_exec($sql);
$nrow = pg_numrows($result);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function OnSubmit_form1(f) {
	var i, count;
	count = 0;
	for (i = 0; i < <?=$nrow?>; i++) {
		if (f["job_id[]"][i].checked)
			count++;
	}
	if (count < 2) {
		alert("��ʬɽ�����뤿��ˤϥ���֤򣲤İʾ����򤷤Ƥ���������");
		return false;
	}
	return true;
}
function OnClick_allsel() {
	var f = document.form1;
	for (i = 0; i < <?=$nrow?>; i++) {
		if (!f["job_id[]"][i].disabled)
			f["job_id[]"][i].checked = true;
	}
}
//-->
</script>
<? menu_script() ?>
</head>
<body>
<? page_header() ?>
<? menu_start() ?>

<form method="post" action="job_diff.php" name="form1" onsubmit="return OnSubmit_form1(this)">
<table border=0 cellpadding=0 cellspacing=1 width="95%">
	<tr>
		<td>�����<?=$nrow?></td>
		<td align="right">
<?if ($nrow >= 2) {?>
			<input type="button" value="������" onclick="OnClick_allsel()">
			<input type="submit" value="��ʬɽ��">
<?}?>
			<input type="button" value="����롡" onclick="history.back()">
		</td>
	</tr>
</table>

<table border=1 cellpadding=0 cellspacing=1 width="95%">
	<tr class="tch">
		<th>�����ID</th>
		<th>�����̾</th>
<?if ($nrow >= 2) {?>
		<th>����</th>
<?}?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$job_id = $fetch->jb_job_id;
?>
	<tr class="tc<?=$i % 2?>">
<?
	if ($fetch->jb_search_id == null)
		echo "<td align='center'>$job_id</a></td>\n";
	else
		echo "<td align='center'><a href='job_show.php?job_id=$job_id'>$job_id</a></td>\n";

	echo "<td>" . htmlspecialchars($fetch->jb_job_name) . "</td>\n";
	if ($nrow >= 2) {
		if ($fetch->jb_search_id == null)
			echo "<td align='center'><input type='checkbox' name='job_id[]' value='$job_id' disabled></td>\n";
		else
			echo "<td align='center'><input type='checkbox' name='job_id[]' value='$job_id'></td>\n";
	}
	echo "</tr>\n";
}
?>
</table>
</form>

<? menu_end() ?>
<? page_footer() ?>
</body>
</html>
