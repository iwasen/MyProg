<?php
/**
*
* @author Ryuji
* @version $Id: dailyReportConfigForm.class.php,v 1.2 2006/05/29 03:26:01 ryu Exp $
*/
require_once MO_WEBAPP_DIR .'/lib/HNbActionForm.class.php';
class NaviDailyReportConfigForm extends HNbSemiAutoActionForm
{
	public
	    $navi_status,
	    $navi_comment_report,
	    $navi_trackback_report,
	    $navi_contact_report,
	    $navi_page_view_report,
	    $navi_visiter_report,
	    $navi_answer_report,
	    $navi_last_guest_report,
	    $navi_last_regist_report,
	    $navi_last_resign_report,
	    $navi_poster_report,
	    $navi_posts_report;
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
    	return array(
    	    'navi_status',
    	    'navi_comment_report',
    	    'navi_trackback_report',
    	    'navi_contact_report',
    	    'navi_page_view_report',
    	    'navi_visiter_report',
    	    'navi_answer_report',
    	    'navi_last_guest_report',
    	    'navi_last_regist_report',
    	    'navi_last_resign_report',
    	    'navi_poster_report',
    	    'navi_posts_report'
    	);
    }
    /**
     * 入力必須パラメータをキー、値にエラーメセッージを入れた連想配列を返す
     * @example return array('email' => 'メールアドレスが入力されていません');
     */
    protected function getRequireParameterKeys()
    {
        return array();
    }
    public function fetch($master, $request)
    {
        parent::fetch($master, $request);
        if(!$request->hasParameter('navi_status')){
            $this->addError(HNb::tr('デイリーレポートを利用するか選択してください'));
        }
        $this->navi_comment_report = ($this->navi_comment_report > 0) ? 1 : 0;
        $this->navi_trackback_report = ($this->navi_trackback_report > 0) ? 1 : 0;
        $this->navi_contact_report = ($this->navi_contact_report > 0) ? 1 : 0;
        $this->navi_page_view_report = ($this->navi_page_view_report > 0) ? 1 : 0;
        $this->navi_visiter_report = ($this->navi_visiter_report > 0) ? 1 : 0;
        $this->navi_answer_report = ($this->navi_answer_report > 0) ? 1 : 0;
        $this->navi_last_guest_report = ($this->navi_last_guest_report > 0) ? 1 : 0;
        $this->navi_last_regist_report = ($this->navi_last_regist_report > 0) ? 1 : 0;
        $this->navi_last_resign_report = ($this->navi_last_resign_report > 0) ? 1 : 0;
        $this->navi_poster_report = ($this->navi_poster_report > 0) ? 1 : 0;
        $this->navi_posts_report = ($this->navi_posts_report > 0) ? 1 : 0;
    }
    
    public function load($master,$request)
    {
        $this->navi_status = $master->getAttribute('ndr_status');
        $this->navi_comment_report = $master->getAttribute('ndr_comment_report');
        $this->navi_trackback_report = $master->getAttribute('ndr_trackback_report');
        $this->navi_contact_report = $master->getAttribute('ndr_contact_report');
        $this->navi_page_view_report =$master->getAttribute('ndr_page_view_report');
        $this->navi_visiter_report = $master->getAttribute('ndr_visiter_report');
        $this->navi_answer_report = $master->getAttribute('ndr_answer_report');
        $this->navi_last_guest_report = $master->getAttribute('ndr_last_guest_report');
        $this->navi_last_regist_report = $master->getAttribute('ndr_last_regist_report');
        $this->navi_last_resign_report = $master->getAttribute('ndr_last_resign_report');
        $this->navi_poster_report = $master->getAttribute('ndr_poster_report');
        $this->navi_posts_report = $master->getAttribute('ndr_posts_report');
    }
    
    public function update($master, $request)
    {
        $master->setAttribute('ndr_status', $this->navi_status);
        $master->setAttribute('ndr_comment_report', $this->navi_comment_report);
        $master->setAttribute('ndr_trackback_report', $this->navi_trackback_report);
        $master->setAttribute('ndr_contact_report', $this->navi_contact_report);
        $master->setAttribute('ndr_page_view_report', $this->navi_page_view_report);
        $master->setAttribute('ndr_visiter_report', $this->navi_visiter_report);
        $master->setAttribute('ndr_answer_report', $this->navi_answer_report);
        $master->setAttribute('ndr_last_guest_report', $this->navi_last_guest_report);
        $master->setAttribute('ndr_last_regist_report', $this->navi_last_regist_report);
        $master->setAttribute('ndr_last_resign_report', $this->navi_last_resign_report);
        $master->setAttribute('ndr_poster_report', $this->navi_poster_report);
        $master->setAttribute('ndr_posts_report', $this->navi_posts_report);
    }
}
?>