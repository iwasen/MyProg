<?php
/**
 * �ҤȤӤȎ�net ���̥ե�����
 *
 * CSV���Ͻ������饹
 *
 *
 * @package
 * @author
 * @version
 */

class csv_class {
	protected $csv = '';
	protected $set_flag = false;

	// �����󥹥ȥ饯��
	public function __construct($filename) {
		header('Pragma: no-cache');
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename="' . mb_convert_encoding($filename, 'SJIS') . '"');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
	}

	// ��CSV����
	public function set_csv($data, $quote = false) {
		if ($this->set_flag || $this->csv != '')
			$this->csv .= ',';

		if ($quote || strstr($data, ',') || strstr($data, '"') || strstr($data, "\n"))
			$this->csv .= '"' . str_replace('"', '""', $data) . '"';
		else
			$this->csv .= $data;

		$this->set_flag = true;
	}

	// ��CSV����
	public function output_csv() {
		echo mb_convert_encoding($this->csv, 'SJIS', 'EUC-JP'), "\r\n";

		$this->csv = '';
		$this->set_flag = false;
	}

	// ������եǡ�������
	public function export_data() {
		$argn = func_num_args();

		for ($i = 0; $i < $argn; $i++) {
			$class = func_get_arg($i);

			// �ǡ�������
			$data_ary = $class->get_export_data($i == 0);
			foreach ($data_ary as $col_ary) {
				foreach ($col_ary as $data)
					$this->set_csv($data);

				$this->output_csv();
			}
		}
	}

	// ������Խ���
	public function output_newline() {
		echo "\r\n";
	}
}
?>