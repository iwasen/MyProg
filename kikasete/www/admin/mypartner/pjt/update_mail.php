<?
/******************************************************
' System :きかせて・net事務局・エージェント用ページ
' Content:Myパートナー・ＷＥＢアンケート手動修正
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_partner.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
include("$inc/error_msg.php");

// メイン処理
set_global2('mypartner', 'Myパートナー管理', 'メール文登録', BACK_TOP);

// 差込変数存在チェック
function check_variable(&$text, $name, $place) {
	global $msg;

	if (strpos($text, $name) === false)
		$msg[] = "必要な差込変数（{$name}）が{$place}に無いようです。";
}

// Myパートナークラス取得
$mp = &$_SESSION['s_my_partner'];
$enquete = &$mp->enquete;
$pjt_id = $mp->pjt_id;

// フォームからクラスへ代入
$mp->subject = $subject;
$mp->header = $header;
$mp->body = $body;
$mp->footer = $footer;

// リセット処理
if ($next_action == 'reset') {
	$mp->subject = '';
	$mp->header = '';
	$mp->body = '';
	$mp->footer = '';
	redirect('edit_mail.php?edit=1');
}

// プレビュー処理
if ($next_action == 'preview') {
	redirect('preveiw_mail.php');
}

// 差込変数存在チェック
check_variable($mp->header, '%POINT%', 'ヘッダ');
check_variable($mp->body, '%MONITOR_NAME%', '本文');
check_variable($mp->body, '%ENQUETE_PARAM%', '本文');
check_variable($mp->body, '%MAIL_ADDR%', '本文');
check_variable($mp->body, '%POINT%', '本文');

if (!$msg) {
	// DBに保存
	$mp->write_db();
}
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

<?
if (!$msg) {
?>
<table border=0 cellspacing=2 cellpadding=3 width=700>
	<tr>
		<td align="center" class="m4"><?=$g_title?></td>
	</tr>
	<tr>
		<td align="center"><br>
			<p class="msg">Myパートナーのメール文を更新しました。</p>
			<p><input type="button" value="　戻る　" onclick="location.href='<?=get_return_page()?>'"></p>
		</td>
	</tr>
</table>
<?
} else
	error_msg($msg);
?>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
