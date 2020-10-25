<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * ���ޥ����� �Хå��ʥ�С�����ƥ��ɽ���ӥ塼
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/MailMag/lib/MailMagBaseView.class.php');

class BacknumberContentsSuccessView extends MailMagBaseView {
	// ���ӥ塼�¹�
	function execute(){
		$request = $this->getContext()->getRequest();

		$this->setTemplate('BacknumberContents.html');

		$mailmag = $request->getAttribute('mailmag');
		$mailmag['publish_count'] = number_format($mailmag['publish_count']);
		$mailmag['reader_num'] = number_format($mailmag['reader_num']);
		$mailmag['last_date'] = $this->format_datetime($mailmag['last_date']);

		$contents = &$mailmag['contents'];
		$contents['send_num'] = number_format($contents['send_num']);
		$contents['send_date'] = $this->format_datetime($contents['send_date']);

		$comment_ary = &$mailmag['comment_ary'];
		if (is_array($comment_ary)) {
			foreach ($comment_ary as &$list) {
				$list['date'] = $this->format_datetime($list['date']);
			}
		}

		$trackback_ary = &$mailmag['trackback_ary'];
		if (is_array($trackback_ary)) {
			foreach ($trackback_ary as &$list) {
				$list['date'] = $this->format_datetime($list['date']);
			}
		}

		$this->setAttribute('mailmag', $mailmag);

		if ($request->getAttribute('mailmag_comment_ok'))
			$this->setAttribute('mailmag_comment_msg', '��������Ƥ��꤬�Ȥ��������ޤ�����');
		if ($request->getAttribute('mailmag_comment_ng'))
			$this->setAttribute('mailmag_comment_msg', '��Ƥ˺ݤ����Ϥ򤴱�θ���������������դ��Ϥ��äƤ��뤿�ᡢ����Ƥ�ȿ�ǤǤ��ޤ���Ǥ�����');

		// ¿����Ƥ��ɻߥե饰����
		$this->setAttribute('mailmag_comment_time', $request->getAttribute('mailmag_comment_time'));

		// ���顼��å���������
		$this->setAttribute('mailmag_error_ary', $request->getErrors());

		$this->useMainTemplate();
	}
}
?>