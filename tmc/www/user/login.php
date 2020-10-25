<?
$inc = "../inc";
include("$inc/common.php");
include("$inc/database.php");

// ログインOK
function login_ok($user_id, $page) {
	// 飛び先決定
	switch ($page) {
	case 1:
		$url = 'mypage1.php';
		break;
	default:
		$url = 'mypage.php';
		break;
	}

	// ユーザIDをセッションに保存
	$_SESSION['user_id'] = $user_id;

	redirect($url);
}

// セッション開始
session_start();

if ($_GET['t']) {
	// ワンクリックURL
	$login_id = $_GET['t'];
	$page = $_GET['p'];
	$sql = "SELECT ulg_user_id,ulg_password FROM t_user_login WHERE ulg_login_id=" . sql_char($login_id);
	$result = db_exec($sql);
	if (pg_num_rows($result) != 0) {
		$fetch = pg_fetch_object($result, 0);
		$user_id = $fetch->ulg_user_id;
		$cookie_key = "tmc_$user_id";
		if ($_COOKIE[$cookie_key] == md5($fetch->ulg_password))
			login_ok($user_id, $page);
	}
} elseif ($_POST['login_id']) {
	// ログインページ
	$login_id = $_POST['login_id'];
	$page = $_POST['page'];
	$sql = "SELECT ulg_user_id,ulg_password FROM t_user_login WHERE ulg_login_id=" . sql_char($login_id);
	$result = db_exec($sql);
	if (pg_num_rows($result) != 0) {
		$fetch = pg_fetch_object($result, 0);
		$user_id = $fetch->ulg_user_id;
		$cookie_key = "tmc_$user_id";
		if ($_POST['password'] == $fetch->ulg_password) {
			setcookie($cookie_key, md5($fetch->ulg_password), COOKIE_EXPIRE, '/');
			login_ok($user_id, $page);
		}
	}
}

// ログイン失敗
$url = 'index.php?err=1';
if ($page != '')
	$url .= "&page=$page";
if ($login_id != '')
	$url .= "&login_id=$login_id";
redirect($url);
?>