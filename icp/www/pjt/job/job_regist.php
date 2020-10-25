<?
/******************************************************
' System :ICPプロジェクトページ
' Content:新規ジョブ登録ページ
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/auth.php");
include("$inc/select.php");

$inc = "$top/inc";
include("$inc/pjt_common.php");
include("$inc/login_check.php");
include("$inc/header.php");

// 権限チェック
if (!check_staff_auth('ジョブ管理'))
	redirect("$top/index.php");
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
	return confirm("ジョブを登録します。よろしいですか？");
}
//-->
	</script>
</head>

<body onload="document.form1.name.focus()">
<div align="center">

<? pjt_header() ?>

<!-- pankuzu -->
<table border="0" cellspacing="0" cellpadding="3" bgcolor="#fdc92b" width="800" class="pankuzu">
<tr>
	<td><a href="../index.php" title="ホーム">ホーム</a>
		&nbsp;&nbsp;＞&nbsp;&nbsp;
		新規ジョブ登録
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
<form method="post" name="form1" action="job_regist_comp.php" onsubmit="return onsubmit_form1(this)">
<table border="0" cellspacing="0" cellpadding="0" width="800" class="main">
<tr>
	<td width="20"><img src="../img/spacer.gif" width="20" height="1" border="0"></td>
	<td width="760">
		<table border="0" cellspacing="0" cellpadding="3" bgcolor="#e4e4e4" width="760" class="contents">
		<tr>
			<td>■新規ジョブ登録</td>
		</tr>
		</table>

		<table border="1" cellspacing="0" cellpadding="2" width="760" class="card">
		<tr>
			<th width="200">ジョブ名</th>
			<td width="560"><input type="text" name="name" value="" size="40" class="form"></td>
		</tr>
		<tr>
			<th>ジョブ詳細</th>
			<td><textarea name="description" cols="60" rows="5" class="form"></textarea></td>
		</tr>
		<tr>
			<th width="200">会議室タイプ</th>
			<td width="560"><input checked type="radio" name="room_type" value="1" id="gi"><label for="gi">ネットグルイン</label><br>
			<input type="radio" name="room_type" value="2" id="pi"><label for="pi">定性メールアンケート</label><br>
			<input type="radio" name="room_type" value="3" id="sr"><label for="sr">バックルーム</label></td>
		</tr>
		<tr>
			<th width="200">会議室名</th>
			<td width="560"><input type="text" name="room_name" value="" size="40" class="form"></td>
		</tr>
		<tr>
			<th>実施期間</th>
			<td><select name="start_year" class="form"><? select_year(2004, '', date('Y')) ?></select>年
				<select name="start_month" class="form"><? select_month('', 1) ?></select>月
				<select name="start_day" class="form"><? select_day('', 1) ?></select>日　〜　
				<select name="end_year" class="form"><? select_year(2004, '', date('Y')) ?></select>年
				<select name="end_month" class="form"><? select_month('', 12) ?></select>月
				<select name="end_day" class="form"><? select_day('', 31) ?></select>日</td>
		</tr>
		<tr>
			<th>最大使用人数</th>
			<td><input type="text" name="member_max" size=4 maxlength=4 class="form">人 （0を入力すると制限なし）</td>
		</tr>
		<tr>
			<th>ジョブコード</th>
			<td><input type="text" name="job_cd" value="" size="40" class="form"> ※半角英数字のみ</td>
		</tr>
		<tr>
			<th>メールアドレス開示</th>
			<td>
				<input type="radio" name="open_mail_addr" value="f" id="open_mail_addr_f" checked><label for="open_mail_addr_f">メールアドレスを開示しない（置換する）</label><br>
				<input type="radio" name="open_mail_addr" value="t" id="open_mail_addr_t"><label for="open_mail_addr_t">メールアドレスを開示する（置換しない）</label>
			</td>
		</tr>
		<tr>
			<th>添付ファイル</th>
			<td>
				<input type="radio" name="attachment_file" value="f" id="attachment_file_f" checked><label for="attachment_file_f">使用しない</label><br>
				<input type="radio" name="attachment_file" value="t" id="attachment_file_t"><label for="attachment_file_t">使用する</label>
			</td>
		</tr>
		</table>
	</td>
	<td width="20"><img src="../img/spacer.gif" width="20" height="1" border="0"></td>
</tr>
</table>
<!-- main -->

<!-- 戻る -->
<br>
<table border="0" cellspacing="0" cellpadding="0" width="800" class="regist">
<tr>
	<td width="10"><img src="../img/spacer.gif" width="10" height="30" border="0"></td>
	<td width="780" align="center"><input type="submit" value="　登録　" class="form">
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
