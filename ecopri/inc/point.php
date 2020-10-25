<?
/******************************************************
' System :Eco-footprint ����
' Content:�ݥ���Ƚ���
'******************************************************/

// �ݥ���ȼ���
define('PT_MONTHLY', 1);		// ���Υ�󥯤ˤ��ݥ����

// �ݥ���Ȥ���Ϳ
function add_point($point_type, $point, $seq_no = 0) {
	// �ݥ���Ȥ�0�ʤ鲿�⤷�ʤ�
	if ($point == 0)
		return;

	// ����ֹ�λ��꤬�ʤ���Х�����ID�Ȥ���
	if ($seq_no == 0)
		$seq_no = $_SESSION['ss_seq_no'];

	db_begin_trans();

	// �ݥ����������ɲ�
	$sql = "INSERT INTO l_point (pl_seq_no,pl_point_type,pl_point) VALUES ($seq_no,$point_type,$point)";
	db_exec($sql);

	// �ݥ���ȻĹ��ȿ��
	$sql = "SELECT pt_seq_no FROM t_point WHERE pt_seq_no=$seq_no";
	$result = db_exec($sql);
	if (pg_numrows($result))
		$sql = "UPDATE t_point SET pt_point=pt_point+$point WHERE pt_seq_no=$seq_no";
	else
		$sql = "INSERT INTO t_point (pt_seq_no,pt_point) VALUES ($seq_no,$point)";
	db_exec($sql);

	db_commit_trans();
}

// �ݥ���ȻĹ����
function get_point($seq_no = 0) {
	// ����ֹ�λ��꤬�ʤ���Х�����ID�Ȥ���
	if ($seq_no == 0)
		$seq_no = $_SESSION['ss_seq_no'];

	// �ݥ���ȻĹ�ơ��֥뤫�����
	$sql = "SELECT pt_point FROM t_point WHERE pt_seq_no=$seq_no";
	$point = db_fetch1($sql);
	if (!$point)
		$point = 0;

	return $point;
}
?>