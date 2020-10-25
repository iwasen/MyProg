<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:Ｐｒｏアンケート更新処理
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/search.php");
include("$inc/pro_search.php"); // 2006/06/26/ BTI
include("$inc/enquete.php");
include("$inc/pro_enquete.php");
include("$inc/get_form.php");
include("$inc/check.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
include("$inc/error_msg.php");

set_global('proenquete', 'Ｐｒｏアンケート管理', 'Ｐｒｏアンケート更新', BACK_TOP);

// セッション処理
$pro_enq = &$_SESSION['ss_pro_enq'];
if ($pro_enq->pro_enq_no != $pro_enq_no)
	redirect("show.php?marketer_id=$marketer_id&pro_enq_no=$pro_enq_no");

$search = &$pro_enq->search;

// フォームからクラスへ代入
$pro_enq->sample_num = get_number($_POST['sample_num']);

if (isset($_POST['type']))
	$search->type = $_POST['type'];

if ($_POST['cell_option'] == 2) {
	// セル指定有り
	$search->sex = '';
	$search->age_cd = '';
	$search->age_from = $_POST['age_span_from'];
	$search->age_to = $_POST['age_span_to'];
} else {
	// セル指定無し
	$search->sex = $_POST['sex'];

	$pro_enq->age_type = $_POST['age_type'];
	if ($pro_enq->age_type == '1') {
		$search->age_cd = get_multi_data($_POST['age_cd']);
		$search->age_from = '';
		$search->age_to = '';
	} elseif ($pro_enq->age_type == '2') {
		$search->age_cd = '';
		$search->age_from = $_POST['age_from'];
		$search->age_to = $_POST['age_to'];
	} else {
		$search->age_cd = '';
		$search->age_from = '';
		$search->age_to = '';
	}
}

$search->mikikon = $_POST['mikikon'];
$search->jitaku_area = get_multi_data($_POST['jitaku_area']);
$search->shokugyou = get_multi_data($_POST['shokugyou']);
$search->gyoushu = get_multi_data($_POST['gyoushu']);
$search->shokushu = get_multi_data($_POST['shokushu']);
$search->have_child = get_multi_data($_POST['have_child']);
$search->housing_form = get_multi_data($_POST['housing_form']);
$search->have_car = get_multi_data($_POST['have_car']);
$search->genre = get_multi_data($_POST['genre']);
$search->conveni = get_multi_data($_POST['conveni']);
$search->super = get_multi_data($_POST['super']);
$search->ma_profile = get_multi_data($_POST['ma_profile']);
$search->taisho_myenq_list = $_POST['taisho_myenq_list_flag'] ? $_POST['taisho_myenq_list'] : '';
$search->jogai_myenq_list = $_POST['jogai_myenq_list_flag'] ? $_POST['jogai_myenq_list'] : '';

$cell = &$pro_enq->cell;
$cell->cell_option = $_POST['cell_option'];
$cell->age_option = $_POST['age_option'];
$cell->sex_option = $_POST['age_option'] == 1 ? 2 : ($_POST['age_option'] == 2 ? $_POST['sex_option2'] : $_POST['sex_option3']);
$cell->rate_option = $_POST['rate_option'];
$cell->send_num = $_POST['cell'][$cell->age_option];
$cell->send_rate = $_POST['rate_option'] == 1 ? null : ($_POST['rate_option'] == 2 ? $_POST['rate_mikikon'] : $_POST['rate_area']);

// 他ページへリダイレクト
switch ($next_action) {
case 'send_check':
	// 発信数確認
	redirect("send_check.php?marketer_id=$marketer_id&pro_enq_no=$pro_enq_no");
case 'send_check2':
	// 発信数確認
	redirect("send_check2.php?marketer_id=$marketer_id&pro_enq_no=$pro_enq_no");
}

// 最大発信数チェック
if ($pro_enq->sample_num <= 0)
	$msg[] = '本アンケート目標サンプル数を入力してください。';
else {
	$max_sample_num = 50000;
	if (!check_num($pro_enq->sample_num, 0, $max_sample_num))
		$msg[] = "本アンケート目標サンプル数は{$max_sample_num}サンプル以下で入力してください。";
}

if (!$msg) {
	$msg2 = 'Ｐｒｏアンケートの対象条件を設定しました。';

	// 配信割合チェック
	if ($cell->rate_option == 2) {
		// 未既婚でどれかが100%ならそれを検索条件とする
		for ($i = 1; $i <= 3; $i++) {
			if ($cell->send_rate[$i] == 100) {
				$cell->rate_option = 1;
				$search->mikikon = "$i";
				break;
			}
		}
	} elseif ($cell->rate_option == 3) {
		// 配信割合が設定されていない地域は検索対象から外す
		if (is_array($_POST['area_cd'])) {
			$jitaku_area = $_POST['jitaku_area'];
			foreach ($_POST['area_cd'] as $area_cd) {
				if ($_POST['rate_area'][$area_cd] == 0) {
					unset($jitaku_area[$area_cd]);
					$msg2 = '配信割合が設定されていない地域は検索対象から外しました。';
				}
			}
			$search->jitaku_area = get_multi_data($jitaku_area);
		}
	}

	// 対象者数チェック
	$short = false;
	if ($cell->cell_option == '1') {
		$pro_enq->calc_send_num();
		// 人数検索対応 2006/03/03 BTI
		//if (!$msg && $search->get_monitor_num($marketer_id, $pro_enq_no, $pro_enq->sample_num) < $pro_enq->send_num)
		// 2006/06/23
		if (!$msg && $search->get_monitor_num_by_ref(&$pro_enq) < $pro_enq->send_num) {
			$short = true;
			$msg2 = 'アンケート対象モニター数が目標サンプル数に満たないようです。';
		}
	} elseif ($cell->cell_option == '2') {
		$search_cell = $cell->get_search_cell($search->make_sql(), true, $pro_enq->appearance_ratio);
		$send_cell = $cell->get_send_cell();
		$cell->get_range($sex_s, $sex_e, $age_s, $age_e, $rate_s, $rate_e);

		if ($cell->rate_option == '1') {
			for ($sex = $sex_s; $sex <= $sex_e && !$short; $sex++) {
				for ($age = $age_s; $age <= $age_e && !$short; $age++) {
					if ((int)$search_cell[$sex][$age][0] < (int)$send_cell[$sex][$age][0])
						$short = true;
				}
			}
		} else {
			for ($sex = $sex_s; $sex <= $sex_e && !$short; $sex++) {
				for ($age = $age_s; $age <= $age_e && !$short; $age++) {
					for ($rate = $rate_s; $rate <= $rate_e && !$short; $rate++) {
						if ((int)$search_cell[$sex][$age][$rate] < (int)$send_cell[$sex][$age][$rate])
							$short = true;
					}
				}
			}
		}
		if ($short) {
			$msg2 = '発信人数の足りない年代（地域）があります。最大発信数以上発信を希望させる場合は、修正してください。';
		}
	}


	// DBに保存
	$pro_enq->save_search = true;
	$pro_enq->write_db();
	$pro_enq_no = $pro_enq->pro_enq_no;

	// モニター数が不足の場合は追加発信をしない
	$send_count = $short ? 9 : 0;
	$sql = "UPDATE t_pro_enquete SET mep_send_count=$send_count WHERE mep_marketer_id=$marketer_id AND mep_pro_enq_no=$pro_enq_no AND mep_send_count<>$send_count";
	db_exec($sql);

	// 更新時、アンケート回答データを削除する 2006/03/28 BTI
	$sql = "DELETE FROM t_answer WHERE an_enquete_id IN ($pro_enq->enquete_id,$pro_enq->enquete2_id)";
	db_exec($sql);
	$sql = "DELETE FROM t_ans_select WHERE as_enquete_id IN ($pro_enq->enquete_id,$pro_enq->enquete2_id)";
	db_exec($sql);
	$sql = "DELETE FROM t_ans_matrix WHERE ax_enquete_id IN ($pro_enq->enquete_id,$pro_enq->enquete2_id)";
	db_exec($sql);

	// 対象条件の設定の人数検索 2006/06/26
	// ネットワーク識別テーブルのフラグ(pet_target_flg)を立てる
//	$pro_enq->read_db($marketer_id, $pro_enq_no);
	$search = &$pro_enq->search;
//	$search->get_monitor_num($marketer_id, $pro_enq_no);
	$get_target_flg = $search->get_target_flg();
	$sql = "UPDATE t_pro_enq_target SET pet_target_flg='$get_target_flg' WHERE pet_enquete_id IN ($pro_enq->enquete_id,$pro_enq->enquete2_id)";
	db_exec($sql);


	// セッション変数削除
	unset($_SESSION['ss_pro_enq']);
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body>
<? page_header() ?>

<br>
<?
if (!$msg) {
?>
<div align="center">
<form>
<p><?=$msg2?></p>
<input type="button" value="　戻る　" onclick="location.href='show.php?marketer_id=<?=$marketer_id?>&pro_enq_no=<?=$pro_enq_no?>'">
</form>
</div>
<?
} else
	error_msg($msg, 'edit3.php?edit=1');
?>
<? page_footer() ?>
</body>
</html>
