<?
/******************************************************
' System :Eco-footprint ����ڡ���
' Content:���������Ѷ��̴ؿ�
'******************************************************/

// �ո��̻��Υ��顼�ȥ�å�����
define('SIM_ALERT', '����ä��������㤦�ߤ������ո��̤�����<br><br>');

// �饸���ܥ���ɽ���������
function sim_radio_ary($name, $ary, $default, $eco = '', $prefix = '', $postfix = ' ') {
	if ($eco != '')
		$on = ' onclick="select_list(' . "'$eco'" . ', value)"';

	foreach ($ary as $value => $text)
		echo $prefix, '<nobr>', '<input type="radio" name="', $name, '" ', value_checked($value, $default), $on, '>',  $text, $postfix, '</nobr>';
}

// �ץ����������ɽ���ʿ�����
function sim_select_no($name, $min, $max, $default, $eco = '', $prefix = '', $postfix = '', $step = 1) {
	if ($eco != '')
		$on = ' onchange="select_list(' . "'$eco'" . ', value)"';

	echo "<select name='$name'$on>";
	for ($i = $min; $i <= $max; $i += $step)
		echo '<option ', value_selected($i, $default), '>', $prefix, $i, $postfix, '</option>';
	echo '</select>';
}

// �ץ����������ɽ���������
function sim_select_ary($name, &$ary, $default, $eco = '', $on = '') {
	if ($eco != '')
		$on = ' onchange="select_list(' . "'$eco'" . ', value)"';

	echo "<select name='$name'$on>";
	foreach ($ary as $value => $text)
		echo '<option ', value_selected($value, $default), '>', $text, '</option>';
	echo '</select>';
}

// ����ɽ��
function sim_number($num) {
	if ($num >= 0)
		echo number_format($num);
	else
		echo '<font color="red">', number_format($num), '</font>';
}

// ��ʨ����ˡ̾�μ���
function yuwakashi_houhou_name($key = null) {
	$ary = array(
		1 => '�ŵ��ݥå�',
		2 => '�䤫��ʥ�����',
		3 => '�䤫���IH��'
	);
	return $key == null ? $ary : $ary[$key];
}

// �ݲ���ˡ̾�μ���
function hoon_houhou_name($key = null) {
	$ary = array(
		1 => '�ŵ��ݥå�',
		2 => '��ˡ��'
	);
	return $key == null ? $ary : $ary[$key];
}

// �ݲ�����̾������
function hoon_jikan_name($key = null) {
	$ary = array(
		0  => '�ݲ����ʤ�',
		24 => '������',
		16 => '�������ʳ�',
		8  => 'ī����',
		4  => '�դΤ�'
	);
	return $key == null ? $ary : $ary[$key];
}

// ���絡����̾�μ���
function kansou_shurui_name($key = null) {
	$ary = array(
		1 => '�ŵ������ഥ�絡',
		2 => '���������ഥ�絡',
		3 => '�ŵ����������絡',
		4 => '��ͭ�ʤ�'
	);
	return $key == null ? $ary : $ary[$key];
}

// ������ˡ̾�μ���
function kansou_houhou_name($key = null) {
	$ary = array(
		1 => '���絡�ϻ��Ѥ��ʤ�',
		2 => '���絡�������������ʪ�ΰ����Τߤ�ޤ��',
		3 => '���絡�Ǥ�䴥�礵�����崳��',
		4 => '�������塢�������Τ�Τϴ��絡��Ȥ�'
	);
	return $key == null ? $ary : $ary[$key];
}

// ��˼����̾�μ���
function reibou_shurui_name($key = null) {
	$ary = array(
		1 => '��ڷ�',
		2 => '�ʥ��ͷ�'
	);
	return $key == null ? $ary : $ary[$key];
}

// ��˼����̾�μ���
function danbou_shurui_name($key = null) {
	$ary = array(
		1 => '����������ڷ�',
		2 => '��������ʥ��ͷ�',
		3 => '�����ե���ҡ�����',
		4 => '�����ե���ҡ�����',
		5 => '�������弰����˼',
		6 => '�ۥåȥ����ڥå�',
		7 => '������',
		8 => '�����˼�ʤ�'
	);
	return $key == null ? $ary : $ary[$key];
}

// �������̾�μ���
function shoumei_basho_name($key = null) {
	$ary = array(
		1 => 'LD',
		2 => '���',
		3 => '�¼�',
		4 => 'ϭ��',
		5 => '���إۡ���'
	);
	return $key == null ? $ary : $ary[$key];
}

// �Ĥ���λ�����ˡ̾�μ���
function nokoriyu_name($key = null) {
	$ary = array(
		1 => '���Ѥ��ʤ�',
		2 => '�����Τ߻���',
		3 => '�����Ȥ�����1����� '
	);
	return $key == null ? $ary : $ary[$key];
}
?>