<?php
function err_msg ($msg_code, $p_titl) {
	switch ($msg_code) {
	case "PP001":	//publisher_id 取得エラー
		$l_msg = "データベースに接続できませんでした";
		break;
	case "PP002":	//publisherマスタ　アクセスエラー
		$l_msg = "データベースに接続できませんでした";
		break;
	case "PM001":	//magazine_id 取得エラー
		$l_msg = "データベースに接続できませんでした";
		break;
	case "PM002":	//magazineマスタ　アクセスエラー
		$l_msg = "データベースに接続できませんでした";
		break;
	default:
		$l_msg = "エラー";
		break;
	}
	$print_msg =  $l_msg . "(" . $msg_code . ")";
	include ("err-msg.html");
}
?>
