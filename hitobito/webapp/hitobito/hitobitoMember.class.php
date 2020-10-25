<?php
/**
 * @author Ryuji
 * $Id: hitobitoMember.class.php,v 1.6 2006/03/09 08:21:57 ryu Exp $
 */
class hitobitoMember
{
//	public	$email = '',
//			$password =''
//	;
	private	
			//$member_id,
			$attributes = array()
	;
	
	
	public function setRegistDate($time)
	{
		$this->attributes['mbr_regist_date'] = $time;
	}
	/**
	 * 退会
	 * @param integer	$resign_id
	 */
	public function resign($resign_id)
	{
		$this->attributes['mbr_resign_id'] = $resign_id;
		$this->attributes['mbr_status'] = 9;
		$this->attributes['mbr_resign_date'] = time();
	}
	
	public function setAttributes($attributes)
	{
		$this->attributes = $attributes;
		$this->email = $attributes['mbr_mail_addr'];
		$this->passowrd = $attributes['mbr_password'];
	}
	
	public function getAttributes()
	{
		return $this->attributes;
	}
	
	public function getId()
	{
		return $this->attributes['mbr_member_id'];
	}
	
	public function getEmail()
	{
		return $this->attributes['mbr_mail_addr'];
	}
	
	public function getHitobito_letter()
	{
		return $this->attributes['mbr_hitobito_letter'];
	}
	
	public function getStatus()
	{
		return $this->attributes['mbr_status'];
	}
	
	public function setEmail($email)
	{
		$this->attributes['mbr_mail_addr'] = $email;
		$this->attributes['mbr_mail_addr_low'] = strtolower($email);
	}
	
	public function setHitobito_letter($letter)
	{
		$this->attributes['mbr_hitobito_letter'] = $letter;
	}
	
	public function setStatus($status)
	{
		$this->attributes['mbr_status'] = $status;
	}
	
	public function setPassword($password)
	{
		$this->attributes['mbr_password'] = md5($password);
	}
	
	public function getMd5Password()
	{
		return $this->attributes['mbr_password'];
	}
	
	public function setMd5Password($md5Password)
	{
		$this->attributes['mbr_password'] = $md5Password;
	}
	/**
	 * 
	 */
	public function getAttribute($key)
	{
		return $this->attributes[$key];
	}
	
	public function setAttribute($key,$var)
	{
	    $this->attributes[$key] = $var;
	}
	
	public function getName()
	{
		return '*****';
	} 
	public function isNew()
	{
		if($this->getId() > 0){
			return FALSE;
		}else{
			return TRUE;
		}
	}
}

class hitobitoNavi extends hitobitoMember
{
	private $naviInfo;
	public function getName()
	{
		return $this->naviInfo->getAttribute('nav_name1').$this->naviInfo->getAttribute('nav_name2');
	}
	public function setNaviInfo($naviInfo)
	{
		$this->naviInfo = $naviInfo;
	}
	
	// TODO prefixで振り分けるget Attribute
}

class hitobitoWaiwaiMember extends hitobitoMember
{
	private $roomId;
	private $nickname;
	private $isNew;
	
	public function setNickname($nickname)
	{
		$this->setAttribute('rmm_nickname', $nickname);
	}
	public function getNickname()
	{
		return $this->getAttribute('rmm_nickname');
	}
	
	public function setWaiwaiId($id)
	{
		$this->roomId = $id;
	}
	
	public function getWaiwaiId(){
		return $this->roomId;
	}
	
	public function setNew($boolian = TRUE){
		$this->isNew = $boolian;
	}
	public function isNew()
	{
		return $this->isNew;
	}
}
?>