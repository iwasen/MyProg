<?
$top = './..';
$inc = "$top/../inc";
include("$inc/ma_profile.php");
include("$inc/select.php");
$inc = "$top/inc";
$img = "$top/image";
include("$inc/mn_regist.php");
include("$inc/mn_mypage.php");
include("$inc/mn_header2.php");

// チェックボックス表示処理
function checkbox_common($sql, $name, $code, $cols) {
	$ary = explode(',', $code);

	$result = db_exec($sql);
	$nrow = pg_numrows($result);

	echo '<table border=0 width="100%" cellspacing=0 cellpadding=0>', "\n";
	$col = 0;
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_row($result, $i);
		if ($col % $cols == 0)
			echo "<tr>\n";

		echo "<td width='50%'><font size=2><input type='checkbox' name='${name}[]' value='$fetch[0]'", in_array($fetch[0], $ary) ? ' checked' : '', '>', htmlspecialchars($fetch[1]), "</font></td>\n";

		if (++$col % $cols == 0)
			echo "</tr>\n";
	}
	if ($col % $cols != 0)
		echo "</tr>\n";

	echo "</table>\n";
}

// 興味ジャンル
function checkbox_genre($code) {
	$sql = "SELECT kg_genre_cd,kg_name FROM m_genre WHERE kg_status=0 ORDER BY kg_order";
	checkbox_common($sql, 'genre', $code, 2);
}

// よく行くコンビニ
function checkbox_conveni($code) {
	$sql = "SELECT cv_conveni_cd,cv_name FROM m_conveni WHERE cv_status=0 ORDER BY cv_order";
	checkbox_common($sql, 'conveni', $code, 2);
}

// よく行くスーパー
function checkbox_super($code) {
	$sql = "SELECT sp_super_cd,sp_name FROM m_super WHERE sp_status=0 ORDER BY sp_order";
	checkbox_common($sql, 'super', $code, 2);
}

// 追加プロファイル
function checkbox_ma_profile($profile_id, $code) {
	$sql = "SELECT pfs_profile_cd,pfs_select_text FROM t_profile_sel WHERE pfs_profile_id=$profile_id ORDER BY pfs_order";
	checkbox_common($sql, 'ma_profile', $code, 2);
}

// セッション処理
if (!isset($_SESSION['ss_regist']))
	redirect('m_update.php');
$reg = &$_SESSION['ss_regist'];

?>
<? monitor_menu('', true, 'onload_body()') ?>

<script type="text/javascript">
<!--
function onload_body() {
	disp_spouse();
	disp_child();
}
function disp_spouse() {
	document.getElementById("id_spouse").style.display = document.form1.spouse_flg[0].checked ? "" : "none";
}
function disp_child() {
	var f = document.form1;
	document.getElementById("id_child").style.display = f.have_child[0].checked ? "" : "none";
	for (i = 0; i < <?=MAX_MONITOR_CHILD?>; i++)
		document.getElementById("id_child_" + i).style.display = (i < f.child_num.value) ? "" : "none";
	document.getElementById("id_add_child").style.display = (f.child_num.value < <?=MAX_MONITOR_CHILD?>) ? "" : "none";
	document.getElementById("id_del_child").style.display = (f.child_num.value > 1) ? "" : "none";
}
function add_child() {
	document.form1.child_num.value++;
	disp_child();
}
function del_child() {
	document.form1.child_num.value--;
	disp_child();
}
//-->
</script>

<form name="form1" method="post" action="m_update_l_check.php">
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
		<td colspan="3"><table width="450" border="0" cellspacing="0" cellpadding="20">
				<tr>
					<td>
						<font size="2"><table border="0" cellpadding="3" cellspacing="2" width="100%">
							<tr>
								<td bgcolor="#525294"><font color="#ffcc00">■　</font><font color="white">生活情報の更新</font></td>
							</tr>
							
						<td><font size = "2"> 登録情報更新に関するＦＡＱは<a href = faq.php#14 target="_blank">こちら</a> </font></td>
							
							<tr>
								<td><font size="2"><b><br>
									</b><table border="0" cellpadding="3" cellspacing="2" width="100%">
										<tr>
											<td bgcolor="#c3c2ff"><font color="#ffcc00" size="2">●</font><font size="2">同居家族</font></td>
										</tr>
										<tr>
											<td width="100%" valign="top">
												<font size="2">
						■配偶者について（未既婚）<br>
						<nobr>
						&nbsp;&nbsp;&nbsp;
						<input type="radio" name="spouse_flg" <?=value_checked('1', $reg->spouse_flg)?> onclick="disp_spouse()">あり（既婚）　
						<input type="radio" name="spouse_flg" <?=value_checked('2', $reg->spouse_flg)?> onclick="disp_spouse()">なし（未婚）　
						<input type="radio" name="spouse_flg" <?=value_checked('3', $reg->spouse_flg)?> onclick="disp_spouse()">その他
						<input type="text" name="spouse_other" <?=value($reg->spouse_other)?>>
						</nobr>
						<br><br>
						<div id="id_spouse">
							−−配偶者の生年月日（任意回答）<br>
							&nbsp;&nbsp;&nbsp;
							<select name="spouse_birthday_y"><? select_year(1900, ' ', $reg->spouse_birthday_y, date('Y')) ?></select> 年　
							<select name="spouse_birthday_m"><? select_month(' ', $reg->spouse_birthday_m) ?></select> 月　
							<select name="spouse_birthday_d"><? select_day(' ', $reg->spouse_birthday_d) ?></select> 日
							<br><br>
							−−配偶者の職業<br>
							&nbsp;&nbsp;&nbsp;
							<select name="spouse_shokugyo_cd" size="1"><?select_shokugyou('お選び下さい', $reg->spouse_shokugyo_cd)?></select>
							<br><br>
						</div>
						■お子さまについて<br>
						&nbsp;&nbsp;&nbsp;
						<input type="radio" name="have_child" <?=value_checked('1', $reg->have_child)?> onclick="disp_child()">子供あり　
						<input type="radio" name="have_child" <?=value_checked('2', $reg->have_child)?> onclick="disp_child()">子供なし
						<br>
生年月日について、年月までは必ず正確にお選びください。
日にちの指定がためらわれる場合は「15日」としてください。<br>
<font color="red">※4月1日生まれの場合は是非正確にご入力をお願い致します。</font><br>
						<div id="id_child">
<?
$child_num = count($reg->child);
if ($child_num == 0)
	$child_num = 1;

for ($i = 0; $i < MAX_MONITOR_CHILD; $i++) {
?>
							<table cellpadding="1" cellspacing="0" id="id_child_<?=$i?>" <?=$i < $child_num ? '' : 'style="display:none"'?>>
								<tr>
									<td colspan="2">&nbsp;</td>
								</tr>
								<tr>
									<td colspan="2">−−<?=$i + 1?>人目</td>
								</tr>
								<tr>
									<td width="80">&nbsp;&nbsp;&nbsp; 性別</td>
									<td>
										<input type="radio" name="child_sex[<?=$i?>]" <?=value_checked('1', $reg->child[$i]->sex)?>>男子　
										<input type="radio" name="child_sex[<?=$i?>]" <?=value_checked('2', $reg->child[$i]->sex)?>>女子
									</td>
								</tr>
								<tr>
									<td width="80">&nbsp;&nbsp;&nbsp; 生年月日</td>
									<td>
										<select name="child_birthday_y[<?=$i?>]"><? select_year(1900, ' ', $reg->child[$i]->birthday_y, date('Y')) ?></select> 年　
										<select name="child_birthday_m[<?=$i?>]"><? select_month(' ', $reg->child[$i]->birthday_m) ?></select> 月　
										<select name="child_birthday_d[<?=$i?>]"><? select_day(' ', $reg->child[$i]->birthday_d) ?></select> 日
									</td>
								</tr>
							</table>
<?
}
?>
							&nbsp;&nbsp;&nbsp;
							<input type="button" id="id_add_child" value="追加" onclick="add_child()">
							<input type="button" id="id_del_child" value="削除" onclick="del_child()">
							<input type="hidden" name="child_num" <?=value($child_num)?>>
						</div>
						<br>
						■現在同居されているシニア層の方について（60歳以上の方、本人を除く）<br>&nbsp;&nbsp;&nbsp;
						<input type="radio" name="senior_flg" <?=value_checked('1', $reg->senior_flg)?>>あり　
						<input type="radio" name="senior_flg" <?=value_checked('2', $reg->senior_flg)?>>なし
												</font>
											</td>
										</tr>
										<tr>
											<td bgcolor="#c3c2ff"><font color="#ffcc00" size="2">●</font><font size="2">住居形態</font></td>
										</tr>
										<tr>
											<td width="100%" valign="top">
												<font size="2">
													<input type="radio" name="housing_form" <?=value_checked('1', $reg->housing_form)?>>一戸建て　
													<input type="radio" name="housing_form" <?=value_checked('2', $reg->housing_form)?>>集合住宅
												</font>
											</td>
										</tr>
										<tr>
											<td bgcolor="#c3c2ff"><font color="#ffcc00" size="2">●</font><font size="2">車の所有</font></td>
										</tr>
										<tr>
											<td width="100%" valign="top">
												<font size="2">
													<input type="radio" name="have_car" <?=value_checked('1', $reg->have_car)?>>免許を持っていて、車も所有している<br>
													<input type="radio" name="have_car" <?=value_checked('2', $reg->have_car)?>>免許は持っているが、車は所有していない<br>
													<input type="radio" name="have_car" <?=value_checked('3', $reg->have_car)?>>免許は所有していない
												</font>
											</td>
										</tr>
										<tr>
											<td bgcolor="#c3c2ff"><font color="#ffcc00" size="2">●</font><font size="2">よく行くコンビニ</font></td>
										</tr>
										<tr>
											<td width="100%" valign="top"><? checkbox_conveni($reg->conveni) ?></td>
										</tr>
										<tr>
											<td bgcolor="#c3c2ff"><font color="#ffcc00" size="2">●</font><font size="2">よく行くスーパー</font></td>
										</tr>
										<tr>
											<td width="100%" valign="top"><? checkbox_super($reg->super) ?></td>
										</tr>
<?
// 追加プロファイル
$ma_profile = get_ma_profile();
foreach ($ma_profile as $profile_id => $profile_name) {
?>
										<tr>
											<td bgcolor="#c3c2ff"><font color="#ffcc00" size="2">●</font><font size="2"><?=htmlspecialchars($profile_name)?></font></td>
										</tr>
										<tr>
											<td width="100%" valign="top"><? checkbox_ma_profile($profile_id, $reg->ma_profile) ?></td>
										</tr>
<?
}
?>
										<tr>
											<td bgcolor="#c3c2ff"><font color="#ffcc00" size="2">●</font><font size="2">興味ジャンル</font></td>
										</tr>
										<tr>
											<td width="100%" valign="top"><? checkbox_genre($reg->genre) ?></td>
										</tr>
									</table><br></font>
								</td>
							</tr>
							<tr>
								<td>
									<center><hr>
									<input type="submit" value="　確　認　">
									<input type="button" value="　戻　る　" onclick="location.href='m_confirm_b.php'">
									<input type="reset" value="　元に戻す　">
									</center>
								</td>
							</tr>
						</table></font>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</body>
</html>
