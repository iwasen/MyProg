<?php
/**
 * �ҤȤӤȎ�net ���̥ե�����
 *
 * �Хå��������饹
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

	// �����󥹥ȥ饯��
	public function __construct($batch_id) {
		$this->batch_id = $batch_id;

		// ���ϻ���ʥޥ������áˤ���¸
		$this->start_time = array_sum(explode(' ', microtime()));

		// ����¹Ի��֤򣱣�ʬ������
		set_time_limit(600);

		// ��ŵ�ư�����å�
		if ($this->dup_check())
			$this->batch_log(9, '��ŵ�ư');
		else
			$this->executable = true;
	}

	// ���ǥ��ȥ饯��
	public function __destruct() {
		// �Хå����񤭹��ߤ�μ¤˹Ԥ����ᡢ��öDB��³���Ĥ���
		db_close();

		// ��λ����ʥޥ������áˤ���¸
		$this->end_time = array_sum(explode(' ', microtime()));

		// �Хå����񤭹���
		foreach ($this->log_ary as $log)
			$this->batch_log($log->status, $log->description);
	}

	// ���Хå�������
	public function set_log($status, $description) {
		$log->status = $status;
		$log->description = $description;
		$this->log_ary[] = $log;
	}

	// ����ŵ�ư�����å�
	protected function dup_check() {
		umask(0);
		$filename = "/tmp/lock_{$this->batch_id}";
		if (($this->fp_lock = fopen($filename, 'w')) == false)
			return true;

		if (!flock($this->fp_lock, LOCK_EX | LOCK_NB, $wouldblock))
			return true;

		return $wouldblock;
	}

	// ���Хå����񤭹���
	protected function batch_log($status, $description) {
		$rec['lbt_status'] = sql_number($status);
		$rec['lbt_file_name'] = sql_char($this->batch_id);
		$rec['lbt_description'] = sql_char($description);
		$rec['lbt_processing_time'] = sql_number($this->end_time - $this->start_time);
		db_insert('l_batch', $rec);
	}
}
?>