<?php
/**
 * @package	Member
 * @author Ryuji
 * @version $Id: accountForm.class.php,v 1.5 2006/06/23 02:43:39 ryu Exp $
 */
 require_once MO_WEBAPP_DIR .'/lib/HNbActionForm.class.php';
 require_once MO_WEBAPP_DIR .'/hitobito/db/m_todofuken.class.php';
 require_once MO_WEBAPP_DIR .'/lib/HNbFormUtility.class.php';
 
abstract class MemberAccountBaseForm extends HNbActionForm
{
	public 	$member_mail,
			$member_password,
			$member_confirm_password,
			$member_name,
			$member_name_kana,
			$member_sex_cd,
			$member_birth_year,
			$member_birth_month,
			$member_birth_day,
			$member_zip_cd1,
			$member_zip_cd2,
			$member_address1,
			$member_address2,
			$member_tel1,
			$member_tel2,
			$member_tel3,
			$member_self_introduction,
			
			$id;
	public function fetch($master, $request)
	{
	    parent::fetch($master, $request);
	    $strParamsKey = array(
	        'member_mail',
	        'member_password', 
	        'member_confirm_password',
			'member_name',
			'member_name_kana',
			'member_zip_cd1',
			'member_zip_cd2',
			'member_address2',
			'member_tel1',
			'member_tel2',
			'member_tel3',
			'member_self_introduction',
	        );
		$intParamsKey = array(
		    'member_sex_cd',
		    'member_birth_year',
		    'member_birth_month',
		    'member_birth_day',
		    'member_address1'
		    );
		
		foreach($strParamsKey as $key){
			$this->$key = trim($request->getParameter($key, ''));
		}
		foreach($intParamsKey as $key){
			$this->$key = intval($request->getParameter($key, 0));
		}

		$validater = new HNbValidater();
		
		// 入力必須項目のチェック mail, password, confirm_password
		if(strlen($this->member_mail) == 0){
			$this->addError('メールアドレスを入力してください');
		}
		// メールアドレスの妥当性検査
		if(!$validater->validateEmail($this->member_mail)){
			$this->addError('メールアドレスが有効ではありません');
		}
		// pass一致しているか
		if($this->member_password != $this->member_confirm_password){
			$this->addError('パスワードとパスワード再入力が一致しません。');
		}

        //ver2.1 seq1 add parameter validate
        // sex_cd
        if($this->member_sex_cd > 2){
            dir('Bad request');
        }
        // zip
        if(!empty($this->member_zip_cd1) || !empty($this->member_zip_cd2)){
            if((strlen($this->member_zip_cd1) != 3) || (strlen($this->member_zip_cd2) != 4) || (!is_numeric($this->member_zip_cd1)) || (!is_numeric($this->member_zip_cd2))){
                $this->addError('郵便番号を正しく入力してください。');
            }
        }
        // birthday
        if(!empty($this->member_birth_year) || !empty($this->member_birth_month) || !empty($this->member_birth_day)){
            if(!checkdate($this->member_birth_month, $this->member_birth_day, $this->member_birth_year)){
                $this->addError('生年月日を正しく入力してください。');
            }
        }
        // tel
        if(!empty($this->member_tel1) || !empty($this->member_tel2) || !empty($this->member_tel3)){
            $tel = $this->member_tel1.'-'.$this->member_tel2.'-'.$this->member_tel3;
            if(!preg_match('/^[0-9]+-[0-9]+-[0-9]+$/', $tel)){
                $this->addError('電話番号を正しく入力してください。');
            }
        }
        
	}
	public function update($master, $request)
	{
		$master->setEmail($this->member_mail);
		if(strlen($this->member_password) > 0){
			$master->setPassword($this->member_password);
		}
		//ver2.1 seq1 add
		$master->setAttribute('mbr_name', $this->member_name);
		$master->setAttribute('mbr_name_kana', $this->member_name_kana);
		$master->setAttribute('mbr_sex_cd', $this->member_sex_cd);
       	if(checkdate($this->member_birth_month, $this->member_birth_day, $this->member_birth_year)){
			$master->setAttribute('mbr_birthday', $this->member_birth_year . '-'. $this->member_birth_month . '-'. $this->member_birth_day);
       	}else{
       		$master->setAttribute('mbr_birthday', null);
       	}
		$master->setAttribute('mbr_zip_cd', $this->member_zip_cd1 . $this->member_zip_cd2);
		$master->setAttribute('mbr_address1', $this->member_address1);
		$master->setAttribute('mbr_address2', $this->member_address2);
		$master->setAttribute('mbr_tel', $this->member_tel1.'-'.$this->member_tel2.'-'.$this->member_tel3);
		$master->setAttribute('mbr_self_introduction', $this->member_self_introduction);
	}
	
    public function getSexOptions()
    {
        $sex_options = array(1 => '男', 2 => '女');
        return $sex_options;
    }
    public function getAddress1Options()
    {
        $handler = new m_todofukenObjectHandler();
        $objects = $handler->getObjects('1=1');
        $ret = array();
        $ret[0] = '選択してください';
        foreach($objects as $obj){
            $ret[$obj->getId()] = $obj->getAttribute('tdf_name');
        }
        return $ret;
    }
    
    public function getYearOptions()
    {
    	$yearOptions = HNbFormUtility::getYearOptions(date('Y'),1900, '年');
    	return $yearOptions;
    }
    public function getMonthOptions()
    {
        $ret = array();
        $ret[0] = '月';
        for($i = 1; $i<=12; $i++){
            $ret[$i] = $i.'月';
        }
        return $ret;
    }
    public function getDayOptions()
    {
        $ret = array();
        $ret[0] = '日';
        for($i = 1; $i<=31; $i++){
            $ret[$i] = $i.'日';
        }
        return $ret;
    }
    
    public function getViewBirthYear()
    {
        $options =  $this->getYearOptions();
        return $options[$this->member_birth_year];
    } 
    
    public function getViewSex()
    {
        $options =  $this->getSexOptions();
        return $options[$this->member_sex_cd];
    }
    public function getViewAddress1()
    {
    	if($this->member_address1 > 0){
        	$options =  $this->getAddress1Options();
        	return $options[$this->member_address1];
    	}else{
    		return '';
    	}
    }
}

class MemberNewRegistForm extends MemberAccountBaseForm
{
	private $extend_require_parameter_list;
	public function fetch($master , $request)
	{
		parent::fetch($master, $request);
		
		// extend_require_parameter_list の入力必須チェック
		foreach($this->extend_require_parameter_list as $key_number){
			switch($key_number){
				case 1:
					if(strlen($this->member_name) ==0){
						$this->addError('お名前を入力してください');
					}
					break;
				case 2:
					if(strlen($this->member_name_kana) ==0){
						$this->addError('ふりがなを入力してください');
					}
					break;
				case 3:
					if($this->member_sex_cd != 1 && $this->member_sex_cd !=2){
						$this->addError('性別を入力してください');
					}
					break;
				case 4:
		            if(!checkdate($this->member_birth_month, $this->member_birth_day, $this->member_birth_year)){
						$this->addError('生年月日を入力してください');
					}
					break;
				case 5:
		            if((strlen($this->member_zip_cd1) != 3) || (strlen($this->member_zip_cd2) != 4) || (!is_numeric($this->member_zip_cd1)) || (!is_numeric($this->member_zip_cd2))){
						$this->addError('郵便番号を入力してください');
					}
					break;
				case 6:
					if(!$this->member_address1){
						$this->addError('都道府県を選択してください');
					}
					break;
				case 7:
					if(strlen($this->member_address2) ==0){
						$this->addError('都道府県以下の住所を入力してください');
					}
					break;
				case 8:
					if(strlen($this->member_tel1) ==0 || strlen($this->member_tel2) ==0 || strlen($this->member_tel3) ==0){
						$this->addError('電話番号を入力してください');
					}
					break;
				case 9:
					if(strlen($this->member_self_introduction) ==0){
						$this->addError('自己紹介を入力してください');
					}
					break;
			}
		}
		
		$validater = new HNbValidater();
		
		if(strlen($this->member_password) == 0){
			$this->addError('パスワードを入力してください。');
		}
		if(strlen($this->member_password) < 6){
			$this->addError('パスワードを6文字以上で入力してください。');
		}
		
		if(strlen($this->member_confirm_password) == 0){
			$this->addError('パスワード（確認用）を入力してください。');
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
	public function setRequireParameterList($list)
	{
		$this->extend_require_parameter_list = $list;
	}	
}

 
class MemberEditAccountForm extends MemberAccountBaseForm
{
	
	public function fetch($master , $request)
	{
		parent::fetch($master, $request);
		
		// メールアドレスの重複検査
		$handler = HNb::getHandler('MemberManager');
		if($handler->inMemberByEmail($this->member_mail, $this->id)){
			$this->addError('すでに使われているメールアドレスです');
		}	
		
		
	}
	
	public function load($master, $requset)
	{
		$this->member_mail = $master->getEmail();
		$this->id = $master->getId();
		
		//ver2.1 seq1 add
		$this->member_name = $master->getAttribute('mbr_name');
		$this->member_name_kana = $master->getAttribute('mbr_name_kana');
		$this->member_sex_cd = $master->getAttribute('mbr_sex_cd');
		list($this->member_birth_year, $this->member_birth_month, $this->member_birth_day) = explode('-', $master->getAttribute('mbr_birthday'));
		$this->member_zip_cd1 = substr($master->getAttribute('mbr_zip_cd'),0, 3);
		$this->member_zip_cd2 = substr($master->getAttribute('mbr_zip_cd'),3, 4);
		$this->member_address1 = $master->getAttribute('mbr_address1');
		$this->member_address2 = $master->getAttribute('mbr_address2');
		list($this->member_tel1, $this->member_tel2, $this->member_tel3) = explode('-', $master->getAttribute('mbr_tel'));
		$this->member_self_introduction = $master->getAttribute('mbr_self_introduction');
	}
	
}
?>