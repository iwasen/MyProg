<?php
/**
 * ひとびと･net 共通ファイル
 *
 * イチオシナビ選択処理
 *
 *
 * @package
 * @author
 * @version
 */

// イチオシナビ選択
function select_ichioshi_navi($channel_id = '') {
	db_begin_trans();

	$sql = "SELECT inv_channel_id, inv_position, inv_selection_mode, inv_fix_navi_page_id, inv_loop_navi_page_id, inv_current_navi_page_id"
			. " FROM t_ichioshi_navi"
			. ($channel_id != '' ? " WHERE inv_channel_id=$channel_id" : "")
			. " ORDER BY inv_channel_id, inv_position";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		if ($fetch->inv_position == 1)
			$selected_navi_page_id = '';

		// 選択モードによる処理分け
		switch ($fetch->inv_selection_mode) {
		case 1:		// 固定モード
			$current_navi_page_id = $fetch->inv_fix_navi_page_id;
			break;
		case 2:		// ランダム(人気記事連動1-5位)
			$sb1 = "SELECT nvp_navi_page_id"
					. " FROM t_blog_ranking"
					. " JOIN t_blog_article ON bla_article_id=blr_article_id"
					. " JOIN t_navi_page ON nvp_blog_id=bla_blog_id"
					. " WHERE bla_status=1 AND bla_open_flag=1 AND bla_keisai_flag=1" . ($selected_navi_page_id != '' ? " AND nvp_navi_page_id NOT IN ($selected_navi_page_id)" : "")
					. " ORDER BY blr_index DESC"
					. " LIMIT 5";
			$sql = "SELECT nvp_navi_page_id FROM ($sb1) sb1 ORDER BY random() LIMIT 1";
			$current_navi_page_id = db_fetch1($sql);
			break;
		case 3:		// ランダム
			$sb1 = "SELECT nvp_navi_page_id"
					. " FROM t_navi_page"
					. " WHERE nvp_status=1 AND nvp_open_flag=1" . ($selected_navi_page_id != '' ? " AND nvp_navi_page_id NOT IN ($selected_navi_page_id)" : "")
					. " ORDER BY random()"
					. " LIMIT 1";
			$sql = "SELECT nvp_navi_page_id FROM ($sb1) sb1 ORDER BY random() LIMIT 1";
			$current_navi_page_id = db_fetch1($sql);
			break;
		case 4:		// ループ
			$ary = explode(',', $fetch->inv_loop_navi_page_id);
			$key = array_search($fetch->inv_current_navi_page_id, $ary);
			if ($key === false)
				$key = 0;
			else {
				if (++$key >= count($ary))
					$key = 0;
			}
			$current_navi_page_id = $ary[$key];
			break;
		}

		// 既に選択済みのナビページ
		if ($selected_navi_page_id != '')
			$selected_navi_page_id .= ',';
		$selected_navi_page_id .= $current_navi_page_id;

		// DB更新
		$rec['inv_current_navi_page_id'] = sql_number($current_navi_page_id);
		db_update('t_ichioshi_navi', $rec, sprintf("inv_channel_id=%s AND inv_position=%s", sql_number($fetch->inv_channel_id), sql_number($fetch->inv_position)));
	}

	db_commit_trans();
}
?>