<?
// クリックカウンタ登録
function regist_click_counter($vol_no, &$body) {
	if ($_POST['cc_no'] != '') {
		$cc_no = explode('||', $_POST['cc_no']);
		$cc_url_name = explode('||', $_POST['cc_url_name']);
		$cc_jump_url = explode('||', $_POST['cc_jump_url']);

		for ($i = 0; $i < count($cc_no); $i++) {
			$rec['clc_url_name'] = sql_char($cc_url_name[$i]);
			$rec['clc_jump_url'] = sql_char($cc_jump_url[$i]);
			$rec['clc_start_date'] = sql_date($_POST['cc_start_date']);
			$rec['clc_end_date'] = sql_date($_POST['cc_end_date']);
			db_insert('t_click_counter', $rec);
			$cc_id = get_current_seq('t_click_counter', 'clc_cc_id');

			$rec['fln_vol_no'] = sql_number($vol_no);
			$rec['fln_cc_id'] = sql_number($cc_id);
			db_insert('t_follow_click', $rec);

			$body = str_replace("%CTID{$cc_no[$i]}%", $cc_id, $body);
		}
	}
}
?>