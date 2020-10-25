<?
include('../inc/common.php');
include('../inc/database.php');

//===  <option>タグ出力 ===
function option($val, $sel) {
	if ($val == $sel)
		$selected = ' selected';

	return "<option$selected>$val</option>\n";
}

//=== 市区町村選択肢出力 ===
function WriteShikuchouson() {
	global $todoufuken, $shikuchouson;

	Writeln("<option value=''" . Selected(shikuchouson, '') . ">　─お選びください─　</option>");
	if ($todoufuken != '') {
		$sql = "SELECT ZC_shikuchouson,MIN(ZC_seq_no) AS seq_no FROM T_ZIPCODE WHERE ZC_todoufuken='$todoufuken' GROUP BY ZC_shikuchouson ORDER BY seq_no";
		$ds = CreateDynaset($sql, ORADYN_READONLY);
		while (!$ds->EOF()) {
			Writeln("<option" . Selected($shikuchouson, $ds->GetValue('ZC_shikuchouson')) . '>' . $ds->GetValue('ZC_shikuchouson') . "</option>");
			$ds->MoveNext();
		}
	}
}

//=== 町名表示 ===
function WriteChoumei() {
	global $todoufuken, $shikuchouson;

	$sql = "SELECT ZC_zip_code,ZC_chouiki FROM T_ZIPCODE WHERE ZC_todoufuken='$todoufuken' AND ZC_shikuchouson='$shikuchouson' ORDER BY ZC_seq_no";
	$ds = CreateDynaset($sql, ORADYN_READONLY);
	while (!$ds->EOF()) {
		$zip_code = $ds->GetValue('ZC_zip_code');
		Writeln("<option value='$zip_code'>" .substr($zip_code, 0, 3) . '-' . substr($zip_code, 3, 4) . '&nbsp;' . $ds->GetValue('ZC_chouiki') . "</option>");
		$ds->Movenext();
	}
}
?>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
		<title>郵便番号かんたんサーチ</title>
<SCRIPT LANGUAGE=javascript>
<!--
function OnChange_todoufuken() {
	document.form1.sel_mode.value = "1";
	document.form1.submit();
}
function OnChange_shikuchouson() {
	document.form1.sel_mode.value = "2";
	document.form1.submit();
}
function OnChange_choumei() {
	var index, zip;
	index = document.form1.choumei.selectedIndex;
	if (index >= 0) {
		zip = document.form1.choumei.options[index].value;
		if (document.form1.pfc.value == "1") {
			opener.document.form1.zip_kyojuu1.value = zip.substr(0, 3);
			opener.document.form1.zip_kyojuu2.value = zip.substr(3, 4);
			window.close();
		} else if (document.form1.pfc.value == "2") {
			opener.document.form1.zip_kinmu1.value = zip.substr(0, 3);
			opener.document.form1.zip_kinmu2.value = zip.substr(3, 4);
			window.close();
		}
	}
}
//-->
</SCRIPT>
	</head>

	<body bgcolor="white" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
		<table cellspacing="0" cellpadding="0" border="0" height="58">
			<tr height="48">
				<td bgcolor="white" align="left" valign="top" height="48"><img height="48" width="150" src="../img/logo1.gif" alt="はいメール・net"><img height="48" width="482" src="../img/logo2.gif" alt="interactive communication network"></td>
			</tr>
			<tr height="16">
				<td height="16" align="left" valign="top"><img height="3" width="642" src="../img/logo_line.gif"></td>
			</tr>
		</table>
		<form method="post" name="form1">
		<input type="hidden" name="pfc" value="<?=$pfc?>">
		<br>
		<table border="0" cellspacing="0" mm_noconvert="TRUE" cellpadding="0">
			<tr height="36">
				<td width="50" height="36" align="right" valign="top"><img height="16" width="50" src="../img/space.gif"></td>
				<td width="550" height="36" valign="top">
					<div align="left">
						<font color="#2f2180"><b><img height="26" width="435" src="img/10-title.gif" alt="郵便番号かんたんサーチ"></b></font></div>
				</td>
			</tr>
			<tr height="36">
				<td width="50" height="36" align="right" valign="top">　</td>
				<td width="550" height="36"><font size="2">あなたの調べたい地域を入力すると、簡単に郵便番号が検索できます。<br>
					都道府県を選んだ後に、市区町村を選んで下さい。検索結果に調べた郵便番号が表示されます。</font></td>
			</tr>
			<tr>
				<td width="50" align="right" valign="top">　</td>
				<td width="550" align="left" valign="top"><br>
					<table border="0" cellpadding="0" cellspacing="2" width="376">
						<tr>
							<td width="40"><img height="16" width="40" src="../img/space.gif"></td>
							<td><font size="2" color="#b2cce4">■</font><font size="2">都道府県</font></td>
							<td><select name="todoufuken" size="1" onChange="OnChange_todoufuken()">
									<option value=""<?=Selected($todoufuken, '')?>>　─お選びください─　</option>
									<?=option("北海道", $todoufuken)?>
									<?=option("青森県", $todoufuken)?>
									<?=option("岩手県", $todoufuken)?>
									<?=option("宮城県", $todoufuken)?>
									<?=option("秋田県", $todoufuken)?>
									<?=option("山形県", $todoufuken)?>
									<?=option("福島県", $todoufuken)?>
									<?=option("茨城県", $todoufuken)?>
									<?=option("栃木県", $todoufuken)?>
									<?=option("群馬県", $todoufuken)?>
									<?=option("埼玉県", $todoufuken)?>
									<?=option("千葉県", $todoufuken)?>
									<?=option("東京都", $todoufuken)?>
									<?=option("神奈川県", $todoufuken)?>
									<?=option("新潟県", $todoufuken)?>
									<?=option("富山県", $todoufuken)?>
									<?=option("石川県", $todoufuken)?>
									<?=option("福井県", $todoufuken)?>
									<?=option("山梨県", $todoufuken)?>
									<?=option("長野県", $todoufuken)?>
									<?=option("岐阜県", $todoufuken)?>
									<?=option("静岡県", $todoufuken)?>
									<?=option("愛知県", $todoufuken)?>
									<?=option("三重県", $todoufuken)?>
									<?=option("滋賀県", $todoufuken)?>
									<?=option("京都府", $todoufuken)?>
									<?=option("大阪府", $todoufuken)?>
									<?=option("兵庫県", $todoufuken)?>
									<?=option("奈良県", $todoufuken)?>
									<?=option("和歌山県", $todoufuken)?>
									<?=option("鳥取県", $todoufuken)?>
									<?=option("島根県", $todoufuken)?>
									<?=option("岡山県", $todoufuken)?>
									<?=option("広島県", $todoufuken)?>
									<?=option("山口県", $todoufuken)?>
									<?=option("徳島県", $todoufuken)?>
									<?=option("香川県", $todoufuken)?>
									<?=option("愛媛県", $todoufuken)?>
									<?=option("高知県", $todoufuken)?>
									<?=option("福岡県", $todoufuken)?>
									<?=option("佐賀県", $todoufuken)?>
									<?=option("長崎県", $todoufuken)?>
									<?=option("熊本県", $todoufuken)?>
									<?=option("大分県", $todoufuken)?>
									<?=option("宮崎県", $todoufuken)?>
									<?=option("鹿児島県", $todoufuken)?>
									<?=option("沖縄県", $todoufuken)?>
								</select></td>
						</tr>
						<tr>
							<td width="40"></td>
							<td><font size="2" color="#b2cce4">■</font><font size="2">市区町村</font></td>
							<td><select name="shikuchouson" size="1" onChange="OnChange_shikuchouson()">
<?
WriteShikuchouson();
?>
								</select></td>
						</tr>
					</table><br>
				</td>
			</tr>
			<tr height="36">
				<td width="50" height="36">　 </td>
				<td width="550" height="36">
					<hr>
<?
if ($sel_mode == '2') {
?>
					<table border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td align="left" valign="top"><img height="16" width="40" src="../img/space.gif"></td>
							<td width="441" align="left" valign="top"><b>検索できました。<br>
								</b><br>
								<select name="choumei" size=8 onClick="OnChange_choumei()">
<?
	WriteChoumei();
?>
								</select>
<?
	if ($pfc != '') {
?>
								<br><br>
								</a><font size="2">郵便番号をクリックしてください。<br>
								（郵便番号をクリックすると、登録画面に自動的に表示されます。）</font>
<?
	}
?>
							</td>
						</tr>
					</table>
<?
}
?>
				</td>
			</tr>
		</table>
		<input type="hidden" name="sel_mode">
		</form>
	</body>

</html>
