<?php

require_once MO_WEBAPP_DIR .'/lib/HNbActionForm.class.php';
abstract class NaviAdminModifyCommentForm extends HNbSemiAutoActionForm
{
	public
		$navi_poster_name,
		$navi_comment,
		$navi_status,
		$navi_date;
    /**
     * ʸ����ѥ�᡼��������������֤�
     */
    protected function getStringParameterKeys()
    {
    	return array('navi_poster_name', 'navi_comment');
    }
    /**
     * �����ѥ�᡼��������������֤�
     */
    protected function getIntegerParameterKeys()
    {
    	return array('navi_status');
    }
    /**
     * ����ɬ�ܥѥ�᡼���򥭡����ͤ˥��顼�᥻�á��������줿Ϣ��������֤�
     * @example return array('email' => '�᡼�륢�ɥ쥹�����Ϥ���Ƥ��ޤ���');
     */
    protected function getRequireParameterKeys()
    {
    	return array(
			'navi_poster_name' => HNb::tr('��Ƽ�̾�����Ϥ��Ƥ���������'), 
			'navi_comment' => HNb::tr('������Ƥ����Ϥ��Ƥ���������'));
    }
}
class BlogAdminModifyCommentForm extends NaviAdminModifyCommentForm
{
	public function load($master, $requset)
	{
		$this->navi_poster_name = $master->getAttribute('blo_user_name');
		$this->navi_comment = $master->getAttribute('blo_comment');
		$this->navi_status = $master->getAttribute('blo_open_flag');
		$this->navi_date = $master->getAttribute('blo_date');
	}
	
	public function update($master, $request)
	{
		
		$master->setAttribute('blo_user_name', $this->navi_poster_name);
		$master->setAttribute('blo_comment', $this->navi_comment);
		$master->setAttribute('blo_open_flag', $this->navi_status);
	}
	
}
class EnqueteAdminModifyCommentForm extends NaviAdminModifyCommentForm
{
	public function load($master, $requset)
	{
		$this->navi_poster_name = $master->getAttribute('neo_user_name');
		$this->navi_comment = $master->getAttribute('neo_comment');
		$this->navi_status = $master->getAttribute('neo_open_flag');
		$this->navi_date = $master->getAttribute('neo_date');
	}
	
	public function update($master, $request)
	{
		
		$master->setAttribute('neo_user_name', $this->navi_poster_name);
		$master->setAttribute('neo_comment', $this->navi_comment);
		$master->setAttribute('neo_open_flag', $this->navi_status);
	}
	
}
class MelmagaAdminModifyCommentForm extends NaviAdminModifyCommentForm
{
	public function load($master, $requset)
	{
		$this->navi_poster_name = $master->getAttribute('mmo_user_name');
		$this->navi_comment = $master->getAttribute('mmo_comment');
		$this->navi_status = $master->getAttribute('mmo_open_flag');
		$this->navi_date = $master->getAttribute('mmo_date');
	}
	
	public function update($master, $request)
	{
		
		$master->setAttribute('mmo_user_name', $this->navi_poster_name);
		$master->setAttribute('mmo_comment', $this->navi_comment);
		$master->setAttribute('mmo_open_flag', $this->navi_status);
	}
	
}
?>