<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package Member
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: ResignAction.class.php,v 1.1 2005/11/22 15:47:29 ryu Exp $
 */

class ResignAction extends Action
{
	private $errors;
	
	public function initialize($context)
	{
		parent::initialize($context);
        $request = $this->getContext()->getRequest();
		
		$handler = HNb::getDBHandler('m_resign_reason');
		$where = 'rsg_status = 1';
		$objs = $handler->getObjects($where);
		$request->setAttribute('resign_reason', $objs);
		
		
		return TRUE;
	}
    public function execute()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

		$resign_id = $request->getParameter('resign_id');
		
		// TODO 退会処理
		$MemberManager = HNb::getHandler('MemberManager');
		$MemberManager->resignMember($user, $resign_id);
		
		        
        return View::SUCCESS;
    }

	public function validate()
	{
        $request = $this->getContext()->getRequest();
		
		$this->resign_id = intval($request->getParameter('resign_id'));
		if($this->resign_id ==0){
			$this->addError('退会理由が選択されていません');
			return FALSE;
		}
		
		$handler = HNb::getDBHandler('m_resign_reason');
		$where = 'rsg_status = 1 AND rsg_resign_id='.$this->resign_id;
		$num = $handler->getCount($where);
		if($num == 0){
			$this->addErrror('無効な退会理由です');
			return FALSE;
		}
		return TRUE;
	}
	
	public function handleError()
	{
		// エラーメッセージ
        $request = $this->getContext()->getRequest();
		$request->setAttribute('member_errors', $this->errors);

		return $this->getDefaultView();
	}

    public function getDefaultView ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

        return View::INPUT;
    }

    public function getRequestMethods()
    {
        
        return Request::POST;
    }

    public function isSecure()
    {
        return true;
    }
    
    private function addError($message)
    {
    	$this->errors[] = $message;
    }
}

?>