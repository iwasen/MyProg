<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * ���ޥ����� �ۿ����ޥ�����ɽ�����������
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/MailMag/lib/MailMagBaseAction.class.php');

class ShowClickAction extends MailMagBaseAction {
	// �����������¹�
	public function execute() {
		$controller = $this->getContext()->getController();
		$request = $this->getContext()->getRequest();

		$contents_id = $request->getParameter('contents_id');
		$contents = $this->db->getClickContents($contents_id);

		$contents['contents'] = $this->getClickCount($contents['body']);

		$request->setAttribute('mailmag_contents', $contents);

		return View::SUCCESS;
	}

	// �����������¹ԥ᥽�åɻ���
	public function getRequestMethods() {
		return Request::GET | Request::POST;
	}

	// ������å������󥿼���
	private function getClickCount($body) {
		$click_ary = array();

		$total = 0;

		foreach (explode("\n", $body) as $text) {
			unset($tmp);

			$tmp['text'] = $text;

			if (preg_match('/\/mc.php\?([0-9]+)/', $text, $matches)) {
				$click = $this->db->getClickCounter($matches[1]);
				$tmp['click'] = $click;
				$total += $click;
			}

			$click_ary[] = $tmp;
		}

		foreach ($click_ary as &$click) {
			if (isset($click['click']))
				$click['percent'] = $total ? round($click['click'] / $total * 100) : 0;
		}

		return $click_ary;
	}

	// ��ǧ�ڤ��׵�
	public function isSecure() {
		return true;
	}

	// ���ʥ��Խ��ڡ���
	protected function isNaviEditPage() {
		return true;
	}
}
?>