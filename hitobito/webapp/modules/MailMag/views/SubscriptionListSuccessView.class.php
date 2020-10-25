<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * ���ɥ��ޥ���Ͽ/����ӥ塼
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/MailMag/lib/MailMagBaseView.class.php');

class SubscriptionListSuccessView extends MailMagBaseView {
	// ���ӥ塼�¹�
	function execute(){
		$request = $this->getContext()->getRequest();

		// �ƥ�ץ졼������
		$this->setTemplate('SubscriptionList.html');

		$mailmag_subscription_list_ary = array();
		$mailmag_list_ary = $request->getAttribute('mailmag_subscription_list_ary');
		if (is_array($mailmag_list_ary)) {
			$user_page_url = $request->getAttribute('user_page_url');

			$seq_no = count($mailmag_list_ary);
			foreach ($mailmag_list_ary as $mailmag_list) {
				$mailmag_list['last_publish_date'] = $this->format_date($mailmag_list['last_publish_date'], '-');
				$mailmag_list['image_url'] = "$user_page_url/image.php?id={$mailmag_list['image_id']}";
				$mailmag_list['reader_cnt'] = number_format($mailmag_list['reader_cnt']);
				$mailmag_list['seq_no'] = $seq_no--;

				$mailmag_subscription_list_ary[] = $mailmag_list;
			}
		}
		$this->setAttribute('mailmag_subscription_list_ary', $mailmag_subscription_list_ary);

		$this->setAttribute('mailmag_msg', $request->getAttribute('mailmag_msg'));

		// �ᥤ��ƥ�ץ졼��
		$this->useMainTemplate();
	 }
}
?>