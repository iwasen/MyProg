<?php

function rm_make_sql ($make_type) {
	/* MAKE SQL */
	switch ($make_type) {
/* **
		case "INSERT_M_MELONPA":
			$sql = "INSERT INTO M_MELONPA (
								reader_id,
				                       		mail_addr,
						                reader_pswd,
						                reader_birth,
						                reader_sex,
						                reader_mrg,
						                reader_zip1,
						                reader_zip2,
						                reader_occup,
								pt_melonpan,
								pt_melmel,
								log_key,
								log_key_flg,
								modify_dt,
								regist_dt
 )
						VALUES (
								:READER_ID,
					                       	:MAIL_ADDR,
						                :READER_PSWD,
						                :READER_BIRTH,
						                :READER_SEX,
						                :READER_MRG,
						                :READER_ZIP1,
						                :READER_ZIP2,
						                :READER_OCCUP,
								:PT_MELONPAN,
								:PT_MELMEL,
								:LOG_KEY,
								:LOG_KEY_FLG,
								SYSDATE,
								SYSDATE
 						)";
			break;
*/
/* **
		case "UPDATE_M_MELONPA":
			$sql = "UPDATE M_MELONPA SET "
					. "mail_addr = :MAIL_ADDR, "
					. "reader_pswd = :READER_PSWD,"
					. "reader_birth = :READER_BIRTH, "
			                . "reader_sex = :READER_SEX, "
			                . "reader_mrg = :READER_MRG, "
			                . "reader_zip1 = :READER_ZIP1, "
			                . "reader_zip2 = :READER_ZIP2, "
			                . "reader_occup = :READER_OCCUP, "
			                . "log_key = :LOG_KEY, "
			                . "log_key_flg = :LOG_KEY_FLG, "
					. "modify_dt    = SYSDATE"
				. " WHERE reader_id = :READER_ID ";
			break;
*/
/* **
		case "UPDATE_M_MELONPA2":
			$sql = "UPDATE M_MELONPA SET "
					. "mail_addr = :MAIL_ADDR, "
					. "reader_pswd = :READER_PSWD,"
					. "reader_birth = :READER_BIRTH, "
			                . "reader_sex = :READER_SEX, "
			                . "reader_mrg = :READER_MRG, "
			                . "reader_zip1 = :READER_ZIP1, "
			                . "reader_zip2 = :READER_ZIP2, "
			                . "reader_occup = :READER_OCCUP, "
					. "modify_dt    = SYSDATE"
				. " WHERE reader_id = :READER_ID ";
			break;
*/
		case "DELETE_M_MELONPA":
			$sql = "DELETE M_MELONPA WHERE reader_id = "
						. ":READER_ID ";
			break;
		case "SELECT_M_MELONPA_MELO":
			$sql = "SELECT * FROM M_MELONPA WHERE reader_id = ";
			break;

		case "SELECT_T_MAILADDR_MAG":
			$sql = "SELECT  ";
			$sql = $sql . "T_MAILADDR.MAG_ID, M_MAGAZINE.MAG_NM";
			$sql = $sql . " FROM T_MAILADDR,M_MAGAZINE WHERE T_MAILADDR.MAG_ID = M_MAGAZINE.MAG_ID and T_MAILADDR.reader_id = ";
			break;

		case "SELECT_M_MELONPA_MAIL_ADR":
			$sql = "SELECT * FROM M_MELONPA where mail_addr = ";
			break;
		case "SELECT_M_MELONPA_MAIL_ADR_DATE":
			$sql = "SELECT READER_ID,";
			$sql = $sql . "MAIL_ADDR,";
			$sql = $sql . "READER_BIRTH,";
			$sql = $sql . "READER_PSWD,";
			$sql = $sql . "READER_SEX,";
			$sql = $sql . "READER_MRG,";
			$sql = $sql . "READER_ZIP1,";
			$sql = $sql . "READER_ZIP2,";
			$sql = $sql . "READER_OCCUP,";
			$sql = $sql . "PT_MELONPAN,";
	                $sql = $sql . "to_char(READER_BIRTH, 'YYYY') as WK_YEAR,";
	                $sql = $sql . "to_char(READER_BIRTH, 'MM') as WK_MONTH,";
	                $sql = $sql . "to_char(READER_BIRTH, 'DD') as WK_DAY,";
			$sql = $sql . "LOG_KEY,";
			$sql = $sql . "LOG_KEY_FLG ";
			$sql = $sql . " FROM M_MELONPA where mail_addr = ";
			break;
		case "SELECT_M_MELONPA_READER_ID_DATE":
			$sql = "SELECT ";
			$sql = $sql . "MAIL_ADDR,";
			$sql = $sql . "READER_BIRTH,";
			$sql = $sql . "READER_PSWD,";
			$sql = $sql . "READER_SEX,";
			$sql = $sql . "READER_MRG,";
			$sql = $sql . "READER_ZIP1,";
			$sql = $sql . "READER_ZIP2,";
			$sql = $sql . "READER_OCCUP,";
			$sql = $sql . "READER_ZIP1,";
	                $sql = $sql . "to_char(READER_BIRTH, 'YYYY') WK_YEAR,";
	                $sql = $sql . "to_char(READER_BIRTH, 'MM') WK_MONTH,";
	                $sql = $sql . "to_char(READER_BIRTH, 'DD') WK_DAY,";
			$sql = $sql . "LOG_KEY,";
			$sql = $sql . "LOG_KEY_FLG ";
			$sql = $sql . " FROM M_MELONPA where READER_ID = ";
			break;

		default:
			break;
	}
	return $sql;
}
?>
