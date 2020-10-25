<?
/******************************************************
' System :�������ơ�net����
' Content:�ǥ����ɽ���
'******************************************************/

// admin���ơ�����
function decode_admin_status($code, $def='����ʤ�') {
	switch ($code) {
	case 0:
		return 'ͭ��';
	case 9:
		return '̵��';
	}
	return $def;
}

// ��˥�������
function decode_monitor_type($code, $def='����ʤ�') {
	switch ($code) {
	case 1:
		return '��˥���';
	case 2:
		return '�ͥѡ��ȥʡ�';
	}
	return $def;
}

// ��˥�������
function decode_monitor_status($code, $def='����ʤ�') {
	switch ($code) {
	case 0:
		return 'ͭ��';
	case 2:
		return '��ã';
	case 8:
		return '�ƥ���';
	case 9:
		return '���';
	}
	return $def;
}

// �ޡ�����������
function decode_marketer_type($code, $def='����ʤ�') {
	switch ($code) {
	case 1:
		return '�᡼��˥塼����Ͽ';
	case 2:
		return '̵����Ͽ';
	case 3:
		return 'My�ڡ�����Ͽ';
	case 4:
		return '�����������';
	}
	return $def;
}

// �ޡ�����������
function decode_marketer_status($code, $def='����ʤ�') {
	switch ($code) {
	case 0:
		return 'ͭ��';
	case 2:
		return '��ã����';
	case 8:
		return '�ƥ���';
	case 9:
		return '���';
	}
	return $def;
}

// ����
function decode_sex($code, $def='����ʤ�') {
	switch ($code) {
	case 1:
		return '����';
	case 2:
		return '����';
	}
	return $def;
}

// ̤����
function decode_mikikon($code, $def='����ʤ�') {
	switch ($code) {
	case 1:
		return '̤��';
	case 2:
		return '����';
	case 3:
		return '����¾';
	}
	
	return $def;
}

// AND/OR
function decode_andor($code, $def='����ʤ�') {
	switch ($code) {
	case 'A':
		return 'AND';
	case 'O':
		return 'OR';
	}
	return $def;
}

// �ϰ�
function decode_area($code) {
	if ($code != '') {
		$sql = "SELECT ar_area_name FROM m_area WHERE ar_area_cd=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}

// ����
function decode_shokugyou($code, $name='����ʤ�') {
	if ($code != '') {
		$sql = "SELECT sg_shokugyou_name FROM m_shokugyou WHERE sg_shokugyou_cd=$code";
		$name = db_fetch1($sql, $def);
	}
	return $name;
}

// �ȼ�
function decode_gyoushu($code) {
	if ($code != '') {
		$sql = "SELECT gs_gyoushu_name FROM m_gyoushu WHERE gs_gyoushu_cd=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}

// ����
function decode_shokushu($code) {
	if ($code != '') {
		$sql = "SELECT ss_shokushu_name FROM m_shokushu WHERE ss_shokushu_cd=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}

// �����������
function decode_agent($code) {
	if ($code != '') {
		$sql = "SELECT ag_name1 || ' ' || ag_name2 FROM t_agent WHERE ag_agent_id=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}

// ��²³��
function decode_family_rel($code) {
	if ($code != '') {
		$sql = "SELECT fr_name FROM m_family_rel WHERE fr_family_rel_cd=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}

// ��Ͽ����
function decode_regist($code, $def='����') {
	switch ($code) {
	case 1:
		return '̤��Ͽ';
	case 2:
		return '��Ͽ�Ѥ�';
	}
	return $def;
}

// ���󥱡��ȼ���
function decode_enq_type($code, $def='����') {
	switch ($code) {
	case '0':
		return '���󥱡���̵��';
	case '1':
		return '�᡼�륢�󥱡���';
	case '2':
		return '�ף�⥢�󥱡���';
	}
	return $def;
}

// ���󥱡��ȼ��̡�û�̡�
function decode_enq_type2($code, $def='����') {
	switch ($code) {
	case '0':
		return '̵��';
	case '1':
		return '�᡼��';
	case '2':
		return '�ף��';
	}
	return $def;
}
// jeon_start seq=77
function decode_enq_type3($code,$kind, $def='����') {
	if($code=='1'&& $kind=='5')return '�᡼�륢�󥱡��ȡʥե�����ǥ��󥰤����';
	else if($code=='1'&& $kind=='6')return '�᡼�륢�󥱡��ȡ��ܥ��󥱡��ȤΤߡ�';
	else if($code=='2'&& $kind=='5')return 'Web���󥱡��ȡʥե�����ǥ��󥰤����';
	else if($code=='2'&& $kind=='6')return 'Web���󥱡��ȡ��ܥ��󥱡��ȤΤߡ�';
	else return '���󥱡���̵��';	
}

//jeon_end seq=77
// ���󥱡��Ⱦ���
/* ������
function decode_enq_status($code, $def='����') {
    switch ($code) {
    case 0:
        return '������';
    case 1:
        return '������λ';
    case 2:
        return '������';
    case 3:
        return '��̳�ɥ����å���';
    case 4:
        return '��ǧ�Ѥ�';
    case 5:
        return '�»���';
    case 6:
        return '������';
    case 7:
        return '��λ';
    case 8:
        return '�ƿ���';
    case 9:
        return '����Ѥ�';
    }
    return $def;
}
*/
// ������
// edited by kthink - delete me
// ���֥��ơ�����̾���б�
function decode_enq_status($code, $def='����', $sub_status=-1) {
    switch ($code) {
    case 0:
        return '������';
    case 1:
        return '������λ';
    case 2:
        return '������';
    case 3:
        if ( $sub_status == 1 )
            return '��̳�ɳ�ǧ��';
        elseif ( $sub_status == 2 )
            return '�ǡ�����Ǽ��ǧ��';
        elseif ( $sub_status == 3 )
            return '��̳�ɾ�ǧ�Ԥ�';

        return '��̳�ɥ����å���';
    case 4:
        return '��ǧ�Ѥ�';
    case 5:
        return '�»���';
    case 6:
        return '������';
    case 7:
        return '��λ';
    case 8:
        return '�ƿ���';
    case 9:
        return '����Ѥ�';
    }
    return $def;
}

// �򴹾���
function decode_seisan_item($code) {
	switch ($code) {
	case 1:
		return 'VISA���եȷ�';
	case 2:
		return '�޽��';	
	case 3:
		return 'WebMoney';
	//jeon_start seq=ad1	
	case 4:
		return '��ŷ���';
	//jeon_start seq=ad1		
	case 5:
		return '�����';
	}
	return '����';
}

// �Ҷ���̵ͭ
function decode_have_child($code, $def='����ʤ�') {
	switch ($code) {
	case 1:
		return 'ͭ��';
	case 2:
		return '̵��';
	}
	return $def;
}

// �������
function decode_housing_form($code, $def='����ʤ�') {
	switch ($code) {
	case 1:
		return '��ͷ���';
	case 2:
		return '���罻��';
	}
	return $def;
}

// �֤ν�ͭ
function decode_have_car($code, $def='����ʤ�') {
	switch ($code) {
	case 1:
		return '�ȵ��ȼ֤��ͭ���Ƥ���';
	case 2:
		return '�ȵ��Τ߽�ͭ���Ƥ���';
	case 3:
		return '�ȵ��Ͻ�ͭ���Ƥ��ʤ�';
	}
	return $def;
}

// ʣ�����ǥ�����
function multi_decode($decode_func, $codes, $def = '', $separator = '��') {
	foreach (explode(',', $codes) as $code)
		$ary[] = $decode_func($code, $def);

	return join($separator, $ary);
}

// -----------SEQ82 START 2006/06/23 by CHOI-------------------
// �̾�/Not���
function decode_not_cond($code) {
	switch ($code) {
	case 'f':
		return '�̾�';
	case 't':
		return 'Not���';
	}
	return '';
}
// -----------SEQ82 END 2006/06/23 by CHOI----------------------

// AND/OR
function decode_and_or($code) {
	switch ($code) {
	case 'A':
		return 'AND';
	case 'O':
		return 'OR';
	case 'F':
		return '��������';
	}
	return '';
}

// �ۿ����ƥ���
function decode_job_category($code) {
	switch ($code) {
	case 1:
		return '��������';
	case 2:
		return '�Ϥ��᡼��';
	}
	return '';
}

// HTML�᡼��
function decode_html_mail($code) {
	switch ($code) {
	case DBTRUE:
		return '��������';
	case DBFALSE:
		return '�������ʤ�';
	}
	return '';
}

// �Ϥ��᡼������ե饰
function decode_haimail_flag($code) {
	switch ($code) {
	case DBTRUE:
		return '��������';
	case DBFALSE:
		return '�������ʤ�';
	}
	return '';
}

// �۶��Ԥ�̵ͭ
function decode_spouse_flg($code, $def='����ʤ�') {
	switch ($code) {
	case 1:
		return 'ͭ��';
	case 2:
		return '̵��';
	case 3:
		return '����¾';
	}
	return $def;
}

// ���˥��ؤ�̵ͭ
function decode_senior_flg($code, $def='����ʤ�') {
	switch ($code) {
	case 1:
		return 'ͭ��';
	case 2:
		return '̵��';
	}
	return $def;
}

// �Ҷ�������
function decode_child_sex($code, $def='����ʤ�') {
	switch ($code) {
	case 1:
		return '�˻�';
	case 2:
		return '����';
	}
	return $def;
}

// �Ҷ��γ�ǯ������
function decode_child_gakunen($code, $name='����ʤ�') {
	if ($code != '') {
		$sql = "SELECT gk_name FROM m_gakunen WHERE gk_gakunen_cd=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}

// ���䥿���ץǥ�����
function decode_question_type($code) {
	switch ($code) {
	case 1:
		return '�饸���ܥ����ñ�������';
	case 2:
		return '�����å��ܥå�����ʣ��������';
	case 3:
		return '�ե꡼������Ĺʸ��';
	case 4:
		return '�ޥȥꥯ����ñ�������';
	case 5:
		return '�ޥȥꥯ����ʣ��������';
	case 6:
		return '���Ͳ���';
	case 7:
		return '�ץ�������ñ�������';
	case 8:
		return '�ե꡼�����ʰ�ԡ�';
	}
}

// ���䥿���ץǥ����ɡʾ�ά����
function decode_question_type2($code) {
	switch ($code) {
	case 1:
		return 'SA';
	case 2:
		return 'MA';
	case 3:
		return 'FA';
	case 4:
		return 'MatrixSA';
	case 5:
		return 'MatrixMA';
	case 6:
		return 'NA';
	case 7:
		return 'SA';
	case 8:
		return 'FA';
	}
}
?>