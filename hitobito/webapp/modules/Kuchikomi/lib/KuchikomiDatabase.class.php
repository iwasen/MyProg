<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * クチコミWEB データベース処理クラス
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_LIB_DIR . '/HNbDatabase.class.php');

class KuchikomiDatabase extends HNbDatabase {
	// ■ナビページのチャネルID取得
	public function getChannelId($navi_page_id) {
		$sql = "SELECT nvp_channel_id AS channel_id FROM t_navi_page WHERE nvp_navi_page_id=$navi_page_id";
		return $this->db->GetOne($sql);
	}

	// ■最新クチコミ情報一覧取得
	public function getKuchikomiList($channel_id, $disp_num) {
		$sql = "SELECT kck_image_url AS image_url"
					. ",kck_content AS content"
					. ",kck_page_url AS page_url"
					. ",kck_title AS title"
					. ",kck_shouhin AS shouhin"
				. " FROM t_kuchikomi"
				. " WHERE kck_keisai_flag=1" . ($channel_id ? " AND kck_kuchikomi_id IN (SELECT kch_kuchikomi_id FROM t_kuchikomi_channel WHERE kch_channel_id=$channel_id)" : "")
				. " ORDER BY kck_kuchikomi_id DESC"
				. " LIMIT $disp_num";
		return $this->db->GetAll($sql);
	}
}
?>