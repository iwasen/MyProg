<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:住居情報入力
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/select.php");
$inc = "$top/inc";
include("$inc/regist2.php");

// IMEだけJavaScript実行
function check_browser($func, $parameter='') {
	if ($func == 'onload') {
		if ( ereg ("MSIE", $_SERVER["HTTP_USER_AGENT"]))
			echo "onload='onload()'";
	} else {
		if ( ereg ("MSIE", $_SERVER["HTTP_USER_AGENT"]))
			echo "$func=\"show_hide('$parameter')\"";
	}
}

// 建築年次
function select_chikunen($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	$sql = "SELECT cn_chikunen_cd,cn_chikunen_text FROM m_chikunen ORDER BY cn_chikunen_cd";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		echo '<option ', value_selected($fetch->cn_chikunen_cd, $selected), '>', htmlspecialchars($fetch->cn_chikunen_text), '</option>', "\n";
	}
}
// 住居面積
function select_space($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	$sql = "SELECT sp_space_cd,sp_space_text FROM m_space ORDER BY sp_space_cd";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		echo '<option ', value_selected($fetch->sp_space_cd, $selected), '>', htmlspecialchars($fetch->sp_space_text), '</option>', "\n";
	}
}

// セッション処理
session_start();
if (!isset($_SESSION['ss_regist2']))
	redirect('member_input_exp.php');
$reg = &$_SESSION['ss_regist2'];
?>
<html>
<head>
<title>☆★☆xxxゾウさんClub☆★☆</title>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<script type="text/javascript">
<!--
function onload() {
	var f = document.form1;
//	show_hide('keitai', f.keitai_cd.value);
	show_hide('boiler');
	show_hide('boiler');
	show_hide('city_gas');
	show_hide('car', f.car_flag.value);
}
function show_hide(id) {
	var show = '';
	var f = document.form1;
	if (document.all) {
		switch (id) {
/*
		case "keitai":
			if (f.keitai_cd.value == '3')
				show = 'disp';
			break;
*/
		case "boiler":
			if (f.boiler_3cd.value == '3') {
				id = 'gas_sub';
				show = 'disp';
				document.getElementById('ele_sub').style.display = "none";
			} else if (f.boiler_3cd.value == '1') {
				id = 'ele_sub';
				show = 'disp';
				document.getElementById('gas_sub').style.display = "none";
			} else {
				document.getElementById('ele_sub').style.display = "none";
				document.getElementById('gas_sub').style.display = "none";
				return;
			}
			break;
		case "city_gas":
			if (f.gas_kind_cd.value == '1')
				show = 'disp';
			break;
		case "car":
			if (f.car_flag[0].checked)
				show = 'disp';
			break;
		}
		ida = document.all(id);
	} else if (document.getElementById) {
		ida = document.getElementById(id);
	} else
		return;

	for (i = 0; i < ida.length; i++) {
		ida[i].style.display = show ? "" : "none";
	}
}
//-->
</script></head>
<body bgcolor="#FF0000" leftmargin="8" topmargin="8" <?=check_browser('onload')?>>
<div align="center">

<form name="form1" method="post" action="member_input04_check.php">
<table width="714" height="90%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
	<tr>
		<td width="35" align="left" valign="top" bgcolor="#FFFFFF">
		<img src="img/kado_1.gif" width="35" height="35"></td>
		<td align="center" valign="bottom"><br>

			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td align="center"><img src="img/nyuukai/title/m_input04_01.gif" width="246" height="37"></td>
				</tr>
			</table>

			<hr width="95%" size="1">
		</td>
		<td width="35" align="right" valign="top">
		<img src="img/kado_2.gif" width="35" height="35"></td>
	</tr>

	<tr>
		<td><br></td>
		<td align="center" valign="top">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td bgcolor="#00CCFF"><img src="img/spacer.gif" width="620" height="4"></td>
				</tr>
			</table>
			<table border="0">
				<tr>
					<td align="right" width=600><font color="#ff0000" size=2>*</font><font size=2>は必須入力です。</font></td>
<!--
					<td bgcolor="#00CCFF"><img src="img/spacer.gif" width="620" height="4"></td>
-->
				</tr>
			</table>

			<table border="0">
				<tr>
					<td align="left" valign="top" width="90" height=40><font size=2><nobr>1.住居形態</nobr></td><td align="left" valign="top" width=10>：</font></td>
					<td width="618" colspan=3 valign="top">
						<table border="0" width=100%>
							<tr>
								<td valign="top" width=5><font color="#ff0000" size=2>*</font></td>
								<td>
						      <select name="keitai_cd"><?=select_keitai('--建物形態--', $reg->keitai_cd)?></select>
<!--
									<select name="keitai_cd">
										<option value=''>--建物形態--</option>
										<option <?=value_selected('1', $reg->keitai_cd)?>>一戸建て(住居専用)</option>
										<option <?=value_selected('3', $reg->keitai_cd)?>>一戸建て(店舗,事務所,医院等併用)</option>
										<option <?=value_selected('2', $reg->keitai_cd)?>>集合住宅</option>
									</select>
-->
<!--
									<span id='keitai'>
									<font size=2>その他</font>
									<input type="text" name="keitai_etc" maxlength=50 value="" id='keitai'>
									</span>
-->
									<!-- 1_keitaiでその他を選んだ場合のみ有効 -->
									<br>
									<font color="#ff0000" size=2>*</font>
									<font size=2>木造ですか？
									<input type="radio" name="sozai_cd" <?=value_checked('1', $reg->sozai_cd)?>>はい
									<input type="radio" name="sozai_cd" <?=value_checked('2', $reg->sozai_cd)?>>いいえ
									</font>
									<br>
									<font color="#ff0000" size=2>*</font>
									<font size=2>へーベルハウスですか？
									<input type="radio" name="hebel_flag" <?=value_checked('1', $reg->hebel_flag)?>>はい
									<input type="radio" name="hebel_flag" <?=value_checked('0', $reg->hebel_flag)?>>いいえ
									</font>
								</td>
							</tr>
						</table>
					</td>
				</tr>

				<tr><td height="5"></td></tr>

				<tr>
					<td align="left" valign="top" width="90" height=40><font size=2><nobr>2.建設年次</nobr></td><td align="left" valign="top" width=10>：</font></td>
					<td colspan=3 valign="top">
						<table border="0" width=100%>
							<tr>
								<td valign="top" width=5></td>
								<td>
						      <select name="chikunen_cd"><?=select_chikunen('--建設年次--', $reg->chikunen_cd)?></select>
								</td>
							</tr>
						</table>
					</td>
				</tr>

				<tr><td height="5"></td></tr>

				<tr>
					<td align="left" valign="top" width="90" height=40><font size=2><nobr>3.住居面積</nobr></td><td align="left" valign="top" width=10>：</font></td>
					<td valign="top" width=270>
						<table border="0" width=100%>
							<tr>
								<td valign="top" width=5><font color="#ff0000" size=2>*</font></td>
								<td width=265>
						      <select name="room_cd"><?=select_room('--間取り--', $reg->room_cd)?></select>
								</td>
							</tr>

							<tr>
								<td valign="top"></td>
								<td>
						      <select name="space_cd"><?=select_space('--床面積(m2)--', $reg->space_cd)?></select>
								</td>
							</tr>
						</table>
					</td>
				</tr>

				<tr><td height="5"></td></tr>

				<tr>
					<td align="left" valign="top" width="90" height=40><font size=2><nobr>4.給湯器タイプ</nobr></td><td align="left" valign="top" width=10>：</font></td>
					<td colspan=2 valign="top">
						<table border="0" width=100%>
							<tr>
								<td valign="top" width=5><font color="#ff0000" size=2>*</font></td>
								<td>
								<select name="boiler_3cd" <?=check_browser('onchange', 'boiler')?>><option value=''>--給湯器タイプ--</option>
								<option <?=value_selected('3', $reg->boiler_3cd)?>>ガス給湯器</option>
								<option <?=value_selected('1', $reg->boiler_3cd)?>>電気温水器</option>
								<option <?=value_selected('5', $reg->boiler_3cd)?>>灯油給湯器</option>
								</select>
								<!-- 選んだプルダウンで下記のどちらかを表示させてください -->
								<br>
								<font size=2>
								<span id='gas_sub'>
								<font color="blue">
								&lt;&lt;ガス給湯器を選択された方&gt;&gt;<br>
								</font>
								潜熱回収型（エコジョーズ等）ですか？<br>
								<font color="#ff0000" size=2>*</font>
								<input type="radio" name="boil_gas_flag" <?=value_checked('1', $reg->boil_gas_flag)?> id='gas_sub'>はい
								<input type="radio" name="boil_gas_flag" <?=value_checked('2', $reg->boil_gas_flag)?> id='gas_sub'>いいえ
								<input type="radio" name="boil_gas_flag" <?=value_checked('3', $reg->boil_gas_flag)?> id='gas_sub'>わからない
								<br>
								</span>
								<span id='ele_sub'>
								<font color="blue">
								&lt;&lt;電気温水器を選択された方&gt;&gt;<br>
								</font>
								ヒートポンプ式（エコキュート等)ですか？<br>
								<font color="#ff0000" size=2>*</font>
								<input type="radio" name="boil_ele_flag" <?=value_checked('1', $reg->boil_ele_flag)?> id='ele_sub'>はい
								<input type="radio" name="boil_ele_flag" <?=value_checked('2', $reg->boil_ele_flag)?> id='ele_sub'>いいえ
								<input type="radio" name="boil_ele_flag" <?=value_checked('3', $reg->boil_ele_flag)?> id='ele_sub'>わからない
								</span>
								</font>
								</td>
							</tr>
						</table>
					</td>
					<td rowspan="8"><img src="img/nyuukai/zou_img/m_input04_01.gif" alt="我が家の住宅に関して"></td>
				</tr>

				<tr><td height="5"></td></tr>

				<tr>
					<td align="left" valign="top" width="90" height=40><font size=2><nobr>5.ガス種</nobr></td><td align="left" valign="top" width=10>：</font></td>
					<td colspan=2 valign="top">
						<table border="0" width=100%>
							<tr>
								<td valign="top" width=5><font color="#ff0000" size=2>*</font></td>
								<td>
						      <select name="gas_kind_cd" <?=check_browser('onchange', 'city_gas')?>><?=select_gas_kind('--ガス種類--', $reg->gas_kind_cd)?></select>
								<br>
								<span id='city_gas'>
								<font size=2>※都市ガスの方のみ→</font>
								<!-- 都市ガス選択者のみ有効になるようにしてください -->
									<font color="#ff0000" size=2>*</font>
									<span id='city_gas'>
						      <select name="gas_type_cd">
									<?=select_gas_type('--ガスタイプ--', $reg->gas_type_cd)?>
<!--
									<option <?=value_selected('99', $reg->gas_type_cd)?> id='city_gas'>後で入力する</option>
-->
									</select>
									</span>
									<br>
									<font color="blue" size=2>
									※『後で入力する』を選択すると[13A]が自動で選択されます。正しいガスタイプが確認できましたら入会登録完了後の「会員情報の変更」で変更をしてください。
									</font>
									</span>
								<!-- 後で入力するを選んだ方には、下記内容の一文を表示させてください -->
								<!-- 『後で入力する』を選択すると[13A]が自動で選択されます。正しいガスタイプが確認できましたら入会登録完了後のマンスリー・データ入力で変更をしてください -->
								</select>
								</td>
							</tr>
						</table>
					</td>
				</tr>

				<tr><td height="5"></td></tr>

				<tr>
					<td align="left" valign="top" width="90" height=40><font size=2><nobr>6.水道の請求月</nobr></td><td align="left" valign="top" width=10>：</font></td>
					<td colspan=2 valign="top">
						<table border="0" width=100%>
							<tr>
								<td valign="top" width=5><font color="#ff0000" size=2>*</font></td>
								<td>
								<select name="water_month">
								<option value=''>--請求月--</option>
				        <option <?=value_selected('1', $reg->water_month)?>>奇数月</option>
				        <option <?=value_selected('2', $reg->water_month)?>>偶数月</option>
<!--
								<option <?=value_selected('99', $reg->water_month)?>>後で入力する</option>
-->
								</select>
								<br>
								<font size=2>※検針票を受け取る月を選択して下さい。 </font><br>
								<font color="black" size=2>
								（わからない方は、とりあえずどちらかを選択して、あとで「会員情報の変更」で設定変更してください。）
								</font>
								</td>
							</tr>
						</table>
					</td>
				</tr>

				<tr><td height="5"></td></tr>

				<tr>
					<td align="left" valign="top" width="90" height=40><font size=2><nobr>7.車所有</nobr></td><td align="left" valign="top" width=10>：</font></td>
					<td colspan=2 valign="top">
						<table border="0" width=100%>
							<tr>
								<td valign="top" rowspan="4" width=5><font color="#ff0000" size=2>*</font></td>
								<td colspan=2><font size=2>所有</font>
								<font size=2>
								<input type="radio" name="car_flag" <?=value_checked('1', $reg->car_flag)?> <?=check_browser('onclick', 'car')?>>あり
								<input type="radio" name="car_flag" <?=value_checked('0', $reg->car_flag)?> <?=check_browser('onclick', 'car')?>>なし
								<!-- なしを選択した場合下記入力欄をグレイアウトする -->
								</font>
								</td>
							</tr>
							<tr id='car'>
								<td width=70><font size=2>ガソリン車</font></td>
								<td><font size=2><input type="text" size=5 maxlength=2 name="gcar_num" <?=value($reg->gcar_num)?>>台</font></td>
							</tr>
							<tr id='car'>
								<td><font size=2>ディーゼル車</font></td>
								<td><font size=2><input type="text" size=5 maxlength=2 name="dcar_num" <?=value($reg->dcar_num)?>>台</font></td>
							</tr>
							<tr id='car'>
								<td><font size=2>バイク</font></td>
								<td><font size=2><input type="text" size=5 maxlength=2 name="bike_num" <?=value($reg->bike_num)?>>台</font></td>
							</tr>
						</table>
					</td>
				</tr>

				<tr><td height="5"></td></tr>

				<tr>
					<td align="left" valign="top" width="90" height=40><font size=2><nobr>8.灯油の利用</nobr></td><td align="left" valign="top" width=10>：</font></td>
					<td colspan=2 valign="top">
						<table border="0" width=100%>
							<tr>
								<td valign="top" width=5><font color="#ff0000" size=2>*</font></td>
								<td>
								<font size=2>
								<input type="radio" name="ol_flag" <?=value_checked('1', $reg->ol_flag)?>>あり
								<input type="radio" name="ol_flag" <?=value_checked('0', $reg->ol_flag)?>>なし
								<br>(1年を通じてお考えください)<br>
								<br>
								</font>
								</td>
							</tr>
						</table>
					</td>
				</tr>

			</table>
			<table>
				<td bgcolor="#00CCFF"><img src="img/spacer.gif" width="620" height="4"></td>
			</table>

			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td align="center"><img src="img/nyuukai/page/03.gif" width="44" height="44"></td>
				</tr>
			</table>

		</td>
		<td><br></td>
	</tr>
	<tr>
		<td width="35" align="left" valign="bottom">
		<img src="img/kado_3.gif" width="35" height="35"></td>
		<td align="center" valign="bottom">
		<hr width="95%" size="1">

			<table border="0" cellspacing="0" cellpadding="0">
				<tr align="center">
<?
if (!$reg->back_page) {
?>

					<td><a href="member_input03.php"><img src="img/nyuukai/button/back.gif" width="99" height="40" border="0" alt="戻る"></a></td>
					<td width="10"><img src="img/spacer.gif" width="10" height="40"></td>
<!--
					<td><a href="member_input04.php"><img src="img/nyuukai/button/cancell.gif" width="99" height="40" border="0" alt="キャンセル"></a></td>
					<td width="10"><img src="img/spacer.gif" width="10" height="40"></td>
-->
					<td><input type="image" src="img/nyuukai/button/next.gif" width="99" height="40" alt="次へ" border="0"></td>
<?
} else {
?>
					<td><a href="javascript:history.back()"><img src="img/nyuukai/button/back.gif" width="99" height="40" border="0" alt="戻る"></a></td>
					<td width="10"></td>
					<td><input type="image" src="img/nyuukai/button/syuusei.gif" width="99" height="40" alt="修正" border="0"></td>
<?
}
?>
				</tr>
				<tr>
					<td height="7" colspan="5"><img src="img/spacer.gif" height="7"></td>
				</tr>
			</table>

		</td>
		<td width="35" height="35" align="right" valign="bottom">
		<img src="img/kado_4.gif" width="35" height="35"></td>
	</tr>
</table>

</form>
</div>

</body>
</html>