<?
/******************************************************
' System :お酒と買物のアンケートモニター事務局用ページ
' Content:ログイン画面処理
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
$inc = "$top/inc";
include("$inc/header.php");

?>
<? staff_header('スタッフログイン', 'onload="onLoad_body()"') ?>

<script type="text/javascript">
<!--
function onLoad_body() {
  document.form1.login_id.focus();
}
function onSubmit_form1(form) {
  if (form.login_id.value == "") {
    alert("ログインＩＤを入力してください。");
    form.login_id.focus();
    return false;
  }
  if (form.password.value == "") {
    alert("パスワードを入力してください。");
    form.password.focus();
    return false;
  }
  return true;
}
//-->
</script>

<!--▼ログインフォーム▼-->
<b>スタッフログイン</b><br><br>

<form method="post" name="form1" action="login.php" onSubmit="return onSubmit_form1(this)">
	<TABLE WIDTH=440 BORDER=0 CELLPADDING=1 CELLSPACING=4 bgcolor=#DDDDDD>
		<TR>
		<TD bgcolor=#FFFFFF>

			<TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=1 bgcolor=#44aa88>
			<TR>
			<TD>

				<TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=0 bgcolor=#FFFFFF>
				<TR>
				<TD align="center" valign="center">

					<TABLE WIDTH=383 BORDER=0 CELLPADDING=0 CELLSPACING=7>
					<TR>
					<TD colspan=2><IMG SRC="images/spacer.gif" WIDTH=1 HEIGHT=20></TD>
					</TR>
					<TR>
					<TD><IMG SRC="images/staff00_05.gif" WIDTH=102 HEIGHT=22 ALT="メールアドレス"></TD>
					<TD><INPUT size=40 name="login_id" <?=value($mail_addr)?>></TD>
					</TR>
					<TR>
					<TD><IMG SRC="images/staff00_08.gif" WIDTH=102 HEIGHT=22 ALT="パスワード"></TD>
					<TD><INPUT size=30 name="password" type="password"></TD>
					</TR>
					<TR>
					<TD></TD>
					<TD align="left"><span onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('b_ssl0','','images/b_ssl0_over.gif',1)"><input type="image" SRC="images/b_ssl0.gif" WIDTH=130 HEIGHT=36 BORDER=0 ALT="非ＳＳＬでログイン" name=b_ssl0 onclick="document.form1.action='<?=http_url('login.php')?>'"></span><span onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('b_ssl1','','images/b_ssl1_over.gif',1)"><input type="image" SRC="images/b_ssl1.gif" WIDTH=130 HEIGHT=36 BORDER=0 ALT="ＳＳＬでログイン" name=b_ssl1 onclick="document.form1.action='<?=https_url('login.php')?>'"></span></TD>
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
</form>
<!--▲ログインフォーム▲-->

<? staff_footer() ?>
