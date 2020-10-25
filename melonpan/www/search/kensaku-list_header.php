<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title>めろんぱん || よりすぐりメルマガサイト</title>
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
            <td width="647">　</td>
            <td width="25">　</td>
          </tr>
          <tr> 
            <td width="24"><img src="../search/img/g2.gif" width="21" height="21"></td>
            <td width="24" bgcolor="#90c84b"><img src="../search/img/g4.gif" width="21" height="21"></td>
            <td width="657" bgcolor="#90c84b"><img src="../search/img/kensakukekka.gif" width="118" height="19"></td>
            <td width="25"><img src="../common/c_rihgt_top.gif" width="21" height="21"></td>
          </tr>
          <tr bgcolor="#ffffd3"> 
            <td colspan="4" bgcolor="#ffffd3" height="21"> 
              <p>　</p>
            </td>
          </tr>
          <tr> 
            <td width="24" bgcolor="#ffffd3">　</td>
            <td width="24" bgcolor="#ffffd3">　</td>
            <td width="657" bgcolor="#ffffd3" align="left" valign="top"> 
<!-- 変更 -->
<table border="0" cellpadding="2" cellspacing="0" width="657">
<!--      -->
                <tr align="left" valign="top"> 
                  <td colspan="2"> 
                    <div align="left"> <font color="#990000"></font> 
<?php
if ( $mode == "rank" ){
	echo "<font color=\"#990000\"><b>ＴＯＰ".$all_num ."</b></font>をリストアップしました<br>";
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
	echo "<input type=\"radio\" name=\"order\" value=\"default\">最新発行日順 \n";
	echo "<input type=\"radio\" name=\"order\" value=\"kari\">創刊日順\n";
	echo "<input type=\"submit\" value=\"並び替え方法を変えて検索し直す\" name=\"submit\">\n";
	echo "<br>\n";
	echo "</font></b><font size=2>※注意：購読/解除のチェックが無効となります。<br>";
	echo "ご注意ください。</font><b><font size=2> </font></b></font></form></div>\n";
	echo "</td>";
	echo "</tr>";
	echo "</table>";
    echo "<font color=\"#990000\"><b>全".$all_num."件</b></font>が該当しました<br>";
}
?>
                      <p><span class="text01">購読（解除）を希望するメルマガの[購読]または [解除]チェックボックスを選択し、最後に[決定]ボタンを押してください。</span> 
                    </div>
                  </td>
                </tr>
              </table>
              <br>
              <table width="657" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF">
                <tr> 
                  <td align="center" valign="top"> 
