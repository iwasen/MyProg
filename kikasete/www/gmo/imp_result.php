<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:モニターＣＳＶデータインポート・結果
'******************************************************/
$top = '../../kikasete/admin';
$inc = "../../kikasete/inc";
include("$inc/common_gmo.php");
$inc = "$top/inc";
include("$inc/header.php");
include("$inc/error_msg.php");
$admin = '.';
//メイン処理
session_start();

?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>info-Qモニターデータインポート</title>
<link rel="stylesheet" type="text/css" href="<?=$admin?>/css/main.css">
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
<form method="post" name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
    <tr>
        <td class="lt">■データインポート結果</td>
    </tr>
    <tr align="left">
        <td><br></td>
        <td colspan="2">

            <table border="0" cellspacing="0" cellpadding="2">
            <tr><td class="color1">

                <span class="font9">●</span><b>CSVファイル名：</b></td>
                <td colspan="2" class="color0"><?=$_SESSION["upload_plainfilename"] ?></span><br></td>
            </tr>
            <tr><td class="color1"><span class="font9">●</span><b>件数：</b></td>
                <td class="color0" align="right"><?=$_SESSION["upload_filecount"]?>&nbsp;件</span></td>

                <td class="color1"><span class="font9">※タイトル行を除く</span></td>
            </tr>
            <tr><td class="color1"><span class="font9">●</span><b>正常件数：</b></td>
                <td class="color0" align="right"><?=$_SESSION["upload_okcnt"]?>&nbsp;件</span></td>
                <td><br></td>
            </tr>

            <tr><td class="color1"><span class="font9">●</span><b>エラー件数：</b></td>
                <td class="color0" align="right"><?=$_SESSION["upload_ngcnt"]?>&nbsp;件</span></td>
                <td><br></td>
            </tr>
            </table>


            <table border="0" cellspacing="0" cellpadding="2">

            <tr><td nowrap class="color1"><br><span class="font9">●</span><b>エラーCSVファイルダウンロードURL：
                &nbsp;<?=$_SESSION["upload_errtime"]?></b></br>
                <A HREF="<?=$_SESSION["upload_errfilename"]?>"><?=$_SESSION["upload_plainerrfilename"]?></A><br></td>
            </tr>
            </table>


        </td>
    </tr>
    <tr align="left">

        <td><br></td>
        <td colspan="2" class="color1">
            <br>※<b>エラー情報がある場合は．．．</b><br>
            <span class="font9">
            エラーデータがある場合は、データベースに登録されません。再アップロードするには、次の手順で行います。<br><br>
            1.エラーCSVファイルダウンロードURLをクリックし、ファイルをダウンロードして下さい。<br>
            2.ダウンロードしたCSVファイルをもとにオリジナルのCSVファイルを訂正します。エラー原因は各行の最右列を参照します。<br>
            3.訂正したCSVファイルを再度インポートします。<br>
            </span>
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
