<?
/******************************************************
' System :「きゃららFactory」クレオ（fude01）
' Content:応募情報クラス
'******************************************************/

class reg_fude01 {
	var $active;
	var $df_seq_no;
	var $df_creo_id;
	var $df_name1;
	var $df_name2;
	var	$df_name1_kana;
	var	$df_name2_kana;
	var	$df_mail_addr;
	var $week_no;
	// コンストラクタ
	function reg_fude01() {
		$this->active = true;
	}

	// 週番号取得
	function get_week_no($today) {
		if ($today >= '20031224' && $today <= '20040108')
			$this->week_no = 1;
		elseif ($today >= '20040109' && $today <= '20040115')
			$this->week_no = 2;
		elseif ($today >= '20040116' && $today <= '20040122')
			$this->week_no = 3;
		elseif ($today >= '20040123' && $today <= '20040129')
			$this->week_no = 4;
		elseif ($today >= '20040130' && $today <= '20040205')
			$this->week_no = 5;
		elseif ($today >= '20040206' && $today <= '20040212')
			$this->week_no = 6;
		elseif ($today >= '20040213' && $today <= '20040219')
			$this->week_no = 7;
		elseif ($today >= '20040220' && $today <= '20040226')
			$this->week_no = 8;
		elseif ($today >= '20040227' && $today <= '20040304')
			$this->week_no = 9;
		elseif ($today >= '20040305' && $today <= '20040311')
			$this->week_no = 10;
		else
			$this->week_no = '';
	}

	// DBに保存
	function save_db() {
		$sql = sprintf("INSERT INTO d_fude01 (df_week_no,df_creo_id,df_name1,df_name2,df_name1_kana,df_name2_kana,df_mail_addr) VALUES (%s,%s,%s,%s,%s,%s,%s)",
			sql_number($this->week_no),
			sql_char($this->df_creo_id),
			sql_char($this->df_name1),
			sql_char($this->df_name2),
			sql_char($this->df_name1_kana),
			sql_char($this->df_name2_kana),
			sql_char($this->df_mail_addr));
		db_exec($sql);
		
	}
}

?>