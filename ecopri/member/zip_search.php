<?
/******************************************************
' System :Eco-footprint ²ñ°÷¥Ú¡¼¥¸
' Content:Í¹ÊØÈÖ¹æ¸¡º÷
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");

//===  <option>¥¿¥°½ÐÎÏ ===
function option($val, $sel) {
	if ($val == $sel)
		$selected = ' selected';

	return "<option$selected>$val</option>\n";
}

//=== »Ô¶èÄ®Â¼ÁªÂò»è½ÐÎÏ ===
function write_shikuchouson($todoufuken, $shikuchouson) {
	echo '<option ', value_selected('', $shikuchouson), ">¡¡¨¡¤ªÁª¤Ó¤¯¤À¤µ¤¤¨¡¡¡</option>\n";
	if ($todoufuken != '') {
		$sql = "SELECT zc_shikuchouson,MIN(zc_seq_no) AS seq_no FROM m_zip WHERE zc_todoufuken='$todoufuken' GROUP BY zc_shikuchouson ORDER BY seq_no";
		$result = db_exec($sql);
		$nrow = pg_numrows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);
			echo option($fetch->zc_shikuchouson, $shikuchouson);
		}
	}
}

//=== Ä®Ì¾É½¼¨ ===
function write_choumei($todoufuken, $shikuchouson) {
	$sql = "SELECT zc_zip_cd,zc_chouiki FROM m_zip WHERE zc_todoufuken='$todoufuken' AND zc_shikuchouson='$shikuchouson' ORDER BY zc_seq_no";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
	  $zip_cd = $fetch->zc_zip_cd;
	  echo "<option value='$zip_cd'>",substr($zip_cd, 0, 3), '-', substr($zip_cd, 3, 4), "&nbsp;$fetch->zc_chouiki</option>\n";
	}
}
?>
<html lang="ja">
	<head>
		<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
		<meta http-equiv="Pragma" content="no-cache">
		<title>Í¹ÊØÈÖ¹æ¤«¤ó¤¿¤ó¥µ¡¼¥Á</title>
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
	if (opener && !opener.closed) {
		index = document.form1.choumei.selectedIndex;
		if (index >= 0) {
			zip = document.form1.choumei.options[index].value;
			opener.document.form1.zip1.value = zip.substr(0, 3);
			opener.document.form1.zip2.value = zip.substr(3, 4);
			window.close();
		}
	}
}
//-->
</SCRIPT>
	</head>

	<body bgcolor="white" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
		<form method="post" name="form1">
		<br>
		<table border="0" cellspacing="0" mm_noconvert="TRUE" cellpadding="0">
			<tr height="36">
				<td width="50" height="36" align="right" valign="top"></td>
				<td width="550" height="36" valign="top">
					<div align="left">
						<font color="#2f2180"><b>¢£Í¹ÊØÈÖ¹æ¸¡º÷</b></font></div>
				</td>
			</tr>
			<tr height="36">
				<td width="50" height="36" align="right" valign="top"></td>
				<td width="550" height="36"><font size="2"><font color="#ff3300"><b>
					£±¡¥ÅÔÆ»ÉÜ¸©¤òÁª¤ó¤À¸å¤Ë¡¢»Ô¶èÄ®Â¼¤òÁª¤ó¤Ç²¼¤µ¤¤¡£</b></font><br>
					&nbsp;&nbsp;&nbsp;&nbsp;¡Ê¸¡º÷·ë²Ì¤ËÄ´¤Ù¤¿Í¹ÊØÈÖ¹æ¤¬É½¼¨¤µ¤ì¤Þ¤¹¡£¡Ë</font></td>
			</tr>
			<tr>
				<td width="50" align="right" valign="top">¡¡</td>
				<td width="550" align="left" valign="top"><br>
					<table border="0" cellpadding="0" cellspacing="2" width="376">
						<tr>
							<td width="40"></td>
							<td><font size="2" color="#b2cce4">¢£</font><font size="2">ÅÔÆ»ÉÜ¸©</font></td>
							<td><select name="todoufuken" size="1" onChange="OnChange_todoufuken()">
									<option <?=value_selected('', $todoufuken)?>>¡¡¨¡¤ªÁª¤Ó¤¯¤À¤µ¤¤¨¡¡¡</option>
									<?=option("ËÌ³¤Æ»", $todoufuken)?>
									<?=option("ÀÄ¿¹¸©", $todoufuken)?>
									<?=option("´ä¼ê¸©", $todoufuken)?>
									<?=option("µÜ¾ë¸©", $todoufuken)?>
									<?=option("½©ÅÄ¸©", $todoufuken)?>
									<?=option("»³·Á¸©", $todoufuken)?>
									<?=option("Ê¡Åç¸©", $todoufuken)?>
									<?=option("°ñ¾ë¸©", $todoufuken)?>
									<?=option("ÆÊÌÚ¸©", $todoufuken)?>
									<?=option("·²ÇÏ¸©", $todoufuken)?>
									<?=option("ºë¶Ì¸©", $todoufuken)?>
									<?=option("ÀéÍÕ¸©", $todoufuken)?>
									<?=option("ÅìµþÅÔ", $todoufuken)?>
									<?=option("¿ÀÆàÀî¸©", $todoufuken)?>
									<?=option("¿·³ã¸©", $todoufuken)?>
									<?=option("ÉÙ»³¸©", $todoufuken)?>
									<?=option("ÀÐÀî¸©", $todoufuken)?>
									<?=option("Ê¡°æ¸©", $todoufuken)?>
									<?=option("»³Íü¸©", $todoufuken)?>
									<?=option("Ä¹Ìî¸©", $todoufuken)?>
									<?=option("´ôÉì¸©", $todoufuken)?>
									<?=option("ÀÅ²¬¸©", $todoufuken)?>
									<?=option("°¦ÃÎ¸©", $todoufuken)?>
									<?=option("»°½Å¸©", $todoufuken)?>
									<?=option("¼¢²ì¸©", $todoufuken)?>
									<?=option("µþÅÔÉÜ", $todoufuken)?>
									<?=option("ÂçºåÉÜ", $todoufuken)?>
									<?=option("Ê¼¸Ë¸©", $todoufuken)?>
									<?=option("ÆàÎÉ¸©", $todoufuken)?>
									<?=option("ÏÂ²Î»³¸©", $todoufuken)?>
									<?=option("Ä»¼è¸©", $todoufuken)?>
									<?=option("Åçº¬¸©", $todoufuken)?>
									<?=option("²¬»³¸©", $todoufuken)?>
									<?=option("¹­Åç¸©", $todoufuken)?>
									<?=option("»³¸ý¸©", $todoufuken)?>
									<?=option("ÆÁÅç¸©", $todoufuken)?>
									<?=option("¹áÀî¸©", $todoufuken)?>
									<?=option("°¦É²¸©", $todoufuken)?>
									<?=option("¹âÃÎ¸©", $todoufuken)?>
									<?=option("Ê¡²¬¸©", $todoufuken)?>
									<?=option("º´²ì¸©", $todoufuken)?>
									<?=option("Ä¹ºê¸©", $todoufuken)?>
									<?=option("·§ËÜ¸©", $todoufuken)?>
									<?=option("ÂçÊ¬¸©", $todoufuken)?>
									<?=option("µÜºê¸©", $todoufuken)?>
									<?=option("¼¯»ùÅç¸©", $todoufuken)?>
									<?=option("²­Æì¸©", $todoufuken)?>
								</select></td>
						</tr>
						<tr>
							<td width="40"></td>
							<td><font size="2" color="#b2cce4">¢£</font><font size="2">»Ô¶èÄ®Â¼</font></td>
							<td><select name="shikuchouson" size="1" onChange="OnChange_shikuchouson()">
<?write_shikuchouson($todoufuken, $shikuchouson)?>
								</select></td>
						</tr>
					</table><br>
				</td>
			</tr>
			<tr height="36">
				<td width="50" height="36">¡¡ </td>
				<td width="550" height="36">
					<hr>
<?if ($sel_mode == '2') {?>
					<table border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td align="left" valign="top" width="40"></td>
							<td width="441" align="left" valign="top"><b>¸¡º÷·ë²Ì<br>
								</b><br>
								<select name="choumei" size=8 onChange="OnChange_choumei()">
<?write_choumei($todoufuken, $shikuchouson)?>
								</select>
								<br><br>
								</a><font size="2"><font color="#ff3300"><b>£²¡¥Í¹ÊØÈÖ¹æ¤ò¥¯¥ê¥Ã¥¯¤·¤Æ¤¯¤À¤µ¤¤¡£</b></font><br>
								¡ÊÍ¹ÊØÈÖ¹æ¤ò¥¯¥ê¥Ã¥¯¤¹¤ë¤È¡¢ÅÐÏ¿²èÌÌ¤Ë¼«Æ°Åª¤ËÉ½¼¨¤µ¤ì¤Þ¤¹¡£¡Ë</font>
							</td>
							<td><img src="img/nyuukai/zou_img/zip.gif" border="0"></td>
						</tr>
					</table>
<?}?>
				</td>
			</tr>
		</table>
		<input type="hidden" name="sel_mode">
		</form>
	</body>

</html>
