<?
// ����Хꥹ��ID
define('GANBA_YUWAKASHI', 1);
define('GANBA_KANSOU', 2);
define('GANBA_TV_KOUDOU', 3);
define('GANBA_TV_KIKI', 4);
define('GANBA_TOILET_TSUUDEN', 5);
define('GANBA_TOILET_SESSUI', 6);
define('GANBA_SHOUMEI', 7);
define('GANBA_REIBOU_LD', 8);
define('GANBA_REIBOU_KO', 9);
define('GANBA_DANBOU_LD',10);
define('GANBA_KONRO', 11);
define('GANBA_SHOKKIARAI', 12);
define('GANBA_YOKUSOU', 13);
define('GANBA_SHOWER', 14);
define('GANBA_OIDAKI', 15);
define('GANBA_KYUUTOUKI', 16);
define('GANBA_SHOWER_HEAD', 17);
define('GANBA_SENGAN', 18);
define('GANBA_SENTAKU', 19);
define('GANBA_SUSUGI', 20);
define('GANBA_NAMAGOMI', 21);
define('GANBA_SHIGENGOMI', 22);
define('GANBA_TEIKOUGAISHA', 23);
define('GANBA_HAIKIRYOU', 24);
define('GANBA_UNTENHOUHOU', 25);

// ���ͥ륮��ʬ�ॳ����
define('ENG_DENKI', 1);
define('ENG_GAS', 2);
define('ENG_SUIDOU', 3);
define('ENG_GOMI', 4);
define('ENG_GASOLINE', 5);

// ����ʬ��
define('BUNRUI1_EISEI', 1);
define('BUNRUI1_GORAKU', 2);
define('BUNRUI1_KAJI', 3);
define('BUNRUI1_SHOUMEI', 4);
define('BUNRUI1_KAITEKI', 5);
define('BUNRUI1_KOUTSUU', 6);

function read_sim_data($ganbalist_id) {
	// ����ֹ����
	$seq_no = $_SESSION['ss_seq_no'];

	// �������ǥǡ��������
	$sql = "SELECT sm_eco_data FROM t_simulation WHERE sm_seq_no=$seq_no AND sm_ganbalist_id=$ganbalist_id";
	return db_fetch1($sql);
}

function write_ganbarist($ganbalist_id, $ym, $energy, $water, $cost, $co2, $eco_data, $advice) {
	// ����ֹ����
	$seq_no = $_SESSION['ss_seq_no'];

	// ���ͥ륮��ʬ�����
	$eng_bunrui = $_SESSION['s_eng_bunrui'];

	// ǯ��λ��꤬�ʤ����ϸ��ߤ�ǯ������
	if ($ym == '')
		$ym = date('Ym');

	db_begin_trans();

	if ($eco_data) {
		// �Ť��������ǥǡ�������
		$sql = "DELETE FROM t_simulation WHERE sm_seq_no=$seq_no AND sm_ganbalist_id=$ganbalist_id";
		db_exec($sql);

		// �������������ǥǡ������ɲ�
		$sql = sprintf("INSERT INTO t_simulation (sm_seq_no,sm_ganbalist_id,sm_eco_data) VALUES (%s,%s,%s)",
				sql_number($seq_no),
				sql_number($ganbalist_id),
				sql_char($eco_data));
		db_exec($sql);
	}

	// �Ť�����Хꥹ�Ȥ���
	$sql = "DELETE FROM t_ganbalist WHERE gn_seq_no=$seq_no AND gn_ym=$ym AND gn_ganbalist_id=$ganbalist_id";
	db_exec($sql);

	// ����������Хꥹ�Ȥ��ɲ�
	$sql = sprintf("INSERT INTO t_ganbalist (gn_seq_no,gn_ym,gn_ganbalist_id,gn_eng_bunrui,gn_energy,gn_water,gn_cost,gn_co2,gn_advice) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s)",
			sql_number($seq_no),
			sql_char($ym),
			sql_number($ganbalist_id),
			sql_number($eng_bunrui),
			sql_number($energy),
			sql_number($water),
			sql_number($cost),
			sql_number($co2),
			sql_char($advice));
	db_exec($sql);

	db_commit_trans();
}
?>