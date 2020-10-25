<?php

function mcom_make_sql ($make_type) {
	/* MAKE SQL */
	switch ($make_type) {
	case "SELECT_M_MAG_TYPE":
		$sql = "SELECT * from M_MAG_TYPE";
		break;
	case "SELECT_M_MAG_TYPE_ID":
		$sql = "SELECT * from M_MAG_TYPE WHERE type_id = :TYPE_ID";
		break;
	case "SELECT_M_MAG_AREA":
		$sql = "SELECT * from M_MAG_AREA";
		break;
	case "SELECT_M_MAG_AREA_ID":
		$sql = "SELECT * from M_MAG_AREA WHERE area_id = :AREA_ID";
		break;
	case "SELECT_M_MAG_OCCUP":
		$sql = "SELECT * from M_MAG_OCCUP";
		break;
	case "SELECT_M_MAG_OCCUP_ID":
		$sql = "SELECT * from M_MAG_OCCUP WHERE occup_id = :OCCUP_ID";
		break;
	case "SELECT_M_MAG_BG":
		$sql = "SELECT * from M_MAG_BG";
		break;
	case "SELECT_M_MAG_BG_ID":
		$sql = "SELECT * from M_MAG_BG WHERE bg_id = :BG_ID";
		break;
	case "SELECT_M_MAG_CATG":
		$sql = "SELECT * from M_MAG_CATG";
		break;
	case "SELECT_M_MAG_CATG_ID":
		$sql = "SELECT * from M_MAG_CATG WHERE catg_id = :CATG_ID";
		break;
	default:
		break;
	}
	return $sql;
}
?>
