<?php
include("../inc/com_db_func.inc");

$input_data = "";

//
// Get Recommend
//

if ($last_recomend_id == "") { $last_recomend_id = 9999999999999999;}
$sql = "select count(*) as COUNT from T_RECOMEND where mag_id = '$mag_id' and open_flg = 1 ";
$sql .= " and recomend_id < $last_recomend_id ";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$zrows = $fetch->count;
} else {
	$error_msg = $cibi_error_msg_8;
	exit();
}

$sql = "select R.recomend_id,R.recom_titl,R.recom_text,R.recom_nic,M.mag_nm from T_RECOMEND R,M_MAGAZINE M ";
$sql .= " where R.mag_id = '$mag_id' and M.mag_id = '$mag_id' and recomend_id < $last_recomend_id ";
$sql .= " and  open_flg = 1 order by recomend_id desc";
$ret = db_exec($sql);
$nrow = pg_numrows($ret);
if (!$ret) {
	$error_msg = $cibi_error_msg_8;
	exit();
}
$count_num = 0;
$reco_data = "";
for ($i = 0; $i < $nrow; $i++) {
	$resarray_reco= pg_fetch_array ($ret, $i);
	$count_num++;
	$reco_data .= "<p><font size=\"2\">\n";
	//$reco_data .= "$resarray_reco[0]\n";
	$reco_data .= "■".$resarray_reco[1]."<br>\n";
	$reco_data .= $resarray_reco[2];
	$reco_data .= "（".$resarray_reco[3]."）\n";;
	$reco_data .= "</font></p>\n";
	$last_rec_id = $resarray_reco[0];
	if ( $count_num > 4 ) {
		break;
	}
}
if ( $zrows > 5 ) {
	$reco_res = "<div align=\"right\">\n";
	$reco_res .= "<a href=\"recommend_all.php?mag_id=".$mag_id."&last_recomend_id=$last_rec_id&nrows=$nrows\">\n";
	$reco_res .= "次の推薦文を読む&gt;&gt;&gt;</a><br>\n";
	$reco_res .= "</div>\n";
}
include("tmpl_html/recommend_all.html");

?>
