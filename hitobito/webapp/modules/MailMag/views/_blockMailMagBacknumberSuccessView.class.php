<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * ���ޥ��Хå��ʥ�С�ɽ���ӥ塼
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/MailMag/lib/MailMagBaseView.class.php');

class _blockMailMagBacknumberSuccessView extends MailMagBaseView {
	// ���ӥ塼�¹�
	function execute() {
		$request = $this->getContext()->getRequest();

		$this->setTemplate('_blockMailMagBacknumber.html');

		$mailmag = $request->getAttribute('mailmag');

		$backnumber_ary = &$mailmag['backnumber_ary'];
		if (is_array($backnumber_ary)) {
			foreach ($backnumber_ary as $i => &$list) {
				$list['send_date'] = $this->format_datetime($list['send_date']);
				$list['bgcolor'] = $i % 2 ? '#FFFFFF' : '#E6F2FF';
			}
		}
		$this->setAttribute('mailmag', $mailmag);
   }
}
?>