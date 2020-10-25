<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:Ｍｙアンケート変更処理
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_enquete.php");
include("$inc/check.php");
include("$inc/get_form.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
include("$inc/error_msg.php");

set_global('myenquete', 'Ｍｙアンケート管理', 'Ｍｙアンケート情報変更', BACK_TOP);

// セッション処理
$myenq = &$_SESSION['ss_my_enq'];
if ($myenq->my_enq_no != $my_enq_no)
	redirect("show.php?marketer_id=$marketer_id&my_enq_no=$my_enq_no");

$search = &$myenq->search;

// フォームからクラスへ代入
$myenq->send_num = get_number($_POST['send_num']);

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

	$myenq->age_type = $_POST['age_type'];
	if ($myenq->age_type == '1') {
		$search->age_cd = get_multi_data($_POST['age_cd']);
		$search->age_from = '';
		$search->age_to = '';
	} elseif ($myenq->age_type == '2') {
		$search->age_cd = '';
		$search->age_from = $_POST['age_from'];
		$search->age_to = $_POST['age_to'];
	} else {
		$search->age_cd = '';
		$search->age_from = '';
		$search->age_to = '';
	}
}

$search->type = $_POST['type'];
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
$search->special_panel = get_multi_data($_POST['special_panel']);

$cell = &$myenq->cell;
$cell->cell_option = $_POST['cell_option'];
$cell->age_option = $_POST['age_option'];
$cell->sex_option = $_POST['age_option'] == 1 ? 2 : ($_POST['age_option'] == 2 ? $_POST['sex_option2'] : $_POST['sex_option3']);
$cell->rate_option = $_POST['rate_option'];
$cell->send_num = $_POST['cell'][$cell->age_option];
$cell->send_rate = $_POST['rate_option'] == 1 ? null : ($_POST['rate_option'] == 2 ? $_POST['rate_mikikon'] : $_POST['rate_area']);

// 最大発信数チェック
if ($myenq->send_num <= 0)
	$msg[] = '最大発信数を入力してください。';

if (!$msg) {
	$msg2 = 'Myアンケートの対象条件を設定しました。';

	// 配信割合チェック
	if ($cell->rate_option == 2) {
		// 未既婚でどちらかが100%ならそちらを検索条件とする
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
	if ($cell->cell_option == '1') {
		if (!$msg && $search->get_monitor_num() < $myenq->send_num)
			$msg2 = '対象モニター数が最大発信数に満たないようです。';
	} elseif ($cell->cell_option == '2') {
		$search_cell = $cell->get_search_cell($search->make_sql());
		$send_cell = $cell->get_send_cell();
		$cell->get_range($sex_s, $sex_e, $age_s, $age_e, $rate_s, $rate_e);

		$short = false;
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
			$edit = true;
			$msg2 = '発信人数の足りない年代（地域）があります。最大発信数以上発信を希望させる場合は、修正してください。';
		}
	}

	// DBに保存
	$myenq->save_search = true;
	$myenq->write_db();
	$my_enq_no = $myenq->my_enq_no;

	// セッション変数削除
	unset($_SESSION['ss_my_enq']);
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
<input type="button" value="　戻る　" onclick="location.href='show.php?marketer_id=<?=$marketer_id?>&my_enq_no=<?=$my_enq_no?>'">
</form>
</div>
<?
} else
	error_msg($msg, 'edit3.php?edit=1');
?>
<? page_footer() ?>
</body>
</html>
