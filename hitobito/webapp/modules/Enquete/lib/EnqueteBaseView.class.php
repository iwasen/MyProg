<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ナビアンケート ビュー基本クラス
 *
 *
 * @package
 * @author
 * @version
 */

abstract class EnqueteBaseView extends SmartyView {
	// ■初期化
	public function initialize($context) {
		parent::initialize($context);

		// ナビページID
		$request = $context->getRequest();
		$this->setAttribute('navi_page_id', $request->getAttribute('navi_page_id'));

		// チャネルID
		$this->setAttribute('channel_id', $request->getAttribute('channel_id'));

		return true;
	}

	// ■日時整形
	protected function format_datetime($date, $def = '', $separator_ymd = '.') {
		if ($date == '')
			return $def;
		else {
			sscanf($date, "%d-%d-%d %d:%d", &$y, &$m, &$d, &$h, &$i);
			return sprintf("%02d%s%02d%s%02d %02d:%02d", $y % 100, $separator_ymd, $m, $separator_ymd, $d, $h, $i);
		}
	}

	// ■日付整形
	protected function format_date($date, $def='', $separator_ymd = '.') {
		if ($date == '')
			return $def;
		else {
			sscanf($date, "%d-%d-%d", &$y, &$m, &$d);
			return sprintf("%02d%s%02d%s%02d", $y % 100, $separator_ymd, $m, $separator_ymd, $d);
		}
	}

	// ■年選択肢
	protected function getYearOptions() {
		$start_year = 2005;
		$end_year = date('Y') + 5;

		$ary = array();
		for ($year = $start_year; $year <= $end_year; $year++)
			$ary[$year] = $year;

		return $ary;
	}

	// ■月選択肢
	protected function getMonthOptions() {
		$ary = array();
		for ($month = 1; $month <= 12; $month++)
			$ary[$month] = $month;

		return $ary;
	}

	// ■日選択肢
	protected function getDayOptions() {
		$ary = array();
		for ($day = 1; $day <= 31; $day++)
			$ary[$day] = $day;

		return $ary;
	}

	// ■日付をYMDに分解して設定
	protected function setYMD(&$ary, $name) {
		if (isset($ary[$name]))
			sscanf($ary[$name], "%d-%d-%d", &$year, &$month, &$day);
		else {
			$year = date('Y');
			$month = 1;
			$day = 1;
		}
		$ary[$name . '_year'] = $year;
		$ary[$name . '_month'] = $month;
		$ary[$name . '_day'] = $day;
	}

	// ■CSV出力
	protected function outputCSV($filename, $data_ary, $quote = false) {
		header('Pragma: no-cache');
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename="' . mb_convert_encoding($filename, 'SJIS') . '"');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');

		if (is_array($data_ary)) {
			foreach ($data_ary as $row_ary) {
				$csv = array();
				foreach ($row_ary as $data) {
					if ($quote || strstr($data, ',') || strstr($data, '"') || strstr($data, "\n"))
						$csv[] = '"' . str_replace('"', '""', $data) . '"';
					else
						$csv[] = $data;
				}
				echo mb_convert_encoding(join(',', $csv), 'SJIS', 'EUC-JP'), "\r\n";
			}
		}
	}
}
?>