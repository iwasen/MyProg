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
     * 文字列パラメータキーの配列を返す
     */
    protected function getStringParameterKeys()
    {
        return array();
    }
    /**
     * 整数パラメータキーの配列を返す
     */
    protected function getIntegerParameterKeys()
    {
    	return array('navi_notify');
    }
    /**
     * 入力必須パラメータをキー、値にエラーメセッージを入れた連想配列を返す
     * @example return array('email' => 'メールアドレスが入力されていません');
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
            $this->addError(HNb :: tr('コメント通知を利用するか選択してください'));
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
            $this->addError(HNb :: tr('トラックバック通知を利用するか選択してください'));
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
            $this->addError(HNb :: tr('問い合わせ通知を利用するか選択してください'));
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