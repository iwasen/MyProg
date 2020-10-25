<?php
	include( "../sub/head.inc");
	include("../inc/com_db_func.inc");
	include("../sub/csub.inc");

/*==========================================================*/
/*															*/
/*==========================================================*/
Function get_num($where_target,$param) {
	global $gConn;
	$sql = "select distinct count(*) from M_MAGAZINE where ($where_target = $param)";
	$sql .= " and ( mag_pub_status_flg = '01' or mag_pub_status_flg = '03' ) ";
	$sql .= " and ( mag_pub_stop_flg <> '02' )";
	$sql .= " and ( publisher_id <> 0 )";

	$ret = db_exec($sql);

	if (!$ret) {
		$error_msg = $cibi_error_msg_6;
		exit();
	}
	$resnum= pg_fetch_array ($ret, 0);
	return $resnum[0];
}

/*==========================================================*/
/*	main													*/
/*==========================================================*/

if ($mail_addr == "") {
	$mail_addr = $cookie_mail_addr;
	$reader_pswd = $cookie_reader_pswd;
	$reader_id = $cookie_reader_id;
}

if ($mail_addr != "") {
	pass_check($gConn,trim($mail_addr),trim($reader_pswd));
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html><!-- #BeginTemplate "/Templates/index.dwt" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title>めろんぱん || よりすぐりメルマガサイト</title>
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
          <td width="21">　</td>
        </tr>
        <tr> 
          <td width="21"><!-- #BeginEditable "2" --><img src="img/g2.gif" width="21" height="21"><!-- #EndEditable --></td>
          <td width="21" bgcolor="#90c84b"><!-- #BeginEditable "4" --><img src="img/g4.gif" width="21" height="21"><!-- #EndEditable --></td>
          <td width="557" bgcolor="#90c84b"><!-- #BeginEditable "title2" --><img src="img/muke.gif" width="190" height="21"><!-- #EndEditable --></td>
          <td width="21"><img src="../common/c_rihgt_top.gif" width="21" height="21"></td>
        </tr>
        <tr bgcolor="#ffffd3"> 
          <td colspan="4" bgcolor="#ffffd3" height="21"><!-- #BeginEditable "sub" --><br>
            <!-- #EndEditable --></td>
        </tr>
        <tr> 
          <td width="21" bgcolor="#ffffd3">　</td>
          <td width="21" bgcolor="#ffffd3">　</td>
          <td width="557" bgcolor="#ffffd3"><!-- #BeginEditable "%90%7D" -->
      <table width="550" border="0" cellspacing="1" cellpadding="5">
        <tr > 
          <td align="left" valign="top"><span class="text01">あなたの属性にあったメルマガを検索できるページです。発行者の方たちの「こんな人に読んでもらいたい！」という気持ちと、 
            あなたの属性をてらしあわせます。 性別・年代・職業・エリアの４種類を用意しています。</span><span class="text01">あなたの属性からクリックすると、各属性ごとの一覧が表示されます。</span> 
            <form action="../melonpa/search_result.php" method="post">
              <table width="600" border="0" cellspacing="0" cellpadding="0">
                <tr> 
                  <td align="left" valign="top" bgcolor="#FFFFFF"> 
                    <table border="0" cellpadding="1" cellspacing="1" width="150">
                      <tr align="left" valign="top" bgcolor="#e2f0c9"> 
                        <td bgcolor="#91c741" colspan="2"><font color="#336600">▼</font><font color="white"><b>性別 
                          </b></font></td>
                      </tr>
                      <tr align="left" valign="top" bgcolor="#e2f0c9"> 
                        <td width="18">
                          <input type="checkbox" name="sex[]" value="1">
                        </td>
<td width="120"><font size="2">男性向け <?php echo "(".get_num("mag_reader_sex",1).")"; ?> </font></td>
                      </tr>
                      <tr align="left" valign="top"> 
                        <td width="18" bgcolor="white"><font size="2">
                          <input type="checkbox" name="sex[]2" value="2">
                          </font></td>
<td width="120" bgcolor="white"><font size="2">女性向け <?php echo "(".get_num("mag_reader_sex",2).")"; ?> 
                          </font></td>
                      </tr>
                      <tr align="left" valign="top" bgcolor="#e2f0c9"> 
                        <td bgcolor="#91c741" colspan="2"><font color="#336600">▼</font><font color="white"><b>年代</b></font></td>
                      </tr>
                      <tr align="left" valign="top" bgcolor="#e2f0c9"> 
                        <td width="18">
                          <input type="checkbox" name="age[]" value="10">
                        </td>
<td width="120"><font size="2">10代 <?php echo "(".get_num("mag_reader_age",10).")"; ?></font></td>
                      </tr>
                      <tr align="left" valign="top"> 
                        <td width="18" bgcolor="white">
                          <input type="checkbox" name="age[]" value="20">
                        </td>
<td width="120" bgcolor="white"><font size="2">20代 <?php echo "(".get_num("mag_reader_age",20).")"; ?></font></td>
                      </tr>
                      <tr align="left" valign="top" bgcolor="#e2f0c9"> 
                        <td width="18">
                          <input type="checkbox" name="age[]" value="30">
                        </td>
<td width="120"><font size="2">30代 <?php echo "(".get_num("mag_reader_age",30).")"; ?></font></td>
                      </tr>
                      <tr align="left" valign="top"> 
                        <td width="18" bgcolor="white">
                          <input type="checkbox" name="age[]" value="40">
                        </td>
<td width="120" bgcolor="white"><font size="2">40代 <?php echo "(".get_num("mag_reader_age",40).")"; ?></font></td>
                      </tr>
                      <tr align="left" valign="top" bgcolor="#e2f0c9"> 
                        <td width="18">
                          <input type="checkbox" name="age[]" value="50">
                        </td>
<td width="120"><font size="2">50代 <?php echo "(".get_num("mag_reader_age",50).")"; ?></font></td>
                      </tr>
                      <tr align="left" valign="top"> 
                        <td width="18" bgcolor="white">
                          <input type="checkbox" name="age[]" value="60">
                        </td>
<td width="120" bgcolor="white"><font size="2">60代以上 <?php echo "(".get_num("mag_reader_age",60).")"; ?> 
                          <input type="hidden" name="mode" value="reader">
                          </font></td>
                      </tr>
                    </table>
                  </td>
                  <td align="left" valign="top" bgcolor="#FFFFFF"> 



                    <table border="0" cellpadding="1" cellspacing="1" width="150">
                      <tr align="left" valign="top" bgcolor="#e2f0c9"> 
                        <td bgcolor="#91c741" colspan="2"><font color="#336600">▼</font><font color="white"><b>エリア</b></font></td>
                      </tr>

<?php
	$sql = "select area_id,area from M_MAG_AREA order by area_id";
	$ret = db_exec($sql);
	$nrow = pg_numrows($ret);
	
	if ($nrow == 0) {
		$error_msg = $cibi_error_msg_6;
		exit();
	}

	$color_sw = 0;
	for ($i = 0; $i < $nrow; $i++) {
		$resarray= pg_fetch_array ($ret, $i);
		$sql = "select distinct count(*) from M_MAGAZINE where (mag_reader_area = '$resarray[0]')";
		$sql .= " and ( mag_pub_status_flg = '01' or mag_pub_status_flg = '03' ) ";
		$sql .= " and ( mag_pub_stop_flg <> '02' )";
		$sql .= " and ( publisher_id <> 0 )";
		$ret_area = db_exec($sql);

		if ( !$color_sw ) {
			echo "<tr align=\"left\" valign=\"top\" bgcolor=\"#e2f0c9\">\n";
			$color_sw = 1;
		}else{
			echo "<tr align=\"left\" valign=\"top\">\n";
			$color_sw = 0;
		}

		$res_area= pg_fetch_array ($ret_area, 0);
		echo "<td width=\"18\"><font size=\"2\">\n";
		echo "<input type=\"checkbox\" name=\"area[]\" value=\"$resarray[0]\">";
		echo "</font></td>\n<td width=\"120\"><font size=\"2\">";                        
		echo "$resarray[1] ($res_area[0])\n";
		echo "</font></td>";
		echo "</tr>\n";

	}
?>

                    </table>
                  </td>
                  <td width="35" align="left" valign="top" bgcolor="#FFFFFF">
                    <table border="0" cellpadding="1" cellspacing="1" width="300">
                      <tr align="left" valign="top" bgcolor="#e2f0c9"> 
                        <td bgcolor="#91c741" colspan="2"><font color="#336600">▼</font><font color="white"><b>職業</b></font></td>
                      </tr>
					  
					  

<?php
	$sql = "select occup_id,occup from M_MAG_OCCUP order by occup_id";
	$ret = db_exec($sql);
	$nrow = pg_numrows($ret);

	if ($nrow == 0) {
		$error_msg = $cibi_error_msg_6;
		exit();
	}
	$color_sw = 0;
	for ($i = 0; $i < $nrow; $i++) {
		$resarray= pg_fetch_array ($ret, $i);
		$sql = "select distinct count(*) from M_MAGAZINE where (mag_reader_occup = '$resarray[0]')";
		$sql .= " and ( mag_pub_status_flg = '01' or mag_pub_status_flg = '03' ) ";
		$sql .= " and ( mag_pub_stop_flg <> '02' )";
		$sql .= " and ( publisher_id <> 0 )";
		$ret_occup = db_exec($sql);

		if ( !$color_sw ) {
			echo "<tr align=\"left\" valign=\"top\" bgcolor=\"#e2f0c9\">\n";
			$color_sw = 1;
		}else{
			echo "<tr align=\"left\" valign=\"top\">\n";
			$color_sw = 0;
		}
		$res_occup= pg_fetch_array ($ret_occup, 0);
		echo "<td width=\"18\"><font size=\"2\">\n";
		echo "<input type=\"checkbox\" name=\"occup[]\" value=\"$resarray[0]\">";
		echo "</font></td>\n<td width=\"275\"><font size=\"2\">"; 
		echo "$resarray[1] ($res_occup[0])\n";
		echo "</font></td>";
		echo "</tr>\n";
	}

?> 

                    </table>
                  </td>
                </tr>
              </table>
              <p></p>
              <p>
                <input type="hidden" name="mail_addr">
                <input type="hidden" name="reader_id">
                <input type="hidden" name="reader_pswd">
                <br>
                <input type="submit" value="　検　索　" name="submit">
            </form>
          </td>
        </tr>
      </table>
      <!-- #EndEditable --></td>
          <td width="21" bgcolor="#ffffd3">　</td>
        </tr>
        <tr> 
          <td width="21"><img src="../common/c_left_bottom.gif" width="21" height="21"></td>
          <td width="21" bgcolor="#90c84b">　</td>
          <td width="557" bgcolor="#90c84b">　</td>
          <td width="21"><img src="../common/c_right_bottom.gif" width="21" height="21"></td>
        </tr>
<?php draw_footer( ".." ); ?>

      </table>
    </td>
  </tr>
</table>
</body>
<!-- #EndTemplate --></html>
