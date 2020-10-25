<?php
/**
 * �����o�[�o�^ �\���A�N�V����
 *
 * @package top
 * @author y.fujimori<wfujimori@xxxxxxx.co.jp>
 * @version $id$
 */

class _blockTopGoNaviAction extends Action
{

    public function execute()
    {

        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

		// ���O�C���ς݂Ȃ�\�����Ȃ�
		if($user->isAuthenticated()){
			return View::NONE;
		}

        return View::SUCCESS;
    }
}
?>