<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:我が家と似た家族グラフデータ生成
'******************************************************/

// 順位表示
function disp_rank($rank, $num, $co2) {

	if ($co2) {
		// 王冠マーク表示
		if ($num != 0 && ($rank / $num <= 0.2) || $rank == 1)
			echo '<img src="img/graph/crown.gif" width=32 height=21>';

		echo "${rank}位／${num}人中";
	} else
		echo '----';
}

// 会員番号取得
$seq_no = $_SESSION['ss_seq_no'];

// 最新のデータの年月取得
$sql = "SELECT max(cr_ym) FROM t_co2_rank WHERE cr_seq_no=$seq_no";
$date = db_fetch1($sql);
if (!$date)
	$date = '2002-10-01';
$this_month = (int)get_datepart('M', $date);

// 総合順位有効条件
$rank_cond = "(bd_auto_commit=3 AND bd_el_use>0 AND bd_wt_use>0 AND (bd_gs_use>0 OR mb_gas_kind_cd=3) AND NOT (COALESCE(mb_ol_flag,0)=1 AND COALESCE(bd_ol_inp_cd,0)=2))";

// 総合順位決定の項目
$rank_co2 = "cr_ele_co2+cr_gas_co2+cr_oil_co2+cr_wtr_co2";

// 本人のCO2排出量取得
$sql = "SELECT cr_ele_co2,cr_gas_co2,cr_oil_co2,cr_wtr_co2,cr_gso_co2,cr_dst_co2,$rank_co2 AS ttl_co2,$rank_cond AS rank_valid"
		. " FROM t_co2_rank"
		. " JOIN t_base_data ON cr_seq_no=bd_mb_seq_no AND cr_ym=bd_month"
		. " JOIN t_member ON mb_seq_no=bd_mb_seq_no"
		. " WHERE cr_seq_no=$seq_no AND cr_ym='$date'";
$result = db_exec($sql);
if (pg_numrows($result))
	$fetch = pg_fetch_object($result, 0);
$ele_co2 = (int)$fetch->cr_ele_co2;
$gas_co2 = (int)$fetch->cr_gas_co2;
$oil_co2 = (int)$fetch->cr_oil_co2;
$wtr_co2 = (int)$fetch->cr_wtr_co2;
$gso_co2 = (int)$fetch->cr_gso_co2;
$dst_co2 = (int)$fetch->cr_dst_co2;
$ttl_co2 = (int)$fetch->ttl_co2;
$rank_valid = $fetch->rank_valid;

// 地域コードと家族カテゴリ取得
$sql = "SELECT ar_area2_cd,mb_family_ctg,fc_text"
		. " FROM t_member"
		. " JOIN m_area ON mb_area_cd=ar_area_cd"
		. " JOIN m_family_ctg ON mb_family_ctg=fc_family_ctg_cd"
		. " WHERE mb_seq_no=$seq_no";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$area2_cd = $fetch->ar_area2_cd;
	$family_ctg = $fetch->mb_family_ctg;
	$famiry_text = $fetch->fc_text;
}

// 共通のSELECT句を用意
$select = "count(*) AS ttl_num,sum(CASE WHEN $rank_co2<$ttl_co2 THEN 1 ELSE 0 END) AS ttl_rank"
		. " ,sum(1) AS ele_num,sum(CASE WHEN $rank_cond AND cr_ele_co2<$ele_co2 THEN 1 ELSE 0 END) AS ele_rank"
		. " ,sum(1) AS gas_num,sum(CASE WHEN $rank_cond AND cr_gas_co2<$gas_co2 THEN 1 ELSE 0 END) AS gas_rank"
		. " ,sum(1) AS wtr_num,sum(CASE WHEN $rank_cond AND cr_wtr_co2<$wtr_co2 THEN 1 ELSE 0 END) AS wtr_rank"
		. " ,sum(CASE WHEN bd_ol_use>0 THEN 1 ELSE 0 END) AS oil_num,sum(CASE WHEN bd_ol_use>0 AND cr_oil_co2<$oil_co2 THEN 1 ELSE 0 END) AS oil_rank"
		. " ,sum(CASE WHEN bd_gs_use>0 THEN 1 ELSE 0 END) AS gso_num,sum(CASE WHEN bd_gs_use>0 AND cr_gso_co2<$gso_co2 THEN 1 ELSE 0 END) AS gso_rank"
		. " ,sum(CASE WHEN bd_gm_use>0 THEN 1 ELSE 0 END) AS dst_num,sum(CASE WHEN bd_gm_use>0 AND cr_dst_co2<$dst_co2 THEN 1 ELSE 0 END) AS dst_rank";

// 全体総数と順位取得
$sql = "SELECT $select,sum(CASE WHEN $rank_cond THEN $rank_co2 ELSE 0 END) AS sum_co2,max(CASE WHEN $rank_cond THEN $rank_co2 ELSE 0 END) AS max_co2"
		. " FROM t_co2_rank"
		. " JOIN t_base_data ON cr_seq_no=bd_mb_seq_no AND cr_ym=bd_month"
		. " JOIN t_member ON cr_seq_no=mb_seq_no"
		. " WHERE cr_ym='$date' AND $rank_cond";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$total_ttl_num = $fetch->ttl_num;
	$total_ttl_rank = $fetch->ttl_rank + 1;
	$total_ele_num = $fetch->ele_num;
	$total_ele_rank = $fetch->ele_rank + 1;
	$total_gas_num = $fetch->gas_num;
	$total_gas_rank = $fetch->gas_rank + 1;
	$total_oil_num = $fetch->oil_num;
	$total_oil_rank = $fetch->oil_rank + 1;
	$total_wtr_num = $fetch->wtr_num;
	$total_wtr_rank = $fetch->wtr_rank + 1;
	$total_gso_num = $fetch->gso_num;
	$total_gso_rank = $fetch->gso_rank + 1;
	$total_dst_num = $fetch->dst_num;
	$total_dst_rank = $fetch->dst_rank + 1;
	$max_co2 = max($fetch->max_co2, 1);
	if ($total_ttl_num)
		$ave_co2 = $fetch->sum_co2 / $total_ttl_num;
}

// 地域総数と順位取得
$sql = "SELECT $select"
		. " FROM t_co2_rank"
		. " JOIN t_base_data ON cr_seq_no=bd_mb_seq_no AND cr_ym=bd_month"
		. " JOIN t_member ON cr_seq_no=mb_seq_no"
		. " JOIN m_area ON mb_area_cd=ar_area_cd"
		. " WHERE cr_ym='$date' AND $rank_cond AND ar_area2_cd=$area2_cd";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$local_ttl_num = $fetch->ttl_num;
	$local_ttl_rank = $fetch->ttl_rank + 1;
	$local_ele_num = $fetch->ele_num;
	$local_ele_rank = $fetch->ele_rank + 1;
	$local_gas_num = $fetch->gas_num;
	$local_gas_rank = $fetch->gas_rank + 1;
	$local_oil_num = $fetch->oil_num;
	$local_oil_rank = $fetch->oil_rank + 1;
	$local_wtr_num = $fetch->wtr_num;
	$local_wtr_rank = $fetch->wtr_rank + 1;
	$local_gso_num = $fetch->gso_num;
	$local_gso_rank = $fetch->gso_rank + 1;
	$local_dst_num = $fetch->dst_num;
	$local_dst_rank = $fetch->dst_rank + 1;
}

// カテゴリ総数と順位取得
$sql = "SELECT $select"
		. " FROM t_co2_rank"
		. " JOIN t_base_data ON cr_seq_no=bd_mb_seq_no AND cr_ym=bd_month"
		. " JOIN t_member ON cr_seq_no=mb_seq_no"
		. " WHERE cr_ym='$date' AND $rank_cond AND mb_family_ctg=$family_ctg";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$categ_ttl_num = $fetch->ttl_num;
	$categ_ttl_rank = $fetch->ttl_rank + 1;
	$categ_ele_num = $fetch->ele_num;
	$categ_ele_rank = $fetch->ele_rank + 1;
	$categ_gas_num = $fetch->gas_num;
	$categ_gas_rank = $fetch->gas_rank + 1;
	$categ_oil_num = $fetch->oil_num;
	$categ_oil_rank = $fetch->oil_rank + 1;
	$categ_wtr_num = $fetch->wtr_num;
	$categ_wtr_rank = $fetch->wtr_rank + 1;
	$categ_gso_num = $fetch->gso_num;
	$categ_gso_rank = $fetch->gso_rank + 1;
	$categ_dst_num = $fetch->dst_num;
	$categ_dst_rank = $fetch->dst_rank + 1;
}

// 平均値と棒グラフための全員のCO2取得
$sql = "SELECT $rank_co2 AS co2"
		. " FROM t_co2_rank"
		. " JOIN t_base_data ON cr_seq_no=bd_mb_seq_no AND cr_ym=bd_month"
		. " JOIN t_member ON cr_seq_no=mb_seq_no"
		. " WHERE cr_ym='$date' AND $rank_cond"
		. " ORDER BY co2";
$result = db_exec($sql);
$nrow = pg_numrows($result);
$ave_rank = 1;
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_row($result, $i);
	$co2_ary[$i] = $fetch[0];
	if ($fetch[0] < $ave_co2)
		$ave_rank++;
}

// 上限を１桁に切り上げ
$n = pow(10, (int)log10($max_co2));
$max_co2 = ((int)($max_co2 / $n) + 1) * $n;

// グラフのバーの高さ取得
for ($i = 0; $i < 50; $i++)
	$bar_ary[$i] = (int)($co2_ary[(int)($i * $nrow / 50)] * 100 / $max_co2);

// 総合順位が無効の処理
if ($rank_valid != 't') {
	$total_ttl_rank = '---';
	$local_ttl_rank = '---';
	$categ_ttl_rank = '---';
	$ele_co2 = 0;
	$gas_co2 = 0;
	$wtr_co2 = 0;
	$oil_co2 = 0;
	$gso_co2 = 0;
	$dst_co2 = 0;
}
?>