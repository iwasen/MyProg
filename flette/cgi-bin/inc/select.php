<?
/******************************************************
' System :flette�����åեڡ���
' Content:�����ɽ������
'******************************************************/

// <option>��������
function option_tag($value, $selected, $display) {
	echo '<option ', value_selected($value, $selected), '>', $display, '</option>', "\n";
}

// ��������
function select_hour($selected) {
	option_tag('', $selected, '--');
	for ($i = 0; $i < 24; $i++) {
		$s = sprintf('%02d', $i);
		option_tag($s, $selected, $s);
	}
}

// ʬ����
function select_minute($selected) {
	option_tag('', $selected, '--');
	for ($i = 0; $i < 60; $i += 5) {
		$s = sprintf('%02d', $i);
		option_tag($s, $selected, $s);
	}
}

// �����åո���
function select_staff_auth($selected) {
	if ($selected == '')
		option_tag('', $selected, '���򤷤Ƥ�������');

	option_tag('1', $selected, '�����ԥ����å�');
	option_tag('2', $selected, 'Ź�ޥ����å�');
}

// �����åե��ơ�����
function select_staff_status($selected) {
	option_tag('1', $selected, 'ͭ��');
	option_tag('9', $selected, '̵��');
}
?>