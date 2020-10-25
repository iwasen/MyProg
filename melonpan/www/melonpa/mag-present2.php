<?
include("../inc/com_rm_func.inc");
include("../sub/head.inc");
include("inc/database.php");
include("inc/present.inc");

/*-------------------------------------------
	������ɤμ���
---------------------------------------------*/
function get_keyword($conn, $present_id){

$sql = "SELECT "
	. "  PD_keyword1"
	. ", PD_keyword2"
	. ", PD_keyword3"
	. " FROM M_PRESENT_DATA "
	. " WHERE PD_present_id = '$present_id' ";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$keyword1 = $fetch->pd_keyword1;
	$keyword2 = $fetch->pd_keyword2;
	$keyword3 = $fetch->pd_keyword3;
}
return array( $keyword1, $keyword2, $keyword3 );
}
/*-------------------------------------------
	������ɤγ�����
---------------------------------------------*/
function assign_keyword($code, $a_KeyWord ){

	reset ($a_KeyWord);
	for ( $i = 0 ; $i < 3 ; $i++ ) {
		$column = each ($a_KeyWord);
		$keyword = $column['value'];
		if ( keyword_check($code, $keyword) ) {
			return $keyword;
		}
	}
	return "";
}
/*-------------------------------------------
	�ޥ�������ɳ�ǧ(��������)
---------------------------------------------*/
function check_reader($conn, $mail_adr, 
                     $mag_id1, $mag_id2, $mag_id3){
$sql = "SELECT count(*) as cnt FROM T_MAILADDR "
	. " WHERE ( mag_id = '$mag_id1' and mail_addr = '$mail_adr' ) "
	. "   or  ( mag_id = '$mag_id2' and mail_addr = '$mail_adr' ) "
	. "   or  ( mag_id = '$mag_id3' and mail_addr = '$mail_adr' ) ";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$cnt = $fetch->cnt;
}else{
	$cnt=0;
}
return $cnt;
}

/*-------------------------------------------
	�ޥ�������ɳ�ǧ(��¸�ɼ�)
---------------------------------------------*/
function check_reader_kison($conn, $mail_adr, 
                     $mag_id1, $mag_id2, $mag_id3){

	$sql = "select mag_id from T_MAILADDR where MAG_ID = '$mag_id1' and MAIL_ADDR = '$mail_adr'";
	$result = db_exec($sql);
	if (!pg_numrows($result)) { return false; }

	$sql = "select mag_id from T_MAILADDR where MAG_ID = '$mag_id2' and MAIL_ADDR = '$mail_adr'";
	$result = db_exec($sql);
	if (!pg_numrows($result)) { return false; }

	$sql = "select mag_id from T_MAILADDR where MAG_ID = '$mag_id3' and MAIL_ADDR = '$mail_adr'";
	$result = db_exec($sql);
	if (!pg_numrows($result)) { return false; }

/*
	$sql = "select mag_id from T_MAILADDR where MAG_ID = '$mag_id1' and MAIL_ADDR = '$mail_adr'";
	$select = new select_sql($sql);
	if (! $select->fetch()) {
		return false;
	}

	$sql = "select mag_id from T_MAILADDR where MAG_ID = '$mag_id2' and MAIL_ADDR = '$mail_adr'";
	$select = new select_sql($sql);
	if (! $select->fetch()) {
		return false;
	}

	$sql = "select mag_id from T_MAILADDR where MAG_ID = '$mag_id3' and MAIL_ADDR = '$mail_adr'";
	$select = new select_sql($sql);
	if (! $select->fetch()) {
		return false;
	}
*/
	return true;

}
/*=============================================================================

	�ץ쥼��ȱ�����դ���Ͽ

=============================================================================*/
//$conn = db_conxxxt();
$check_ok=false;
$print_msg = "";

/* echo "mail_adr=$mail_adr, oubo1=$oubo1, oubo2=$oubo2, oubo3=$oubo3"; debug */

$oubo1=trim($oubo1);
$oubo2=trim($oubo2);
$oubo3=trim($oubo3);
$mail_adr=trim(strtolower($mail_adr));

/*-----------------------------
	���ϥ����å�	
-------------------------------*/
if (strlen($mail_adr) == 0) {
	$print_msg .= "���᡼�륢�ɥ쥹�����Ϥ���Ƥ��ʤ��褦�Ǥ���<br>";
} else {
	if (strlen($mail_adr) <  6) $print_msg .= "���᡼�륢�ɥ쥹��û������褦�Ǥ���<br>";
	if (strlen($mail_adr) > 50) $print_msg .= "���᡼�륢�ɥ쥹��Ĺ������褦�Ǥ���<br>";
	if (chkmail_addr($mail_adr) == -1) $print_msg .= "���᡼�륢�ɥ쥹�����������Ϥ���Ƥ��ʤ��褦�Ǥ���<br>";
}
if ($hitorigoto=="") $print_msg .= "�������ΤҤȤꤴ�Ȥ����Ϥ���Ƥ��ʤ��褦�Ǥ���<br>";
if ($oubo1=="") $print_msg .= "��������ڣ��ۤ�Ž���դ����Ƥ��ʤ��褦�Ǥ���<br>";
if ($oubo2=="") $print_msg .= "��������ڣ��ۤ�Ž���դ����Ƥ��ʤ��褦�Ǥ���<br>";
if ($oubo3=="") $print_msg .= "��������ڣ��ۤ�Ž���դ����Ƥ��ʤ��褦�Ǥ���<br>";
if ( $print_msg != "" ){
    include("../melonpa/mag-present2.html");
	exit;
}
/*-----------------------------
	������ν�ʣ�����å�
-------------------------------*/
if ($oubo1==$oubo2 || $oubo1==$oubo3 || $oubo2==$oubo3){
	$print_msg .= "���ץ쥼��ȱ��������ʣ���Ƥ���褦�Ǥ���<br>";
    include("../melonpa/mag-present2.html");
	exit;
}
/*-----------------------------
	������β���
-------------------------------*/
if ( !decode_present($oubo1, $dec_1_present_id, $dec_1_mag_id) )
	$print_msg .= "��������ڣ��ۤ�̵���Τ褦�Ǥ���<br>";
if ( !decode_present($oubo2, $dec_2_present_id, $dec_2_mag_id) )
	$print_msg .= "��������ڣ��ۤ�̵���Τ褦�Ǥ���<br>";
if ( !decode_present($oubo3, $dec_3_present_id, $dec_3_mag_id) )
	$print_msg .= "��������ڣ��ۤ�̵���Τ褦�Ǥ���<br>";

if ( $print_msg != "" ){
    include("../melonpa/mag-present2.html");
	exit;
}
/*-----------------------------
	�ץ쥼���ID�γ�ǧ
-------------------------------*/
if ( $present_id != $dec_1_present_id ||
     $present_id != $dec_2_present_id ||
     $present_id != $dec_3_present_id  ) {

	$print_msg .= "������Υץ쥼����ѤǤϤʤ��������������褦�Ǥ���<br>";
    include("../melonpa/mag-present2.html");
	exit;
}

/*-----------------------------
	������ɥ����å�
-------------------------------*/
if ( $keyword_flag == "1" ){
	$array_keyword = get_keyword($conn, $present_id);

	$Keyword1=assign_keyword($oubo1, $array_keyword);
	$Keyword2=assign_keyword($oubo2, $array_keyword);
	$Keyword3=assign_keyword($oubo3, $array_keyword);
	
	if ($Keyword1==$Keyword2 || $Keyword1==$Keyword3 || $Keyword2==$Keyword3){
		$print_msg .= "��������ɤ����ġ�·�äƤ��ʤ��褦�Ǥ���<br>";
		include("../melonpa/mag-present2.html");
		exit;
	}
}

/*-----------------------------
	���ޥ����ɳ�ǧ
-------------------------------*/
if ($present_type == "A"){
	//��������
	if ( check_reader($conn, $mail_adr, $dec_1_mag_id, $dec_2_mag_id, $dec_3_mag_id) != 3 ) {
		$print_msg .= "�����Υ᡼�륢�ɥ쥹�ǤϹ��ɤ���Ƥ��ʤ����ޥ��α����������褦�Ǥ���<br>";
		include("../melonpa/mag-present2.html");
		exit;
	}
}else{
	//��¸����
	if ( !check_reader_kison($conn, $mail_adr, $dec_1_mag_id, $dec_2_mag_id, $dec_3_mag_id) ) {
		$print_msg .= "�����Υ᡼�륢�ɥ쥹�ǤϹ��ɤ���Ƥ��ʤ����ޥ��α����������褦�Ǥ���<br>";
		include("../melonpa/mag-present2.html");
		exit;
	}
}

/*-----------------------------
	��ʣ�������ߥ����å�
	���ջ��ε�ǽ?�֣��ͣ��ץ쥼��ȤˤĤ�����Τߡ�
-------------------------------*/
$sql = "SELECT "
	. "  to_char(PL_date, 'MM') as DATE_m "
	. ", to_char(PL_date, 'DD') as DATE_d "
	. " FROM T_PRESENT_LIST "
	. "WHERE PL_present_id = '$present_id' "
	. "  AND PL_mail_adr   = '$mail_adr'";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$date_m = $fetch->date_m;
	$date_d = $fetch->date_d;
	$print_msg  = "�����Ǥˤ��Υ᡼�륢�ɥ쥹�Ǥ����礤�������Ƥ���褦�Ǥ���<br>";
//	$print_msg  = $mail_adr."�����".$present_name."�ؤΤ�����ϡ�";
//	$print_msg .= round($date_m)."��".round($date_d)."������Ͽ�ѤȤʤäƤ���ޤ���";
	include("../melonpa/mag-present2.html");
	exit;
}
/*-----------------------------
	�������ߥǡ�����Ͽ
-------------------------------*/
$sql  = "insert into T_PRESENT_LIST "
	."       ( PL_present_id, PL_mail_adr, PL_mag_id1, PL_mag_id2, PL_mag_id3, PL_date) "
	."values ('$present_id','$mail_adr','$dec_1_mag_id','$dec_2_mag_id','$dec_3_mag_id', 'now')";

if (!db_exec($sql)) {
	$print_msg = "��ͽ�����̥��顼��ȯ�����ޤ�����";
    include("../melonpa/mag-present2.html");
	exit;
}
/*-----------------------------
	��Ͽ��λ��å�����ɽ��
-------------------------------*/
$check_ok=true;
$print_msg  = $present_name."�Τ����������դ��ޤ����������Ԥ�ȯɽ�ϡ�";
$print_msg .= round(substr($lottery,5,2))."��".round(substr($lottery,8,2))."������";
$print_msg .= "����Ͽ���줿�᡼�륢�ɥ쥹��Ϣ�����Ƥ��������ޤ���<BR>";
$print_msg .= "�ʤ�����ǰ�ʤ����������줿���ؤΥ᡼��ϡ������ꤷ�ޤ���<BR>";
include("../melonpa/mag-present2.html");
?>
