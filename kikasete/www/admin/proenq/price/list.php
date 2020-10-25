<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:Pro���󥱡�������ɽ�ޥ����ꥹ��ɽ��
'******************************************************/

$top = "../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/list.php");

//�ᥤ�����
set_global('proenquete', '�У�異�󥱡��ȴ����åޥ������ƥʥ�', '����ɽ����', BACK_TOP);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function sort_list(sort, dir) {
	var f = document.form1;
	f.sort_col.value = sort;
	f.sort_dir.value = dir;
	f.submit();
}
//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<?
$question_ary = array();
$sql = "SELECT DISTINCT pfp_question_num FROM m_finding_price ORDER BY pfp_question_num";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$question_ary[] = $fetch->pfp_question_num;
}

$sample_ary = array();
$sql = "SELECT DISTINCT pfp_sample_num FROM m_finding_price ORDER BY pfp_sample_num";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$sample_ary[] = $fetch->pfp_sample_num;
}

$price_ary = array();
$sql = "SELECT pfp_question_num,pfp_sample_num,pfp_price FROM m_finding_price";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$price_ary[$fetch->pfp_sample_num][$fetch->pfp_question_num] = $fetch->pfp_price;
}
?>
<form name="form2">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">���ե�����ǥ�������ɽ������ߡ�</td>
		<td class="lb">
			<input type="button" value="����ݡ���" onclick="location.href='import.php'">
			<input type="button" value="���Խ���" onclick="location.href='edit.php'">
		</td>
	</tr>
</table>
</form>

<table <?=LIST_TABLE?> width="100%">
	<tr class="tch">
		<td colspan=2 rowspan=2></td>
		<td align="center" colspan="<?=count($price_ary)?>"><b>�����</b></td>
	</tr>
	<tr class="tch">
<?
foreach ($question_ary as $question_num) {
?>
		<td align="center"><?=$question_num?></td>
<?
}
?>
	</tr>
<?
foreach ($sample_ary as $i => $sample_num) {
?>
	<tr>
<?
	if ($i == 0) {
?>
		<td class="tch" align="center" rowspan="<?=count($sample_ary)?>" width="5%"><b>��<br>��<br>��<br>��<br>��</b></td>
<?
	}
?>
		<td class="tch" align="center" width="10%">��<?=number_format($sample_num)?></td>
<?
	foreach ($question_ary as $question_num) {
?>
		<td class="tc<?=$i % 2?>" align="center" width="10%"><?=number_format($price_ary[$sample_num][$question_num])?></td>
<?
	}
?>
	</tr>
<?
}
?>
</table>
<br>
<?
$question_ary = array();
$sql = "SELECT DISTINCT pep_question_num FROM m_proenq_price ORDER BY pep_question_num";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$question_ary[] = $fetch->pep_question_num;
}

$sample_ary = array();
$sql = "SELECT DISTINCT pep_sample_num FROM m_proenq_price ORDER BY pep_sample_num";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$sample_ary[] = $fetch->pep_sample_num;
}

$price_ary = array();
$sql = "SELECT pep_question_num,pep_sample_num,pep_price FROM m_proenq_price";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$price_ary[$fetch->pep_sample_num][$fetch->pep_question_num] = $fetch->pep_price;
}
?>
<form name="form2">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">���ܥ��󥱡�������ɽ������ߡ�</td>
		<td class="lb">
			<input type="button" value="����ݡ���" onclick="location.href='import2.php'">
			<input type="button" value="���Խ���" onclick="location.href='edit2.php'">
		</td>
	</tr>
</table>
</form>

<table <?=LIST_TABLE?> width="100%">
	<tr class="tch">
		<td colspan=2 rowspan=2></td>
		<td align="center" colspan="<?=count($price_ary)?>"><b>�����</b></td>
	</tr>
	<tr class="tch">
<?
foreach ($question_ary as $question_num) {
?>
		<td align="center"><?=$question_num?></td>
<?
}
?>
	</tr>
<?
foreach ($sample_ary as $i => $sample_num) {
?>
	<tr>
<?
	if ($i == 0) {
?>
		<td class="tch" align="center" rowspan="<?=count($sample_ary)?>" width="5%"><b>��<br>��<br>��</b></td>
<?
	}
?>
		<td class="tch" align="center" width="10%">��<?=number_format($sample_num)?></td>
<?
	foreach ($question_ary as $question_num) {
?>
		<td class="tc<?=$i % 2?>" align="center" width="10%"><?=number_format($price_ary[$sample_num][$question_num])?></td>
<?
	}
?>
	</tr>
<?
}
?>
</table>

</div>

<? page_footer() ?>
</body>
</html>
