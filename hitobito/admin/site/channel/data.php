<?php
/**
 * ひとびと･net 事務局ページ
 *
 * チャネルマスタ処理クラス
 *
 *
 * @package
 * @author
 * @version
 */

class channel_data_class extends data_class {
	public $channel_id;
	public $status = 1;
	public $channel_name;
	public $order;
	public $banner_url = 'http://';
	public $banner_link_url = 'http://';
	public $subchannel = array();
	public $subchannel_id;
	public $kcom_category_id;

	// ■コンストラクタ
	public function __construct() {
		parent::__construct();

		$sql = sprintf("SELECT MAX(chn_order) FROM m_channel");
		$this->order = (int)(db_fetch1($sql, 0) / 10 + 1) * 10;
	}

	// ■DBからデータ読み込み
	public function read_db($channel_id) {
		$sql = sprintf("SELECT * FROM m_channel WHERE chn_channel_id = %s", sql_number($channel_id));
		$fetch = $this->fetch_db($sql);

		// チャネル情報読み込み
		$this->channel_id = $fetch->chn_channel_id;
		$this->status = $fetch->chn_status;
		$this->channel_name = $fetch->chn_channel_name;
		$this->order = $fetch->chn_order;
		$this->banner_url = $fetch->chn_banner_url;
		$this->banner_link_url = $fetch->chn_banner_link_url;

		// サブチャネル情報読み込み
		$sql = sprintf("SELECT * FROM m_sub_channel WHERE sch_channel_id = %s ORDER BY sch_order", sql_number($channel_id));
		$result = db_exec($sql);
		$nrow = pg_numrows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);
			$this->subchannel[$fetch->sch_subchannel_id] = $fetch->sch_subchannel_name;
		}
	}

	// ■フォームからデータ読み込み
	public function read_form() {
		$this->channel_id = $_POST['channel_id'];
		$this->status = $_POST['status'];
		$this->channel_name = $_POST['channel_name'];
		$this->order = $_POST['order'];
		$this->banner_url = $_POST['banner_url'];
		$this->banner_link_url = $_POST['banner_link_url'];
		$this->subchannel_id = $_POST['subchannel_id'];
		$this->kcom_category_id = $_POST['kcom_category_id'];
	}

	// ■DBに追加
	public function insert_db() {
		db_begin_trans();

		$sql = "SELECT (COALESCE(MAX(chn_channel_id), 0) + 1) FROM m_channel";
		$this->channel_id = db_fetch1($sql);

		$rec = $this->set_rec();
		$rec['chn_channel_id'] = sql_number($this->channel_id);
		db_insert('m_channel', $rec);

		$this->write_subchannel();

		$this->write_kcom_category();

		db_commit_trans();
	}

	// ■DBを更新
	public function update_db() {
		db_begin_trans();

		$rec = $this->set_rec();
		db_update('m_channel', $rec, "chn_channel_id=$this->channel_id");

		$this->write_subchannel();

		$this->write_kcom_category();

		db_commit_trans();
	}

	protected function set_rec() {
		$rec['chn_status'] = sql_number($this->status);
		$rec['chn_channel_name'] = sql_char($this->channel_name);
		$rec['chn_order'] = sql_char($this->order);
		$rec['chn_banner_url'] = sql_char($this->banner_url);
		$rec['chn_banner_link_url'] = sql_char($this->banner_link_url);

		return $rec;
	}

	// ■サブチャネルを書き込み
	protected function write_subchannel() {
		db_delete('m_sub_channel', "sch_channel_id=$this->channel_id");

		$order = 10;

		$ary = explode("\n", $this->subchannel_id);

		foreach ($ary as $tmp) {
			if ($tmp != '') {
				list($subchannel_id, $subchannel_name) = explode("\t", $tmp);

				if ($subchannel_id)
					$this->insert_subchennel($subchannel_id, $subchannel_name, $order);
			}
		}

		foreach ($ary as $tmp) {
			if ($tmp != '') {
				list($subchannel_id, $subchannel_name) = explode("\t", $tmp);

				if (!$subchannel_id)
					$this->insert_subchennel("(SELECT (COALESCE(MAX(sch_subchannel_id), 0) + 1) FROM m_sub_channel)", $subchannel_name, $order);
			}
		}
	}

	// ■サブチャネル追加
	protected function insert_subchennel($subchannel_id, $subchannel_name, &$order) {
		$rec['sch_subchannel_id'] = $subchannel_id;
		$rec['sch_channel_id'] = sql_number($this->channel_id);
		$rec['sch_status'] = sql_number(1);
		$rec['sch_subchannel_name'] = sql_char($subchannel_name);
		$rec['sch_order'] = sql_number($order);
		db_insert('m_sub_channel', $rec);

		$order += 10;
	}

	// ■クチコミWEBカテゴリを更新
	protected function write_kcom_category() {
		db_delete('m_channel_kcom', "chk_channel_id=$this->channel_id");

		$ary = explode("\t", $this->kcom_category_id);
		foreach ($ary as $tmp) {
			if ($tmp != '') {
				$category_id = explode(':', $tmp);
				$rec['chk_channel_id'] = sql_number($this->channel_id);
				$rec['chk_l1'] = sql_number($category_id[0]);
				$rec['chk_l2'] = sql_number($category_id[1]);
				$rec['chk_l3'] = sql_number($category_id[2]);
				$rec['chk_l4'] = sql_number($category_id[3]);
				$rec['chk_l5'] = sql_number($category_id[4]);
				db_insert('m_channel_kcom', $rec);
			}
		}
	}

	// ■DBから削除
	public function delete_db() {
		db_begin_trans();

		db_delete('m_channel', "chn_channel_id=$this->channel_id");
		db_delete('m_sub_channel', "sch_channel_id=$this->channel_id");
		db_delete('m_channel_kcom', "chk_channel_id=$this->channel_id");

		db_commit_trans();
	}
}
?>