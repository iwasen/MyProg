<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:Ｍｙアンケート更新処理
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/search.php");
include("$inc/enquete.php");
include("$inc/my_enquete.php");
include("$inc/get_form.php");
include("$inc/check.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
include("$inc/error_msg.php");

// 差込変数存在チェック
function check_variable(&$text, $name, $place) {
	global $msg;

	if (strpos($text, $name) === false)
		$msg[] = "必要な差込変数（{$name}）が{$place}に無いようです。";
}

set_global('myenquete', 'Ｍｙアンケート管理', 'Ｍｙアンケート更新', BACK_TOP);

// セッション処理
$myenq = &$_SESSION['ss_my_enq'];
if ($myenq->my_enq_no != $my_enq_no)
	redirect("show.php?marketer_id=$marketer_id&my_enq_no=$my_enq_no");

// フォームからクラスへ代入
$myenq->mail_title = $subject;
$myenq->mail_header = $header;
$myenq->mail_contents = $body;
$myenq->mail_footer = $footer;

// リセット処理
if ($next_action == 'reset') {
	$myenq->mail_title = '';
	$myenq->mail_header = '';
	$myenq->mail_contents = '';
	$myenq->mail_footer = '';
	redirect("edit_mail.php?marketer_id=$marketer_id&edit=1");
}

// 差込変数存在チェック
check_variable($myenq->mail_header, '%POINT%', 'ヘッダ');
//check_variable($myenq->mail_contents, '%MONITOR_NAME%', '本文');
check_variable($myenq->mail_contents, '%ENQUETE_PARAM%', '本文');
//check_variable($myenq->mail_contents, '%MAIL_ADDR%', '本文');
check_variable($myenq->mail_contents, '%POINT%', '本文');

if (!$msg) {
	// DBに保存
	$myenq->write_db();

	// セッション変数削除
	unset($_SESSION['ss_my_enq']);
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body>
<? page_header() ?>

<br>
<?
if (!$msg) {
?>
<div align="center">
<form>
<p>Ｍｙアンケートのメール文を設定しました。</p>
<input type="button" value="　戻る　" onClick="location.href='show.php?marketer_id=<?=$marketer_id?>&my_enq_no=<?=$my_enq_no?>'">
</form>
</div>
<?
} else
    error_msg($msg);
?>
<? page_footer() ?>
</body>
</html>
