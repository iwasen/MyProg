<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:CSV�ե�������ϴؿ�
'******************************************************/

// CSV����
function set_csv(&$csv, $data, $quote=false) {
	global $g_set_csv;

	if ($g_set_csv || $csv != '')
		$csv .= ',';

	if ($quote || strstr($data, ',') || strstr($data, '"') || strstr($data, "\n"))
		$csv .= '"' . str_replace('"', '""', $data) . '"';
	else
		$csv .= $data;

	$g_set_csv = true;
}

// CSV���Ͻ���
function prepare_csv($filename) {
	header('Pragma: no-cache');
	header('Content-Type: text/csv');
	header('Content-Disposition: attachment; filename="' . mb_convert_encoding($filename, 'SJIS') . '"');
	header('Expires: 0');
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Pragma: public');
}

// CSV����
function output_csv(&$csv) {
	global $g_set_csv;

	echo mb_convert_encoding($csv, 'SJIS', 'EUC-JP'), "\r\n";
	$csv = '';
	$g_set_csv = false;
}
?>