<?php
function mk_data() {

	global $data,$resarray,$reader_id,$reader_pswd,$mail_addr;
	global $mode, $rank_id, $page, $all_num;
	$mae = strftime("%Y.%m.%d ",time() - (14*24*60*60));
	$mark = "";
	
	if ($resarray[13] == "01"){ //����Ͽ�ξ��
		if ($resarray[12] > $mae)	{ $mark .= "<img src=\"../img/shincyaku.gif\">";}	//����
	}else{
		if ($resarray[17] > $mae)	{ $mark .= "<img src=\"../img/shincyaku.gif\">";}	//����
	}
	if ($resarray[13] == "01")	{ $mark .= "<img src=\"../img/kari.gif\">";}			//����Ͽ
	if ($resarray[11] == 1)		{ $mark .= "<img src=\"../img/osusume.gif\">";} 		//��������
	if (trim($resarray[18]) != "")	{ $mark .= "<img src=\"../img/osusumekako.gif\">";}	//��������
	if (trim($resarray[17]) != ""){
		if ( check_Birthday($resarray[17]) == 1){
			$mark .= "<img src=\"../img/birth.gif\">"; 									//������
		}
	}
	if ($resarray[15] == "1")	{ $mark .= "<img src=\"../img/meron.gif\">";}			//���ꥸ�ʥ�
	if ($resarray[16] == "02")	{ $mark .= "<img src=\"../img/imode.gif\">";} 			//������
	if ($resarray[14] == "1") 	{ $mark .= "<img src=\"../img/point.gif\">";}			//��Хå���
    $data .= "<table border=0 cellspacing=0 cellpadding=0 bgcolor=\"#91c741\" width=557>\n"
// ---------- ������
	. "<tr>\n"
	. "<td class=\"text9ptnoColor\">"
	. "<table width=557 border=0 cellspacing=1 cellpadding=1>"
	. "<tr height=20 bgcolor=\"#91c741\">" 
	. "<td colspan=\"6\" height=\"20\"><b><b><font color=\"white\"><b><b><b><font color=\"white\"><b><b><b>"
	. "<a href=\"../melonpa/mag-detail.php?mag_id=".$resarray[0]."\">"
	. "<img src=\"../search/img/syosai.gif\" width=36 height=17 align=\"right\" border=0></a>"
	. "</b></b></b></font></b></b></b></font><b>"
	. "<a href=\"../melonpa/mag-detail.php?mag_id=".$resarray[0]."\">"
	. trim(htmlspecialchars($resarray[1]))."</a><b><b><b></b></b></b>"
	. "<b><b></b></b></b><font color=\"white\"><b><b><b><font color=\"white\"><b><b><b></b></b></b></font></b></b></b></font></b></td>"
	. "</tr>\n";
// ---------- ������
	$data .= "<tr>\n"
	. "<td bgcolor=\"#e2f0c9\" width=80 class=\"text9ptnoColor\">"
	. "<center>"
	. "<font color=\"#336600\" size=2>���ޥ�ID</font>"
	. "</center>"
	. "</td>"
	. "<td class=\"text9ptnoColor\" bgcolor=\"white\" width=\"56\">"
	. "<div align=\"center\"> <font size=2>" .$resarray[0] ."</font></div>"
	. "</td>"
	. "<td bgcolor=\"#e2f0c9\" width=90 class=\"text9ptnoColor\">"
	. "<center>"
	. "<font color=\"#336600\" size=\"2\">�ǽ�ȯ������</font>"
	. "</center>"
	. "</td>"
	. "<td class=\"text9ptnoColor\" bgcolor=\"white\" width=130><div align=\"center\"><font size=2>";
	if ( mbsubstr($resarray[9], 0, 10) == "2000/01/01") { /* �Ʒ�NO24 ɽ�������ѹ� */
		$data .= "̤ȯ��\n";
	} else {
		$data .= $resarray[9]."\n";
	}
	$data .= "</font></div></td>"
	. "<td bgcolor=\"#e2f0c9\" width=70 class=\"text9ptnoColor\">"
	. "<center>"
	. "<font color=\"#336600\" size=2>�ޡ���</font>"
	. "</center>"
	. "</td>"
	. "<td width=130 class=\"text9ptnoColor\" bgcolor=\"white\">"
	. "<div align=\"center\"><font size=2>" .$mark ."</font></div>"
	. "</td>"
	. "</tr>\n";
// ---------- ������
	$data .= "<tr>" 
	. "<td width=80 bgcolor=\"#e2f0c9\" class=\"text9ptnoColor\">"
	. "<center>"
	. "<font color=\"#336600\" size=2>���ƥ���</font><font color=\"#336600\">"
	. "</font>"
	. "</center>"
	. "</td>"
	. "<td class=\"text9ptnoColor\" bgcolor=\"white\" colspan=3>"
	. "<table width=276 border=0 cellspacing=0 cellpadding=0>"
	. "<tr>"
	. "<td><font size=2>".trim($resarray[7])."��".trim($resarray[8]) ."</font></td>"
	. "</tr>"
	. "</table>"
	. "</td>"
	. "<td width=70 bgcolor=\"#e2f0c9\" class=\"text9ptnoColor\">"
	. "<center>"
	. "<font color=\"#336600\" size=2>ȯ�Լ���</font>"
	. "</center>"
	. "</td>"
	. "<td width=130 class=\"text9ptnoColor\" bgcolor=\"white\"><font size=2>".trim(htmlspecialchars($resarray[3]))."</font></td>"
	. "</tr>\n";
// ---------- ������
	$data .= "<tr>\n"
	. "<td colspan=4 class=\"text9ptnoColor\" bgcolor=\"white\">"
	. "<table width=356 border=0 cellspacing=0 cellpadding=0>"
	. "<tr>"
	. "<td>".trim(htmlspecialchars($resarray[2]))."</td>"
	. "</tr>"
	. "</table>"
	. "</td>"
	. "<td bgcolor=\"#e2f0c9\" class=\"text9ptnoColor\" width=70>"
	. "<center>"
	. "<font color=\"#336600\" size=2>��Ͽ</font>"
	. "</center>"
	. "</td>"
	. "<td class=\"text9ptnoColor\" width=130 bgcolor=\"white\">"
	. "<div align=\"left\"> <font color=\"#336600\" size=2>";
if ($mail_addr == "") {
	$data .= "<input type=\"radio\" name=\"read_chk[]\" value=\"on".$resarray[0]."\" onclick=\"check_select()\"> ���ɡ� ";
	$data .= "<input type=\"radio\" name=\"read_chk[]\" value=\"of".$resarray[0]."\" onclick=\"check_select()\"> ����� ";
} else {
	$data .= "<input type=\"checkbox\" name=\"read_chk[]\" value=\"on".$resarray[0]."\" onclick=\"check_select()\"> ���ɡ� ";
	$data .= "<input type=\"checkbox\" name=\"read_chk[]\" value=\"of".$resarray[0]."\" onclick=\"check_select()\"> ����� ";
}
	$data .= "</font></div>"
	. "</td>"
	. "</tr>"
	. "</table></td></tr>\n"
	. "</table><br>\n";
	
}

/* ------------------------------------------------------ */
/* 	������Ƚ��
/* ------------------------------------------------------ */
#--- ������Ƚ�� ---
function check_Birthday($base_date) {
	// $base_date = YYYY-MM-DD

	$flg = 0;
	for ( $i=-7 ; $i<7 ; $i++){
		$tmp_date = strftime("%Y-%m-%d",time() + ($i*24*60*60));
		if ( substr($base_date,5,2) == substr($tmp_date,5,2) &&
			 substr($base_date,8,2) == substr($tmp_date,8,2) &&
			 substr($base_date,0,4) != substr($tmp_date,0,4) ){
			$flg = 1;
			break;
		}
	}
	return $flg;
}
?>
