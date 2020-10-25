<?php
/**
 * �ҤȤӤȎ�net ���̥ե�����
 *
 * �����ɽ������
 *
 *
 * @package
 * @author
 * @version
 */

// <option>��������
function option_tag($value, $selected, $text) {
	$attr = value_selected($value, $selected);
	return "<option $attr>$text</option>\n";
}

// ɽ���Կ�
function select_displine($selected) {
	echo '<option ', value_selected(0, $selected), ">- ���� -</option>\n";
	for ($i = 10; $i <= 100; $i += 10)
		echo '<option ', value_selected($i, $selected), ">$i</option>\n";
}

// ǯ
function select_year($start_year, $default, $selected) {
	if ($default)
		$tag = option_tag('', $selected, $default);

	$end_year = date('Y') + 8;
	for ($i = $start_year; $i < $end_year; $i++)
		$tag .= option_tag($i, $selected, $i . 'ǯ');

	return $tag;
}

// ��
function select_month($default, $selected) {
	if ($default)
		$tag = option_tag('', $selected, $default);

	for ($i = 1; $i <= 12; $i++)
		$tag .= option_tag($i, $selected, $i . '��');

	return $tag;
}

// ��
function select_day($default, $selected) {
	if ($default)
		$tag = option_tag('', $selected, $default);

	for ($i = 1; $i <= 31; $i++)
		$tag .= option_tag($i, $selected, $i . '��');

	return $tag;
}

// ǯ��(YYYYMM)
function select_year_month($default, $selected) {
	$start_ym = '200507';
	$end_ym = date('Ym');

	if ($default)
		$tag = option_tag('', $selected, $default);

	for ($ym = $start_ym; $ym <= $end_ym; $ym = sprintf('%04d%02d', $y, $m)) {
		sscanf($ym, '%4d%2d', &$y, &$m);
		$tag .= option_tag($ym, $selected, sprintf('%d/%d', $y, $m));

		if (++$m > 12) {
			$y++;
			$m = 1;
		}
	}

	return $tag;
}

// ����
function select_hour($default, $selected) {
	if ($default)
		$tag = option_tag('', $selected, $default);

	for ($i = 0; $i < 24; $i++) {
		if ($i < 12)
			$tag .= option_tag($i, $selected, 'AM ' . $i);
		else
			$tag .= option_tag($i, $selected, 'PM ' . ($i - 12));
	}

	return $tag;
}

// ��ƻ�ܸ�
function select_todofuken($default, $selected) {
	if ($default)
		$tag = option_tag('', $selected, $default);

	$sql = "SELECT tdf_area_id,tdf_name FROM m_todofuken ORDER BY tdf_area_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$tag .= option_tag($fetch->tdf_area_id, $selected, htmlspecialchars($fetch->tdf_name));
	}

	return $tag;
}

// ����
function select_shokugyo($default, $selected) {
	if ($default)
		$tag = option_tag('', $selected, $default);

	$sql = "SELECT skg_shokugyo_id,skg_shokugyo_name FROM m_shokugyo ORDER BY skg_shokugyo_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$tag .= option_tag($fetch->skg_shokugyo_id, $selected, htmlspecialchars($fetch->skg_shokugyo_name));
	}

	return $tag;
}

// ���С����ơ�����
function select_member_status($default, $selected) {
	if ($default)
		$tag = option_tag('', $selected, $default);

	$tag .= option_tag('0', $selected, '����Ͽ')
				. option_tag('1', $selected, 'ͭ��')
				. option_tag('2', $selected, '��ã����')
				. option_tag('3', $selected, '������')
				. option_tag('9', $selected, '���');

	return $tag;
}

// ML���С����ơ�����
function select_member_ml_status($default, $selected) {
	if ($default)
		$tag = option_tag('', $selected, $default);

	$tag .= option_tag('2', $selected, '��������Ͽ')
				. option_tag('3', $selected, '����Ͽ')
				. option_tag('9', $selected, '���');

	return $tag;
}

// �ʥӥ��ơ�����
function select_navi_status($selected) {
	$tag  = option_tag('0', $selected, '̤��ǧ')
				. option_tag('1', $selected, '��ǧ')
				. option_tag('2', $selected, '����')
				. option_tag('9', $selected, '���');

	return $tag;
}

// ����ͥ�
function select_channel($default, $selected) {
	if ($default)
		$tag = option_tag('', $selected, $default);

	$sql = "SELECT chn_channel_id, chn_channel_name FROM m_channel WHERE chn_status = 1 ORDER BY chn_order";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$tag .= option_tag($fetch->chn_channel_id, $selected, htmlspecialchars($fetch->chn_channel_name));
	}

	return $tag;
}

// ���֥���ͥ�
function select_sub_channel($default, $selected) {
	if ($default)
		$tag = option_tag('', $selected, $default);

	$sql = "SELECT sch_subchannel_id, sch_subchannel_name FROM m_sub_channel WHERE sch_status = 1 ORDER BY sch_order";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$tag .= option_tag($fetch->sch_subchannel_id, $selected, htmlspecialchars($fetch->sch_subchannel_name));
	}

	return $tag;
}

// �ʥ�̾
function select_navi_name($default, $selected) {
	if ($default)
		$tag = option_tag('', $selected, $default);

	$sql = "SELECT nav_navi_id, nav_name1, nav_name2 FROM t_navi ORDER BY nav_navi_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
echo $fetch->nav_name;
		$tag .= option_tag($fetch->nav_navi_id, $selected, htmlspecialchars(trim("$fetch->nav_name1 $fetch->nav_name2")));
	}

	return $tag;
}

// �ҤȤӤ��ؤ��ۿ�����
function select_hitobito_letter($default, $selected) {
	if ($default)
		$tag = option_tag('', $selected, $default);

	$tag .= option_tag('0', $selected, '�ۿ����')
				. option_tag('1', $selected, '�ۿ���');

	return $tag;
}

// �ʥӥڡ��������ե饰
function select_navi_open_flag($default, $selected) {
	if ($default)
		$tag = option_tag('', $selected, $default);

	$tag .= option_tag('0', $selected, '̤����')
				. option_tag('1', $selected, '������')
				. option_tag('2', $selected, '������');

	return $tag;
}

// Blog/ML/���ޥ������ե饰
function select_open_status($default, $selected) {
	if ($default)
		$tag = option_tag('', $selected, $default);

	$tag .= option_tag('0', $selected, '�����')
				. option_tag('1', $selected, '������');

	return $tag;
}

// �磻�磻���
function select_room($default, $selected) {
	if ($default)
		$tag = option_tag('', $selected, $default);

	$sql = "SELECT rom_room_id, rom_name FROM t_room WHERE rom_status IN (1,2) ORDER BY rom_room_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$tag .= option_tag($fetch->rom_room_id, $selected, htmlspecialchars($fetch->rom_name));
	}

	return $tag;
}

// pickup
function select_pickup($default, $selected) {
	if ($default)
		$tag = option_tag('', $selected, $default);

	$tag .= option_tag('1', $selected, 'pickup');

	return $tag;
}

// �ʥӥ��쥯��
function select_naviselect($default, $selected) {
	if ($default)
		$tag = option_tag('', $selected, $default);

	$tag .= option_tag('1', $selected, '�ʥӥ��쥯��');

	return $tag;
}

// ��̳�ɥ����å�����
function select_admin_check($default, $selected) {
	if ($default)
		$tag = option_tag('', $selected, $default);

	$tag .= option_tag('1', $selected, '̤�����å�')
				. option_tag('2', $selected, '����ʤ�')
				. option_tag('3', $selected, '���ꤢ��');

	return $tag;
}

// �ѥȥ���������å�����
function select_patrol_check($default, $selected) {
	return select_admin_check($default, $selected);
}

// ������ʾ�
function select_over_count($default, $selected) {
	if ($default)
		$tag = option_tag('', $selected, $default);

	$tag .= option_tag('0', $selected, '0');
	$tag .= option_tag('1-', $selected, '1-');
	$tag .= option_tag('5-', $selected, '5-');
	$tag .= option_tag('10-', $selected, '10-');
	$tag .= option_tag('20-', $selected, '20-');
	$tag .= option_tag('50-', $selected, '50-');
	$tag .= option_tag('100-', $selected, '100-');
	$tag .= option_tag('200-', $selected, '200-');
	$tag .= option_tag('500-', $selected, '500-');
	$tag .= option_tag('1000-', $selected, '1000-');

	return $tag;
}

// ���ޥ��ۿ����ơ�����
function select_melmaga_send_status($default, $selected) {
	if ($default)
		$tag = option_tag('', $selected, $default);

	$tag .= option_tag('1', $selected, '̤�ۿ�')
				. option_tag('2', $selected, '�ۿ���');

	return $tag;
}

// ��Ƽ��̡���ʸ/������/�ȥ�å��Хå���
function select_remark_kind($default, $selected) {
	if ($default)
		$tag = option_tag('', $selected, $default);

	$tag .= option_tag('1', $selected, '��ʸ')
				. option_tag('3', $selected, '�ȥ�å��Хå�')
				. option_tag('2', $selected, '������');

	return $tag;
}

// NG���
function select_ngword($default, $selected) {
	if ($default)
		$tag = option_tag('', $selected, $default);

	$tag .= option_tag('1', $selected, 'NG̵��')
				. option_tag('2', $selected, 'NGͭ��');

	return $tag;
}

// �ʥӥڡ���̾
function select_navi_page_title($default, $selected, $channel_id = '') {
	if ($default)
		$tag = option_tag('', $selected, $default);

	$sql = "SELECT nvp_navi_page_id, nvp_title"
			. " FROM t_navi_page"
			. " WHERE nvp_status = 1 AND nvp_open_flag=1" . ($channel_id ? " AND nvp_channel_id=$channel_id" : '')
			. " ORDER BY nvp_navi_page_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$tag .= option_tag($fetch->nvp_navi_page_id, $selected, htmlspecialchars($fetch->nvp_title));
	}

	return $tag;
}

// �磻�磻��ĥ��ơ�����
function select_room_status($default, $selected) {
	if ($default)
		$tag = option_tag('', $selected, $default);

	$tag .= option_tag('1', $selected, '����')
				. option_tag('2', $selected, '�����')
				. option_tag('9', $selected, '�ĺ�');

	return $tag;
}

?>