<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:��˥����ãӣ֥ǡ�������ݡ���
'******************************************************/
$inc = "../../kikasete/inc";
$admin = '.';
include("$inc/common_gmo.php");

?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>info-Q��˥����ǡ�������ݡ���</title>
<link rel="stylesheet" type="text/css" href="<?=$admin ?>/css/main.css">

	    <script type=text/javascript>
	    <!--

	    var checked = false;

	    function stop() {
			checked = true;
	    }

	    function progress() {
	        document.getElementById("check").innerHTML += "��";
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
        <td align="center"><nobr>info-Q��˥����ǡ�������ݡ���</nobr></td>
    </tr>
</table>
<hr class="header">

<div align="center">
<form method="post" name="form1" action="data_imp.php" enctype="multipart/form-data">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
    <tr>
        <td class="lt">��CSV�ե�����λ���</td>
        <td class="lb"></td>
    </tr>
    <tr align="left">
        <td class="lc" colspan="2">
            <table border="1" width="100%" cellspacing="0" cellpadding="2">
            <tr><td class="small"><BR>&nbsp;
                <input type="hidden" name="MAX_FILE_SIZE" value="20000000">
                <input TYPE="file" NAME="upfile" SIZE="80" value=""><br>
                <label>&nbsp;<input type="checkbox" name="chkflg" value="1" checked="checked" />��Ƭ�Ԥ��󥿥��ȥ�ԤȤ��ư���</label>
            </td></tr>
            </table>
        </td>
    </tr>
    <tr align="left">
        <td colspan="2" class="small2"><font color="blue">
            <br>��<b>������ˡ</b><br>
            <span>
            1.���ȥܥ���򥯥�å������Ѥ���CSV�ե���������򤷤ޤ���<br>
            2.CSV�ե��������Ƭ�Ԥ��󥿥��ȥ�ξ��ϡ������å��ܥå���������å����ޤ���<br>
            3.CSV����ݡ��ȥܥ���򥯥�å����Ʋ�������</br></span></font>
			<div style="color:red">���ãӣ֥���ݡ��ȥܥ��󲡲���ϡ����������Ǥ��뤳�ȤϤǤ��ޤ���</div>
        </td>
    </tr>
    <tr align="left">
        <td colspan="2" align="center">
			<div id="button">
	            <INPUT TYPE="button" NAME="Check_Btn" VALUE=" CSV����ݡ���" onclick="onloaded();">
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
