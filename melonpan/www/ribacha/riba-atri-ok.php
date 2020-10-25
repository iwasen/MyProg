<?php
include ("inc/pub_passchk.inc");
include ("../inc/com_path.inc");
include ("$compath/err_msg.inc");
include ("$compath/com_db_func.inc");

/****************************************************************
 Main
****************************************************************/
if ($ara == "" && $occup == "" && $age == "" && $sex == "" && $mrg=="") {
	$p_titl = "りばっチャ属性設定エラー";
	$error_msg = "どれかひとつは選択してください";
	include("tmpl_html/atri-error.html");
	exit();
}


$ocp_all = "";
if (count($occup) != 0) {
	while (list($key,$val) = each($occup)) {
		$ocp_all2 .= $val."::";
	}
	if (strlen($ocp_all2) > 2) {
		if (ereg("::",$ocp_all2)) {
		$len = strlen($ocp_all2) - 2;
			$ocp_all = substr($ocp_all2,0,$len);
		}
	}
}

$age_all = "";
if (count($age) != 0) {
	while (list($key,$val) = each($age)) {
		$age_all2 .= $val."::";
	}
	if (strlen($age_all2) > 2) {
		if (ereg("::",$age_all2)) {
		$len = strlen($age_all2) - 2;
			$age_all = substr($age_all2,0,$len);
		}
	}
}

$sex_all = "";
if (count($sex) != 0) {
	while (list($key,$val) = each($sex)) {
		$sex_all2 .= $val."::";
	}
	if (strlen($sex_all2) > 2) {
		if (ereg("::",$sex_all2)) {
		$len = strlen($sex_all2) - 2;
			$sex_all = substr($sex_all2,0,$len);
		}
	}
}

$ara_all = "";
if (count($ara) != 0) {
	while (list($key,$val) = each($ara)) {
		$ara_all2 .= $val."::";
	}
	if (strlen($ara_all2) > 2) {
		if (ereg("::",$ara_all2)) {
		$len = strlen($ara_all2) - 2;
			$ara_all = substr($ara_all2,0,$len);
		}
	}
}


$mrg_all = "";
if (count($mrg) != 0) {
	while (list($key,$val) = each($mrg)) {
		$mrg_all2 .= $val."::";
	}
	if (strlen($mrg_all2) > 2) {
		if (ereg("::",$mrg_all2)) {
		$len = strlen($mrg_all2) - 2;
			$mrg_all = substr($mrg_all2,0,$len);
		}
	}
}
$conn = db_conxxxt();
$sql = "select count(*) As COUNT from m_ribaoptin where mag_id = '$mag_id'";
$stmt = OCIParse($conn, $sql);
OCIDefinebyname($stmt, "COUNT", &$count);
OCIExecute($stmt, OCI_DEFAULT);
OCIFetch($stmt);
OCIFreeStatement($stmt);

if ($count == 0) {
	$sql2 = "insert into m_ribaoptin values(opt_id_seq.NEXTVAL ,'$mag_id','$age_all','$sex_all','$mrg_all','$ocp_all','$ara_all','$ex',sysdate)";
} else {
	$sql2 = "update m_ribaoptin set ";
	$sql2 .= "age='$age_all',";
	$sql2 .= "sex='$sex_all',";
	$sql2 .= "mrg='$mrg_all',";
	$sql2 .= "ocp='$ocp_all',";
	$sql2 .= "ara='$ara_all',";
	$sql2 .= "ex='$ex',";
	$sql2 .= "modify_dt=SYSDATE where mag_id = '$mag_id'";
}
$stmt = OCIParse($conn, $sql2);
OCIExecute($stmt, OCI_DEFAULT);
	if (! $stmt) {
		ocifreestatement($stmt);
		$error_msg = $cibi_error_msg_6;
		db_logoff();
		exit();
	}

OCICommit($conn);
db_logoff();
if ($publisher_id == 0) {
	include("mag-kanryou.html");
} else {
	include("mag-newkanryou.html");
}

?>
