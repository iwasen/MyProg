<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:トップページ（ログイン）
'******************************************************/
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
?>
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title>☆★☆xxxゾウさんClub☆★☆</title>
<script type="text/javascript">
<!--
function open_window(url) {
 window.open(url, "_blank","width=750,scrollbars=yes,resizable=yes,status=no,menubar=no,toolbar=no");
}
//-->
</script>
</HEAD>
<BODY BGCOLOR="#ff0000">
<DIV ALIGN="center">
<TABLE CELLPADDING="0" CELLSPACING="0" width=720>
	<TR>
		<TD colspan=5><IMG SRC="img/top/top_logo.gif" ALT="xxxゾウさんClub" width=720 height=195 usemap="#link_what" border=0></TD>
			<map name="link_what" title="Eco-footprintとは"><area coords="20,20,300,180" shape="rect" href="javascript:open_window('what_eco.php')"></map>
	</TR>
	<TR>
		<TD><IMG SRC="img/top/txit.gif" ALT="EcoゾウQ&A" width=452 height=50 border=0 usemap="#link_qa"></TD>
			<map name="link_qa" title="EcoゾウQ&A">
			<area coords="70,8,335,28" shape="rect" href="javascript:open_window('qa.php')">
			<area coords="230,28,450,45" shape="rect" href="http://www.macromedia.com/jp/shockwave/download/?P1_Prod_Version=ShockwaveFlash&Lang=Japanese" target="_blank">
			</map>
		<TD BGCOLOR="#ffffff" colspan=3 align="center" width=229>
			<IMG SRC="img/top/bane_banner.gif" ALT="プレゼント" width=200 height=40><BR>
		</TD>
		<TD BGCOLOR="#ffffff" width=39><BR></TD>
	</TR>
	<TR>
		<TD ROWSPAN="9"><IMG SRC="img/top/3toku.gif" ALT="Ecoゾウ３得" width=452 height=449><BR></TD>
		<TD colspan=3 height=52 width=229 bgcolor="#ffffff"><IMG SRC="img/top/title_kaiin.gif" ALT="会員入り口" width=229 height=52><BR></TD>
		<TD width=39><IMG SRC="img/top/kaiin_waku1.gif" ALT="img" width=39 height=52></TD>
	</TR>
	<TR>
		<form method="post" action="login.php">
		<TD BGCOLOR="#ffffff" width=57><IMG SRC="img/top/icon_id.gif" ALT="ID" width=57 height=16></td>
		<td width=136 BGCOLOR="#ffffff"><input type="text" name="member_id" size=17 maxlength=20 value=<?=$_COOKIE['ck_member_id']?>></td>
		<td bgcolor="#ffffff" rowspan=2 width=36><input type="image" src="img/top/button_go.gif" alt="GO" width=36 height=36></td>
		<TD rowspan=5 bgcolor="#ffffff" width=39><IMG SRC="img/top/kaiin_waku2.gif" ALT="img" width=39 height=167></TD>
	</tr>
	<tr>
		<TD BGCOLOR="#ffffff" width=57><IMG SRC="img/top/icon_pass.gif" ALT="password" width=57 height=16></td>
		<td BGCOLOR="#ffffff" width=136><input type="password" name="password" size=17 maxlength=20></td>
		</form>
	</tr>
	<tr>
		<td align="center" colspan=3 BGCOLOR="#ffffff" width=229>
			<IMG SRC="img/top/pass2.gif" ALT="パスワード忘れたら" width=127 height=11 usemap="#link_help" border=0>
			<map name="link_help" title="パスワード忘れ"><area coords="0,0,130,15" shape="rect" href="help.php"></map>
		</td>
	</tr>
	<tr>
		<td colspan=3 valign="top" align="center" BGCOLOR="#ffffff"><IMG SRC="img/top/oshirase.gif" ALT="お知らせ" width=182 height=13></td>
	</tr>
	<tr>
		<td colspan=3 align="center" valign="bottom" BGCOLOR="#ffffff">
			<IFRAME SRC="top_info.php" NAME="info" width=229 height=88 frameborder=0></IFRAME>
		</TD>
	</TR>
	<TR>
		<TD colspan=3 height=64 width=229 bgcolor="#ffffff"><IMG SRC="img/top/title_touroku.gif" ALT="登録する" width=229 height=64><BR></TD>
		<TD><IMG SRC="img/top/kaiin_waku3.gif" ALT="img" width=39 height=64><BR></TD>
	</TR>
	<TR>
		<TD BGCOLOR="#ffffff" colspan=3 align="center" height="143" width=229>
		<IMG SRC="img/top/button_touroku.gif" ALT="登録する" width=218 height=82 usemap="#link_regist" border=0><BR>
			<map name="link_regist" title="登録する"><area coords="0,0,218,82" shape="rect" href="rule.php"></map>
		<IMG SRC="img/top/button_toiawase.gif" ALT="問合せ" width=164 height=19 usemap="#link_mail" border=0>
			<map name="link_mail" title="お問合せ"><area coords="10,0,180,20" shape="rect" href="mailto:info@ecofootprint.jp"></map>
		</TD>
		<TD width=39 bgcolor="#ffffff"><IMG SRC="img/top/kaiin_waku4.gif" ALT="img" width=39 height=143><BR></TD>
	</TR>
	<TR>
		<TD colspan=3 bgcolor="#ffffff"><IMG SRC="img/top/kaiin_waku6.gif" ALT="img" width=229 height=23><BR></TD>
		<TD height=23 bgcolor="#ffffff"><IMG SRC="img/top/kaiin_waku5.gif" ALT="img" width=39 height=23><BR></TD>
	</TR>
</TABLE>

<TABLE CELLPADDING="0" CELLSPACING="0" width=720>
	<TR>
		<TD><IMG SRC="img/top/whateco.gif" ALT="xxxゾウさんClubとは" width=720 height=628 usemap="#link_what2" border=0><BR></TD>
			<map name="link_what2" title="Eco-footprintとは"><area coords="580,246,620,270" shape="rect" href="javascript:open_window('what_eco.php')"></map>
	</TR>
</TABLE>
</DIV></BODY>
</HTML>
