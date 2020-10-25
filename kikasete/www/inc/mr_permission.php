<?
/******************************************************
' System :きかせて・net共通
' Content:マーケター権限
'******************************************************/

define('PMR_MY_ENQUETE', 0);
define('PMR_MY_MONITOR', 1);
define('PMR_MY_SEARCH', 2);
define('PMR_MY_AGENT', 3);
define('PMR_ENQ_RESULT', 4);
define('PMR_REGIST_INF', 5);
define('PMR_ASSISTANT', 6);
define('PMR_CHG_AGENT', 7);

define('PMR_DEF_MARKETER', '1111111111');
define('PMR_DEF_ASSISTANT', '1111100000');

define('PMR_LEN', 10);

function check_permission($permission) {
	return $_SESSION['ss_permission']{$permission} == '1';
}

function input_permission($permission) {
?>
<input type="checkbox" name="pmr[]" value="<?=PMR_MY_ENQUETE?>" <?=checked($permission{PMR_MY_ENQUETE})?>>Ｍｙアンケート・Ｐｒｏアンケートの作成・申請
<br><input type="checkbox" name="pmr[]" value="<?=PMR_ENQ_RESULT?>" <?=checked($permission{PMR_ENQ_RESULT})?>>過去＆現在実施のアンケート詳細閲覧・ダウンロード
<br><input type="checkbox" name="pmr[]" value="<?=PMR_MY_AGENT?>" <?=checked($permission{PMR_MY_AGENT})?>>エージェントへの問い合わせ
<br><input type="checkbox" name="pmr[]" value="<?=PMR_MY_MONITOR?>" <?=checked($permission{PMR_MY_MONITOR})?>>Ｍｙパートナーの実施
<br><input type="checkbox" name="pmr[]" value="<?=PMR_MY_SEARCH?>" <?=checked($permission{PMR_MY_SEARCH})?>>Ｍｙサーチの設定・更新・検索
<?
}

function show_permission($permission) {
	if ($permission{PMR_MY_ENQUETE})
		echo 'Ｍｙアンケート・Ｐｒｏアンケートの作成・申請<br>';
	if ($permission{PMR_ENQ_RESULT})
		echo '過去＆現在実施のアンケート詳細閲覧・ダウンロード<br>';
	if ($permission{PMR_MY_AGENT})
		echo 'エージェントへの問い合わせ<br>';
	if ($permission{PMR_MY_MONITOR})
		echo 'Ｍｙパートナーの実施<br>';
	if ($permission{PMR_MY_SEARCH})
		echo 'Ｍｙサーチの設定・更新・検索<br>';
}
?>