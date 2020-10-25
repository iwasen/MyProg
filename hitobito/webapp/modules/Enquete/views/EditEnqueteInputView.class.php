<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * �ʥӥ��󥱡��� ���󥱡����Խ����ϥӥ塼
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Enquete/lib/EnqueteBaseView.class.php');

class EditEnqueteInputView extends EnqueteBaseView {
	// ���ӥ塼�¹�
	public function execute() {
		$request = $this->getContext()->getRequest();

		// �ƥ�ץ졼������
		$this->setTemplate('EditEnqueteInput.html');

		// ���󥱡�����Ͽ����
		$enquete_data = $request->getAttribute('enquete_data');
		$this->setYMD($enquete_data, 'start_date');
		$this->setYMD($enquete_data, 'end_date');

		// ���󥱡�����������
		$enquete_option = $enquete_data['option'];
		$option_ary = array();
		$option_no = 1;
		if (is_array($enquete_option)) {
			foreach ($enquete_option as $option) {
				$option_ary[$option_no++] = $option['option_text'];
			}
		}
		while ($option_no <= 9)
			$option_ary[$option_no++] = '';
		$enquete_data['option_ary'] = $option_ary;
		$this->setAttribute('enquete_data', $enquete_data);

		// ���������
		$this->setAttribute('enquete_year_options', $this->getYearOptions());
		$this->setAttribute('enquete_month_options', $this->getMonthOptions());
		$this->setAttribute('enquete_day_options', $this->getDayOptions());

		// ���顼��å���������
		$this->setAttribute('enquete_error_ary', $request->getErrors());

		// �ᥤ��ƥ�ץ졼��
		$this->useMainTemplate();
	}
}

?>