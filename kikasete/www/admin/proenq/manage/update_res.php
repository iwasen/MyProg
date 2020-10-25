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

// フォームから入力データ取得
$pro_enq->res_and_or = $_POST['res_and_or'];
$pro_enq->appearance_ratio = get_number($_POST['appearance_ratio']);

if ($pro_enq->appearance_ratio == 0)
	$msg[] = '想定出現率が入力されていないようです。';
elseif (!check_num($pro_enq->appearance_ratio, 1, 99))
	$msg[] = '想定出現率は1〜99の範囲で入力してください。';

if (!$msg) {
	$pro_enq->res_cond = array();
	if (is_array($_POST['and_or'])) {
		foreach ($_POST['and_or'] as $qno => $and_or) {
			$pro_enq->res_cond[$qno]->and_or = $and_or;
			$pro_enq->res_cond[$qno]->not_cond = $_POST['not_cond'][$qno];
			$pro_enq->res_cond[$qno]->select_no = $_POST['select_no'][$qno] ? join(',', $_POST['select_no'][$qno]) : '';
		}
	} else
		$pro_enq->res_cond = null;

	// エラー無しなら次ページへ
	if (!$msg) {
		// Proアンケート保存
		$pro_enq->save_res_cond = true;

		if ($pro_enq->finding_flag == DBTRUE) {
			$pro_enq->enquete2->description = $pro_enq->res_cond_text('とお答えいただいた方にお聞きします。');
			$pro_enq->save_enquete2 = true;
		}

		$pro_enq->write_db();
		$pro_enq_no = $pro_enq->pro_enq_no;

		// セッション変数削除
		unset($_SESSION['ss_pro_enq']);
	}
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
<p>Ｐｒｏアンケートの本アンケート対象条件を設定しました。</p>
<input type="button" value="　戻る　" onclick="location.href='show.php?marketer_id=<?=$marketer_id?>&pro_enq_no=<?=$pro_enq_no?>'">
</form>
</div>
<?
} else
	error_msg($msg);
?>
<? page_footer() ?>
</body>
</html>
