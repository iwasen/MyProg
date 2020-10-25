<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/pro_enquete.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");

// セッション処理
if ($pro_enq_no) {
	$owner_id = $_SESSION['ss_owner_id'];
	$_SESSION['ss_pro_enq'] = new pro_enquete_class;
	$pro_enq = &$_SESSION['ss_pro_enq'];
	$pro_enq->read_db($owner_id, $pro_enq_no);

	// コピー元アンケートID保存
	$src_enquete_id = $pro_enq->enquete_id;
	$src_enquete2_id = $pro_enq->enquete2_id;

	$pro_enq->pro_enq_no = '';
	$pro_enq->enquete_id = '';
	$pro_enq->enquete2_id = '';
	$pro_enq->search_id = '';
	$pro_enq->save_enquete = true;
	$pro_enq->save_enquete2 = true;
	$pro_enq->save_search = true;
	$pro_enq->save_res_cond = true;
	$pro_enq->make_step = 0;
	$pro_enq->mail_title = '';
	$pro_enq->mail_header = '';
	$pro_enq->mail_contents = '';
	$pro_enq->mail_footer = '';

	$enquete = &$pro_enq->enquete;
	$enquete->status = 0;
	$enquete->description = '';

	$enquete->duplicate_image();
	if ($pro_enq->finding_flag == DBTRUE)
		$pro_enq->enquete2->duplicate_image();

	$pro_enq->write_db();

	// アンケートクリーニング条件設問の回答アンケート番号補正
	$cleaning = $pro_enq->cleaning;
	if (is_array($cleaning->cleaning_cond)) {
		foreach ($cleaning->cleaning_cond as $cleaning_cond) {
			if (is_array($cleaning_cond->ans_cond)) {
				foreach ($cleaning_cond->ans_cond as $seq_no => $ans_cond) {
					switch ($ans_cond->ans_enquete_id) {
					case $src_enquete_id:
						$cleaning_cond->ans_cond[$seq_no]->ans_enquete_id = $pro_enq->enquete_id;
						break;
					case $src_enquete2_id:
						$cleaning_cond->ans_cond[$seq_no]->ans_enquete_id = $pro_enq->enquete2_id;
						break;
					}
				}
			}
		}
	}
	$pro_enq->cleaning->write_db($pro_enq->enquete2_id);

	if ($pro_enq->enquete_id)
		$pro_enq->enquete->write_branch_cond();

	if ($pro_enq->enquete_id2 && $pro_enq->enquete_id2 != $pro_enq->enquete_id) {
		$pro_enq->enquete2->write_branch_cond();
		$pro_enq->enquete2->write_cleaning_cond();
	}

} else
	redirect('pe_index.php');

redirect("pe_make.php?pro_enq_no=$pro_enq->pro_enq_no");
?>