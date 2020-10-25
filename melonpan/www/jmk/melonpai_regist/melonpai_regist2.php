<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:メルマガナビ登録
'******************************************************/

$title_text = $TT_melonpai_regist;
$title_color = $TC_DOKUSHA;

$sql = "SELECT * FROM M_MELONPAI WHERE melonpai_id='$melonpai_id'";
$result = db_exec($sql);
$nrow = pg_numrows($result);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><?= $title_text ?></title>
<link rel="stylesheet" type="text/css" href="../css/melonpan.css">
<SCRIPT LANGUAGE=javascript>
<!--
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
    if (name.value == "") {
      alert("氏名を入力してください。");
      name.focus();
      return false;
    }
    if (mail_addr.value == "") {
      alert("メールアドレスを入力してください。");
      mail_addr.focus();
      return false;
    }
		if (!isA(mail_addr.value)) {
			alert("メールアドレスは半角英数で入力してください。");
			mail_addr.focus();
			return false;
		}
		if (pswd.value == "") {
			alert("パスワードを入力してください。");
			pswd.focus();
			return false;
		}
		if (!isA(pswd.value)) {
			alert("パスワードは半角英数で入力してください。");
			pswd.focus();
			return false;
		}
		if (!isA(zip1.value)) {
			alert("郵便番号は半角英数で入力してください。");
			zip1.focus();
			return false;
		}
		if (!isA(zip2.value)) {
			alert("郵便番号は半角英数で入力してください。");
			zip2.focus();
			return false;
		}
		if (!isA(tel.value)) {
			alert("電話番号は半角英数で入力してください。");
			tel.focus();
			return false;
		}		str=pro.value;
		if (str.length > 50) {
			alert("プロフィールが長すぎます。");
			pro.focus();
			return(false);
		}
    if (choice_num.value == "") {
      alert("みつくろい上限値を入力してください。");
      choice_num.focus();
      return false;
    }
		if (!num_chk(choice_num.value)){  
			alert("みつくろい上限値は半角の数値で入力してください。");  
			choice_num.focus();  
			return(false);  
		} 
    if (recom_num.value == "") {
      alert("推薦文上限値を入力してください。");
      recom_num.focus();
      return false;
    }
		if (!num_chk(recom_num.value)){  
			alert("推薦文上限値は半角の数値で入力してください。");  
			recom_num.focus();  
			return(false);  
		} 
    if (osusume_num.value == "") {
      alert("オススメ上限値を入力してください。");
      osusume_num.focus();
      return false;
    }
		if (!num_chk(osusume_num.value)){  
			alert("オススメ上限値は半角の数値で入力してください。");  
			osusume_num.focus();  
			return(false);  
		}
    if (abunai_num.value == "") {
      alert("あぶないよ上限値を入力してください。");
      abunai_num.focus();
      return false;
    }
		if (!num_chk(abunai_num.value)){  
			alert("あぶないよ上限値は半角の数値で入力してください。");  
			abunai_num.focus();  
			return(false);  
		} 
    if (point.value == "") {
      alert("ポイントを入力してください。");
      point.focus();
      return false;
    }
		if (!num_chk(point.value)){  
			alert("ポイントは半角の数値で入力してください。");  
			point.focus();  
			return(false);  
		} 

  }
	return confirm("更新します。よろしいですか？");
}
function OnClick_sakujo() {
	if (confirm("削除します。よろしいですか？")) {
    location.href = "melonpai_regist5.php?melonpai_id=<?= $melonpai_id ?>";
	}
}
//-->
</SCRIPT>
</head>
<body>

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form method="post" action="melonpai_regist4.php" name="form1" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width='700'>
		<tr>
			<td class="m0">■メルマガナビ登録情報</td>
		</tr>
<?php
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$melonpai_id = $fetch->melonpai_id;
	$melonpai_pswd = $fetch->melonpai_pswd;
	$melonpai_name = $fetch->melonpai_name;
	$melonpai_nic = $fetch->melonpai_nic;
	$melonpai_mail_addr = $fetch->melonpai_mail_addr;
	$melonpai_zip1 = $fetch->melonpai_zip1;
	$melonpai_zip2 = $fetch->melonpai_zip2;
	$melonpai_add = $fetch->melonpai_add;
	$melonpai_tel = $fetch->melonpai_tel;
	$melonpai_m_flg = $fetch->melonpai_m_flg;
	$melonpai_point = $fetch->melonpai_point;
	$melonpai_pro = $fetch->melonpai_pro;
	$choice_num = $fetch->choice_num;
	$osusume_num = $fetch->osusume_num;
	$recom_num = $fetch->recom_num;
	$abunai_num = $fetch->abunai_num;
	$modify_dt = $fetch->modify_dt;
	if ($melonpai_m_flg == '0') {
		$flg_action = " checked";
	} elseif ($melonpai_m_flg == '1') {
		$flg_break = " checked";
	} elseif ($melonpai_m_flg == '2') {
		$flg_stop = " checked";
	}
?>
		<tr>
			<td>
				<table border=0 cellspacing=1 cellpadding=3 width="100%">
					<tr>
						<td class="m5">ＩＤ</td>
						<td class="n5"><?= $melonpai_id ?></td>
					</tr>
					<tr>
						<td class="m5">活動状況</td>
						<td class="n5">
							<input type="radio" name="m_flg" value="0" <?= $flg_action ?>><font size="-1"> 活動中 </font>
							<input type="radio" name="m_flg" value="1" <?= $flg_break ?>><font size="-1"> 休止中 </font>
							<input type="radio" name="m_flg" value="2" <?= $flg_stop ?>><font size="-1"> 停止 </font>
						</td>
					</tr>
					<tr>
						<td class="m5">氏　　名</td>
						<td class="n5"><input size=50 name="name" maxlength=25 value="<?= htmlspecialchars($melonpai_name) ?>"><font class="note">（全角）</font></td>
					</tr>
					<tr>
						<td class="m5">ニックネーム</td>
						<td class="n5"><input size=50 name="nic" maxlength=10 value="<?= htmlspecialchars($melonpai_nic) ?>"><font class="note">（半角英数）</font></td>
					</tr>
					<tr>
						<td class="m5" width="25%">メールアドレス</td>
						<td class="n5"><input size=50 name="mail_addr" maxlength=50 value="<?= $melonpai_mail_addr ?>"><font class="note">（半角英数）</font><BR></td>
					</tr>
					<tr>
						<td class="m5">パスワード</td>
						<td class="n5"><input size=20 name="pswd" maxlength=8 value="<?= $melonpai_pswd ?>"><font class="note">（半角英数：8文字以内）</font></td>
					</tr>
					<tr>
						<td class="m5">郵便番号</td>
						<td class="n5"><input size=3 name="zip1" maxlength=3 value="<?= trim($melonpai_zip1) ?>">-<input size=4 name="zip2" maxlength=4 value="<?= trim($melonpai_zip2) ?>"> <font class="note">（半角数値）</font></td>
					</tr>
					<tr>
						<td class="m5">住　　所</td>
						<td class="n5"><input size=80 name="add" maxlength=50 value="<?= $melonpai_add ?>"><font class="note">（全角）</font></td>
					</tr>
					<tr>
						<td class="m5">電話番号</td>
						<td class="n5"><input size=20 name="tel" maxlength=15 value="<?= trim($melonpai_tel) ?>"><font class="note">（半角数値）</font></td>
					</tr>
					<tr>
						<td class="m5">プロフィール</td>
						<td class="n5"><textarea name="pro" cols="40" rows="4"><?= $melonpai_pro ?></textarea><font class="note">（全角：50文字以内）</font></td>
					</tr>
					<tr>
						<td class="m5">みつくろい上限値</td>
						<td class="n5"><input size=10 DIR=rtl name="choice_num" maxlength=4 value="<?= $choice_num ?>"><font class="note">（半角数値）</font></td>
					</tr>
					<tr>
						<td class="m5">推薦文上限値</td>
						<td class="n5"><input size=10 DIR=rtl name="recom_num" maxlength=4 value="<?= $recom_num ?>"><font class="note">（半角数値）</font></td>
					</tr>
					<tr>
						<td class="m5">オススメ上限値</td>
						<td class="n5"><input size=10 DIR=rtl name="osusume_num" maxlength=4 value="<?= $osusume_num ?>"><font class="note">（半角数値）</font></td>
					</tr>
					<tr>
						<td class="m5">あぶないよ上限値</td>
						<td class="n5"><input size=10 DIR=rtl name="abunai_num" maxlength=4 value="<?= $abunai_num ?>"><font class="note">（半角数値）</font></td>
					</tr>
					<tr>
						<td class="m5">ポイント</td>
						<td class="n5"><input size=10 DIR=rtl name="point" maxlength=10 value="<?= $melonpai_point ?>"><font class="note">（半角数値）</font></td>
					</tr>
				</table>
			</td>
		</tr>
<?php
}
?>
	</table>
	<br>
	<input type="hidden" name="melonpai_id" value="<?= $melonpai_id ?>">
	<input type="submit" value=" 更新 ">
	<input type="reset" value="リセット">
	<input type="reset" value="削除" onclick="OnClick_sakujo()">
	<input type="button" value=" 戻る " onclick="history.back()">
</form>
</center>

<? footer_jmk(0); ?>

</body>
</html>
