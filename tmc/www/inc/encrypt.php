<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:�Ź沽�ؿ�
'******************************************************/

define('XOR_ID', 0x38c95ea1);

// ID�ΰŹ沽
function encrypt_id($id) {
	// ID��Ź沽
	$code = sprintf('%08x', $id ^ XOR_ID);

	// CRC���ղ�
	return sprintf("%04x%s", crc32($code) & 0xffff, $code);
}

// ID������
function decrypt_id($code) {
	// �����ɤ�ʬ��
	sscanf($code, '%4x%8x', &$crc, &$id);

	// CRC�Υ����å�
	if ($crc != (crc32(substr($code, 4)) & 0xffff)) {
		return 0;
	}

	// ID������
	return $id ^ XOR_ID;
}
?>