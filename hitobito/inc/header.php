<?php
/**
 * ひとびと･net 事務局ページ
 *
 * ヘッダ/フッタ情報クラス
 *
 *
 * @package
 * @author
 * @version
 */

define('BACK_LOGOFF', 0);
define('BACK_TOP', 1);
define('BACK_CLOSE', 2);
define('BACK_NONE', 3);

class header_class {
	protected $class;
	protected $section;
	protected $title;
	protected $back;

	// ■コンストラクタ
	public function __construct($title, $back) {
		global $top, $page_kind;

		$this->class = 'hitobito';
		$this->section = $section;
		$this->title = $title;
		$this->back = $back;

		// セクション名を取得
		$section = array();
		for ($dir = "$top/"; strlen($dir) > 1; $dir = substr($dir, 0, strlen($dir) - 3)) {
			$file = $dir . 'section.php';
			if (file_exists($file))
				include($file);
		}
		$this->section = join('｜', $section);
	}

	// ■htmlヘッダ
	public function html_header() {
		global $top;
?>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<meta http-equiv="Content-Style-Type" content="text/css">
<title><?=$this->title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/hitobito.css">
<?php
	}

	// ■ページヘッダ
	public function page_header() {
?>
<table cellspacing=0 cellpadding=0 width="100%">
	<tr>
		<td align="left" style="font-size:x-small;font-weight:bold;color:#666666"><?=$this->section?></td>
		<td align="right" valign=bottom norap><?=$this->back_link()?></td>
	</tr>
</table>
<table border=0 width="100%" cellspacing=1 cellpadding=3 class="head">
	<tr onclick="location.reload()">
		<td align="center"><nobr><?=$this->title?></nobr></td>
	</tr>
</table>
<hr class="headfood">
<div align="center">
<?php
	}

	// ■ページフッタ
	public function page_footer() {
		if (isset($_SESSION['login_type']))
			$login = "Login: " . $_SESSION['login_mail_addr'];
?>
</div>
<hr class="headfood">
<table cellspacing=0 cellpadding=0 width="100%">
	<tr>
		<td align="left" style="font-size:x-small;font-weight:bold;color:#666666"><?=$login?></td>
		<td align="right" valign=bottom norap><?=$this->back_link()?></td>
	</tr>
</table>
<div align="center">Copyright(c) <?=date('Y')?> xx xxxxx Inc. All rights reserved.</div>
<?php
	}

	// ■戻りリンク
	protected function back_link() {
		global $top;

		switch ($this->back) {
		case BACK_LOGOFF:
			$back = "<small><font color=blue>■</font><a href=\"$top/login/logoff.php\">ログオフ</a></small>";
			break;
		case BACK_TOP:
			$back = "<small><font color=blue>■</font><a href=\"$top/top.php\">トップへ戻る</a></small>";
			break;
		case BACK_CLOSE:
			$back = "<small><font color=blue>■</font><a href=\"javascript:window.close()\">閉じる</a></small>";
			break;
		default:
			$back = '';
			break;
		}

		return $back;
	}
}
?>