<?
/******************************************************
' System :お酒と買物のアンケートモニター事務局用ページ
' Content:クライアント情報更新処理
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

set_global('client', 'クライアント管理', 'クライアント登録', BACK_TOP);

switch ($next_action) {
case 'new':
	$sql = "SELECT cl_client_id FROM m_client WHERE cl_mail_addr='$mail_addr'";
	$result = db_exec($sql);
	if (pg_num_rows($result) == 0) {
		$sql = "INSERT INTO m_client (cl_name1,cl_name2,cl_mail_addr,cl_password) VALUES (" .
						sql_char($name1) . "," .
						sql_char($name2) . "," .
						sql_char($mail_addr) . "," .
						sql_char($password) . ")";
		db_exec($sql);
		$msg = 'クライアントを登録しました。';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '入力したメールアドレスは既に登録されています。';
		$ret = 'history.back()';
	}
	break;
case 'update':
	$sql = "SELECT cl_client_id FROM m_client WHERE cl_mail_addr='$mail_addr' AND cl_client_id<>$client_id AND cl_status<>9";
	$result = db_exec($sql);
	if (pg_num_rows($result) == 0) {
		$sql = "UPDATE m_client SET" .
					" cl_status=" . sql_char($status) .
					",cl_name1=" . sql_char($name1) .
					",cl_name2=" . sql_char($name2) .
					",cl_mail_addr=" . sql_char($mail_addr) .
					",cl_password=" . sql_char($password) .
					",cl_update_date=current_timestamp" .
					" WHERE cl_client_id=$client_id";
		db_exec($sql);
		$msg = 'クライアント情報を更新しました。';
		$ret = 'location.href=\'list.php\'';
	} else {
		$msg = '入力したメールアドレスは既に登録されています。';
		$ret = 'history.back()';
	}
	break;
case 'delete':
	$sql = "UPDATE m_client SET cl_status=9 WHERE cl_client_id=$client_id";
	db_exec($sql);
	$msg = 'クライアントを削除しました。';
	$ret = 'location.href=\'list.php\'';
	break;
default:
	redirect('list.php');
}
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
<p class="msg"><?=$msg?></p>
<p><input type="button" id="ok" value="　戻る　" onclick="<?=$ret?>"></p>
</div>

<? page_footer() ?>
</body>
</html>
