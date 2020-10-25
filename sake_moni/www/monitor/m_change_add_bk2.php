<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/get_form.php");
include("$inc/select.php");
include("$inc/check.php");
$inc = "$top/inc";
include("$inc/mypage.php");

// 入力パラメータ取得
$payment_type = get_zip($_POST['payment_type']);
$zip = get_zip($_POST['zip']);
$area = $_POST['area'];
$address = $_POST['address'];
$name1 = $_POST['name1'];
$name2 = $_POST['name2'];
$mail_addr = $_POST['mail_addr'];
$account_num = $_POST['account_num'];
$net_kind = $_POST['net_kind'];
$monitor_id = $_SESSION['ss_monitor_id'];

if ($_POST['edit']) {
	if ($net_kind == 2) {
		if ($payment_type == '')
			$err['payment_type'] = '謝礼支払方法が選択されていないようです';
		else {
			if ($payment_type == 2) {
				if ($zip == '')
					$err['zip'] = '郵便番号が入力されていないようです';
				elseif (!check_zip2($zip))
					$err['zip'] = '郵便番号が正しく入力されていないようです';

				if ($area == '')
					$err['area'] = '都道府県が選択されていないようです';

				if ($address == '')
					$err['address'] = '住所が入力されていないようです';
			}

			if ($payment_type == 3) {
				if ($account_num == '')
					$err['account_num'] = '口座番号が入力されていないようです';
				elseif (!check_num($account_num))
					$err['account_num'] = '口座番号が正しく入力されていないようです';
			}

			if ($name1 == '' || $name2 == '')
				$err['name'] = 'お名前が入力されていないようです';
		}
	}

	if ($mail_addr == '')
		$err['mail_addr'] = 'メールアドレスが入力されていないようです';
	elseif (!check_mail_addr($mail_addr))
		$err['mail_addr'] = 'メールアドレスが正しく入力されていないようです';

	if (!$err) {
		$change_add['payment_type'] = $payment_type;
		$change_add['zip'] = $zip;
		$change_add['area'] = $area;
		$change_add['address'] = $address;
		$change_add['name1'] = $name1;
		$change_add['name2'] = $name2;
		$change_add['mail_addr'] = $mail_addr;
		$change_add['account_num'] = $account_num;
		$change_add['net_kind'] = $net_kind;
		$_SESSION['change_add'] = $change_add;

		redirect('m_change_add_conf.php');
	}
} else {
	$sql = "SELECT mn_mail_addr,mn_jitaku_zip,mn_jitaku_area,mn_jitaku_addr2,mn_name1,mn_name2,mn_net_kind,mn_payment_type,mn_account_num FROM t_monitor WHERE mn_monitor_id=$monitor_id";
	$result = db_exec($sql);
	if (pg_num_rows($result)) {
		$fetch = pg_fetch_object($result, 0);

		$mail_addr = $fetch->mn_mail_addr;
		$zip = $fetch->mn_jitaku_zip;
		$area = $fetch->mn_jitaku_area;
		$address = $fetch->mn_jitaku_addr2;
		$name1 = $fetch->mn_name1;
		$name2 = $fetch->mn_name2;
		$net_kind = $fetch->mn_net_kind;
		$payment_type = $fetch->mn_payment_type != '' ? $fetch->mn_payment_type : '2';
		$account_num = $fetch->mn_account_num;
	}
}

?>
<HTML>
<HEAD>
<TITLE>お酒と買物のアンケートモニター｜登録情報の変更｜</TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Content-Script-Type" content="text/JavaScript">
<link rel="stylesheet" href="style.css" type="text/css">
<script language="JavaScript" src="mouseover.js"></script>
<script type="text/javascript">
<!--
function change_payment_type() {
	var f = document.form1;
	if (f.payment_type) {
		if (f.payment_type[0].checked) {
			change_display("zip", false);
			change_display("area", false);
			change_display("address", false);
			change_display("account_num", false);
		} else if (f.payment_type[1].checked) {
			change_display("zip", true);
			change_display("area", true);
			change_display("address", true);
			change_display("account_num", false);
		} else if (f.payment_type.length >= 3 && f.payment_type[2].checked) {
			change_display("zip", false);
			change_display("area", false);
			change_display("address", false);
			change_display("account_num", true);
		}
	}
}
function change_display(name, disp) {
	if (document.getElementById(name + "1"))
		document.getElementById(name + "1").style.display = disp ? "" : "none";
	if (document.getElementById(name + "2"))
		document.getElementById(name + "2").style.display = disp ? "" : "none";
}
function onload_body() {
	change_payment_type();
}
//-->
</script>
</HEAD>
<body bgcolor=#DDDDDD text=#333333 link=#333333 vlink=#333333 alink=#333333 
leftmargin=0 topmargin=0 rightmargin=0 bottommargin=0 marginwidth=0 marginheight=0 onload="onload_body()">

<TABLE WIDTH=760 BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD bgcolor=#FFFFFF valign=top height=900>

	<TABLE WIDTH=720 BORDER=0 CELLPADDING=0 CELLSPACING=0>
	<TR>
	<TD background="images/monitor_title2.gif">

		<TABLE WIDTH=720 BORDER=0 CELLPADDING=0 CELLSPACING=0>
		<TR>
		<TD ROWSPAN=3>
			<IMG SRC="images/m_header_01.gif" WIDTH=618 HEIGHT=106></TD>
		<TD>
			<IMG SRC="images/m_header_02.gif" WIDTH=90 HEIGHT=75></TD>
		<TD>
			<IMG SRC="images/m_header_03.gif" WIDTH=12 HEIGHT=75></TD>
		</TR>
		<TR>
		<TD><a href="m_logout.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('b_logout','','images/b_logout_over.gif',1)"><IMG SRC="images/b_logout.gif" WIDTH=90 HEIGHT=24 name=b_logout BORDER=0 ALT="ログアウト"></TD>
		<TD>
			<IMG SRC="images/m_header_05.gif" WIDTH=12 HEIGHT=24></TD>
		</TR>
		<TR>
		<TD>
			<IMG SRC="images/m_header_06.gif" WIDTH=90 HEIGHT=7></TD>
		<TD>
			<IMG SRC="images/m_header_07.gif" WIDTH=12 HEIGHT=7></TD>
		</TR>
		</TABLE>

	</TD>
	</TR>
	<TR>
	<TD bgcolor="#FFFFFF"><IMG SRC="images/spacer.gif" WIDTH=1 HEIGHT=1></TD>
	</TR>
	<TR>
	<TD bgcolor="#99CCFF" height=18>

		<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 width=720>
		<TR>
		<TD WIDTH=25><IMG SRC="images/spacer.gif" WIDTH=25 HEIGHT=18></TD>
		<TD align="left" WIDTH=375><span class="t10"><a href="m_index.php">現在実施中のアンケート一覧</a>&nbsp;＞&nbsp;登録情報の変更</span></TD>
		<TD align="right" WIDTH=300><span class="t10"><a href="m_change_add.php">［登録情報の変更］</a>&nbsp;<a href="m_change_pw.php">［パスワード変更］</a>&nbsp;</span></TD>
		</TR>
		</TABLE>

	</TD>
	</TR>
	<TR>
	<TD bgcolor="#666666"><IMG SRC="images/spacer.gif" WIDTH=1 HEIGHT=1></TD>
	</TR>
	<TR>
	<TD align=center height=320>

<!--▼登録情報の変更フォーム▼-->
<form name="form1" method="post" action="m_change_add.php">
	<TABLE WIDTH=490 BORDER=0 CELLPADDING=1 CELLSPACING=4 bgcolor=#DDDDDD>
		<TR>
		<TD bgcolor=#FFFFFF>

			<TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=1 bgcolor=#99CCFF>
			<TR>
			<TD>

				<TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=0 bgcolor=#FFFFFF>
				<TR>
				<TD align="center" valign="center">
					<TABLE WIDTH=433 BORDER=0 CELLPADDING=0 CELLSPACING=7>
					<TR>
					<TD><IMG SRC="images/spacer.gif" WIDTH=102 HEIGHT=1></TD>
					<TD><IMG SRC="images/spacer.gif" WIDTH=310 HEIGHT=1></TD>
					</TR>
					<TR>
					<TD colspan=2><IMG SRC="images/m_change_add.gif" WIDTH=200 HEIGHT=36 alt="登録情報の変更"></TD>
					</TR>
<?
if ($net_kind == 2) {
?>
					<TR>
					<TD><IMG SRC="images/monitor00_14.gif" WIDTH=102 HEIGHT=22 ALT="謝礼支払方法"></TD>
					<TD><span class="t12">
						<input type="radio" name="payment_type" <?=value_checked(1, $payment_type)?> onclick="change_payment_type()">iMiポイント&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="radio" name="payment_type" <?=value_checked(2, $payment_type)?> onclick="change_payment_type()">郵便為替&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<!--<input type="radio" name="payment_type" <?=value_checked(3, $payment_type)?> onclick="change_payment_type()">ネットバンク-->
					</TD>
					</TR>
<?
	if ($err['payment'] != '') {
?>
					<TR>
					<TD></TD>
					<TD><span class="err">＜<?=$err['payment']?>＞</span></TD>
					</TR>
<?
	}
?>
					<TR id="zip1">
					<TD><IMG SRC="images/monitor00_10.gif" WIDTH=102 HEIGHT=22 ALT="郵便番号"></TD>
					<TD><span class="t12"><INPUT size=8 name="zip" <?=value($zip)?>> ※半角数字７桁でご記入ください。（例1300015）</span></TD>
					</TR>
<?
	if ($err['zip'] != '') {
?>
					<TR id="zip2">
					<TD></TD>
					<TD><span class="err">＜<?=$err['zip']?>＞</span></TD>
					</TR>
<?
	}
?>
					<TR id="area1">
					<TD><IMG SRC="images/monitor00_11.gif" WIDTH=102 HEIGHT=22 ALT="都道府県"></TD>
					<TD><span class="t12"><SELECT name="area"><? select_area('--- 選択してください ---', $area) ?></select></span></TD>
					</TR>
<?
	if ($err['area'] != '') {
?>
					<TR id="area2">
					<TD></TD>
					<TD><span class="err">＜<?=$err['area']?>＞</span></TD>
					</TR>
<?
	}
?>
					<TR id="address1">
					<TD><IMG SRC="images/monitor00_12.gif" WIDTH=102 HEIGHT=22 ALT="住所"></TD>
					<TD><span class="t12"><INPUT size=50 name="address" <?=value($address)?>></span></TD>
					</TR>
<?
	if ($err['address'] != '') {
?>
					<TR id="address2">
					<TD></TD>
					<TD><span class="err">＜<?=$err['address']?>＞</span></TD>
					</TR>
<?
	}
?>
					<TR>
					<TD><IMG SRC="images/monitor00_16.gif" WIDTH=102 HEIGHT=22 ALT="お名前"></TD>
					<TD><span class="t12">
					（姓）<INPUT size=50 name="name1" <?=value($name1)?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					（名）<INPUT size=50 name="name2" <?=value($name2)?>></span></TD>
					</TR>
<?
	if ($err['name'] != '') {
?>
					<TR>
					<TD></TD>
					<TD><span class="err">＜<?=$err['name']?>＞</span></TD>
					</TR>
<?
	}
?>
<?
}
?>
					<TR>
					<TD><IMG SRC="images/monitor00_13.gif" WIDTH=102 HEIGHT=22 ALT="メールアドレス"></TD>
					<TD><span class="t12"><INPUT size=50 name="mail_addr" <?=value($mail_addr)?>></span></TD>
					</TR>
<?
if ($err['mail_addr'] != '') {
?>
					<TR>
					<TD></TD>
					<TD><span class="err">＜<?=$err['mail_addr']?>＞</span></TD>
					</TR>
<?
}

if ($net_kind == 2) {
?>
					<TR id="account_num1" style="display:none">
					<TD><IMG SRC="images/monitor00_15.gif" WIDTH=102 HEIGHT=22 ALT="口座番号"></TD>
					<TD><span class="t12"><INPUT size=50 name="account_num" <?=value($account_num)?>></span></TD>
					</TR>
<?
	if ($err['account_num'] != '') {
?>
					<TR id="account_num2" style="display:none">
					<TD></TD>
					<TD><span class="err">＜<?=$err['account_num']?>＞</span></TD>
					</TR>
<?
	}
}
?>
					<TR>
					<TD></TD>
					<TD align="left"><span onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('b_s1','','images/b_s1_over.gif',1)"><input type="image" SRC="images/b_s1.gif" WIDTH=130 HEIGHT=36 BORDER=0 ALT="変更する" name=b_s1></span></TD>
					</TR>
					<TR>
					<TD colspan=2><IMG SRC="images/spacer.gif" WIDTH=1 HEIGHT=10></TD>
					</TR>
					</TABLE>
				</TD>
				</TR>
				</TABLE>

			</TD>
			</TR>
			</TABLE>

		</TD>
		</TR>
	</TABLE>
	<input type="hidden" name="net_kind" <?=value($net_kind)?>>
	<input type="hidden" name="edit" value="1">
</form>
<!--▲登録情報の変更フォーム▲-->

	</TD>
	</TR>
	<TR>
	<TD><IMG SRC="images/footer_bar.gif" WIDTH=720 HEIGHT=2></TD>
	</TR>
	<TR>
	<TD align=right><IMG SRC="images/copyright.gif" WIDTH=349 HEIGHT=22 ALT="copyright"></TD>
	</TR>
	<TR>
	<TD align=right>
<SPAN class="copyright">このホームページに使われている画像・写真・記事等は、全て&nbsp;<a href="http://www.xxxxxxx.co.jp/" target="_blank">株式会社○○○○○○</a>&nbsp;に帰属します。<BR>
無断で複製・再利用することは法律により禁じられています。<BR><BR></SPAN>
	</TD>
	</TR>
	</TABLE>

</TD>
<TD background="images/bg_gray.gif"><IMG SRC="images/spacer.gif" WIDTH=40 HEIGHT=1></TD>
</TR>
</table>

</BODY>
</HTML>