<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * �ʥӥ��󥱡��� ���󥱡��Ȳ������ϥӥ塼
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Enquete/lib/EnqueteBaseView.class.php');

class AnswerEnqueteInputView extends EnqueteBaseView {
	// ���ӥ塼�¹�
	public function execute() {
		$request = $this->getContext()->getRequest();

		// �ƥ�ץ졼������
		$this->setTemplate('AnswerEnqueteInput.html');

		// ���󥱡�����Ͽ����
		$enquete_data = $request->getAttribute('enquete_data');
		$enquete_data['start_date'] = $this->format_date($enquete_data['start_date']);
		$enquete_data['end_date'] = $this->format_date($enquete_data['end_date']);

		// ���󥱡�����������
		$enquete_option = $enquete_data['option'];
		$option_ary = array();
		if (is_array($enquete_option)) {
			foreach ($enquete_option as $option) {
				$option['bgcolor'] = $option['option_no'] % 2 ? '#E6F2FF' : '#FFFFFF';
				$option['input_type'] = $enquete_data['question_type'] == 1 ? 'radio' : 'checkbox';
				$option_ary[] = $option;
			}
		}
		$enquete_data['option_ary'] = $option_ary;
		$this->setAttribute('enquete_data', $enquete_data);

		// ���顼��å���������
		$this->setAttribute('enquete_error_ary', $request->getErrors());

		// �ᥤ��ƥ�ץ졼��
		$this->useMainTemplate();
	}
}

?>