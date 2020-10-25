<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * �磻�磻��ļ� ��ǧ������ꥹ�ȥӥ塼
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseView.class.php');

class EntryListSuccessView extends WaiwaiBaseView {
	// ���ӥ塼�¹�
	function execute() {
		$request = $this->getContext()->getRequest();

		// �ƥ�ץ졼������
		$this->setTemplate('EntryList.html');

		$waiwai_entry_list_ary = array();
		$entry_list_ary = $request->getAttribute('waiwai_entry_list_ary');
		if (is_array($entry_list_ary)) {
			$user_page_url = $request->getAttribute('user_page_url');

			$seq_no = count($entry_list_ary);
			foreach ($entry_list_ary as $entry_list) {
				$entry_list['last_date'] = $this->format_date($entry_list['last_date'], '-');
				$entry_list['image_url'] = "$user_page_url/image.php?id={$entry_list['image_id']}";
				$entry_list['seq_no'] = $seq_no--;
				$entry_list['mail_send_flag'] = $entry_list['mail_send_flag'];

				$waiwai_entry_list_ary[] = $entry_list;
			}
		}
		$this->setAttribute('waiwai_entry_list_ary', $waiwai_entry_list_ary);

		$this->setAttribute('waiwai_msg', $request->getAttribute('waiwai_msg'));

		// ���顼��å���������
		$this->setAttribute('waiwai_error_ary', $request->getErrors());

		// �ᥤ��ƥ�ץ졼��
		$this->useMainTemplate();
	}
}

?>