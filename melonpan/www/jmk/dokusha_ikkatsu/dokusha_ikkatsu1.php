<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:読者一括登録/削除
'******************************************************/

$title_text = $TT_dokusha_ikkatsu;
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
function OnLoad_body() {
  document.form1.mag_id.focus();
}

function OnSubmit_form1() {
 with (document.form1) {
    if (mag_id.value == "") {
      alert("メルマガIDを入力してください。");
      mag_id.focus();
      return false;
    }
	}
}
function OnSubmit_form2() {
 with (document.form2) {
    if (keyword.value == "") {
      alert("キーワードを入力してください。");
      keyword.focus();
      return false;
    }
	}
}
//-->
</SCRIPT>
</head>
<body onLoad="OnLoad_body()">

<? header_jmk($title_text,$title_color,0); ?>

<center>
	<table border=0 cellspacing=0 cellpadding=0 width='700'>
	  <tr><br>
	    <td class="m0">■読者一括登録/削除</td>
	  </tr>
	</table>
	<br>
<form method="post" name="form1" action="dokusha_ikkatsu2.php" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width='700'>
	  <tr>
	    <td>・メルマガIDを入力してください<font size="-1">（先頭の"0"は、省略できます。）</font>
	      <table border=0 cellspacing=2 cellpadding=3 width='100%'>
	        <tr>
	          <td class="h3" width="25%">メルマガID</td>
	          <td class="n4"><input type="text" size="47" maxlength="6" name="mag_id"></td>
						<td><input type="submit" value=" 検索 "></td>
	        </tr>
	      </table>
	    </td>
	  </tr>
	</table>
</form>
<form method="post" name="form2" action="dokusha_ikkatsu2.php" onSubmit="return OnSubmit_form2();">
	<table border=0 cellspacing=0 cellpadding=0 width='700'>
	  <tr>
	    <td>・キーワードを入力してください<font size="-1">（複数のキーワードで検索する場合は、間にスペースを入れてください。）</font>
	      <table border=0 cellspacing=2 cellpadding=3 width='100%'>
	        <tr>
	          <td class="h3" width="25%">キーワード</td>
	          <td class="n4"><input type="text" size="30" maxlength="30" name="keyword">
						<input type=radio name=andor value=and checked>and
						<input type=radio name=andor value=or>or</td>
	        	<td><input type="submit" value=" 検索 "></td>
					</tr>
	      </table>
	    </td>
	  </tr>
	</table>
</form>
<input type="button" value=" 戻る " onclick="location.href='../menu.php'">
</center><br>

<? footer_jmk(0); ?>

</body>
</html>
