<?php
/**
 * ナビアンケート結果 表示アクション
 *
 * @package top
 * @author y.fujimori<wfujimori@xxxxxxx.co.jp>
 * @version $id$
 */

require_once(MO_MODULE_DIR . '/Enquete/lib/EnqueteBaseAction.class.php');
require_once dirname(__FILE__).'/../config/config.inc.php';

class _blockEnqResultListAction extends EnqueteBaseAction 
{

	public function execute() 
	{
	
		$request = $this->getContext()->getRequest();
	
		// アンケート結果設定
		$enquete_data = $this->db->getEnqueteData(ENQUETE_RESULT_VIEW_ID);
		
		if (empty($enquete_data)) {
			return View::NONE;
		}
		$enquete_data['option'] = $this->db->getAnswerOption(ENQUETE_RESULT_VIEW_ID);
		$enquete_data['answer_count'] = $this->db->getAnswerCount(ENQUETE_RESULT_VIEW_ID);
		$request->setAttribute('enquete_data', $enquete_data);

		return View::SUCCESS;
	}

}
?>
