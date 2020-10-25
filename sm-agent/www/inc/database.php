<?
/******************************************************
' System :�᡼���ۿ������ӥ�
' Content:�ǡ����١�������
'******************************************************/

// �ǡ����١��������ץ�
function db_open() {
	global $gConn, $gDbErrMsg;

	if (!$gConn) {
		if (($host = $_ENV['SMAGENT_DB_HOST']) == '')
			$host = 'localhost';

		if (($dbname = $_ENV['SMAGENT_DB_NAME']) == '')
			$dbname = 'sm-agent';

		$gConn = pg_conxxxt("host=$host port=5432 dbname=$dbname user=postgres");

		$gDbErrMsg = '';
	}

	return $gConn;
}

// SQLʸ�¹�
function db_exec($sql) {
	global $gConn, $gDbErrMsg, $g_sql_debug;

	$result = pg_exec(db_open(), $sql);
	if (!$result) {
		if ($_ENV['SQL_DEBUG'])
			echo '<font color="red">', htmlspecialchars($sql), '</font><br>';
		$gDbErrMsg = pg_errormessage($gConn);
	} elseif ($g_sql_debug)
		echo '<font color="blue">', htmlspecialchars($sql), '</font><br>';

	return $result;
}

// �ȥ�󥶥�����󳫻�
function db_begin_trans() {
	global $trans_count;

	if ($trans_count++ == 0)
		db_exec('begin');
}

// �ȥ�󥶥�����󥳥ߥå�
function db_commit_trans() {
	global $trans_count;

	if (--$trans_count == 0)
		db_exec('commit');
}

// �ȥ�󥶥���������Хå�
function db_rollback() {
	global $trans_count;

	if (--$trans_count == 0)
		db_exec('rollback');
}

// ʸ����ǡ���
function sql_char($data, $null='null') {
	if ($data != '') {
		$data = addslashes($data);
		return "'$data'";
 	} else
		return $null;
}

// ���ͥǡ���
function sql_number($data, $null='null') {
	$data = strval($data);
	if ($data != '')
		return "$data";
	else
		return $null;
}

// ���եǡ���
function sql_date($data, $null='null') {
	if ($data != '' && $data{0} != '/')
		return "'$data'";
	else
		return $null;
}

// ���եǡ����ʥѡ����̡�
function sql_date2($year, $month, $day, $hour=0, $minute=0, $second=0) {
	if ($year != '' && $month != '' && $day != '')
		return "'$year/$month/$day $hour:$minute:$second'";
	else
		return 'null';
}

// BOOL�ǡ���
function sql_bool($bool, $null='null') {
	if ($bool == 't' || $bool == 'f')
		return "'$bool'";
	else
		return $null;
}

// SQL��������
function sql_datetime($check, $year, $month, $day, $hour = 0, $second = 0, $minute = 0) {
	if ($check)
		return "'$year/$month/$day $hour:$second:$minute'";
	else
		return 'null';
}

// ǯ������ʬ�ü���
function get_datepart($part, $date) {
	if (strlen($date) == 10)
		sscanf($date, "%d-%d-%d", &$year, &$month, &$day);
	else
		sscanf($date, "%d-%d-%d %d:%d:%d", &$year, &$month, &$day, &$hour, &$minute, &$second);

	switch ($part) {
	case 'Y':
		return $year;
	case 'M':
		return $month;
	case 'D':
		return $day;
	case 'h':
		return $hour;
	case 'm':
		return $minute;
	case 's':
		return $second;
	}
}

// currval����
function get_currval($seqtbl) {
	$sql = "SELECT currval('$seqtbl')";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_row($result, 0);
		return $fetch[0];
	}
}

// �����ƥ�������
function get_system_info($column = '*') {
	$sql = "SELECT $column FROM m_system WHERE sy_system_id=1";
	$result = db_exec($sql);
	if (pg_numrows($result))
		return pg_fetch_object($result, 0);
}

// ���ǡ����ե��å�
function db_fetch1($sql, $def='') {
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_row($result, 0);
		return $fetch[0];
	}
	return $def;
}

// ľ��Υ��顼��å���������
function db_errormessage() {
	global	$gDbErrMsg;

	return trim($gDbErrMsg);
}

// ��������̾����
function get_seq_name($table, $column) {
	$seq_new = "{$table}_{$column}_seq";

	$table_len = strlen($table);
	$column_len = strlen($column);
	if ($table_len + $column_len > 26) {
		if ($table_len > 13) {
			if ($column_len > 13) {
				$table = substr($table, 0, 13);
				$column = substr($column, 0, 13);
			} else
				$table = substr($table, 0, 26 - $column_len);
		} else
			$column = substr($column, 0, 26 - $table_len);
	}
	$seq_old = "{$table}_{$column}_seq";

	$sql = "SELECT relname FROM pg_class WHERE relname='$seq_new' OR relname='$seq_old'";
	return db_fetch1($sql);
}
?>