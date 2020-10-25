<?php

function pai_make_sql ($make_type) {
	/* MAKE SQL */
	switch ($make_type) {
		case "INSERT_T_CHOICE":
			$sql = "INSERT INTO t_choice (
								cho_id,
				                       		reader_id,
						                com_title,
						                com_text,
						                send_mail_addr,
								melonpai_id,
						                modify_dt,
						                cho_flg,
						                cho_dt
 )
						VALUES (
								:CHO_ID,
					                       	:READER_ID,
						                :COM_TITLE,
						                :COM_TEXT,
						                :SEND_MAIL_ADDR,
								:MELONPAI_ID,
						                SYSDATE,
						                :CHO_FLG,
						                SYSDATE
 						)";
			break;
		case "INSERT_T_CHOICE_2":
			$sql = "INSERT INTO t_choice (
								cho_id,
						                com_title,
						                com_text,
						                send_mail_addr,
								melonpai_id,
						                modify_dt,
						                cho_flg,
						                cho_dt
 )
						VALUES (
								:CHO_ID,
						                :COM_TITLE,
						                :COM_TEXT,
						                :SEND_MAIL_ADDR,
								:MELONPAI_ID,
						                SYSDATE,
						                :CHO_FLG,
						                SYSDATE
 						)";
			break;

		case "UPDATE_T_CHOICE":
			$sql = "UPDATE t_choice SET "
					. "reader_id = :READER_ID,"
					. "com_title = :COM_TITLE, "
			                . "com_text = :COM_TEXT, "
			                . "send_mail_addr = :SEND_MAIL_ADDR, "
			                . "modify_dt = SYSDATE, "
			                . "cho_flg = :CHO_FLG, "
			                . "cho_dt = :CHO_DT "
				. " WHERE cho_id = :CHO_ID ";
			break;
		case "DELETE_T_CHOICE":
			$sql = "DELETE t_choice WHERE cho_id = "
						. ":CHO_ID ";
			break;
		default:
			break;
	}
	return $sql;
}
?>
