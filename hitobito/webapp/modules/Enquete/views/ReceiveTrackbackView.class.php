<?php
/**
 * ShortDescription
 *
 * �ʥӥ��󥱡��� �ȥ�å��Хå��쥹�ݥ�
 * *
 * @package
 * @author
 * @version
 */

class ReceiveTrackbackSuccessView extends SmartyView {
	public function execute () {
		$this->setTemplate('TrackbackSuccess.xml');
	}
}

class ReceiveTrackbackErrorView extends SmartyView {
	public function execute() {
		$request = $this->getContext()->getRequest();

		$this->setAttribute('enquete_trackback_error', join(', ', $request->getErrors()));
		$this->setTemplate('TrackbackError.xml');
	}
}
?>