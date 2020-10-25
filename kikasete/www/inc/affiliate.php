<?
/******************************************************
' System :きかせて・net共通
' Content:アフィリエイト関連
'******************************************************/

// アフィリエイト種別
define('AFFILIATE_WEB', 1);			// Web
define('AFFILIATE_MAIL', 2);		// メール

// アフィリエイト種別名取得
function decode_affiliate_type($code) {
	switch ($code) {
	case AFFILIATE_WEB:
		return 'Web';
	case AFFILIATE_MAIL:
		return 'メール';
	}
}
?>