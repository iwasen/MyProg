<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * メルマガトラックバック受信アクション
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/MailMag/lib/MailMagBaseAction.class.php');
require_once MO_WEBAPP_DIR .'/modules/Navi/lib/notifyMail.class.php';
require_once MO_LIB_DIR . '/HNbSpamCheck.class.php';

class ReceiveTrackbackAction extends MailMagBaseAction {
	// ■アクション実行
	public function execute() {
		$request = $this->getContext()->getRequest();

		$trackback = array();
		$trackback['contents_id'] = $request->getParameter('contents_id');
		$trackback['title'] = mb_convert_encoding(strip_tags($request->getParameter('title')), 'EUC-JP', 'UTF-8, EUC-JP, SJIS');
		$trackback['url'] = strip_tags($request->getParameter('url'));
		$trackback['blog_name'] = mb_convert_encoding(strip_tags($request->getParameter('blog_name')), 'EUC-JP', 'UTF-8,EUC-JP, SJIS');
		$trackback['excerpt'] = mb_convert_encoding(strip_tags($request->getParameter('excerpt')), 'EUC-JP', 'UTF-8, EUC-JP, SJIS');
		$this->db->insertTrackback($trackback);

		$contents_id = $trackback['contents_id'];
		$mailmag_contents = $this->db->getMailMagContents($contents_id);
		$notifyValues = array(
            'TITLE' => $trackback['title'],
            'BLOG_NAME' => $trackback['blog_name'],
            'BLOG_URL' => $trackback['url'],
            'BODY' => $trackback['excerpt'],
			'ENTRY_TITLE' => $mailmag_contents['subject'],
			'ENTRY_URL' => hitobito::getUrl() . "index.php?module=MailMag&action=BacknumberContents&contents_id=$contents_id"
            );
        $notifer = new NaviNotifyMail();
        $notifer->notify(hitobito::getNaviPageInfo(), 'trackback', $notifyValues);
		return View::SUCCESS;
	}

	// ■Validator登録
	public function registerValidators($validatorManager) {
		$validatorManager->registerName('contents_id', true, 'No Contents ID');
		$validatorManager->registerName('title', true, 'No Title');
		$validatorManager->registerName('url', true, 'No URL');
	}

	// ■入力データチェック
	public function validate() {
		$request = $this->getContext()->getRequest();

		$contents_id = $request->getParameter('contents_id');
		if ($contents_id != '') {
			if (!is_numeric($contents_id)) {
				$request->setError('contents_id', 'Bad Contents ID');
				return false;
			}

			$mailmag = $this->db->getMailMagContents($contents_id);
			if (!$mailmag || $mailmag['trackback_flag'] != 1) {
				$request->setError('contents_id', 'Invalid Contents ID');
				return false;
			}
		}

		// SPAMチェック 2007/05/23 aizawa
		$spamcheck = new HNbSpamCheck;
		if ($spamcheck->checkTrackbackSpam($request)) {
			$request->setError('contents_id', 'Invalid Request');
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
