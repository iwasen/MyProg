<?php
include ("../inc/com_db_func.inc");
include ("../melonpa/inc/error_msg_list.inc");
include ("../sub/head.inc");
include ("bucknumber_inc.php");

/* ---------------------------------------- */
/* BackNumber取得							*/
/* ---------------------------------------- */
function get_backnum($mag_id){

	$sql = "select index_id, to_char(MODEFY_DT ,'YY/MM/DD') from L_BACKNUMBER "
		.  " where mag_id = '$mag_id' and MODEFY_DT >= current_timestamp - 365 order by modefy_dt desc";
	$ret = db_exec($sql);
	if (!$ret){
		return;
	}
	$cnt = pg_numrows($ret);
	for( $i = 0 ; $i < $cnt ; $i++ ){
		$backnum= pg_fetch_array ($ret, $i);
		echo "<tr bgcolor=\"#ffffd3\"><td width=21></td><td width=38 align=\"center\"><font size=2>";
		echo "<a href=\"../letter/backnumber_all.php?back_rid=". $backnum[0] ."\">".$backnum[1]."</a>";
		echo "</font></td><td width=21></td></tr>";
	}

	echo "<p>";
	echo "<tr bgcolor=\"#ffffd3\"><td width=21></td><td width=38 align=\"left\"><font size=2>";
	echo "<a href=\"../melonpa/mag-detail.php?mag_id=".$mag_id."\">戻る</a>";
	echo "</font></td><td width=21></td></tr>";

	return;
}

/* ---------------------------------------- */
/* <a href>追加  							*/
/* ---------------------------------------- */
function add_href_tag($body){

	$str_list = array();

	$st=0; $cnt=0;

	while ( !(($pos = strpos ($body, "http://", $st)) === false) ){
		// 当面80文字以内でurlチェック
		for ( $i=0 ; $i<200 ; $i++ ){
			$a_char = substr( $body, $pos+$i+7, 1);
			if ($a_char == " " or $a_char == "\n" or $a_char == "\r" or $a_char == "<" ){
				$url = substr($body, $pos, $i+7);
				$ref = "<a href=\"".$url."\" target='_blank'>".$url."</a>";
				// ２バイト文字混入か（limitterが2バイト文字の場合当面無効）
				if ( mbstrlen($url) == strlen($url) ){ 
					array_push($str_list, $ref, $url, $pos);
					$cnt++;
				}
				break;
			}
		}
		$st = $pos + 7;
	}
	for ( $i=0; $i<$cnt ; $i++ ){
		$pos = array_pop($str_list);
		$url = array_pop($str_list);
		$ref = array_pop($str_list);
		$body = substr_replace( $body, $ref, $pos, strlen($url) );
	}
	return $body;
}

/****************************************************************
 Main
****************************************************************/

// 非表示チェック
bucknb_check($back_rid);

$sql = "select L.MAG_ID,L.MAG_TITL,L.MAG_BODY,to_char(L.MODEFY_DT ,'YY/MM/DD  HH24:MI') as modefy_dt,L.MAG_VOLUME,";
$sql .= "M.MAG_NM,P.OWNER_NM_KANJ,L.BODY_ID ";
$sql .= " from L_BACKNUMBER L,M_MAGAZINE M,M_PUBLISHER P ";
$sql .= " where (L.index_id = '$back_rid') and ";
$sql .= " L.MAG_ID = M.MAG_ID and ";
$sql .= " M.PUBLISHER_ID = P.PUBLISHER_ID ";
$result = db_exec($sql);
$cols = pg_numrows($result);
$cnt = 0;
if ($cols < 1 ) { $table = $em_back_1; }
	$fetch = pg_fetch_object($result, 0);
	$mag_id = $fetch->mag_id;
	$mag_title = $fetch->mag_titl;
	$mag_body = $fetch->mag_body;
	$modefy_dt = $fetch->modefy_dt;
	$mag_volume = $fetch->mag_volume;
	$mag_nm = $fetch->mag_nm;
	$publisher_nm_kanj = $fetch->owner_nm_kanj;
	$body_id = $fetch->body_id;

	$mag_body = htmlspecialchars($mag_body);
	$mag_body = add_href_tag($mag_body);

?>
<html><!-- #BeginTemplate "/Templates/index.dwt" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title>めろんぱん || よりすぐりメルマガサイト</title>
</head>
<body bgcolor="#FFFFFF" text="#666666" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" vlink="#993300" link="#336600">
<?php	draw_head( ".." );	?>
<table width="753" border="0" cellspacing="0" cellpadding="0">
  <tr>

    <td align="center" valign="top"> 
      <table width="620" border="0" cellspacing="0" cellpadding="0" align="center">
        <tr> 
          <td width="21"><img src="img/letter1.gif" width="21" height="21"></td>
          <td width="21"><img src="img/letter3.gif" width="21" height="21"></td>
          <td width="557">　</td>
          <td width="21">　</td>
        </tr>
        <tr> 
          <td width="21"><img src="img/letter2.gif" width="21" height="21"></td>
          <td width="21" bgcolor="#90c84b"><img src="img/letter4.gif" width="21" height="21"></td>
          <td width="557" bgcolor="#90c84b"><img src="img/syosai.gif" width="89" height="21"></td>
          <td width="21"><img src="../common/c_rihgt_top.gif" width="21" height="21"></td>
        </tr>
        <tr bgcolor="#ffffd3"> 
          <td colspan="4" bgcolor="#ffffd3" height="21">
      <p>　</p>
      <p>　</p>
          </td>
        </tr>
        <tr> 
          <td width="21" bgcolor="#ffffd3">　</td>
          <td width="21" bgcolor="#ffffd3">　</td>
          <td width="557" bgcolor="#ffffd3"><!-- #BeginEditable "%90%7D" --> 
      <table border="0" cellpadding="0" cellspacing="0" bgcolor="#91c741">
        <tr> 
          <td> 
            <table width="517" border="0" cellspacing="1" cellpadding="2">
              <tr bgcolor="#ffcc33"> 
                <td colspan="4" bgcolor="#91c741"><font color="#336600"><b>【</b></font><font color="white"><b>ID <?php echo $mag_id ?>：</b></font>
<font color="black"><b><?php echo $mag_nm ?></b></font><b><font color="#336600">】</font></b></td>
              </tr>
              <tr align="center" bgcolor="#e2f0c9"> 
                <td width="73"> 
                  <center>
                    <font color="#336600" class="text9ptnoColor" size="2">メルマガID</font> 
                  </center>
                </td>
                <td width="265"><font color="#336600" class="text9ptnoColor" size="2">タイトル</font></td>
                <td width="100"><font color="#336600" class="text9ptnoColor" size="2">配信日時</font></td>
                <td width="58"><font color="#336600" class="text9ptnoColor" size="2">発行部数</font></td>
              </tr>
              <tr> 
                <td align="center" width="73" bgcolor="white"> 
                  <center>
                    <font size="2"><?php echo $mag_id ?> </font> 
                  </center>
                </td>
                <td align="center" width="265" bgcolor="white"><font size="2"><?php echo $mag_nm ?> 
                  </font></td>
                <td align="center" width="100" bgcolor="white"><font size="2"><?php echo $modefy_dt; ?></font></td>
                <td align="right" width="58" bgcolor="white"><font size="2"><?php echo $mag_volume; ?></font></td>
              </tr>
              <tr> 
                <td align="center" bgcolor="#e2f0c9" colspan="4"> 
                  <center>
                    <font color="#336600" class="text9ptnoColor" size="2">●●●本　文●●●</font> 
                  </center>
                </td>
              </tr>
              <tr> 
                <td align="center" bgcolor="white" colspan="4" valign="top"> 
                  <div align="left"> 
                    <pre>　
</pre>
                    <table border="0" cellpadding="7" cellspacing="0">
                      <tr> 
                        <td align="left" valign="top"> 
                          <div align="left"> 
<pre><tt><?php echo $mag_body; ?></tt></pre>
                            <p> 
                            <p> </p>
                          </div>
                          <p> 
                          <p> 
                        </td>
                      </tr>
                    </table>
                  </div>
                </td>
              </tr>
            </table>
          </td>

        </tr>
      </table>
      <br>
      <br>
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
<!-- バックナンバー一覧 -->
<td width="5">　</td>
<td align="center" valign="TOP"> 
  <table width="80" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr> 
	  <td width="21" height="22">　</td>
      <td width="38" height="22">　</td>
      <td width="21" height="22">　</td>
    </tr>
    <tr> 
      <td width="21"><img src="../img/r_left.gif" width="21" height="21"></td>
      <td width="38" bgcolor="#90c84b" align="center"><font color="black" size="2"></font></td>
      <td width="21"><img src="../common/c_rihgt_top.gif" width="21" height="21"></td>
    </tr>
	<tr bgcolor="#ffffd3"> 
      <td colspan="4" bgcolor="#ffffd3" height="21">
        <p>　</p>
        <p>　</p>
      </td>
	</tr>
	<tr bgcolor="#ffffd3">
	  <td width="21" align="right"> <font size="2" color="black">B</td>
	  <td width="38" align="center"><font size="2" color="black">acknumbe</font></td>
	  <td width="21" align="left">  <font size="2" color="black">r</td>
	</tr>
      <?php $backnum=get_backnum($mag_id) ; ?>	
	<tr bgcolor="#ffffd3"> 
      <td colspan="4" bgcolor="#ffffd3" height="21">
        <p>　</p>
        <p>　</p>
      </td>
	</tr>
	<tr> 
      <td width="21"><img src="../common/c_left_bottom.gif" width="21" height="21"></td>
      <td width="38" bgcolor="#90c84b">　</td>
      <td width="21"><img src="../common/c_right_bottom.gif" width="21" height="21"></td>
    </tr>
  </table>
<!-------------------->
  </tr>
</table>
</body>
<!-- #EndTemplate --></html>
