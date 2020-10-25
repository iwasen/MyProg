<?php

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseView.class.php');
class NewRegistStep1BaseView extends WaiwaiBaseView
{
	protected $controller,$request,$user;
	public function initialize($context)
	{
		parent::initialize($context);
        $this->controller = $this->getContext()->getController();
        $this->request = $this->getContext()->getRequest();
        $this->user = $this->getContext()->getUser();

        // assign
        $attributeNames = $this->request->getAttributeNames();
        foreach($attributeNames as $key){
            $var = $this->request->getAttribute($key);
            $this->setAttribute($key, $var);
        }
        
        $this->useMainTemplate();
		// 閲覧共通設定
		
		return TRUE;
	}
	
	public function execute()
	{
	}
	
}

class NewRegistStep1InputView extends NewRegistStep1BaseView
{
	public function execute()
	{
		parent::execute();
		$account_form_template = MO_MODULE_DIR.'/Member/templates/_regist_input.inc.html';
		$this->setAttribute('account_form_template', $account_form_template);
		$this->setTemplate('RegistMemberStep1.html');
		
		// 必須パラメータ表示指定
		$require_item = array();
		$require_item['mail'] = true;
		$require_item['password'] = true;
		$require_item['confirm_password'] = true;
		
		$member_required_item_list = $this->request->getAttribute('member_required_item_list');
		if(count($member_required_item_list) > 0){
			foreach($member_required_item_list as $item_number){
				switch($item_number){
					case 1:
						$require_item['name'] = true;
						break;
					case 2:
						$require_item['name_kana'] = true;
						break;
					case 3:
						$require_item['sex_cd'] = true;
						break;
					case 4:
						$require_item['birthday'] = true;
						break;
					case 5:
						$require_item['zip_cd'] = true;
						break;
					case 6:
						$require_item['address1'] = true;
						break;
					case 7:
						$require_item['address2'] = true;
						break;
					case 8:
						$require_item['tel'] = true;
						break;
					case 9:
						$require_item['self_introduction'] = true;
						break;
				}
			}
		}
		
		$this->setAttribute('require_item', $require_item);
	}
}

class NewRegistStep1AlertView extends NewRegistStep1BaseView
{
	public function execute()
	{
		parent::execute();
		$account_confirm_template = MO_MODULE_DIR.'/Member/templates/_regist_confirm.inc.html';
		$this->setAttribute('account_confirm_template', $account_confirm_template);
		$this->setTemplate('RegistMemberStep1Confirm.html');
	}
}

class NewRegistStep1SuccessView extends NewRegistStep1BaseView
{
	public function execute()
	{
		parent::execute();
		$this->setTemplate('RegistMemberStep2.html');
	}
}

class NewRegistStep1ErrorView extends NewRegistStep1BaseView
{
	public function execute()
	{
		parent::execute();

		$this->setTemplate('message.html');
	}
}

?>