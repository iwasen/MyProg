<?
/******************************************************
' System :ICPプロジェクトページ
' Content:ジョブ基本設定更新ページ
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/auth.php");
include("$inc/decode.php");
include("$inc/format.php");
include("$inc/select.php");
include("$inc/status.php");

$inc = "$top/inc";
include("$inc/pjt_common.php");
include("$inc/login_check.php");
include("$inc/header.php");

// 権限チェック
if (!check_staff_auth('ジョブ管理'))
	redirect("$top/index.php");

// 入力パラメータ
$job_id = $_POST['job_id'];

// ジョブ名（ジョブコード）取得
$job_name_cd = get_job_name_cd($job_id);

// ジョブ情報取得
$sql = "SELECT * FROM t_job WHERE jb_job_id=" . sql_number($job_id);
$result = db_exec($sql);
if (pg_num_rows($result) > 0) {
	$fetch = pg_fetch_object($result, 0);

	// 状態コード取得
	$status = $fetch->jb_status;

	// 実施開始日付を年月日に分解
	$start_date = sql_time($fetch->jb_start_date);
	$start_year = date('Y', $start_date);
	$start_month = date('m', $start_date);
	$start_day = date('d', $start_date);

	// 実施終了日付を年月日に分解
	$end_date = sql_time($fetch->jb_end_date);
	$end_year = date('Y', $end_date);
	$end_month = date('m', $end_date);
	$end_day = date('d', $end_date);
} else
	program_err(__FILE__, __LINE__);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">

<head>
	<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
	<meta http-equiv="Pragma" content="no-cache">
	<title><?=htmlspecialchars($_SESSION[SCD]['pjt_name'])?>　icp.net</title>
	<link rel="stylesheet" type="text/css" href="<?=$top?>/default.css">
	<script type="text/javascript">
<!--
function goto(page) {
	var f = document.goto1;
	f.action = page;
	f.submit();
}
function check_date(y, m, d) {
	var dt = new Date(y, m - 1, d);
	if (isNaN(dt))
		return false;
	else {
		if (dt.getYear() == y && dt.getMonth() + 1 == m && dt.getDate() == d)
		return true;
	else
		return false;
	}
}
function check_num(s) {
	for (i = 0; i < s.length; i++) {
		if (s.charAt(i) < "0" || s.charAt(i) > "9") {
			return false;
		}
	}
	return true;
}
function onsubmit_form1(f) {
	if (f.name.value == "") {
		alert("ジョブ名を入力してください。");
		f.name.focus();
		return false;
	}
	if (f.job_cd.value.match(/[0-9A-Za-z_]+/g) != f.job_cd.value) {
		alert("ジョブコードは半角英数字で入力してください。");
		f.job_cd.focus();
		return false;
	}
	if (f.room_name.value == "") {
		alert("会議室名を入力してください。");
		f.room_name.focus();
		return false;
	}
	var now = new Date();
	today = new Date(now.getYear(), now.getMonth(), now.getDate());
	if (!check_date(f.start_year.value, f.start_month.value, f.start_day.value)) {
		alert("実施開始日付の指定が正しくありません。");
		f.start_day.focus();
		return(false);
	}
	var start_date = new Date(f.start_year.value, f.start_month.value - 1, f.start_day.value);
	if (start_date < today) {
		if (!confirm("実施開始日付に過去の日付が入力されていますが、よろしいですか？")) {
			f.start_year.focus();
			return false;
		}
	}
	if (!check_date(f.end_year.value, f.end_month.value, f.end_day.value)) {
		alert("実施終了日付の指定が正しくありません。");
		f.end_day.focus();
		return(false);
	}
	var end_date = new Date(f.end_year.value, f.end_month.value - 1, f.end_day.value);
	if (start_date > end_date) {
		alert("実施終了日付は実施開始日付より後の日付を入力してください。");
		f.end_year.focus();
		return false;
	}
	if (f.member_max.value == "") {
		alert("最大使用人数を入力してください。");
		f.member_max.focus();
		return false;
	}
	if (!check_num(f.member_max.value)) {
		alert("最大使用人数は半角数字で入力してください。");
		f.member_max.focus();
		return false;
	}
	if (f.job_cd.value == "") {
		alert("ジョブコードを入力してください。");
		f.job_cd.focus();
		return false;
	}
	if (!f.job_cd.value.match(/^[\w_]+$/)) {
		alert("ジョブコードは半角英数字で入力してください。");
		f.job_cd.focus();
		return false;
	}
	return confirm("ジョブを更新します。よろしいですか？");
}
//-->
	</script>
</head>

<body>
<div align="center">

<form method="post" name="goto1">
<input type="hidden" name="job_id" <?=value($job_id)?>>
</form>

<? pjt_header() ?>

<!-- pankuzu -->
<table border="0" cellspacing="0" cellpadding="3" bgcolor="#fdc92b" width="800" class="pankuzu">
<tr>
	<td><a href="../index.php" title="ホーム">ホーム</a>
		&nbsp;&nbsp;＞&nbsp;&nbsp;
		<a href="javascript:goto('detail.php')" title="このジョブの詳細を表示します"><?=htmlspecialchars($job_name_cd)?>情報</a>
		&nbsp;&nbsp;＞&nbsp;&nbsp;
		基本設定更新
		</td>
</tr>
</table>
<!-- pankuzu -->

<!-- 新規ジョブ -->
<table border="0" cellspacing="0" cellpadding="0" width="800" class="regist">
<tr>
	<td width="10"><img src="../img/spacer.gif" width="10" height="30" border="0"></td>
	<td width="790" align="right"><img src="../img/spacer.gif" width="10" height="30" border="0"></td>
</tr>
</table>
<!-- 新規ジョブ -->

<!-- main -->
<form method="post" name="form1" action="basic_update_comp.php" onsubmit="return onsubmit_form1(this)">
<input type="hidden" name="job_id" <?=value($job_id)?>>
<table border="0" cellspacing="0" cellpadding="0" width="800" class="main">
<tr>
	<td width="20"><img src="../img/spacer.gif" width="20" height="1" border="0"></td>
	<td width="760">
		<table border="0" cellspacing="0" cellpadding="3" bgcolor="#e4e4e4" width="760" class="contents">
		<tr>
			<td>■ジョブ基本設定更新</td>
		</tr>
		</table>

		<table border="1" cellspacing="0" cellpadding="2" width="760" class="card">
		<tr>
			<th width="200">ジョブ名（ジョブID）</th>
			<td width="560"><input type="text" name="name" <?=value($fetch->jb_name)?> size="40" class="form">（<?=$job_id?>）</td>
		</tr>
		<tr>
			<th>ジョブ詳細</th>
			<td><textarea name="description" cols="60" rows="5" class="form"><?=htmlspecialchars($fetch->jb_description)?></textarea></td>
		</tr>
		<tr>
			<th width="200">会議室タイプ</th>
			<td width="560">
<?
if ($status == $g_job_status['申請前']) {
?>
				<input type="radio" name="room_type" <?=value_checked('1', $fetch->jb_room_type)?> id="gi"><label for="gi">ネットグルイン</label><br>
				<input type="radio" name="room_type" <?=value_checked('2', $fetch->jb_room_type)?> id="pi"><label for="pi">定性メールアンケート</label><br>
				<input type="radio" name="room_type" <?=value_checked('3', $fetch->jb_room_type)?> id="sr"><label for="sr">バックルーム</label>
<?
} else {
?>
				<input type="hidden" name="room_type" <?=value($fetch->jb_room_type)?>>
				<?=decode_room_type($fetch->jb_room_type)?>
<?
}
?>
			</td>
		</tr>
		<tr>
			<th width="200">会議室名</th>
			<td width="560"><input type="text" name="room_name" <?=value($fetch->jb_room_name)?> size="40" class="form"></td>
		</tr>
		<tr>
			<th>実施期間</th>
			<td>
<?
if ($status == $g_job_status['申請前']) {
?>
				<select name="start_year" class="form"><? select_year(2004, '', $start_year) ?></select>年
				<select name="start_month" class="form"><? select_month('', $start_month) ?></select>月
				<select name="start_day" class="form"><? select_day('', $start_day) ?></select>日　〜　
				<select name="end_year" class="form"><? select_year(2004, '', $end_year) ?></select>年
				<select name="end_month" class="form"><? select_month('', $end_month) ?></select>月
				<select name="end_day" class="form"><? select_day('', $end_day) ?></select>日
<?
} else {
?>
				<input type="hidden" name="start_year" <?=value($start_year)?>>
				<input type="hidden" name="start_month" <?=value($start_month)?>>
				<input type="hidden" name="start_day" <?=value($start_day)?>>
				<input type="hidden" name="end_year" <?=value($end_year)?>>
				<input type="hidden" name="end_month" <?=value($end_month)?>>
				<input type="hidden" name="end_day" <?=value($end_day)?>>
				<?=format_date_j($fetch->jb_start_date)?> 〜 <?=format_date_j($fetch->jb_end_date)?>
<?
}
?>
			</td>
		</tr>
		<tr>
			<th>最大使用人数</th>
			<td>
<?
if ($status == $g_job_status['申請前']) {
?>
				<input type="text" name="member_max" size=4 maxlength=4 class="form" <?=value($fetch->jb_member_max)?>></select>人 （0を入力すると制限なし）
<?
} else {
?>
				<input type="hidden" name="member_max" <?=value($fetch->jb_member_max)?>>
				<?=$fetch->jb_member_max ? number_format($fetch->jb_member_max) . '人' : '制限なし'?>
<?
}
?>
			</td>
		</tr>
		<tr>
			<th>ジョブコード</th>
			<td>
<?
if ($status == $g_job_status['申請前']) {
?>
				<input type="text" name="job_cd" <?=value($fetch->jb_job_cd)?> size="40" class="form"> ※半角英数字のみ
<?
} else {
?>
				<input type="hidden" name="job_cd" <?=value($fetch->jb_job_cd)?>>
				<?=htmlspecialchars($fetch->jb_job_cd)?>
<?
}
?>
			</td>
		</tr>
		<tr>
			<th>メールアドレス開示</th>
			<td>
<?
if ($status == $g_job_status['申請前']) {
?>
				<input type="radio" name="open_mail_addr" <?=value_checked(DBFALSE, $fetch->jb_open_mail_addr)?> id="open_mail_addr_f"><label for="open_mail_addr_f">メールアドレスを開示しない（置換する）</label><br>
				<input type="radio" name="open_mail_addr" <?=value_checked(DBTRUE, $fetch->jb_open_mail_addr)?> id="open_mail_addr_t"><label for="open_mail_addr_t">メールアドレスを開示する（置換しない）</label>
<?
} else {
	if ($fetch->jb_open_mail_addr == DBFALSE) {
?>
				メールアドレスを開示しない（置換する）
<?
	} else {
?>
				メールアドレスを開示する（置換しない）
<?
	}
?>
				<input type="hidden" name="open_mail_addr" <?=value($fetch->jb_open_mail_addr)?>>
<?
}
?>
			</td>
		</tr>
		<tr>
			<th>添付ファイル</th>
			<td>
<?
if ($status == $g_job_status['申請前']) {
?>
				<input type="radio" name="attachment_file" <?=value_checked(DBFALSE, $fetch->jb_attachment_file)?> id="attachment_file_f"><label for="attachment_file_f">使用しない</label><br>
				<input type="radio" name="attachment_file" <?=value_checked(DBTRUE, $fetch->jb_attachment_file)?> id="attachment_file_t"><label for="attachment_file_t">使用する</label>
<?
} else {
	if ($fetch->jb_attachment_file == DBFALSE) {
?>
				使用しない
<?
	} else {
?>
				使用する
<?
	}
?>
				<input type="hidden" name="attachment_file" <?=value($fetch->jb_attachment_file)?>>
<?
}
?>
			</td>
		</tr>
		<tr>
			<th>インポート済み人数</th>
			<td><a href="javascript:goto('list.php')" title="このジョブに関連づいているメンバーのリストを表示します"><?=number_format($fetch->jb_member_num)?>人</a></td>
		</tr>
		<tr>
			<th>状態</th>
			<td><?=decode_job_status($status)?></td>
		</tr>
		</table>
	</td>
</tr>
</table>
<!-- main -->

<!-- 戻る -->
<br>
<table border="0" cellspacing="0" cellpadding="0" width="800" class="regist">
<tr>
	<td width="10"><img src="../img/spacer.gif" width="10" height="30" border="0"></td>
	<td width="780" align="center"><input type="submit" value="　更新　" class="form">
	<input type="button" value="キャンセル" class="form" onclick="history.back()"></td>
	<td width="10"><img src="../img/spacer.gif" width="10" height="30" border="0"></td>
</tr>
</table>
</form>
<!-- 戻る -->

<? pjt_footer() ?>

</div>
</body>

</html>
