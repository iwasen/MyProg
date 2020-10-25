<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * メルマガ管理 バックナンバー一覧表示ビュー
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/MailMag/lib/MailMagBaseView.class.php');

class BacknumberListSuccessView extends MailMagBaseView {
	// ■ビュー実行
	function execute() {
		$request = $this->getContext()->getRequest();

		$this->setTemplate('BacknumberList.html');

		$mailmag = $request->getAttribute('mailmag');
		$mailmag['publish_count'] = number_format($mailmag['publish_count']);
		$mailmag['reader_num'] = number_format($mailmag['reader_num']);
		$mailmag['last_date'] = $this->format_datetime($mailmag['last_date'], '-');

		$backnumber_ary = &$mailmag['backnumber_ary'];
		if (is_array($backnumber_ary)) {
			foreach ($backnumber_ary as $i => &$list) {
				$list['send_date'] = $this->format_datetime($list['send_date']);
				$list['bgcolor'] = $i % 2 ? '#FFFFFF' : '#E6F2FF';
			}
		}
		$this->setAttribute('mailmag', $mailmag);

		$this->useMainTemplate();
	}
}
?>