<?
/******************************************************
' System :きかせて・net共通
' Content:画像処理
'******************************************************/

// 画像ファイル保存
function save_image($image_id, &$file) {
	if ($file['size'] != 0) {
		if ($fp = fopen($file['tmp_name'], 'r')) {
			$data = fread($fp, $file['size']);
			fclose($fp);

			$rec['im_size'] = sql_number($file['size']);
			$rec['im_name'] = sql_char($file['name']);
			$rec['im_type'] = sql_char($file['type']);
			$rec['im_data'] = sql_char(base64_encode($data));

			if ($image_id) {
				db_update('t_image', $rec, "im_image_id=$image_id");
			} else {
				db_insert('t_image', $rec);
				$image_id = get_currval('t_image_im_image_id_seq');
			}
		}
	}

	return $image_id;
}

// 画像ファイル削除
function delete_image($image_id) {
	if ($image_id)
		db_delete('t_image', "im_image_id=$image_id");
}
?>