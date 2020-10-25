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

//'=== Main ===
$sql ="SELECT publisher_id, mail_add_main, mail_add_sub, owner_pswd, owner_nm_kanj, owner_nm_kana, owner_zip1, owner_zip2"
	. ", owner_prefec, owner_add, owner_tel, owner_riba_flg, pt_melonpan"
	. ", TO_CHAR(modefy_dt, 'YYYY') as modi_year"
	. ", TO_CHAR(modefy_dt, 'MM') as modi_month"
	. ", TO_CHAR(modefy_dt, 'DD') as modi_day"
	. ", TO_CHAR(modefy_dt, 'hh24:mi:ss') as modi_time"
	. ", TO_CHAR(regist_dt, 'YYYY') as reg_year"
	. ", TO_CHAR(regist_dt, 'MM') as reg_month"
	. ", TO_CHAR(regist_dt, 'DD') as reg_day"
	. ", TO_CHAR(regist_dt, 'hh24:mi:ss') as reg_time"
	. " FROM M_PUBLISHER"
	. " WHERE publisher_id='$publisher_id'";
$result = db_exec($sql);
if (!pg_numrows($result)) {
		header ("location: ../admin/err_msg.php?id=0901");
	exit;
} else {
	$fetch = pg_fetch_object($result, 0);
	$publisher_id = $fetch->publisher_id;
	$mail_add_main = $fetch->mail_add_main;
	$mail_add_sub = $fetch->mail_add_sub;
	$owner_pswd = $fetch->owner_pswd;
	$owner_nm_kanj = $fetch->owner_nm_kanj;
	$owner_nm_kana = $fetch->owner_nm_kana;
	$owner_zip1 = $fetch->owner_zip1;
	$owner_zip2 = $fetch->owner_zip2;
	$owner_prefec = $fetch->owner_prefec;
	$owner_add = $fetch->owner_add;
	$owner_tel = $fetch->owner_tel;
	$owner_riba_flg = $fetch->owner_riba_flg;
	$pt_melonpan = $fetch->pt_melonpan;
	$modi_year = $fetch->modi_year;
	$modi_month = $fetch->modi_month;
	$modi_day = $fetch->modi_day;
	$modi_time = $fetch->modi_time;
	$reg_year = $fetch->reg_year;
	$reg_month = $fetch->reg_month;
	$reg_day = $fetch->reg_day;
	$reg_time = $fetch->reg_time;
	if ($owner_riba_flg == '1') {
		$riba = " checked";
	} if ($owner_riba_flg <> '1') {
		$no_riba = " checked";
	}
	
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><?= $title_text ?></title>
<link rel="stylesheet" type="text/css" href="../css/melonpan.css">
<SCRIPT LANGUAGE=javascript>
<!--
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
		if (!num_chk(owner_tel.value)){
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
	return confirm("更新します。よろしいですか？");
}
function OnClick_sakujo(publisher_id) {
	location.href = "riba_regist4.php?pub_id=" + publisher_id;
}
//-->
</SCRIPT>
</head>
<body>

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form method="post" action="riba_regist3.php" name="form1" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width='700'>
		<tr>
			<td class="m0">■発行者情報メンテナンス</td>
		</tr>
	<table border=0 cellspacing=1 cellpadding=3 width="700">
		<tr>
			<td class="m5" width="25%">基本メールアドレス</td>
			<td class="n5"><input size=50 name=mail_add_main maxlength=50 <?= value($mail_add_main) ?>><font class="note">（半角英数）</font><BR></td>
		</tr>
		<tr>
			<td class="m5" width="25%">基本メールアドレス２</td>
			<td class="n5"><input size=50 name=mail_add_sub maxlength=50 <?= value($mail_add_sub) ?>><font class="note">（半角英数）</font><BR></td>
		</tr>
		<tr>
			<td class="m5">パスワード</td>
			<td class="n5"><input size=20 name=owner_pswd maxlength=20 <?= value($owner_pswd) ?>><font class="note">（半角英数：4文字以上8文字以内）</font></td>
		</tr>
		<tr>
			<td class="m5">発行者[漢字]</td>
			<td class="n5"><input size=40 name=owner_nm_kanj maxlength=25 <?= value($owner_nm_kanj) ?>><font class="note">（全角）</font></td>
		</tr>
		<tr>
			<td class="m5">発行者[カナ]</td>
			<td class="n5"><input size=40 name=owner_nm_kana maxlength=25 <?= value($owner_nm_kana) ?>><font class="note">（全角）</font></td>
		</tr>
		<tr>
			<td class="m5">郵便番号</td>
			<td class="n5"><input size=3 name=owner_zip1 maxlength=3 value="<?= $owner_zip1 ?>">-<input size=4 name=owner_zip2 maxlength=4 value="<?= $owner_zip2 ?>"> <font class="note">（半角）</font></td>
		</tr>
		<tr>
			<td class="m5">連絡先住所</td>
			<td class="n5"><select name="owner_prefec"><? SelectKen($owner_prefec); ?></select>
				<input size=50 name=owner_add maxlength=100 <?= value($owner_add) ?>><font class="note">（全角）</font></td>
		</tr>
		<tr>
			<td class="m5">連絡先電話番号</td>
			<td class="n5"><input size=20 name=owner_tel maxlength=14 value="<?= $owner_tel ?>"><font class="note">（半角）</font></td>
		</tr>
		<tr>
			<td class="m5">ポイント</td>
			<td class="n5"><input size=9 DIR=rtl name=pt_melonpan maxlength=14 value="<?= $pt_melonpan ?>"><font class="note">（半角数値）</font></td>
		</tr>
		<tr>
			<td class="m5">登録日</td>
<? if ($reg_year == "") { ?>
			<td class="n5" name="modify_dt">-</td>
<? } else { ?>
			<td class="n5" name="modify_dt"><? print $reg_year . "年" . sprintf("%2d", $reg_month) . "月" . sprintf("%2d", $reg_day) . "日 " . $reg_time; ?></td>
<? } ?>
		</tr>
		<tr>
			<td class="m5">最終更新日</td>
			<td class="n5" name="reg_dt"><? print $modi_year . "年" . sprintf("%2d", $modi_month) . "月" . sprintf("%2d", $modi_day) . "日 " . $modi_time; ?></td>
		</tr>
<?PHP
}
?>
	</table><br>
	<input type="hidden" name="old_mail_add_main" value="<?= $mail_add_main ?>">
	<input type="hidden" name="publisher_id" value=<?= $publisher_id ?>>
	<input type="submit" value=" 更新 ">
	<input type="reset" value="リセット">
	<input type="button" value=" 削除 " onclick="OnClick_sakujo('<?= $publisher_id ?>')">
	<input type="button" value=" 戻る " onclick="history.back()">
</form>
</center>

<? footer_jmk(0); ?>

</body>
</html>
