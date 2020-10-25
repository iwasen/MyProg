<?php
/**
 * �إå��� ɽ�����������
 *
 * @package top
 * @author y.fujimori<wfujimori@xxxxxxx.co.jp>
 * @version $id$
 */

require_once MO_WEBAPP_DIR.'/hitobito/hitobitoChannel.class.php';
class _blockHeaderAction extends Action
{

    public function execute ()
    {

        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

		// ������Ѥߤʤ�ɽ�����ʤ�
		if($user->isAuthenticated()){
	        $request->setAttribute('template', '_blockHeaderAfterLogin.html');
        	return View::SUCCESS;
		}

	   	$request->setAttribute('template', '_blockHeader.html');
        return View::SUCCESS;

    }
}
?>