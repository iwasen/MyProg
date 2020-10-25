<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * �ʥӥ��󥱡��� ���󥱡��ȷ�����ϥӥ塼
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Enquete/lib/EnqueteBaseView.class.php');

class ShowResultInputView extends EnqueteBaseView {
	// ���ӥ塼�¹�
	public function execute() {
		$request = $this->getContext()->getRequest();

		// �ƥ�ץ졼������
		$this->setTemplate('ShowResultInput.html');

		// ���󥱡�����Ͽ����
		$enquete_data = $request->getAttribute('enquete_data');
		$enquete_data['start_date'] = $this->format_date($enquete_data['start_date']);
		$enquete_data['end_date'] = $this->format_date($enquete_data['end_date']);

		// ���󥱡�����������
		$enquete_option = $enquete_data['option'];
		$option_ary = array();
		if (is_array($enquete_option)) {
			foreach ($enquete_option as $option) {
				$answer_count = $enquete_data['answer_count'];
				$option['bgcolor'] = $option['option_no'] % 2 ? '#E6F2FF' : '#FFFFFF';
				$option['percent'] = $answer_count ? round($option['answer_count'] / $answer_count * 100) : 0;
				$option['bar_width'] = $answer_count ? round($option['answer_count'] / $answer_count * 200) : 0;
				$option_ary[] = $option;
			}
		}
		$enquete_data['option_ary'] = $option_ary;
		$this->setAttribute('enquete_data', $enquete_data);

		// �����Ⱦ�������
		$enquete_comment = $request->getAttribute('enquete_comment');
		$comment_log = $enquete_comment['log'];
		$comment_num = 0;
		$comment_log_ary = array();
		if (is_array($comment_log)) {
			foreach ($comment_log as $comment) {
				$comment['date'] = $this->format_datetime($comment['date']);
				$comment_log_ary[] = $comment;
				$comment_num++;
			}
		}
		$enquete_comment['comment_num'] = $comment_num;
		$enquete_comment['comment_log_ary'] = $comment_log_ary;
		$this->setAttribute('enquete_comment', $enquete_comment);

		// �ȥ�å��Хå���������
		$enquete_trackback = $request->getAttribute('enquete_trackback');
		$trackback_log = $enquete_trackback['log'];
		$trackback_num = 0;
		$trackback_log_ary = array();
		if (is_array($trackback_log)) {
			foreach ($trackback_log as $trackback) {
				$trackback['date'] = $this->format_datetime($trackback['date']);
				$trackback_log_ary[] = $trackback;
				$trackback_num++;
			}
		}
		$enquete_trackback['trackback_num'] = $trackback_num;
		$enquete_trackback['trackback_log_ary'] = $trackback_log_ary;
		$this->setAttribute('enquete_trackback', $enquete_trackback);

		// ������å���������
		if ($request->getAttribute('enquete_answer_ok'))
			$this->setAttribute('enquete_answer_msg', '���󥱡��Ȳ������꤬�Ȥ��������ޤ�����');
		if ($request->getAttribute('enquete_comment_ok'))
			$this->setAttribute('enquete_answer_msg', '��������Ƥ��꤬�Ȥ��������ޤ�����');
		if ($request->getAttribute('enquete_comment_ng'))
			$this->setAttribute('enquete_answer_msg', '��Ƥ˺ݤ����Ϥ򤴱�θ���������������դ��Ϥ��äƤ��뤿�ᡢ<br>����Ƥ�ȿ�ǤǤ��ޤ���Ǥ�����');

		// ¿����Ƥ��ɻߥե饰����
		$this->setAttribute('enquete_comment_flag', $request->getAttribute('enquete_comment_flag'));

		// ���顼��å���������
		$this->setAttribute('enquete_error_ary', $request->getErrors());

		// �ᥤ��ƥ�ץ졼��
		$this->useMainTemplate();
	}
}

?>