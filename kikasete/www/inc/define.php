<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:定数定義
'******************************************************/

define('MUST_ITEM', '<sup><font color="green">*</font></sup>');
define('LIST_TABLE', 'border=1 cellspacing=0 cellpadding=1 bordercolorlight="#f0f0f0" frame="box"');

// 戻り先
define('BACK_NONE', 0);
define('BACK_LOGOFF', 1);
define('BACK_TOP', 2);
define('BACK_CLOSE', 3);

// 登場人物属性
define('PS_MONITOR_VISITOR', 0);		// ビジター（モニター）
define('PS_MONITOR', 1);						// モニター
define('PS_MARKETING_PARTNER', 2);	// リサーチパートナー
define('PS_MARKETER_VISITOR', 3);		// ビジター（マーケター）
define('PS_MARKETER_MAILNEWS', 4);	// メールニュース購読者
define('PS_MARKETER_FREE', 5);			// マーケター（無料）
define('PS_MARKETER_PAY', 6);				// マーケター（有料）
define('PS_MARKETER_GUEST', 7);			// ゲスト（有料登録者の）
define('PS_MARKETER_ASSISTANT', 8);	// アシスタント
define('PS_AGENT', 9);							// エージェント
define('PS_AGENT_SUB', 10);					// エージェント（サブ）
define('PS_ADMIN', 11);							// 事務局

// システムエラー処理
function system_error($msg, $fname) {
	global $top;
	redirect("$top/../common/system_error.php");
}
?>