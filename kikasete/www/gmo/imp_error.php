<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:��˥����ãӣ֥ǡ�������ݡ��ȡ����
'******************************************************/
$top = '../admin';
$inc = "../inc";
include("$inc/common_gmo.php");
$inc = "$top/inc";
include("$inc/header.php");
include("$inc/error_msg.php");
//�ᥤ�����
session_start();

?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>info-Q��˥����ǡ�������ݡ���</title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
//-->
</script>
</head>
<body>
<table border=0 width="100%" cellspacing=1 cellpadding=3 class="proenquete">
    <tr>
        <td align="center"><nobr>info-Q��˥����ǡ�������ݡ���</nobr></td>
    </tr>
</table>
<hr class="header">
<div align="center">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
    <tr>
        <td class="lt">���ǡ�������ݡ��ȥ��顼</td>
    </tr>
    <tr align="left">

        <td><br></td>
        <td colspan="2" class="color1">
            <? if( $_REQUEST["cancel"] == "" ) { ?>
            <br>��<b>��ʣ�ǡ���������ޤ�������</b><br>
            <br>��<b>��˥����ɣ� �� <?=$_REQUEST["errid"];?></b><br><br>
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
        <td align="right"><small><a href="csv_upload.php">���</a></small></td>
    </tr>
</table>
</body>
</html>
