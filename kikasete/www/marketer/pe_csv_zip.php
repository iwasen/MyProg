<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/format.php");
include("$inc/csv.php");
include("$inc/mye_csv.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/pro_enquete.php");

session_start();

// アンケートID,タイトル取得
$owner_id = $_SESSION['ss_owner_id'];
$sql = "SELECT mep_enquete_id,mep_enquete2_id,mep_finding_flag,en_title"
	. " FROM t_pro_enquete"
	. " JOIN t_enquete ON en_enquete_id=mep_enquete_id"
	. " WHERE mep_marketer_id=$owner_id AND mep_pro_enq_no=$pro_enq_no";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$enquete_id = $fetch->mep_enquete_id;
	$enquete2_id = $fetch->mep_enquete2_id;
} else
	exit;

// タイトルのファイル名として使えない文字を置換
$title = mb_ereg_replace ('[\/:,;*?"<>|]' , '_' , $fetch->en_title);

// CSV用のヘッダを出力しない
$g_disable_prepare_csv = true;

// 設問CSVデータ取得
ob_start();
output_attribute_csv();
if ($fetch->mep_finding_flag == DBTRUE) {
    output_question_csv($fetch->mep_enquete_id, true);
    output_question_csv($fetch->mep_enquete2_id);
} else
    output_question_csv($fetch->mep_enquete_id);
$csv_question = ob_get_contents();
ob_end_clean();

// 回答CSVデータ取得
ob_start();
csv_answer($enquete2_id, false, false, $fetch->mep_finding_flag == DBTRUE ? $enquete_id : 0, '');
$csv_answer = ob_get_contents();
ob_end_clean();

// zipファイル用テンポラリファイル名取得
$zipfile = tempnam ('/tmp', 'kikasete_zip_');

// zipファイル作成
$zip = new ZipArchive();
$zip->open($zipfile, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE);
$zip->addFromString(mb_convert_encoding("questions_{$title}.csv", 'SJIS'), $csv_question);
$zip->addFromString(mb_convert_encoding("data_{$title}.csv", 'SJIS'), $csv_answer);
$zip->close();

// HTTPヘッダ出力
header('Pragma: no-cache');
header('Content-Type: application/zip');
header('Content-Disposition: attachment; filename="' . mb_convert_encoding("{$title}.zip", 'SJIS') . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');

// zipファイル出力
echo file_get_contents($zipfile);

// テンポラリファイル削除
unlink($zipfile);
