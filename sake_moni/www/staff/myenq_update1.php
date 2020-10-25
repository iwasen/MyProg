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

// フォームから入力データ取得
$enquete = &$myenq->enquete;

$enquete->title = trim($_POST['title']);
$enquete->enq_type = 2;
$enquete->start_date_y = get_number($_POST['start_date_y']);
$enquete->start_date_m = get_number($_POST['start_date_m']);
$enquete->start_date_d = get_number($_POST['start_date_d']);
$enquete->start_date_h = get_number($_POST['start_date_h']);
$enquete->end_date_y = get_number($_POST['end_date_y']);
$enquete->end_date_m = get_number($_POST['end_date_m']);
$enquete->end_date_d = get_number($_POST['end_date_d']);
$enquete->end_date_h = get_number($_POST['end_date_h']);
if (isset($_POST['ans_cond']))
	$myenq->ans_cond = join("\n", $_POST['ans_cond']);

// 入力チェック
if ($enquete->title == '' )
	$msg[] = 'アンケートタイトルを入力してください。';

if ($enquete->enq_type == '')
	$msg[] = 'アンケート種類を選択してください。';

$time = time();
$start_date_ok = true;
$end_date_ok = true;

if ($enquete->start_date_y == '') {
	$msg[] = '開始日時の年を入力してください。';
	$start_date_ok = false;
} elseif (!check_num($enquete->start_date_y, date('Y'), 2999)) {
	$msg[] = '開始日時の年が正しく入力されていないようです。';
	$start_date_ok = false;
}

if ($enquete->start_date_m == '') {
	$msg[] = '開始日時の月を入力してください。';
	$start_date_ok = false;
} elseif (!check_num($enquete->start_date_m, 1, 12)) {
	$msg[] = '開始日時の月が正しく入力されていないようです。';
	$start_date_ok = false;
}

if ($enquete->start_date_d == '') {
	$msg[] = '開始日時の日を入力してください。';
	$start_date_ok = false;
} elseif (!check_num($enquete->start_date_d, 1, 31)) {
	$msg[] = '開始日時の日が正しく入力されていないようです。';
	$start_date_ok = false;
}

if ($enquete->start_date_h == '') {
	$msg[] = '開始日時の時を入力してください。';
	$start_date_ok = false;
} elseif (!check_num($enquete->start_date_h, 0, 23)) {
	$msg[] = '開始日時の時が正しく入力されていないようです。';
	$start_date_ok = false;
}

if ($start_date_ok) {
	$start_date = strtotime("$enquete->start_date_y/$enquete->start_date_m/$enquete->start_date_d $enquete->start_date_h:0:0");
	if ($start_date == -1) {
		$msg[] = '開始日付が正しく入力されていないようです。';
		$start_date_ok = false;
	} else if ($start_date < $time) {
		$msg[] = '開始日時を過ぎています。';
		$start_date_ok = false;
	}
}

if ($enquete->end_date_y == '') {
	$msg[] = '終了日時の年を入力してください。';
	$end_date_ok = false;
} elseif (!check_num($enquete->end_date_y, date('Y'), 2999)) {
	$msg[] = '終了日時の年が正しく入力されていないようです。';
	$end_date_ok = false;
}

if ($enquete->end_date_m == '') {
	$msg[] = '終了日時の月を入力してください。';
	$end_date_ok = false;
} elseif (!check_num($enquete->end_date_m, 1, 12)) {
	$msg[] = '終了日時の月が正しく入力されていないようです。';
	$end_date_ok = false;
}

if ($enquete->end_date_d == '') {
	$msg[] = '終了日時の日を入力してください。';
	$end_date_ok = false;
} elseif (!check_num($enquete->end_date_d, 1, 31)) {
	$msg[] = '終了日時の日が正しく入力されていないようです。';
	$end_date_ok = false;
}

if ($enquete->end_date_h == '') {
	$msg[] = '終了日時の時を入力してください。';
	$end_date_ok = false;
} elseif (!check_num($enquete->end_date_h, 0, 23)) {
	$msg[] = '終了日時の時が正しく入力されていないようです。';
	$end_date_ok = false;
}

if ($end_date_ok) {
	$end_date = strtotime("$enquete->end_date_y/$enquete->end_date_m/$enquete->end_date_d");
	if ($end_date == -1) {
		$msg[] = '終了日付が正しく入力されていないようです。';
		$end_date_ok = false;
	} else if ($start_date_ok && $end_date < $start_date) {
		$msg[] = '終了日時は開始日時以降を入力してください。';
		$end_date_ok = false;
	}
}

// エラー無しなら次ページへ
if (!$msg) {
	// アンケート保存
	$myenq->save_enquete = true;
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
					<td><font size="2" class="honbun2">アンケートの実施概要を設定しました。</font></td>
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
