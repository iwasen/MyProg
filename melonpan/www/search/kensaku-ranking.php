<?php
include("../inc/com_db_func.inc");
include( "../sub/head.inc");
include("../sub/csub.inc");

function Ranling_List( $r_num ){

	$r_lisi = array();

	/* �طʿ� -------------------------- */
	if ( $r_num % 2 ) 
		$r_list["color"] = "\"#e2f0c9\" ";
	else
		$r_list["color"] = "\"#ffffff\" ";
	/* ��å����� ---------------------- */
	if 		( $r_num == 101 ):	$r_list["list_msg"] = "�ɼԿ�Ranking" ;
	elseif 	( $r_num == 102 ):	$r_list["list_msg"] = "�ɼԤ������Ƥ�Ranking";
	elseif 	( $r_num == 103 ):	$r_list["list_msg"] = "����ʸ��¿��Ranking";
	elseif 	( $r_num == 104 ):	$r_list["list_msg"] = "���Ψ���㤤Ranking";
	elseif 	( $r_num == 105 ):	$r_list["list_msg"] = "�ɼԤ�ʿ��ǯ���㤤Ranking";
	elseif 	( $r_num == 106 ):	$r_list["list_msg"] = "�ɼԤ�ʿ��ǯ�𤬹⤤Ranking";

	elseif 	( $r_num == 201 ):	$r_list["list_msg"] = "�������ɤ�Ǥ�Ranking";
	elseif 	( $r_num == 202 ):	$r_list["list_msg"] = "�������ɤ�Ǥ�Ranking";
	elseif 	( $r_num == 203 ):	$r_list["list_msg"] = "�ӥ��ͥ��ޥ��ɤ�Ǥ�Ranking";
	elseif 	( $r_num == 204 ):	$r_list["list_msg"] = "���翦���ɤ�Ǥ�Ranking";
	elseif 	( $r_num == 205 ):	$r_list["list_msg"] = "���ء����פ��ɤ�Ǥ�Ranking";
	elseif 	( $r_num == 206 ):	$r_list["list_msg"] = "��������Ranking";
	else:
	endif;
	/* ������̲��̥ѥ�᡼�� --------- */
	$r_list["href_prm"] = "?mode=rank&rank_id=" .$r_num;
	/* ɽ�� --------------------------- */
	echo "<tr>\n";
	echo "<td width=19  bgcolor=" .$r_list["color"] ."align=\"center\">";
	echo "  <img src=\"img/kanmuri.gif\" width=18 height=20>";
	echo "</td>";
	echo "<td width=181 bgcolor=" .$r_list["color"] .">";
	if ( $r_num == 206 ) { // �ڡ������椢��
		echo "  <a href=\"kensaku-list_ranking2.php" .$r_list["href_prm"] ."\">";
	}else{
		echo "  <a href=\"../melonpa/search_result_ranking.php" .$r_list["href_prm"] ."\">";
	}
	echo      $r_list["list_msg"];
	echo "  </a>";
	echo "</td>";
	echo "<td width=40  bgcolor=" .$r_list["color"] .">";
	if ( $r_num == 206 ) {	// �ڡ������椢��
		echo "  <a href=\"kensaku-list_ranking2.php" .$r_list["href_prm"] ."\">";
	}else{
		echo "  <a href=\"../melonpa/search_result_ranking.php" .$r_list["href_prm"] ."\">";
	}
	echo "  <img src=\"img/go.gif\" width=33 height=15 align=\"right\" border = 0></a>";
	echo "</td>";
	echo "</tr>\n";

}
/****************************************************************
 Main
****************************************************************/
//$gConn = db_conxxxt();

if ($mail_addr != "") {pass_check($gConn,trim($mail_addr),trim($reader_pswd));}
if ($mail_addr == "") {
	$mail_addr = $cookie_mail_addr;
	$reader_pswd = $cookie_reader_pswd;
	$reader_id = $cookie_reader_id;
}
?>
<html>
<!-- #BeginTemplate "/Templates/index.dwt" --> 
<head>
<!-- #BeginEditable "doctitle" --> 
<title>����Ѥ� || ��ꤹ������ޥ�������</title>
<!-- #EndEditable --> 
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
</head>
<body bgcolor="#FFFFFF" text="#666666" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" vlink="#666600" link="#336600">
<?php	draw_head( ".." );	?>
<table width="753" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td align="center"> 
      <table width="620" border="0" cellspacing="0" cellpadding="0" align="center">
        <tr> 
          <td width="21"><!-- #BeginEditable "1" --><img src="img/g1.gif" width="21" height="21"><!-- #EndEditable --></td>
          <td width="21"><!-- #BeginEditable "3" --><img src="img/g3.gif" width="21" height="21"><!-- #EndEditable --></td>
          <td width="557"><!-- #BeginEditable "title1" --><!-- #EndEditable --></td>
          <td width="21">��</td>
        </tr>
        <tr> 
          <td width="21"><!-- #BeginEditable "2" --><img src="img/g2.gif" width="21" height="21"><!-- #EndEditable --></td>
          <td width="21" bgcolor="#90c84b"><!-- #BeginEditable "4" --><img src="img/g4.gif" width="21" height="21"><!-- #EndEditable --></td>
          <td width="557" bgcolor="#90c84b"><!-- #BeginEditable "title2" --><img src="img/rankingtitle.gif" width="135" height="21" alt="������Ranking"><!-- #EndEditable --></td>
          <td width="21"><img src="../common/c_rihgt_top.gif" width="21" height="21"></td>
        </tr>
        <tr bgcolor="#ffffd3"> 
          <td colspan="4" bgcolor="#ffffd3" height="21"><!-- #BeginEditable "sub" --> <p>��</p><!-- #EndEditable --></td>
        </tr>
        <tr> 
          <td width="21" bgcolor="#ffffd3">��</td>
          <td width="21" bgcolor="#ffffd3">��</td>
          <td width="557" bgcolor="#ffffd3"><!-- #BeginEditable "%90%7D" -->�͡��ʥ�󥭥󥰤ǥ��ޥ������٤�ڡ����Ǥ���<br>
            ���Υڡ����ˤ����ȡ�TOP50�������֤��Ȥ��Ǥ��ޤ���<br>
            <br>
            <table width="557" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td valign="top"> 
                  <table border="0" cellspacing="0" cellpadding="5" width="270">

<?php
	for ( $i=101; $i<=106 ; $i++ ){
		echo Ranling_List($i);
	}
?>
                  </table>
                </td>
                <td valign="top"> 
                  <table border="0" cellspacing="0" cellpadding="5" width="270">
<?php
	for ( $i=201; $i<=206 ; $i++ ){
		echo Ranling_List($i);
	}
?>
                  </table>
                </td>
              </tr>
            </table>
            <br>
            <br>
            <!-- #EndEditable --></td>
          <td width="21" bgcolor="#ffffd3">��</td>
        </tr>
        <tr> 
          <td width="21"><img src="../common/c_left_bottom.gif" width="21" height="21"></td>
          <td width="21" bgcolor="#90c84b">��</td>
          <td width="557" bgcolor="#90c84b">��</td>
          <td width="21"><img src="../common/c_right_bottom.gif" width="21" height="21"></td>
        </tr>
<?php	draw_footer( ".." );	?>
      </table>
    </td>
  </tr>
</table>
</body>
<!-- #EndTemplate -->
</html>
