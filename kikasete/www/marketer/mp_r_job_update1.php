<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_partner.php");
include("$inc/check.php");
include("$inc/get_form.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");
include("$inc/mk_error.php");

// Myパートナークラス取得
$myp = &$_SESSION['s_my_partner'];
$pjt_id = $myp->pjt_id;
if (!$pjt_id)
	redirect('mp_pjt_list.php');

// フォームからクラスへ代入
$myp->recruit_start_y = get_number($recruit_start_y);
$myp->recruit_start_m = get_number($recruit_start_m);
$myp->recruit_start_d = get_number($recruit_start_d);
$myp->recruit_start_h = get_number($recruit_start_h);
$myp->recruit_end_y = get_number($recruit_end_y);
$myp->recruit_end_m = get_number($recruit_end_m);
$myp->recruit_end_d = get_number($recruit_end_d);
$myp->recruit_end_h = get_number($recruit_end_h);

// リクルート条件の取得
$seq_no = 1;
$myp->recruit_jouken = array();
for ($i = 1; $i <= $myp->recruit_jouken_num; $i++) {
	if ($_POST["recruit_jouken$i"] != '') {
		$myp->recruit_jouken[$seq_no] = $_POST["recruit_jouken$i"];
		$seq_no++;
	}
}

// リクルート条件追加処理
if ($next_action == 'add_recruit_jouken') {
	$myp->recruit_jouken_num++;
	redirect('mp_r_job_edit1.php?edit=1');
}

// リクルート期間の開始年チェック
$recruit_start_ok = true;
if ($myp->recruit_start_y == '') {
	$msg[] = 'リクルート期間の開始年が入力されていないようです。';
	$recruit_start_ok = false;
} elseif (!check_num($myp->recruit_start_y, 2004, 2100)) {
	$msg[] = 'リクルート期間の開始年が正しく入力されていないようです。';
	$recruit_start_ok = false;
}

// リクルート期間の開始月チェック
if ($myp->recruit_start_m == '') {
	$msg[] = 'リクルート期間の開始月が入力されていないようです。';
	$recruit_start_ok = false;
} elseif (!check_num($myp->recruit_start_m, 1, 12)) {
	$msg[] = 'リクルート期間の開始月が正しく入力されていないようです。';
	$recruit_start_ok = false;
}

// リクルート期間の開始日チェック
if ($myp->recruit_start_d == '') {
	$msg[] = 'リクルート期間の開始日が入力されていないようです。';
	$recruit_start_ok = false;
} elseif (!check_num($myp->recruit_start_d, 1, 31)) {
	$msg[] = 'リクルート期間の開始日が正しく入力されていないようです。';
	$recruit_start_ok = false;
}

// リクルート期間の開始時間チェック
if ($myp->recruit_start_h == '') {
	$msg[] = 'リクルート期間の開始時間が入力されていないようです。';
	$recruit_start_ok = false;
} elseif (!check_num($myp->recruit_start_h, 0, 23)) {
	$msg[] = 'リクルート期間の開始時間が正しく入力されていないようです。';
	$recruit_start_ok = false;
}

// リクルート期間の開始年月日正当性チェック
if ($recruit_start_ok) {
	if (!checkdate($myp->recruit_start_m, $myp->recruit_start_d, $myp->recruit_start_y)) {
		$msg[] = 'リクルート期間の開始年月日が正しく入力されていないようです。';
		$recruit_start_ok = false;
	} else
		$recruit_start = mktime($myp->recruit_start_h, 0, 0, $myp->recruit_start_m, $myp->recruit_start_d, $myp->recruit_start_y);
}

// リクルート期間の終了年チェック
$recruit_end_ok = true;
if ($myp->recruit_end_y == '') {
	$msg[] = 'リクルート期間の終了年が入力されていないようです。';
	$recruit_end_ok = false;
} elseif (!check_num($myp->recruit_end_y, 2004, 2100)) {
	$msg[] = 'リクルート期間の終了年が正しく入力されていないようです。';
	$recruit_end_ok = false;
}

// リクルート期間の終了月チェック
if ($myp->recruit_end_m == '') {
	$msg[] = 'リクルート期間の終了月が入力されていないようです。';
	$recruit_end_ok = false;
} elseif (!check_num($myp->recruit_end_m, 1, 12)) {
	$msg[] = 'リクルート期間の終了月が正しく入力されていないようです。';
	$recruit_end_ok = false;
}

// リクルート期間の終了日チェック
if ($myp->recruit_end_d == '') {
	$msg[] = 'リクルート期間の終了日が入力されていないようです。';
	$recruit_end_ok = false;
} elseif (!check_num($myp->recruit_end_d, 1, 31)) {
	$msg[] = 'リクルート期間の終了日が正しく入力されていないようです。';
	$recruit_end_ok = false;
}

// リクルート期間の終了時間チェック
if ($myp->recruit_end_h == '') {
	$msg[] = 'リクルート期間の終了時間が入力されていないようです。';
	$recruit_end_ok = false;
} elseif (!check_num($myp->recruit_end_h, 0, 23)) {
	$msg[] = 'リクルート期間の終了時間が正しく入力されていないようです。';
	$recruit_end_ok = false;
}

// リクルート期間の終了年月日正当性チェック
if ($recruit_end_ok) {
	if (!checkdate($myp->recruit_end_m, $myp->recruit_end_d, $myp->recruit_end_y)) {
		$msg[] = 'リクルート期間の終了年月日が正しく入力されていないようです。';
		$recruit_end_ok = false;
	} else
		$recruit_end = mktime($myp->recruit_end_h, 0, 0, $myp->recruit_end_m, $myp->recruit_end_d, $myp->recruit_end_y);
}

// リクルート期間の開始と終了年月日の関係チェック
if ($recruit_start && $recruit_end) {
	if ($recruit_start >= $recruit_end)
		$msg[] = 'リクルート期間の終了日は開始日より後の日付を入力してください。';
}

// リクルート条件入力チェック
if (count($myp->recruit_jouken) == 0)
	$msg[] = 'リクルート条件を１つ以上入力してください。';

if (!$msg) {
	// DBに保存
	$myp->save_recruit_jouken = true;
	$myp->recruit_flag |= 0x01;
	$myp->write_db();

	// セッション変数削除
	unset($_SESSION['s_my_partner']);
}
?>
<? marketer_header('Myパートナー', PG_NULL) ?>
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
					<td width="768" valign="top"><img src="images/t_mk_mymonitor2.gif" width="538" height="20"></td>
				</tr>
			</table>
			<!-- myパートナー -->
			<div align="center">
			<form>
			<table border=0 cellspacing=2 cellpadding=3 width="95%">
				<tr>
					<td>Myパートナーの実施概要を設定しました。</td>
				</tr>
			</table>
			<br>

			<a href="mp_r_job.php?pjt_id=<?=$pjt_id?>"><img src="images/mkk_bt/back.gif" alt="戻る" name="image2" width="108" height="31" border="0"></a>
			</form>
			</div>
		</td>
	</tr>
</table>
<? } else{?>
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
