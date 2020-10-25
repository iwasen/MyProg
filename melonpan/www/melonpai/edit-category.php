<?php
include ("../inc/com_path.inc");
include ("$compath/err_msg.inc");
include ("$compath/com_func.inc");
include ("$compath/com_db_func.inc");
include ("$compath/sql_mcom.inc");
include ("$compath/com_mm_func.inc");
include ("$incpath/mex_passchk.inc");
include ("../sub/head.inc");

function editcatg_msg($edit_flg) {
	if ($edit_flg) {
		print "<p class=\"text01\">";
		print "カテゴリ名を更新しました。";
		print "</p>";
	}
}

/*======================================================================================*/
/*	(main)										*/
/*======================================================================================*/

$p_titl = "カテゴリ追加";

mex_passchk($conn, $melonpai_id, $melonpai_pswd);
if ($Update) {
	$edit_flg = TRUE;
	$w_msg = "";
	$print_msg = "";
	for ($i = 1; $i<21; $i++) {
		$nm_name =  "catg_nm" . sprintf("%02d", $i);
		$categiry_name = trim($$nm_name);

		if (strlen($categiry_name) > 60) {
			$w_msg .=  "No." . $i . " : 「" . $categiry_name . "」<br>";
		}
	}
	if ($w_msg != "") {
		$print_msg .=  "「カテゴリ名」は、全角３０文字以内で入力してください";
		$print_msg .=  "<br><br>" . $w_msg;
		db_logoff();
		include("err_msg.php");
		exit();
	}
	$update_flg = 0;
	for ($i = 1; $i<21; $i++) {
		$nm_name =  "catg_nm" . sprintf("%02d", $i);
		$$nm_name = trim($$nm_name);
		$categiry_name = $$nm_name;
		$sql = "SELECT * FROM M_BK_CATEGORY WHERE";
		$sql .= " melonpai_id = " . $melonpai_id;
		$sql .= " and category_id = " . $i;
		$sql .= " ORDER BY category_id";
		$result = db_exec($sql);
		if ($recflg = pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);
			$r_categiry_name = $fetch->categiry_name;
		}
		if (strlen($categiry_name) == 0) {
			if ($recflg) {
				$$nm_name = $r_categiry_name;
			}
		} else {
			$update_flg = 1;
			if ($recflg) {
				$sql = "UPDATE M_BK_CATEGORY SET";
				$sql .= " categiry_name = '" . $categiry_name . "',";
				$sql .= " modify_dt = 'now'";
				$sql .= " WHERE ";
				$sql .= " melonpai_id = " . $melonpai_id;
				$sql .= " and category_id = " . $i;
				$result = db_exec($sql);
			} else {
				db_begin_trans();
				if (($catg_id = update_squ_key ("M_BK_CATEGORY")) != "-1") {
					db_commit_trans();
				} else {
					db_rollback();
					err_msg ("PM002", $p_titl);
					exit();
				}
				$sql = "INSERT INTO M_BK_CATEGORY ( ";
				$sql .= " catg_id, melonpai_id, category_id, categiry_name, modify_dt)";
				$sql .= " VALUES ( ";
				$sql .= " $catg_id, $melonpai_id, $i, '$categiry_name', 'now')";
				db_exec($sql);
			}
		}
	}
	if ($update_flg == 1) {
	}
} else {
	$edit_flg = FALSE;
	$sql = "SELECT * FROM M_BK_CATEGORY WHERE";
	$sql .= " melonpai_id = " . $melonpai_id;
	$sql .= " ORDER BY category_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	$lcount = 0;
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$categiry_name = $fetch->categiry_name;
		$lcount++;
		if ($lcount > 20) {
			break;
		}
		$nm_name =  "catg_nm" . sprintf("%02d", $lcount);
		$$nm_name =  $categiry_name;
	}
	$lcount++;
	for ($i=$lcount; $i<21; $i++) {
		$nm_name =  "catg_nm" . sprintf("%02d", $i);
		$$nm_name = "";
	}
}
include("tmpl_html/edit-category.html");
?>
