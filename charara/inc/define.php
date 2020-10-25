<?
/******************************************************
' System :「きゃららFactory」インクルードファイル
' Content:定数定義
'******************************************************/

define('MUST_ITEM', '<sup><font color="green">*</font></sup>');
define('LIST_TABLE', 'border=1 cellspacing=0 cellpadding=1 bordercolorlight="#f0f0f0" frame="box"');

// 戻り先
define('BACK_NONE', 0);
define('BACK_LOGOFF', 1);
define('BACK_TOP', 2);
define('BACK_CLOSE', 3);

// システムエラー処理
function system_error($msg, $fname) {
	global $top;
	redirect("$top/../admin/common/system_error.php");
}
?>