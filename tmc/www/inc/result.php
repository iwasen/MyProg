<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:���Ѥ������Ϣ
'******************************************************/

// ����¾�ؿ��Τ����
function select_sonota_kanshin($default, $selected) {
	if ($default)
		option_tag('', $selected, $default);

	option_tag('1', $selected, '����');
	option_tag('2', $selected, '�ʤ�');
	option_tag('3', $selected, '����');
}

// �ؿ�����᡼����
function select_kanshin_maker($default, $selected) {
	if ($default)
		option_tag('', $selected, $default);

	option_tag('1', $selected, '�ȥ西');
	option_tag('2', $selected, '����');
	option_tag('3', $selected, '�ۥ��');
	option_tag('4', $selected, '��ɩ');
	option_tag('5', $selected, '�ޥĥ�');
	option_tag('6', $selected, '���Х�');
	option_tag('7', $selected, '������');
	option_tag('8', $selected, '�����ϥ�');
	option_tag('9', $selected, '������');
	option_tag('0', $selected, '����¾');
}

// �������٥��Ͷ��
function select_jishuu_enent($default, $selected) {
	if ($default)
		option_tag('', $selected, $default);

	option_tag('1', $selected, '�����åեե�������');
	option_tag('2', $selected, '�������դΤߵ���');
	option_tag('3', $selected, '��������');
	option_tag('4', $selected, '�ؿ�̵��');
}

// �����󶡤η�³
function select_teikyou_keizoku($default, $selected) {
	if ($default)
		option_tag('', $selected, $default);

	option_tag('1', $selected, '����');
	option_tag('2', $selected, '��˾����');
}

// ��Ź�ؤ���˾
function select_mise_youbou($default, $selected) {
	if ($default)
		option_tag('', $selected, $default);

	option_tag('1', $selected, '����');
	option_tag('2', $selected, '�ʤ�');
}

// �����ʬ
function select_kecchaku($default, $selected) {
	if ($default)
		option_tag('', $selected, $default);

	option_tag('3', $selected, '�ꥹ������');
	option_tag('6', $selected, '�ͣ���');
}

// ���
function select_rank($default, $selected) {
	if ($default)
		option_tag('', $selected, $default);

	option_tag('S', $selected, 'S�����̤��Ǥ���');
	option_tag('A', $selected, 'A�����̤ˤĤʤ���');
	option_tag('B', $selected, 'B������ˤĤʤ���');
	option_tag('D', $selected, 'D�����䤫�ʷ�³�ե���λ��');
	option_tag('E', $selected, 'E���ե�������');
	option_tag('J', $selected, 'J�����ҵ�');
	option_tag('M', $selected, 'M���ե������');
}

// ��������
function select_kounyuu_joukyou($default, $selected) {
	if ($default)
		option_tag('', $selected, $default);

	option_tag('1', $selected, '����');
	option_tag('2', $selected, '�ȥ西¾�����ͥ�');
	option_tag('3', $selected, '¾�᡼����');
}
?>