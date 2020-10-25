<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:メールアドレス・クリーニング・サービス
'******************************************************/

$title_text = $TT_mail_cleaning;
$title_color = $TC_OTHER;

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><?= $title_text ?></title>
<link rel="stylesheet" type="text/css" href="../css/melonpan.css">
<SCRIPT LANGUAGE=javascript>
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
function num_chk(v1) {
	var p1 = v1;
	for (i=0; i<p1.length; i++){
		if (p1.substring(i,i+1)<"0" || p1.substring(i,i+1)>"9") {
			return(false);
		}
	}
	return true;
}
function isA(val) {
	var numstring ="0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ{}./+-*=~!#$%^&_?@";
	for(var i = 0; i < val.length; i++) {
		if(numstring.indexOf(val.charAt(i)) == -1)
			return false;
	}
	return true;
}
function OnSubmit_form1() {
	with (document.form1) {
    if (data_file.value == "") {
      alert("ファイルを指定してください。");
      data_file.focus();
      return false;
    }
    if (job_name.value == "") {
      alert("名称を入力してください。");
      job_name.focus();
      return false;
    }
    if (client_name.value == "") {
      alert("クライアント企業名を入力してください。");
      client_name.focus();
      return false;
    }
    if (subject.value == "") {
      alert("件名を入力してください。");
      subject.focus();
      return false;
    }
    if (from.value == "") {
      alert("Fromアドレスを入力してください。");
      from.focus();
      return false;
    }
		if (!isA(from.value)) {
			alert("Fromアドレスは半角英数で入力してください。");
			from.focus();
			return false;
		}
    if (reply_to.value != "") {
	 		if (!isA(reply_to.value)) {
				alert("Reply_Toアドレスは半角英数で入力してください。");
				reply_to.focus();
				return false;
			}
		}
    if (body.value == "") {
      alert("本文を入力してください。");
      body.focus();
      return false;
    }
		if (!check_date(start_year.value, start_month.value, start_day.value)) {
			alert("日付の指定が正しくありません。");
			start_day.focus();
			return(false);
		}
		dd = new Date();
		dd = new Date(dd.getYear(), dd.getMonth(), dd.getDate());
		var dt = new Date(start_year.value, start_month.value - 1, start_day.value);
		if (dd > dt) {
			alert("過去の日付は指定できません。");
			start_day.focus();
			return(false);
		}
    if (start_hour.value == "") {
      alert("開始時間を選択してください。");
      start_hour.focus();
      return false;
    }
		if (!check_date(end_year.value, end_month.value, end_day.value)) {
			alert("日付の指定が正しくありません。");
			end_day.focus();
			return(false);
		}
		dd = new Date();
		dd = new Date(dd.getYear(), dd.getMonth(), dd.getDate());
		var dt = new Date(end_year.value, end_month.value - 1, end_day.value);
		if (dd > dt) {
			alert("過去の日付は指定できません。");
			end_day.focus();
			return(false);
		}
    if (end_hour.value == "") {
      alert("終了時間を選択してください。");
      end_hour.focus();
      return false;
    }
	}
	return confirm("登録処理を開始します。よろしいですか？");
}

//-->
</SCRIPT>
</head>
<body>

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form method="post" ENCTYPE="multipart/form-data" action="cleaning3.php" name="form1" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width='750'>
		<tr>
			<td class="m0">■メール・クリーニング・サービス設定</td>
		</tr>
	</table>
	<table border=0 cellspacing=2 cellpadding=3 width="750">
		<tr>
			<td class="m4"><nobr>メールアドレスリストファイル</nobr></td>
			<td class="n6"><input size=60 name="data_file" type="file" ></td>
		</tr>
		<tr>
			<td class="m4"><nobr>名称</nobr></td>
			<td class="n6"><input size=60 name="job_name" maxlength=50></td>
		</tr>
		<tr>
			<td class="m4"><nobr>クライアント企業名</nobr></td>
			<td class="n6"><input size=60 name="client_name" maxlength=50></td>
		</tr>
		<tr>
			<td class="m4"><nobr>件名</nobr></td>
			<td class="n6"><input size=60 name="subject" maxlength=100></td>
		</tr>
		<tr>
			<td class="m4"><nobr>Fromアドレス</nobr></td>
			<td class="n6"><input size=60 name="from" maxlength=50><font class="note">（半角英数）</font></td>
		</tr>
		<tr>
			<td class="m4"><nobr>Reply-Toアドレス</nobr></td>
			<td class="n6"><input size=60 name="reply_to" maxlength=50><font class="note">（半角英数）</font></td>
		</tr>
		<tr>
			<td class="m4"><nobr>本文</nobr></td>
			<td class="n6"><textarea name="body" cols=72 rows=20></textarea></td>
		</tr>
		<tr>
			<td class="m4"><nobr>開始日時</nobr></td>
			<td class="n6">
				<select name="start_year"><? select_year(2001, ' ', ''); ?></select>年
				<select name="start_month"><? select_month(' ', ''); ?></select>月
				<select name="start_day"><? select_day(' ', ''); ?></select>日
				<select name="start_hour"><? select_hour(' ', ''); ?></select>
			</td>
		</tr>
		<tr>
			<td class="m4"><nobr>終了日時</nobr></td>
			<td class="n6">
				<select name="end_year"><? select_year(2001, ' ', ''); ?></select>年
				<select name="end_month"><? select_month(' ', ''); ?></select>月
				<select name="end_day"><? select_day(' ', ''); ?></select>日
				<select name="end_hour"><? select_hour(' ', ''); ?></select>
			</td>
		</tr>
	</table>
	<br>
	<input type="hidden" name="status" value="0" >
	<input type="submit" value="登録処理">
	<input type="reset" value="リセット">
	<input type="button" value=" 戻る " onclick="history.back()">
</form>
</center>

<? footer_jmk(0); ?>

</body>
</html>
