<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:�������ݡ��ȥǡ���CSV����
'******************************************************/

$top = "..";
$inc = "$top/../inc";
//include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");
include("$inc/csv.php");
include("csv_inc.php");

// ���ϥѥ�᡼��
$date = $_GET['date'];

// YYYYMMDD
$ymd = str_replace('/', '', $date);

// �ƥ�ݥ��ǥ��쥯�ȥ����
$temp_dir = '/tmp/tmc';
if (!file_exists($temp_dir))
	mkdir($temp_dir);

// ����ǥ��쥯�ȥ����
$work_dir = $temp_dir . '/' . uniqid('export_');
if (!file_exists($work_dir))
	mkdir($work_dir);

// ���ꤵ�줿���դΥǡ��������
$handover_date = "urg_handover_date + interval '795 minutes'";
$sql = "SELECT urg_user_id,urg_dealer_cd,upt_upload_cd,sch_sales_channel_mark"
		. " FROM t_user_regist"
		. " JOIN t_upload_dealer ON upd_dealer_cd=urg_dealer_cd"
		. " JOIN t_upload_tantou ON upt_upload_cd=upd_upload_cd"
		. " JOIN t_dealer ON dlr_dealer_cd=urg_dealer_cd"
		. " JOIN t_sales_channel ON sch_sales_channel_cd=dlr_sales_channel_cd"
		. " WHERE date_trunc('day',$handover_date)='$date' AND upt_active=true"
		. " ORDER BY upt_upload_cd,sch_sales_channel_mark,urg_dealer_cd,urg_user_id";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
for ($i = 0; $i < $nrow; ) {
	$fetch = pg_fetch_object($result, $i);
	$dealer_cd = $fetch->urg_dealer_cd;
	$upload_cd = $fetch->upt_upload_cd;
	$sales_channel_mark = $fetch->sch_sales_channel_mark;

	// ���åץ���ô���̥ǥ��쥯�ȥ����
	$dir = "$work_dir/$upload_cd";
	if (!file_exists($dir))
		mkdir($dir);

	// CSV�ե����륪���ץ�
	$csv_file = "$dealer_cd$ymd.csv";
	$csv_path = "$dir/$csv_file";
	$fp = fopen($csv_path, "w");

	// �ƥ�ݥ��ե������CSV����
	while ($i < $nrow) {
		$fetch = pg_fetch_object($result, $i);
		if ($fetch->urg_dealer_cd != $dealer_cd)
			break;

		$user_id = $fetch->urg_user_id;

		// Mail��졼������̽���
		$data = output_res_data($user_id);
		fwrite($fp, mb_convert_encoding($data, 'SJIS', 'EUC-JP') . "\r\n");

		// 1to1�᡼�����
		$data = output_1to1_data($user_id);
		if (is_array($data)) {
			foreach (array_keys($data) as $key)
				fwrite($fp, mb_convert_encoding($data[$key], 'SJIS', 'EUC-JP') . "\r\n");
		}

		$i++;
	}

	fclose($fp);

	// �������ͥ���lzh�ե��������
	$lzh_file = "$sales_channel_mark$ymd.lzh";
	shell_exec("cd $dir;lha m $lzh_file $csv_file");

	// �Хå����åץե�������
	$bak_path = "$dir/$sales_channel_mark$ymd.bak";
	if (file_exists($bak_path))
		unlink($bak_path);
}

// ���Τ�lzh�ե��������
$lzh_file = "$ymd.lzh";
shell_exec("cd $work_dir;lha a $lzh_file *");

// ����������ѥإå�����
header('Pragma: no-cache');
header('Content-Type: application/octed-stream');
header('Content-Disposition: inline; filename="' . $lzh_file . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');

// CSV�ե��������
$lzh_path = "$work_dir/$lzh_file";
if (file_exists($lzh_path))
	readfile($lzh_path);

// ����ǥ��쥯�ȥ���
shell_exec("rm -rf $work_dir");

exit;
?>