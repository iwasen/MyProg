<?php
/**
 * ひとびと･net 共通ファイル
 *
 * ダイアログ表示
 *
 *
 * @package
 * @author
 * @version
 */
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<meta http-equiv="Content-Style-Type" content="text/css">
<link rel="stylesheet" type="text/css" href="<?=$top?>/hitobito.css">
<script type="text/javascript">
<!--
function onload_body() {
	var btn = "";
	document.getElementById("message").innerText = dialogArguments["message"];
	for (var i = 1; dialogArguments["button" + i]; i++)
		btn += "<input type='button' value='" + dialogArguments["button" + i] + "' onclick='onclick_button(" + i + ")'> ";
	document.getElementById("button").innerHTML = btn;
}
function onclick_button(ret_val) {
	window.returnValue = ret_val;
	window.close();
}
//-->
</script>
</head>
<body bgcolor="threedface" style="margin: 10px" onload="onload_body()">
<center>
<div id="message"></div>
<br>
<div id="button"></div>
</center>
</body>
</html>
