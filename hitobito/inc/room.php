<?php
/**
 * ひとびと･net 共通ファイル
 *
 * 会議室関連
 *
 *
 * @package
 * @author
 * @version
 */

class room_class {
	protected $room_id;
	protected $page_size;
	protected $list_mode = 'thread';

	// ■コンストラクタ
	public function __construct($room_id, $page_size) {
		// $page_size=0は全件
		$this->room_id = $room_id;
		$this->page_size = $page_size;
	}

	// ■発言数取得
	public function get_page_num() {
		if ($this->page_size == 0)
			return 1;

		$sql = "SELECT COUNT(*) FROM l_room_remark WHERE rmk_room_id=$this->room_id AND rmk_status=1 AND rmk_disp_type=1";
		return (int)((db_fetch1($sql, 0) + $this->page_size - 1) / $this->page_size);
	}

	// ■表示モードセット
	public function set_list_mode($list_mode/* 'thread' or 'time' */) {
		$this->list_mode = $list_mode;
	}

	// ■発言リスト取得
	public function get_list($page_no, $remark_id = null) {
		// list_modeによりスレッド形式または投稿順に行ごとの配列を返す（返値をforeachで回して表示すればよい）
		// リストをクリックした時はJavaScriptでclick_remark(remark_id)を呼び出すので、その関数を作って適当に処理する
		// $page_noは1から
		// $remark_idを指定した場合はその発言を太字で表示する

		$ary = array();

		if ($this->list_mode == 'thread')
			$this->get_thread_list($page_no, $remark_id, $ary);
		else
			$this->get_time_list($page_no, $remark_id, $ary);

		return $ary;
	}

	// ■指定した発言の含まれるページ番号を取得
	public function get_remark_page($remark_id) {
		if ($this->page_size == 0)
			return 1;

		if ($this->list_mode == 'thread')
			$count = $this->get_thread_page($remark_id);
		else
			$count = $this->get_time_page($remark_id);

		return (int)($count / $this->page_size) + 1;
	}

	// ■指定した発言内容を取得する
	public function get_contents($remark_id) {
		$sql = "SELECT rmk_content FROM l_room_remark WHERE rmk_remark_id=$remark_id";
		return db_fetch1($sql);
	}

	// ■スレッド表示取得
	protected function get_thread_list($page_no, $remark_id, &$ary, $parent_remark_id = 0, $count = 0, $depth = 0) {
		if ($depth > 100)
			return $count;

		$offset = ($page_no - 1) * $this->page_size;

		$sql = sprintf(
					"SELECT rmk_remark_id, rmk_seq_no, rmk_date, rmk_subject, rmk_member_id, rmk_child_num, rmk_nickname"
				. " FROM l_room_remark"
				. " WHERE rmk_room_id=%s AND rmk_parent_remark_id=%s AND rmk_status=1 AND rmk_disp_type=1"
				. " ORDER BY rmk_remark_id",
				sql_number($this->room_id),
				sql_number($parent_remark_id));
		$result = db_exec($sql);
		$nrow = pg_num_rows($result);
		for ($i = 0; $i < $nrow; $i++) {
			if ($this->page_size != 0 && $count >= $offset + $this->page_size)
				break;

			$fetch = pg_fetch_object($result, $i);

			if ($count >= $offset) {
				$s =  str_repeat('　', $depth) . ($depth ? '└' : '') . "<a href='javascript:click_remark($fetch->rmk_remark_id)'>" . '[' . $fetch->rmk_seq_no . '] ' . htmlspecialchars($fetch->rmk_subject) . '　' . htmlspecialchars($fetch->rmk_nickname) . '　' . format_datetime($fetch->rmk_date) . '</a>';

				if ($fetch->rmk_remark_id == $remark_id)
					$s = "<b>$s</b>";

				$ary[] = $s;
			}

			$count++;

			if ($fetch->rmk_child_num) {
				if ($fetch->rmk_child_num + $count < $offset)
					$count += $fetch->rmk_child_num;
				else
					$count = $this->get_thread_list($page_no, $remark_id, $ary, $fetch->rmk_remark_id, $count, $depth + 1);
			}
		}

		return $count;
	}

	// ■投稿順表示取得
	protected function get_time_list($page_no, $remark_id, &$ary) {
		$limit = $this->page_size == 0 ? '' : "LIMIT $this->page_size";
		$offset = 'OFFSET ' . (($page_no - 1) * $this->page_size);

		$sql = "SELECT rmk_remark_id, rmk_seq_no, rmk_subject, rmk_date, rmk_room_id, rmk_member_id, rmk_nickname"
				. " FROM l_room_remark"
				. " WHERE rmk_room_id=$this->room_id AND rmk_status=1 AND rmk_disp_type=1"
				. " ORDER BY rmk_seq_no"
				. " $limit $offset";
		$result = db_exec($sql);
		$nrow = pg_numrows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);

			$s = "<a href='javascript:click_remark($fetch->rmk_remark_id)'>[$fetch->rmk_seq_no] " . htmlspecialchars($fetch->rmk_subject) . '　' . htmlspecialchars($fetch->rmk_nickname) . '　' . format_datetime($fetch->rmk_date) . '</a>';

			if ($fetch->rmk_remark_id == $remark_id)
				$s = "<b>$s</b>";

			$ary[] = $s;
		}
	}

	// ■スレッド表示において指定した発言の含まれるページ番号を取得
	protected function get_thread_page($remark_id, $parent_remark_id = 0, $count = 0, $depth = 0, &$find = false) {
		if ($depth > 100)
			return $count;

		$sql = sprintf(
					"SELECT rmk_remark_id,rmk_child_num"
				. " FROM l_room_remark"
				. " WHERE rmk_room_id=%s AND rmk_parent_remark_id=%s AND rmk_status=1 AND rmk_disp_type=1"
				. " ORDER BY rmk_remark_id",
				sql_number($this->room_id),
				sql_number($parent_remark_id));
		$result = db_exec($sql);
		$nrow = pg_num_rows($result);
		for ($i = 0; $i < $nrow && $find == false; $i++) {
			$fetch = pg_fetch_object($result, $i);

			if ($fetch->rmk_remark_id == $remark_id) {
				$find = true;
				break;
			}

			$count++;

			if ($fetch->rmk_child_num)
				$count = $this->get_thread_page($remark_id, $fetch->rmk_remark_id, $count, $depth + 1, $find);
		}

		return $count;
	}

	// ■投稿順表示において指定した発言の含まれるページ番号を取得
	protected function get_time_page($remark_id) {
		$count = 0;

		$sql = "SELECT rmk_remark_id"
				. " FROM l_room_remark"
				. " WHERE rmk_room_id=$this->room_id AND rmk_status=1 AND rmk_disp_type=1"
				. " ORDER BY rmk_seq_no";
		$result = db_exec($sql);
		$nrow = pg_numrows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);

			if ($fetch->rmk_remark_id == $remark_id) {
				$count = $i;
				break;
			}
		}

		return $count;
	}
}
?>