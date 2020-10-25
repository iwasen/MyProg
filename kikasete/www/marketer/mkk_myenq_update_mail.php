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
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");
include("$inc/mk_error.php");

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
		redirect("mkk_myenq_make.php?my_enq_no=$my_enq_no");
} else
	redirect('mkk_myenq.php');

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
	redirect('mkk_myenq_edit_mail.php?edit=1');
}

// プレビュー処理
if ($next_action == 'preview')
	redirect('mkk_myenq_mailprev.php');

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
<? marketer_header('Myアンケート', PG_NULL) ?>

<table width="789" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td><img src="images/common/spacer.gif" width="1" height="10" border="0"></td>
	</tr>
</table>
<?
if (!$msg) {
?>
<table border="0" cellspacing="0" cellpadding="0" bgcolor="ffffff">
	<tr>
		<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
		<td width="778" align="center" valign="top">
			<table border="0" cellspacing="0" cellpadding="0" width="778">
				<tr>
					<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
					<td width="768" valign="top"><img src="images/t_mk_myenq2.gif" width="538" height="20"></td>
				</tr>
			</table>
			<div align="center">
			<form>
			<table border=0 cellspacing=2 cellpadding=3 width="95%">
				<tr>
					<td>Myアンケートのメール文を設定しました。</td>
				</tr>
			</table>
			<br>
			<a href="mkk_myenq_make.php?my_enq_no=<?=$my_enq_no?>"><img src="images/mkk_bt/back.gif" alt="戻る" name="image2" width="108" height="31" border="0"></a>
			</form>
			</div>
		</td>
	</tr>
</table>
<?
} else{?>
	<table width="788" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><? error_msg($msg);?></td>
  </tr>
</table>
<? }?></td>
    <td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="20"></td>
  </tr>
</table>

<? marketer_footer() ?>
