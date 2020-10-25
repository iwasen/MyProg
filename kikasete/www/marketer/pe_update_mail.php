<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/pro_enquete.php");
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
if (isset($pro_enq_no)) {
	$pro_enq = &$_SESSION['ss_pro_enq'];
	if ($pro_enq->pro_enq_no != $pro_enq_no)
		redirect("pe_make.php?pro_enq_no=$pro_enq_no");
} else
	redirect('pe_index.php');

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
	redirect('pe_edit_mail.php?edit=1');
}

// プレビュー処理
if ($next_action == 'preview')
	redirect('pe_mailprev.php');

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
<? marketer_header('Ｐｒｏアンケート', PG_NULL) ?>
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
<table  border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
		<td width="778" align="center" valign="top">
			<table border="0" cellspacing="0" cellpadding="0" width="778">
				<tr>
					<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
					<td width="768" valign="top"><img src="images/mkk_pe/title_01.gif"></td>
				</tr>
			</table>
			<div align="center">
			<form>
			<table border=0 cellspacing=2 cellpadding=3 width="95%">
				<tr>
					<td>Ｐｒｏアンケートのメール文を設定しました。</td>
				</tr>
			</table>
			<br>
<a href="pe_make.php?pro_enq_no=<?=$pro_enq_no?>"><img src="images/mkk_bt/back.gif" alt="戻る" width="108" height="31" border="0"></a>
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

<? }?>

</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>

<? marketer_footer() ?>
