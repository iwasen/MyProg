<?php
/**
 * ひとびと･net 共通ファイル
 *
 * NGワード処理関数
 *
 *
 * @package
 * @author
 * @version
 */

// ■NGワード存在チェック
function check_ngword($text) {
	// 全角英字→半角英字、半角／全角カタカナ→全角ひらがな
	$check_text = strtoupper(mb_convert_kana($text, 'rHcV'));

	$ngword_ary = array();

	$sql = "SELECT ngw_ngword_id,ngw_word FROM m_ngword WHERE ngw_status=1 ORDER BY ngw_ngword_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		if (strpos($check_text, strtoupper(mb_convert_kana($fetch->ngw_word, 'rHcV'))) !== false)
			$ngword_ary[] = $fetch->ngw_ngword_id;
	}

	return join(',', $ngword_ary);
}

// ■NGワードデコード
function decode_ngword($code, $separator = ' ') {
	$ngword_ary = array();

	if ($code != '') {
		$sql = "SELECT ngw_word FROM m_ngword WHERE ngw_ngword_id IN ($code) ORDER BY ngw_ngword_id";
		$result = db_exec($sql);
		$nrow = pg_numrows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);

			$ngword_ary[] = $fetch->ngw_word;
		}
	}

	return join($separator, $ngword_ary);
}
?>