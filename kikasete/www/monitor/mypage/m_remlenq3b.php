<?
$top = './..';
$inc = "$top/../inc";
include("$inc/enquete.php");
include("$inc/point.php");
include("$inc/enq_cleaning.php");
$inc = "$top/inc";
$img = "$top/image";
//include("$inc/mn_mypage.php");
//include("$inc/mn_header2.php");
include("$top/../inc/database.php");
include("$inc/mn_disp.php");
include("$inc/mn_chara.php");
include("$inc/mn_error.php");
include("$inc/mn_enquete.php");
include("$top/../inc/common.php");
include("$inc/mn_request.php");

$monitor_id = $_SESSION['ss_monitor_id'];
$answer_ary = &$_SESSION['ss_answer'];

// モニターテーブル
$monitor_tbl = 't_monitor';
if ($_SESSION['ss_req_flg'] == 'g')
	$monitor_tbl = 'g_monitor';
$back = 'history.back()';
$ret_url = $_SESSION['ss_ret_url'];

// アンケートID存在チェック
if ($enquete_id == '') {
	$msg[] = 'アンケートが選択されていないようです。';
}

// テストか本番か識別  2006/03/3 BTI
$isTesting = false;
if (!$msg) {
	$sql = "SELECT mep_enquete_id,mep_enquete2_id FROM t_pro_enquete WHERE mep_enquete_id=$enquete_id or mep_enquete2_id=$enquete_id";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		if ( $fetch->mep_enquete_id == $fetch->mep_enquete2_id ) { // t_enqeute->en_enq_kind is 6
			$sql = "SELECT en_enq_kind,en_status,en_sub_status FROM t_enquete WHERE en_enquete_id=$enquete_id";
			$result = db_exec($sql);
			if (pg_numrows($result)) {
				$fetch = pg_fetch_object($result, 0);
				if ( $fetch->en_enq_kind == 6 && $fetch->en_status == 3 && $fetch->en_sub_status == 2 ) { // ファインディングアンケートでデータ格納確認中な?E
					$isTesting = true;
				}
			}
		} else { // current enquete_id->en_enq_kind is 5 or 6
			$sql = "SELECT en_enq_kind,en_status,en_sub_status FROM t_enquete WHERE en_enquete_id=$fetch->mep_enquete_id";
			$result = db_exec($sql);
			if (pg_numrows($result)) {
				$fetch = pg_fetch_object($result, 0);
				if ( $fetch->en_enq_kind == 5 && $fetch->en_status == 3 && $fetch->en_sub_status == 2 ) { // ファインディングアンケートでデータ格納確認中な?E
					$isTesting = true;
				}
			}
		}
	}
}

if ( !$isTesting ) {
	// アンケート有効性チェック
	if ($_SESSION['ss_req_flg'] == 't') {
		if (!$msg) {
			$sql = "SELECT en_point FROM t_enquete WHERE en_enquete_id=$enquete_id AND en_status IN (5,10) AND en_enq_type=2";
			$result = db_exec($sql);
			if (pg_numrows($result)) {
				$fetch = pg_fetch_object($result, 0);
				$point = $fetch->en_point;
			} else
				$msg[] = '選択されたアンケートは有効ではありません。';
		}
	}
	if ($_SESSION['ss_req_flg'] == 't') {
		// 回答対象者チェック
		if (!$msg) {
			$sql = "SELECT el_enquete_id FROM t_enquete_list WHERE el_enquete_id=$enquete_id AND el_monitor_id=$monitor_id";
			$result = db_exec($sql);
			if (pg_numrows($result) == 0)
				$msg[] = 'あなたはこのアンケートに参加していません。';
		}
	}
}

// 回答済みチェック
if (!$msg) {
	$sql = "SELECT an_enquete_id FROM t_answer WHERE an_enquete_id=$enquete_id AND an_monitor_id=$monitor_id";
	$result = db_exec($sql);
	if (pg_numrows($result))
		$msg[] = 'あなたは既にこのアンケートに回答されているようです。';
}

$enquete = new enquete_class;
$enquete->read_db($enquete_id);

if (!$msg) {
	// セッション切れチェック
	if (!isset($answer_ary)) {
		// 回答済みにするため、無効回答として登録
		$rec['an_enquete_id'] = sql_number($enquete_id);
		$rec['an_monitor_id'] = sql_number($monitor_id);
		$rec['an_valid_flag'] = sql_bool(DBFALSE);
		if ( $isTesting ) {
			$rec['an_test_flg'] = sql_bool(DBTRUE);
		}
		db_insert('t_answer', $rec);

		// ファインディングアンケートの回答を無効とする
		if ($enquete->enq_kind == 6) {
			$sql = "SELECT mep_enquete_id FROM t_pro_enquete WHERE mep_enquete2_id=$enquete_id";
			$finding_enquete_id = db_fetch1($sql);
			if ($finding_enquete_id) {
				$rec['an_valid_flag'] = sql_bool(DBFALSE);
				db_update('t_answer', $rec, "an_enquete_id=$finding_enquete_id AND an_monitor_id=$monitor_id");
			}
		}

		if ($_SESSION['ss_req_flg'] == 't') {
			// ポイント付与
			monitor_point($monitor_id, PT_ENQUETE, $point, null, null, $enquete_id);

			redirect("m_remlenq_err.php");
		} else {
			redirect("m_remlenq_err.php?id=g${monitor_id}g${enqId}&status=" . ($enquete->enq_kind == 5 ? '2' : '1'));
		}
	}

	foreach ($enquete->question as $qno => $question) {
		if ($qno >= $first_qno && $qno <= $last_qno) {
			// 必須回答チェック
			if ($question->must_flag == 't') {
				switch ($question->question_type) {
				case 1:     // 単一選択肢
				case 2:     // 複数選択肢
				case 6:		// 数量回答
				case 7:		// プルダウン
					if ($_POST["answer$qno"] == '')
						$msg[] = "Ｑ{$qno}の回答が入力されていないようです。";
					break;
				case 3:     // フリー回答
				case 8:		// フリー回答（１行）
					if (trim(str_replace('　', '', $_POST["answer$qno"])) == '')
						$msg[] = "Ｑ{$qno}の回答が入力されていないようです。";
					break;
				case 4:     // マトリクス(SA)
				case 5:     // マトリクス(MA)
					$sno_ary = array();
					if (is_array($_POST["answer$qno"])) {
						foreach ($_POST["answer$qno"] as $no) {
							$ary = explode(':', $no);
							$sno_ary[] = $ary[0];
						}
					}
					foreach (array_keys($question->hyousoku) as $sno) {
						if (!in_array($sno, $sno_ary))
							$msg[] = "Ｑ{$qno}の{$sno}行目の回答が入力されていないようです。";
					}
					break;
				}
			}

			// 単一選択肢,複数選択肢のフリー回等チェック
			if ($question->fa_sno) {
				switch ($question->question_type) {
				case 1:     // 単一選択肢
					if ($_POST["answer$qno"] == $question->fa_sno) {
						if (trim(str_replace('　', '', $_POST["free_answer$qno"])) == '')
							$msg[] = "Ｑ{$qno}のフリー回答が入力されていないようです。";
					}
					break;
				case 2:     // 複数選択肢
					if (is_array($_POST["answer$qno"])) {
						foreach ($_POST["answer$qno"] as $answer) {
							if ($answer == $question->fa_sno) {
								if (trim(str_replace('　', '', $_POST["free_answer$qno"])) == '')
									$msg[] = "Ｑ{$qno}のフリー回答が入力されていないようです。";
							}
						}
					}
					break;
				}
			}

			// 排他選択チェック
			if ($question->ex_sno != 0 && $question->question_type == 2) {
				if (count($_POST["answer$qno"]) > 1 && in_array($question->ex_sno, $_POST["answer$qno"]))
					$msg[] = "Ｑ{$qno}で「{$question->sel_text[$question->ex_sno]}」を選択された場合はその他の選択はできません。";
			}

			// 数量回答の数値チェック
			if ($question->question_type == 6) {
				$len = strlen($_POST["answer$qno"]);
				for ($i = 0; $i < $len; $i++) {
					$c = $_POST["answer$qno"][$i];
					if (!(($c == '-' && $i == 0) || ($c >= '0' && $c <= '9') || $c == '.')) {
						$msg[] = "Ｑ{$qno}は数字で入力してください。";
						break;
					}
				}
			}
		}
	}
}

// マトリクスの重複回答チェック
if (!$msg) {
	foreach ($enquete->question as $qno => $question) {
		if ($qno >= $first_qno && $qno <= $last_qno) {
			if ($question->question_type == 4 || $question->question_type == 5) {
				if ($question->dup_flag == 't') {
					if (is_array($_POST["answer$qno"])) {
						$tno = array();
						foreach ($_POST["answer$qno"] as $no) {
							$ary = explode(':', $no);
							$tno[] = $ary[1];
						}
						if (count($tno) != count(array_unique($tno)))
							$msg[] = "Ｑ{$qno}の回答に重複があるようです。";
					}
				}
			}
		}
	}

	// ニックネームの入力チェック
	if ($enquete->enq_kind == 4) {
		if (isset($nickname) && $nickname == '')
			$msg[] = 'ニックネームが入力されていないようです。';
	}
}

if (!$msg) {
	// アンケート内容取得
	for ($qno = $first_qno; $qno <= $last_qno; $qno++) {
		$question = &$enquete->question[$qno];
		unset($answer_ary[$qno]);

		switch ($question->question_type) {
		case 1:     // 単一選択肢
		case 7:		// プルダウン
			$answer = $_POST["answer$qno"];
			if ($answer != '')
				$answer_ary[$qno][$answer] = ($answer == $question->fa_sno) ? $_POST["free_answer$qno"] : '';
			break;
		case 2:     // 複数選択肢
			if (is_array($_POST["answer$qno"])) {
				foreach ($_POST["answer$qno"] as $answer)
					$answer_ary[$qno][$answer] = ($answer == $question->fa_sno) ? $_POST["free_answer$qno"] : '';
			}
			break;
		case 3:     // フリー回答
		case 6:		// 数量回答(NA)
		case 8:		// フリー回答（１行）
			$answer = $_POST["answer$qno"];
			if ($answer != '')
				$answer_ary[$qno][0] = $answer;
			break;
		case 4:     // マトリクス(SA)
		case 5:     // マトリクス(MA)
			if (is_array($_POST["answer$qno"])) {
				foreach ($_POST["answer$qno"] as $answer)
					$answer_ary[$qno][$answer] = '';
			}
			break;
		}
	}
	unset($question);

	// 分岐条件チェック
	$next_qno = 0;
	foreach ($enquete->question as $qno => $question) {
		if ($qno <= $last_qno)
			continue;

		$branch_cond = &$enquete->branch_cond[$qno];

		if (isset($branch_cond)) {
			$ok = ($branch_cond->and_or == 'A') ? true : false;
			foreach ($branch_cond->cond as $cond) {
				$select_no_ary = explode(',', $cond->select_no);
				$match_count = 0;
				$unmatch_count = 0;
				//jeon_start seq=81
				if($cond->cond_question_no>=10000){
					$cond->cond_question_no=$cond->cond_question_no-10000;
					$sql= "SELECT eq_question_type FROM t_enq_question WHERE eq_enquete_id=$enquete_id-1 AND eq_question_no=$cond->cond_question_no";
					$result = db_exec($sql);
					$fetch = pg_fetch_object($result, 0);
					$type = $fetch->eq_question_type;
					if($type==1 || $type==2 || $type==3){
						$sql = "select as_sel_no from t_ans_select where as_enquete_id=($enquete_id-1) and as_monitor_id=$monitor_id and as_question_no=$cond->cond_question_no";
						$result = db_exec($sql);
						if(pg_numrows($result)!=0){
							$fetch = pg_fetch_object($result, 0);
							$as_select_no = explode(',',$fetch->as_sel_no);
						}
					}else{
						$sql = "select ax_hyousoku_no,ax_hyoutou_no from t_ans_matrix where ax_enquete_id=($enquete_id-1) and ax_monitor_id=$monitor_id and ax_question_no=$cond->cond_question_no";
						$result = db_exec($sql);
						if(pg_numrows($result)!=0){
							$nrow = pg_numrows($result);
							for ($i = 0; $i < $nrow; $i++) {
							$fetch = pg_fetch_object($result, $i);
							$as_select_no[$i]=$fetch->ax_hyousoku_no.":".$fetch->ax_hyoutou_no;
							}
						}
					}
					if(pg_numrows($result)!=0){
					foreach($as_select_no as $sno ) {
						if (in_array($sno, $select_no_ary))
							$match_count++;
						else
							$unmatch_count++;

						}
					}
				}else{
					if (isset($answer_ary[$cond->cond_question_no])) {
						foreach (array_keys($answer_ary[$cond->cond_question_no]) as $sno) {
							if (in_array($sno, $select_no_ary))
								$match_count++;
							else
								$unmatch_count++;
						}
				//jeon_end seq=81
					}
				}
				$select_count = count($select_no_ary);

				switch ($cond->and_or) {
				case 'A':
					$ok2 = ($match_count == $select_count);
					break;
				case 'O':
					$ok2 = ($match_count >= 1);
					break;
				case 'F':
					$ok2 = ($match_count == $select_count && $unmatch_count == 0);
					break;
				}

				if ($cond->not_cond == DBTRUE)
					$ok2 = !$ok2;

				if ($ok2) {
					if ($branch_cond->and_or == 'O') {
						$ok = true;
						break;
					}
				} else {
					if ($branch_cond->and_or == 'A') {
						$ok = false;
						break;
					}
				}
			}

			if ($ok) {
				$next_qno = $qno;
				break;
			}
		} else {
			$next_qno = $qno;
			break;
		}
	}

	// 次ページがあれば回答ページへ戻る
	if ($next_qno) {
		if ($_SESSION['ss_req_flg'] == 't') {   // きかせて
			redirect("m_remlenq3a.php?enquete_id=$enquete_id&nqno=$next_qno");
		} else {                                // InfoQ
//            redirect("m_remlenq3a.php?id=g${monitor_id}g${enquete_id}&nqno=$next_qno");
			redirect("m_remlenq3a.php?enquete_id=${enquete_id}&id=g${monitor_id}g${enqId}&nqno=$next_qno");
		}
	}

	if (is_array($answer_ary) && count($answer_ary) > 0) {
		db_begin_trans();
		// 回答保存
		$rec['an_enquete_id'] = sql_number($enquete_id);
		$rec['an_monitor_id'] = sql_number($monitor_id);
		// テストデータなら回答情報テーブルのテストフラグをTrueに設定 2006/03/03 BTI
		if ( $isTesting ) {
			$rec['an_test_flg'] = sql_bool(DBTRUE);
		}
		db_insert('t_answer', $rec);
		unset($answer);
		foreach ($answer_ary as $qno => $answer) {
			foreach ($answer as $sno => $free_text) {
				switch ($enquete->question[$qno]->question_type) {
				case 1:     // 単一選択肢
				case 2:     // 複数選択肢
				case 3:     // フリー回答
				case 6:     // 数量回答
				case 7:		// プルダウン
				case 8:		// フリー回答（１行）
					$rec['as_enquete_id'] = sql_number($enquete_id);
					$rec['as_question_no'] = sql_number($qno);
					$rec['as_sel_no'] = sql_number($sno);
					$rec['as_monitor_id'] = sql_number($monitor_id);
					$rec['as_free_answer'] = sql_char($free_text);
					db_insert('t_ans_select', $rec);
					break;
				case 4:     // マトリクス(SA)
				case 5:     // マトリクス(MA)
					$ary = explode(':', $sno);
					$rec['ax_enquete_id'] = sql_number($enquete_id);
					$rec['ax_monitor_id'] = sql_number($monitor_id);
					$rec['ax_question_no'] = sql_number($qno);
					$rec['ax_hyousoku_no'] = sql_number($ary[0]);
					$rec['ax_hyoutou_no'] = sql_number($ary[1]);
					db_insert('t_ans_matrix', $rec);
					break;
				}
			}
		}

		// サンプルクリーニング処理
//        if ($_SESSION['ss_req_flg'] == 't' && $enquete->enq_kind == 6) {    // kikasete only 2006/03/14 BTI
		if ($enquete->enq_kind == 6) {    // 両方 2006/05/17 BTI
			$cleaning = new enq_cleaning_class;
			$cleaning->read_db($enquete_id);
			$valid_flag = $cleaning->check_answer($monitor_id);
		} else
			$valid_flag = true;

		if ($valid_flag) {
			// 有効なら有効回答数カウント
			$sql = "UPDATE t_ans_count SET anc_ans_count=anc_ans_count+1 WHERE anc_enquete_id=$enquete_id";
			db_exec($sql);
		} else {
			// 無効なら有効回答フラグクリア
			$rec['an_valid_flag'] = sql_bool(DBFALSE);
			db_update('t_answer', $rec, "an_enquete_id=$enquete_id AND an_monitor_id=$monitor_id");
		}

		// きかせて・netなら、以下を処理
		if ($_SESSION['ss_req_flg'] == 't') {
			// Myパートナーの処理
			if ($enquete->enq_kind == 4) {
				// アンケートIDからプロジェクトIDを取得
				$sql = "SELECT ppj_pjt_id FROM t_mp_pjt WHERE ppj_enquete_id=$enquete_id";
				$pjt_id = db_fetch1($sql);

				// Myパートナー参加モニターテーブルに追加
				if ($pjt_id) {
					foreach (array_keys($zokugara) as $i) {
						if ($zokugara[$i] != '')
							$family_info .= "{$zokugara[$i]} {$age[$i]}才 {$gyoushu2[$i]}\n";
					}

					$sql = "SELECT COUNT(*) FROM t_mp_monitor WHERE pmn_pjt_id=$pjt_id AND pmn_monitor_id=$monitor_id";
					if (db_fetch1($sql) == 0) {
						$rec['pmn_pjt_id'] = sql_number($pjt_id);
						$rec['pmn_monitor_id'] = sql_number($monitor_id);
						$rec['pmn_nickname'] = sql_char($nickname);
						$rec['pmn_gyoushu'] = sql_char($gyoushu1);
						$rec['pmn_family_info'] = sql_char($family_info);
						db_insert('t_mp_monitor', $rec);
					}
				}
			}

			// ポイント付与
			monitor_point($monitor_id, PT_ENQUETE, $point, null, null, $enquete_id);
		}
		// きかせて・net

		// GMOならg_monitorからg_enq_monitorに属性を保存
		if ($_SESSION['ss_req_flg'] == 'g') {
			$sql = "INSERT INTO g_enq_monitor "
				. " (mn_enquete_id,mn_monitor_id,mn_enq_date,mn_birthday,mn_age,mn_sex,mn_mikikon,mn_jitaku_zip,mn_jitaku_area,mn_jitaku_addr1,mn_jitaku_addr2,mn_jitaku_addr3,mn_shokugyou_cd,mn_gyoushu_cd,mn_shokushu_cd,mn_kinmu_area,mn_have_child,mn_housing_form,mn_have_car)"
				. " SELECT $enquete_id,mn_monitor_id,current_timestamp,mn_birthday,DATE_PART('Y',AGE(mn_birthday)),mn_sex,mn_mikikon,mn_jitaku_zip,mn_jitaku_area,mn_jitaku_addr1,mn_jitaku_addr2,mn_jitaku_addr3,mn_shokugyou_cd,mn_gyoushu_cd,mn_shokushu_cd,mn_kinmu_area,mn_have_child,mn_housing_form,mn_have_car"
				. " FROM g_monitor"
				. " WHERE mn_monitor_id=$monitor_id";
			db_exec($sql);
		}

		db_commit_trans();
		//$_SESSION['ss_chara_image'] = get_chara_image($monitor_id);

		// ファインディングアンケートの処理
		if ($enquete->enq_kind == 5) {
			$sql = "SELECT mep_sample_num,mep_enquete2_id,mep_res_and_or FROM t_pro_enquete WHERE mep_enquete_id=$enquete_id";
			$result = db_exec($sql);
			if (pg_numrows($result)) {
				$fetch = pg_fetch_object($result, 0);
				$enquete2_id = $fetch->mep_enquete2_id;
				$res_and_or = $fetch->mep_res_and_or;

				if ( !$isTesting ) {
					 // サンプル数上限チェック
					if (check_pro_enquete($monitor_id, $enquete2_id, $enquete->start_date)) {
						// 本アンケート回答条件判定
						$ok = ($res_and_or == 'A') ? true : false;

						$sql = "SELECT erc_question_no,erc_select_no,erc_and_or,erc_not_cond"
								. " FROM t_enq_res_cond"
								. " WHERE erc_enquete_id=$enquete_id"
								. " ORDER BY erc_question_no";
						$result = db_exec($sql);
						$nrow = pg_numrows($result);
						for ($i = 0; $i < $nrow; $i++) {
							$fetch = pg_fetch_object($result, $i);

							$select_no_ary = explode(',', $fetch->erc_select_no);
							$match_count = 0;

							if (isset($answer_ary[$fetch->erc_question_no])) {
								foreach (array_keys($answer_ary[$fetch->erc_question_no]) as $sno) {
									if (in_array($sno, $select_no_ary))
										$match_count++;
									else
										$unmatch_count++;
								}
							}

							$select_count = count($select_no_ary);

							switch ($fetch->erc_and_or) {
							case 'A':
								$ok2 = ($match_count == $select_count);
								break;
							case 'O':
								$ok2 = ($match_count >= 1);
								break;
							case 'F':
								$ok2 = ($match_count == $select_count && $unmatch_count == 0);
								break;
							}

							if ($fetch->erc_not_cond == DBTRUE)
								$ok2 = !$ok2;

							if ($ok2) {
								if ($res_and_or == 'O') {
									$ok = true;
									break;
								}
							} else {
								if ($res_and_or == 'A') {
									$ok = false;
									break;
								}
							}
						}

						if ($ok) {
							// 本アンケート回答許可
							// きかせて・netなら、以下を処理
							if ($_SESSION['ss_req_flg'] == 't') {
								$sql = sprintf("SELECT COUNT(*) FROM t_enquete_list WHERE el_enquete_id=%s AND el_monitor_id=%s", sql_number($enquete2_id), sql_number($monitor_id));
								if (db_fetch1($sql) == 0) {
									$rec['el_enquete_id'] = sql_number($enquete2_id);
									$rec['el_monitor_id'] = sql_number($monitor_id);
									$rec['el_send_date'] = 'current_timestamp';
									$rec['el_status'] = sql_number(1);
									db_insert('t_enquete_list', $rec);
								}

								// 本アンケートへ(きかせて）
								redirect("m_remlenq3a.php?enquete_id=$enquete2_id");
			   
							} else {
								// 本アンケートへ(GMO)
								$_SESSION['enquete_id'] = $enquete2_id;
//                                redirect("m_remlenq3a.php?id=g${monitor_id}g${enquete2_id}&k=5");
								  redirect("m_remlenq3a.php?enquete_id=${enquete2_id}&id=g${monitor_id}g${enqId}&k=5");
							}
						} else {    // GMO ファインディングアンケートのみ
							if ($_SESSION['ss_req_flg'] != 't') {
								$ret_url .= "&status=2";
							}
						}
					} else { // GMOでサンプル数を超えた場合
						if ($_SESSION['ss_req_flg'] != 't') {
							$ret_url .= "&status=2";
						}
					}
				} else {
					// 本アンケート回答条件判定
					$ok = ($res_and_or == 'A') ? true : false;

					$sql = "SELECT erc_question_no,erc_select_no,erc_and_or,erc_not_cond"
							. " FROM t_enq_res_cond"
							. " WHERE erc_enquete_id=$enquete_id"
							. " ORDER BY erc_question_no";
					$result = db_exec($sql);
					$nrow = pg_numrows($result);
					for ($i = 0; $i < $nrow; $i++) {
						$fetch = pg_fetch_object($result, $i);

						$select_no_ary = explode(',', $fetch->erc_select_no);
						$match_count = 0;
						$unmatch_count = 0;
						$unmatch_count = 0;
						if (isset($answer_ary[$fetch->erc_question_no])) {
							foreach (array_keys($answer_ary[$fetch->erc_question_no]) as $sno) {
								if (in_array($sno, $select_no_ary))
									$match_count++;
								else
									$unmatch_count++;
							}
						}

						$select_count = count($select_no_ary);

						switch ($fetch->erc_and_or) {
						case 'A':
							$ok2 = ($match_count == $select_count);
							break;
						case 'O':
							$ok2 = ($match_count >= 1);
							break;
						case 'F':
							$ok2 = ($match_count == $select_count && $unmatch_count == 0);
							break;
						}

						if ($fetch->erc_not_cond == DBTRUE)
							$ok2 = !$ok2;

						if ($ok2) {
							if ($res_and_or == 'O') {
								$ok = true;
								break;
							}
						} else {
							if ($res_and_or == 'A') {
								$ok = false;
								break;
							}
						}
					}

					if ($ok) {
						// 本アンケート回答許可
						// きかせて・netなら、以下を処理
						if ($_SESSION['ss_req_flg'] == 't') {
							// 本アンケートへ(きかせて）
						redirect("m_remlenq3a.php?enquete_id=$enquete2_id");
						} else {
							// 本アンケートへ(GMO)
							$_SESSION['enquete_id'] = $enquete2_id;
//                            redirect("m_remlenq3a.php?id=g${monitor_id}g${enquete2_id}&k=5");
							  redirect("m_remlenq3a.php?enquete_id=${enquete2_id}&id=g${monitor_id}g${enqId}&k=5");
						}
					} else {    // GMO ファインディングアンケートのみ
						if ($_SESSION['ss_req_flg'] != 't') {
							$ret_url .= "&status=2";
						}
					}
				} // if ( !$isTesting ) {
			}
		} else {    // GMO & 本アンケート
			if ($_SESSION['ss_req_flg'] != 't') {
				$ret_url .= "&status=1";
			}
		}
	} else {
		$msg[] = '回答がひとつも入力されていないようです。';
		if ($_SESSION['ss_req_flg'] == 't') {
			$back = "location.href='m_remlenq3a.php?enquete_id=$enquete_id'";
		} else {
//            $back = "m_remlenq3a.php?id=g${monitor_id}g${enquete_id}";
			  $back = "location.href='m_remlenq3a.php?enquete_id=${enquete_id}&id=g${monitor_id}g${enqId}'";
		}
	}
}

unset($answer_ary);
?>
<? /* monitor_menu() */ ?>
<?
if (!$msg) {
	if ($_SESSION['ss_req_flg'] == 't') {
?>
	<HTML>
	<HEAD>
		<TITLE>きかせて・net</TITLE>
		<META http-equiv=Content-Type content="text/html; charset=EUC-JP">
	<script type="text/javascript">
	<!--
	function opener_redirect(){
		window.parent.opener.top.location.href="<?=$ret_url?>";
	}
	// -->
	</script>
	</HEAD>
	<?
	// 格納テスト中はopener_redirect()なし  2006/03/3 BTI
	if ( !$isTesting ) {
	?>
	<BODY onLoad="opener_redirect();">
	<?
	} else { ?>
	<BODY>
	<? } ?>

		<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
			<TBODY>
				<TR bgColor=#c0e2fa>
					<TD><IMG height=29 alt=通常アンケート src="<?=$img?>/my_enq.gif" width=45 align=absMiddle>
						<SPAN class=mysttl> 通常アンケート </SPAN>
					</TD>
				</TR>
				<TR>
					<TD><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD></TR>
				<TR>
					<TD bgColor=#88b7d8><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD>
				</TR>
			</TBODY>
		</TABLE>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td width="15">　</td>
				<td colspan="2">
					<table border="0" cellpadding="20" cellspacing="0" width="100%" bgcolor="white">
						<tr>
							<td><font color="#2b2b63"><b>今回は、「<?=htmlspecialchars($enquete->title)?>」にご回答いただき、ありがとうございました。</b></font> <font size="2"><br><br>
									<?
										// テストの場合、回答終了後モニタへのメッセージを出さない
										if ( !$isTesting ) {
									?>

								また、<? disp_monitor_name() ?>さんにアンケートのお知らせがあった場合には、ぜひご回答くださいね。<br>
									<?  } ?>
								</font>
								<hr width="100%">
								<center>
									<input type="button" value="　閉じる　" onclick="javascript: self.close ();">
								</center>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</BODY>
	</HTML>
	<?
	} // if ($_SESSION['ss_req_flg'] == 't')
	else { // GMOの場合
		header("Location: $ret_url");
/*
	?>

	<HTML>
	<HEAD>
		<TITLE>きかせて・net</TITLE>
		<META http-equiv=Content-Type content="text/html; charset=EUC-JP">
	<script type="text/javascript">
	<!--
	function opener_redirect(){
		window.parent.top.location.href="<?=$ret_url?>";
	}
	// -->
	</script>
	</HEAD>
	<BODY onLoad="opener_redirect();">

		<TABLE cellSpacing=0 cellPadding=3 width="100%" border=0>
		<TBODY>
			<TR class=thanks_logo>
				<TD align=left colSpan=2>
					<!-----<img src="C:Program FilesWeb調査票作成ツール共有フォルダ/infoq_logo.gif"><br>
						<table width="100%" border="0" cellspacing="0" cellpadding="5">
						 <tr class="cover_logo">
						  <td colspan="3"><hr align="center" width="100%" size="3"></td>
						 </tr>
						</table>----->
				</TD>
			</TR>
			<TR class=thanks_contents>
				<TD width="2%">  </TD>
				<TD align=left>
					<CENTER><BR><BR>
					<TABLE cellSpacing=0 cellPadding=5 border=1>
					<TBODY>
						<TR class="16p" bgColor="#c5ff59">
							<TD>
							<CENTER>
							 以上で、<B>『<?=htmlspecialchars($enquete->title)?>』</B>は終了です。
							</CENTER>
							</TD>
						</TR>
					</TBODY>
					</TABLE>
					<BR><BR><BR><BR>
									アンケートにご協力頂き、ありがとうございました。<BR>
					<!-----今後とも、<b>infoＱ</b>をよろしくお願いいたします。<br>-----><BR><BR>
					<input type="button" value="　閉じる　" onclick="javascript: window.top.close();">
					</CENTER>
				</TD>
			</TR>
			<TR class=thanks_foot>
			<TD align=left colSpan=2></TD>
			</TR>
		</TBODY>
		</TABLE>
	<?
*/
	}
	?>
<?
} // if (!$msg)
else {
	error_msg($msg, $back);
}
?>