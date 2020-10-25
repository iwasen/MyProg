<?
$print_msg = "";
$Chk_flg = TRUE;

$mag_cycle = "00";

// 「カテゴリー」の変換
if (count($mag_catg_chk) == 2) {
	$mag_catg_1 = $mag_catg_chk[0];
	$mag_catg_2 = $mag_catg_chk[1];
}
if ($mm_update) {
	if (count($mag_catg_chk) == 0) {
		$mag_catg_chk[0] = $mag_catg_1;
		$mag_catg_chk[1] = $mag_catg_2;
	}
}
// 「背景」の変換
if (count($mag_bg_chk) == 2) {
	$mag_bg_1 = $mag_bg_chk[0];
	$mag_bg_2 = $mag_bg_chk[1];
}
if ($mm_update) {
	if (count($mag_bg_chk) == 0) {
		$mag_bg_chk[0] = $mag_bg_1;
		$mag_bg_chk[1] = $mag_bg_2;
	}
}

// 「発行周期」の長さチェック
$mag_cycle_day = i18n_ja_jp_hantozen($mag_cycle_day, "K");
if (strlen($mag_cycle_day) > 40)
{
	$print_msg = $print_msg . "「発行周期」は、全角２０文字以内で入力してください。<br>\n";
}
if (strlen($mag_cycle_day) == 0)
{
	$print_msg = $print_msg . "「発行周期」が記入されていないようです。<br>\n";
}

// 「ＵＲＬ」の変換
if (strlen($mag_hp) == 0) {
	$mag_hp = "http://";
} else {
	if (strlen($mag_hp) > 80)
	{
		$print_msg = $print_msg . "「ＵＲＬ」は、半角８０文字以内で入力してください。<br>\n";
	}
}

// 「めるめる・net　ID」のチェック
if ($melmel_id != "") {
	if (strlen($melmel_id) > 5)
	{
		$print_msg =  "「めるめる・net　ID」は、半角５文字で入力してください。<br>\n";
	}
	if ( !(ereg("^[0-9]{5}$", $melmel_id)) )
	{
		$print_msg =  "「めるめる・net　ID」は、半角５文字で入力してください。<br>\n";
	}
}
// 「メルマガ発行元メールアドレス」のチェック
if (strlen($mail_add_publish) == 0)
{
	$print_msg =  "「メルマガ発行元メールアドレス」が記入されていないようです。<br>\n";
} else {
	// 「メルマガ発行元メールアドレス」の長さチェック
	if (strlen($mail_add_publish) > 50)
	{
		$print_msg = $print_msg . "「メルマガ発行元メールアドレス」は、５０文字以内で入力してください。<br>\n";
	} else {
		if ( !(ereg ("^([0-9a-zA-Z._-]+)(@[0-9a-zA-Z._-]+)$", $mail_add_publish)) )
		{
			$print_msg = $print_msg . "「メルマガ発行元メールアドレス」が正しく入力されていないようです。<br>\n";
		}
	}
}
// 「メルマガタイトル」のチェック
if (strlen($mag_nm) == 0)
{
	$print_msg = $print_msg . "「メルマガタイトル」が記入されていないようです。<br>\n";
} else {
	// 「メルマガタイトル」の長さチェック
	$mag_nm = i18n_ja_jp_hantozen($mag_nm, "K");
	if (strlen($mag_nm) > 80)
	{
		$print_msg = $print_msg . "「メルマガタイトル」は、全角４０文字以内で入力してください。<br>\n";
	}
	//else {
		//$mag_nm_chk = i18n_ja_jp_hantozen($mag_nm, "K");
		//if (strcmp($mag_nm, $mag_nm_chk) != 0)
		//{
		//	$print_msg = $print_msg . "「メルマガタイトル」には、半角カナは使用できません。<br>\n";
		//}
	//}
}
// 「メルマガ紹介文[ロング]」のチェック
if (strlen($mag_intro_long) == 0)
{
	$print_msg = $print_msg . "「メルマガ紹介文[ロング]」が記入されていないようです。<br>\n";
} else {
	// 「メルマガ紹介文[ロング]」の長さチェック
	$mag_intro_long = i18n_ja_jp_hantozen($mag_intro_long, "K");
	if (strlen($mag_intro_long) > 500)
	{
		$print_msg = $print_msg . "「メルマガ紹介文[ロング]」は、全角２５０文字以内で入力してください。<br>\n";
	}
}
// 「メルマガ紹介文[ショート]」のチェック
if (strlen($mag_intro_short) == 0)
{
	$print_msg =$print_msg . "「メルマガ紹介文[ショート]」が記入されていないようです。<br>\n";
} else {
	// 「メルマガ紹介文[ショート]」の長さチェック
	$mag_intro_short = i18n_ja_jp_hantozen($mag_intro_short, "K");
	if (strlen($mag_intro_short) > 100)
	{
		$print_msg = $print_msg . "「メルマガ紹介文[ショート]」は、全角５０文字以内で入力してください。<br>\n";
	}
}
// 「タイプ」のチェック
if (strlen($mag_type) == 0)
{
	$print_msg = $print_msg . "「タイプ」が選択されていないようです。<br>\n";
}
// 「発行周期」のチェック
//	if (strlen($mag_cycle) == 0)
//	{
//		$print_msg = $print_msg . "「発行周期」が選択されていないようです。<br>\n";
//	}
// 「カテゴリ」のチェック
if (count($mag_catg_chk) != 2)
{
	$print_msg = $print_msg . "「カテゴリ」の選択数が間違っています。２つ選択してください。<br>\n";
}
// 「読む背景」のチェック
if (count($mag_bg_chk) != 2)
{
	$print_msg = $print_msg . "「読む背景」の選択数が間違っています。２つ選択してください。<br>\n";
}
// 「とくに読んでもらいたい性別」のチェック
if (strlen($mag_reader_sex) == 0)
{
	$print_msg = $print_msg . "「とくに読んでもらいたい性別」が選択されていないようです。<br>\n";
}
// 「とくに読んでもらいたい年代」のチェック
if (strlen($mag_reader_age) == 0)
{
	$print_msg = $print_msg . "「とくに読んでもらいたい年代」が選択されていないようです。<br>\n";
}
// 「とくに読んでもらいたいエリア」のチェック
if (strlen($mag_reader_area) == 0)
{
	$print_msg = $print_msg . "「とくに読んでもらいたいエリア」が選択されていないようです。<br>\n";
}
// 「とくに読んでもらいたい職業」のチェック
if (strlen($mag_reader_occup) == 0 || $mag_reader_occup == "選択してください")
{
	$print_msg = $print_msg . "「とくに読んでもらいたい職業」が選択されていないようです。<br>\n";
}
// 「バックナンバー公開／非公開」のチェック
if (strlen($mag_bn_pub) == 0) {
	$print_msg = $print_msg . "「バックナンバー公開／非公開」が選択されていないようです。<br>\n";
}
// 「プレゼント応募券の挿入」のチェック
if (strlen($mag_present_ins) == 0) {
	$print_msg = $print_msg . "「プレゼント応募券の挿入」が選択されていないようです。<br>\n";
}
// 「めろんぱんオリジナル」のチェック
if (strlen($mag_original_flg) == 0) {
	$print_msg = $print_msg . "「めろんぱんオリジナル」が選択されていないようです。<br>\n";
}
// 「配信結果通知メール」のチェック
if (strlen($send_notice_flg) == 0) {
	$print_msg = $print_msg . "「配信結果通知メール」が選択されていないようです。<br>\n";
}

// 「ＭＬの場合」のチェック
//	if (strlen($ml_type) == 0)
//	{
//		$print_msg = $print_msg . "「ＭＬの場合」が選択されていません。<br>\n";
//	}


if ($print_msg != "") {
	$Chk_flg = FALSE;
}

?>
