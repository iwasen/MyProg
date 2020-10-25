<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package Member
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: RegistNaviAction.class.php,v 1.12 2006/06/06 15:56:03 ryu Exp $
 */

require_once MO_WEBAPP_DIR.'/lib/HNbActionForm.class.php';
require_once MO_WEBAPP_DIR. '/hitobito/db/t_navi.class.php';
require_once MO_WEBAPP_DIR. '/hitobito/db/t_navi_page.class.php';

require_once MO_WEBAPP_DIR.'/modules/Member/forms/registNaviForm.class.php';

class RegistNaviAction extends HNbEditAction
{
	protected function fetchID()
	{
		return 0;
	}

	protected function getManager()
	{
		return new MemberNaviRegistManager();
	}
	
	protected function getActionForm()
	{
		return new MemberRegistNaviPageForm();
	}

	protected function setUpNewObject(&$obj)
	{
		// =====================================================================
		// Object Init Code : ���֥������Ȥξ����ˤ��碌���������ɬ�פʤ餳���˽�
		// =====================================================================
		// (Ex.) $obj->setBlog_id($this->blog_id);
		$obj->setAttribute('nav_member_id', $this->user->getAttribute('mbr_member_id'));
		$obj->setAttribute('nvp_type', 1);
		$obj->setAttribute('nav_forward_flag', 1);// ž�����Ѥ�����
		
		$request = $this->getContext()->getRequest();
		$request->setAttribute('is_new_navi', true);
		
		// navi�ʤ�ʥӾ����Ϳ���Ƥ���
		$naviHandler = HNb::getDBHandler('t_navi');
		$criteria = new HNbCriteria();
		$criteria->setWhere('nav_member_id='.$this->user->getAttribute('mbr_member_id'));
		$navi = $naviHandler->getOne($criteria);
		if($navi->getId() > 0){
			$obj->setAttributes($navi->getAttributes());
			$request->setAttribute('is_new_navi', false);
		}
	}

	protected function isEdit($obj)
	{
		// =======================================================================
		// Permission Check etc...  �Խ����¤Υ����å��ʤɤ�ɬ�פʤ餳���˥����ɤ��
		// ======================================================================= 
		if($this->user->isAuthenticated()){
			return TRUE;
		}else{
			$this->request->setAttribute('message', HNb::tr('�ʥӤ˱��礹��ˤϥ��С���Ͽ��ɬ�פǤ���'));
			return FALSE;
		}
	}
	
	protected function isConfirm()
	{
		return TRUE;
	}

	public function isSecure()
	{
		return TRUE;
	}
	
	public function execute()
	{
		$result = parent::execute();
		if($result == View::SUCCESS){
			// ��̳�ɤإ᡼��
			$master = $this->request->getAttribute('EditActionMaster');
			$masterVars = $master->getAttributes();
			$mailer = hitobito::getMailer();
			$mailer->setAttribute('NAME', $masterVars['nav_name1'].' '.$masterVars['nav_name2']);
			$mailer->setAttribute('MAIL', $this->user->getAttribute('mbr_mail_addr'));
			
			$mailer->loadTemplate('Aregist_navi');
			
			$systemMaster = HNbSystemMaster::getInstance();
        	$notifyAddr = $systemMaster->getAttribute('notify_addr');
        	$mailer->send($notifyAddr);
			
			// ����Ԥإ᡼��
			$mailer->loadTemplate('regist_navi');
			$mailer->send($this->user->getAttribute('mbr_mail_addr'));
			return View::SUCCESS;
		}else{
			return $result;
		}
	}
	
}

class MemberNaviRegistObject extends HNbValueObject
{
	private $t_navi;
	private $t_navi_page;
	public function __construct()
	{
		$this->t_navi = new t_naviObject;
		$this->t_navi_page = new t_navi_pageObject;
	}
	
	public function isNewNai()
	{
		
	}
	
	public function setAttribute($key, $var)
	{
		$prefix = substr($key, 0, 3);
		switch($prefix){
			case 'nav':
				// �ʥӾ���
				$this->t_navi->setAttribute($key, $var);
				break;
			case 'nvp':
				//�ʥӥڡ�������
				$this->t_navi_page->setAttribute($key, $var);
				break;
		}
	}
	
	public function getAttribute($key)
	{
		$prefix = substr($key, 0, 3);
		switch($prefix){
			case 'nav':
				// �ʥӾ���
				return $this->t_navi->getAttribute($key);
			case 'nvp':
				//�ʥӥڡ�������
				return $this->t_navi_page->getAttribute($key);
		}
	}
	
	public function getAttributes()
	{
		$ret = $this->t_navi->getAttributes();
		$ret = array_merge($ret, $this->t_navi_page->getAttributes());
		return $ret;
	}
	
	public function setAttributes($values)
	{
		foreach($values as $key => $var){
			$this->setAttribute($key, $var);
		}
	}
	
	public function isNew()
	{
		return TRUE;
	}
}

class MemberNaviRegistManager
{
	// BLOG�Ȥ襤�襤��ļ���Ĥ��äƤ���
	public function insert($obj)
	{
//		HNb::setAdodbDebug(TRUE);
		// �ʥӤʤ�ʥӾ���򹹿�
		$naviHandler = HNb::getDBHandler('t_navi');
		$navi = $naviHandler->create();
		$navi->setAttributes($obj->getAttributes());
		if(!$naviHandler->insert($navi)){
			return FALSE;
		}
		
		$navipageHandler = HNb::getDBHandler('t_navi_page');
		$navipage = $navipageHandler->create();
		$navipage->setAttributes($obj->getAttributes());
		// Blog����
		if(!$blog = $this->makeBlog()){
			return FALSE;
		}
		$navipage->setAttribute('nvp_blog_id', $blog->getId());
		
		// �襤�襤��ļ�ʻ�ѥ����פʤ��ļ�����
		$type = $obj->getAttribute('nvp_type');
		if($obj->getAttribute('nvp_type') == 1){
			if(!$waiwai = $this->makeWaiwai($obj)){
				return FALSE;
			}
			$navipage->setAttribute('nvp_room_id', $waiwai->getId());
		}else{
			$navipage->setAttribute('nvp_room_id', NULL);
		}
		
		
		$navipage->setAttribute('nvp_navi_id', $navi->getId());
		// ���ޥ�ID���ˡ����Ǥ���ɬ�פ�����
		if(!$mailMag = $this->makeMailMag()){
			return FALSE;
		}
		$navipage->setAttribute('nvp_melmaga_id', $mailMag->getId());
		if(!$navipageHandler->insert($navipage)){
			return FALSE;
//			HNb::setAdodbDebug(FALSE);
		}
//			HNb::setAdodbDebug(FALSE);
		return TRUE;
		
	}
	
	/**
	 * @return t_melmaga
	 */
	protected function makeMailMag()
	{
		$mailMagHandler = HNb::getDBHandler('t_melmaga');
		$mag = $mailMagHandler->create();
		$mag->setAttribute('mmg_status', 1);
		$mag->setAttribute('mmg_melmaga_name', HNb::tr('���ޥ�̾��̤����'));
		if(!$mailMagHandler->insert($mag)){
			return FALSE;
		}
		return $mag;
	}
	
	/**
	 * @return t_blog
	 */
	protected function makeBlog()
	{
		$blogHandler = HNb::getDBHandler('t_blog');
		$blog = $blogHandler->create();
		$blog->setAttribute('blg_title', HNb::tr('�֥�̾��̤����'));
		$blog->setAttribute('blg_status', 1);
		if(!$blogHandler->insert($blog)){
			return FALSE;
		}
		return $blog;
	}
	
	/**
	 * @return t_rootm
	 */
	 protected function makeWaiwai($obj)
	 {
		$waiwaiHandler = HNb::getDBHandler('t_room');
		$waiwai = $waiwaiHandler->create();
//		$waiwai->setAttribute('rom_name', HNb::tr('�磻�磻��ļ�̤̾����'));
		$waiwai->setAttribute('rom_name', $obj->getAttribute('nvp_title'));
		$waiwai->setAttribute('rom_status', 1);
		$code = uniqid(rand(), TRUE); // ��ˡ����ʥ���������
		$waiwai->setAttribute('rom_room_code', $code);
		if(!$waiwaiHandler->insert($waiwai)){
			return FALSE;
		}
		return $waiwai;
	 }
	
	public function get($id)
	{
		return new MemberNaviRegistObject();
	}
	
	public function create()
	{
		return new MemberNaviRegistObject();
	}
}
?>
