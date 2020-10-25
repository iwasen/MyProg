<?
/******************************************************
' System :Eco-footprint 管理用ページ
' Content:トップメニュー画面
'******************************************************/

$top = '.';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");

set_global('ecopri', '', 'xxxゾウさんClub 管理メニュー', BACK_LOGOFF);

?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body>

<? page_header() ?>
<table align="center" border=0 cellpadding=8 cellspacing=0 width="90%">
	<tr>
		<td>
			<table width="100%" <?=LIST_TABLE?>>
				<tr><th class="member" colspan=3 align="left">■会員情報管理</th></tr>
				<tr>
					<td width="33%"><a href="member/personal/find.php">会員個人検索</a></td>
					<td width="33%"><a href="member/search/condition.php">会員数検索</a></td>
					<td><a href="member/input/list.php">会員データ入力状況</a></td>
				</tr>
				<tr>
					<td><a href="member/msg/menu.php">会員トップページ設定</a></td>
					<td><a href="member/all_mail/list.php">全配信メール設定</a></td>
					<td width="33%"><a href="member/close_mail/list.php">完了督促メール配信設定</a></td>
				</tr>
				<tr>
					<td width="33%"><a href="member/login/list.php">会員ログイン履歴</a></td>
					<td width="33%"><a href="member/taikai_list/list.php">退会者リスト</a></td>
					<td><a href=""><br></a></td>
<!--
					<td><a href=""><a href="member/ganbalist/list.php">ガンバリスト利用状況</a></a></td>
-->				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" <?=LIST_TABLE?>>
				<tr><th class="rival" colspan=3 align="left">■ライバル設定管理</a></th></tr>
				<tr>
					<td width="33%"><a href=""><br></a></td>
					<td width="33%"><a href=""><br></a></td>
					<td width="33%"><a href=""><br></a></td>
				</tr>
				<tr>
					<td><a href=""><br></a></td>
					<td><a href=""><br></a></td>
					<td><a href=""><br></a></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" <?=LIST_TABLE?>>
				<tr><th class="point" colspan=3 align="left">■ポイント管理</th></tr>
				<tr>
					<td width="33%"><a href=""><br></a></td>
					<td width="33%"><a href=""><br></a></td>
					<td width="33%"><a href=""><br></a></td>
				</tr>
				<tr>
					<td><a href=""><br></a></td>
					<td><a href=""><br></a></td>
					<td><a href=""><br></a></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" <?=LIST_TABLE?>>
				<tr><th class="master" colspan=3 align="left">■マスタ管理</th></tr>
				<tr>
					<td width="33%"><a href="master/area/menu.php">地域マスタ</a></td>
					<td width="33%"><a href="master/life/menu.php">住居関連マスタ</a></td>
					<td width="33%"><a href=""><br></a></td>
				</tr>
				<tr>
					<td><a href=""><br></a></td>
					<td><a href=""><br></a></td>
					<td><a href=""><br></a></td>
				</tr>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" <?=LIST_TABLE?>>
				<tr><th class="etc" colspan=3 align="left">■その他管理</th></tr>
					<td width="33%"><a href="etc/message/list.php">Ecoゾウさんからのお知らせ設定</a></td>
					<td width="33%"><a href="etc/mail_template/list.php">送信メールテンプレート</a></td>
					<td width="33%"><a href="etc/click/list.php">CD-ROMクリック履歴</a></td>
				</tr>
				<tr>
					<td><a href="etc/present/list.php">プレゼント管理</a></td>
					<td><a href=""><br></a></td>
					<td><a href=""><br></a></td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<? page_footer() ?>

</body>
</html>
