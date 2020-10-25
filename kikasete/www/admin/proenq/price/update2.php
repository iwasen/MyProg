<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:本アンケート料金情報更新処理
'******************************************************/

$top = "../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

set_global('proenquete', 'Ｐｒｏアンケート管理｜マスタメンテナンス', '料金表管理', BACK_TOP);

switch ($next_action) {
case 'import':
	db_begin_trans();

	db_delete('m_proenq_price', 'true');

	$handle = fopen($_FILES['import_file']['tmp_name'], "r");
	if ($question_num_ary = fgetcsv($handle, 1000, ",")) {
		while ($csv = fgetcsv($handle, 1000, ",")) {
			if ($csv[0] != '' && is_numeric($csv[0])) {
				$sample_num = $csv[0];
				foreach ($csv as $i => $price) {
					if ($i != 0) {
						$rec['pep_question_num'] = sql_number($question_num_ary[$i]);
						$rec['pep_sample_num'] = sql_number($sample_num);
						$rec['pep_price'] = sql_number($price);
						db_insert('m_proenq_price', $rec);
					}
				}
			}
		}
	}
	fclose($handle);

	db_commit_trans();

	$msg = '本アンケート料金をインポートしました。';
	break;
case 'update':
	db_begin_trans();

	db_delete('m_proenq_price', 'true');

	if (is_array($sample_num) && is_array($question_num)) {
		foreach ($sample_num as $sno => $sample) {
			if ($sample != '') {
				foreach ($question_num as $qno => $question) {
					if ($question != '') {
						$rec['pep_question_num'] = sql_number($question);
						$rec['pep_sample_num'] = sql_number($sample);
						$rec['pep_price'] = sql_number($price[$sno][$qno]);
						db_insert('m_proenq_price', $rec);
					}
				}
			}
		}
	}

	db_commit_trans();

	$msg = '本アンケート料金を更新しました。';
	break;
default:
	redirect('list.php');
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
<p><input type="button" id="ok" value="　戻る　" onclick="location.href='list.php'"></p>
</div>

<? page_footer() ?>
</body>
</html>
