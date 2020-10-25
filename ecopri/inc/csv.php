<?
/******************************************************
' System :Eco-footprint ����
' Content:CSV�ե��������
'******************************************************/

// CSV����
function set_csv(&$csv, $data) {
	if ($csv != '')
		$csv .= ',';

	if (strstr($data, ',') || strstr($data, '"') || strstr($data, "\n"))
		$csv .= '"' . str_replace('"', '""', $data) . '"';
	else
		$csv .= $data;
}

function prepare_csv($filename) {
	header('Pragma: no-cache');
	header('Content-Type: text/csv');
	header('Content-Disposition: inline; filename="' . $filename . '"');
}

function output_csv($csv) {
	echo mb_convert_encoding($csv, 'SJIS', 'EUC-JP'), "\r\n";
}
?>