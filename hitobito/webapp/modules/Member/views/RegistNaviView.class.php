<?php
/**
 * ShortDescription
 *
 * Description
 * * 
 * @package Member
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: RegistNaviView.class.php,v 1.3 2006/06/06 15:56:03 ryu Exp $
 */
require_once MO_WEBAPP_DIR.'/hitobito/hitobitoChannel.class.php';

class RegistNaviBaseView extends SmartyView
{
	protected function getCategoryOptions()
	{
        $category_options = array();
        $channelManager = new hitobitoChannelManager();
        $list = $channelManager->getMainChannelList();
        foreach($list as $obj){
       		$category_options[$obj->getId()] = $obj->getTitle();
        }
        return $category_options;
	}
	protected function getNavipageTypeOptions()
	{
        $navipage_type = array(2 => HNb::tr('�ʥӥڡ����Τ�'), 1 => HNb::tr('�ʥӥڡ��� + �磻�磻��ļ�'));
        return $navipage_type;
	}
	protected function getSexOptions()
	{
    	$sex_radio = array(1 => HNb::tr('��'), 2=> HNb::tr('��'));
    	return $sex_radio;
    }	
	protected function getTodofukenOptions()
	{
        $todofuken_options = hitobito::getTodofukenArray();
        return $todofuken_options;
    	
    }	
	protected function getShokugyoOptions()
	{
        // ����
        $shokugyo_options = hitobito::getShokugyoArray();
		return $shokugyo_options;    	
    }	
    protected function getForwardOptions()
    {
    	$forward_options = array(1 => HNb::tr('ž�������Ѥ���'), 0 => HNb::tr('ž�������Ѥ��ʤ�'));
    	return $forward_options;
    }	
    
    public function execute()
    {
        $request = $this->getContext()->getRequest();


        // assign
        $attributeNames = $request->getAttributeNames();
        foreach($attributeNames as $key){
            $var = $request->getAttribute($key);
            $this->setAttribute($key, $var);
        }
        
        $this->useMainTemplate();
        //��Ͽ�������
        $this->setAttribute('navi_touroku_setumei', HNb::tr('�ʥ���Ͽ��������ʥ���Ͽ��������ʥ���Ͽ�������'));
    }	
}

class RegistNaviInputView extends RegistNaviBaseView
{
    public function execute ()
    {
    	parent::execute();
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

        $this->setTemplate('regist_navi.html');

        // assign
        $attributeNames = $request->getAttributeNames();
        foreach($attributeNames as $key){
            $var = $request->getAttribute($key);
            $this->setAttribute($key, $var);
        }
        
//        $this->useMainTemplate();
        
        // ���ƥ���ɥ��åץ�����
        $category_options = array();
        $channelManager = new hitobitoChannelManager();
        $list = $channelManager->getMainChannelList();
        foreach($list as $obj){
       		$category_options[$obj->getId()] = $obj->getTitle();
        }
        $this->setAttribute('category_options', $category_options);
        
        // ���祿���ץ饸���ܥ���
        $navipage_type = array(2 => HNb::tr('�ʥӥڡ����Τ�'), 1 => HNb::tr('�ʥӥڡ��� + �磻�磻��ļ�'));
        $this->setAttribute('navipage_type', $navipage_type);
        
        // ���̥饸���ܥ���
        $sex_radio = array(1 => HNb::tr('��'), 2=> HNb::tr('��'));
        $this->setAttribute('sex_radio', $sex_radio);
        
        //��ƻ�ܸ�
        $todofuken_options = hitobito::getTodofukenArray();
        $this->setAttribute('todofuken_options', $todofuken_options);
        // ����
        $shokugyo_options = hitobito::getShokugyoArray();
        $this->setAttribute('shokugyo_options', $shokugyo_options);

         //ž�����ɥ쥹
        $forward_options = $this->getForwardOptions();
        $this->setAttribute('forward_options', $forward_options);
        
    }
}

class RegistNaviAlertView extends RegistNaviBaseView
{
    public function execute ()
    {
    	parent::execute();
    	$request = $this->getContext()->getRequest();
    	
        $this->setTemplate('regist_navi_confirm.html');
        
        $editform = $request->getAttribute('editform');
        
        $category = $this->getCategoryOptions();
        $this->setAttribute('navipage_category', $category[$editform->member_navipage_category]);
//        $type = $this->getNavipageTypeOptions();
//        $this->setAttribute('navipage_type', $type[$editform->member_navipage_type]);
        $sex = $this->getSexOptions();
        $this->setAttribute('navi_sex', $sex[$editform->member_navi_sex]);
        $address = $this->getTodofukenOptions();
        $this->setAttribute('navi_address1', $address[$editform->member_navi_address1]);
        $jobs = $this->getShokugyoOptions();
        $this->setAttribute('navi_shokugyo', $jobs[$editform->member_navi_shokugyo]);
        
        $forward_options = $this->getForwardOptions();
        $this->setAttribute('navi_forward_flag', $forward_options[$editform->member_navi_forward_flag]);
        
        
    }
}

class RegistNaviSuccessView extends SmartyView
{
    public function execute ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

        $this->setTemplate('regist_navi_success.html');

        // assign
        $attributeNames = $request->getAttributeNames();
        foreach($attributeNames as $key){
            $var = $request->getAttribute($key);
            $this->setAttribute($key, $var);
        }
        
        $this->useMainTemplate();
    }
}

class RegistNaviErrorView extends SmartyView
{
    public function execute ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

        $this->setTemplate('regist_navi_error.html');

        // assign
        $attributeNames = $request->getAttributeNames();
        foreach($attributeNames as $key){
            $var = $request->getAttribute($key);
            $this->setAttribute($key, $var);
        }
        
        $this->useMainTemplate();
    }
}
?>