<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mypage.php");
?>
<HTML>
<HEAD>
<TITLE>�������ʪ�Υ��󥱡��ȥ�˥����ø��߼»���Υ��󥱡��Ȱ�����</TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Content-Script-Type" content="text/JavaScript">
<link rel="stylesheet" href="style.css" type="text/css">
<script language="JavaScript" src="mouseover.js"></script>
</HEAD>
<body bgcolor=#DDDDDD text=#333333 link=#333333 vlink=#333333 alink=#333333 
leftmargin=0 topmargin=0 rightmargin=0 bottommargin=0 marginwidth=0 marginheight=0>

<TABLE WIDTH=760 BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD bgcolor=#FFFFFF valign=top height=900>

	<TABLE WIDTH=720 BORDER=0 CELLPADDING=0 CELLSPACING=0>
	<TR>
	<TD background="images/marketer_title2.gif">

		<TABLE WIDTH=720 BORDER=0 CELLPADDING=0 CELLSPACING=0>
		<TR>
		<TD ROWSPAN=3>
			<IMG SRC="images/c_header_01.gif" WIDTH=618 HEIGHT=106></TD>
		<TD>
			<IMG SRC="images/c_header_02.gif" WIDTH=90 HEIGHT=75></TD>
		<TD>
			<IMG SRC="images/c_header_03.gif" WIDTH=12 HEIGHT=75></TD>
		</TR>
		<TR>
		<TD><a href="c_logout.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('b_logout','','images/b_logout_over.gif',1)"><IMG SRC="images/b_logout.gif" WIDTH=90 HEIGHT=24 name=b_logout BORDER=0 ALT="��������"></TD>
		<TD>
			<IMG SRC="images/c_header_05.gif" WIDTH=12 HEIGHT=24></TD>
		</TR>
		<TR>
		<TD>
			<IMG SRC="images/c_header_06.gif" WIDTH=90 HEIGHT=7></TD>
		<TD>
			<IMG SRC="images/c_header_07.gif" WIDTH=12 HEIGHT=7></TD>
		</TR>
		</TABLE>

	</TD>
	</TR>
	<TR>
	<TD bgcolor="#FFFFFF"><IMG SRC="images/spacer.gif" WIDTH=1 HEIGHT=1></TD>
	</TR>
	<TR>
	<TD bgcolor="#FFCC66" height=18>

		<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0>
		<TR>
		<TD><IMG SRC="images/spacer.gif" WIDTH=25 HEIGHT=18></TD>
		<TD><span class="t10">�ԣϣ�</span></TD>
		</TR>
		</TABLE>

	</TD>
	</TR>
	<TR>
	<TD bgcolor="#666666"><IMG SRC="images/spacer.gif" WIDTH=1 HEIGHT=1></TD>
	</TR>
	<TR>
	<TD align=center>

<TABLE WIDTH="100%" BORDER=0 CELLPADDING=5 CELLSPACING=0>
	<TR>
		<TD class="t21">����Ĵ���ο���</TD>
	</TR>
	<TR>
		<TD><SPAN class="t22">&nbsp;���������ȵڤ�Ĵ�����ơ���ݡ��ȷ��֤Ϥ���������򤷤Ƥ���������</SPAN></TD>
	</TR>
	<TR>
		<TD align="center">
<a href="shinsei.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('b_shinsei','','images/b_shinsei_over.gif',1)"><IMG SRC="images/b_shinsei.gif" WIDTH=130 HEIGHT=36 BORDER=0 ALT="Ĵ����������" name=b_shinsei></a></TD>
	</TR>
	<TR>
		<TD><IMG SRC="images/spacer.gif" WIDTH=1 HEIGHT=1></TD>
	</TR>
	<TR>
		<TD class="t21">��̡���ݡ��Ȥΰ���</TD>
	</TR>
	<TR>
		<TD><SPAN class="t22">&nbsp;�ƴ�Ȥη�̵ڤӥ�ݡ��ȤϤ����餫������Ǥ��ޤ���</SPAN></TD>
	</TR>
	<TR>
		<TD><IMG SRC="images/spacer.gif" WIDTH=1 HEIGHT=1></TD>
	</TR>
	<TR>
		<TD align="center">
<TABLE WIDTH=320 BORDER=0 CELLPADDING=0 CELLSPACING=0>
	<TR>
		<TD><SPAN class="t14">��<B>�����̷�����̥ꥹ��</B></SPAN></TD>
	</TR>
	<TR>
		<TD><IMG SRC="images/spacer.gif" WIDTH=1 HEIGHT=5></TD>
	</TR>
	<TR>
		<TD height="28" align="center"><SPAN class="t11">|
<?
// ���־������
$len = 0;
$sql = "SELECT gt_gyoutai_cd,gt_gyoutai_name"
		. " FROM m_gyoutai"
		. " ORDER BY gt_order";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
//�����Ū���ɲá�GMS��SM��ޤȤ�롣20061222
$fetch = pg_fetch_object($result, 0);
echo $br.'<a href="report.php?gyoutai_cd='.$fetch->gt_gyoutai_cd.'">GMS��SM</a> |';
for ($i = 2; $i < $nrow; $i++) {
//��END 20061222
//�����Ū�˥����ȥ����ȡ�20061222
//for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<a href="report.php?gyoutai_cd=<?=$fetch->gt_gyoutai_cd?>"><?=htmlspecialchars($fetch->gt_gyoutai_name)?></a> |
<?
}
?>
		</SPAN></TD>
	</TR>
</TABLE>
<BR>
<form name="form1" method="get" action="report.php">
<TABLE WIDTH=400 BORDER=0 CELLPADDING=4 CELLSPACING=0 BGCOLOR="#ffcc99" STYLE="border-style:solid;border-color:black;border-width:1">
	<TR>
		<TD><IMG SRC="images/spacer.gif" WIDTH=20 HEIGHT=1></TD>
		<TD><SPAN class="t14">��������̾����</SPAN><BR><SPAN class="t22">����������̾�����Ϥ��Ƥ���������</SPAN></TD>
		<TD><INPUT TYPE="text" NAME="chain_search_text" <?=value($_GET['chain_search_text'])?>> <INPUT TYPE="submit" VALUE="����"></TD>
		<TD><IMG SRC="images/spacer.gif" WIDTH=20 HEIGHT=1></TD>
	</TR>
<?
if (isset($_GET['search_err'])) {
	switch ($_GET['search_err']) {
	case 1:
		$search_err_text = '���������������̾�����Ϥ��Ƥ���������';
		break;
	case 2:
		$search_err_text = '��������������󤬤���ޤ���';
		break;
	case 3:
		$search_err_text = '���������������ʣ������ޤ���';
		break;
	}
?>
	<TR>
		<TD><IMG SRC="images/spacer.gif" WIDTH=20 HEIGHT=1></TD>
		<TD colspan=2><SPAN class="err"><?=$search_err_text?></SPAN></TD>
		<TD><IMG SRC="images/spacer.gif" WIDTH=20 HEIGHT=1></TD>
	</TR>
<?
}
?>
</TABLE>
</form>
<BR>
<TABLE WIDTH=320 BORDER=0 CELLPADDING=0 CELLSPACING=0>
	<TR>
		<TD><SPAN class="t14">��<B>��ݡ��Ȱ���</B></SPAN></TD>
	</TR>
	<TR>
		<TD><IMG SRC="images/spacer.gif" WIDTH=20 HEIGHT=5></TD>
	</TR>
	<TR>
		<TD>
			<TABLE WIDTH="100%" BORDER=1 CELLPADDING=2 CELLSPACING=0 BORDERCOLOR="#555555" bordercolordark="#ffffff" STYLE="border-style:solid;border-color:black;border-width:1">
				<TR BGCOLOR="#b9bda7" bordercolordark="#b9bda7">
					<TD STYLE="border-left-style:solid;border-top-style:solid"><SPAN class="t14">�ϰ�</SPAN></TD>
					<TD STYLE="border-top-style:solid"><SPAN class="t14">����</SPAN></TD>
					<TD STYLE="border-top-style:solid"><SPAN class="t14">��������̾</SPAN></TD>
				</TR>
<?
// ��������������
$sql = "SELECT ch_chain_cd,ch_name,ch_chain_area_cd,gt_gyoutai_cd,gt_gyoutai_name"
		. " FROM m_chain"
		. " JOIN m_gyoutai ON gt_gyoutai_cd=ch_gyoutai_cd"
		. " JOIN m_client_chain ON st_client_id={$_SESSION['ss_client_id']} AND st_chain_cd=ch_chain_cd"
		. " WHERE ch_status=0"
		. " ORDER BY gt_order,ch_order";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$gyoutai_name = ($fetch->gt_gyoutai_cd == 1 || $fetch->gt_gyoutai_cd == 2) ? 'GMS��SM' : $fetch->gt_gyoutai_name;
	$chain_ary[$fetch->ch_chain_area_cd][$fetch->ch_chain_cd] = array($gyoutai_name, $fetch->ch_name);
}

// �������󥨥ꥢ����
$sql = "SELECT ca_chain_area_cd,ca_area_name"
		. " FROM m_chain_area"
		. " ORDER BY ca_chain_area_cd";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	if (is_array($chain_ary[$fetch->ca_chain_area_cd])) {
		$area_flag = true;

		foreach ($chain_ary[$fetch->ca_chain_area_cd] as $chain_cd => $name_ary) {
			$gyoutai_name = $name_ary[0];
			$chain_name = $name_ary[1];
?>
				<TR>
<?
			if ($area_flag) {
?>
					<TD STYLE="border-left-style:solid;border-top-style:solid" rowspan="<?=count($chain_ary[$fetch->ca_chain_area_cd])?>"><SPAN class="t12"><?=htmlspecialchars($fetch->ca_area_name)?></a></SPAN></TD>
					<TD STYLE="border-top-style:solid"><SPAN class="t11"><?=htmlspecialchars($gyoutai_name)?></SPAN></TD>
					<TD STYLE="border-top-style:solid"><SPAN class="t11"><a href="report.php?chain_cd=<?=$chain_cd?>"><?=htmlspecialchars($chain_name)?></a></SPAN></TD>
<?
				$area_flag = false;
			} else {
?>
					<TD><SPAN class="t11"><?=htmlspecialchars($gyoutai_name)?></SPAN></TD>
					<TD><SPAN class="t11"><a href="report.php?chain_cd=<?=$chain_cd?>"><?=htmlspecialchars($chain_name)?></a></SPAN></TD>
<?
			}
?>
				</TR>
<?
		}
	}
}
?>
			</TABLE>
		</TD>
	</TR>
</TABLE>

		</TD>
	</TR>
</TABLE>
<BR>
<!--��ɽ�Ȥߢ�-->

	</TD>
	</TR>
	<TR>
	<TD><IMG SRC="images/footer_bar_ore.gif" WIDTH=720 HEIGHT=2></TD>
	</TR>
	<TR>
	<TD align=right><IMG SRC="images/copyright.gif" WIDTH=349 HEIGHT=22 ALT="copyright"></TD>
	</TR>
	<TR>
	<TD align=right>
<SPAN class="copyright">���Υۡ���ڡ����˻Ȥ��Ƥ���������̿����������ϡ�����&nbsp;<a href="http://www.xxxxxxx.co.jp/" target="_blank">������ҡ�����������</a>&nbsp;�˵�°���ޤ���<BR>
̵�Ǥ�ʣ���������Ѥ��뤳�Ȥ�ˡΧ�ˤ��ؤ����Ƥ��ޤ���<BR><BR></SPAN>
	</TD>
	</TR>
	</TABLE>

</TD>
<TD background="images/bg_gray.gif"><IMG SRC="images/spacer.gif" WIDTH=40 HEIGHT=1></TD>
</TR>
</table>

</BODY>
</HTML>
