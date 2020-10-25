<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:Myパートナー・リクルートジョブ新規登録
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");

redirect("r_job_edit.php?pjt_id=$pjt_id&new=1");
?>