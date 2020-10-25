<?php

function mp_make_sql ($make_type) {
	/* MAKE SQL */
	switch ($make_type) {
		case "INSERT_M_PUBLISHER":
			$sql = "INSERT INTO M_PUBLISHER ( publisher_id, mail_add_main, "
						   . "mail_add_sub, "
						   . "owner_nm_kanj, owner_nm_kana, owner_zip1, "
						   . "owner_zip2, "
	                        		      . "owner_prefec, owner_add, owner_tel, "
						      . "owner_pswd, owner_status_flg, "
						      . "pt_melonpan, pt_melmel, modefy_dt ,owner_riba_flg) "
					 . "VALUES ( :PUBLISHER_ID, :MAIL_ADD_MAIN, :MAIL_ADD_SUB, "
	                        	  . ":OWNER_NM_KANJ, :OWNER_NM_KANA, :OWNER_ZIP1, "
	                        	  . ":OWNER_ZIP2, "
					  . ":OWNER_PREFEC, :OWNER_ADD, :OWNER_TEL, :OWNER_PSWD, "
	                        	  . ":OWNER_STATUS_FLG, :PT_MELONPAN, :PT_MELMEL, SYSDATE, 1)";
			break;
		case "SELECT_M_PUBLISHER":
			$sql = "SELECT * FROM M_PUBLISHER WHERE publisher_id = :PUBLISHER_ID";
			break;
		case "SELECT_M_PUBLISHER_MAIL_ADD_MAIN":
			$sql = "SELECT * FROM M_PUBLISHER WHERE mail_add_main =";
			break;
		case "UPDATE_M_PUBLISHER":
			$sql = "UPDATE  M_PUBLISHER SET
                        			mail_add_main = :MAIL_ADD_MAIN,
                        			mail_add_sub = :MAIL_ADD_SUB,
                        			owner_nm_kanj = :OWNER_NM_KANJ,
                        			owner_nm_kana = :OWNER_NM_KANA,
                        			owner_zip1 = :OWNER_ZIP1,
                        			owner_zip2 = :OWNER_ZIP2,
                        			owner_prefec = :OWNER_PREFEC,
                        			owner_add = :OWNER_ADD,
                        			owner_tel = :OWNER_TEL,
                        			owner_pswd = :OWNER_PSWD,
						modefy_dt   = SYSDATE
						 WHERE publisher_id = :PUBLISHER_ID";
			break;
		case "UPDATE_M_PUBLISHER_ONE":
			$sql = "UPDATE  M_PUBLISHER SET
                        			mail_add_main = :MAIL_ADD_MAIN,
                        			owner_nm_kanj = :OWNER_NM_KANJ,
                        			owner_nm_kana = :OWNER_NM_KANA,
                        			owner_zip1 = :OWNER_ZIP1,
                        			owner_zip2 = :OWNER_ZIP2,
                        			owner_add = :OWNER_ADD,
                        			owner_tel = :OWNER_TEL,
                        			owner_pswd = :OWNER_PSWD,
						modefy_dt   = SYSDATE
						 WHERE publisher_id = :PUBLISHER_ID";

			break;
		case "INSERT_T_PUB_DELREASON":
			$sql = "INSERT INTO T_PUB_DELREASON (	pub_del_id ,
								publisher_id ,
                        					reason ,
	                        				modefy_dt )
						VALUES (
								:PUB_DEL_ID,
                        					:PUBLISHER_ID,
	                        				:REASON,
	                        				SYSDATE      )";
			break;
		case "UPDATE_T_PUB_DELREASON":
			$sql = "UPDATE  T_PUB_DELREASON SET
                        		publisher_id      = :PUBLISHER_ID,
	                        	reason      = :REASON,
					modefy_dt   = SYSDATE
					WHERE pub_del_id = :PUB_DEL_ID";
			break;
		case "SELECT_T_PUB_DELREASON_ID":
			$sql = "SELECT * FROM T_PUB_DELREASON WHERE pub_del_id = :PUB_DEL_ID";
			break;

		default:
			break;
	}
	return $sql;
}
?>
