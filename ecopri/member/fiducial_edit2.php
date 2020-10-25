<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:会員情報入力
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/select.php");
$inc = "$top/inc";
include("$inc/fiducial1.php");
include("$inc/mypage.php");

// 表示色セット
function set_color($data,$flag) {
	if ($flag == 1)
		return "<font color=\"blue\">" . number_format($data) . "</font>";
	else
		return "<font color=\"red\">" . number_format($data) . "</font>";
}

// ページ確認
$date = add_date(date("Y-m-d"), 0, -2, 0);
$sql = "SELECT mb_regist_date FROM t_member WHERE mb_seq_no=" . $_SESSION['ss_seq_no'] . " AND mb_regist_date >=" . sql_date($date);
if (db_fetch1($sql))
	redirect('fiducial_change1.php');

// セッション処理
if (!isset($_SESSION['ss_fiducial1']))
	$_SESSION['ss_fiducial1'] = new fiducial1;
$reg = &$_SESSION['ss_fiducial1'];

// フォームからのデータ取得
$reg->edit_no = $edit_no;

$reg->set_start_ym();
$year = $reg->start_y;
$month = $reg->start_m;

$reg->read_db1();

?>
<html>
<head>
<title>☆★☆xxxゾウさんClub☆★☆</title>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
</head>

<body bgcolor="#FF0000" leftmargin="8" topmargin="8">
<form name="form1" method="post" action="fiducial_edit_check2.php">

<div align="center">
<table width="714" height="90%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
	<tr>
		<td width="35" align="left" valign="top" bgcolor="#FFFFFF"><img src="img/kado_1.gif" width="35" height="35"></td>
		<td align="center" valign="top">
		<br>

			<table border="0" cellspacing="0" cellpadding="0">
				<tr align="center">
					<td><img src="img/fiducial/titleA_03.gif" width=431 height=69 border=0 alt="過去の数値"></td>
				</tr>
			</table>
		</td>
		<td width="35" align="right" valign="top">
		<img src="img/kado_2.gif" width="35" height="35"></td>
	</tr>

	<tr>
		<td></td>
		<td align="center" valign="top">
		<hr size="1">
		</td>
		<br>
	</tr>
	<tr>
		<td></td>
	</tr>
	<tr>
		<td></td>
		<td>
			<table border="1" width="650" bordercolor="#33ff00" cellpadding="0" cellspacing="1">
				<tr align="center" bgcolor="#eeffee">
					<td width=110><font size=2><b>マンスリーデータ<br></b></font><font size=1>月単位で<br>発生するデータ</font></td>
					<td colspan="2" width=180 valign="center"><img src="img/character/denki.gif" border="0" width="62" height="72"></td>
					<td colspan="2" width=180 valign="center"><img src="img/character/gas.gif" border="0" width="62" height="72"></td>
					<td colspan="2" width=180 valign="center"><img src="img/character/suidou.gif" border="0" width="62" height="72"></td>
				</tr>
<?
for ($i = 0; $i < 12; $i++) {
	$eng = &$reg->eng[$i];
	if ($i != $edit_no) {
?>
				<tr align="right">
					<td width=110 align="center"><?=$eng->bd_y."/".sprintf("%02d", $eng->bd_m)?></td>
					<td width=90><font size=2><?=set_color($eng->el_use,$eng->el_use_flag)?>&nbsp;kwh&nbsp;</font></td>
					<td width=90><font size=2><?=set_color($eng->el_sum,$eng->el_sum_flag)?>&nbsp;円&nbsp;</td>
					<td width=90><font size=2><?=set_color($eng->gs_use,$eng->gs_use_flag)?>&nbsp;m3&nbsp;</font></td>
					<td width=90><font size=2><?=set_color($eng->gs_sum,$eng->gs_sum_flag)?>&nbsp;円&nbsp;</font></td>
					<td width=90><font size=2><?=set_color($eng->wt_use,$eng->wt_use_flag)?>&nbsp;m3&nbsp;</font></td>
					<td width=90><font size=2><?=set_color($eng->wt_sum,$eng->wt_sum_flag)?>&nbsp;円&nbsp;</font></td>
				</tr>
<?
	} else {
?>
				<tr align="right">
					<td width=110 align="center"><?=$eng->bd_y."/".sprintf("%02d", $eng->bd_m)?></td>
					<td width=90><font size=2><input type="text" name="edit_el_use" size=7 maxlength=8 <?=value($eng->el_use!=''?$eng->el_use:0)?>>&nbsp;kwh&nbsp;</font></td>
					<td width=90><font size=2><input type="text" name="edit_el_sum" size=7 maxlength=8 <?=value($eng->el_sum!=''?$eng->el_sum:0)?>>&nbsp;円&nbsp;</td>
					<td width=90><font size=2><input type="text" name="edit_gs_use" size=7 maxlength=8 <?=value($eng->gs_use!=''?$eng->gs_use:0)?>>&nbsp;m3&nbsp;</font></td>
					<td width=90><font size=2><input type="text" name="edit_gs_sum" size=7 maxlength=8 <?=value($eng->gs_sum!=''?$eng->gs_sum:0)?>>&nbsp;円&nbsp;</font></td>
					<td width=90><font size=2><input type="text" name="edit_wt_use" size=7 maxlength=8 <?=value($eng->wt_use!=''?$eng->wt_use:0)?>>&nbsp;m3&nbsp;</font></td>
					<td width=90><font size=2><input type="text" name="edit_wt_sum" size=7 maxlength=8 <?=value($eng->wt_sum!=''?$eng->wt_sum:0)?>>&nbsp;円&nbsp;</font></td>
				</tr>
<?
	}
}
?>
			</table>
		</td>
	</tr>
	<tr><td><br></td></tr>
	<tr>
		<td></td>
		<td>
			<table border="1" width="650" bordercolor="#cc66ff" cellpadding="0" cellspacing="1">
				<tr align="center" bgcolor="#ffeeff">
					<td width=110><font size=2><b>デイリーデータ<br></b></font><font size=1>日単位で<br>発生する>データ</font></td>
					<td width=180 valign="center"><img src="img/character/gomi.gif" border="0" width="62" height="72"></td>
					<td colspan="2" width=180 valign="center"><img src="img/character/touyu.gif" border="0" width="62" height="72"></td>
					<td colspan="2" width=180 valign="center"><img src="img/character/gasoline.gif" border="0" width="62" height="72"></td>
				</tr>
<?
for ($i = 0; $i < 12; $i++) {
	$eng = &$reg->eng[$i];
	if ($i != $edit_no) {
?>
				<tr align="right">
					<td width=110 align="center"><?=$eng->bd_y."/".sprintf("%02d", $eng->bd_m)?></td>
					<td width=180><font size=2><font color="red"><?=set_color($eng->gm_use,$eng->gm_use_flag)?></font>&nbsp;kg&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font></td>
					<td width=90><font size=2><?=set_color($eng->ol_use,$eng->ol_use_flag)?>&nbsp;L&nbsp;</font></td>
					<td width=90><font size=2><?=set_color($eng->ol_sum,$eng->ol_sum_flag)?>&nbsp;円&nbsp;</font></td>
					<td width=90><font size=2><?=set_color($eng->gl_use,$eng->gl_use_flag)?>&nbsp;L&nbsp;</font></td>
					<td width=90><font size=2><?=set_color($eng->gl_sum,$eng->gl_sum_flag)?>&nbsp;円&nbsp;</font></td>
				</tr>
<?
	} else {
?>
				<tr align="right">
					<td width=110 align="center"><?=$eng->bd_y."/".sprintf("%02d", $eng->bd_m)?></td>
					<td width=180><font size=2><input type="text" name="edit_gm_use" size=7 maxlength=8 <?=value($eng->gm_use!=''?$eng->gm_use:0)?>>&nbsp;kg&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font></td>
					<td width=90><font size=2><input type="text" name="edit_ol_use" size=7 maxlength=8 <?=value($eng->ol_use!=''?$eng->ol_use:0)?>>&nbsp;L&nbsp;</font></td>
					<td width=90><font size=2><input type="text" name="edit_ol_sum" size=7 maxlength=8 <?=value($eng->ol_sum!=''?$eng->ol_sum:0)?>>&nbsp;円&nbsp;</font></td>
					<td width=90><font size=2><input type="text" name="edit_gl_use" size=7 maxlength=8 <?=value($eng->gl_use!=''?$eng->gl_use:0)?>>&nbsp;L&nbsp;</font></td>
					<td width=90><font size=2><input type="text" name="edit_gl_sum" size=7 maxlength=8 <?=value($eng->gl_sum!=''?$eng->gl_sum:0)?>>&nbsp;円&nbsp;</font></td>
				</tr>
<?
	}
}
?>
			</table>
			<br>
		</td>
	</tr>
	<tr>
		<td align="center" colspan=3><font size=2><b>・修正理由</b><font color="red">[必須]</font>&nbsp;<input type="text" name="reason" size=60 maxlength=100></td>
	</tr>
	<tr>
		<td align="center" colspan=3 height=45 valign="bottom">
			<input type="image" src="img/fiducial/soushin.gif" width=267 height=35 border=0 alt="修正データを送信する">
		</td>
	</tr>
	<tr>
		<td></td>
		<td><hr size="1"></td>
		<td></td>
	</tr>
	<tr>
		<td width="35" align="left" valign="bottom"><img src="img/kado_3.gif" width="35" height="35"></td>
		<td align="center"><img src="img/button/input/back4.gif" width=137 height=44 border=0 alt="変更しないで戻る" onclick="history.back()"></td>
		<td width="35" height="35" align="right" valign="bottom">
		<img src="img/kado_4.gif" width="35" height="35"></td>
	</tr>
</table>
</div>
</form>
</body>
