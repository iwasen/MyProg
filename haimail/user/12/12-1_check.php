<?
include('../inc/common.php');
include('../inc/database.php');
include('../inc/point.php');

//=== ����Ͽ���� ===
function Registration($seq_no) {
	global $member_id, $baitai_id;

	BeginTrans();

	//����Ͽ�ǡ�������
	$sql = "SELECT * FROM T_KARI_TOUROKU WHERE KT_seq_no=$seq_no AND KT_status='0'";
	$ds1 = CreateDynaset($sql, ORADYN_DEFAULT);
	if ($ds1->EOF())
		redirect('../index.php');

	//����Ͽ�ǡ���������Ͽ�Ѥߤ�����
	$sql = "UPDATE T_KARI_TOUROKU SET KT_status='1' WHERE KT_seq_no=$seq_no";
	ExecuteSQL($sql);

	//PRID����
	$pr_id = $ds1->GetValue('KT_pr_id');

	//����ޥ�������Ͽ
	$member_id = MakeID(GetNextVal('MM_MEMBER_ID'), 10);
	$sql = "SELECT * FROM T_MEMBER WHERE MM_member_id='$member_id'";
	$ds2 = CreateDynaset($sql, ORADYN_DEFAULT);
	if (!$ds2->EOF())
		redirect('../index.php');

	$sql = sprintf("INSERT INTO T_MEMBER (MM_member_id,MM_mail_adr,MM_password,MM_seibetsu,MM_mikikon,MM_seinengappi,MM_zip_kyojuu,MM_zip_kinmu,MM_shokugyou,MM_html_mail,MM_kyoumi_genre,MM_yokuiku_mise,MM_docchiha,MM_oshirase_mail,MM_shoukai_no,MM_pr_id,MM_regist_date,MM_update_date,MM_update_cnt,MM_status,MM_regist_type) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
			SqlStr($member_id),
			SqlStr($ds1->GetValue('KT_mail_adr')),
			SqlStr($ds1->GetValue('KT_password')),
			SqlStr($ds1->GetValue('KT_seibetsu')),
			SqlStr($ds1->GetValue('KT_mikikon')),
			SqlDate($ds1->GetValue('KT_seinengappi')),
			SqlStr($ds1->GetValue('KT_zip_kyojuu')),
			SqlStr($ds1->GetValue('KT_zip_kinmu')),
			SqlStr($ds1->GetValue('KT_shokugyou')),
			SqlStr($ds1->GetValue('KT_html_mail')),
			SqlStr($ds1->GetValue('KT_kyoumi_genre')),
			SqlStr($ds1->GetValue('KT_yokuiku_mise')),
			SqlStr($ds1->GetValue('KT_docchiha')),
			SqlStr($ds1->GetValue('KT_oshirase_mail')),
			SqlNum($ds1->GetValue('KT_shoukai_no')),
			SqlStr($pr_id),
			"SYSDATE",
			"SYSDATE",
			"0",
			"0",
			SqlStr($ds1->GetValue('KT_regist_type')));
	ExecuteSQL($sql);

	//�Ҳ�Ԥ���Ҳ�Ԥ˥ݥ���Ȥ���Ϳ
	if ($ds1->GetValue('KT_shoukai_no') != '') {
		$sql = "UPDATE T_HISHOUKAI SET HS_regist_flag='1'"
				. " WHERE HS_shoukai_no=" . $ds1->GetValue('KT_shoukai_no') . " AND HS_mail_adr='" . $ds1->GetValue('KT_mail_adr') . "'";
		ExecuteSQL($sql);

		//��Ҳ�ԥݥ����
		AddPoint($member_id, '01', 100);

		//�Ҳ�ԥݥ����
		$sql = "SELECT SK_member_id FROM T_SHOUKAI WHERE SK_shoukai_no=" . $ds1->GetValue('KT_shoukai_no');
		$ds3 = CreateDynaset($sql, ORADYN_READONLY);
		if (!$ds3->EOF())
			AddPoint($ds3->GetValue('SK_member_id'), '03', 100);
	}

	//������Ͽ�ԥơ��֥���ɲ�
	$sql = "INSERT INTO T_NEW_MEMBER VALUES('$member_id')";
	ExecuteSQL($sql);

	//����ID�����
	if ($pr_id != '') {
		$sql = "SELECT PR_baitai_id FROM T_PR WHERE PR_pr_id='$pr_id'";
		$ds3 = CreateDynaset($sql, ORADYN_READONLY);
		if (!$ds3->EOF())
			$baitai_id = $ds3->GetValue('PR_baitai_id');
	}

//���ץ�󥰡������ڡ���====
	$sql = "UPDATE T_PRESENT SET PS_member_id='$member_id' WHERE PS_present_id='06' AND PS_seq_no=$seq_no";
	ExecuteSQL($sql);
//============================

	//�Ŀͥ��ե��ꥨ�����罸�ˤ��ݥ������Ϳ
	$sql = "SELECT PR_member_id,PR_affiliate FROM T_PR WHERE PR_pr_id='$pr_id' AND pr_baitai_id='03'";
	$ds3 = CreateDynaset($sql, ORADYN_READONLY);
	if (!$ds3->EOF()) {
		if ($ds3->GetValue('PR_member_id') != '' && $ds3->GetValue('PR_affiliate') > 0)
			AddPoint($ds3->GetValue('PR_member_id'), '04', $ds3->GetValue('PR_affiliate'));
	}

	//��̣�����롢�褯�Ԥ�Ź���ɤä��ɥǡ�������
	ExecuteSQL("BEGIN S_CREATE_SEARCH_DATA('$member_id'); END;");

	CommitTrans();
}

//=== ����Ͽ����󥻥���� ===
function Cancel($seq_no) {
	//����Ͽ�ǡ�������
	$sql = "SELECT * FROM T_KARI_TOUROKU WHERE KT_seq_no=$seq_no AND KT_status='0'";
	$ds = CreateDynaset($sql, ORADYN_DEFAULT);
	if ($ds->EOF())
		redirect('../index.php');

	//����Ͽ�ǡ����򥭥�󥻥�Ѥߤ�����
	$sql = "UPDATE T_KARI_TOUROKU SET KT_status='2' WHERE KT_seq_no=$seq_no";
	ExecuteSQL($sql);
}

//=== �ᥤ����� ===
if ($id == '')
	redirect('../index.php');

if (isset($regist)) {
	Registration($id);

	if ($baitai_id == '06')	//�Х�塼����å�
		redirect('12a-2-vcli.html');
	elseif ($baitai_id == BT_NETMILE) //�ͥåȥޥ���
		redirect("12a-2_nm.php?id=$member_id");
	elseif ($baitai_id == '34') //�������å������ե�
		redirect("12a-2_gc.php?id=$member_id");	
	else
		redirect("12a-2.php?id=$member_id");
} elseif (isset($cancel)) {
	Cancel($id);
	redirect('12b-2.html');
} else
	redirect('../index.php');
?>