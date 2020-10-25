<?php
include( "../sub/head.inc");
?>
<html>
<!-- #BeginTemplate "/Templates/index.dwt" --> 
<head>
<!-- #BeginEditable "doctitle" --> 
<title>めろんぱん || よりすぐりメルマガサイト</title>
<!-- #EndEditable --> 
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
</head>
<body bgcolor="#FFFFFF" text="#666666" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" vlink="#666600" link="#336600">
<?php	draw_head( ".." );	?>
<TABLE width="753" border="0" cellspacing="0" cellpadding="0">
  <TR> 
    <TD align="center"> 
      <TABLE width="620" border="0" cellspacing="0" cellpadding="0" align="center">
        <TR> 
          <TD width="21"><IMG src="../search/img/g1.gif" width="21" height="21"></TD>
          <TD width="21"><IMG src="../search/img/g3.gif" width="21" height="21"></TD>
          <TD width="557"></TD>
          <TD width="21">　</TD>
        </TR>
        <TR> 
          <TD width="21"><IMG src="../search/img/g2.gif" width="21" height="21"></TD>
          <TD width="21" bgcolor="#90c84b"><IMG src="../search/img/g4.gif" width="21" height="21"></TD>
<!--
          <TD width="557" bgcolor="#90c84b"><IMG src="../search/img/rankingtitle.gif" width="135" height="21" alt="いろいろRanking"></TD>
-->
<TD>
<TABLE width="557" border="0" cellspacing="0" cellpadding="0" align="center">
<TR>
<TD width="157" bgcolor="#94cd3e"><IMG src="../search/img/rankingtitle.gif" width="135" height="21" alt="いろいろRanking"></TD>
<TD width="400" bgcolor="#94cd3e"><div align="right" ><font size=2 color="000000">
<?php
//if ( $rank_id != "102" ){ //どんどん読者が増えている以外
	if ( strftime("%M", time()) > 15){
		$mm = round(strftime("%m",time()),0);
		$dd = round(strftime("%d",time()),0);
		$hh = round(strftime("%H",time()),0);
	}else{
		$mm = round(strftime("%m",time() - (60*60)),0);
		$dd = round(strftime("%d",time() - (60*60)),0);
		$hh = round(strftime("%H",time() - (60*60)),0);
	}
	$koushinbi = $mm."月".$dd."日 ".$hh."時現在";
//}
echo $koushinbi;
?>
</font></div></TD>
</TR>
</TABLE>
</TD>
          <TD width="21"><IMG src="../common/c_rihgt_top.gif" width="21" height="21"></TD>
        </TR>
        <TR bgcolor="#ffffd3"> 
          <TD colspan="4" bgcolor="#ffffd3" height="21"> <P>　</P></TD>
        </TR>
        <TR> 
          <TD width="21" bgcolor="#ffffd3">　</TD>
          <TD width="21" bgcolor="#ffffd3">　</TD>
          <TD width="557" bgcolor="#ffffd3">
            <TABLE width="557" border="0" cellspacing="0" cellpadding="0">
              <TR>
                <TD valign="top"> 
                  <TABLE border="0" cellspacing="0" cellpadding="5" width="540">

<TR>
<TD align="right" colspan="2"><font size="2" style="font size=8pt"><?php echo $cmnt; ?></font></TD>
</TR>

<TR>
<TD width=28  bgcolor="#e2f0c9" align="center">  <IMG src="../search/img/kanmuri.gif" width=18 height=20></TD><TD width=502 bgcolor="#e2f0c9" ><b>  <?php echo $rank_msg; ?> </b></TD>
</TR>

                  </TABLE>
                </td>
              </tr>

              <TR><TD height=6 width="557" ></td></tr>

<TR>
<TD valign="top"> 
<TABLE border="0" cellspacing="0" cellpadding="5" width="540">
<?php
if ( $nrows == 0 ){
		echo "<BR>";
		echo "ただいまランキングを更新中です。";
}else{
//	for ( $i = 0 ; $i < $nrows ; $i++ ) {
	for ($i = 0; $i < $nrows; $i++) {
		$resarray= pg_fetch_array ($ret, $i);
/* **
		reset ($results);
		$column = each ($results);	$ranking = $column['value'];
		$column = each ($results);	$mag_id = $column['value'];
		$column = each ($results);	$mag_nm = $column['value'];
		$column = each ($results);	$point = $column['value'];
*/
		$ranking[$i] = $resarray[0];
		$mag_id[$i] = $resarray[1];
		$mag_nm[$i] = $resarray[2];
		$point[$i] = $resarray[3];
		if ($rank_id == "102") {
			$ranking[$i] = $i + 1;
		}
		if ( $ranking[$i] > 10 ){
			$next_mag = $i ;
			break;
		}
		if($rank_id == "101"): 	   $point_view = number_format($point[$i]). "人" ;
		elseif($rank_id == "102"): $point_view = number_format($point[$i]). " up" ;
		elseif($rank_id == "103"): $point_view = number_format($point[$i]). " 通" ;
		elseif($rank_id == "104"): $point_view = sprintf ("%01.2f", $point[$i]). " %" ;
		elseif($rank_id == "105"): $point_view = sprintf ("%01.1f", $point[$i]). " 才" ;
		elseif($rank_id == "106"): $point_view = sprintf ("%01.1f", $point[$i]). " 才" ;
		else:					   $point_view = sprintf ("%01.1f", $point[$i]). " %" ;
		endif;
		if ( $i % 2 ) $bgcolor = "\"#FFFFFF\""; else $bgcolor = "\"#FFFF99\"" ;

		echo "<TR>";
		echo "<TD width=30 align=\"right\" bgcolor=$bgcolor><b><font color=\"#FF3300\">$ranking[$i]</font></b></TD>";
		echo "<TD width=400 align=\"left\" bgcolor=$bgcolor nowrap><b><font color=\"#336600\">";
		echo "<a href=\"../melonpa/mag-detail.php?mag_id=".$mag_id[$i]."\">".$mag_nm[$i]."</a></font></b></TD>";
		echo "<TD width=70 align=\"right\" bgcolor=$bgcolor><b><font color=\"#336600\">$point_view</font></b></TD>";
		echo "</TR>";
		
	}
}
?>
</TABLE>
</td>
</tr>

<TR><TD height=10 width="557" ></td></tr>
<TR>
<TD valign="top"> 
<TABLE border="0" cellspacing="0" cellpadding="5" width="540">

<?php
if ($next_mag!=""){
//	for ( $i = $next_mag; $i < $nrows, $i < 50; $i++ ) {
	for ( $i = $next_mag; $i < $nrows; $i++ ) {
		$resarray= pg_fetch_array ($ret, $i);
/*
		reset ($results);
		$column = each ($results);	$ranking = $column['value'];
		$column = each ($results);	$mag_id = $column['value'];
		$column = each ($results);	$mag_nm = $column['value'];
		$column = each ($results);	$point = $column['value'];
*/
		$ranking[$i] = $resarray[0];
		$mag_id[$i] = $resarray[1];
		$mag_nm[$i] = $resarray[2];
		$point[$i] = $resarray[3];

		if($rank_id == "101"): 	   $point_view = number_format($point[$i]). "人" ;
		elseif($rank_id == "102"): $point_view = number_format($point[$i]). " up" ;$ranking[$i] = $i + 1;
		elseif($rank_id == "103"): $point_view = number_format($point[$i]). " 通" ;
		elseif($rank_id == "104"): $point_view = sprintf ("%01.2f", $point[$i]). " %" ;
		elseif($rank_id == "105"): $point_view = sprintf ("%01.1f", $point[$i]). " 才" ;
		elseif($rank_id == "106"): $point_view = sprintf ("%01.1f", $point[$i]). " 才" ;
		else:					   $point_view = sprintf ("%01.1f", $point[$i]). " %" ;
		endif;

		echo "<TR>";
		echo "<TD width=30 align=\"right\">$ranking[$i]</TD>";
		echo "<TD width=395 align=\"left\" nowrap>";
		echo "<a href=\"../melonpa/mag-detail.php?mag_id=".$mag_id[$i]."\">".$mag_nm[$i] ."</a></TD>";
		echo "<TD width=75 align=\"right\">$point_view</TD>";
		echo "</TR>";
		if ( (($i+1) % 5) == 0 ){
			echo "</TABLE>";
            echo "</td>";
			echo "</tr>";
			echo "<TR><TD height=10 width=557 ></td></tr>";
			echo "<TR>";
			echo "<TD valign=\"top\">";
			echo "<TABLE border=0 cellspacing=0 cellpadding=5 width=540>";
		}
	if ($i >= 50) {
		break;
	}
	}
}
?>
</TABLE>
</td>
</tr>

            </TABLE>
            </TD>
          <TD width="21" bgcolor="#ffffd3">　</TD>
        </TR>
        <TR bgcolor="#ffffd3"> 
          <TD colspan="4" bgcolor="#ffffd3" height="21"> <P>　</P></TD>
        </TR>
        <TR> 
          <TD width="21"><IMG src="../common/c_left_bottom.gif" width="21" height="21"></TD>
          <TD width="21" bgcolor="#90c84b">　</TD>
          <TD width="557" bgcolor="#90c84b">　</TD>
          <TD width="21"><IMG src="../common/c_right_bottom.gif" width="21" height="21"></TD>
        </TR>
<?php draw_footer( ".." ); ?>
<!--
<TR> 
  <TD colspan="4"> 
    <DIV align="center"><BR>
      <BR>
<A href="http://www.echelp.net/" target="_blank"><IMG src="../banner/ecHelp.gif" width=468 height=60 border=0></A>
      <BR>
      <BR>
      <FONT size="1">※<A href="../index.html">めろんぱん</A>は、（株）○○○○○○の幕ﾆ理念「発信する生活ﾒをx援する」に基づいて運営されています。</FONT><BR>
      <HR width="85%" size="1">
      <FONT size="2"><BR>
      <BR>
      Copyright(c)2001 xxxxxxx, Co., Ltd. All rights reserved.<BR>
      <A href="http://www.xxxxxxx.co.jp/guide/xxxxxxx.html" target="_blank"><IMG src="../common/do_rogo.gif" width="40" height="40" border="0"></A><BR>
      <BR>
      </FONT></DIV>
  </TD>
</TR>
-->
      </TABLE>
    </TD>
  </TR>
</TABLE>
</BODY>
</HTML>

