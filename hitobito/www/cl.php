<?php
/**
 * ひとびと･net ユーザページ
 *
 * メンバー募集用クリックカウンター処理
 *
 *
 * @package
 * @author
 * @version
 */

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/crypt.php");

// パラメータ取得
$code = trim($_SERVER['QUERY_STRING']);

if ($code != '') {
	$recruit_id = decrypt_id($code);
	if ($recruit_id) {
		// 飛び先URL取得
		$sql = sprintf("SELECT rct_jump_url FROM t_recruit_click WHERE rct_recruit_id=%s", sql_number($recruit_id));
		$jump_url = db_fetch1($sql);

		if ($jump_url != '') {
			db_begin_trans();

			// クリック数カウントアップ
			$rec['rcc_click_count'] = 'rcc_click_count+1';
			db_update('c_recruit_click', $rec, sprintf("rcc_recruit_id=%s", sql_number($recruit_id)));

			// クリック履歴
			$rec['rcl_recruit_id'] = sql_number($recruit_id);
			$rec['rcl_ip_adr'] = sql_char($_SERVER['REMOTE_ADDR']);
			db_insert('l_recruit_click', $rec);

			db_commit_trans();

			// クッキーにIDをセット
			SetCookie('cookie_recruit_id', $recruit_id, 0, '/');

			redirect($jump_url);
		}
	}
}

// パラメータが不正の場合
header('HTTP/1.0 404 Not Found');
exit;
?>