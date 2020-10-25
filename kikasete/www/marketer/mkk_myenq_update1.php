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
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");
include("$inc/mk_error.php");

// セッション処理
if (isset($my_enq_no)) {
	$myenq = &$_SESSION['ss_my_enq'];
	if ($myenq->my_enq_no != $my_enq_no)
		redirect("mkk_myenq_make.php?my_enq_no=$my_enq_no");
} else
	redirect('mkk_myenq.php');

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
if (isset($_POST['ans_cond'])) {
	$ans_cond_ary = array();
	foreach ($_POST['ans_cond'] as $ans_cond) {
		if ($ans_cond != '')
			$ans_cond_ary[] = $ans_cond;
	}
	$myenq->ans_cond = join("\n", $ans_cond_ary);
}

if ($_POST['next_action'] == 'add_cond') {
	$myenq->ans_cond_count++;
	redirect("mkk_myenq_edit1.php?edit=1");
}

// 入力チェック
if ($enquete->title == '' )
	$msg[] = 'アンケートタイトルを入力してください。';

if ($enquete->enq_type == '')
	$msg[] = 'アンケート種類を選択してください。';

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

get_quickest_date($send_date_y, $send_date_m, $send_date_d, $send_date_h);
$quickest_date = strtotime("$send_date_y/$send_date_m/$send_date_d $send_date_h:00:00");

if ($start_date_ok) {
	$start_date = strtotime("$enquete->start_date_y/$enquete->start_date_m/$enquete->start_date_d $send_date_h:00:00");
	if ($start_date == -1) {
		$msg[] = '開始日付が正しく入力されていないようです。';
		$start_date_ok = false;
	} else if ($start_date < $quickest_date) {
		$msg[] = '最早発信開始時間以降の日時で開始日時を設定してください。';
		$start_date_ok = false;
	} elseif (date('Y', $start_date) != $enquete->start_date_y || date('n', $start_date) != $enquete->start_date_m || date('j', $start_date) != $enquete->start_date_d) {
		$msg[] = '開始日付が正しく入力されていないようです。';
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
	$end_date = strtotime("$enquete->end_date_y/$enquete->end_date_m/$enquete->end_date_d $enquete->end_date_h:00:00");
	if ($end_date == -1) {
		$msg[] = '終了日付が正しく入力されていないようです。';
		$end_date_ok = false;
	} else if ($end_date <= $quickest_date) {
		$msg[] = '最早発信開始時間以降の日時で終了日時を設定してください。';
		$end_date_ok = false;
	} else if ($start_date_ok && $end_date < $start_date) {
		$msg[] = '終了日時は開始日時以降を入力してください。';
		$end_date_ok = false;
	} elseif (date('Y', $end_date) != $enquete->end_date_y || date('n', $end_date) != $enquete->end_date_m || date('j', $end_date) != $enquete->end_date_d) {
		$msg[] = '終了日付が正しく入力されていないようです。';
		$end_date_ok = false;
	}
}

// 通知先のデフォルトをセット
if ($myenq->my_enq_no == '') {
	if ($_SESSION['ss_marketer_type'] == MK_ASSISTANT)
		$myenq->notify_assistant = $_SESSION['ss_marketer_id'];
}

// メールタイトルのデフォルトをセット
$myenq->mail_title = $enquete->title . 'についてのアンケート';

// エラー無しなら次ページへ
if (!$msg) {
	// Myアンケート保存
	$myenq->save_enquete = true;
	$myenq->write_db();
	$my_enq_no = $myenq->my_enq_no;

	// セッション変数削除
	unset($_SESSION['ss_my_enq']);
}
?>
<? marketer_header('Myアンケート', PG_NULL) ?>
<table width="789" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
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
					<td>Myアンケートの実施概要を設定しました。</td>
				</tr>
			</table>
			<br>
			<a href="mkk_myenq_make.php?my_enq_no=<?=$my_enq_no?>"><img src="images/mkk_bt/back.gif" alt="戻る" name="image2" width="108" height="31" border="0"></a>
			</form>
			</div>
		</td>
	</tr>
</table>
<? } else{?>
	<table width="788" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td><? error_msg($msg, 'mkk_myenq_edit1.php?edit=1');?></td>
      </tr>
    </table>
<? } ?>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? marketer_footer() ?>
