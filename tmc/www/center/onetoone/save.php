<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:OneToOneメール・メール一時保存処理
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");

// 入力パラメータ
$comm_id = $_POST['comm_id'];

// 返信メールを保存
$rec['msv_new_comm_id'] = sql_number($_POST['new_comm_id']);
$rec['msv_subject'] = sql_char($_POST['subject']);
$rec['msv_body'] = sql_char($_POST['body']);
$rec['msv_footer'] = sql_char($_POST['footer']);
$rec['msv_end_flag'] = sql_bool($_POST['end_flag']);

$sql = "SELECT COUNT(*) FROM t_mail_save WHERE msv_comm_id=$comm_id";
if (db_fetch1($sql) == 0) {
	$rec['msv_comm_id'] = sql_number($comm_id);
	db_insert('t_mail_save', $rec);
} else
	db_update('t_mail_save', $rec, "msv_comm_id=$comm_id");
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
</head>
<body onLoad="document.all.ok.focus()">

<? center_header('OneToOneメール｜返信メール一時保存') ?>

<div align="center">
<p class="msg">返信メールは一時保存されました。</p>
<p><input type="button" id="ok" value="　戻る　" onclick="location.href='<?=$_POST['url']?>'"></p>
</div>

<? center_footer() ?>

</body>
</html>
