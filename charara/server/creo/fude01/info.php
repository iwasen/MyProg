<?
/******************************************************
' System :「きゃららFactory」クレオキャンペーン
' Content:入力画面
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");

// ランダムID取得
$id = $QUERY_STRING;

// メイン
if (!$id)
	redirect('error.php');
else {
	$sql = "SELECT nd_status FROM t_nigaoe_data WHERE nd_random_id=" . sql_char($id);
	$status = db_fetch1($sql);
	if (!$status)
		redirect('error.php');
	elseif ($status >= 3)
		redirect('update.php?' . $id);
}

?>
<!doctype html public "-//W3C//DTD HTML 4.0 Transitional//EN">
<html lang="ja">
<head>
<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>クレオにがおえキャンペーン：ご当選おめでとうございます！！（ご利用にあたって）</title>
<script language="JavaScript">
<!--
mac=(navigator.appVersion.indexOf('Mac') != -1)?true:false;
ie=(navigator.appName.charAt(0) == "M")?true:false;
ns=(navigator.appName.charAt(0) == "N")?true:false;

if(mac)	{
	fName="css/mac.css";
}
else{
	if(ie){
	fName="css/win_ie.css";
	}
	else if(ns){
	fName="css/win_ns.css";
	}
}

if(fName != null){
	document.write("<link rel=stylesheet href='"+fName+"' type='text/css'>");
}
function onclick_btn2() {
	alert("次のステップに進むには、「同意する」をクリックして下さい。");
}
//-->
</script>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<div align="center">
<br><br>
<span class="xx-large"><font color="#FF0000"><b>「ご利用にあたって」</b></font></span>
<br><br><br>

<table width="500" border="0">
	<tr>
		<td><span class="large">
		<ul type=square>
			<li>製作にがおえの肖像権に関する一切の責任は、利用者ご本人が全ての責任を負う事をご承諾いただきます。
		<br><br>
			<li>にがおえの製作に関しては、必ずご本人（被写体）の承諾を得てください。
		<br><br>
			<li>プライバシーの保護に関して、お送りいただいた写真などの情報は第三者に開示する事は一切ありません。
		<br><br>
			<li>キャラクター、タレント、著名人などの製作はできません。
			<br><br>
			<li>作成されたにがおえは肖像権者の許諾なく、また個人的な範囲を越えて、複製、販売するなどの目的に使用することはできません。
		<br>
		</ul>
		</td>
	</tr>
</table>
<table width="450" border="0">
	<tr>
		<td align="right"><span class="large">以　上</span></td>
	</tr>
</table>
<br>
<br>
<form name="form1" action="update.php" method="post">
<table width="300" border="0">
	<tr>
		<td><div align="center"><input name="btn1" type="submit" value=" 同意する "></div></td>
		<td><div align="center"><input name="btn2" type="button" value="同意しない" onclick="onclick_btn2()"></div></td>
		<input type="hidden" name="id" <?=value($id)?>>
	</tr>
</table>
</form>
</div>
</body>
</html>