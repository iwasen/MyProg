<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/pro_search.php");
include("$inc/pro_enquete.php");
include("$inc/pro_enq_target.php");
include("$inc/check.php");
include("$inc/get_form.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");
include("$inc/mk_error.php");

define('MAX_SEND_NUM', 3000);

// セッション処理
if (isset($pro_enq_no)) {
	$pro_enq = &$_SESSION['ss_pro_enq'];
	if ($pro_enq->pro_enq_no != $pro_enq_no)
		redirect("pe_make.php?pro_enq_no=$pro_enq_no");
} else
	redirect('pe_index.php');

$search = &$pro_enq->search;

// フォームからクラスへ代入
$pro_enq->sample_num = get_number($_POST['sample_num']);

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

$cell = &$pro_enq->cell;
$cell->cell_option = $_POST['cell_option'];
$cell->age_option = $_POST['age_option'];
$cell->sex_option = $_POST['age_option'] == 1 ? 2 : ($_POST['age_option'] == 2 ? $_POST['sex_option2'] : $_POST['sex_option3']);
$cell->rate_option = $_POST['rate_option'];
$cell->send_num = $_POST['cell'][$cell->age_option];
$cell->send_rate = $_POST['rate_option'] == 1 ? null : ($_POST['rate_option'] == 2 ? $_POST['rate_mikikon'] : $_POST['rate_area']);

// 他ページへリダイレクト
switch ($next_action) {
case 'search':
	// 人数検索
	redirect("pe_matest2.php?pro_enq_no=$pro_enq_no");
case 'send_check':
	// 発信数確認
	redirect("pe_send_check.php?pro_enq_no=$pro_enq_no");
case 'send_check2':
	// 発信数確認
	redirect("pe_send_check2.php?pro_enq_no=$pro_enq_no");
}

// 最大発信数チェック
if ($pro_enq->sample_num <= 0)
	$msg[] = '本アンケート目標サンプル数を入力してください。';
else {
	$max_sample_num = 5000;
	if (!check_num($pro_enq->sample_num, 0, $max_sample_num))
		$msg[] = "本アンケート目標サンプル数は{$max_sample_num}サンプル以下で入力してください。";
}

if (!$msg) {
	$msg2 = 'Ｐｒｏアンケートの対象条件を設定しました。';

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
		$pro_enq->calc_send_num();
		if (!$msg && $search->get_monitor_num($pro_enq->marketer_id, $pro_enq->pro_enq_no) < $pro_enq->send_num)
			$msg2 = 'アンケート対象モニター数が目標サンプル数に満たないようです。';
	} elseif ($cell->cell_option == '2') {
		$search_cell = $cell->get_search_cell($search->make_sql(), true, $pro_enq->appearance_ratio,$search->get_target_flg());
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
	$pro_enq->save_search = true;
	$pro_enq->write_db();
	$pro_enq_no = $pro_enq->pro_enq_no;

	// ネットワーク識別テーブル作成
	$search->marketer_id = $pro_enq->marketer_id;
	$search->pro_enq_no = $pro_enq->pro_enq_no;
	$pro_enq_target = new pro_enq_target_class;
	$pro_enq_target->target_flg = $search->get_target_flg();
	$pro_enq_target->enquete_id = $pro_enq->enquete2_id;
	$pro_enq_target->write_db();
	if ($pro_enq->finding_flag) {
		$pro_enq_target->enquete_id = $pro_enq->enquete_id;
		$pro_enq_target->write_db();
	}

	// セッション変数削除
	unset($_SESSION['ss_pro_enq']);
}
?>
<? marketer_header('Ｐｒｏアンケート', PG_NULL) ?>
<table width="789" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td><img src="images/common/spacer.gif" width="1" height="10" border="0"></td>
	</tr>
</table>

<?
if (!$msg) {
?>
<table  border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
		<td width="778" align="center" valign="top">
			<table border="0" cellspacing="0" cellpadding="0" width="778">
				<tr>
					<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
					<td width="768" valign="top"><img src="images/mkk_pe/title_01.gif"></td>
				</tr>
			</table>
			<div align="center">
			<form>
			<table border=0 cellspacing=2 cellpadding=3 width="95%">
				<tr>
					<td><?=$msg2?></td>
				</tr>
			</table>
			<br>
<?
if ($edit) {
?>
<a href="pe_edit3.php?pro_enq_no=<?=$pro_enq_no?>"><img src="images/mkk_bt/revision.gif" alt="修　正" width="108" height="31"></a>
<?
}
?>
<a href="pe_make.php?pro_enq_no=<?=$pro_enq_no?>"><img src="images/mkk_bt/back.gif" alt="戻る" width="108" height="31" border="0"></a>
			</form>
			</div>
		</td>
	</tr>
</table>
<?
} else{?>

	<table width="788" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><? error_msg($msg, 'pe_edit3.php?edit=1');?></td>
  </tr>
</table>

<? }?>

</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? marketer_footer() ?>
