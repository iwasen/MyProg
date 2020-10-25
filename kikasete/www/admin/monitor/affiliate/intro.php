<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content: 紹介用途説明表示
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

$sql = "SELECT af_web_url,af_introduction FROM t_affiliate WHERE af_affiliate_id=$affiliate_id";
$result = db_exec($sql);
$fetch = pg_fetch_object($result, 0);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>紹介用途説明</title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body bgcolor="#ffffee">

<div align="center">
<form>
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td><span class="small">紹介用URL：</span> <a href="<?=$fetch->af_web_url?>" target="_blank"><?=htmlspecialchars($fetch->af_web_url)?></a></td>
		<td align="right"><input type="button" value="　閉じる　" onclick="window.close()"></td>
	</tr>
	<tr>
		<td class="small" colspan=2><hr><pre><?=htmlspecialchars($fetch->af_introduction)?></pre><hr></td>
	</tr>
</table>
<br>
<input type="button" value="　閉じる　" onclick="window.close()">
</form>
</div>

</body>
</html>
