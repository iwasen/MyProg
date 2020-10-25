<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:モニターＣＳＶデータインポート・結果
'******************************************************/
$top = '../admin';
$inc = "../inc";
include("$inc/common_gmo.php");
$inc = "$top/inc";
include("$inc/header.php");
include("$inc/error_msg.php");
//メイン処理
session_start();

?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>info-Qモニターデータインポート</title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
//-->
</script>
</head>
<body>
<table border=0 width="100%" cellspacing=1 cellpadding=3 class="proenquete">
    <tr>
        <td align="center"><nobr>info-Qモニターデータインポート</nobr></td>
    </tr>
</table>
<hr class="header">
<div align="center">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
    <tr>
        <td class="lt">■データインポートエラー</td>
    </tr>
    <tr align="left">

        <td><br></td>
        <td colspan="2" class="color1">
            <? if( $_REQUEST["cancel"] == "" ) { ?>
            <br>※<b>重複データがあります．．．</b><br>
            <br>※<b>モニターＩＤ ： <?=$_REQUEST["errid"];?></b><br><br>
            <? } else { ?>
            <br><b><?=$_REQUEST["cancel"];?></b><br>
            <? } ?>

        </td>
    </tr>
</table>
<input type="hidden" name="action" value="init">
</form>
<hr class="header">
<table border=0 width="100%">
    <tr>
        <td align="right"><small><a href="csv_upload.php">戻る</a></small></td>
    </tr>
</table>
</body>
</html>
