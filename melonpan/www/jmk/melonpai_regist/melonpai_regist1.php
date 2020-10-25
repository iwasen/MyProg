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
      alert("みつくろい初期値を入力してください。");
      choice_num.focus();
      return false;
    }
		if (!num_chk(choice_num.value)){  
			alert("みつくろい初期値は半角の数値で入力してください。");  
			choice_num.focus();  
			return(false);  
		} 
    if (recom_num.value == "") {
      alert("推薦文初期値を入力してください。");
      recom_num.focus();
      return false;
    }
		if (!num_chk(recom_num.value)){  
			alert("推薦文初期値は半角の数値で入力してください。");  
			recom_num.focus();  
			return(false);  
		} 
    if (osusume_num.value == "") {
      alert("オススメ初期値を入力してください。");
      osusume_num.focus();
      return false;
    }
		if (!num_chk(osusume_num.value)){  
			alert("オススメ初期値は半角の数値で入力してください。");  
			osusume_num.focus();  
			return(false);  
		}
    if (abunai_num.value == "") {
      alert("あぶないよ初期値を入力してください。");
      abunai_num.focus();
      return false;
    }
		if (!num_chk(abunai_num.value)){  
			alert("あぶないよ初期値は半角の数値で入力してください。");  
			abunai_num.focus();  
			return(false);  
		} 
    if (point.value == "") {
      alert("ポイント初期値を入力してください。");
      point.focus();
      return false;
    }
		if (!num_chk(point.value)){  
			alert("ポイント初期値は半角の数値で入力してください。");  
			point.focus();  
			return(false);  
		} 

  }
	return confirm("登録します。よろしいですか？");
}
//-->
</SCRIPT>
</head>
<body>

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form method="post" action="melonpai_regist3.php" name="form1" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width='700'>
		<tr>
			<td class="m0">■メルマガナビ登録情報</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=1 cellpadding=3 width="100%">
					<tr>
						<td class="m5">活動状況</td>
						<td class="n5">
							<input type="radio" name="m_flg" value="0" checked><font size="-1"> 活動中 </font>
							<input type="radio" name="m_flg" value="1"><font size="-1"> 休止中 </font>
							<input type="radio" name="m_flg" value="2"><font size="-1"> 停止 </font>
						</td>
					</tr>
					<tr>
						<td class="m5">氏　　名</td>
						<td class="n5"><input size=50 name="name" maxlength=25><font class="note">（全角）</font></td>
					</tr>
					<tr>
						<td class="m5">ニックネーム</td>
						<td class="n5"><input size=50 name="nic" maxlength=10><font class="note">（半角英数）</font></td>
					</tr>
					<tr>
						<td class="m5" width="25%">メールアドレス</td>
						<td class="n5"><input size=50 name="mail_addr" maxlength=50><font class="note">（半角英数）</font><BR></td>
					</tr>
					<tr>
						<td class="m5">パスワード</td>
						<td class="n5"><input size=20 name="pswd" maxlength=8><font class="note">（半角英数：8文字以内）</font></td>
					</tr>
					<tr>
						<td class="m5">郵便番号</td>
						<td class="n5"><input size=3 name="zip1" maxlength=3>-<input size=4 name="zip2" maxlength=4> <font class="note">（半角数値）</font></td>
					</tr>
					<tr>
						<td class="m5">住　　所</td>
						<td class="n5"><input size=80 name="add" maxlength=50><font class="note">（全角）</font></td>
					</tr>
					<tr>
						<td class="m5">電話番号</td>
						<td class="n5"><input size=20 name="tel" maxlength=15><font class="note">（半角数値）</font></td>
					</tr>
					<tr>
						<td class="m5">プロフィール</td>
						<td class="n5"><textarea name="pro" cols="40" rows="4"></textarea><font class="note">（全角：50文字以内）</font></td>
					</tr>
					<tr>
						<td class="m5">みつくろい上限初期値</td>
						<td class="n5"><input size=10 DIR=rtl name="choice_num" maxlength=4 value=0><font class="note">（半角数値）</font></td>
					</tr>
					<tr>
						<td class="m5">推薦文上限初期値</td>
						<td class="n5"><input size=10 DIR=rtl name="recom_num" maxlength=4 value=0><font class="note">（半角数値）</font></td>
					</tr>
					<tr>
						<td class="m5">オススメ上限初期値</td>
						<td class="n5"><input size=10 DIR=rtl name="osusume_num" maxlength=4 value=0><font class="note">（半角数値）</font></td>
					</tr>
					<tr>
						<td class="m5">あぶないよ上限初期値</td>
						<td class="n5"><input size=10 DIR=rtl name="abunai_num" maxlength=4 value=0><font class="note">（半角数値）</font></td>
					</tr>
					<tr>
						<td class="m5">ポイント初期値</td>
						<td class="n5"><input size=10 DIR=rtl name="point" maxlength=10 value=0><font class="note">（半角数値）</font></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<br>
	<input type="submit" value=" 登録 ">
	<input type="reset" value="リセット">
	<input type="button" value=" 戻る " onclick="history.back()">

</form>
</center>

<? footer_jmk(0); ?>

</body>
</html>
