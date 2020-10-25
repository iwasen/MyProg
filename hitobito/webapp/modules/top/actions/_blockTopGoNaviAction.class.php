<?php
/**
 * メンバー登録 表示アクション
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

		// ログイン済みなら表示しない
		if($user->isAuthenticated()){
			return View::NONE;
		}

        return View::SUCCESS;
    }
}
?>