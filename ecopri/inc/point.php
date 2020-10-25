<?
/******************************************************
' System :Eco-footprint 共通
' Content:ポイント処理
'******************************************************/

// ポイント種別
define('PT_MONTHLY', 1);		// 毎月のランクによるポイント

// ポイントの付与
function add_point($point_type, $point, $seq_no = 0) {
	// ポイントが0なら何もしない
	if ($point == 0)
		return;

	// 会員番号の指定がなければログインIDとする
	if ($seq_no == 0)
		$seq_no = $_SESSION['ss_seq_no'];

	db_begin_trans();

	// ポイント履歴に追加
	$sql = "INSERT INTO l_point (pl_seq_no,pl_point_type,pl_point) VALUES ($seq_no,$point_type,$point)";
	db_exec($sql);

	// ポイント残高に反映
	$sql = "SELECT pt_seq_no FROM t_point WHERE pt_seq_no=$seq_no";
	$result = db_exec($sql);
	if (pg_numrows($result))
		$sql = "UPDATE t_point SET pt_point=pt_point+$point WHERE pt_seq_no=$seq_no";
	else
		$sql = "INSERT INTO t_point (pt_seq_no,pt_point) VALUES ($seq_no,$point)";
	db_exec($sql);

	db_commit_trans();
}

// ポイント残高取得
function get_point($seq_no = 0) {
	// 会員番号の指定がなければログインIDとする
	if ($seq_no == 0)
		$seq_no = $_SESSION['ss_seq_no'];

	// ポイント残高テーブルから取得
	$sql = "SELECT pt_point FROM t_point WHERE pt_seq_no=$seq_no";
	$point = db_fetch1($sql);
	if (!$point)
		$point = 0;

	return $point;
}
?>