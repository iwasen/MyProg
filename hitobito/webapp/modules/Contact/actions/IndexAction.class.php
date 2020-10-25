<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package Contact
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: IndexAction.class.php,v 1.6 2006/05/13 04:50:15 ryu Exp $
 */

require_once MO_WEBAPP_DIR.'/lib/HNbActionForm.class.php';

require_once MO_WEBAPP_DIR.'/modules/Navi/lib/notifyMail.class.php';

//require_once MO_WEBAPP_DIR . '/hitobito/db/t_navi_page_notify.class.php';
class IndexAction extends HNbEditAction
{
	protected $formId, $navipageId;
	protected function fetchID()
	{
		return 0;
	}

	protected function getManager()
	{
		return HNb::getDBHandler('t_navi_inquiry');
	}
	
	protected function getActionForm()
	{
		$this->formId = intval($this->request->getParameter('contact_form_id'));
		$this->request->setAttribute('formId', $this->formId);
		$this->navipageId = hitobito::getNaviPageInfo()->getId();
		$formFile = MO_WEBAPP_DIR.'/modules/Contact/forms/'.$this->formId.'Form.class.php';
		if(file_exists($formFile)){
			require_once $formFile;
			$formName = 'Contact'.$this->formId.'Form';
			return new $formName();
		}
		$error = new HNbException();
		$error->setForward('Default', 'Error404');
		throw $error;
	}

	protected function setUpNewObject(&$obj)
	{
		// =====================================================================
		// Object Init Code : オブジェクトの状況にあわせた初期化が必要ならここに書く
		// =====================================================================
		// (Ex.) $obj->setBlog_id($this->blog_id); 
		$obj->setAttribute('nvi_navi_page_id', $this->navipageId);
		
	}

	protected function isEdit($obj)
	{
		// =======================================================================
		// Permission Check etc...  編集権限のチェックなどが必要ならここにコードを書く
		// ======================================================================= 

		return true;
	}

	public function isSecure()
	{
		return false;
	}
	
	protected function insert($obj)
	{
		if(!parent::insert($obj)){
			return FALSE;
		}
		$notifer = new NaviNotifyMail();
		$values = array(
		    'SUBJECT' => $obj->getAttribute('nvi_title'),
		    'BODY' => $obj->getAttribute('nvi_content')
		    );
		return $notifer->notify(hitobito::getNaviPageInfo(), 'contact', $values);
		
        /*		
		$handler = new t_navi_page_notifyObjectHandler();
		$notifyConfig = $handler->get(hitobito::getNaviPageInfo()->getId());
		if($notifyConfig->isNew() || $notifyConfig->getAttribute('nnt_contact_notify') == 1){
		
		    $mailer = hitobito::getMailer();
		    $mailer->loadTemplate('contact2navi');
		    $mailer->setAttribute('SUBJECT', $obj->getAttribute('nvi_title'));
		    $mailer->setAttribute('BODY', $obj->getAttribute('nvi_content'));
		    $navipage =hitobito::getNaviPageInfo(); 
		    $mailer->setAttribute('NAVIPAGE_TITLE', $navipage->getTitle());
		    $mailer->setAttribute('NAVIEDIT_URL', hitobito::getUrl().'index.php?module=Navi&action=NaviEdit&navipage_id='.$navipage->getId());
    		
		    $to = $navipage->getNaviEmail();
		    return $mailer->send($to);
	    }
	    return TRUE;
	    */
	}
	protected function isConfirm()
	{
		return TRUE;
	}

}

?>
