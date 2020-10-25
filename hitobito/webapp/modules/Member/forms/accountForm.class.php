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
		
		// ����ɬ�ܹ��ܤΥ����å� mail, password, confirm_password
		if(strlen($this->member_mail) == 0){
			$this->addError('�᡼�륢�ɥ쥹�����Ϥ��Ƥ�������');
		}
		// �᡼�륢�ɥ쥹������������
		if(!$validater->validateEmail($this->member_mail)){
			$this->addError('�᡼�륢�ɥ쥹��ͭ���ǤϤ���ޤ���');
		}
		// pass���פ��Ƥ��뤫
		if($this->member_password != $this->member_confirm_password){
			$this->addError('�ѥ���ɤȥѥ���ɺ����Ϥ����פ��ޤ���');
		}

        //ver2.1 seq1 add parameter validate
        // sex_cd
        if($this->member_sex_cd > 2){
            dir('Bad request');
        }
        // zip
        if(!empty($this->member_zip_cd1) || !empty($this->member_zip_cd2)){
            if((strlen($this->member_zip_cd1) != 3) || (strlen($this->member_zip_cd2) != 4) || (!is_numeric($this->member_zip_cd1)) || (!is_numeric($this->member_zip_cd2))){
                $this->addError('͹���ֹ�����������Ϥ��Ƥ���������');
            }
        }
        // birthday
        if(!empty($this->member_birth_year) || !empty($this->member_birth_month) || !empty($this->member_birth_day)){
            if(!checkdate($this->member_birth_month, $this->member_birth_day, $this->member_birth_year)){
                $this->addError('��ǯ���������������Ϥ��Ƥ���������');
            }
        }
        // tel
        if(!empty($this->member_tel1) || !empty($this->member_tel2) || !empty($this->member_tel3)){
            $tel = $this->member_tel1.'-'.$this->member_tel2.'-'.$this->member_tel3;
            if(!preg_match('/^[0-9]+-[0-9]+-[0-9]+$/', $tel)){
                $this->addError('�����ֹ�����������Ϥ��Ƥ���������');
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
        $sex_options = array(1 => '��', 2 => '��');
        return $sex_options;
    }
    public function getAddress1Options()
    {
        $handler = new m_todofukenObjectHandler();
        $objects = $handler->getObjects('1=1');
        $ret = array();
        $ret[0] = '���򤷤Ƥ�������';
        foreach($objects as $obj){
            $ret[$obj->getId()] = $obj->getAttribute('tdf_name');
        }
        return $ret;
    }
    
    public function getYearOptions()
    {
    	$yearOptions = HNbFormUtility::getYearOptions(date('Y'),1900, 'ǯ');
    	return $yearOptions;
    }
    public function getMonthOptions()
    {
        $ret = array();
        $ret[0] = '��';
        for($i = 1; $i<=12; $i++){
            $ret[$i] = $i.'��';
        }
        return $ret;
    }
    public function getDayOptions()
    {
        $ret = array();
        $ret[0] = '��';
        for($i = 1; $i<=31; $i++){
            $ret[$i] = $i.'��';
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
		
		// extend_require_parameter_list ������ɬ�ܥ����å�
		foreach($this->extend_require_parameter_list as $key_number){
			switch($key_number){
				case 1:
					if(strlen($this->member_name) ==0){
						$this->addError('��̾�������Ϥ��Ƥ�������');
					}
					break;
				case 2:
					if(strlen($this->member_name_kana) ==0){
						$this->addError('�դ꤬�ʤ����Ϥ��Ƥ�������');
					}
					break;
				case 3:
					if($this->member_sex_cd != 1 && $this->member_sex_cd !=2){
						$this->addError('���̤����Ϥ��Ƥ�������');
					}
					break;
				case 4:
		            if(!checkdate($this->member_birth_month, $this->member_birth_day, $this->member_birth_year)){
						$this->addError('��ǯ���������Ϥ��Ƥ�������');
					}
					break;
				case 5:
		            if((strlen($this->member_zip_cd1) != 3) || (strlen($this->member_zip_cd2) != 4) || (!is_numeric($this->member_zip_cd1)) || (!is_numeric($this->member_zip_cd2))){
						$this->addError('͹���ֹ�����Ϥ��Ƥ�������');
					}
					break;
				case 6:
					if(!$this->member_address1){
						$this->addError('��ƻ�ܸ������򤷤Ƥ�������');
					}
					break;
				case 7:
					if(strlen($this->member_address2) ==0){
						$this->addError('��ƻ�ܸ��ʲ��ν�������Ϥ��Ƥ�������');
					}
					break;
				case 8:
					if(strlen($this->member_tel1) ==0 || strlen($this->member_tel2) ==0 || strlen($this->member_tel3) ==0){
						$this->addError('�����ֹ�����Ϥ��Ƥ�������');
					}
					break;
				case 9:
					if(strlen($this->member_self_introduction) ==0){
						$this->addError('���ʾҲ�����Ϥ��Ƥ�������');
					}
					break;
			}
		}
		
		$validater = new HNbValidater();
		
		if(strlen($this->member_password) == 0){
			$this->addError('�ѥ���ɤ����Ϥ��Ƥ���������');
		}
		if(strlen($this->member_password) < 6){
			$this->addError('�ѥ���ɤ�6ʸ���ʾ�����Ϥ��Ƥ���������');
		}
		
		if(strlen($this->member_confirm_password) == 0){
			$this->addError('�ѥ���ɡʳ�ǧ�ѡˤ����Ϥ��Ƥ���������');
		}

		// �᡼�륢�ɥ쥹�ν�ʣ����
		$handler = HNb::getHandler('MemberManager');
		if($handler->isEmailUsedInAliveMemberAccount($this->member_mail)){
			$this->addError('���Ǥ˻Ȥ��Ƥ���᡼�륢�ɥ쥹�Ǥ�');
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
		
		// �᡼�륢�ɥ쥹�ν�ʣ����
		$handler = HNb::getHandler('MemberManager');
		if($handler->inMemberByEmail($this->member_mail, $this->id)){
			$this->addError('���Ǥ˻Ȥ��Ƥ���᡼�륢�ɥ쥹�Ǥ�');
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