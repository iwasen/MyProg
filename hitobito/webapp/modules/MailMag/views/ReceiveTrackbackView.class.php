<?php
/**
 * ShortDescription
 *
 * メルマガトラックバックレスポンス
 * *
 * @package
 * @author
 * @version
 */

class ReceiveTrackbackSuccessView extends SmartyView {
	public function execute() {
		$this->setTemplate('TrackbackSuccess.xml');
	}
}

class ReceiveTrackbackErrorView extends SmartyView {
	public function execute() {
		$request = $this->getContext()->getRequest();

		$this->setAttribute('mailmag_trackback_error', join(', ', $request->getErrors()));
		$this->setTemplate('TrackbackError.xml');
	}
}
?>