<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * �磻�磻��ļ� �᡼��إå����եå��Խ����������
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseAction.class.php');

class EditHeaderAction extends WaiwaiBaseAction {
	// �����������¹�
	public function execute() {
		$request = $this->getContext()->getRequest();

		switch ($this->getSubmitButton()) {
		case 'waiwai_update_btn':	// ��Ͽ���󹹿�
			// �᡼��إå����եå�����
			$header = $this->getParameters();
			$this->db->updateHeaderInfo($this->room_id, $header);
			break;
		case 'waiwai_preview_btn':	// �ץ�ӥ塼
			break;
		case 'waiwai_default_btn':	// �ǥե���Ȥ��᤹
			// �᡼��إå����եå�����
			$request->setAttribute('waiwai_header', $this->db->getDefaultHeader($this->room_id, $this->member_id));

			// ���������ѿ�����
			$request->setAttribute('waiwai_keyword', $this->getkeyword());
			return View::INPUT;
		}

		return View::SUCCESS;
	}

	// ���ǥե���ȥӥ塼ɽ��
	public function getDefaultView() {
		$request = $this->getContext()->getRequest();

		// �᡼��إå����եå�����
		$request->setAttribute('waiwai_header', $this->db->getHeaderInfo($this->room_id));

		// ���������ѿ�����
		$request->setAttribute('waiwai_keyword', $this->getkeyword());

		return View::INPUT;
	}

	// �����������¹ԥ᥽�åɻ���
	public function getRequestMethods() {
		return Request::POST;
	}

	// ��Validator��Ͽ
	public function registerValidators($validatorManager) {
		switch ($this->getSubmitButton()) {
		case 'waiwai_update_btn':	// ��Ͽ���󹹿�
		case 'waiwai_preview_btn':	// �ץ�ӥ塼
			$validatorManager->registerName('waiwai_header', TRUE, '�إå���̤���ϤǤ���');
			$validatorManager->registerName('waiwai_footer', TRUE, '�եå���̤���ϤǤ���');
			break;
		}
	}

	// �����ϥ��顼����
	public function handleError() {
		$request = $this->getContext()->getRequest();

		// �᡼��إå����եå�����
		$request->setAttribute('waiwai_header', $this->getParameters());

		// ���������ѿ�����
		$request->setAttribute('waiwai_keyword', $this->getkeyword());

		return View::INPUT;
	}

	// ���ѥ�᡼������
	private function getParameters() {
		$request = $this->getContext()->getRequest();

		$params['header'] = $request->getParameter('waiwai_header');
		$params['footer'] = $request->getParameter('waiwai_footer');

		return $params;
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