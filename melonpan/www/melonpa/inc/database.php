<?php

//'=== �f�[�^�x�[�X�I�[�v�� ===
function db_open() {
	global  $gConn;

	if (!$gConn) {
		if (($host = $_ENV['MELONPAN_DB']) == '')
			$host = 'localhost';
		$gConn = pg_conxxxt("host=$host port=5432 dbname=melonpan user=postgres");
	}

	return $gConn;
}

// SQL�����s
function db_exec($sql) {
	return pg_exec(db_open(), $sql);
}

// �g�����U�N�V�����J�n
function db_begin_trans() {
	global $trans_count;

	if ($trans_count++ == 0)
		db_exec('begin');
}

// �g�����U�N�V�����R�~�b�g
function db_commit_trans() {
	global $trans_count;

	if (--$trans_count == 0)
		db_exec('commit');
}

// �g�����U�N�V�������[���o�b�N
function db_rollback() {
	global $trans_count;

	if (--$trans_count == 0)
		db_exec('rollback');
}
?>
