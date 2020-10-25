<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package Contact
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: ReplyAction.class.php,v 1.5 2006/08/28 14:30:09 ryu Exp $
 */

require_once MO_WEBAPP_DIR.'/lib/HNbActionForm.class.php';

require_once MO_WEBAPP_DIR.'/modules/Contact/forms/replyForm.class.php';
require_once MO_WEBAPP_DIR.'/modules/Contact/lib/Reply.class.php';

class ReplyAction extends HNbEditAction
{
	protected function fetchID()
	{
		return 0;
	}

	protected function getManager()
	{
		return new ContactReplyManager();
	}
	
	protected function getActionForm()
	{
		return new ContactReplyForm();
	}

	protected function setUpNewObject(&$obj)
	{
		// =====================================================================
		// Object Init Code : オブジェクトの状況にあわせた初期化が必要ならここに書く
		// =====================================================================
		$inquiryId = intval($this->request->getParameter('contact_inquiry_id'));
		$handler = HNb::getDBHandler('t_navi_inquiry');
		$inquiry = $handler->get($inquiryId);
		if($inquiry->isNew()){
			die();
		}
		$this->request->setAttribute('inquiry', $inquiry);
		$obj->setAttribute('nva_inquiry_id', $inquiryId);
		$obj->setAttribute('nva_mail_addr', $inquiry->getAttribute('nvi_mail_addr'));
		$obj->setAttribute('nva_subject', 'RE:'.$inquiry->getAttribute('nvi_title'));
		$defaultContent = preg_replace('/^/m', '> ', $inquiry->getAttribute('nvi_content'));
		$obj->setAttribute('nva_content', $defaultContent);
		 
	}

	protected function isEdit($obj)
	{
		// =======================================================================
		// Permission Check etc...  編集権限のチェックなどが必要ならここにコードを書く
		// ======================================================================= 
		Navi::GuardNaviEdit($this->getContext()->getUser());
		return true;
	}
	
    public function execute()
    {
        try
        {
            $this->manager = $this->getManager();
            if ($this->isConfirm() && $this->request->hasParameter('save_execute'))
            {
                if ($this->save())
                {
                    return View :: SUCCESS;
                } else
                {
                    return View :: ERROR;
                }

            }
            $editform = $this->getActionForm();

            $id = $this->fetchId();

            $master = $this->manager->get($id);
            if ($master->isNew())
            {
                $this->setUpNewObject($master);
            }

            if (!$this->isEdit($master))
            {
                return View :: ERROR;
            }
            if ($editform->init($master, $this->request) === HNB_ACTIONFORM_POST_SUCCESS)
            {
                // update
                $editform->update($master, $this->request);

                if ($this->isConfirm())
                {
                    $values = $master->getAttributes();
                    $this->user->setAttribute('objectAttributes', $values, 'HNbEditAction');
                    $this->request->setAttribute('editform', $editform);
                    return View :: ALERT; // confirm画面
                }

                $this->request->setAttribute('editform', $editform);
                $this->request->setAttribute('obj', $master);
                $result = $this->manager->insert($master);
                if ($result == TRUE)
                {
                	// メール送信
                	$mailer = hitobito::getMailer();
					$mailer->loadTemplate('contact_reply');
					$mailer->setAttribute('SUBJECT', $master->getAttribute('nva_subject'));
					$mailer->setAttribute('CONTENTS', $master->getAttribute('nva_content'));
					$mailer->setAttribute('URL', hitobito::getUrl());
					$mailer->addBcc($this->user->getAttribute('mbr_mail_addr'));
					
			
					$mailer->send($master->getAttribute('nva_mail_addr'));
//                	$from = $this->user->getAttribute('mbr_mail_addr');
//					$header = 'From: '.$from;
//					mb_send_mail($master->getAttribute('nva_mail_addr'), $master->getAttribute('nva_subject'), $master->getAttribute('nva_content') , $header);
                	
                    return View :: SUCCESS;
                } else
                {
                    return View :: ERROR;
                }
            } else
            {
                $this->request->setAttribute('editform', $editform);
                $this->request->setAttribute('obj', $master);
                // reply list
                $reply_list = $this->manager->getObjects('nva_inquiry_id='.intval($this->request->getParameter('contact_inquiry_id')));
                $this->request->setAttribute('reply_list', $reply_list);
                return View :: INPUT;
            }
        } catch (HNbException $e)
        {
            if ($e->hasForward())
            {
                $this->getContext()->getController()->forward($e->getForwardModule(), $e->getForwardAction());
                return View :: NONE;
            }
            if($e->hasReturnView()){
            	return $e->getReturnView();
            }
        }
    }
			

	public function isSecure()
	{
		return true;
	}
}

?>
