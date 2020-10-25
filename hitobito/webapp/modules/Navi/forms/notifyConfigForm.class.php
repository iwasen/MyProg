<?php
/**
*
* @author Ryuji
* @version $Id: notifyConfigForm.class.php,v 1.2 2006/05/29 03:26:01 ryu Exp $
*/
require_once MO_WEBAPP_DIR .'/lib/HNbActionForm.class.php';
abstract class NaviNotifyConfigForm extends HNbSemiAutoActionForm
{
	public
	    $navi_notify;
	protected $no_navi_notify_message = '';
    /**
     * ʸ����ѥ�᡼��������������֤�
     */
    protected function getStringParameterKeys()
    {
        return array();
    }
    /**
     * �����ѥ�᡼��������������֤�
     */
    protected function getIntegerParameterKeys()
    {
    	return array('navi_notify');
    }
    /**
     * ����ɬ�ܥѥ�᡼���򥭡����ͤ˥��顼�᥻�á��������줿Ϣ��������֤�
     * @example return array('email' => '�᡼�륢�ɥ쥹�����Ϥ���Ƥ��ޤ���');
     */
     protected function getRequireParameterKeys()
     {
        return array();
    }
    
}

class NaviCommentNotifyConfigForm extends NaviNotifyConfigForm
{
    public function fetch($master, $request)
    {
        parent::fetch($master, $request);
        if(!$request->hasParameter('navi_notify')){
            $this->addError(HNb :: tr('���������Τ����Ѥ��뤫���򤷤Ƥ�������'));
        }
    }
    public function load($master, $request)
    {
        $this->navi_notify = $master->getAttribute('nnt_comment_notify');
    }
    
    public function update($master, $request)
    {
        $master->setAttribute('nnt_comment_notify', $this->navi_notify);
    }
}

class NaviTrackbackNotifyConfigForm extends NaviNotifyConfigForm
{
    public function fetch($master, $request)
    {
        parent::fetch($master, $request);
        if(!$request->hasParameter('navi_notify')){
            $this->addError(HNb :: tr('�ȥ�å��Хå����Τ����Ѥ��뤫���򤷤Ƥ�������'));
        }
    }
    
    public function load($master, $request)
    {
        $this->navi_notify = $master->getAttribute('nnt_trackback_notify');
    }
    
    public function update($master, $request)
    {
        $master->setAttribute('nnt_trackback_notify', $this->navi_notify);
    }
}



class NaviContactNotifyConfigForm extends NaviNotifyConfigForm
{
    public function fetch($master, $request)
    {
        parent::fetch($master, $request);
        if(!$request->hasParameter('navi_notify')){
            $this->addError(HNb :: tr('�䤤��碌���Τ����Ѥ��뤫���򤷤Ƥ�������'));
        }
    }
    
    public function load($master, $request)
    {
        $this->navi_notify = $master->getAttribute('nnt_contact_notify');
    }
    
    public function update($master, $request)
    {
        $master->setAttribute('nnt_contact_notify', $this->navi_notify);
    }
}

?>