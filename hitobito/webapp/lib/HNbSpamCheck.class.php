<?php
/**
 * ひとびと･net
 *
 * SPAMチェック
 *
 *
 * @package
 * @author
 * @version
 */

class HNbSpamCheck
{
	private $ngword_ary;

	// ■コンストラクタ
	public function __construct() {
		// NGワードマスタから読み込み
		$db = HNb::getAdodb();
		$sql = "SELECT ngw_word FROM m_ngword WHERE ngw_status=1";
		$rs = $db->Execute($sql);
		$this->ngword_ary = array();
		while ($row = $rs->FetchRow()) {
			$this->ngword_ary[] = strtoupper(mb_convert_kana($row['ngw_word'], 'rHcV'));
		}
	}

	// ■トラックバックのSpamチェック
	public function checkTrackbackSpam($request) {
		$title = mb_convert_encoding(strip_tags($request->getParameter('title')), 'EUC-JP', 'UTF-8, EUC-JP, SJIS');
		$url = $request->getParameter('url');
		$excerpt = mb_convert_encoding(strip_tags($request->getParameter('excerpt')), 'EUC-JP', 'UTF-8, EUC-JP, SJIS');

		// 英語のみはNG
		if ($this->checkEnglish($excerpt))
			return true;

		// NGワードが含まれていたらNG
		if ($this->checkNGWord($title) || $this->checkNGWord($url) || $this->checkNGWord($excerpt))
			return true;

		return false;
	}

	// ■英数字だけならtrueを返す
	private function checkEnglish($text) {
		return preg_match('/^[ -~]*$/m', $text) != 0;
	}

	// ■NGワードが含まれていたらtrueを返す
	private function checkNGWord($text) {
		// 全角英字→半角英字、半角／全角カタカナ→全角ひらがな
		$convert_text = strtoupper(mb_convert_kana($text, 'rHcV'));

		// NGワードが含まれていたらNG
		foreach ($this->ngword_ary as $ngword) {
			$convert_ngword = strtoupper(mb_convert_kana($ngword, 'rHcV'));

			if (strpos($convert_text, $convert_ngword) !== false)
				return true;
		}

		return false;
	}
}
?>
