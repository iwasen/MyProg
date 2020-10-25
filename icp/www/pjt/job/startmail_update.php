<?
/******************************************************
' System :ICPプロジェクトページ
' Content:開始メール設定ページ
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/auth.php");
include("$inc/select.php");

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
$sql = "SELECT jb_start_subject,jb_start_from_name,jb_start_content,jb_start_send_date,jb_start_add_mail"
		. " FROM t_job"
		. " WHERE jb_job_id=" . sql_number($job_id);
$result = db_exec($sql);
if (pg_num_rows($result) > 0) {
	$fetch = pg_fetch_object($result, 0);

	// 送信を年月日時分に分解
	if ($fetch->jb_start_send_date) {
		$send_date = sql_time($fetch->jb_start_send_date);
		$send_year = date('Y', $send_date);
		$send_month = date('m', $send_date);
		$send_day = date('d', $send_date);
		$send_hour = date('H', $send_date);
		$send_minute = date('i', $send_date);
	} else
		$send_year = date('Y');
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
function onsubmit_form1(f) {
	if (f.subject.value == "") {
		alert("サブジェクトを入力してください。");
		f.subject.focus();
		return false;
	}
	if (f.content.value == "") {
		alert("本文を入力してください。");
		f.content.focus();
		return false;
	}
	if (!check_date(f.send_year.value, f.send_month.value, f.send_day.value)) {
		alert("送信日の指定が正しくありません。");
		f.send_day.focus();
		return(false);
	}
	var now = new Date();
	var send_date = new Date(f.send_year.value, f.send_month.value - 1, f.send_day.value, f.send_hour.value, f.send_minute.value);
	if (send_date < now) {
		if (!confirm("送信日に過去の日付が入力されていますが、よろしいですか？")) {
			f.send_year.focus();
			return false;
		}
	}
	switch (f.proc.value) {
	case "preview":
		f.action = "startmail_preview.php";
		f.target = "_blank";
		return true;
	case "testmail":
		f.action = "startmail_testmail.php";
		f.target = "_self";
		return confirm("テストメールを送信します。よろしいですか？");
	case "update":
		f.action = "startmail_update_comp.php";
		f.target = "_self";
		return confirm("開始メールを更新します。よろしいですか？");
	}
	return false;
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
		開始メール設定更新
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
<form method="post" name="form1" action="startmail_update_comp.php" onsubmit="return onsubmit_form1(this)">
<input type="hidden" name="job_id" <?=value($job_id)?>>
<table border="0" cellspacing="0" cellpadding="0" width="800" class="main">
<tr>
	<td width="20"><img src="../img/spacer.gif" width="20" height="1" border="0"></td>
	<td width="760">
		<table border="0" cellspacing="0" cellpadding="3" width="760" class="contents">
		<tr>
			<td>■開始メール設定更新</td>
		</tr>
		</table>

		<table border="1" cellspacing="0" cellpadding="2" width="760" class="card">
		<tr>
			<th width="200">サブジェクト</th>
			<td width="560"><input type="text" name="subject" <?=value($fetch->jb_start_subject)?> size="60" class="form"></td>
		</tr>
		<tr>
			<th>差出人名称</th>
			<td><input type="text" name="from_name" <?=value($fetch->jb_start_from_name)?> size="30" class="form"></td>
		</tr>
		<tr>
			<th>本文</th>
			<td><textarea name="content" cols="80" rows="30" class="form"><?=htmlspecialchars($fetch->jb_start_content)?></textarea></td>
		</tr>
		<tr>
			<th>送信日</th>
			<td><select name="send_year" class="form"><? select_year(2004, '', $send_year) ?></select>年
				<select name="send_month" class="form"><? select_month('', $send_month) ?></select>月
				<select name="send_day" class="form"><? select_day('', $send_day) ?></select>日
				<select name="send_hour" class="form"><? select_hour('', $send_hour) ?></select>時
				<select name="send_minute" class="form"><? select_minute('', $send_minute) ?></select>分
			</td>
		</tr>
		<tr>
			<th>同報先</th>
			<td><textarea name="add_mail" cols="60" rows="5" class="form"><?=str_replace(' ', ",\n", htmlspecialchars($fetch->jb_start_add_mail))?></textarea><br>
カンマ区切りで入力して下さい。</td>
		</tr>
		</table>
		</td>
	<td width="20"><img src="../img/spacer.gif" width="20" height="1" border="0"></td>
</tr>
</table>

<!-- main -->

<!-- 戻る -->
<table border="0" cellspacing="0" cellpadding="0" width="800" class="regist">
<tr>
	<td width="10"><img src="../img/spacer.gif" width="10" height="30" border="0"></td>
	<td width="780" align="center">
		<input type="submit" value="メールプレビュー" class="form" onclick="document.form1.proc.value='preview'">
		<input type="submit" value="テストメール送信" class="form" onclick="document.form1.proc.value='testmail'">
		<input type="submit" value="　更新　" class="form" onclick="document.form1.proc.value='update'">
		<input type="button" value="キャンセル" class="form" onclick="history.back()">
		<input type="hidden" name="proc">
	</td>
	<td width="10"><img src="../img/spacer.gif" width="10" height="30" border="0"></td>
</tr>
</table>
</form>
<!-- 戻る -->

<? pjt_footer() ?>

</div>
</body>

</html>
