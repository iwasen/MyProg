<?php
include ("../inc/com_path.inc");
include ("$compath/err_msg.inc");
include ("$compath/com_func.inc");
include ("$compath/com_db_func.inc");
include ("$compath/sql_mcom.inc");
include ("$compath/com_mm_func.inc");
include ("$incpath/mex_passchk.inc");
include ("../sub/head.inc");

/****************************************************************
 Main
****************************************************************/
$p_titl = "ヘッダーフッター追加";

mex_passchk($conn, $melonpai_id, $melonpai_pswd);

#job_flgチェック
if ($job_flg == "") {
//	$conn = db_conxxxt();
	$sql = "select header_txt,footer_txt from m_recom_txt where melonpai_id = $melonpai_id";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$header = $fetch->header_txt;
		$footer = $fetch->footer_txt;
	}
	include("tmpl_html/header_footer.html");
	exit();
} elseif ($job_flg == "add") {
	if (strlen($header) > 512) {
		$print_msg = "ヘッダーの長さは2500文字以内で書いてください";
		include("tmpl_html/header_footer-ok.html");
		exit();
	}
	if (strlen($footer) > 512) {
		$print_msg = "フッターの長さは250文字以内で書いてください";
		include("tmpl_html/header_footer-ok.html");
		exit();
	}
	$sql = "select * from m_recom_txt where melonpai_id = $melonpai_id ";
	$result = db_exec($sql);
	$ncols = pg_numrows($result);
	if ($ncols == 0 ) {
		if (($recom_txt_id = update_squ_key ("M_RECOM_TXT")) != "-1") {
			$sql = "insert into m_recom_txt values($recom_txt_id, $melonpai_id,'$header','$footer' ,'now')";
		}
	} else {
		$sql = "update m_recom_txt set header_txt = '$header',footer_txt = '$footer' ";
		$sql .= " where melonpai_id = $melonpai_id";
	}
	db_exec($sql);
	$print_msg = "みつくろい用ヘッダーとフッターを設定しました";
	include("tmpl_html/header_footer-ok.html");
}

?>