<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:CSV�ե�������ϴؿ�
'******************************************************/

// CSV�����ʥ������ݡ����ѡ�
function set_csv_export(&$csv, $data) {
	if ($csv != '')
		$csv .= ',';

	$data = str_replace(',', '��', $data);
	$data = mb_str_replace('��', '��', $data);
	$data = str_replace('"', '��', $data);
	$data = str_replace("\r", '', $data);
	$csv .= str_replace("\n", '}', $data);
}

// CSV�������̾��
function set_csv(&$csv, $data) {
	if ($csv != '')
		$csv .= ',';

	if (strstr($data, ',') || strstr($data, '"') || strstr($data, "\n"))
		$csv .= '"' . str_replace('"', '""', $data) . '"';
	else
		$csv .= $data;
}

// CSV���Ͻ���
function prepare_csv($filename) {
	header('Pragma: no-cache');
	header('Content-Type: text/csv');
	header('Content-Disposition: inline; filename="' . mb_convert_encoding($filename, 'SJIS') . '"');
	header('Expires: 0');
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Pragma: public');
}

// CSV����
function output_csv($csv) {
	echo mb_convert_encoding($csv, 'SJIS', 'EUC-JP'), "\r\n";
}

// �ޥ���Х����б�ʸ�����ִ�
function mb_str_replace($search, $replace, $subject) {
	$offset = 0;
	$len= mb_strlen($replace);
	while (($pos = mb_strpos($subject, $search, $offset)) !== false) {
		$subject = mb_substr($subject, 0, $pos) . $replace . mb_substr($subject, $pos + $len);
		$pos += $len;
	}
	return $subject;
}
?>