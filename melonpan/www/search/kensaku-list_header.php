<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title>����Ѥ� || ��ꤹ������ޥ�������</title>
</head>
<body bgcolor="#FFFFFF" text="#666666" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" vlink="#666600" link="#336600">
<?php
	draw_head("..");
?>
  <table width="753" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td align="center"> 
        <table width="720" border="0" cellspacing="0" cellpadding="0" align="center">
          <tr> 
            <td width="24"><img src="../search/img/g1.gif" width="21" height="21"></td>
            <td width="24"><img src="../search/img/g3.gif" width="21" height="21"></td>
            <td width="647">��</td>
            <td width="25">��</td>
          </tr>
          <tr> 
            <td width="24"><img src="../search/img/g2.gif" width="21" height="21"></td>
            <td width="24" bgcolor="#90c84b"><img src="../search/img/g4.gif" width="21" height="21"></td>
            <td width="657" bgcolor="#90c84b"><img src="../search/img/kensakukekka.gif" width="118" height="19"></td>
            <td width="25"><img src="../common/c_rihgt_top.gif" width="21" height="21"></td>
          </tr>
          <tr bgcolor="#ffffd3"> 
            <td colspan="4" bgcolor="#ffffd3" height="21"> 
              <p>��</p>
            </td>
          </tr>
          <tr> 
            <td width="24" bgcolor="#ffffd3">��</td>
            <td width="24" bgcolor="#ffffd3">��</td>
            <td width="657" bgcolor="#ffffd3" align="left" valign="top"> 
<!-- �ѹ� -->
<table border="0" cellpadding="2" cellspacing="0" width="657">
<!--      -->
                <tr align="left" valign="top"> 
                  <td colspan="2"> 
                    <div align="left"> <font color="#990000"></font> 
<?php
if ( $mode == "rank" ){
	echo "<font color=\"#990000\"><b>�ԣϣ�".$all_num ."</b></font>��ꥹ�ȥ��åפ��ޤ���<br>";
}else{
	echo "<table border=1 cellpadding=3 cellspacing=1 bgcolor=\"#fcffbd\" align=\"right\" width=243>";
	echo "<tr>";
	echo "<td>";
	echo "<div align=\"left\"><form action=\"search_result.php\" method=post>\n";
	echo "<input type=\"hidden\" name=\"mode\" value=\"".$mode."\">\n";
	echo "<input type=\"hidden\" name=\"reader_id\" value=\"".$reader_id."\">\n";
	echo "<input type=\"hidden\" name=\"reader_pswd\" value=\"".$reader_pswd."\">\n";
	echo "<input type=\"hidden\" name=\"mail_addr\" value=\"".$mail_addr."\">\n";
	echo "<input type=\"hidden\" name=\"email\" value=\"".$mail_addr."\">\n";
	//print "<input type=\"hidden\" name=\"page\" value=\"".$page."\">\n";
	echo $input_data;
    echo "<font color=\"#990000\"><b><font size=\"2\">";
	echo "<input type=\"radio\" name=\"order\" value=\"default\">�ǿ�ȯ������ \n";
	echo "<input type=\"radio\" name=\"order\" value=\"kari\">�ϴ�����\n";
	echo "<input type=\"submit\" value=\"�¤��ؤ���ˡ���Ѥ��Ƹ�����ľ��\" name=\"submit\">\n";
	echo "<br>\n";
	echo "</font></b><font size=2>����ա�����/����Υ����å���̵���Ȥʤ�ޤ���<br>";
	echo "����դ���������</font><b><font size=2> </font></b></font></form></div>\n";
	echo "</td>";
	echo "</tr>";
	echo "</table>";
    echo "<font color=\"#990000\"><b>��".$all_num."��</b></font>���������ޤ���<br>";
}
?>
                      <p><span class="text01">���ɡʲ���ˤ��˾������ޥ���[����]�ޤ��� [���]�����å��ܥå��������򤷡��Ǹ��[����]�ܥ���򲡤��Ƥ���������</span> 
                    </div>
                  </td>
                </tr>
              </table>
              <br>
              <table width="657" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF">
                <tr> 
                  <td align="center" valign="top"> 
