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
function OnLoad_body() {
  document.form1.mag_id.focus();
}
function OnClick_mag() {
  var win;
	if (document.form1.mag_id.value == "") {
		alert("メルマガIDを入力してください。");
		return;
	}
	mag_id=document.form1.mag_id.value;
  win = window.open("../melmaga_detail/mag_detail1.php?mag_id=" + mag_id, "info_mag", "scrollbars=yes,resizable=yes,width=600,height=700");
  win.focus();
}
function OnSubmit_form1(frm) {
	if (frm.mag_id.value == "") {
		alert("メルマガIDを入力してください。");
		frm.mag_id.focus();
		return false;
	}
	if (frm.importfile.value == "") {
		alert("インポートファイルを入力してください。");
		frm.importfile.focus();
		return false;
	}
	return confirm("インポートします。よろしいですか？");
}
//-->
</SCRIPT>
</head>
<body onLoad="OnLoad_body()">

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form method="post" name="form1" ENCTYPE="multipart/form-data" action="import2.php" onSubmit="return OnSubmit_form1(this);">
<table border=0 cellspacing=0 cellpadding=0 width="700">
	<tr>
		<td>
			<table border=0 cellspacing=0 cellpadding=0 width="100%">
				<tr>
					<td>■メルマガ一括登録</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table border=1 cellspacing=0 cellpadding=2 width="100%">
				<tr>
					<td class="h1" align="center" width="30%"><nobr>メルマガID</nobr></td>
					<td>
						<input type="text" size="10" maxlength="6" name="mag_id">
						<font class="note">(先頭の"0"は省略できます。)</font>
						<input type="button" value="ﾒﾙﾏｶﾞ確認" OnClick='JavaScript:OnClick_mag()'>
					</td>
				</tr>
				<tr>
					<td class="h1" align="center" width="30%"><nobr>追加登録</nobr></td>
					<td>
						<input type="checkbox" name="bakyun" value="1" checked>バ・キューン
						<input type="checkbox" name="letter" value="1" checked>めろんぱんレター
					</td>
				</tr>
				<tr>
					<td class="h1" align="center" width="30%"><nobr>インポートファイル</nobr></td>
					<td><input type="file" name="importfile" size=60></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<br>
<input type="submit" value="インポート">
<input type="button" value=" 戻る " onclick="history.back()">
</form>
</center>

<? footer_jmk(0); ?>

</body>
</html>
