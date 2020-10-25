<?
/******************************************************
' System :きかせて・netモニターＭｙページ
' Content:アンケート関連処理
'******************************************************/
	include_once("./../../inc/pro_enq_target.php");
// PROアンケート回答可能チェック
function check_pro_enquete($monitor_id, $enquete_id, $start_date) {
	// モニターテーブルを識別する
	$pet = new pro_enq_target_class;
	$pet->read_db($enquete_id);
	$monitor_flg = $pet->target_flg;
	// 検索IDとサンプル数を取得
	$sql = "SELECT mep_search_id,mep_sample_num FROM t_pro_enquete WHERE mep_enquete2_id=$enquete_id";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$search_id = $fetch->mep_search_id;
		$sample_num = $fetch->mep_sample_num;

		// セル指定方法を取得
		$sql = "SELECT cla_age_option,cla_sex_option FROM t_cell_assign WHERE cla_search_id=$search_id";
		$result = db_exec($sql);
		if (pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);
			$age_option = $fetch->cla_age_option;
			$sex_option = $fetch->cla_sex_option;
		} else {
			$age_option = 1;
			$sex_option = 1;
		}

		$select = array();
		$join = array();
		$where = array();

		// 年代
		$date = "date '$start_date'";
		switch ($age_option) {
		case 2:		// 5歳刻み
			$select[] = "ae_age_cd AS age,ae_age_from AS age_from,ae_age_to AS age_to";
			$join[] = "JOIN m_age ON DATE_PART('Y',AGE($date,mn_birthday)) BETWEEN ae_age_from AND ae_age_to";
			$where[] = 'DATE_PART(\'Y\',AGE($date,mn_birthday)) BETWEEN $age_from AND $age_to';
			break;
		case 3:		// 10歳刻み
			$select[] = "ge_age_cd AS age,ge_age_from AS age_from,ge_age_to AS age_to";
			$join[] = "JOIN m_age10 ON DATE_PART('Y',AGE($date,mn_birthday)) BETWEEN ge_age_from AND ge_age_to";
			$where[] = 'DATE_PART(\'Y\',AGE($date,mn_birthday)) BETWEEN $age_from AND $age_to';
			break;
		default:
			$select[] = "0 AS age,0 AS age_from,0 AS age_to";
			break;
		}

		// 性別
		switch ($sex_option) {
		case 2:
			$select[] = "mn_sex AS sex";
			$where[] = 'mn_sex=$sex';
			break;
		default:
			$select[] = "0 AS sex";
			break;
		}

		$select = join(',', $select);
		$join = join(',', $join);
		$where = join(' AND ', $where);

		// モニターの年代、性別を取得
		$sql = "SELECT $select FROM {$monitor_flg}_monitor $join WHERE mn_monitor_id=$monitor_id";
		$result = db_exec($sql);
		if (pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);
			$age = $fetch->age;
			$age_from = $fetch->age_from;
			$age_to = $fetch->age_to;
			$sex = $fetch->sex;

			// 該当セルの設定サンプル数を取得
			if ($age_option != 1 || $sex_option != 1) {
				$sql = "SELECT cln_send_num FROM t_cell_num WHERE cln_search_id=$search_id AND cln_sex=$sex AND cln_age=$age";
				$sample_num = db_fetch1($sql);
			}

			// 既に回答済みの件数を取得
			$sql = "SELECT COUNT(*) FROM t_answer WHERE an_enquete_id=$enquete_id AND an_valid_flag";
			if ($where != '') {
				$where = eval("return \"$where\";");
				$sql .= " AND EXISTS (SELECT * FROM {$monitor_flg}_monitor WHERE mn_monitor_id=an_monitor_id AND $where)";
			}
			$answer_num = db_fetch1($sql);

			// 110%以上回答済みなら回答権無し 120%に変更2008/03/06 namiki
			if ($answer_num >= ceil($sample_num * 1.20))
				return false;
		}
	}

	return true;
}
?>
