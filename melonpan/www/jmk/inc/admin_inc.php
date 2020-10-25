<?
$TC_HAKKOUSHA = "aa4488";
$TC_DOKUSHA = "44aa88";
$TC_MASTER = "aa8844";
$TC_MASTER_MAINTE = "9f8fc0";
$TC_OTHER = "888888";

$TT_kari_touroku = "仮登録承認処理";
$TT_hon_touroku = "本登録承認処理";
$TT_teishi = "メルマガ停止処理";
$TT_riba_regist = "りばっチャ登録";
$TT_hinshitsu = "品質チェック";
$TT_pub_msg = "「事務局からのお知らせ」入力";
$TT_hakkousha_mainte = "発行者情報メンテナンス";
$TT_letter_hakkousha = "発行者宛てメール";

$TT_dokusha_mainte = "読者情報メンテナンス";
$TT_addr_search = "読者検索/代理解除";
$TT_dokusha_ikkatsu = "読者一括登録/削除";
$TT_melonpai_regist = "メルマガナビ登録";
$TT_melonpai_kannri = "メルマガナビ管理";
$TT_max_setting = "メルマガナビ上限設定";
$TT_disabled_adr = "購読禁止メールアドレス登録";

$TT_letter_melonpan = "めろんぱんレター配信";
$TT_letter_bakyun = "バ・キューン配信";
$TT_letter_tsuushin = "めろんぱん通信配信";
$TT_haishin_log = "事務局メール配信ログ";
$TT_hitorigoto = "「今日のひとりごと」入力";
$TT_whats_new = "「What's New」入力";
$TT_footer = "ヘッダー／フッターデータ登録";
$TT_haishin_yoyaku = "配信予約一覧";
$TT_kanri_suuji = "管理数字表示";
$TT_osusume_log = "オススメログ一覧";
$TT_henkou_list = "登録情報変更リスト";
$TT_sabun_point = "読者登録数/差分ポイント付与";
$TT_add_point = "発行者・読者・メルマガナビ/ポイント付与";
$TT_point_list = "ポイント状況閲覧";
$TT_letter_html = "めろんぱんHTMLレター";

$TT_present = "プレゼント応募管理";
$TT_mail_cleaning = "メールクリーニングサービス";
$TT_banner_set = "広告バナー設定";
$TT_click = "広告クリックカウンタ";

$TT_mail_template = "送信メールテンプレート";
$TT_batch_schedule = "バッチスケジュール";
$TT_server_mainte = "配信予約禁止設定";
$TT_tassei = "読者達成メール設定";

if ($cookie_admin_id == "") {
	$cookie_admin_id = "";
	header ("location: ../login.php");
	exit;
} else {
	$sql = "SELECT * FROM M_ADMIN, M_SUPERPWD WHERE admin_id = '$cookie_admin_id'";
	$result = db_exec($sql);
	if (pg_numrows($result) <= 0) {
		$cookie_admin_id = "";
		header ("location: ../login.php");
		exit;
	}
}
?>