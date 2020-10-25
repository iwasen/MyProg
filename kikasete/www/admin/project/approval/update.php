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

//メイン処理
if ($project_id) {
	$sql = "SELECT jb_enquete_id FROM t_job WHERE jb_project_id=$project_id";
	$title = 'プロジェクト';
} elseif ($job_id) {
	$sql = "SELECT jb_enquete_id FROM t_job WHERE jb_job_id=$job_id";
	$title = 'ジョブ';
}

set_global2('project', 'プロジェクト管理', "${title}承認処理", BACK_TOP);

$sql = "UPDATE t_enquete SET en_status=4 WHERE en_enquete_id IN ($sql) AND (en_status=2 OR en_status=3)";
db_exec($sql);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body onLoad="document.all.ok.focus()">
<? page_header() ?>

<div align="center">
<p class="msg"><?=$title?>を承認しました。</p>
<p><input type="button" id="ok" value="　戻る　" onclick="location.href='list.php'"></p>
</div>

<? page_footer() ?>
</body>
</html>
