<?
/******************************************************
' System :Eco-footprint ����ڡ���
' Content:���ͥ륮����Ĺ�����
'******************************************************/

function eng_comment($eng_type, $month, &$use, &$comp, &$rank) {
	// ̤���ѡ�rank��"08"�ˤλ���ɽ�����ʤ�
	if ($rank[$eng_type] == '08') {
		echo '<br>';
		return;
	}

	// ������
	$comp_abs = abs($comp[$eng_type]);

	// �����������ä�
	if ($comp[$eng_type] > 0) {
		$up_down = '������';
		$color = 'red';
	} else {
		$up_down = '���ä�';
		$color = 'blue';
	}

	// ���ͥ륮�����̤��ȤΥ�����ɽ��
	switch ($eng_type) {
	case 'ele':
		$gobi1 = '������';
		$gobi2 = '������';
		$gobi3 = '���ä�������';
		$unit = 'kwh';
		break;
	case 'gas':
		$gobi1 = '�Ǥ���ޤ���';
		$gobi2 = '�Ǥ���ޤ���';
		$gobi3 = '�Ǥ���ޤ�����';
		$unit = 'm3';
		break;
	case 'wtr':
		$gobi1 = '�衣';
		$gobi2 = '�';
		$gobi3 = '���ä���衪';
		$unit = 'm3';
		break;
	case 'oil':
		$gobi1 = '���ʡ�';
		$gobi2 = '���ʡ�';
		$gobi3 = '���ä����ʡ�';
		$unit = 'L';
		break;
	case 'gso':
		$gobi1 = '������';
		$gobi2 = '������';
		$gobi3 = '���ä�������';
		$unit = 'L';
		break;
	case 'dst':
		$gobi1 = '�ǥ��ǥ���';
		$gobi2 = '�ǥ��ǥ���';
		$gobi3 = '���ä��ǥ��ǥ���';
		$unit = 'kg';
		break;
	}

	if ($comp_abs != 0)
		echo "{$month}��λ����̤�&nbsp;{$use[$eng_type]}{$unit}&nbsp;{$gobi1}<br>��ǯ����٤��&nbsp;<font color='$color'><b>{$comp_abs}{$unit}</b>&nbsp;{$up_down}</font>{$gobi2}<br>";
	else
		echo "��ǯ��Ʊ��{$gobi3}<br><br>";
}
?>