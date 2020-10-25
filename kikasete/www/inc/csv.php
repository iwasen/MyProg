<?
/******************************************************
' System :きかせて・net共通
' Content:CSVファイル出力
'******************************************************/

// CSV生成
function set_csv(&$csv, $data) {
	global $g_set_csv;

	if ($g_set_csv || $csv != '')
		$csv .= ',';

	if (strpos($data, "\r") !== false)
		$data = str_replace("\r", '', $data);

	if (strpos($data, ',') !== false || strpos($data, '"') !== false || strpos($data, "\n") !== false)
		$csv .= '"' . str_replace('"', '""', $data) . '"';
	else
		$csv .= $data;

	$g_set_csv = true;
}

// CSV出力準備
function prepare_csv($filename) {
	global $g_disable_csv_header;

	if (!$g_disable_prepare_csv) {
		header('Pragma: no-cache');
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename="' . mb_convert_encoding($filename, 'SJIS') . '"');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
	}
}

// CSV出力
function output_csv(&$csv) {
	global $g_set_csv;

	echo mb_convert_encoding($csv, 'SJIS', 'EUC-JP'), "\r\n";
	$csv = '';
	$g_set_csv = false;
}
?>