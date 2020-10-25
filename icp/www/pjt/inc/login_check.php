<?
/******************************************************
' System :ICPプロジェクトページ
' Content:ログイン済みチェック
'******************************************************/

session_start();

$g_member_id = $_SESSION[SCD]['member_id'];

if ($g_member_id == '') {
	$location = "$top/login/login.php";
	if ($_GET['job_cd'] != '')
		$location .= '?job_cd=' . $_GET['job_cd'];
	header("location: $location");
	exit;
}
?>