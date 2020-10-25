<?
$top = '../../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/define.php");

/******************************************************
' System :きかせて・netエージェント用ページ
' Content:ジョブ状態変更
'******************************************************/

//メイン処理

set_global2('project', 'プロジェクト管理', 'ジョブ状態変更', BACK_TOP);

db_begin_trans();

$sql = "UPDATE t_job SET jb_send_status=0,jb_notice_flag=FALSE WHERE jb_job_id=$job_id";
db_exec($sql);

$sql = "UPDATE t_enquete SET en_status=1 WHERE en_enquete_id=(SELECT jb_enquete_id FROM t_job WHERE jb_job_id=$job_id)";
db_exec($sql);

db_commit_trans();
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<? menu_script() ?>
</head>
<body>

<? page_header() ?>
<? menu_start() ?>

<div align="center">
<p class="msg">未承認状態へ変更しました。</p>
<p><input type="button" id="ok" value="　戻る　" onclick="location.href='../../list2.php'"></p>
</div>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
