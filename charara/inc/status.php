<?
// 似顔絵ステータス
define('STATUS_SERVER_RECEIVED', 1);	// 課金サーバから受信済み
define('STATUS_USER_RECEIVED', 2);		// ユーザからの申込み受付済み
define('STATUS_PHOTO_RECEIVED', 3);		// 写真受信済み
define('STATUS_STAFF_ASSIGNED', 4);		// 作画スタッフ割り当て済み
define('STATUS_MAKING', 5);						// 似顔絵作成中
define('STATUS_COMPLETED', 6);				// 似顔絵作成完了
define('STATUS_CHECK_OK', 7);					// チェックOK
define('STATUS_USER_NOTIFIED', 8);		// ユーザに通知済み
define('STATUS_DOWNLOADED', 9);				// ユーザダウンロード済み
define('STATUS_END', 10);							// 終了
define('STATUS_WAIT_REPLY', 11);			// メール返信待ち
?>