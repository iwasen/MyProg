<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:モニターＣＳＶデータインポート
'******************************************************/
$inc = "../../kikasete/inc";
$admin = '.';
include("$inc/common_gmo.php");

?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>info-Qモニターデータインポート</title>
<link rel="stylesheet" type="text/css" href="<?=$admin ?>/css/main.css">

	    <script type=text/javascript>
	    <!--

	    var checked = false;

	    function stop() {
			checked = true;
	    }

	    function progress() {
	        document.getElementById("check").innerHTML += "■";
			if( !checked ) setTimeout('progress()', 2000);

	    }

	    function onloaded()
	    {
	        document.getElementById("check").innerHTML = "";
	        document.getElementById("button").style.display = 'none';
	        setTimeout('progress()', 2000);

	        document.form1.submit();
	    }

	    //-->
	    </script>

</head>


<body onunload="stop();">

<table border=0 width="100%" cellspacing=1 cellpadding=3 class="proenquete">
    <tr onclick="location.reload()">
        <td align="center"><nobr>info-Qモニターデータインポート</nobr></td>
    </tr>
</table>
<hr class="header">

<div align="center">
<form method="post" name="form1" action="data_imp.php" enctype="multipart/form-data">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
    <tr>
        <td class="lt">■CSVファイルの指定</td>
        <td class="lb"></td>
    </tr>
    <tr align="left">
        <td class="lc" colspan="2">
            <table border="1" width="100%" cellspacing="0" cellpadding="2">
            <tr><td class="small"><BR>&nbsp;
                <input type="hidden" name="MAX_FILE_SIZE" value="20000000">
                <input TYPE="file" NAME="upfile" SIZE="80" value=""><br>
                <label>&nbsp;<input type="checkbox" name="chkflg" value="1" checked="checked" />先頭行は列タイトル行として扱う</label>
            </td></tr>
            </table>
        </td>
    </tr>
    <tr align="left">
        <td colspan="2" class="small2"><font color="blue">
            <br>※<b>指定方法</b><br>
            <span>
            1.参照ボタンをクリックし使用するCSVファイルを選択します。<br>
            2.CSVファイルの先頭行が列タイトルの場合は、チェックボックスをチェックします。<br>
            3.CSVインポートボタンをクリックして下さい。</br></span></font>
			<div style="color:red">注：ＣＳＶインポートボタン押下後は、処理を中断することはできません。</div>
        </td>
    </tr>
    <tr align="left">
        <td colspan="2" align="center">
			<div id="button">
	            <INPUT TYPE="button" NAME="Check_Btn" VALUE=" CSVインポート" onclick="onloaded();">
			</div>
            <INPUT TYPE="hidden" NAME="action" VALUE="upload">
        </td>
    </tr>

</table>
<hr class="header">

<div style="text-align:left;color:red" id="check"></div></font>

</form>
</div>

</body>
</html>
