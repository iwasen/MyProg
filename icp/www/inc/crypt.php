<?
/******************************************************
' System :ICP���̥��󥯥롼�ɥե�����
' Content:�Ź沽�ؿ�
'******************************************************/

define('XOR_ID', 0x32c956a1);

// ID�ΰŹ沽
function encrypt_id($id) {
	// ID��Ź沽
	$code = sprintf('%08x', $id ^ XOR_ID);

	// CRC���ղ�
	return sprintf("%04x%s", crc32($code) & 0xffff, $code);
}

// ID������
function decrypt_id($code) {
	// Ĺ�������å�
	if (strlen($code) != 12)
		return 0;

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