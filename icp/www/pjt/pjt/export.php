<?
/******************************************************
' System :ICPプロジェクトページ
' Content:メンバーエクスポート
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/auth.php");
include("$inc/csv.php");
include("$inc/format.php");

$inc = "$top/inc";
include("$inc/pjt_common.php");
include("$inc/login_check.php");

// 入力パラメータ
$pjt_id = $_SESSION[SCD]['pjt_id'];

// プロジェクトCDを取得
$sql = "SELECT pj_pjt_cd"
		. " FROM t_pjt"
		. " WHERE pj_pjt_id=$pjt_id";
$result = db_exec($sql);
$fetch = pg_fetch_object($result, 0);

// 出力ファイル名
prepare_csv("member_{$fetch->pj_pjt_cd}.csv");

// ヘッダ出力
$csv = 'メールアドレス,password,組織ID,組織メンバーID,ニックネーム,姓,名,セイ,メイ,性別コード,誕生日,未既婚,職業,業種,職種,郵便番号,住所,電話番号,プロジェクトメンバ管理,ジョブ管理,発言ログエクスポート,発言録エクスポート,参加者メンバエクスポート,発言集計エクスポート';
output_csv($csv);

// メンバー情報出力
$sql = "SELECT pm_mail_addr,pm_password,pm_org_id,pm_org_member_id,pm_nickname,pm_name1,pm_name2,pm_name1_kana,pm_name2_kana,pm_sex_cd,to_char(pm_birthday, 'YYYY/MM/DD') AS pm_birthday,pm_mikikon_cd,pm_shokugyou,pm_gyoushu,pm_gyoushu,pm_shokushu,pm_zip_cd,pm_address,pm_tel_no,pm_staff_auth"
		. " FROM t_pjt_member"
		. " WHERE pm_pjt_id=$pjt_id AND pm_status=1"
		. " ORDER BY pm_member_id";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	set_csv($csv, $fetch->pm_mail_addr);
	set_csv($csv, $fetch->pm_password);
	set_csv($csv, $fetch->pm_org_id);
	set_csv($csv, $fetch->pm_org_member_id);
	set_csv($csv, $fetch->pm_nickname);
	set_csv($csv, $fetch->pm_name1);
	set_csv($csv, $fetch->pm_name2);
	set_csv($csv, $fetch->pm_name1_kana);
	set_csv($csv, $fetch->pm_name2_kana);
	set_csv($csv, $fetch->pm_sex_cd);
	set_csv($csv, $fetch->pm_birthday);
	set_csv($csv, $fetch->pm_mikikon_cd);
	set_csv($csv, $fetch->pm_shokugyou);
	set_csv($csv, $fetch->pm_gyoushu);
	set_csv($csv, $fetch->pm_shokushu);
	set_csv($csv, $fetch->pm_zip_cd);
	set_csv($csv, $fetch->pm_address);
	set_csv($csv, $fetch->pm_tel_no);

	set_csv($csv, check_staff_auth('プロジェクトメンバ管理', $fetch->pm_staff_auth) ? '1' : '0');
	set_csv($csv, check_staff_auth('ジョブ管理', $fetch->pm_staff_auth) ? '1' : '0');
	set_csv($csv, check_staff_auth('発言ログエクスポート', $fetch->pm_staff_auth) ? '1' : '0');
	set_csv($csv, check_staff_auth('発言録エクスポート', $fetch->pm_staff_auth) ? '1' : '0');
	set_csv($csv, check_staff_auth('参加者メンバエクスポート', $fetch->pm_staff_auth) ? '1' : '0');
	set_csv($csv, check_staff_auth('発言集計エクスポート', $fetch->pm_staff_auth) ? '1' : '0');

	output_csv($csv);
}

exit;
?>