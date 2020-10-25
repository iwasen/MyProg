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

// �����å��ܥå���ɽ������
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

// ��̣������
function checkbox_genre($code) {
	$sql = "SELECT kg_genre_cd,kg_name FROM m_genre WHERE kg_status=0 ORDER BY kg_order";
	checkbox_common($sql, 'genre', $code, 2);
}

// �褯�Ԥ�����ӥ�
function checkbox_conveni($code) {
	$sql = "SELECT cv_conveni_cd,cv_name FROM m_conveni WHERE cv_status=0 ORDER BY cv_order";
	checkbox_common($sql, 'conveni', $code, 2);
}

// �褯�Ԥ������ѡ�
function checkbox_super($code) {
	$sql = "SELECT sp_super_cd,sp_name FROM m_super WHERE sp_status=0 ORDER BY sp_order";
	checkbox_common($sql, 'super', $code, 2);
}

// �ɲåץ�ե�����
function checkbox_ma_profile($profile_id, $code) {
	$sql = "SELECT pfs_profile_cd,pfs_select_text FROM t_profile_sel WHERE pfs_profile_id=$profile_id ORDER BY pfs_order";
	checkbox_common($sql, 'ma_profile', $code, 2);
}

// ���å�������
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
					<SPAN class=mysttl_menu>�����ץ�ե�����</SPAN>
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
		<td width="15">��</td>
		<td colspan="3"><table width="450" border="0" cellspacing="0" cellpadding="20">
				<tr>
					<td>
						<font size="2"><table border="0" cellpadding="3" cellspacing="2" width="100%">
							<tr>
								<td bgcolor="#525294"><font color="#ffcc00">����</font><font color="white">�������ι���</font></td>
							</tr>
							
						<td><font size = "2"> ��Ͽ���󹹿��˴ؤ���ƣ��Ѥ�<a href = faq.php#14 target="_blank">������</a> </font></td>
							
							<tr>
								<td><font size="2"><b><br>
									</b><table border="0" cellpadding="3" cellspacing="2" width="100%">
										<tr>
											<td bgcolor="#c3c2ff"><font color="#ffcc00" size="2">��</font><font size="2">Ʊ���²</font></td>
										</tr>
										<tr>
											<td width="100%" valign="top">
												<font size="2">
						���۶��ԤˤĤ��ơ�̤������<br>
						<nobr>
						&nbsp;&nbsp;&nbsp;
						<input type="radio" name="spouse_flg" <?=value_checked('1', $reg->spouse_flg)?> onclick="disp_spouse()">����ʴ����ˡ�
						<input type="radio" name="spouse_flg" <?=value_checked('2', $reg->spouse_flg)?> onclick="disp_spouse()">�ʤ���̤���ˡ�
						<input type="radio" name="spouse_flg" <?=value_checked('3', $reg->spouse_flg)?> onclick="disp_spouse()">����¾
						<input type="text" name="spouse_other" <?=value($reg->spouse_other)?>>
						</nobr>
						<br><br>
						<div id="id_spouse">
							�ݡ��۶��Ԥ���ǯ������Ǥ�ղ�����<br>
							&nbsp;&nbsp;&nbsp;
							<select name="spouse_birthday_y"><? select_year(1900, ' ', $reg->spouse_birthday_y, date('Y')) ?></select> ǯ��
							<select name="spouse_birthday_m"><? select_month(' ', $reg->spouse_birthday_m) ?></select> �
							<select name="spouse_birthday_d"><? select_day(' ', $reg->spouse_birthday_d) ?></select> ��
							<br><br>
							�ݡ��۶��Ԥο���<br>
							&nbsp;&nbsp;&nbsp;
							<select name="spouse_shokugyo_cd" size="1"><?select_shokugyou('�����Ӳ�����', $reg->spouse_shokugyo_cd)?></select>
							<br><br>
						</div>
						�����Ҥ��ޤˤĤ���<br>
						&nbsp;&nbsp;&nbsp;
						<input type="radio" name="have_child" <?=value_checked('1', $reg->have_child)?> onclick="disp_child()">�Ҷ����ꡡ
						<input type="radio" name="have_child" <?=value_checked('2', $reg->have_child)?> onclick="disp_child()">�Ҷ��ʤ�
						<br>
��ǯ�����ˤĤ��ơ�ǯ��ޤǤ�ɬ�����Τˤ����Ӥ���������
���ˤ��λ��꤬����������ϡ�15���פȤ��Ƥ���������<br>
<font color="red">��4��1�����ޤ�ξ����������Τˤ����Ϥ򤪴ꤤ�פ��ޤ���</font><br>
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
									<td colspan="2">�ݡ�<?=$i + 1?>����</td>
								</tr>
								<tr>
									<td width="80">&nbsp;&nbsp;&nbsp; ����</td>
									<td>
										<input type="radio" name="child_sex[<?=$i?>]" <?=value_checked('1', $reg->child[$i]->sex)?>>�˻ҡ�
										<input type="radio" name="child_sex[<?=$i?>]" <?=value_checked('2', $reg->child[$i]->sex)?>>����
									</td>
								</tr>
								<tr>
									<td width="80">&nbsp;&nbsp;&nbsp; ��ǯ����</td>
									<td>
										<select name="child_birthday_y[<?=$i?>]"><? select_year(1900, ' ', $reg->child[$i]->birthday_y, date('Y')) ?></select> ǯ��
										<select name="child_birthday_m[<?=$i?>]"><? select_month(' ', $reg->child[$i]->birthday_m) ?></select> �
										<select name="child_birthday_d[<?=$i?>]"><? select_day(' ', $reg->child[$i]->birthday_d) ?></select> ��
									</td>
								</tr>
							</table>
<?
}
?>
							&nbsp;&nbsp;&nbsp;
							<input type="button" id="id_add_child" value="�ɲ�" onclick="add_child()">
							<input type="button" id="id_del_child" value="���" onclick="del_child()">
							<input type="hidden" name="child_num" <?=value($child_num)?>>
						</div>
						<br>
						������Ʊ�蘆��Ƥ��륷�˥��ؤ����ˤĤ��ơ�60�аʾ�������ܿͤ������<br>&nbsp;&nbsp;&nbsp;
						<input type="radio" name="senior_flg" <?=value_checked('1', $reg->senior_flg)?>>���ꡡ
						<input type="radio" name="senior_flg" <?=value_checked('2', $reg->senior_flg)?>>�ʤ�
												</font>
											</td>
										</tr>
										<tr>
											<td bgcolor="#c3c2ff"><font color="#ffcc00" size="2">��</font><font size="2">�������</font></td>
										</tr>
										<tr>
											<td width="100%" valign="top">
												<font size="2">
													<input type="radio" name="housing_form" <?=value_checked('1', $reg->housing_form)?>>��ͷ��ơ�
													<input type="radio" name="housing_form" <?=value_checked('2', $reg->housing_form)?>>���罻��
												</font>
											</td>
										</tr>
										<tr>
											<td bgcolor="#c3c2ff"><font color="#ffcc00" size="2">��</font><font size="2">�֤ν�ͭ</font></td>
										</tr>
										<tr>
											<td width="100%" valign="top">
												<font size="2">
													<input type="radio" name="have_car" <?=value_checked('1', $reg->have_car)?>>�ȵ�����äƤ��ơ��֤��ͭ���Ƥ���<br>
													<input type="radio" name="have_car" <?=value_checked('2', $reg->have_car)?>>�ȵ��ϻ��äƤ��뤬���֤Ͻ�ͭ���Ƥ��ʤ�<br>
													<input type="radio" name="have_car" <?=value_checked('3', $reg->have_car)?>>�ȵ��Ͻ�ͭ���Ƥ��ʤ�
												</font>
											</td>
										</tr>
										<tr>
											<td bgcolor="#c3c2ff"><font color="#ffcc00" size="2">��</font><font size="2">�褯�Ԥ�����ӥ�</font></td>
										</tr>
										<tr>
											<td width="100%" valign="top"><? checkbox_conveni($reg->conveni) ?></td>
										</tr>
										<tr>
											<td bgcolor="#c3c2ff"><font color="#ffcc00" size="2">��</font><font size="2">�褯�Ԥ������ѡ�</font></td>
										</tr>
										<tr>
											<td width="100%" valign="top"><? checkbox_super($reg->super) ?></td>
										</tr>
<?
// �ɲåץ�ե�����
$ma_profile = get_ma_profile();
foreach ($ma_profile as $profile_id => $profile_name) {
?>
										<tr>
											<td bgcolor="#c3c2ff"><font color="#ffcc00" size="2">��</font><font size="2"><?=htmlspecialchars($profile_name)?></font></td>
										</tr>
										<tr>
											<td width="100%" valign="top"><? checkbox_ma_profile($profile_id, $reg->ma_profile) ?></td>
										</tr>
<?
}
?>
										<tr>
											<td bgcolor="#c3c2ff"><font color="#ffcc00" size="2">��</font><font size="2">��̣������</font></td>
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
									<input type="submit" value="���Ρ�ǧ��">
									<input type="button" value="���ᡡ�롡" onclick="location.href='m_confirm_b.php'">
									<input type="reset" value="�������᤹��">
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
