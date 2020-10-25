<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_enquete.php");
include("$inc/check.php");
include("$inc/get_form.php");
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

$search = &$myenq->search;

// フォームからクラスへ代入
$myenq->send_num = get_number($_POST['send_num']);
$search->sex = (count($_POST['sex']) == 1) ? $_POST['sex'][0] : '';
$myenq->age_type = $_POST['age_type'];
if ($myenq->age_type == '1') {
	$search->age_cd = get_multi_data($_POST['age_cd']);
	$search->age_from = '';
	$search->age_to = '';
} elseif ($myenq->age_type == '2') {
	$search->age_cd = '';
	$search->age_from = $_POST['age_from'];
	$search->age_to = $_POST['age_to'];
} else {
	$search->age_cd = '';
	$search->age_from = '';
	$search->age_to = '';
}
$search->mikikon = (count($_POST['mikikon']) == 1) ? $_POST['mikikon'][0] : '';
$search->jitaku_area = get_multi_data($_POST['jitaku_area']);
$search->shokugyou = get_multi_data($_POST['shokugyou']);
$search->chain = get_multi_data($_POST['chain']);
$search->child = (count($_POST['child']) == 1) ? $_POST['child'][0] : '';
$search->research = (count($_POST['research']) == 1) ? $_POST['research'][0] : '';

// 人数検索処理
if ($next_action == 'search')
	redirect("myenq_matest2.php?my_enq_no=$my_enq_no");

// 最大発信数チェック
if ($myenq->send_num <= 0)
	$msg[] = '最大発信数を入力してください。';
elseif (!check_num($myenq->send_num, 0, 9999))
	$msg[] = '最大発信数は9999通以下で入力してください。';

// 対象者数チェック
if (!$msg && $search->get_monitor_num() < $myenq->send_num)
	$msg[] = '対象モニター数が最大発信数に満たないようです。';

if (!$msg) {
	// DBに保存
	$myenq->save_search = true;
	$myenq->write_db();
	$my_enq_no = $myenq->my_enq_no;

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
					<td><font size="2" class="honbun2">アンケートの対象条件を設定しました。</font></td>
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
