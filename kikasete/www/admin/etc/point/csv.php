<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:ポイント精算
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/csv.php");
include("$inc/decode.php");
include("$inc/format.php");

// CSV出力準備
//jeon_start seq=ad1
switch ($shouhin){
	case 1:
	$filename = 'visa_' . Date('Ymd') . '.csv';
	break;
	case 2:
	$filename = 'tosyo_' . Date('Ymd') . '.csv';
	break;
	case 3:
	$filename = 'Webmoney_' . Date('Ymd') . '.csv';
	break;
	case 4:
	$filename = 'e-bank_' . Date('Ymd') . '.csv';
	break; 
	default :
	$filename = 'seisan' . Date('Ymd') . '.csv';
	break;
}
//jeon_end seq=ad1

prepare_csv($filename);
// 出力対象レコード取得
$seq_no = get_multi_data($seq_no);
// CSVヘッダ出力
//jeon_start seq=ad1
if($shouhin=='1' || $shouhin=='2'){
	if($shouhin=='1')
	$csv = "申請ポイント,発送人数,枚数,金額";
	else
	$csv = "申請ポイント,発送人数(組数),金額";
	output_csv($csv);
	
	$sql = "SELECT si_point,count(*) AS count,si_point*count(*)/1000 AS ea, si_point*count(*) AS money FROM t_seisan WHERE si_seq_no IN ($seq_no) GROUP BY si_point ORDER BY si_point DESC";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	$sum_cunt=0;
	if($shouhin=='1')
	$sum_ea=0;
	$sum_money=0;
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$csv = '';
		set_csv($csv, $fetch->si_point);
		set_csv($csv, $fetch->count);
		if($shouhin=='1')
		set_csv($csv, $fetch->ea);
		
		set_csv($csv, "\\$fetch->money");
		output_csv($csv);
		$sum_cunt+=$fetch->count;
		if($shouhin=='1')
		$sum_ea+=$fetch->ea;
		$sum_money+=$fetch->money;
	}

	output_csv($csv);
	if($shouhin=='1')
	$csv = ",発送人数合計,発送枚数合計,金額合計";
	else
	$csv = ",発送人数合計,金額合計";
	output_csv($csv);
	if($shouhin=='1')
	$csv = ",$sum_cunt,$sum_ea,\\$sum_money";
	else
	$csv = ",$sum_cunt,\\$sum_money";
	output_csv($csv);

	output_csv($csv);
	output_csv($csv);
}
//$csv = "ポイント,交換商品,氏名,郵便番号,住所,電話番号";
$csv= "モニターID,申請日,氏名(性),氏名(名),メールアドレス,郵便番号,住所,電話番号,金融機関名,支店名,支店番号,口座番号,口座名義,ポイント,交換商品";
//jeon_end seq=ad1
output_csv($csv);



// CSVデータ出力
//jeon_start seq=ad1
//$sql = "SELECT si_point,si_item,si_name1,si_name2,si_zip,si_addr,si_tel FROM t_seisan WHERE si_seq_no IN ($seq_no) ORDER BY si_seq_no";

$sql = "SELECT mn_name1,mn_name2,mn_mail_addr,si_member_id,si_req_date,si_name1,si_name2,si_zip,si_addr,si_tel,si_siten_name,si_siten_no,si_kouza_no,si_kouza_name,si_point,si_item FROM t_seisan JOIN t_monitor ON si_member_id=mn_monitor_id WHERE si_seq_no IN ($seq_no) ORDER BY si_point DESC,si_req_date DESC";
//jeon_end seq=ad1
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$csv = '';
	set_csv($csv, $fetch->si_member_id);
	set_csv($csv, $fetch->si_req_date);
	if($fetch->si_item=='4' || $fetch->si_item=='3'){
	set_csv($csv, $fetch->mn_name1);
	set_csv($csv, $fetch->mn_name2);	
	}else{
	set_csv($csv, $fetch->si_name1);
	set_csv($csv, $fetch->si_name2);
	}
	if($fetch->si_item=='3')
	set_csv($csv, $fetch->mn_mail_addr);
	else 
	set_csv($csv, '-');	
	set_csv($csv, format_zip($fetch->si_zip));
	set_csv($csv, $fetch->si_addr);
	set_csv($csv, $fetch->si_tel);
	if($fetch->si_item=='4')
	set_csv($csv, decode_seisan_item($fetch->si_item));
	else 
	set_csv($csv, '-');	
	if($fetch->si_siten_name!="")
	set_csv($csv, $fetch->si_siten_name);
	else set_csv($csv, '-');
	if($fetch->si_siten_no!="")
	set_csv($csv, $fetch->si_siten_no);
	else set_csv($csv, '-');
	if($fetch->si_kouza_no!="")
	set_csv($csv, $fetch->si_kouza_no);
	else set_csv($csv, '-');
	if($fetch->si_kouza_name!="")
	set_csv($csv, $fetch->si_kouza_name);
	else set_csv($csv, '-');
	if($fetch->si_point!="")
	set_csv($csv, $fetch->si_point);
	else set_csv($csv, '-');
	if($fetch->si_item!="")
	set_csv($csv, decode_seisan_item($fetch->si_item));
	else set_csv($csv, '-');
	

	output_csv($csv);
}

?>