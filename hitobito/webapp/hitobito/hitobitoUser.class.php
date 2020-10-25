<?php
/**
 * hitobitoUser
 * 
 * 
 */
class hitobitoUser extends BasicSecurityUser
{
	public function isMyNaviPage()
	{
		$navipage = hitobito::getNaviPageInfo();
		if($this->isNavi() && $navipage->getAttribute('nvp_navi_id') == $this->getAttribute('nav_navi_id')){
			return TRUE;
		}else{
			return FALSE;
		}
	}
	
	public function isMyAssistNaviPage()
	{
		$navipage = hitobito::getNaviPageInfo();
		if($this->isNavi() && $navipage->hasAssistNaviByNaviId($this->getAttribute('nav_navi_id'))){
			return TRUE;
		}else{
			return FALSE;
		}		
	}
	
	public function isNavi()
	{
		if($this->getAttribute('member_type') == 'navi')
		{
			return TRUE;
		}
		return FALSE;
	}
}
?>