<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * �ʥӥ��󥱡��� �ǿ����󥱡���ɽ���ӥ塼
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Enquete/lib/EnqueteBaseView.class.php');

class _blockNewestEnqueteSuccessView extends EnqueteBaseView
{
	// ���ӥ塼�¹�
	public function execute() {
		$request = $this->getContext()->getRequest();

		// �ƥ�ץ졼������
		$this->setTemplate('_blockNewestEnquete.html');

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
	}
}
?>