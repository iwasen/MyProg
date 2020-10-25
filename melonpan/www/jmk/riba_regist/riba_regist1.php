<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:りばっチャ登録
'******************************************************/

$title_text = $TT_riba_regist;
$title_color = $TC_HAKKOUSHA;

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><?= $title_text ?></title>
<link rel="stylesheet" type="text/css" href="../css/melonpan.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnLoad_body() {
  document.form1.mail_add_main.focus();
}
function isA(val) {
	var numstring ="0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ{}./+-*=~!#$%^&_?@";
	for(var i = 0; i < val.length; i++) {
		if(numstring.indexOf(val.charAt(i)) == -1)
			return false;
	}
	return true;
}
function num_chk(val) {
	var numstring ="0123456789./+-";
	for(var i = 0; i < val.length; i++) {
		if(numstring.indexOf(val.charAt(i)) == -1)
			return false;
	}	return true;
}
function OnSubmit_form1() {
 with (document.form1) {
    if (mail_add_main.value == "") {
      alert("基本メールアドレスを入力してください。");
      mail_add_main.focus();
      return false;
    }
		if (!isA(mail_add_main.value)) {
			alert("基本メールアドレスは半角英数で入力してください。");
			mail_add_main.focus();
			return false;
		}
    if (mail_add_sub.value == "") {
      alert("基本メールアドレス２を入力してください。");
      mail_add_sub.focus();
      return false;
    }
		if (!isA(mail_add_sub.value)) {
			alert("基本メールアドレス２は半角英数で入力してください。");
			mail_add_sub.focus();
			return false;
		}
    if (owner_pswd.value == "") {
      alert("パスワードを入力してください。");
      owner_pswd.focus();
      return false;
    }
    if (owner_nm_kanj.value == "") {
      alert("発行者名を入力してください。");
      owner_nm_kanj.focus();
      return false;
    }
    if (owner_nm_kana.value == "") {
      alert("発行者名[カナ]を入力してください。");
      owner_nm_kana.focus();
      return false;
    }
    if (owner_zip1.value == "") {
      alert("郵便番号を入力してください。");
      owner_zip1.focus();
      return false;
    }
		if (!num_chk(owner_zip1.value)){
			alert("郵便番号は半角の数値で入力してください。");
			owner_zip1.focus();
			return(false);
		}
    if (owner_zip2.value == "") {
      alert("郵便番号を入力してください。");
      owner_zip2.focus();
      return false;
    }
		if (!num_chk(owner_zip2.value)){
			alert("郵便番号は半角の数値で入力してください。");
			owner_zip2.focus();
			return(false);
		}
    if (owner_add.value == "") {
      alert("住所を入力してください。");
      owner_add.focus();
      return false;
    }
    if (owner_tel.value == "") {
      alert("電話番号を入力してください。");
      owner_tel.focus();
      return false;
    }
		if (!isA(owner_tel.value)){
			alert("電話番号は半角の数値で入力してください。");
			owner_tel.focus();
			return(false);
		}
    if (pt_melonpan.value == "") {
      alert("ポイントを入力してください。");
      pt_melonpan.focus();
      return false;
    }
		if (!num_chk(pt_melonpan.value)){
			alert("ポイントは半角の数値で入力してください。");
			pt_melonpan.focus();
			return(false);
		}
  }
	return confirm("登録します。よろしいですか？");
}
//-->
</SCRIPT>
</head>
<body onLoad="OnLoad_body()">

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form method="post" action="riba_regist6.php" name="form1" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width='700'>
		<tr>
			<td class="m0">■【りばっチャ】発行者情報登録</td>
		</tr>
	<table border=0 cellspacing=1 cellpadding=3 width="700">
		<tr>
			<td class="m5" width="25%">基本メールアドレス</td>
			<td class="n5"><input size=50 name=mail_add_main maxlength=50><font class="note">（半角英数）</font><BR></td>
		</tr>
		<tr>
			<td class="m5" width="25%">基本メールアドレス２</td>
			<td class="n5"><input size=50 name=mail_add_sub maxlength=50><font class="note">（半角英数）</font><BR></td>
		</tr>
		<tr>
			<td class="m5">パスワード</td>
			<td class="n5"><input size=20 name=owner_pswd maxlength=20><font class="note">（半角英数：4文字以上8文字以内）</font></td>
		</tr>
		<tr>
			<td class="m5">発行者[漢字]</td>
			<td class="n5"><input size=40 name=owner_nm_kanj maxlength=25><font class="note">（全角）</font></td>
		</tr>
		<tr>
			<td class="m5">発行者[カナ]</td>
			<td class="n5"><input size=40 name=owner_nm_kana maxlength=25><font class="note">（全角）</font></td>
		</tr>
		<tr>
			<td class="m5">郵便番号</td>
			<td class="n5"><input size=3 name=owner_zip1 maxlength=3>-<input size=4 name=owner_zip2 maxlength=4> <font class="note">（半角）</font></td>
		</tr>
		<tr>
			<td class="m5">連絡先住所</td>
			<td class="n5"><select name="owner_prefec"><? SelectKen(); ?></select>
			<input size=50 name=owner_add maxlength=100 value="<?= $owner_add ?>"><font class="note">（全角）</font></td>
		</tr>
		<tr>
			<td class="m5">連絡先電話番号</td>
			<td class="n5"><input size=20 name=owner_tel maxlength=14><font class="note">（半角）</font></td>
		</tr>
		<tr>
			<td class="m5">ポイント</td>
			<td class="n5"><input size=9 DIR=rtl name=pt_melonpan maxlength=14 value=0><font class="note">（半角数値）</font></td>
		</tr>
	</table><br>
	<input type="hidden" name="publisher_id" value=<?= $publisher_id ?>>
	<input type="submit" value=" 登録 ">
	<input type="reset" value="リセット">
	<input type="button" value=" 戻る " onclick="history.back()">
</form>
</center>

<? footer_jmk(0); ?>

</body>
</html>
