<?
/******************************************************
' System :�������ơ�net����
' Content:����������Ͻ���
'******************************************************/

function search_script($search, $job_id = '') {
	global $top, $show_haimail_flag;
?>
<script type="text/javascript">
<!--
function show_reset() {
	with (document.form1) {
		show_hide('html_mail_c', html_mail_flag.checked);
<? if ($show_haimail_flag) { ?>
		show_hide('haimail_flag_c', haimail_flag_flag.checked);
<? } ?>
		show_hide('sex_c', sex_flag.checked);
		show_hide('age_c', age_flag.checked);
		show_hide('mikikon_c', mikikon_flag.checked);
		show_hide('shokugyou_c', shokugyou_flag.checked);
		show_hide('gyoushu_c', gyoushu_flag.checked);
		show_hide('shokushu_c', shokushu_flag.checked);
		show_hide('kyojuu_c', kyojuu_flag.checked);
		show_hide('kinmu_c', kinmu_flag.checked);
		show_hide('spouse_flg_c', spouse_flg_flag.checked);
		show_hide('have_child_c', have_child_flag.checked);
		show_hide('senior_flg_c', senior_flg_flag.checked);
		show_hide('housing_form_c', housing_form_flag.checked);
		show_hide('have_car_c', have_car_flag.checked);
		show_hide('genre_c', genre_flag.checked);
		show_hide('conveni_c', conveni_flag.checked);
		show_hide('super_c', super_flag.checked);
<? /* ?>
		show_hide('family_sex_c', family_sex_flag.checked);
		show_hide('family_rel_c', family_rel_flag.checked);
		show_hide('family_age_c', family_age_flag.checked);
		show_hide('child_c', child_flag.checked);
		show_hide('child_age_c', child_age_flag.checked);
<? */ ?>
		show_hide('sql_c', sql_flag.checked);
		show_hide('zip_c', zip_flag.checked);
		show_hide('taishou_hasshin_c', taishou_hasshin_flag.checked);
		show_hide('taishou_response_c', taishou_response_flag.checked);
		show_hide('jogai_hasshin_c', jogai_hasshin_flag.checked);
		show_hide('jogai_response_c', jogai_response_flag.checked);
		show_hide('member_list_c', member_list_flag.checked);
		show_hide('cross1_c', cross1_flag.checked);
		show_hide('cross2_c', cross2_flag.checked);
<?
	foreach (array_keys($search->ma_profile_tbl) as $profile_id) {
		$base_name = "profile$profile_id";
?>
		show_hide('<?=$base_name?>_c', <?=$base_name?>_flag.checked);
<?
	}
?>
		show_spouse();
		show_child();
	}
}
function show_hide(id, show) {
	if (show)
		document.all(id).style.display = "";
	else
		document.all(id).style.display = "none";
}
function all_check(name, check) {
	var n = document.form1(name).length;
	for (var i = 0; i < n; i++)
		document.form1(name)[i].checked = check;
}
function job_select(title, name) {
	var url;
	url = "<?=$top?>/../common/job_select.php?job_id=<?=$job_id?>&title=" + title + "&name=" + name + "&id=" + document.form1(name).value.split("\n").join(",");
	window.open(url, "job_select", "resizable=yes,scrollbars=yes");
}
function member_select(name) {
	var url;
	url = "<?=$top?>/../common/member_list.php?name=" + name + "&id=" + document.form1(name).value.split("\n").join(",") + "&job_id=<?=$job_id?>";
	window.open(url, "member_list", "resizable=yes,scrollbars=yes,width=600");
}
function check_search_data(f) {
	if (f.age_flag.checked) {
		if (!check_number(f.age_from.value)) {
			alert("ǯ��Ͽ��ͤ����Ϥ��Ƥ���������");
			f.age_from.focus();
			return false;
		}
		if (!check_number(f.age_to.value)) {
			alert("ǯ��Ͽ��ͤ����Ϥ��Ƥ���������");
			f.age_to.focus();
			return false;
		}
	}
<? /* ?>
	if (f.family_age_flag.checked) {
		if (!check_number(f.family_age_from.value)) {
			alert("��²��ǯ��Ͽ��ͤ����Ϥ��Ƥ���������");
			f.family_age_from.focus();
			return false;
		}
		if (!check_number(f.family_age_to.value)) {
			alert("��²��ǯ��Ͽ��ͤ����Ϥ��Ƥ���������");
			f.family_age_to.focus();
			return false;
		}
	}
	if (f.child_age_flag.checked) {
		if (!check_number(f.child_age_from.value)) {
			alert("�Ҷ���ǯ��Ͽ��ͤ����Ϥ��Ƥ���������");
			f.child_age_from.focus();
			return false;
		}
		if (!check_number(f.child_age_to.value)) {
			alert("�Ҷ���ǯ��Ͽ��ͤ����Ϥ��Ƥ���������");
			f.child_age_to.focus();
			return false;
		}
	}
<? */ ?>
	if (f.zip_flag.checked) {
		if (!check_numbers(f.zip.value)) {
			alert("͹���ֹ�λ���˸�꤬����ޤ���");
			f.zip.focus();
			return false;
		}
	}
	if (f.taishou_hasshin_flag.checked) {
		if (!check_numbers(f.taishou_hasshin.value)) {
			alert("�о�ȯ������֤λ���˸�꤬����ޤ���");
			f.taishou_hasshin.focus();
			return false;
		}
	}
	if (f.taishou_response_flag.checked) {
		if (!check_numbers(f.taishou_response.value)) {
			alert("�оݥ쥹�ݥ󥹥���֤λ���˸�꤬����ޤ���");
			f.taishou_response.focus();
			return false;
		}
	}
	if (f.jogai_hasshin_flag.checked) {
		if (!check_numbers(f.jogai_hasshin.value)) {
			alert("����ȯ������֤λ���˸�꤬����ޤ���");
			f.jogai_hasshin.focus();
			return false;
		}
	}
	if (f.jogai_response_flag.checked) {
		if (!check_numbers(f.jogai_response.value)) {
			alert("�����쥹�ݥ󥹥���֤λ���˸�꤬����ޤ���");
			f.jogai_response.focus();
			return false;
		}
	}
	if (f.member_list_flag.checked) {
		if (!check_numbers(f.member_list.value)) {
			alert("���С��ꥹ�Ȥλ���˸�꤬����ޤ���");
			f.member_list.focus();
			return false;
		}
	}
	if (f.cross1_flag.checked && f.cross2_flag.checked) {
		for (var i = 0; i < f.cross1.length; i++) {
			if (f.cross1[i].checked && f.cross2[i].checked) {
				alert("������������ɽƬ��ɽ¦�ϰۤʤ���ܤ����򤷤Ƥ���������");
				f.cross1[0].focus();
				return false;
			}
		}
	}
	return true;
}
function check_number(s) {
	var okStr = "0123456789 ";
	var ok = true;
	for (var i = 0; i < s.length; i++) {
		if (okStr.indexOf(s.charAt(i)) == -1) {
			ok = false;
			break;
		}
	}
	return ok;
}
function check_numbers(s) {
	var okStr = "0123456789,\r\n ";
	var ok = true;
	for (var i = 0; i < s.length; i++) {
		if (okStr.indexOf(s.charAt(i)) == -1) {
			ok = false;
			break;
		}
	}
	return ok;
}
function show_spouse() {
	var f = document.form1;
	document.getElementById("id_spouse").style.display = (f.spouse_flg_flag.checked && f.spouse_flg[0].checked) ? "" : "none";
}
function show_child() {
	var f = document.form1;
	document.getElementById("id_child").style.display = (f.have_child_flag.checked && f.have_child[0].checked) ? "" : "none";
}
//-->
</script>
<?
}

// dislay ����
function display($flag) {
	if (!$flag)
		return 'style="display:none"';
}

// ��������
function checkbox_shokugyou($shokugyou, $class) {
	$sql = "SELECT sg_shokugyou_cd,sg_shokugyou_name FROM m_shokugyou ORDER BY sg_order";
	checkbox_common($sql, 'shokugyou', $shokugyou, 2, $class);
}

// �ȼ�����
function checkbox_gyoushu($gyoushu, $class) {
	$sql = "SELECT gs_gyoushu_cd,gs_gyoushu_name FROM m_gyoushu WHERE gs_type_cd=1 ORDER BY gs_order";
	checkbox_common($sql, 'gyoushu', $gyoushu, 2, $class);
}

// ��������
function checkbox_shokushu($shokushu, $class) {
	$sql = "SELECT ss_shokushu_cd,ss_shokushu_name FROM m_shokushu ORDER BY ss_order";
	checkbox_common($sql, 'shokushu', $shokushu, 2, $class);
}

// �����å��ܥå���ɽ������
function checkbox_common($sql, $name, $code, $cols, $class) {
	$code_ary = explode(',', $code);
	$sel_ary = array();

	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_row($result, $i);
		$sel_ary[$fetch[0]] = $fetch[1];
	}

	checkbox_common2($sel_ary, $name, $code_ary, $cols, $class);
}

// �����å��ܥå���ɽ������
function checkbox_common2($sel_ary, $name, $code_ary, $cols, $class) {
	echo '<table border=1 width="100%" bgcolor="#FFFFFF" bordercolorlight="#d0d0d0"', $class ? " class='$class'" : '', ">\n";
	$col = 0;
	foreach ($sel_ary as $code => $text) {
		if ($col % $cols == 0)
			echo "<tr>\n";

		echo "<td width='10%'><input type='checkbox' name='${name}[]' value='$code'", (is_array($code_ary) && in_array($code, $code_ary)) ? ' checked' : '', '>', htmlspecialchars($text), "</td>\n";

		if (++$col % $cols == 0)
			echo "</tr>\n";
	}
	if ($col % $cols != 0)
		echo "</tr>\n";

	echo "</table>\n";
}

// ����
function checkbox_juusho($name, $def, $class) {
	$defc = "$def,";

	echo '<table border=1 width="100%" bgcolor="#FFFFFF" bordercolorlight="#d0d0d0"', $class ? " class='$class'" : '', ">\n";

	$sql = "SELECT ci_todoufuken,ci_shiku FROM m_city ORDER BY ci_seq_no";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);

	$flag = false;
	$count = 0;
	$cols = 6;
	for ($i = 0; $i < $nrow; ) {
		$fetch = pg_fetch_object($result, $i);

		$kakko = '';
		if (is_null($fetch->ci_shiku)) {
			$value = $fetch->ci_todoufuken;
			$disp = $fetch->ci_todoufuken;
		} else {
			$value = "$fetch->ci_todoufuken/$fetch->ci_shiku";
			if (!$flag) {
				$kakko = '��';
				$flag = true;
			}
			$disp = '<font color="#008000">' . $fetch->ci_shiku . '</font>';
		}

		$i++;

		if ($flag) {
			if ($i == $nrow)
				$disp .= '��';
			else {
				$fetch = pg_fetch_object($result, $i);
				if (is_null($fetch->ci_shiku)) {
					$disp .= '��';
					$flag = false;
				}
			}
		}

		if ($count % $cols == 0)
			echo '<tr>';

		if (strstr($defc, "$value,"))
			$check = ' checked';
		else
			$check = '';

		echo "<td width='10%'><nobr>$kakko<input type='checkbox' name='{$name}[]' value='$value'$check>$disp</nobr></td>\n";

		if (++$count % $cols == 0)
			echo '</tr>';
	}

	if ($count % $cols != 0)
		echo '</tr>';

	echo '</table>';
}

// ��̣������
function checkbox_genre($genre, $class) {
	$sql = "SELECT kg_genre_cd,kg_name FROM m_genre WHERE kg_status=0 ORDER BY kg_order";
	checkbox_common($sql, 'genre', $genre, 4, $class);
}

// �褯�Ԥ�����ӥ�
function checkbox_conveni($conveni, $class) {
	$sql = "SELECT cv_conveni_cd,cv_name FROM m_conveni WHERE cv_status=0 ORDER BY cv_order";
	checkbox_common($sql, 'conveni', $conveni, 4, $class);
}

// �褯�Ԥ������ѡ�
function checkbox_super($super, $class) {
	$sql = "SELECT sp_super_cd,sp_name FROM m_super WHERE sp_status=0 ORDER BY sp_order";
	checkbox_common($sql, 'super', $super, 4, $class);
}

// ��²��³��
function checkbox_family_rel($family_rel, $class) {
	$sql = "SELECT fr_family_rel_cd,fr_name FROM m_family_rel ORDER BY fr_family_rel_cd";
	checkbox_common($sql, 'family_rel', $family_rel, 8, $class);
}

function search_condition($search, $class = '', $shubetu = '') {
	global $show_haimail_flag;
?>
<tr>
	<td class="m1" width="20%">�������</td>
	<td class="n1" width="80%">
<?
// ����˥�����ǥե���ȤȤ���
if ($search->type =='')
	$search->type = '0';
?>
		<input type="hidden" name="type_flag" value="1">
		<input type="radio" name="type" <?=value_checked('0', $search->type)?>>����˥���
		<input type="radio" name="type" <?=value_checked('5', $search->type)?>>�����ƥ��֥�˥����Τ�
		<input type="radio" name="type" <?=value_checked('2', $search->type)?>>�ꥵ�����ѡ��ȥʡ��Τ�
	</td>
</tr>
<tr>
	<td class="m1">HTML�᡼������ե饰</td>
	<td class="n1">
		<input type="checkbox" name="html_mail_flag" <?=checked($search->html_mail)?> onclick="show_hide('html_mail_c', checked)">���ꤹ��
		<span id="html_mail_c">&nbsp;
			<input type="radio" name="html_mail" <?=value_checked(DBTRUE, $search->html_mail)?>>�ۿ�OK
			<input type="radio" name="html_mail" <?=value_checked(DBFALSE, $search->html_mail)?>>�ۿ�NG
		</span>
	</td>
</tr>
<? if ($show_haimail_flag) { ?>
<tr>
	<td class="m1">�Ϥ��᡼������ե饰</td>
	<td class="n1">
		<input type="checkbox" name="haimail_flag_flag" <?=checked($search->mn_haimail_flag)?> onclick="show_hide('haimail_flag_c', checked);if (!checked) {document.form1.haimail_flag[0].checked = false;document.form1.haimail_flag[1].checked = false;}">���ꤹ��
		<span id="haimail_flag_c">&nbsp;
			<input type="radio" name="haimail_flag" <?=value_checked(DBTRUE, $search->haimail_flag)?>>��������
			<input type="radio" name="haimail_flag" <?=value_checked(DBFALSE, $search->haimail_flag)?>>�������ʤ�
		</span>
	</td>
</tr>
<? } ?>
<tr style="font-size:smaller;font-weight=bold;color=#ffffff;background-color=#4682B4">
	<td colspan=2>���ܾ���</td>
</tr>
<tr>
	<td class="m1">����</td>
	<td class="n1">
		<input type="checkbox" name="sex_flag" <?=checked($search->sex)?> onclick="show_hide('sex_c', checked)">���ꤹ��
		<span id="sex_c">&nbsp;
			<input type="radio" name="sex" <?=value_checked('1', $search->sex)?>>����
			<input type="radio" name="sex" <?=value_checked('2', $search->sex)?>>����
		</span>
	</td>
</tr>
<tr>
	<td class="m1">ǯ��</td>
	<td class="n1">
		<input type="checkbox" name="age_flag" <?=checked($search->age_from || $search->age_to)?> onclick="show_hide('age_c', checked)">���ꤹ��
		<span id="age_c">&nbsp;
			<input type="text" name="age_from" size=4 value="<?=$search->age_from?>">�ͤ���
			<input type="text" name="age_to" size=4 value="<?=$search->age_to?>">�ͤޤ�
		</span>
	</td>
</tr>
<tr>
	<td class="m1">̤����</td>
	<td class="n1">
		<input type="checkbox" name="mikikon_flag" <?=checked($search->mikikon)?> onclick="show_hide('mikikon_c', checked)">���ꤹ��
		<span id="mikikon_c">&nbsp;
			<input type="radio" name="mikikon" <?=value_checked('1', $search->mikikon)?>>̤��
			<input type="radio" name="mikikon" <?=value_checked('2', $search->mikikon)?>>����
			<input type="radio" name="mikikon" <?=value_checked('3', $search->mikikon)?>>����¾
		</span>
	</td>
</tr>
<tr>
	<td class="m1">����</td>
	<td class="n1">
		<input type="checkbox" name="shokugyou_flag" <?=checked($search->shokugyou)?> onclick="show_hide('shokugyou_c', checked)">���ꤹ��
		<span id="shokugyou_c">&nbsp;
			<input type="button" value="������" onclick="all_check('shokugyou[]', true)">
			<input type="button" value="�����" onclick="all_check('shokugyou[]', false)">
			<? checkbox_shokugyou($search->shokugyou, $class) ?>
		</span>
	</td>
</tr>
<tr>
	<td class="m1">�ȼ�</td>
	<td class="n1">
		<input type="checkbox" name="gyoushu_flag" <?=checked($search->gyoushu)?> onclick="show_hide('gyoushu_c', checked)">���ꤹ��
		<span id="gyoushu_c">&nbsp;
			<input type="button" value="������" onclick="all_check('gyoushu[]', true)">
			<input type="button" value="�����" onclick="all_check('gyoushu[]', false)">
			<? checkbox_gyoushu($search->gyoushu, $class) ?>
		</span>
	</td>
</tr>
<tr>
	<td class="m1">����</td>
	<td class="n1">
		<input type="checkbox" name="shokushu_flag" <?=checked($search->shokushu)?> onclick="show_hide('shokushu_c', checked)">���ꤹ��
		<span id="shokushu_c">&nbsp;
			<input type="button" value="������" onclick="all_check('shokushu[]', true)">
			<input type="button" value="�����" onclick="all_check('shokushu[]', false)">
			<? checkbox_shokushu($search->shokushu, $class) ?>
		</span>
	</td>
</tr>
<tr>
	<td class="m1">�ｻ�ϰ�</td>
	<td class="n1">
		<input type="checkbox" name="kyojuu_flag" <?=checked($search->kyojuu)?> onclick="show_hide('kyojuu_c', checked)">���ꤹ��
		<span id="kyojuu_c">&nbsp;
			<input type="button" value="������" onclick="all_check('kyojuu[]', true)">
			<input type="button" value="�����" onclick="all_check('kyojuu[]', false)">
			<? checkbox_juusho('kyojuu', $search->kyojuu, $class) ?>
		</span>
	</td>
</tr>
<tr>
	<td class="m1">��̳���ϰ�</td>
	<td class="n1">
		<input type="checkbox" name="kinmu_flag" <?=checked($search->kinmu)?> onclick="show_hide('kinmu_c', checked)">���ꤹ��
		<span id="kinmu_c">&nbsp;
			<input type="button" value="������" onclick="all_check('kinmu[]', true)">
			<input type="button" value="�����" onclick="all_check('kinmu[]', false)">
			&nbsp;���ｻ�ϡ���̳��
			<input type="radio" name="kinmu_andor" <?=value_checked('O', $search->kinmu_andor)?>>OR
			<input type="radio" name="kinmu_andor" <?=value_checked('A', $search->kinmu_andor)?>>AND
			<? checkbox_juusho('kinmu', $search->kinmu, $class) ?>
		</span>
	</td>
</tr>
<tr class="small" bgcolor="#4682B4">
	<td colspan=2><font color="white"><b>�������</b></font></td>
</tr>
<tr>
	<td class="m1">Ʊ���²���۶��ԡ�</td>
	<td class="n1">
		<p>
			<input type="checkbox" name="spouse_flg_flag" <?=checked($search->spouse_flg)?> onclick="show_hide('spouse_flg_c', checked);show_spouse()">���ꤹ��
			<span id="spouse_flg_c">&nbsp;
				<input type="radio" name="spouse_flg" <?=value_checked('1', $search->spouse_flg)?> onclick="show_spouse()">ͭ��
				<input type="radio" name="spouse_flg" <?=value_checked('2', $search->spouse_flg)?> onclick="show_spouse()">̵��
				<input type="radio" name="spouse_flg" <?=value_checked('3', $search->spouse_flg)?> onclick="show_spouse()">����¾
			</span>
		</p>
		<div id="id_spouse">
			<p>
				�ݡ��۶��Ԥ�ǯ��<br>
				&nbsp;&nbsp;&nbsp;
				<input type="text" name="spouse_age_from" size=4 value="<?=$search->spouse_age_from?>">�ͤ���
				<input type="text" name="spouse_age_to" size=4 value="<?=$search->spouse_age_to?>">�ͤޤ�
			</p>
			<p>
				�ݡ��۶��Ԥο���<br>
				&nbsp;&nbsp;&nbsp;
				<select name="spouse_shokugyo"><? select_shokugyou('����̵��', $search->spouse_shokugyo) ?></select>
			</p>
		</div>
	</td>
</tr>
<tr>
	<td class="m1">Ʊ���²(�Ҷ���</td>
	<td class="n1">
		<p>
			<input type="checkbox" name="have_child_flag" <?=checked($search->have_child)?> onclick="show_hide('have_child_c', checked);show_child()">���ꤹ��
			<span id="have_child_c">&nbsp;
				<input type="radio" name="have_child" <?=value_checked('1', $search->have_child)?> onclick="show_child()">ͭ��
				<input type="radio" name="have_child" <?=value_checked('2', $search->have_child)?> onclick="show_child()">̵��
			</span>
		</p>
		<div id="id_child">
			<p>
				�ݡݻҶ�������<br>
				&nbsp;&nbsp;&nbsp;
				<input type="radio" name="child_sex" <?=value_checked('1', $search->child_sex)?>>�˻�
				<input type="radio" name="child_sex" <?=value_checked('2', $search->child_sex)?>>����
			</p>
			<p>
				�ݡݻҶ���ǯ��<br>
				&nbsp;&nbsp;&nbsp;
				<input type="text" name="child_age_from" size=4 value="<?=$search->child_age_from?>">�ͤ���
				<input type="text" name="child_age_to" size=4 value="<?=$search->child_age_to?>">�ͤޤ�
			</p>
			<p>
				�ݡݻҶ��γ�ǯ<br>
				&nbsp;&nbsp;&nbsp;
				<select name="child_gakunen"><? select_child_gakunen('����̵��', $search->child_gakunen) ?></select>
			</p>
		</div>
	</td>
</tr>
<tr>
	<td class="m1">Ʊ���²�ʥ��˥��ء�</td>
	<td class="n1">
		<div id="id_senior">
			<p>
				<input type="checkbox" name="senior_flg_flag" <?=checked($search->senior_flg)?> onclick="show_hide('senior_flg_c', checked)">���ꤹ��
				<span id="senior_flg_c">&nbsp;
					<input type="radio" name="senior_flg" <?=value_checked('1', $search->senior_flg)?>>ͭ��
					<input type="radio" name="senior_flg" <?=value_checked('2', $search->senior_flg)?>>̵��
				</span>
			</p>
		</div>
	</td>
</tr>
<tr>
	<td class="m1">�������</td>
	<td class="n1">
		<input type="checkbox" name="housing_form_flag" <?=checked($search->housing_form)?> onclick="show_hide('housing_form_c', checked)">���ꤹ��
		<span id="housing_form_c">&nbsp;
			<input type="radio" name="housing_form" <?=value_checked('1', $search->housing_form)?>>��ͷ���
			<input type="radio" name="housing_form" <?=value_checked('2', $search->housing_form)?>>���罻��
		</span>
	</td>
</tr>
<tr>
	<td class="m1">�֤ν�ͭ</td>
	<td class="n1">
		<input type="checkbox" name="have_car_flag" <?=checked($search->have_car)?> onclick="show_hide('have_car_c', checked)">���ꤹ��
		<span id="have_car_c">&nbsp;
			<input type="checkbox" name="have_car[]" <?=value_checked_multi('1', $search->have_car)?>>�ȵ��ȼ֤��ͭ���Ƥ���
			<input type="checkbox" name="have_car[]" <?=value_checked_multi('2', $search->have_car)?>>�ȵ��Τ߽�ͭ���Ƥ���
			<input type="checkbox" name="have_car[]" <?=value_checked_multi('3', $search->have_car)?>>�ȵ��Ͻ�ͭ���Ƥ��ʤ�
		</span>
	</td>
</tr>
<tr>
	<td class="m1">��̣������</td>
	<td class="n1">
		<input type="checkbox" name="genre_flag" <?=checked($search->genre)?> onclick="show_hide('genre_c', checked)">���ꤹ��
		<span id="genre_c">&nbsp;
			<input type="button" value="������" onclick="all_check('genre[]', true)">
			<input type="button" value="�����" onclick="all_check('genre[]', false)">
			&nbsp;�������
			<input type="radio" name="genre_andor" <?=value_checked('O', $search->genre_andor)?>>OR
			<input type="radio" name="genre_andor" <?=value_checked('A', $search->genre_andor)?>>AND
			<? checkbox_genre($search->genre, $class) ?>
		</span>
	</td>
</tr>
<tr>
	<td class="m1">�褯�Ԥ�����ӥ�</td>
	<td class="n1">
		<input type="checkbox" name="conveni_flag" <?=checked($search->conveni)?> onclick="show_hide('conveni_c', checked)">���ꤹ��
		<span id="conveni_c">&nbsp;
			<input type="button" value="������" onclick="all_check('conveni[]', true)">
			<input type="button" value="�����" onclick="all_check('conveni[]', false)">
			&nbsp;�������
			<input type="radio" name="conveni_andor" <?=value_checked('O', $search->conveni_andor)?>>OR
			<input type="radio" name="conveni_andor" <?=value_checked('A', $search->conveni_andor)?>>AND
			<? checkbox_conveni($search->conveni, $class) ?>
		</span>
	</td>
</tr>
<tr>
	<td class="m1">�褯�Ԥ������ѡ�</td>
	<td class="n1">
		<input type="checkbox" name="super_flag" <?=checked($search->super)?> onclick="show_hide('super_c', checked)">���ꤹ��
		<span id="super_c">&nbsp;
			<input type="button" value="������" onclick="all_check('super[]', true)">
			<input type="button" value="�����" onclick="all_check('super[]', false)">
			&nbsp;�������
			<input type="radio" name="super_andor" <?=value_checked('O', $search->super_andor)?>>OR
			<input type="radio" name="super_andor" <?=value_checked('A', $search->super_andor)?>>AND
			<? checkbox_super($search->super, $class) ?>
		</span>
	</td>
</tr>
<? /* ?>
<tr class="small" bgcolor="#4682B4">
	<td colspan=2><font color="white"><b>��²����</b></font></td>
</tr>
<tr>
	<td class="m1">��²������</td>
	<td class="n1">
		<input type="checkbox" name="family_sex_flag" <?=checked($search->family_sex)?> onclick="show_hide('family_sex_c', checked)">���ꤹ��
		<span id="family_sex_c">&nbsp;
			<input type="checkbox" name="family_sex_m" <?=value_checked('t', $search->family_sex_m)?>>����
			<input type="checkbox" name="family_sex_w" <?=value_checked('t', $search->family_sex_w)?>>����
		</span>
	</td>
</tr>
<tr>
	<td class="m1">��²��³��</td>
	<td class="n1">
		<input type="checkbox" name="family_rel_flag" <?=checked($search->family_rel)?> onclick="show_hide('family_rel_c', checked)">���ꤹ��
		<span id="family_rel_c">&nbsp;
			<input type="button" value="������" onclick="all_check('family_rel[]', true)">
			<input type="button" value="�����" onclick="all_check('family_rel[]', false)">
			&nbsp;�������
			<input type="radio" name="family_rel_andor" <?=value_checked('O', $search->family_rel_andor)?>>OR
			<input type="radio" name="family_rel_andor" <?=value_checked('A', $search->family_rel_andor)?>>AND
			<? checkbox_family_rel($search->family_rel, $class) ?>
		</span>
	</td>
</tr>
<tr>
	<td class="m1">��²��ǯ��</td>
	<td class="n1">
		<input type="checkbox" name="family_age_flag" <?=checked($search->family_age_from || $search->family_age_to)?> onclick="show_hide('family_age_c', checked)">���ꤹ��
		<span id="family_age_c">&nbsp;
			<input type="text" name="family_age_from" size=4 value="<?=$search->family_age_from?>">�ͤ���
			<input type="text" name="family_age_to" size=4 value="<?=$search->family_age_to?>">�ͤޤ�
		</span>
	</td>
</tr>
<tr>
	<td class="m1">�Ҷ���̵ͭ</td>
	<td class="n1">
		<input type="checkbox" name="child_flag" <?=checked($search->child)?> onclick="show_hide('child_c', checked)">���ꤹ��
		<span id="child_c">&nbsp;
			<input type="radio" name="child" <?=value_checked('t', $search->child)?>>ͭ��
			<input type="radio" name="child" <?=value_checked('f', $search->child)?>>̵��
		</span>
	</td>
</tr>
<tr>
	<td class="m1">�Ҷ���ǯ��</td>
	<td class="n1">
		<input type="checkbox" name="child_age_flag" <?=checked($search->child_age_from || $search->child_age_to)?> onclick="show_hide('child_age_c', checked)">���ꤹ��
		<span id="child_age_c">&nbsp;
			<input type="text" name="child_age_from" size=4 value="<?=$search->child_age_from?>">�ͤ���
			<input type="text" name="child_age_to" size=4 value="<?=$search->child_age_to?>">�ͤޤ�
		</span>
	</td>
</tr>
<? */ ?>
<?
if (count($search->ma_profile_tbl) > 0) {
	$ma_profile = array();
	foreach (explode(',', $search->ma_profile) as $profile_cd) {
		if ($profile_cd)
			$ma_profile[$search->ma_profile_cd[$profile_cd]][] = $profile_cd;
	}

	$ma_profile_andor = explode(',', $search->ma_profile_andor);
?>
<tr class="small" bgcolor="#4682B4">
	<td colspan=2><font color="white"><b>��ư�ɲåץ�ե�����</b></font></td>
</tr>
<?
	foreach ($search->ma_profile_tbl as $profile_id => $profile_data) {
		$base_name = "profile$profile_id";
		$and_or = in_array($profile_id, $ma_profile_andor) ? 'A' : 'O';
?>
<tr>
	<td class="m1"><?=htmlspecialchars($profile_data->profile_name)?></td>
	<td class="n1">
		<input type="checkbox" name="<?=$base_name?>_flag" <?=checked(isset($ma_profile[$profile_id]))?> onclick="show_hide('<?=$base_name?>_c', checked)">���ꤹ��
		<span id="<?=$base_name?>_c">&nbsp;
			<input type="button" value="������" onclick="all_check('<?=$base_name?>[]', true)">
			<input type="button" value="�����" onclick="all_check('<?=$base_name?>[]', false)">
			&nbsp;�������
			<input type="radio" name="<?=$base_name?>_andor" <?=value_checked('O', $and_or)?>>OR
			<input type="radio" name="<?=$base_name?>_andor" <?=value_checked('A', $and_or)?>>AND
			<? checkbox_common2($profile_data->sel, $base_name, $ma_profile[$profile_id], 4, $class) ?>
		</span>
	</td>
</tr>
<?
	}
}
?>
<tr class="small" bgcolor="#4682B4">
	<td colspan=2><font color="white"><b>����¾�ξ��</b></font></td>
</tr>
<tr>
	<td class="m1">�ɲãӣѣ�</td>
	<td class="n1">
		<input type="checkbox" name="sql_flag" <?=checked($search->sql)?> onclick="show_hide('sql_c', checked)">���ꤹ��
		<span id="sql_c">&nbsp;
			<font class="note">��SQLʸ��WHERE�������Ϥ��Ƥ���������</font><br>
			<textarea name="add_sql" cols=80 rows=5><?=$search->sql?></textarea>
		</span>
	</td>
</tr>
<tr>
	<td class="m1">͹���ֹ�</td>
	<td class="n1">
		<input type="checkbox" name="zip_flag" <?=checked($search->zip)?> onclick="show_hide('zip_c', checked)">���ꤹ��
		<span id="zip_c">&nbsp;
			<font class="note">��͹���ֹ�򥫥�ޤǶ��ڤä����Ϥ��Ƥ���������</font><br>
			<textarea name="zip" cols=80 rows=3><?=$search->zip?></textarea>
		</span>
	</td>
</tr>
<tr>
	<td class="m1">�о�ȯ�������</td>
	<td class="n1">
		<input type="checkbox" name="taishou_hasshin_flag" <?=checked($search->taishou_hasshin)?> onclick="show_hide('taishou_hasshin_c', checked)">���ꤹ��
		<span id="taishou_hasshin_c">&nbsp;
			<font class="note">�ʥ����ID�򥫥�ޤǶ��ڤä����Ϥ��뤫��������ץܥ���򥯥�å����Ƥ���������</font>&nbsp;
			<input type="button" value="����" onclick="job_select('<?=urlencode('�о�ȯ�����������')?>', 'taishou_hasshin')"><br>
			<textarea name="taishou_hasshin" cols=80 rows=3><?=$search->taishou_hasshin?></textarea>
		</span>
	</td>
</tr>
<tr>
	<td class="m1">�оݥ쥹�ݥ󥹥����</td>
	<td class="n1">
		<input type="checkbox" name="taishou_response_flag" <?=checked($search->taishou_response)?> onclick="show_hide('taishou_response_c', checked)">���ꤹ��
		<span id="taishou_response_c">&nbsp;
			<font class="note">�ʥ����ID�򥫥�ޤǶ��ڤä����Ϥ��뤫��������ץܥ���򥯥�å����Ƥ���������</font>&nbsp;
			<input type="button" value="����" onclick="job_select('<?=urlencode('�оݥ쥹�ݥ󥹥��������')?>', 'taishou_response')"><br>
			<textarea name="taishou_response" cols=80 rows=3><?=$search->taishou_response?></textarea>
		</span>
	</td>
</tr>
<tr>
	<td class="m1">����ȯ�������</td>
	<td class="n1">
		<input type="checkbox" name="jogai_hasshin_flag" <?=checked($search->jogai_hasshin)?> onclick="show_hide('jogai_hasshin_c', checked)">���ꤹ��
		<span id="jogai_hasshin_c">&nbsp;
			<font class="note">�ʥ����ID�򥫥�ޤǶ��ڤä����Ϥ��뤫��������ץܥ���򥯥�å����Ƥ���������</font>&nbsp;
			<input type="button" value="����" onclick="job_select('<?=urlencode('����ȯ�����������')?>', 'jogai_hasshin')"><br>
			<textarea name="jogai_hasshin" cols=80 rows=3><?=$search->jogai_hasshin?></textarea>
		</span>
	</td>
</tr>
<tr>
	<td class="m1">�����쥹�ݥ󥹥����</td>
	<td class="n1">
		<input type="checkbox" name="jogai_response_flag" <?=checked($search->jogai_response)?> onclick="show_hide('jogai_response_c', checked)">���ꤹ��
		<span id="jogai_response_c">&nbsp;
			<font class="note">�ʥ����ID�򥫥�ޤǶ��ڤä����Ϥ��뤫��������ץܥ���򥯥�å����Ƥ���������</font>&nbsp;
			<input type="button" value="����" onclick="job_select('<?=urlencode('�����쥹�ݥ󥹥��������')?>', 'jogai_response')"><br>
			<textarea name="jogai_response" cols=80 rows=3><?=$search->jogai_response?></textarea>
		</span>
	</td>
</tr>
<tr>
	<td class="m1">���С��ꥹ��</td>
	<td class="n1">
		<input type="checkbox" name="member_list_flag" <?=checked($search->member_list)?> onclick="show_hide('member_list_c', checked)">���ꤹ��
		<span id="member_list_c">&nbsp;
			<font class="note">�ʥ��С����롼��ID�򥫥�ޤǶ��ڤä����Ϥ��뤫��������ץܥ���򥯥�å����Ƥ���������</font>&nbsp;
			<input type="button" value="����" onclick="member_select('member_list')"><br>
			<textarea name="member_list" cols=80 rows=3><?=$search->member_list?></textarea>
		</span>
	</td>
</tr>
<tr class="small" bgcolor="#4682B4">
	<td colspan=2><font color="white"><b>����������</b></font></td>
</tr>
<tr>
	<td class="m1">ɽƬ����</td>
	<td class="n1">
		<input type="checkbox" name="cross1_flag" onclick="show_hide('cross1_c', checked)">���ꤹ��
		<span id="cross1_c">&nbsp;
			<table border=0 cellspacing=0 cellpadding=0>
				<tr>
					<td><input type="radio" name="cross1" value="1">����</td>
					<td><input type="radio" name="cross1" value="2">ǯ���̡�10�й�ߡ�</td>
					<td><input type="radio" name="cross1" value="3">ǯ���̡�5�й�ߡ�</td>
					<td><input type="radio" name="cross1" value="4">̤������</td>
					<td><input type="radio" name="cross1" value="5">������</td>
				</tr>
				<tr>
					<td><input type="radio" name="cross1" value="6">�ȼ���</td>
					<td><input type="radio" name="cross1" value="7">������</td>
					<td><input type="radio" name="cross1" value="8">��ƻ�ܸ���</td>
					<td><input type="radio" name="cross1" value="9">���ꥢ��</td>
				</tr>
				<tr>
					<td><input type="radio" name="cross1" value="10">����ӥ���</td>
					<td><input type="radio" name="cross1" value="11">�����ѡ���</td>
					<td><input type="radio" name="cross1" value="12">��̣��������</td>
				</tr>
			</table>
		</span>
	</td>
</tr>
<tr>
	<td class="m1">ɽ¦����</td>
	<td class="n1">
		<input type="checkbox" name="cross2_flag" onclick="show_hide('cross2_c', checked)">���ꤹ��
		<span id="cross2_c">&nbsp;
			<table border=0 cellspacing=0 cellpadding=0>
				<tr>
					<td><input type="radio" name="cross2" value="1">����</td>
					<td><input type="radio" name="cross2" value="2">ǯ���̡�10�й�ߡ�</td>
					<td><input type="radio" name="cross2" value="3">ǯ���̡�5�й�ߡ�</td>
					<td><input type="radio" name="cross2" value="4">̤������</td>
					<td><input type="radio" name="cross2" value="5">������</td>
				</tr>
				<tr>
					<td><input type="radio" name="cross2" value="6">�ȼ���</td>
					<td><input type="radio" name="cross2" value="7">������</td>
					<td><input type="radio" name="cross2" value="8">��ƻ�ܸ���</td>
					<td><input type="radio" name="cross2" value="9">���ꥢ��</td>
				</tr>
				<tr>
					<td><input type="radio" name="cross2" value="10">����ӥ���</td>
					<td><input type="radio" name="cross2" value="11">�����ѡ���</td>
					<td><input type="radio" name="cross2" value="12">��̣��������</td>
				</tr>
			</table>
		</span>
	</td>
</tr>
<?
}
?>