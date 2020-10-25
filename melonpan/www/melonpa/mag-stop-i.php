<?php
include ("../inc/com_db_func.inc");
/* -------------------------------------------------------------------- */
/*	i-mode 解除 (sub)													*/
/* -------------------------------------------------------------------- */
//ページ前半表示
function dsp_header(){
echo "<html>"
	."<head><title>メルマガ解除</title></head>";
}
//ページ後半表示
function dsp_footer(){
echo "[<a href=\"http://www.melonpan.net/\">トップへ戻る</a>]"
	."<hr>"
	."Copyright &copy; 2000 xxxxxxx, Co.,Ltd. All rights reserved."
	."</body>"
	."</html>";
}
//初期画面(メールアドレス入力)
function dsp_init(){
echo "<body>メールアドレス入力<br>"
	."<form action=\"mag-stop-i.php?mode=search\" method=\"post\">"
	."<INPUT type=\"text\" name=\"email\" size=15>"
	."<INPUT type=\"submit\" name=\"Submit1\" value=\"次へ\">"
	."</form>";
//	."<INPUT type=\"text\" name=\"email\" size=\"15\" value=\"user1@hmg.local.net\">"

}

//絞込みキーワード入力画面
function dsp_search($email){
echo "<body>"
	."<form action=\"mag-stop-i.php?mode=list\" method=\"post\">"
	."<INPUT type=\"text\" name=\"keyword\" size=15>"
	."<INPUT type=\"submit\" name=\"Submit2\" value=\"検索\">"
	."<INPUT type=\"hidden\" name=\"email\"   value=\"".$email."\">"
	."</form>";
}

//メルマガ検索結果表示画面
function dsp_list($email, $keyword, $nrows, $mag_list){
echo "<body>"
	."<form action=\"mag-stop-i.php?mode=list\" method=\"post\">"
	."<INPUT type=\"text\"   name=\"keyword\" size=15 value=\"".$keyword."\">"
	."<INPUT type=\"submit\" name=\"Submit3\" value=\"検索\">"
	."<INPUT type=\"hidden\" name=\"email\"   value=\"".$email."\">"
	."</form>"	
	."<hr>";
	for ( $i = 0 ; $i < $nrows ; $i++ ) {
		reset ($mag_list);
		$column = each ($mag_list);	$mag_id = $column['value'];
		$column = each ($mag_list);	$mag_nm = $column['value'];
		$mag_nm = trim($mag_nm[$i]);
		echo "●<a href=\"mag-stop-i.php?mode=confirm&mag_id=". $mag_id[$i] ."&email=".$email."\">"
			."$mag_nm</a><br>";
	}
}

//処理確認画面
function dsp_confirm($email, $mag_id, $mag_nm){
echo "<body>"
	."<form action=\"mag-stop-i.php?mode=stop\" method=\"post\">"
	.$mag_nm."の購読を解除します。"
	."<INPUT type=\"submit\" name=\"Submit5\" value=\"解除\">"
	."<INPUT type=\"hidden\" name=\"mag_id\"  value=\"".$mag_id."\">"
	."<INPUT type=\"hidden\" name=\"email\"   value=\"".$email. "\">"
	."</form>";
}

//処理結果表示画面
function dsp_msg($msg){
echo "<body><br>"
	."<form>"
	.$msg."<BR>";
}

//メールアドレス確認
function check_email($email){

	$sql = "select count(*) as cnt from T_MAILADDR M where mail_addr = "."'".$email."'";
	$select = new select_sql($sql);
	if ($select->fetch()) {
		$cnt = $select->field("CNT");
		if ( $cnt == 0 )
			return false;
	}else{
		return false;
	}
	return true;

}
//メルマガ一覧取得
function mag_search($email, $keyword){
	global $nrows;

	$conn = db_conxxxt();
	$mag_list = array();
	$sql = "select distinct M.mag_id, M.mag_nm "
		 . "  from M_MAGAZINE M,"
		 . "      (select mag_id from T_MAILADDR where mail_addr = '".$email."') A"
		 . " where M.mag_id = A.mag_id"
		 ."    and ( M.mag_pub_status_flg = '01' or M.mag_pub_status_flg = '03' ) "
		 . "   and ( M.mag_pub_stop_flg <> '02' )"
		 . "   and ( M.publisher_id <> 0 )";

	if ($keyword != ""){
		$keyword = stripslashes($keyword);
		$keyword = ereg_replace("'","''",$keyword);
		$keyword = strtoupper($keyword);
		$kw11 = i18n_ja_jp_hantozen($keyword, "a"); 	// 英数を半角へ
		$kw12 = i18n_ja_jp_hantozen($keyword, "A");		// 英数を全角へ
		$kw21 = i18n_ja_jp_hantozen($keyword, "KV");	// 全角カナへ(マガジン名、紹介文は半角カナなし)

		$sql .= " and (UPPER(M.mag_nm) Like '%$keyword%' "
			. "    or  UPPER(M.mag_nm) Like '%$kw11%' "
			. "    or  UPPER(M.mag_nm) Like '%$kw12%' "
			. "    or  UPPER(M.mag_nm) Like '%$kw21%' "
			. "    or  UPPER(M.mag_id) Like '%$kw11%'"
			. "        )";
	}

	$stmt = OCIParse($conn,$sql);
	if (!OCIExecute($stmt)) {
		OCIFreestatement($stmt);
		return $mag_list;
	}

	$nrows = OCIFetchStatement ($stmt, &$mag_list);
	OCIFreestatement($stmt);
	db_logoff();
	return $mag_list;
}

//削除確認データ取得
function select_stop_mag($email, $mag_id){
	global $mag_nm;

	$sql = "select M.mag_nm "
		 . "  from M_MAGAZINE M,"
		 . "      (select mag_id from T_MAILADDR where mail_addr = '".$email."') A"
		 . " where A.mag_id = M.mag_id "
		 . "   and A.mag_id = "."'".$mag_id."'";
	$select = new select_sql($sql);
	if ($select->fetch()) {
		$mag_nm = $select->field("MAG_NM");
	}else{
		return false;
	}
	return true;
}

//メールアドレス削除
function del_mail_addr($email, $mag_id){

	$sql = "select count(*) as cnt from T_MAILADDR "
		 . " where mail_addr = '".$email."' and mag_id = "."'".$mag_id."'";
	$select = new select_sql($sql);
	if ($select->fetch()) {
		$cnt = $select->field("CNT");
	}
	if ($cnt != 1) 
		return false;


	$conn = db_conxxxt();
	$sql = "delete from T_MAILADDR "
		 . " where mail_addr = '".$email."' and mag_id = "."'".$mag_id."'";
	$stmt = OCIParse($conn,$sql);

	if (!OCIExecute($stmt))
		return false;
	if (!OCIFreestatement($stmt))
		return false;

	db_logoff();
	return true;

}
/* -------------------------------------------------------------------- */
/*	imode 解除（Main）													*/
/* -------------------------------------------------------------------- */
dsp_header();

if ($mode=="init"){
	//メールアドレス入力
	dsp_init();
}elseif($mode=="search"){
	//絞込み用キーワード入力
	if ( check_email($email) ){
		dsp_search($email);
	}else{
		dsp_msg("このメールアドレスでは購読していないようです。");
	}
}elseif($mode=="list"){
	//メルマガ一覧表示
	$mag_list = mag_search($email, $keyword);
	dsp_list($email, $keyword, $nrows, $mag_list);
}elseif($mode=="confirm"){
	//削除確認
	if (select_stop_mag($email, $mag_id)){
		dsp_confirm($email, $mag_id, $mag_nm);
	}else{
		dsp_msg("購読解除に失敗しました。");
	}
}elseif($mode=="stop"){
	//削除
	if ( del_mail_addr($email, $mag_id) ){
		$msg="購読解除完了。";
	}else{
		$msg="購読解除に失敗しました。";
	}
	dsp_msg($msg);
}else{

}

dsp_footer();
exit();


?>
