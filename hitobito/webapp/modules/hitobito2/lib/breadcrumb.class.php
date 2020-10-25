<?php


require_once MO_WEBAPP_DIR.'/hitobito/hitobitoChannel.class.php';
class hitobitoBreadcrumbBuilder
{
	private $context;
	private $breadcrumbList = array();
	public function __construct($context)
	{
		$this->context = $context;
	}
	private function getContext()
	{
		return $this->context;
	}
	public function render()
	{
		$request = $this->getContext()->getRequest();
		$module = $request->getParameter('module');
		$action = $request->getParameter('action');
		$navipage = hitobito::getNaviPageInfo();
		if($navipage->getId() > 0){
			// navipage
			// MainChannel
			$categoryId = $navipage->getChannelId();
			$this->addCategory($categoryId);
			// navipageTitle
			$this->add('index.php?module=Navi&amp;navipage_id='.$navipage->getId(), $navipage->getTitle());
			
		}
		if($request->hasParameter('category_id')){
			$categoryId = intval($request->getParameter('category_id'));
			$this->addCategory($categoryId);
		}
		switch($module){
			case 'Member':
				switch($action){
					case 'remindPassword':
						$this->add('index.php?module='.$module.'&amp;action='.$action, HNb::tr('�ѥ���ɤκ�ȯ��'));
						break;
					case 'RegistNavi':
						$this->add('index.php?module='.$module.'&amp;action='.$action, HNb::tr('�ʥ���Ͽ'));
						break;
				}
				break;
			case 'Navi':
				switch($action){
					case 'ShowNaviEdit':
						$this->addNaviEdit();
						break;
				}
				break;
			case 'Blog':
				switch($action){
					case 'PostStory':
					case 'adminStories':
					case 'adminImage':
						$this->addNaviEdit();
						break;
				}
				break;
			case 'MailMag':
				switch($action){
					case 'ListMailMag':
						$this->addNaviEdit();
						break;
				}
				break;
			case 'Enquete':
				switch($action){
					case 'NaviEnquete':
						$this->addNaviEdit();
						break;
				}
				break;
			case 'Waiwai':
				switch($action){
					case 'EditMenu':
						$this->addNaviEdit();
						break;
				}
				break;
			case 'Contact':
				switch($action){
					case 'AdminList':
						$this->addNaviEdit();
						break;
				}
				break;
			case 'WebLinks':
				switch($action){
					case 'AdminItems':
						$this->addNaviEdit();
						break;
				}
				break;
			
		}
		// TODO ���С���Ͽ�����Խ�(���ޥ���������ޤ�)
		// TODO ���󥱡��Ȱ����䥢�󥱡���̾�䥢�󥱡��ȷ��
		// TODO �ʥӥ��쥯�Ȱ���
		// TODO ���������󥯰���	
		$ret = '';
		foreach($this->breadcrumbList as $breadcrumb){
			$ret .= sprintf('<img src="image/head_track_link.gif" width="16" height="12" align="absmiddle"><a href="%s">%s</a>', 
				$breadcrumb['url'],
				$breadcrumb['text']);
		}
		return $ret;
	}
	
	private function add($url, $text)
	{
		$this->breadcrumbList[] = array('url' => $url, 'text'=> $text);
	}
	private function addCategory($categoryId)
	{
			$categoryManager = new hitobitoChannelManager();
			$category = $categoryManager->get($categoryId);
			$this->add('index.php?module=Category&amp;action=ShowMainCategory&amp;category_id='.$category->getId(), $category->getTitle());
	}
	private function addNaviEdit()
	{
		// TODO �ʥӥ��ǥ��åȡ�磻�磻��ļ��������Ȥ����褦�ʴ����� ��
		$this->add('index.php?module=Navi&amp;action=ShowNaviEdit&amp;navipage_id='.hitobito::getNaviPageInfo()->getId(), HNb::tr('�ʥӥ��ǥ��å�'));
	}
}
?>