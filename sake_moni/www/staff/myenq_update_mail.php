<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_enquete.php");
include("$inc/check.php");
$inc = "$top/inc";
include("$inc/mypage.php");
include("$inc/header2.php");
include("$inc/error.php");

// 差込変数存在チェック
function check_variable(&$text, $name, $place) {
	global $msg;

	if (strpos($text, $name) === false)
		$msg[] = "必要な差込変数（{$name}）が{$place}に無いようです。";
}

// セッション処理
if (isset($my_enq_no)) {
	$myenq = &$_SESSION['ss_my_enq'];
	if ($myenq->my_enq_no != $my_enq_no)
		redirect("myenq_make.php?my_enq_no=$my_enq_no");
} else
	redirect('myenq.php');

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
	redirect('myenq_edit_mail.php?edit=1');
}

// プレビュー処理
if ($next_action == 'preview')
	redirect('myenq_mailprev.php');

// 差込変数存在チェック
check_variable($myenq->mail_header, '%POINT%', 'ヘッダ');
check_variable($myenq->mail_contents, '%MONITOR_NAME%', '本文');
check_variable($myenq->mail_contents, '%ENQUETE_PARAM%', '本文');
check_variable($myenq->mail_contents, '%MAIL_ADDR%', '本文');
check_variable($myenq->mail_contents, '%POINT%', '本文');

if (!$msg) {
	// DBに保存
	$myenq->write_db();

	// セッション変数削除
	unset($_SESSION['ss_my_enq']);
}
?>
<? staff_header('アンケート登録') ?>

<table width="720" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><img src="images/shim.gif" width="1" height="10" border="0"></td>
	</tr>
</table>

<?
if (!$msg) {
?>
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="10"><img src="images/shim.gif" width="10" border="0"></td>
		<td width="710" align="center" valign="top">
			<div align="center">
			<form>
			<table border=0 cellspacing=2 cellpadding=3 width="95%">
				<tr>
					<td><font size="2" class="honbun2">アンケートのメール文を設定しました。</font></td>
				</tr>
			</table>
			<br>
			<input type="button" value="　戻る　" onclick="location.href='myenq_make.php?my_enq_no=<?=$my_enq_no?>'">
			</form>
			</div>
		</td>
	</tr>
</table>
<?
} else
	error_msg($msg);
?>

<? staff_footer() ?>
