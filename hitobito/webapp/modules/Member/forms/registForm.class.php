<?php

class MemberNewRegistForm extends HNbActionForm
{
	public 	$member_mail,
			$member_password,
			$member_confirm_password;
	
	public function fetch($master , $request)
	{
		parent::fetch($master, $request);
		
		$strParamsKey = array('member_mail', 'member_password', 'member_confirm_password');
		
		foreach($strParamsKey as $key){
			$this->$key = trim($request->getParameter($key, ''));
		}
		
		
		$validater = new HNbValidater();
		
		// 入力必須項目のチェック
		if(strlen($this->member_mail) == 0){
			$this->addError('メールアドレスを入力してください');
		}
		if(strlen($this->member_password) == 0){
			$this->addError('パスワードを入力してください。');
		}
		if(strlen($this->member_password) < 6){
			$this->addError('パスワードを6文字以上で入力してください。');
		}
		
		if(strlen($this->member_confirm_password) == 0){
			$this->addError('パスワード（確認用）を入力してください。');
		}
		// メールアドレスの妥当性検査
		if(!$validater->validateEmail($this->member_mail)){
			$this->addError('メールアドレスが有効ではありません');
		}
		// pass一致しているか
		if($this->member_password != $this->member_confirm_password){
			$this->addError('パスワードとパスワード再入力が一致しません。');
		}
		// メールアドレスの重複検査
		$handler = HNb::getHandler('MemberManager');
		if($handler->isEmailUsedInAliveMemberAccount($this->member_mail)){
			$this->addError('すでに使われているメールアドレスです');
		}	
	}
	
	public function load($master, $requset)
	{
	}
	
	public function update($master, $request)
	{
		$master->setEmail($this->member_mail);
		if(strlen($this->member_password) > 0){
			$master->setPassword($this->member_password);
		}
	}

}
?>