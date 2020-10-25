<?php
function msm_make_sql ($make_type, $key_count) {
	/* MAKE SQL */
	switch ($make_type) {
		case "GET_SQU_M_PUBLISHER":
			$sql = "SELECT * from M_SQU_MGR WHERE table_id = '0101' for update";
			break;
		case "PUT_SQU_M_PUBLISHER":
			$sql = "UPDATE  M_SQU_MGR SET key_count = " . $key_count . " WHERE table_id = '0101'";
			break;
		case "GET_SQU_M_MAGAZINE":
			$sql = "SELECT * from M_SQU_MGR WHERE table_id = '0102' for update";
			break;
		case "PUT_SQU_M_MAGAZINE":
			$sql = "UPDATE M_SQU_MGR SET key_count = " . $key_count . " WHERE table_id = '0102'";
			break;
		case "GET_SQU_T_BODY":
			$sql = "SELECT * from M_SQU_MGR WHERE table_id = '0103' for update";
			break;
		case "PUT_SQU_T_BODY":
			$sql = "UPDATE M_SQU_MGR SET key_count = " . $key_count . " WHERE table_id = '0103'";
			break;
		case "GET_SQU_T_PUB_DELREASON":
			$sql = "SELECT * from M_SQU_MGR WHERE table_id = '0104' for update";
			break;
		case "PUT_SQU_T_PUB_DELREASON":
			$sql = "UPDATE M_SQU_MGR SET key_count = " . $key_count . " WHERE table_id = '0104'";
			break;
		case "GET_SQU_T_MAG_DELREASON":
			$sql = "SELECT * from M_SQU_MGR WHERE table_id = '0105' for update";
			break;
		case "PUT_SQU_T_MAG_DELREASON":
			$sql = "UPDATE M_SQU_MGR SET key_count = " . $key_count . " WHERE table_id = '0105'";
			break;
		case "GET_SQU_T_MAG_STOPREASON":
			$sql = "SELECT * from M_SQU_MGR WHERE table_id = '0106' for update";
			break;
		case "PUT_SQU_T_MAG_STOPREASON":
			$sql = "UPDATE M_SQU_MGR SET key_count = " . $key_count . " WHERE table_id = '0106'";
			break;
		case "GET_SQU_M_MELONPA":
			$sql = "SELECT * from M_SQU_MGR WHERE table_id = '0201' for update";
			break;
		case "GET_SQU_T_WHATS_NEW":
			$sql = "SELECT * from M_SQU_MGR WHERE table_id = '0302' for update";
			break;
		case "GET_SQU_T_RIBA_SEND":
			$sql = "SELECT * from M_SQU_MGR WHERE table_id = '040'2 for update";
			break;
		case "PUT_SQU_M_MELONPA":
			$sql = "UPDATE  M_SQU_MGR SET key_count = " . $key_count . " WHERE table_id = '0201'";
			break;
		case "GET_SQU_T_MAILADDR":
			$sql = "SELECT * from M_SQU_MGR WHERE table_id = '0202' for update";
			break;
		case "PUT_SQU_T_MAILADDR":
			$sql = "UPDATE  M_SQU_MGR SET key_count = " . $key_count . " WHERE table_id = '0202'";
			break;
		case "GET_SQU_T_DELREASON":
			$sql = "SELECT * from M_SQU_MGR WHERE table_id = '0203' for update";
			break;
		case "PUT_SQU_T_DELREASON":
			$sql = "UPDATE  M_SQU_MGR SET key_count = " . $key_count . " WHERE table_id = '0203'";
			break;
		case "GET_SQU_T_RECOMEND":
			$sql = "SELECT * from M_SQU_MGR WHERE table_id = '0204' for update";
			break;
		case "PUT_SQU_T_RECOMEND":
			$sql = "UPDATE  M_SQU_MGR SET key_count = " . $key_count . " WHERE table_id = '0204'";
			break;
		case "GET_SQU_T_ADMIN_MSG":
			$sql = "SELECT * from M_SQU_MGR WHERE table_id = '0301' for update";
			break;
		case "PUT_SQU_T_ADMIN_MSG":
			$sql = "UPDATE  M_SQU_MGR SET key_count = " . $key_count . " WHERE table_id = '0301'";
			break;
		case "GET_SQU_T_CHOICE":
			$sql = "SELECT * from M_SQU_MGR WHERE table_id = '0401' for update";
			break;
		case "PUT_SQU_T_CHOICE":
			$sql = "UPDATE  M_SQU_MGR SET key_count = " . $key_count . " WHERE table_id = '0401'";
			break;
		case "PUT_SQU_T_WHATS_NEW":
			$sql = "UPDATE  M_SQU_MGR SET key_count = " . $key_count . " WHERE table_id = '0302'";
			break;
		case "PUT_SQU_T_RIBA_SEND":
			$sql = "UPDATE  M_SQU_MGR SET key_count = " . $key_count . " WHERE table_id = '0402'";
			break;
		case "PUT_SQU_T_MAG_FOOTER":
			$sql = "UPDATE  M_SQU_MGR SET key_count = " . $key_count . " WHERE table_id = '0403'";
			break;
		case "GET_SQU_T_MAG_FOOTER":
			$sql = "SELECT * from M_SQU_MGR WHERE table_id = '0403' for update";
			break;
		case "PUT_SQU_L_MELONPAI_LOG":
			$sql = "UPDATE  M_SQU_MGR SET key_count = " . $key_count . " WHERE table_id = '0501'";
			break;
		case "GET_SQU_L_MELONPAI_LOG":
			$sql = "SELECT * from M_SQU_MGR WHERE table_id = '0501' for update";
			break;
		case "PUT_SQU_M_BK_CATEGORY":
			$sql = "UPDATE  M_SQU_MGR SET key_count = " . $key_count . " WHERE table_id = '0502'";
			break;
		case "GET_SQU_M_BK_CATEGORY":
			$sql = "SELECT * from M_SQU_MGR WHERE table_id = '0502' for update";
			break;
		case "PUT_SQU_T_BOOKMARK":
			$sql = "UPDATE  M_SQU_MGR SET key_count = " . $key_count . " WHERE table_id = '0503'";
			break;
		case "GET_SQU_T_BOOKMARK":
			$sql = "SELECT * from M_SQU_MGR WHERE table_id = '0503' for update";
			break;
		case "PUT_SQU_M_RECOM_TXT":
			$sql = "UPDATE  M_SQU_MGR SET key_count = " . $key_count . " WHERE table_id = '0504'";
			break;
		case "GET_SQU_M_RECOM_TXT":
			$sql = "SELECT * from M_SQU_MGR WHERE table_id = '0504' for update";
			break;
		case "PUT_SQU_M_MELONPAI":
			$sql = "UPDATE M_SQU_MGR SET key_count = " . $key_count . " WHERE table_id = '0505'";
			break;
		case "GET_SQU_M_MELONPAI":
			$sql = "SELECT * from M_SQU_MGR WHERE table_id = '0505' for update";
			break;
		case "PUT_SQU_T_POINT_DETAIL":
			$sql = "UPDATE M_SQU_MGR SET key_count = " . $key_count . " WHERE table_id = '0601'";
			break;
		case "GET_SQU_T_POINT_DETAIL":
			$sql = "SELECT * from M_SQU_MGR WHERE table_id = '0601' for update";
			break;
		case "PUT_SQU_T_RESERVE_PT_DETAIL":
			$sql = "UPDATE M_SQU_MGR SET key_count = " . $key_count . " WHERE table_id = '0601'";
			break;
		case "GET_SQU_T_RESERVE_PT_DETAIL":
			$sql = "SELECT * from M_SQU_MGR WHERE table_id = '0601' for update";
			break;
		default:
			break;
	}
	return $sql;
}

?>
