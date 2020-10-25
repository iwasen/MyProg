<?php
/**
 * �ҤȤӤȎ�net
 *
 * SPAM�����å�
 *
 *
 * @package
 * @author
 * @version
 */

class HNbSpamCheck
{
	private $ngword_ary;

	// �����󥹥ȥ饯��
	public function __construct() {
		// NG��ɥޥ��������ɤ߹���
		$db = HNb::getAdodb();
		$sql = "SELECT ngw_word FROM m_ngword WHERE ngw_status=1";
		$rs = $db->Execute($sql);
		$this->ngword_ary = array();
		while ($row = $rs->FetchRow()) {
			$this->ngword_ary[] = strtoupper(mb_convert_kana($row['ngw_word'], 'rHcV'));
		}
	}

	// ���ȥ�å��Хå���Spam�����å�
	public function checkTrackbackSpam($request) {
		$title = mb_convert_encoding(strip_tags($request->getParameter('title')), 'EUC-JP', 'UTF-8, EUC-JP, SJIS');
		$url = $request->getParameter('url');
		$excerpt = mb_convert_encoding(strip_tags($request->getParameter('excerpt')), 'EUC-JP', 'UTF-8, EUC-JP, SJIS');

		// �Ѹ�Τߤ�NG
		if ($this->checkEnglish($excerpt))
			return true;

		// NG��ɤ��ޤޤ�Ƥ�����NG
		if ($this->checkNGWord($title) || $this->checkNGWord($url) || $this->checkNGWord($excerpt))
			return true;

		return false;
	}

	// ���ѿ��������ʤ�true���֤�
	private function checkEnglish($text) {
		return preg_match('/^[ -~]*$/m', $text) != 0;
	}

	// ��NG��ɤ��ޤޤ�Ƥ�����true���֤�
	private function checkNGWord($text) {
		// ���ѱѻ���Ⱦ�ѱѻ���Ⱦ�ѡ����ѥ������ʢ����ѤҤ餬��
		$convert_text = strtoupper(mb_convert_kana($text, 'rHcV'));

		// NG��ɤ��ޤޤ�Ƥ�����NG
		foreach ($this->ngword_ary as $ngword) {
			$convert_ngword = strtoupper(mb_convert_kana($ngword, 'rHcV'));

			if (strpos($convert_text, $convert_ngword) !== false)
				return true;
		}

		return false;
	}
}
?>
