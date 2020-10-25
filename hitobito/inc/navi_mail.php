<?php
/**
 * ひとびと･net 共通ファイル
 *
 * ナビ宛メール送信処理
 *
 *
 * @package
 * @author
 * @version
 */

// ■メール送信処理
function send_navi_mail($navi_page_id, $template_id) {
	$sql = "SELECT mbr_mail_addr, nav_name1, nvp_title"
		. " FROM t_navi_page"
		. " JOIN t_navi ON nav_navi_id=nvp_navi_id"
		. " JOIN t_member ON mbr_member_id=nav_member_id"
		. " WHERE nvp_navi_page_id=$navi_page_id";
	$result = db_exec($sql);
	if (pg_num_rows($result)) {
		$fetch = pg_fetch_object($result, 0);

		// 送信メールテンプレート取得
		get_mail_template($template_id, $subject, $from, $cc, $bcc, $repty_to, $body);

		// 差込処理
		$body = str_replace('%MAIL_ADDR%', $fetch->mbr_mail_addr, $body);
		$body = str_replace('%NAVI_NAME%', $fetch->nav_name1, $body);
		$body = str_replace('%NAVI_PAGE_NAME%', $fetch->nvp_title, $body);

		// 送信メールデータセット
		send_mail_one($subject, $from, $fetch->mbr_mail_addr, $reply_to, $body, $cc, $bcc);
	}
}
?>