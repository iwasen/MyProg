<?

/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:�ͣ����󥱡�������CSV���Ͻ���
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/format.php");
include("$inc/csv.php");
include("$inc/mye_csv.php");
include("$inc/enquete.php");

if($mode=="1"){
//kim_start seq no=49 �ե����ǥ��� ����� ���ڤ�
		// ���󥱡���ID����
	$sql = "SELECT mep_enquete_id,mep_enquete2_id,mep_finding_flag FROM t_pro_enquete WHERE mep_marketer_id=$marketer_id AND mep_pro_enq_no=$pro_enq_no";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
	    $fetch = pg_fetch_object($result, 0);
	    $enquete_id = $fetch->mep_enquete_id;
		$file_prefix = 'data_qr';
	}
	// CSV�ǡ�������
	csv_answer(0, false, false, $enquete_id, $file_prefix);
	exit;
//kim_end seq no=49
}else if($mode=="2"){
	//kim_start seq no=49 �ե����ǥ��� ����������
		// ���󥱡���ID����
	$sql = "SELECT mep_enquete_id,mep_enquete2_id,mep_finding_flag FROM t_pro_enquete WHERE mep_marketer_id=$marketer_id AND mep_pro_enq_no=$pro_enq_no";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
	    $fetch = pg_fetch_object($result, 0);
	    $enquete_id = $fetch->mep_enquete_id;
		$file_prefix = 'qr01';
	}
	// CSV�ǡ�������
	csv_answer2(0, false, false, $enquete_id, $file_prefix);
	exit;
	//kim_end seq no=49
}else if($mode=="3"){
	//kim_start seq no=49 QR ����� ���ܥ��󥱡��� ����� �褭��
		// ���󥱡���ID����
	$sql = "SELECT mep_enquete_id,mep_enquete2_id FROM t_pro_enquete where mep_marketer_id=$marketer_id AND mep_pro_enq_no=$pro_enq_no";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$finding_enquete_id = $fetch->mep_enquete_id;
		$enquete_id = $fetch->mep_enquete2_id;
		$file_prefix = 'data';
	}
	// CSV�ǡ�������
	csv_answer($enquete_id, false, false, $finding_enquete_id, $file_prefix);
	exit;
	//kim_end seq no=49
}else if($mode=="4"){
	//kim_start seq no=49 QR 0,1+ �ܥ��󥱡��� ����޶褭��
		// ���󥱡���ID����
	$sql = "SELECT mep_enquete_id,mep_enquete2_id FROM t_pro_enquete WHERE mep_marketer_id=$marketer_id AND mep_pro_enq_no=$pro_enq_no";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$finding_enquete_id = $fetch->mep_enquete_id;
		$enquete_id = $fetch->mep_enquete2_id;
		$file_prefix = 'qrdr01';
	}
	// CSV�ǡ�������
	csv_answer2($enquete_id, false, false, $finding_enquete_id, $file_prefix);
	exit;
//kim_end seq no=49
}
?>
