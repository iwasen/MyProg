<?
$top = './..';
$inc = "$top/../inc";
include("$inc/select.php");
$inc = "$top/inc";
$img = "$top/image";
include("$inc/mn_regist.php");
include("$inc/mn_partner.php");
include("$inc/mn_mypage.php");
include("$inc/mn_header2.php");

// セッション処理
if (!isset($_SESSION['ss_partner'])) {
	$_SESSION['ss_partner'] = new m_partner;
	$partner = &$_SESSION['ss_partner'];
	$partner->read_db($_SESSION['ss_monitor_id']);
} else
	$partner = &$_SESSION['ss_partner'];

$reg = &$_SESSION['ss_regist'];
$partner->jitaku_zip1 = $reg->jitaku_zip1;
$partner->jitaku_zip2 = $reg->jitaku_zip2;
$partner->jitaku_area = $reg->jitaku_area;
?>
<? monitor_menu(/*'onload="onload_body()"'*/) ?>
<? /* ?>
<script type="text/javascript">
<!--
function onchange_shokugyou() {
	var f = document.form1;
	switch (f.shokugyou_cd.selectedIndex) {
	case 5:
	case 9:
	case 10:
	case 11:
		f.gyoushu_cd.selectedIndex = 12;
		f.shokushu_cd.selectedIndex = 9;
	}
	kinmu_control();
}
function onload_body() {
	kinmu_control();
}
function kinmu_control() {
	var f = document.form1;
	switch (f.shokugyou_cd.selectedIndex) {
	case 5:
	case 9:
	case 10:
	case 11:
		set_visibility("kinmu_name", false);
		set_visibility("kinmu_zip", false);
		set_visibility("kinmu_area", false);
		break;
	default:
		set_visibility("kinmu_name", true);
		set_visibility("kinmu_zip", true);
		set_visibility("kinmu_area", true);
		break;
	}
}
function set_visibility(id, visible) {
	if (visible) {
		if (document.all)
			document.all(id).visibility  = "visible";
		if (document.getElementById)
			document.getElementById(id).style.visibility = "visible";
	} else {
		if (document.all)
			document.all(id).visibility  = "hidden";
		if (document.getElementById)
			document.getElementById(id).style.visibility = "hidden";
	}
}
//-->
</script>
<? */ ?>
<form name="form1" method="post" action="m_update_p_check.php">
	<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
		<TBODY>
			<TR bgColor=#85A9DE>
				<TD><IMG height=29 src="<?=$img?>/spacer.gif" width=1 align=absMiddle>
					<SPAN class=mysttl_menu>■　プロフィール</SPAN>
				</TD>
			</TR>
			<TR>
				<TD><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD></TR>
			<TR>
				<TD bgColor=#88b7d8><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD>
			</TR>
		</TBODY>
	</TABLE>
<br>
<table width="496" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="15">　</td>
		<td colspan="3"><table width="481" border="0" cellspacing="0" cellpadding="20">
				<tr>
					<td>
						<table border="0" cellpadding="3" cellspacing="2" width="100%">
							<tr>
								<td bgcolor="#525294"><font color="#ffcc00">■　</font><font color="white">リサーチパートナー情報の更新</font></td>
							</tr>
							<tr>
								<td><font size="2"><b><br>
									</b></font><font size="2"><br>
									「</font><font size="2" color="#ff0009">※</font><font size="2">」 のある項目は必須項目となりますので、必ずご記入ください。<br>
									</font><table border="0" cellpadding="3" cellspacing="2" width="100%">
										<tr>
											<td colspan="3"><font size="2"><hr></font></td>
										</tr>
										<tr>
											<td width="114" bgcolor="#dedede"><font size="2"><font size="2" color="#ff0009">※</font><font size="2">郵便番号（自宅）</font></td>
											<td colspan="2"><font size="2"><input type="text" name="jitaku_zip1" size="3" <?=value($partner->jitaku_zip1)?>> - <input type="text" name="jitaku_zip2" size="4" <?=value($partner->jitaku_zip2)?>> （半角数字）</font></td>
										</tr>
										<tr>
											<td width="114" bgcolor="#dedede"><font size="2"><font size="2" color="#ff0009">※</font><font size="2">住所</font></td>
											<td colspan="2"><font size="2"><select name="jitaku_area" size="1"><? select_area('お選び下さい　　　　　', $partner->jitaku_area) ?></select></font></td>
										</tr>
										<tr>
											<td width="114" bgcolor="#dedede"><font size="2"><font size="2" color="#ff0009">※</font><font size="2">住所 <br>（市区）</font></td>
											<td colspan="2"><font size="2"><input type="text" name="jitaku_addr1" size="39" <?=value($partner->jitaku_addr1)?>></font></td>
										</tr>
										<tr>
											<td width="114" bgcolor="#dedede"><font size="2"><font size="2" color="#ff0009">※</font><font size="2">住所 <br>（町村以下）</font></td>
											<td colspan="2"><font size="2"><input type="text" name="jitaku_addr2" size="39" <?=value($partner->jitaku_addr2)?>></font></td>
										</tr>
										<tr>
											<td width="114" bgcolor="#dedede"><font size="2"><font size="2" color="#ff0009">　</font><font size="2">住所<br>（アパート・ビル名）</font></td>
											<td colspan="2"><font size="2"><input type="text" name="jitaku_addr3" size="39" <?=value($partner->jitaku_addr3)?>></font></td>
										</tr>
										<tr>
											<td width="114" bgcolor="#dedede"><font size="2"><font size="2" color="#ff0009">※</font><font size="2">電話番号</font></td>
											<td colspan="2"><font size="2"><input type="text" name="jitaku_tel1" size="5" maxlength="5" <?=value($partner->jitaku_tel1)?>> - <input type="text" name="jitaku_tel2" size="5" maxlength="5" <?=value($partner->jitaku_tel2)?>> - <input type="text" name="jitaku_tel3" size="5" maxlength="4" <?=value($partner->jitaku_tel3)?>></font></td>
										</tr>
										<tr>
											<td width="114" bgcolor="#dedede"><font size="2"><font size="2" color="#ff0009">　</font><font size="2">FAX<br>（お持ちの場合）</font></td>
											<td colspan="2"><font size="2"><input type="text" name="jitaku_fax1" size="5" maxlength="5" <?=value($partner->jitaku_fax1)?>> - <input type="text" name="jitaku_fax2" size="5" maxlength="5" <?=value($partner->jitaku_fax2)?>> - <input type="text" name="jitaku_fax3" size="5" maxlength="4" <?=value($partner->jitaku_fax3)?>></font></td>
										</tr>
<? /* ?>
										<tr>
											<td colspan="3"><font size="2"><hr></font></td></tr>
										<tr>
											<td width="114" bgcolor="#dedede"><font size="2"><font size="2" color="#ff0009">※</font><font size="2">職業</font></td>
											<td colspan="2"><font size="2"><select name="shokugyou_cd" size="1" onchange="onchange_shokugyou()"><? select_shokugyou('お選び下さい　　　　　', $partner->shokugyou_cd) ?></select></font></td>
										</tr>
										<tr>
											<td width="114" bgcolor="#dedede"><font size="2"><font size="2" color="#ff0009">※</font><font size="2">業種</font></td>
											<td colspan="2"><font size="2"><select name="gyoushu_cd" size="1"><? select_gyoushu('お選び下さい　　　　　', $partner->gyoushu_cd, 1) ?></select></font></td>
										</tr>
										<tr>
											<td width="114" bgcolor="#dedede"><font size="2"><font size="2" color="#ff0009">※</font><font size="2">職種</font></td>
											<td colspan="2"><font size="2"><select name="shokushu_cd" size="1"><? select_shokushu('お選び下さい　　　　　', $partner->shokushu_cd) ?></select></font></td>
										</tr>
										<tr>
											<td width="114" bgcolor="#dedede"><font size="2"><font id="kinmu_name" size="2" color="#ff0009">※</font><font size="2">勤務先名</font></td>
											<td colspan="2"><font size="2"><input type="text" name="kinmu_name" size="39" <?=value($partner->kinmu_name)?>></font></td>
										</tr>
										<tr>
											<td width="114" bgcolor="#dedede"><font size="2"><font id="kinmu_zip" size="2" color="#ff0009">※</font><font size="2">郵便番号（勤務先）</font></td>
											<td colspan="2"><font size="2"><input type="text" name="kinmu_zip1" size="3" <?=value($partner->kinmu_zip1)?>> - <input type="text" name="kinmu_zip2" size="4" <?=value($partner->kinmu_zip2)?>> （半角数字）</font></td>
										</tr>
										<tr>
											<td width="114" bgcolor="#dedede"><font size="2"><font id="kinmu_area" size="2" color="#ff0009">※</font><font size="2">通勤先住所<br>（都道府県）</font></td>
											<td colspan="2"><font size="2"><select name="kinmu_area" size="1"><? select_area('お選び下さい　　　　　', $partner->kinmu_area) ?></select></font></td>
										</tr>
										<tr>
											<td width="114" bgcolor="#dedede"><font size="2"><font size="2" color="#ff0009">※</font><font size="2">通勤先住所<br>（市区）</font></td>
											<td colspan="2"><font size="2"><input type="text" name="kinmu_addr1" size="39" <?=value($partner->kinmu_addr1)?>></font></td>
										</tr>
										<tr>
											<td width="114" bgcolor="#dedede"><font size="2"><font size="2" color="#ff0009">※</font><font size="2">通勤先住所<br>（町村以下）</font></td>
											<td colspan="2"><font size="2"><input type="text" name="kinmu_addr2" size="39" <?=value($partner->kinmu_addr2)?>></font></td>
										</tr>
										<tr>
											<td width="114" bgcolor="#dedede"><font size="2"><font size="2" color="#ff0009">　</font><font size="2">通勤先住所<br>（アパート・ビル名）</font></td>
											<td colspan="2"><font size="2"><input type="text" name="kinmu_addr3" size="39" <?=value($partner->kinmu_addr3)?>></font></td>
										</tr>
										<tr>
											<td width="114" bgcolor="#dedede"><font size="2"><font size="2" color="#ff0009">※</font><font size="2">通勤先電話番号</font></td>
											<td colspan="2"><font size="2"><input type="text" name="kinmu_tel1" size="5" maxlength="5" <?=value($partner->kinmu_tel1)?>> - <input type="text" name="kinmu_tel2" size="5" maxlength="5" <?=value($partner->kinmu_tel2)?>> - <input type="text" name="kinmu_tel3" size="5" maxlength="4" <?=value($partner->kinmu_tel3)?>></font></td>
										</tr>
										<tr>
											<td width="114" bgcolor="#dedede"><font size="2"><font size="2" color="#ff0009">　</font><font size="2">通勤先FAX<br>（お持ちの場合）</font></td>
											<td colspan="2"><font size="2"><input type="text" name="kinmu_fax1" size="5" <?=value($partner->kinmu_fax1)?>> - <input type="text" name="kinmu_fax2" size="5" <?=value($partner->kinmu_fax2)?>> - <input type="text" name="kinmu_fax3" size="5" <?=value($partner->kinmu_fax3)?>></font></td>
										</tr>
										<tr>
											<td colspan="3"><font size="2"><hr></font></td></tr>
										<tr>
											<td rowspan="2" bgcolor="#dedede" width="114"><font size="2">家族構成<br>（同居の配偶者）</font></td>
											<td width="124"><font size="2">1.　配偶者の有無</font></td>
											<td width="146"><font size="2"><input type="radio" name="family_rel_cd[]" value="1" <?=checked($partner->family[1]->family_rel_cd == '1')?>> ある <input type="radio" name="family_rel_cd[]" value="" <?=checked($partner->family[1]->family_rel_cd != '1')?>> ない</font></td>
										</tr>
										<tr>
											<td width="124"><font size="2">　　年齢</font></td>
											<td width="146"><font size="2"><input type="text" name="family_age[]" size="3" <?=value($partner->family[1]->age)?>> 歳</font></td>
										</tr>
<?
for ($i = 2; $i <= 5; $i++) {
	$family = &$partner->family[$i];
?>
										<tr>
											<td rowspan="2" bgcolor="#dedede" width="114"><font size="2">家族構成<br>（同居家族）</font></td>
											<td width="124"><font size="2"><?=$i?>.　続柄</font></td>
											<td width="146"><font size="2"><select name="family_rel_cd[]" size="1"><? select_family_rel('お選び下さい　　　　　', $family->family_rel_cd) ?></select></font></td>
										</tr>
										<tr>
											<td width="124"><font size="2">　　年齢</font></td>
											<td width="146"><font size="2"><input type="text" name="family_age[]" size="3" <?=value($family->age)?>> 歳</font></td>
										</tr>
<?
}
?>
<? */ ?>
									</table></td>
							</tr>
							<tr>
								<td>
									<center>
										<hr>
										<font size="2">
											<input type="submit" value="　確　認　">
											<input type="button" value="　戻　る　" onclick="location.href='m_confirm_l.php'">
											<input type="reset" value="　元に戻す　">
										</font></center>
								</td>
							</tr>
						</table></td>
				</tr>
			</table></td>
	</tr>
</table>
</form>
</body>
</html>
