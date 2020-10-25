<?php
/**
 * @package	Member
 * @author Ryuji
 * @version $Id: changeMailStatusForm.class.php,v 1.1 2006/01/13 10:44:39 ryu Exp $
 */
 require_once MO_WEBAPP_DIR .'/lib/HNbActionForm.class.php';
class MemberChangeMailStatusForm extends HNbSemiAutoActionForm
{
	public 	
			$member_hitobito_letter,
			$member_status;
	protected function getStringParameterKeys()
	{
		return array();
	}
    /**
     * 整数パラメータキーの配列を返す
     */
    protected function getIntegerParameterKeys()
    {
    	return array('member_hitobito_letter', 'member_status');
    }	
    
    /**
     * 入力必須パラメータをキー、値にエラーメセッージを入れた連想配列を返す
     * @example return array('email' => 'メールアドレスが入力されていません');
     */
    protected function getRequireParameterKeys()
    {
    	return array();
    }
	
	public function fetch($master , $request)
	{
		parent::fetch($master, $request);
		if($this->member_status !=1 && $this->member_status != 3){
			die('invalid parameter');
		}
	}
	
	public function load($master, $requset)
	{
		$this->member_hitobito_letter = $master->getHitobito_letter();
		$this->member_status = $master->getStatus();
		
	}
	
	public function update($master, $request)
	{
		$master->setHitobito_letter($this->member_hitobito_letter);
		$master->setStatus($this->member_status);
	}
}
?>