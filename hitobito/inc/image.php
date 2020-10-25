<?php
/**
 * ひとびと･net 共通ファイル
 *
 * 画像処理クラス
 *
 *
 * @package
 * @author
 * @version
 */

// ■画像保存処理
function save_image($file_id, $image_id = '') {
	$file = &$_FILES[$file_id];

	if ($file['size'] != 0) {
		if ($fp = fopen($file['tmp_name'], 'r')) {
			$data = fread($fp, $file['size']);
			fclose($fp);

			$rec['img_data_size'] = sql_number($file['size']);
			$rec['img_file_name'] = sql_char($file['name']);
			$rec['img_content_type'] = sql_char($file['type']);
			$rec['img_image_data'] = sql_bytea($data);

			if ($image_id) {
				db_update('t_image', $rec, "img_image_id=$image_id");
			} else {
				db_insert('t_image', $rec);
				$image_id = get_current_seq('t_image', 'img_image_id');
			}
		}
	}

	return $image_id;
}

// 画像削除
function delete_image($image_id) {
	if ($image_id)
		db_delete('t_image', "img_image_id=$image_id");
}

// ■画像出力
function disp_image($image_id) {
	$sql = sprintf("SELECT img_data_size,img_content_type,img_image_data FROM t_image WHERE img_image_id=%s", $image_id);
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);

		header("Content-type: $fetch->img_content_type");
		header("Content-length: $fetch->img_data_size");
		echo pg_unescape_bytea($fetch->img_image_data);
	}
	exit;
}
?>