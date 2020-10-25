<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:Ｐｒｏアンケート更新処理
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/search.php");
include("$inc/enquete.php");
include("$inc/pro_enquete.php");
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

set_global('proenquete', 'Ｐｒｏアンケート管理', 'Ｐｒｏアンケート更新', BACK_TOP);

// セッション処理
$pro_enq = &$_SESSION['ss_pro_enq'];
if ($pro_enq->pro_enq_no != $pro_enq_no)
    redirect("show.php?marketer_id=$marketer_id&pro_enq_no=$pro_enq_no");

// フォームからクラスへ代入
$pro_enq->mail_title = $subject;
$pro_enq->mail_header = $header;
$pro_enq->mail_contents = $body;
$pro_enq->mail_footer = $footer;

// リセット処理
if ($next_action == 'reset') {
    $pro_enq->mail_title = '';
    $pro_enq->mail_header = '';
    $pro_enq->mail_contents = '';
    $pro_enq->mail_footer = '';
    redirect("edit_mail.php?marketer_id=$marketer_id&edit=1");
}

// 差込変数存在チェック
check_variable($pro_enq->mail_header, '%POINT%', 'ヘッダ');
check_variable($pro_enq->mail_contents, '%MONITOR_NAME%', '本文');
check_variable($pro_enq->mail_contents, '%ENQUETE_PARAM%', '本文');
check_variable($pro_enq->mail_contents, '%MAIL_ADDR%', '本文');
check_variable($pro_enq->mail_contents, '%POINT%', '本文');

if (!$msg) {
    // DBに保存
    $pro_enq->write_db();

    // セッション変数削除
    unset($_SESSION['ss_pro_enq']);
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
<p>Ｐｒｏアンケートのメール文を設定しました。</p>
<input type="button" value="　戻る　" onclick="location.href='show.php?marketer_id=<?=$marketer_id?>&pro_enq_no=<?=$pro_enq_no?>'">
</form>
</div>
<?
} else
    error_msg($msg);
?>
<? page_footer() ?>
</body>
</html>
