<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ナビアンケート トラックバック受信アクション
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Enquete/lib/EnqueteBaseAction.class.php');
require_once MO_WEBAPP_DIR .'/modules/Navi/lib/notifyMail.class.php';
require_once MO_LIB_DIR . '/HNbSpamCheck.class.php';

class ReceiveTrackbackAction extends EnqueteBaseAction {
	// ■アクション実行
	public function execute() {
		$request = $this->getContext()->getRequest();

		$trackback = array();
		$trackback['enquete_id'] = $request->getParameter('enquete_id');
		$trackback['title'] = mb_convert_encoding(strip_tags($request->getParameter('title')), 'EUC-JP', 'UTF-8, EUC-JP, SJIS');
		$trackback['url'] = strip_tags($request->getParameter('url'));
		$trackback['blog_name'] = mb_convert_encoding(strip_tags($request->getParameter('blog_name')), 'EUC-JP', 'UTF-8,EUC-JP, SJIS');
		$trackback['excerpt'] = mb_convert_encoding(strip_tags($request->getParameter('excerpt')), 'EUC-JP', 'UTF-8, EUC-JP, SJIS');
		$this->db->insertTrackback($trackback);

		$enquete_id = $trackback['enquete_id'];
		$enquete_data = $this->db->getEnqueteData($enquete_id);
		$notifyValues = array(
            'TITLE' => $trackback['title'],
            'BLOG_NAME' => $trackback['blog_name'],
            'BLOG_URL' => $trackback['url'],
            'BODY' => $trackback['excerpt'],
			'ENTRY_TITLE' => $enquete_data['title'],
			'ENTRY_URL' => hitobito::getUrl() . "index.php?module=Enquete&action=ShowResult&enquete_id=$enquete_id"
            );
        $notifer = new NaviNotifyMail();
        $notifer->notify(hitobito::getNaviPageInfo(), 'trackback', $notifyValues);

		return View::SUCCESS;
	}

	// ■Validator登録
	public function registerValidators($validatorManager) {
		$validatorManager->registerName('enquete_id', true, 'No Enquete ID');
		$validatorManager->registerName('title', true, 'No Title');
		$validatorManager->registerName('url', true, 'No URL');
	}

	// ■入力データチェック
	public function validate() {
		$request = $this->getContext()->getRequest();

		$enquete_id = $request->getParameter('enquete_id');
		if ($enquete_id != '') {
			if (!is_numeric($enquete_id)) {
				$request->setError('enquete_id', 'Bad Enquete ID');
				return false;
			}

			$enquete = $this->db->getEnqueteData($enquete_id);
			if (!$enquete || $enquete['trackback_flag'] != 1) {
				$request->setError('enquete_id', 'Invalid Enquete ID');
				return false;
			}
		}

		// SPAMチェック 2007/05/23 aizawa
		$spamcheck = new HNbSpamCheck;
		if ($spamcheck->checkTrackbackSpam($request)) {
			$request->setError('enquete_id', 'Invalid Request');
			return false;
		}

		return true;
	}

	// ■アクション実行メソッド指定
	public function getRequestMethods() {
    	return Request::GET | Request::POST;
	}
}
?>
