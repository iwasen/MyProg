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
include("$inc/my_enquete.php");

session_start();

// ���󥱡���ID,�����ȥ����
$owner_id = $_SESSION['ss_owner_id'];
$sql = "SELECT me_enquete_id,en_title"
	. " FROM t_my_enquete"
	. " JOIN t_enquete ON en_enquete_id=me_enquete_id"
	. " WHERE me_marketer_id=$owner_id AND me_my_enq_no=$my_enq_no";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$enquete_id = $fetch->me_enquete_id;
} else
	exit;

// �����ȥ�Υե�����̾�Ȥ��ƻȤ��ʤ�ʸ�����ִ�
$title = mb_ereg_replace ('[\/:,;*?"<>|]' , '_' , $fetch->en_title);

// CSV�ѤΥإå�����Ϥ��ʤ�
$g_disable_prepare_csv = true;

// ����CSV�ǡ�������
ob_start();
output_attribute_csv();
output_question_csv($enquete_id);
$csv_question = ob_get_contents();
ob_end_clean();

// ����CSV�ǡ�������
ob_start();
csv_answer($enquete_id, false, false, 0, '');
$csv_answer = ob_get_contents();
ob_end_clean();

// zip�ե������ѥƥ�ݥ��ե�����̾����
$zipfile = tempnam ('/tmp', 'kikasete_zip_');

// zip�ե��������
$zip = new ZipArchive();
$zip->open($zipfile, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE);
$zip->addFromString(mb_convert_encoding("questions_{$title}.csv", 'SJIS'), $csv_question);
$zip->addFromString(mb_convert_encoding("data_{$title}.csv", 'SJIS'), $csv_answer);
$zip->close();

// HTTP�إå�����
header('Pragma: no-cache');
header('Content-Type: application/zip');
header('Content-Disposition: attachment; filename="' . mb_convert_encoding("{$title}.zip", 'SJIS') . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');

// zip�ե��������
echo file_get_contents($zipfile);

// �ƥ�ݥ��ե�������
unlink($zipfile);
