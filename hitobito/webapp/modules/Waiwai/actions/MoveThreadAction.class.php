<?php
/**
 * �ҤȤӤȎ�net �桼�����ʥӥڡ���
 *
 * �磻�磻��ļ� ����åɰ�ư���������
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseAction.class.php');

class MoveThreadAction extends WaiwaiBaseAction {
	// �����������¹�
	public function execute() {
		$request = $this->getContext()->getRequest();

		// ����åɰ�ư����
		$from_id = $request->getAttribute('waiwai_move_from_id');
		$to_id = $request->getAttribute('waiwai_move_to_id');
		$option = $request->getAttribute('waiwai_move_option');
		$this->db->moveThread($from_id, $to_id, $option);

		// ������������
		$this->setBrowseCommon();

		return View::SUCCESS;
	}

	// ���ǥե���ȥӥ塼ɽ��
	public function getDefaultView() {
		$request = $this->getContext()->getRequest();

		// ������������
		$this->setBrowseCommon();

		$move['move_from'] = $request->getParameter('waiwai_id');
		$request->setAttribute('waiwai_move', $move);

		return View::INPUT;
	}

	// �����������¹ԥ᥽�åɻ���
	public function getRequestMethods() {
		return Request::POST;
	}

	// ��Validator��Ͽ
	public function registerValidators($validatorManager) {
		$context = $this->getContext();

		// ����̵ͭ�����å�
		$validatorManager->registerName('waiwai_move_from', TRUE, '��ư��ȯ��ID��̤���ϤǤ���');
		$validatorManager->registerName('waiwai_move_to', TRUE, '��ư��ȯ��ID��̤���ϤǤ���');
		$validatorManager->registerName('waiwai_move_option', TRUE, '���ץ����̤����Ǥ���');

		// ��ư��ȯ��ID���������å�
		$params = array(
			'nan_error' => '��ư��ȯ��ID��Ⱦ�ѿ����ǤϤ���ޤ���',
			'min' => 1,
			'min_error' => '��ư��ȯ��ID��1�ʾ�ο����ǻ��ꤷ�Ƥ���������');
		$validator = new NumberValidator;
		$validator->initialize($context, $params);
		$validatorManager->registerValidator('waiwai_move_from', $validator);

		// ��ư��ȯ��ID���������å�
		$params = array(
			'nan_error' => '��ư��ȯ��ID��Ⱦ�ѿ����ǤϤ���ޤ���',
			'min' => 0,
			'min_error' => '��ư��ȯ��ID��0�ʾ�ο����ǻ��ꤷ�Ƥ���������');
		$validator = new NumberValidator;
		$validator->initialize($context, $params);
		$validatorManager->registerValidator('waiwai_move_to', $validator);
	}

	// �����ϥǡ��������å�
	public function validate() {
		$request = $this->getContext()->getRequest();

		$move = $this->getParameters();

		if ($move['move_from'] == $move['move_to']) {
			$request->setError('waiwai_move_from', '��ư��ȯ��ID�Ȱ�ư��ȯ��ID��Ʊ��ID�ϻ���Ǥ��ޤ���');
			return false;
		}

		// ��ư��ID�����å�
		$from_id = $this->db->getRemarkId($this->room_id, $move['move_from']);
		if (!$from_id) {
			$request->setError('waiwai_move_from', "���Ϥ��줿��ư��ȯ��ID��¸�ߤ��ޤ���");
			return false;
		}

		// ��ư��ID�����å�
		if ($move['move_to'] == 0)
			$to_id = 0;
		else {
			$to_id = $this->db->getRemarkId($this->room_id, $move['move_to']);
			if (!$to_id) {
				$request->setError('waiwai_move_to', "���Ϥ��줿��ư��ȯ��ID��¸�ߤ��ޤ���");
				return false;
			}
		}

		// �ƻҴط������å�
		if ($move['move_option'] == 2 && $to_id != 0) {
			if (!$this->db->checkParentChild($this->room_id, $from_id, $to_id)) {
				$request->setError('waiwai_move_to', '����å����Τ򤽤Υ���åɤλҤΰ��֤˰�ư�����뤳�ȤϤǤ��ޤ���');
				return false;
			}
		}

		$request->setAttribute('waiwai_move_from_id', $from_id);
		$request->setAttribute('waiwai_move_to_id', $to_id);
		$request->setAttribute('waiwai_move_option', $move['move_option']);

		return true;
	}

	// �����ϥ��顼����
	public function handleError() {
		$request = $this->getContext()->getRequest();

		// ������������
		$this->setBrowseCommon();

		// ȯ��ID����
		$move = $this->getParameters();
		$request->setAttribute('waiwai_move', $move);

		return View::INPUT;
	}

	// ���ѥ�᡼������
	private function getParameters() {
		$request = $this->getContext()->getRequest();

		$params['move_from'] = $request->getParameter('waiwai_move_from');
		$params['move_to'] = $request->getParameter('waiwai_move_to');
		$params['move_option'] = $request->getParameter('waiwai_move_option');

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