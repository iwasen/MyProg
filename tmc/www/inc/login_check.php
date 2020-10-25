<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:ログイン済みチェック
'******************************************************/

session_start();

$g_staff_id = $_SESSION['s_staff_id'];
$g_privilege = $_SESSION['s_privilege'];

if ($g_staff_id == '') {
	echo "<script type='text/javascript'>\n";
	echo "top.location.href = '$top/login/login.php'\n";
	echo "</script>\n";
	exit;
}
?>