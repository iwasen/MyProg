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

// セッション処理
if (isset($my_enq_no)) {
	$myenq = &$_SESSION['ss_my_enq'];
	if ($myenq->my_enq_no != $my_enq_no)
		redirect("myenq_make.php?my_enq_no=$my_enq_no");
} else
	redirect('myenq.php');

$enquete = &$myenq->enquete;

// フォームからクラスへ代入
$enquete->description = $description;

// リセット処理
if ($next_action == 'reset') {
	$enquete->description = '';
	redirect('myenq_edit_web.php?edit=1');
}

// プレビュー処理
if ($next_action == 'preview')
	redirect('myenq_webprev.php');

if (!$msg) {
	// DBに保存
	$rec['en_description'] = sql_char($enquete->description);
	db_update('t_enquete', $rec, "en_enquete_id=$myenq->enquete_id");

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
					<td><font size="2" class="honbun2">アンケートのアンケートＷＥＢ画面を設定しました。</font></td>
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
