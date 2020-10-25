<?
/******************************************************
' System :お酒と買物のアンケートモニター事務局用ページ
' Content:トップメニュー画面
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

set_global('menu', '', 'お酒と買物のアンケートモニター 事務局メニュー', BACK_LOGOFF);

// 戻り先設定
set_return_page('active.php');
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
				<tr><th class="monitor" colspan=3 align="left">■モニター管理</th></tr>
				<tr>
					<td width="33%"><a href="monitor/personal/find.php">モニター個人検索</a></td>
					<td width="33%"><a href="monitor/mail/menu.php">条件指定メール配信</a></td>
					<td width="33%"></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" <?=LIST_TABLE?>>
				<tr><th class="staff" colspan=3 align="left">■スタッフ管理</a></th></tr>
				<tr>
					<td width="33%"><a href="staff/personal/find.php">スタッフ個人検索</a></td>
					<td width="33%"><a href="staff/login/list.php">ログイン履歴</a></td>
					<td width="33%"><a href="staff/myenq/list.php">Myアンケート管理</a></td>
				</tr>
				<tr>
          <td><a href="staff/regist/new.php">スタッフ登録</a></td>
          <td><a href="staff/csv/list.php">CSVダウンロード</a></td>
					<td></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" <?=LIST_TABLE?>>
				<tr><th class="client" colspan=3 align="left">■クライアント管理</a></th></tr>
				<tr>
          <td width="33%"><a href="client/regist/list.php">クライアント登録</a></td>
					<td width="33%"><a href="client/login/list.php">ログイン履歴</a></td>
					<td width="33%"></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" <?=LIST_TABLE?>>
				<tr><th class="etc" colspan=3 align="left">■その他管理</th></tr>
					<td width="33%"><a href="etc/point/list.php ">ポイント情報出力</a></td>
					<td width="33%"><a href="etc/send_all/list.php">全配信</a></td>
					<td width="33%"><a href="etc/report/list.php">調査結果アップロード</a></td>
				</tr>
				<tr>
					<td><a href="etc/master/menu.php">マスタメンテナンス</a></td>
					<td><a href="etc/batch_log/list.php">バッチログ</a></td>
					<td></td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<? page_footer() ?>

</body>
</html>
