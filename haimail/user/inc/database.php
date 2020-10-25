<?
//=== Oracle Constant ===
define('ORADB_DEFAULT', 0);
define('ORADYN_DEFAULT', 0);
define('ORADYN_READONLY', 4);

//=== DataSource ===
define('ORA_DBNAME', 'haimail');
define('ORA_USER', 'haimail');
define('ORA_PASS', 'do!house');

class DynasetClass {
	var $stmt;
	var $fetch;
	var $result;

	function Open($sql, $opt) {
		global $gConn, $gMode;

		$this->stmt = OCIParse($gConn, $sql);
		if (OCIExecute($this->stmt, $opt | $gMode))
			$this->MoveNext();
	}

	function EOF() {
		return $this->fetch ? false : true;
	}

	function GetValue($col) {
		return $this->result[strtoupper($col)];
	}

	function MoveNext() {
		$this->fetch = OCIFetchInto($this->stmt, $this->result, OCI_ASSOC + OCI_RETURN_LOBS);
	}
}

//=== データベースオープン ===
function OpenDB() {
	global $gConn, $gMode;

	if (!$gConn) {
		putenv('NLS_DATE_FORMAT=YYYY/MM/DD HH24:MI:SS');
		$gConn = OCILogon(ORA_USER, ORA_PASS, ORA_DBNAME);
		$gMode = OCI_COMMIT_ON_SUCCESS;
	}
}

//=== データベースクローズ ===
function CloseDB() {
}

//=== ダイナセット作成 ===
function CreateDynaset($sql, $opt) {

	OpenDB();
	$dynaset = new DynasetClass;
	$dynaset->Open($sql, $opt);

	return $dynaset;
}

//=== SELECT文以外のSQL実行 ===
function ExecuteSQL($sql) {
	global $gConn, $gMode;

	OpenDB();
	$stmt = OCIParse($gConn, $sql);
	return OCIExecute($stmt, $gMode);
}

//=== トランザクション開始 ===
function BeginTrans() {
	global $gMode;

	OpenDB();
	$gMode = OCI_DEFAULT;
}

//=== トランザクションコミット ===
function CommitTrans() {
	global $gConn, $gMode;

	OCICommit($gConn);
	$gMode = OCI_COMMIT_ON_SUCCESS;
}

//=== トランザクションロールバック ===
function Rollback() {
	global $gConn, $gMode;

	OCIRollback($gConn);
	$gMode = OCI_COMMIT_ON_SUCCESS;
}

// 年月日時分秒取得
function get_datepart($part, $date) {
	if (strlen($date) == 10)
		sscanf($date, "%d/%d/%d", &$year, &$month, &$day);
	else
		sscanf($date, "%d/%d/%d %d:%d:%d", &$year, &$month, &$day, &$hour, &$minute, &$second);

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
?>