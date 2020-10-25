<?
/******************************************************
' System :fletteスタッフページ
' Content:CSVファイル出力関数
'******************************************************/

// CSV生成
function set_csv(&$csv, $data) {
	if ($csv != '')
		$csv .= ',';

	if (strstr($data, ',') || strstr($data, '"') || strstr($data, "\n"))
		$csv .= '"' . str_replace('"', '""', $data) . '"';
	else
		$csv .= $data;
}

// CSV出力準備
function prepare_csv($filename) {
	header('Pragma: no-cache');
	header('Content-Type: text/csv');
	header('Content-Disposition: inline; filename="' . mb_convert_encoding($filename, 'SJIS') . '"');
	header('Expires: 0');
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Pragma: public');
}

// CSV出力
function output_csv(&$csv) {
	echo mb_convert_encoding($csv, 'SJIS', 'EUC-JP'), "\r\n";
	$csv = '';
}
?>