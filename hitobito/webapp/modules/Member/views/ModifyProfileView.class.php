<?php
/**
 * ShortDescription
 *
 * Description
 * * 
 * @package Member
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: ModifyProfileView.class.php,v 1.2 2006/06/06 15:56:03 ryu Exp $
 */
require_once MO_WEBAPP_DIR.'/hitobito/hitobitoChannel.class.php';

class ModifyProfileBaseView extends SmartyView
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
        $navipage_type = array(2 => HNb::tr('ナビページのみ'), 1 => HNb::tr('ナビページ + ワイワイ会議室'));
        return $navipage_type;
	}
	protected function getSexOptions()
	{
    	$sex_radio = array(1 => HNb::tr('男'), 2=> HNb::tr('女'));
    	return $sex_radio;
    }	
	protected function getTodofukenOptions()
	{
        $todofuken_options = hitobito::getTodofukenArray();
        return $todofuken_options;
    	
    }	
	protected function getShokugyoOptions()
	{
        // 職業
        $shokugyo_options = hitobito::getShokugyoArray();
		return $shokugyo_options;    	
    }
    
    protected function getForwardOptions()
    {
    	$forward_options = array(1 => HNb::tr('転送を利用する'), 0 => HNb::tr('転送を利用しない'));
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
        
        $this->setAttribute('is_new_navi', FALSE);
        //登録手順説明
        $this->setAttribute('navi_touroku_setumei', HNb::tr('ナビ登録手順説明ナビ登録手順説明ナビ登録手順説明'));
    }	
}

class ModifyProfileInputView extends ModifyProfileBaseView
{
    public function execute ()
    {
    	parent::execute();
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

        $this->setTemplate('navi_profile_form.html');

        // assign
        $attributeNames = $request->getAttributeNames();
        foreach($attributeNames as $key){
            $var = $request->getAttribute($key);
            $this->setAttribute($key, $var);
        }
        
//        $this->useMainTemplate();
        
        // カテゴリドロップダウン
        $category_options = array();
        $channelManager = new hitobitoChannelManager();
        $list = $channelManager->getMainChannelList();
        foreach($list as $obj){
       		$category_options[$obj->getId()] = $obj->getTitle();
        }
        $this->setAttribute('category_options', $category_options);
        
        // 応募タイプラジオボタン
        $navipage_type = array(2 => HNb::tr('ナビページのみ'), 1 => HNb::tr('ナビページ + ワイワイ会議室'));
        $this->setAttribute('navipage_type', $navipage_type);
        
        // 性別ラジオボタン
        $sex_radio = array(1 => HNb::tr('男'), 2=> HNb::tr('女'));
        $this->setAttribute('sex_radio', $sex_radio);
        
        //都道府県
        $todofuken_options = hitobito::getTodofukenArray();
        $this->setAttribute('todofuken_options', $todofuken_options);
        // 職業
        $shokugyo_options = hitobito::getShokugyoArray();
        $this->setAttribute('shokugyo_options', $shokugyo_options);
        
        //転送アドレス
        $forward_options = $this->getForwardOptions();
        $this->setAttribute('forward_options', $forward_options);
        
    }
}

class ModifyProfileAlertView extends ModifyProfileBaseView
{
    public function execute ()
    {
    	parent::execute();
    	$request = $this->getContext()->getRequest();
    	
        $this->setTemplate('navi_profile_confirm.html');
        
        $editform = $request->getAttribute('editform');
        
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

class ModifyProfileSuccessView extends SmartyView
{
    public function execute ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

        $this->setTemplate('message.html');

		$this->setAttribute('message', HNb::tr('ナビ登録情報を更新しました'));
        // assign
        $attributeNames = $request->getAttributeNames();
        foreach($attributeNames as $key){
            $var = $request->getAttribute($key);
            $this->setAttribute($key, $var);
        }
        
        $this->useMainTemplate();
    }
}

class ModifyProfileErrorView extends SmartyView
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