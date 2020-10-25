<?php
/**
 * ひとびと･net 共通ファイル
 *
 * バッチ処理クラス
 *
 *
 * @package
 * @author
 * @version
 */

class batch_class {
	protected $batch_id;
	protected $log_ary = array();
	protected $fp_lock;
	protected $start_time;
	protected $end_time;
	public $executable = false;

	// ■コンストラクタ
	public function __construct($batch_id) {
		$this->batch_id = $batch_id;

		// 開始時刻（マイクロ秒）を保存
		$this->start_time = array_sum(explode(' ', microtime()));

		// 最大実行時間を１０分に設定
		set_time_limit(600);

		// 二重起動チェック
		if ($this->dup_check())
			$this->batch_log(9, '二重起動');
		else
			$this->executable = true;
	}

	// ■デストラクタ
	public function __destruct() {
		// バッチログ書き込みを確実に行うため、一旦DB接続を閉じる
		db_close();

		// 終了時刻（マイクロ秒）を保存
		$this->end_time = array_sum(explode(' ', microtime()));

		// バッチログ書き込み
		foreach ($this->log_ary as $log)
			$this->batch_log($log->status, $log->description);
	}

	// ■バッチログ設定
	public function set_log($status, $description) {
		$log->status = $status;
		$log->description = $description;
		$this->log_ary[] = $log;
	}

	// ■二重起動チェック
	protected function dup_check() {
		umask(0);
		$filename = "/tmp/lock_{$this->batch_id}";
		if (($this->fp_lock = fopen($filename, 'w')) == false)
			return true;

		if (!flock($this->fp_lock, LOCK_EX | LOCK_NB, $wouldblock))
			return true;

		return $wouldblock;
	}

	// ■バッチログ書き込み
	protected function batch_log($status, $description) {
		$rec['lbt_status'] = sql_number($status);
		$rec['lbt_file_name'] = sql_char($this->batch_id);
		$rec['lbt_description'] = sql_char($description);
		$rec['lbt_processing_time'] = sql_number($this->end_time - $this->start_time);
		db_insert('l_batch', $rec);
	}
}
?>