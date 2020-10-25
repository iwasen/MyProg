<?php
/**
 * ひとびと･net 共通ファイル
 *
 * 暗号化関数
 *
 *
 * @package
 * @author
 * @version
 */

define('XOR_ID', 0x27c9b6a3);

// IDの暗号化
function encrypt_id($id) {
	// IDを暗号化
	$code = sprintf('%08x', $id ^ XOR_ID);

	// CRCを付加
	return sprintf("%04x%s", crc32($code) & 0xffff, $code);
}

// IDの復号
function decrypt_id($code) {
	// 長さチェック
	if (strlen($code) != 12)
		return 0;

	// コードを分解
	sscanf($code, '%4x%8x', &$crc, &$id);

	// CRCのチェック
	if ($crc != (crc32(substr($code, 4)) & 0xffff)) {
		return 0;
	}

	// IDを復号
	return $id ^ XOR_ID;
}
?>