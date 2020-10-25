<?php

function mm_make_sql ($make_type) {
	/* MAKE SQL */
	switch ($make_type) {
/* **
		case "INSERT_M_MAGAZINE":
			$sql = "INSERT INTO M_MAGAZINE (
								mag_id ,
                        				publisher_id ,
					                    mail_add_publish,
						                mag_nm,
						                mag_intro_long,
						                mag_intro_short,
						                mag_hp,
						                mag_type,
						                mag_cycle,
						                mag_cycle_day,
						                mag_catg_1,
						                mag_catg_2,
						                mag_bg_1,
						                mag_bg_2,
						                mag_reader_sex,
						                mag_reader_age,
						                mag_reader_area,
						                mag_reader_occup,
						                mag_bn_pub,
						                ml_type,
						                mag_status_flg,
						                mag_pub_status_flg,
						                mag_pub_stop_flg,
						                mag_pub_status_dt,
						                modefy_dt,
						                melmel_id,
										mag_present_ins_flg,
										mag_original_flg,
										mag_from_addr,
										mag_subject_tmpl,
										mag_header_tmpl,
										mag_footer_tmpl,
										send_notice_flg,
						                last_send_dt )
						VALUES (
								:MAG_ID ,
                        				:PUBLISHER_ID ,
					                    :MAIL_ADD_PUBLISH,
						                :MAG_NM,
						                :MAG_INTRO_LONG,
						                :MAG_INTRO_SHORT,
						                :MAG_HP,
						                :MAG_TYPE,
						                :MAG_CYCLE,
						                :MAG_CYCLE_DAY,
						                :MAG_CATG_1,
						                :MAG_CATG_2,
						                :MAG_BG_1,
						                :MAG_BG_2,
						                :MAG_READER_SEX,
						                :MAG_READER_AGE,
						                :MAG_READER_AREA,
						                :MAG_READER_OCCUP,
						                :MAG_BN_PUB,
						                :ML_TYPE,
						                :MAG_STATUS_FLG,
						                :MAG_PUB_STATUS_FLG,
						                :MAG_PUB_STOP_FLG,
						                SYSDATE,
						                SYSDATE,
						                :MELMEL_ID,
										:MAG_PRESENT_INS_FLG,
										:MAG_ORIGINAL_FLG,
										:MAG_FROM_ADDR,
										:MAG_SUBJECT_TMPL,
										:MAG_HEADER_TMPL,
										:MAG_FOOTER_TMPL,
										:SEND_NOTICE_FLG,
						                :LAST_SEND_DT )";
			break;
*/
		case "INSERT_M_MAGAZINE_NOT_CYCLEDAY":
			$sql = "INSERT INTO M_MAGAZINE (
								mag_id ,
                        				publisher_id ,
					                    mail_add_publish,
						                mag_nm,
						                mag_intro_long,
						                mag_intro_short,
						                mag_hp,
						                mag_type,
						                mag_cycle,
						                mag_catg_1,
						                mag_catg_2,
						                mag_bg_1,
						                mag_bg_2,
						                mag_reader_sex,
						                mag_reader_age,
						                mag_reader_area,
						                mag_reader_occup,
						                mag_bn_pub,
						                ml_type,
						                mag_status_flg,
						                mag_pub_status_flg,
						                mag_pub_stop_flg,
						                mag_pub_status_dt,
						                modefy_dt,
						                melmel_id,
										mag_present_ins_flg,
										mag_original_flg,
										mag_from_addr,
										mag_subject_tmpl,
										mag_header_tmpl,
										mag_footer_tmpl,
										send_notice_flg,
						                last_send_dt )
						VALUES (
								:MAG_ID ,
                        				:PUBLISHER_ID ,
					                    :MAIL_ADD_PUBLISH,
						                :MAG_NM,
						                :MAG_INTRO_LONG,
						                :MAG_INTRO_SHORT,
						                :MAG_HP,
						                :MAG_TYPE,
						                :MAG_CYCLE,
						                :MAG_CATG_1,
						                :MAG_CATG_2,
						                :MAG_BG_1,
						                :MAG_BG_2,
						                :MAG_READER_SEX,
						                :MAG_READER_AGE,
						                :MAG_READER_AREA,
						                :MAG_READER_OCCUP,
						                :MAG_BN_PUB,
						                :ML_TYPE,
						                :MAG_STATUS_FLG,
						                :MAG_PUB_STATUS_FLG,
						                :MAG_PUB_STOP_FLG,
						                SYSDATE,
						                SYSDATE,
						                :MELMEL_ID,
										:MAG_PRESENT_INS_FLG,
										:MAG_ORIGINAL_FLG,
										:MAG_FROM_ADDR,
										:MAG_SUBJECT_TMPL,
										:MAG_HEADER_TMPL,
										:MAG_FOOTER_TMPL,
										:SEND_NOTICE_FLG,
						                :LAST_SEND_DT )";
			break;
		case "UPDATE_M_MAGAZINE":
			$sql = "UPDATE M_MAGAZINE SET "
					. "mail_add_publish = :MAIL_ADD_PUBLISH, "
					. "mag_nm = :MAG_NM, "
					. "mag_intro_long = :MAG_INTRO_LONG, "
	                . "mag_intro_short = :MAG_INTRO_SHORT, "
	                . "mag_hp = :MAG_HP, "
	                . "mag_type = :MAG_TYPE, "
	                . "mag_cycle = :MAG_CYCLE, "
	                . "mag_cycle_day = :MAG_CYCLE_DAY, "
	                . "mag_catg_1 = :MAG_CATG_1, "
	                . "mag_catg_2 = :MAG_CATG_2, "
	                . "mag_bg_1 = :MAG_BG_1, "
	                . "mag_bg_2 = :MAG_BG_2, "
	                . "mag_reader_sex = :MAG_READER_SEX, "
	                . "mag_reader_age = :MAG_READER_AGE, "
	                . "mag_reader_area = :MAG_READER_AREA, "
					. "mag_reader_occup = :MAG_READER_OCCUP, "
					. "mag_bn_pub = :MAG_BN_PUB, "
					. "modefy_dt = SYSDATE, "
					. "melmel_id = :MELMEL_ID, "
					. "mag_present_ins_flg = :MAG_PRESENT_INS_FLG, "
					. "mag_original_flg = :MAG_ORIGINAL_FLG, "
					. "mag_from_addr = :MAG_FROM_ADDR, "
					. "mag_subject_tmpl = :MAG_SUBJECT_TMPL, "
					. "mag_header_tmpl = :MAG_HEADER_TMPL, "
		 			. "mag_footer_tmpl = :MAG_FOOTER_TMPL, "
		 			. "send_notice_flg = :SEND_NOTICE_FLG "
				. "WHERE mag_id = :MAG_ID ";
			break;
		case "UPDATE_M_MAGAZINE_NOT_CYCLEDAY":
			$sql = "UPDATE M_MAGAZINE SET "
					. "mail_add_publish = :MAIL_ADD_PUBLISH, "
					. "mag_nm = :MAG_NM, "
					. "mag_intro_long = :MAG_INTRO_LONG, "
	                . "mag_intro_short = :MAG_INTRO_SHORT, "
	                . "mag_hp = :MAG_HP, "
	                . "mag_type = :MAG_TYPE, "
	                . "mag_cycle = :MAG_CYCLE, "
	                . "mag_catg_1 = :MAG_CATG_1, "
	                . "mag_catg_2 = :MAG_CATG_2, "
	                . "mag_bg_1 = :MAG_BG_1, "
	                . "mag_bg_2 = :MAG_BG_2, "
	                . "mag_reader_sex = :MAG_READER_SEX, "
	                . "mag_reader_age = :MAG_READER_AGE, "
	                . "mag_reader_area = :MAG_READER_AREA, "
					. "mag_reader_occup = :MAG_READER_OCCUP, "
					. "mag_bn_pub = :MAG_BN_PUB, "
					. "modefy_dt = SYSDATE, "
					. "melmel_id = :MELMEL_ID, "
					. "mag_present_ins_flg = :MAG_PRESENT_INS_FLG "
					. "mag_original_flg = :MAG_ORIGINAL_FLG, "
					. "mag_from_addr = :MAG_FROM_ADDR, "
					. "mag_subject_tmpl = :MAG_SUBJECT_TMPL, "
					. "mag_header_tmpl = :MAG_HEADER_TMPL, "
		 			. "mag_footer_tmpl = :MAG_FOOTER_TMPL, "
		 			. "send_notice_flg = :SEND_NOTICE_FLG "
				. "WHERE mag_id = :MAG_ID ";
			break;
		case "DELETE_M_MAGAZINE":
			$sql = "DELETE M_MAGAZINE WHERE mag_id = "
						. ":MAG_ID and publisher_id = :PUBLISHER_ID";
			break;
		case "SELECT_M_MAGAZINE_PUB_MAG":
			$sql = "SELECT * FROM M_MAGAZINE WHERE (mag_id = "
						. ":MAG_ID and publisher_id = :PUBLISHER_ID) "
						. " and (mag_pub_status_flg = '01' or mag_pub_status_flg = '03') "
						. " and (mag_pub_stop_flg <> '02') ";
			break;
/* **
		case "SELECT_M_MAGAZINE_PUB":
			$sql = "SELECT * FROM M_MAGAZINE WHERE publisher_id = :PUBLISHER_ID"
						. " and (mag_pub_status_flg = '01' or mag_pub_status_flg = '03') "
						. " and (mag_pub_stop_flg <> '02') ";
			break;
*/
		case "SELECT_M_MAGAZINE_PUB_RED":
			$sql = "SELECT * FROM M_MAGAZINE WHERE publisher_id = :PUBLISHER_ID"
						. " and ((mag_pub_status_flg = '00' or mag_pub_status_flg = '02') "
						. " or (mag_pub_stop_flg = '02')) ";
			break;
		case "SELECT_M_MAGAZINE_PUB_KJ":
			$sql = "SELECT * FROM M_MAGAZINE WHERE publisher_id = :PUBLISHER_ID";
			break;
		case "SELECT_M_MAGAZINE_PUB_K":
			$sql = "SELECT * FROM M_MAGAZINE WHERE publisher_id = :PUBLISHER_ID "
						. "AND mag_status_flg = '00'"
						. " and (mag_pub_status_flg = '01' or mag_pub_status_flg = '03') "
						. " and (mag_pub_stop_flg <> '02') ";
			break;
		case "SELECT_M_MAGAZINE_PUB_F":
			$sql = "SELECT * FROM M_MAGAZINE WHERE publisher_id = :PUBLISHER_ID "
						. "AND mag_status_flg = '01'"
						. " and (mag_pub_status_flg = '01' or mag_pub_status_flg = '03') "
						. " and (mag_pub_stop_flg <> '02') ";
			break;
		case "SELECT_M_MAGAZINE_MAG":
			$sql = "SELECT mag_id FROM M_MAGAZINE WHERE mag_id = :MAG_ID";
			break;
		case "SELECT_M_MAGAZINE":
			$sql = "SELECT * FROM M_MAGAZINE WHERE mag_id = :MAG_ID";
			break;
		case "SELECT_MAG_RES":
			$sql = "SELECT m_magazine.mag_id, m_magazine.mag_nm, t_body.index_id,
				t_body.mag_titl, t_body.mag_body, t_pub_reserve.resrv_month,
				t_pub_reserve.resrv_day,
				to_char(t_pub_reserve.resrv_hour, 'yyyy/mm/dd hh24:mi') as resrv_hour_g,
				m_magazine.mag_type 
				FROM M_MAGAZINE, T_PUB_RESERVE, T_BODY
				WHERE m_magazine.mag_id = t_body.mag_id and
				t_body.index_id = t_pub_reserve.index_id and
				t_pub_reserve.resrv_flg = '0 '";
			break;
		case "INSERT_T_BODY":
			$sql = "INSERT INTO T_BODY (
								index_id ,
                        					mag_id ,
	                        				body_id,
	                        				mag_titl ,
	                        				mag_body ,
								modefy_dt )
						VALUES (
								:INDEX_ID,
                        					:MAG_ID,
	                        				:BODY_ID,
	                        				:MAG_TITL,
	                        				:MAG_BODY,
	                        				SYSDATE      )";
			break;
		case "INSERT_T_PUB_RESERVE":
			$sql = "INSERT INTO T_PUB_RESERVE (
								index_id ,
                        					mag_id ,
	                        				body_id,
	                        				resrv_month,
	                        				resrv_day,
	                        				resrv_hour,
	                        				resrv_flg,
								modefy_dt )
						VALUES (
							:INDEX_ID,
                        				:MAG_ID,
	                        			:BODY_ID,
	                        			:RESRV_MONTH,
							:RESRV_DAY,
	                        			to_date(:RESRV_HOUR, 'YYYY/MM/DD HH24:MI'),
	                        			:RESRV_FLG,
	                        			SYSDATE      )";
			break;
		case "UPDATE_T_BODY":
			$sql = "UPDATE T_BODY SET
                        					mag_id   = :MAG_ID,
	                        				body_id  = :BODY_ID,
	                        				mag_titl = :MAG_TITL ,
	                        				mag_body = :MAG_BODY,
	                        				modefy_dt = SYSDATE
						    WHERE index_id = :INDEX_ID";

			break;
		case "UPDATE_T_PUB_RESERVE":
			$sql = "UPDATE  T_PUB_RESERVE SET
                        		mag_id      = :MAG_ID,
	                        	body_id     = :BODY_ID,
					resrv_month = :RESRV_MONTH,
	                        	resrv_day   = :RESRV_DAY,
	                        	resrv_hour  = to_date(:RESRV_HOUR, 'YYYY/MM/DD HH24:MI'),
	                        	resrv_flg   = :RESRV_FLG,
	                        	modefy_dt = SYSDATE
					WHERE index_id = :INDEX_ID";
			break;
		case "DELETE_T_BODY":
			$sql = "DELETE T_BODY WHERE index_id = :INDEX_ID";
			break;
		case "DELETE_T_RESERVE":
			$sql = "DELETE T_PUB_RESERVE WHERE index_id = :INDEX_ID";
			break;
		case "SELECT_T_BODY":
			$sql = "SELECT * FROM T_BODY WHERE index_id = :INDEX_ID ";
			break;
		case "SELECT_T_BODY_BID_MAX":
			$sql = "SELECT body_id FROM T_BODY WHERE mag_id = :MAG_ID
									ORDER BY BODY_ID DESC";
			break;
		case "SELECT_T_BODY_MAG_BODY":
			$sql = "SELECT * FROM T_BODY WHERE mag_id = :MAG_ID and body_id = :BODY_ID";
			break;
		case "SELECT_T_RESERVE_MAG_BODY":
			$sql = "SELECT * FROM T_PUB_RESERVE WHERE mag_id = :MAG_ID and body_id = :BODY_ID";
			break;
		case "SELECT_T_RESERVE_INDEX":
			$sql = "SELECT * FROM T_PUB_RESERVE WHERE index_id = :INDEX_ID";
			break;
		case "SELECT_T_RESERVE_LOAD":
			$sql = "SELECT  mag_id,
	                        	body_id,
					resrv_month,
	                        	resrv_day,
	                        	to_char(resrv_hour, 'YYYY') resrv_year,
	                        	to_char(resrv_hour, 'hh24') resrv_hour_a,
	                        	to_char(resrv_hour, 'hh12') resrv_hour_h,
	                        	to_char(resrv_hour, 'mi') resrv_hour_m,
	                        	resrv_flg FROM T_PUB_RESERVE WHERE index_id = :INDEX_ID";
			break;
		case "SELECT_T_BODY_MAG":
			$sql = "SELECT * FROM T_BODY WHERE mag_id = :MAG_ID ";
			break;
		case "SELECT_T_RESERVE_MAG":
			$sql = "SELECT * FROM T_PUB_RESERVE WHERE mag_id = :MAG_ID ";
			break;
		case "INSERT_T_MAG_DELREASON":
			$sql = "INSERT INTO T_MAG_DELREASON (	mag_del_id ,
								mag_id ,
                        					reason ,
	                        				modefy_dt )
						VALUES (
								:MAG_DEL_ID,
                        					:MAG_ID,
	                        				:REASON,
	                        				SYSDATE      )";
			break;
		case "UPDATE_T_MAG_DELREASON":
			$sql = "UPDATE  T_MAG_DELREASON SET
                        		mag_id      = :MAG_ID,
	                        	reason      = :REASON,
					modefy_dt   = SYSDATE
					WHERE mag_del_id = :MAG_DEL_ID";
			break;
		case "SELECT_T_MAG_DELREASON_ID":
			$sql = "SELECT * FROM T_MAG_DELREASON WHERE mag_del_id = :MAG_DEL_ID";
			break;
		case "INSERT_T_MAG_STOPREASON":
			$sql = "INSERT INTO T_MAG_STOPREASON (	mag_stop_id ,
								mag_id ,
                        					reason ,
	                        				modefy_dt )
						VALUES (
								:MAG_STOP_ID,
                        					:MAG_ID,
	                        				:REASON,
	                        				SYSDATE      )";
			break;
		case "UPDATE_T_MAG_STOPREASON":
			$sql = "UPDATE  T_MAG_STOPREASON SET
                        		mag_id      = :MAG_ID,
	                        	reason      = :REASON,
					modefy_dt   = SYSDATE
					WHERE mag_stop_id = :MAG_STOP_ID";
			break;
		case "SELECT_T_MAG_STOPREASON_ID":
			$sql = "SELECT * FROM T_MAG_STOPREASON WHERE mag_stop_id = :MAG_STOP_ID";
			break;

		default:
			break;
	}
	return $sql;
}
?>
