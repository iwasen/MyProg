<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * �磻�磻��ļ� �磻�磻��ļ��Խ���˥塼�ӥ塼
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseView.class.php');

class EditMenuInputView extends WaiwaiBaseView {
	// ���ӥ塼�¹�
	function execute() {
		$request = $this->getContext()->getRequest();

		// �ƥ�ץ졼������
		$this->setTemplate('EditMenuInput.html');

		// ���Τ餻��å�����
		$waiwai_notice_ary = array();
		$waiwai_notice_flag = $request->getAttribute('waiwai_notice_flag');
		if ($waiwai_notice_flag['guest_member'] == 't')
			$waiwai_notice_ary[] = '��������Ͽ���С������ޤ�����ǧ��Ȥ�ԤʤäƤ���������';
//		if ($waiwai_notice_flag['public_post'] == 't')
//			$waiwai_notice_ary[] = '���̥桼�������������Ԥ�������ޤ�����ǧ��Ȥ�ԤʤäƤ���������';
		$this->setAttribute('waiwai_notice_ary', $waiwai_notice_ary);
		$this->setAttribute('waiwai_notice_flag', $waiwai_notice_flag);

		// �ᥤ��ƥ�ץ졼��
		$this->useMainTemplate();
	}
}

?>