<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:��˥����ãӣ֥ǡ�������ݡ��ȡ����
'******************************************************/
$top = '../../kikasete/admin';
$inc = "../../kikasete/inc";
include("$inc/common_gmo.php");
$inc = "$top/inc";
include("$inc/header.php");
include("$inc/error_msg.php");
$admin = '.';
//�ᥤ�����
session_start();

?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>info-Q��˥����ǡ�������ݡ���</title>
<link rel="stylesheet" type="text/css" href="<?=$admin?>/css/main.css">
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
<form method="post" name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
    <tr>
        <td class="lt">���ǡ�������ݡ��ȷ��</td>
    </tr>
    <tr align="left">
        <td><br></td>
        <td colspan="2">

            <table border="0" cellspacing="0" cellpadding="2">
            <tr><td class="color1">

                <span class="font9">��</span><b>CSV�ե�����̾��</b></td>
                <td colspan="2" class="color0"><?=$_SESSION["upload_plainfilename"] ?></span><br></td>
            </tr>
            <tr><td class="color1"><span class="font9">��</span><b>�����</b></td>
                <td class="color0" align="right"><?=$_SESSION["upload_filecount"]?>&nbsp;��</span></td>

                <td class="color1"><span class="font9">�������ȥ�Ԥ����</span></td>
            </tr>
            <tr><td class="color1"><span class="font9">��</span><b>��������</b></td>
                <td class="color0" align="right"><?=$_SESSION["upload_okcnt"]?>&nbsp;��</span></td>
                <td><br></td>
            </tr>

            <tr><td class="color1"><span class="font9">��</span><b>���顼�����</b></td>
                <td class="color0" align="right"><?=$_SESSION["upload_ngcnt"]?>&nbsp;��</span></td>
                <td><br></td>
            </tr>
            </table>


            <table border="0" cellspacing="0" cellpadding="2">

            <tr><td nowrap class="color1"><br><span class="font9">��</span><b>���顼CSV�ե�������������URL��
                &nbsp;<?=$_SESSION["upload_errtime"]?></b></br>
                <A HREF="<?=$_SESSION["upload_errfilename"]?>"><?=$_SESSION["upload_plainerrfilename"]?></A><br></td>
            </tr>
            </table>


        </td>
    </tr>
    <tr align="left">

        <td><br></td>
        <td colspan="2" class="color1">
            <br>��<b>���顼���󤬤�����ϡ�����</b><br>
            <span class="font9">
            ���顼�ǡ�����������ϡ��ǡ����١�������Ͽ����ޤ��󡣺ƥ��åץ��ɤ���ˤϡ����μ��ǹԤ��ޤ���<br><br>
            1.���顼CSV�ե�������������URL�򥯥�å������ե�������������ɤ��Ʋ�������<br>
            2.��������ɤ���CSV�ե�������Ȥ˥��ꥸ�ʥ��CSV�ե�������������ޤ������顼�����ϳƹԤκǱ���򻲾Ȥ��ޤ���<br>
            3.��������CSV�ե��������٥���ݡ��Ȥ��ޤ���<br>
            </span>
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
