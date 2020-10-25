<?php
include(MO_LIB_DIR . '/adodb/adodb.inc.php');
include(MO_LIB_DIR . '/adodb/adodb-errorhandler.inc.php');

abstract class HNbDatabase {
	protected $db;

	// ■コンストラクタ
	public function __construct() {
		$this->db = &ADONewConxxxtion('postgres');
		$this->db->Conxxxt(HNB_DB_HOST, HNB_DB_USER, HNB_DB_PASS, HNB_DB_NAME);
		$this->db->raiseErrorFn = 'ADODB_Error_Handler';		// for debug
	}

	// ■カレントシーケンス取得
	public function getCurrentSequence($table, $field) {
		$sql = "SELECT currval('{$table}_{$field}_seq')";
		return $this->db->GetOne($sql);
	}

	// ■次のシーケンス取得
	public function getNextSequence($table, $field) {
		$sql = "SELECT nextval('{$table}_{$field}_seq')";
		return $this->db->GetOne($sql);
	}

	// ■定数取得
	public function getConst($key) {
		$sql = "SELECT sys_value FROM m_system WHERE sys_key='$key'";
		return $this->db->GetOne($sql);
	}

	// ■新規ページビューカウンタ取得
	public function getNewPageViewId($log_flag = true) {
		$rec = array();
		$rec['pvc_log_flag'] = $log_flag ? 1 : 0;
		$this->db->AutoExecute('c_page_view', $rec, 'INSERT');
		return $this->getCurrentSequence('c_page_view', 'pvc_pv_id');
	}

	// ■ページビューカウント
	public function countPageView($pv_id) {
		if ($pv_id) {
			$this->db->BeginTrans();

			// ページビューカウント
			$rec = array();
			$rec['pvc_pv_count'] = "pvc_pv_count + 1";
			$this->db->AutoExecute('c_page_view', $rec, 'UPDATE', "pvc_pv_id=$pv_id");

			// ページビュー履歴
			$sql = "SELECT pvc_log_flag FROM c_page_view WHERE pvc_pv_id=$pv_id";
			if ($this->db->GetOne($sql)) {
				$rec = array();
				$rec['pvl_pv_id'] = $pv_id;
				$rec['pvl_ip_addr'] = $_SERVER['REMOTE_ADDR'];
				$this->db->AutoExecute('l_page_view', $rec, 'INSERT');
			}

			$this->db->CommitTrans();
		}
	}

	// ■NGワードチェック
	public function checkNGWord($text) {
		// 全角英字→半角英字、半角／全角カタカナ→全角ひらがな
		$check_text = strtoupper(mb_convert_kana($text, 'rHcV'));

		$ngword_ary = array();

		$sql = "SELECT ngw_ngword_id, ngw_word"
				. " FROM m_ngword"
				. " WHERE ngw_status=1"
				. " ORDER BY ngw_ngword_id";
		$ary = $this->db->GetAll($sql);
		if (is_array($ary)) {
			foreach ($ary as $ngword) {
				if (strpos($check_text, strtoupper(mb_convert_kana($ngword['ngw_word'], 'rHcV'))) !== false)
					$ngword_ary[] = $ngword['ngw_ngword_id'];
			}
		}

		return join(',', $ngword_ary);
	}
}
?>