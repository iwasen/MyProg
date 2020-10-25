<?
/******************************************************
' System :お酒と買物のアンケートモニター事務局用ページ
' Content:調査結果複数アップ処理
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

// ファイル解凍処理
function decompress_report_file($filename, $tmpfile, $tempdir) {
	if (preg_match('/\.zip$/i', $filename))
		$command = "unzip -n -q -j -d $tempdir $tmpfile";
	elseif (preg_match('/\.lzh$/i', $filename))
		$command = "lha -xiqw=$tempdir $tmpfile";
	else
		return false;

	exec($command);

	return true;
}

// チェーンコードまたは業態コード取得
function get_chain_or_gyoutai_cd($chain_or_gyoutai_name, &$chain_cd, &$gyoutai_cd) {
	$chain_cd = '';
	$gyoutai_cd = '';

	$sql = sprintf("SELECT ch_chain_cd FROM m_chain WHERE ch_name=%s", sql_char($chain_or_gyoutai_name));
	$chain_cd = db_fetch1($sql);
	if ($chain_cd)
		return true;

	$sql = sprintf("SELECT gt_gyoutai_cd FROM m_gyoutai WHERE gt_gyoutai_name=%s", sql_char($chain_or_gyoutai_name));
	$gyoutai_cd = db_fetch1($sql);
	if ($gyoutai_cd)
		return true;

	return false;
}

// content_type取得
function get_content_type($filename) {
	if (preg_match('/\.ppt$/i', $filename))
		return 'application/vnd.ms-powerpoint';
	elseif (preg_match('/\.xls$/i', $filename))
		return 'application/vnd.ms-excel';
	else
		return 'application/octet-stream';
}

set_global('etc', 'その他管理', '調査結果アップロード（複数選択）', BACK_TOP);

$tempdir = "/tmp/" . uniqid('sake_moni_');
mkdir($tempdir);

if (!decompress_report_file($_FILES['compressed_file']['name'], $_FILES['compressed_file']['tmp_name'], $tempdir)) {
	$msg = '圧縮ファイルが不正です。';
} else {
	if ($dh = opendir($tempdir)) {
		while (($filename = readdir($dh)) !== false) {
			$pathname = "$tempdir/$filename";
			if (is_file($pathname)) {
				if (mb_ereg('^([ﾚレ集調])_([^_]+)_([0-9]+)\.(.+)$', $filename, $regs)) {
					$file_kind = $regs[1];
					$chain_or_gyoutai_name = $regs[2];

					if (get_chain_or_gyoutai_cd($chain_or_gyoutai_name, $chain_cd, $gyoutai_cd)) {
						$valid_file[$chain_or_gyoutai_name]['chain_cd'] = $chain_cd;
						$valid_file[$chain_or_gyoutai_name]['gyoutai_cd'] = $gyoutai_cd;

						if ($file_kind == 'ﾚ' || $file_kind == 'レ') {
							$valid_file[$chain_or_gyoutai_name]['report_file'] = file_get_contents($pathname);
							$valid_file[$chain_or_gyoutai_name]['report_file_name'] = basename($filename);
							$valid_file[$chain_or_gyoutai_name]['report_file_type'] = get_content_type($filename);
						} elseif ($file_kind == '集') {
							$valid_file[$chain_or_gyoutai_name]['sum_file'] = file_get_contents($pathname);
							$valid_file[$chain_or_gyoutai_name]['sum_file_name'] = basename($filename);
							$valid_file[$chain_or_gyoutai_name]['sum_file_type'] = get_content_type($filename);
						} elseif ($file_kind == '調') {
							$valid_file[$chain_or_gyoutai_name]['enquete_file'] = file_get_contents($pathname);
							$valid_file[$chain_or_gyoutai_name]['enquete_file_name'] = basename($filename);
							$valid_file[$chain_or_gyoutai_name]['enquete_file_type'] = get_content_type($filename);
						}
					} else
						$invalid_file[] = $filename;
				} else
					$invalid_file[] = $filename;
			}
		}
		closedir($dh);
	}

	if ($invalid_file) {
		$msg = '圧縮ファイルに不正なファイルが含まれています。<br><br>' . join($invalid_file, '<br>');
	} else if ($valid_file) {
		foreach ($valid_file as $report) {
			$rec['rp_chain_cd'] = sql_number($report['chain_cd']);
			$rec['rp_gyoutai_cd'] = sql_number($report['gyoutai_cd']);
			$rec['rp_title'] = sql_char($_POST['title']);
			$rec['rp_from_date'] = sql_date("$from_year/$from_month/$from_day");
			$rec['rp_to_date'] = sql_date("$to_year/$to_month/$to_day");
			$rec['rp_report_file'] = sql_bytea($report['report_file']);
			$rec['rp_report_file_name'] = sql_char($report['report_file_name']);
			$rec['rp_report_file_type'] = sql_char($report['report_file_type']);
			$rec['rp_sum_file'] = sql_bytea($report['sum_file']);
			$rec['rp_sum_file_name'] = sql_char($report['sum_file_name']);
			$rec['rp_sum_file_type'] = sql_char($report['sum_file_type']);
			$rec['rp_enquete_file'] = sql_bytea($report['enquete_file']);
			$rec['rp_enquete_file_name'] = sql_char($report['enquete_file_name']);
			$rec['rp_enquete_file_type'] = sql_char($report['enquete_file_type']);
			db_insert('t_report', $rec);
		}
	}
}

exec("rm -rf $tempdir");

if (!$msg) {
	$msg = '調査結果を登録しました。';
	$ret = 'location.href=\'list.php\'';
} else {
	$ret = 'history.back()';
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body onLoad="document.all.ok.focus()">
<? page_header() ?>

<div align="center">
<p class="msg"><?=$msg?></p>
<p><input type="button" id="ok" value="　戻る　" onclick="<?=$ret?>"></p>
</div>

<? page_footer() ?>
</body>
</html>
