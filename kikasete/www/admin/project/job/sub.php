<?
//�᡼�����DECODE
function decode_mail_format($code, $def = '����') {
	switch ($code) {
	case 0:
		return '�ƥ�����';
	case 1:
		return 'HTML';
	}
	return $def;
}

//����ּ���DECODE
function decode_job_type($code, $def = '����') {
	switch ($code) {
	case 0:
		return 'ȯ��JOB';
	case 1:
		return '���󥱡��ȥ쥹�ݥ�JOB';
	case 2:
		return '����å��쥹�ݥ�JOB';
	}
	return $def;
}

//�ƥ����̾DECODE
function parent_job_name_decode($code) {
	if ($code) {
		$sql = "SELECT jb_job_name FROM t_job WHERE jb_job_id=$code";
		$result = db_exec($sql);
		$fetch = pg_fetch_object($result, 0);
		echo $fetch->jb_job_name;
	}
}
?>