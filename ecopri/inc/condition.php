<?
/******************************************************
' System :Eco-footprint ����
' Content:����������Ͻ���
'******************************************************/

function search_script($job_id = '') {
	global $top;
?>
<script type="text/javascript">
<!--
function show_reset() {
	with (document.form1) {
//		show_hide('step_c', step_flag.checked);
		show_hide('sex_c', sex_flag.checked);
		show_hide('age_c', age_flag.checked);
		show_hide('area2_c', area2_flag.checked);
		show_hide('kyojuu_c', kyojuu_flag.checked);
		show_hide('keitai_c', keitai_flag.checked);
		show_hide('sozai_c', sozai_flag.checked);
		show_hide('hebel_c', hebel_flag.checked);
		show_hide('chikunen_c', chikunen_flag.checked);
		show_hide('room_c', room_flag.checked);
		show_hide('space_c', space_flag.checked);
		show_hide('boiler_c', boiler_flag.checked);
		show_hide('gas_kind_c', gas_kind_flag.checked);
		show_hide('gas_type_c', gas_type_flag.checked);
		show_hide('water_c', water_flag.checked);
		show_hide('car_c', car_flag.checked);
		show_hide('family_c', family_flag.checked);
		show_hide('family_rel_c', family_rel_flag.checked);
		show_hide('child_c', child_flag.checked);
		show_hide('child_youngest_c', child_youngest_flag.checked);
		show_hide('child_age_c', child_age_flag.checked);
		show_hide('gm_c', gm_flag.checked);
		show_hide('ol_c', ol_flag.checked);
		show_hide('gl_c', gl_flag.checked);
		show_hide('reg_kari_c', reg_kari_flag.checked);
		show_hide('reg_c', reg_flag.checked);
		show_hide('entry_c', entry_flag.checked);
		show_hide('commit_c', commit_flag.checked);
		show_hide('commit_m_c', commit_m_flag.checked);
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
	if (f.car_flag.checked) {
		if (!check_number(f.gcar_from.value)) {
			alert("����Ͽ��ͤ����Ϥ��Ƥ���������");
			f.gcar_from.focus();
			return false;
		}
		if (!check_number(f.gcar_to.value)) {
			alert("����Ͽ��ͤ����Ϥ��Ƥ���������");
			f.gcar_to.focus();
			return false;
		}
		if (!check_number(f.dcar_from.value)) {
			alert("����Ͽ��ͤ����Ϥ��Ƥ���������");
			f.dcar_from.focus();
			return false;
		}
		if (!check_number(f.dcar_to.value)) {
			alert("����Ͽ��ͤ����Ϥ��Ƥ���������");
			f.dcar_to.focus();
			return false;
		}
		if (!check_number(f.bike_from.value)) {
			alert("����Ͽ��ͤ����Ϥ��Ƥ���������");
			f.bike_from.focus();
			return false;
		}
		if (!check_number(f.bike_to.value)) {
			alert("����Ͽ��ͤ����Ϥ��Ƥ���������");
			f.bike_to.focus();
			return false;
		}
	}
	if (f.child_youngest_flag.checked) {
		if (!check_number(f.child_youngest_from.value)) {
			alert("�Ҷ���ǯ��Ͽ��ͤ����Ϥ��Ƥ���������");
			f.child_youngest_from.focus();
			return false;
		}
		if (!check_number(f.child_youngest_to.value)) {
			alert("�Ҷ���ǯ��Ͽ��ͤ����Ϥ��Ƥ���������");
			f.child_youngest_to.focus();
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
	if (f.commit_flag.checked) {
		if (!check_number(f.commit.value)) {
			alert("��Ͽ��ͤ����Ϥ��Ƥ���������");
			f.commit.focus();
			return false;
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
//-->
</script>
<?
}
// dislay ����
function display($flag) {
	if (!$flag)
		return 'style="display:none"';
}


// �ｻ����
function checkbox_keitai($keitai, $class) {
	$sql = "SELECT kt_keitai_cd,kt_keitai_name FROM m_keitai ORDER BY kt_order";
	checkbox_common($sql, 'keitai', $keitai, 3, $class);
}

// �ｻ�Ǻ�
function checkbox_sozai($sozai, $class) {
	$sql = "SELECT sz_sozai_cd,sz_sozai_name FROM m_sozai ORDER BY sz_order";
	checkbox_common($sql, 'sozai', $sozai, 3, $class);
}
// ����ǯ��
function checkbox_chikunen($chikunen, $class) {
	$sql = "SELECT cn_chikunen_cd,cn_chikunen_text FROM m_chikunen ORDER BY cn_chikunen_cd";
	checkbox_common($sql, 'chikunen', $chikunen, 4, $class);
}

// ����ּ��
function checkbox_room($room, $class) {
	$sql = "SELECT rm_room_cd,rm_room_name FROM m_room ORDER BY rm_order";
	checkbox_common($sql, 'room', $room, 4, $class);
}

// ��������
function checkbox_space($space, $class) {
	$sql = "SELECT sp_space_cd,sp_space_text FROM m_space ORDER BY sp_space_cd";
	checkbox_common($sql, 'space', $space, 4, $class);
}

// ����勵����
function checkbox_boiler($boiler, $class) {
	$sql = "SELECT bt_boiler_type_cd,bt_boiler_name FROM m_boiler_type ORDER BY bt_order";
	checkbox_common($sql, 'boiler', $boiler, 3, $class);
}

// ������
function checkbox_gas_kind($gas_kind, $class) {
	$sql = "SELECT gk_gas_kind_cd,gk_gas_kind_name FROM m_gas_kind ORDER BY gk_order";
	checkbox_common($sql, 'gas_kind', $gas_kind, 3, $class);
}

// ������
function checkbox_gas_type($gas_type, $class) {
	$sql = "SELECT gt_gas_type_cd,gt_gas_type_name FROM m_gas_type ORDER BY gt_order";
	checkbox_common($sql, 'gas_type', $gas_type, 7, $class);
}

// ��²���ƥ���
function checkbox_family($family, $class) {
	$sql = "SELECT fc_family_ctg_cd,fc_text FROM m_family_ctg ORDER BY fc_family_ctg_cd";
	checkbox_common($sql, 'family', $family, 3, $class);
}

// �����å��ܥå���ɽ������
function checkbox_common($sql, $name, $code, $cols, $class) {
	$ary = explode(',', $code);

	$result = db_exec($sql);
	$nrow = pg_numrows($result);

	echo '<table frame="box" border=1 width="100%" bgcolor="#FFFFFF" bordercolorlight="#d0d0d0"', $class ? " class='$class'" : '', ">\n";
	$col = 0;
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_row($result, $i);
		if ($col % $cols == 0)
			echo "<tr>\n";

		echo "<td width='10%'><input type='checkbox' name='${name}[]' value='$fetch[0]'", in_array($fetch[0], $ary) ? ' checked' : '', '>', htmlspecialchars($fetch[1]), "</td>\n";

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

// ��²��³��
function checkbox_family_rel($family_rel, $class) {
	$sql = "SELECT fr_family_rel_cd,fr_name FROM m_family_rel ORDER BY fr_order";
	checkbox_common($sql, 'family_rel', $family_rel, 5, $class);
}

// �����ϰ�
function checkbox_area2($area2, $class) {
	$sql = "SELECT aa_area2_cd,aa_area2_name FROM m_area2 ORDER BY aa_area2_cd";
	checkbox_common($sql, 'area2', $area2, 3, $class);
}

function search_condition($search, $class = '') {
?>
<tr>
	<td class="m1" width="20%">��Ͽ����</td>
	<td class="n1" width="80%">
	&nbsp;����Ͽ���
<!--
		<input type="checkbox" name="step_flag" <?=checked($search->step)?> onclick="show_hide('step_c', checked)">���ꤹ��
		<span id="step_c">&nbsp;
			<input type="radio" name="step" <?=value_checked('3', $search->step)?>>����Ͽ
			<input type="radio" name="step" <?=value_checked('1', $search->step)?> disabled>����Ͽ��
			<input type="radio" name="step" <?=value_checked('2', $search->step)?> disabled>����Ͽ��
		</span>
-->
	</td>
</tr>
<tr style="font-size:smaller;font-weight=bold;color=#ffffff;background-color=#4682B4">
	<td colspan=2>��Ͽ�Ծ���</td>
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
	<td class="m1">�ｻ�ϰ�</td>
	<td class="n1">
		<input type="checkbox" name="area2_flag" <?=checked($search->area2)?> onclick="show_hide('area2_c', checked)">���ꤹ��
		<span id="area2_c">&nbsp;
			<input type="button" value="������" onclick="all_check('area2[]', true)">
			<input type="button" value="�����" onclick="all_check('area2[]', false)">
			<? checkbox_area2($search->area2, $class) ?>
		</span>
	</td>
</tr>
<tr>
	<td class="m1">��ƻ�ܸ����Իԡ�</td>
	<td class="n1">
		<input type="checkbox" name="kyojuu_flag" <?=checked($search->kyojuu)?> onclick="show_hide('kyojuu_c', checked)">���ꤹ��
		<span id="kyojuu_c">&nbsp;
			<input type="button" value="������" onclick="all_check('kyojuu[]', true)">
			<input type="button" value="�����" onclick="all_check('kyojuu[]', false)">
			<? checkbox_juusho('kyojuu', $search->kyojuu, $class) ?>
		</span>
	</td>
</tr>
<tr style="font-size:smaller;font-weight=bold;color=#ffffff;background-color=#4682B4">
	<td colspan=2>�������</td>
</tr>
<tr>
	<td class="m1">�������</td>
	<td class="n1">
		<input type="checkbox" name="keitai_flag" <?=checked($search->keitai)?> onclick="show_hide('keitai_c', checked)">���ꤹ��
		<span id="keitai_c">&nbsp;
			<input type="button" value="������" onclick="all_check('keitai[]', true)">
			<input type="button" value="�����" onclick="all_check('keitai[]', false)">
			<? checkbox_keitai($search->keitai, $class) ?>
		</span>
	</td>
</tr>
<tr>
	<td class="m1">���ﹽ¤</td>
	<td class="n1">
		<input type="checkbox" name="sozai_flag" <?=checked($search->sozai)?> onclick="show_hide('sozai_c', checked)">���ꤹ��
		<span id="sozai_c">&nbsp;
			<input type="button" value="������" onclick="all_check('sozai[]', true)">
			<input type="button" value="�����" onclick="all_check('sozai[]', false)">
			<? checkbox_sozai($search->sozai, $class) ?>
		</span>
	</td>
</tr>
<tr>
	<td class="m1">�ء��٥�ϥ���</td>
	<td class="n1">
		<input type="checkbox" name="hebel_flag" <?=checked($search->hebel)?> onclick="show_hide('hebel_c', checked)">���ꤹ��
		<span id="hebel_c">&nbsp;
			<input type="radio" name="hebel" <?=value_checked('1', $search->hebel)?>>�Ϥ�
			<input type="radio" name="hebel" <?=value_checked('0', $search->hebel)?>>������
		</span>
	</td>
</tr>
<tr>
	<td class="m1">����ǯ��</td>
	<td class="n1">
		<input type="checkbox" name="chikunen_flag" <?=checked($search->chikunen)?> onclick="show_hide('chikunen_c', checked)">���ꤹ��
		<span id="chikunen_c">&nbsp;
			<input type="button" value="������" onclick="all_check('chikunen[]', true)">
			<input type="button" value="�����" onclick="all_check('chikunen[]', false)">
			<? checkbox_chikunen($search->chikunen, $class) ?>
		</span>
	</td>
</tr>
<tr>
	<td class="m1">����ּ��</td>
	<td class="n1">
		<input type="checkbox" name="room_flag" <?=checked($search->room)?> onclick="show_hide('room_c', checked)">���ꤹ��
		<span id="room_c">&nbsp;
			<input type="button" value="������" onclick="all_check('room[]', true)">
			<input type="button" value="�����" onclick="all_check('room[]', false)">
			<? checkbox_room($search->room, $class) ?>
		</span>
	</td>
</tr>
<tr>
	<td class="m1">��������</td>
	<td class="n1">
		<input type="checkbox" name="space_flag" <?=checked($search->space)?> onclick="show_hide('space_c', checked)">���ꤹ��
		<span id="space_c">&nbsp;
			<input type="button" value="������" onclick="all_check('space[]', true)">
			<input type="button" value="�����" onclick="all_check('space[]', false)">
			<? checkbox_space($search->space, $class) ?>
		</span>
	</td>
</tr>
<tr>
	<td class="m1">����勵����</td>
	<td class="n1">
		<input type="checkbox" name="boiler_flag" <?=checked($search->boiler)?> onclick="show_hide('boiler_c', checked)">���ꤹ��
		<span id="boiler_c">&nbsp;
			<input type="button" value="������" onclick="all_check('boiler[]', true)">
			<input type="button" value="�����" onclick="all_check('boiler[]', false)">
			<? checkbox_boiler($search->boiler, $class) ?>
		</span>
	</td>
</tr>
<tr>
	<td class="m1">������</td>
	<td class="n1">
		<input type="checkbox" name="gas_kind_flag" <?=checked($search->gas_kind)?> onclick="show_hide('gas_kind_c', checked)">���ꤹ��
		<span id="gas_kind_c">&nbsp;
			<input type="button" value="������" onclick="all_check('gas_kind[]', true)">
			<input type="button" value="�����" onclick="all_check('gas_kind[]', false)">
			<? checkbox_gas_kind($search->gas_kind, $class) ?>
		</span>
	</td>
</tr>
<tr>
	<td class="m1">����������</td>
	<td class="n1">
		<input type="checkbox" name="gas_type_flag" <?=checked($search->gas_type)?> onclick="show_hide('gas_type_c', checked)">���ꤹ��
		<span id="gas_type_c">&nbsp;
			<input type="button" value="������" onclick="all_check('gas_type[]', true)">
			<input type="button" value="�����" onclick="all_check('gas_type[]', false)">
			<? checkbox_gas_type($search->gas_type, $class) ?>
		</span>
	</td>
</tr>
<tr>
	<td class="m1">��ƻ���˷�</td>
	<td class="n1">
		<input type="checkbox" name="water_flag" <?=checked($search->water)?> onclick="show_hide('water_c', checked)">���ꤹ��
		<span id="water_c">&nbsp;
			<input type="radio" name="water" <?=value_checked('1', $search->water)?>>�����
			<input type="radio" name="water" <?=value_checked('2', $search->water)?>>������
		</span>
	</td>
</tr>
<tr>
	<td class="m1">�ֽ�ͭ</td>
	<td class="n1">
		<input type="checkbox" name="car_flag" <?=checked($search->gcar_from || $search->gcar_to|| $search->dcar_from || $search->dcar_to || $search->bike || $search->bike_to)?> onclick="show_hide('car_c', checked)">���ꤹ��
		<span id="car_c" class="small">&nbsp;
			�����
			<input type="radio" name="car_andor" value="O" <?=value_checked('O', $search->car_andor)?>>OR
			<input type="radio" name="car_andor" value="A" <?=value_checked('A', $search->car_andor)?>>AND<br>
			&nbsp;����������
			<input type="text" name="gcar_from" size=4 value="<?=$search->gcar_from?>">��
			<input type="text" name="gcar_to" size=4 value="<?=$search->gcar_to?>">��&nbsp;&nbsp;
			�ǥ��������
			<input type="text" name="dcar_from" size=4 value="<?=$search->dcar_from?>">��
			<input type="text" name="dcar_to" size=4 value="<?=$search->dcar_to?>">��&nbsp;&nbsp;
			�Х���
			<input type="text" name="bike_from" size=4 value="<?=$search->bike_from?>">��
			<input type="text" name="bike_to" size=4 value="<?=$search->bike_to?>">��
		</span>
	</td>
</tr>
<tr style="font-size:smaller;font-weight=bold;color=#ffffff;background-color=#4682B4">
	<td colspan=2>��²����</td>
</tr>
<tr>
	<td class="m1">��²���ƥ���</td>
	<td class="n1">
		<input type="checkbox" name="family_flag" <?=checked($search->family)?> onclick="show_hide('family_c', checked)">���ꤹ��
		<span id="family_c" class="small">&nbsp;
			<input type="button" value="������" onclick="all_check('family[]', true)">
			<input type="button" value="�����" onclick="all_check('family[]', false)">
			<? checkbox_family($search->family, $class) ?>
		</span>
	</td>
</tr>
<tr>
	<td class="m1">��²��³��</td>
	<td class="n1">
		<input type="checkbox" name="family_rel_flag" <?=checked($search->family_rel)?> onclick="show_hide('family_rel_c', checked)">���ꤹ��
		<span id="family_rel_c" class='small'>&nbsp;
			<input type="button" value="������" onclick="all_check('family_rel[]', true)">
			<input type="button" value="�����" onclick="all_check('family_rel[]', false)">
			&nbsp;�������
			<input type="radio" name="family_rel_andor" value="O" <?=value_checked('O', $search->family_rel_andor)?>>OR
			<input type="radio" name="family_rel_andor" value="A" <?=value_checked('A', $search->family_rel_andor)?>>AND
			<? checkbox_family_rel($search->family_rel, $class) ?>
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
	<td class="m1">��ǯ����²��ǯ��</td>
	<td class="n1">
		<input type="checkbox" name="child_youngest_flag" <?=checked($search->child_youngest_from || $search->child_youngest_to)?> onclick="show_hide('child_youngest_c', checked)">���ꤹ��
		<span id="child_youngest_c">&nbsp;
			<input type="text" name="child_youngest_from" size=4 value="<?=$search->child_youngest_from?>">�ͤ���
			<input type="text" name="child_youngest_to" size=4 value="<?=$search->child_youngest_to?>">�ͤޤ�
		</span>
	</td>
</tr>
<tr>
	<td class="m1">��²��ǯ��</td>
	<td class="n1">
		<input type="checkbox" name="child_age_flag" <?=checked($search->child_age_from || $search->child_age_to)?> onclick="show_hide('child_age_c', checked)">���ꤹ��
		<span id="child_age_c">&nbsp;
			<input type="text" name="child_age_from" size=4 value="<?=$search->child_age_from?>">�ͤ���
			<input type="text" name="child_age_to" size=4 value="<?=$search->child_age_to?>">�ͤޤ�
		</span>
	</td>
</tr>
<tr style="font-size:smaller;font-weight=bold;color=#ffffff;background-color=#4682B4">
	<td colspan=2>���Ϲ�������</td>
</tr>
<tr>
	<td class="m1">��������(��������)</td>
	<td class="n1">
		<input type="checkbox" name="ol_flag" <?=checked($search->ol)?> onclick="show_hide('ol_c', checked)">���ꤹ��
		<span id="ol_c">&nbsp;
			<input type="radio" name="ol" <?=value_checked('1', $search->ol)?>>����
			<input type="radio" name="ol" <?=value_checked('0', $search->ol)?>>�ʤ�
			<input type="radio" name="ol" <?=value_checked('99', $search->ol)?>>̤����
		</span>
	</td>
</tr>
<tr>
	<td class="m1">��������</td>
	<td class="n1">
		<input type="checkbox" name="gm_flag" <?=checked($search->gm)?> onclick="show_hide('gm_c', checked)">���ꤹ��
		<span id="gm_c">&nbsp;
			<input type="radio" name="gm" <?=value_checked('1', $search->gm)?>>����
			<input type="radio" name="gm" <?=value_checked('0', $search->gm)?>>�ʤ�
			<input type="radio" name="gm" <?=value_checked('99', $search->gm)?>>̤����
		</span>
	</td>
</tr>
<tr>
	<td class="m1">�����������</td>
	<td class="n1">
		<input type="checkbox" name="gl_flag" <?=checked($search->gl)?> onclick="show_hide('gl_c', checked)">���ꤹ��
		<span id="gl_c">&nbsp;
			<input type="radio" name="gl" <?=value_checked('1', $search->gl)?>>����
			<input type="radio" name="gl" <?=value_checked('0', $search->gl)?>>�ʤ�
			<input type="radio" name="gl" <?=value_checked('99', $search->gl)?>>̤����
		</span>
	</td>
</tr>
<tr style="font-size:smaller;font-weight=bold;color=#ffffff;background-color=#4682B4">
	<td colspan=2>��Ͽ����</td>
</tr>
<tr>
	<td class="m1">����Ͽ��</td>
	<td class="n1">
		<input type="checkbox" name="reg_kari_flag" <?=checked($search->reg_kari_y_from)?> onclick="show_hide('reg_kari_c', checked)">���ꤹ��
		<span id="reg_kari_c">&nbsp;
			<select name="reg_kari_y_from"><?=select_year('2002', '����ʤ�', $search->reg_kari_y_from)?></select>ǯ
			<select name="reg_kari_m_from"><?=select_month('��', $search->reg_kari_m_from)?></select>���
			<select name="reg_kari_y_to"><?=select_year('2002', '����ʤ�', $search->reg_kari_y_to)?></select>ǯ
			<select name="reg_kari_m_to"><?=select_month(' ', $search->reg_kari_m_to)?></select>��ޤ�
		</span>
	</td>
</tr>
<tr>
	<td class="m1">����Ͽ��</td>
	<td class="n1">
		<input type="checkbox" name="reg_flag" <?=checked($search->reg_y_from)?> onclick="show_hide('reg_c', checked)">���ꤹ��
		<span id="reg_c">&nbsp;
			<select name="reg_y_from"><?=select_year('2002', '����ʤ�', $search->reg_y_from)?></select>ǯ
			<select name="reg_m_from"><?=select_month('��', $search->reg_m_from)?></select>���
			<select name="reg_y_to"><?=select_year('2002', '����ʤ�', $search->reg_y_to)?></select>ǯ
			<select name="reg_m_to"><?=select_month(' ', $search->reg_to)?></select>��ޤ�
		</span>
	</td>
</tr>
<tr>
	<td class="m1">����в���</td>
	<td class="n1">
		<input type="checkbox" name="entry_flag" <?=checked($search->entry_from)?> onclick="show_hide('entry_c', checked)">���ꤹ��
		<span id="entry_c">&nbsp;
			���񤫤�
			<input type="text" name="entry_from" size=4 <?=value($search->entry_from)?>>�����
			<input type="text" name="entry_to" size=4 <?=value($search->entry_to)?>>����������ޤ��
		</span>
	</td>
</tr>
<tr>
	<td class="m1">���ʴ�λ�β��</td>
	<td class="n1">
		<input type="checkbox" name="commit_flag" <?=checked($search->commit)?> onclick="show_hide('commit_c', checked)">���ꤹ��
		<span id="commit_c">&nbsp;
			<input type="text" name="commit" size=4 value="<?=$search->commit?>">�� �ʾ�
		</span>
	</td>
</tr>
<tr>
	<td class="m1">���ʴ�λ��̵ͭ</td>
	<td class="n1">
		<input type="checkbox" name="commit_m_flag" <?=checked($search->commit_m_y)?> onclick="show_hide('commit_m_c', checked)">���ꤹ��
		<span id="commit_m_c">&nbsp;
			<select name="commit_m_y"><?=select_year('2002', '����ʤ�', $search->commit_m_y)?></select>ǯ
			<select name="commit_m_m"><?=select_month('��', $search->commit_m_m)?></select>�� �˼��ʴ�λ����
			<span class="small">
			�ʡ����Ϥ��ʤ��פ�������롡<input type="checkbox" name="commit_inp_ol" <?=checked($search->commit_inp_ol)?>>������<input type="checkbox" name="commit_inp_gm" <?=checked($search->commit_inp_gm)?>>���ߡ�<input type="checkbox" name="commit_inp_gl" <?=checked($search->commit_inp_gl)?>>��������
			</span>
		</span>
	</td>
</tr>
<?
}
?>