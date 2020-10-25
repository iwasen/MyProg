<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:ジョブ承認処理
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/decode.php");

if ($project_id) {
	$sql = "SELECT pj_project_id,pj_project_name,pj_summary"
			. " FROM t_project"
			. " WHERE pj_project_id=$project_id";
	$title = 'プロジェクト';
} elseif ($job_id) {
	$sql = "SELECT pj_project_id,pj_project_name,pj_summary,jb_job_name,jb_job_type,en_enq_type"
			. " FROM t_job JOIN t_project ON pj_project_id=jb_project_id JOIN t_enquete ON en_enquete_id=jb_enquete_id"
			. " WHERE jb_job_id=$job_id";
	$title = 'ジョブ';
}

set_global2('project', 'プロジェクト管理', "${title}承認処理", BACK_TOP);

$result = db_exec($sql);
if (pg_numrows($result))
	$fetch = pg_fetch_object($result, 0);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<SCRIPT LANGUAGE=javascript>
<!--
function onsubmit_form1() {
	return confirm("この<?=$title?>を承認します。よろしいですか？");
}
//-->
</SCRIPT>
</head>
<body>
<? page_header() ?>

<div align="center">
<table border=0 cellspacing=2 cellpadding=3 width='80%'>
	<tr>
		<td class="m0" colspan=2>■プロジェクト情報</td>
	</tr>
	<tr>
		<td class="m1" width="20%">プロジェクトID</td>
		<td class="n1"><?=$fetch->pj_project_id?></td>
	</tr>
	<tr>
		<td class="m1">プロジェクト名</td>
		<td class="n1"><?=htmlspecialchars($fetch->pj_project_name)?></td>
	</tr>
	<tr>
		<td class="m1">プロジェクト概要</td>
		<td class="n1">
			<textarea class="kanji" name="summary" cols=50 rows=5 readonly><?=htmlspecialchars($fetch->pj_summary)?></textarea>
		</td>
	</tr>
<?
if ($job_id) {
?>
	<tr>
		<td class="m0" colspan=2>■ジョブ情報</td>
	</tr>
	<tr>
		<td class="m1">ジョブID</td>
		<td class="n1"><?=$job_id?></td>
	</tr>
	<tr>
		<td class="m1">ジョブ名</td>
		<td class="n1"><?=htmlspecialchars($fetch->jb_job_name)?></td>
	</tr>
	<tr>
		<td class="m1">アンケート種類</td>
		<td class="n1"><?=decode_enq_type($fetch->en_enq_type)?></td>
	</tr>
<?
}
?>
</table>
<br>
<form method="post" name="form1" action="update.php" onsubmit="return onsubmit_form1()">
	<input type="submit" value="　承認　">
	<input type="button" value="キャンセル" onclick="history.back()">
	<input type="hidden" name="project_id" value="<?=$project_id?>">
	<input type="hidden" name="job_id" value="<?=$job_id?>">
</form>
</div>

<? page_footer() ?>
</body>
</html>
