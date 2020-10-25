<?
/******************************************************
' System :メール配信サービス
' Content:ユーザ情報更新
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/common.php");
include("$inc/header.php");
include("$inc/database.php");

// メイン処理
set_global('ユーザ情報更新', BACK_ADMIN);

switch ($next_action) {
case 'new':
	$sql = sprintf("INSERT INTO m_user (us_mail_addr,us_password,us_name1,us_name2) VALUES (%s,%s,%s,%s)",
				sql_char($mail_addr),
				sql_char($password),
				sql_char($name1),
				sql_char($name2));
	db_exec($sql);

	$msg = 'ユーザを登録しました。';
	$back = "location.href='list.php'";
	break;
case 'update':
	$sql = sprintf("UPDATE m_user SET us_mail_addr=%s,us_password=%s,us_name1=%s,us_name2=%s WHERE us_user_id=%s",
				sql_char($mail_addr),
				sql_char($password),
				sql_char($name1),
				sql_char($name2),
				sql_number($user_id));
	db_exec($sql);

	$msg = 'ユーザ情報を更新しました。';
	$back = "location.href='list.php'";
	break;
case 'delete':
	$sql = "DELETE FROM m_user WHERE us_user_id=$user_id";
	db_exec($sql);

	$msg = 'ユーザを削除しました。';
	$back = "location.href='list.php'";
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
<p><input type="button" id="ok" value="　戻る　" onclick="<?=$back?>"></p>
</div>

<? page_footer() ?>
</body>
</html>
