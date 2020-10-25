<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:トップメニュー画面
'******************************************************/

$top = '.';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");

set_global('kikasete', '', 'きかせて・net 事務局メニュー', BACK_LOGOFF);

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
					<td width="33%"><a href="monitor/profile/show.php">モニタープロファイル</a></td>
					<td width="33%"><a href="monitor/search/condition.php">モニター数検索</a></td>
				</tr>
				<tr>
					<td><a href="monitor/news/list.php">メールニュース配信</a></td>
					<td><a href="monitor/regist/show.php">入退会者集計</a></td>
					<td><a href="monitor/promotion/list.php">プロモーション管理</a></td>
				</tr>
				<tr>
					<td><a href="monitor/undeliver/list.php">不達メール管理</a></td>
					<td><a href="monitor/blacklist/list.php">ブラックリスト管理</a></td>
					<td><a href="monitor/enquete/list.php">１万人アンケート管理</a></td>
				</tr>
				<tr>
					<td><a href="monitor/point/menu.php">ポイント入出力</a></td>
					<td><a href="monitor/affiliate/list.php">アフィリエイト管理</a></td>
					<td><a href="monitor/ma_profile/list.php">プロファイル手動追加</a></td>
				</tr>
				<tr>
					<td><a href="monitor/birthday/list.php">誕生日メール配信</a></td>
					<td></td>
					<td></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" <?=LIST_TABLE?>>
				<tr><th class="marketer" colspan=3 align="left">■マーケター管理</a></th></tr>
				<tr>
					<td width="33%"><a href="marketer/personal/find.php">マーケター個人検索</a></td>
					<td width="33%"><a href="marketer/login/list.php">ログイン履歴</a></td>
					<td width="33%"><a href="marketer/ag_change/list.php">エージェント変更</a></td>
				</tr>
				<tr>
					<td><a href="marketer/news/list.php">メールニュース配信</a></td>
					<td><a href="marketer/regist/show.php">入退会者集計</a></td>
					<td><a href="marketer/mailaddr/list.php">メールアドレス発行</a></td>
				</tr>
				<tr>
          <td><a href="marketer/undeliver/list.php">不達メール管理</a></td>
          <td><a href="marketer/proxy_regist/new.php">有料マーケター代理登録</a></td>
					<td><a href="marketer/takeover/edit.php">サービスの引継ぎ</a></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" <?=LIST_TABLE?>>
				<tr><th class="myenquete" colspan=3 align="left">■Myアンケート管理</a></th></tr>
				<tr>
					<td width="33%"><a href="marketer/myenq/list.php">Myアンケート</a></td>
					<td width="33%"><a href="marketer/myenq/send_sum/index.php">発信数集計</a></td>
					<td width="33%"><a href="marketer/panel/category_list.php">スペシャルパネル管理</a></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" <?=LIST_TABLE?>>
				<tr><th class="proenquete" colspan=3 align="left">■PROアンケート管理</a></th></tr>
				<tr>
					<td width="33%"><a href="proenq/manage/list.php">Proアンケート</a></td>
					<td width="33%"><a href="proenq/price/list.php">料金表管理</a></td>
					<td width="33%"><a href="proenq/send_sum/index.php">発信数集計</a></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" <?=LIST_TABLE?>>
				<tr><th class="mypartner" colspan=3 align="left">■Myパートナー管理</th></tr>
				<tr>
					<td width="33%"><a href="mypartner/pjt/pjt_list.php">Myパートナー管理</a></td>
					<td width="33%"><a href="mypartner/pjt/active.php">Myパートナー進行状況</a></td>
					<td width="33%"><a href="mypartner/approval/list.php">Myパートナー承認処理</a></td>
				</tr>
				<tr>
					<td width="33%"><a href="mypartner/pjt/search.php">Myパートナー履歴管理</a></td>
					<td width="33%"><a href="mypartner/master/list.php">追加発信判断マスタ管理</a></td>
					<td width="33%"><a href="mypartner/send_sum/index.php">発信数集計</a></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" <?=LIST_TABLE?>>
				<tr><th class="agent" colspan=3 align="left">■エージェント管理</th></tr>
				<tr>
					<td width="33%"><a href="agent/personal/find.php">エージェント個人検索</a></td>
					<td width="33%"><a href="agent/login/list.php">ログイン履歴</a></td>
					<td width="33%"><a href="agent/alert/list.php">アラートメール</a></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" <?=LIST_TABLE?>>
				<tr><th class="project" colspan=3 align="left">■プロジェクト管理</th></tr>
				<tr>
					<td width="33%"><a href="project/list2.php">プロジェクト進行状況</a></td>
					<td width="33%"><a href="project/list.php">プロジェクト管理</a></td>
					<td width="33%"><a href="project/client/list.php">企業／担当者登録</a></td>
				</tr>
				<tr>
					<td width="33%"><a href="project/approval/list.php">ジョブ承認処理</a></td>
					<td width="33%"><a href="project/schedule/index.php">スケジュール</a></td>
					<td><a href="project/send_sum/index.php">発信数集計</a></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" <?=LIST_TABLE?>>
				<tr><th class="etc" colspan=3 align="left">■その他管理</th></tr>
					<td width="33%"><a href="etc/point/list.php ">ポイント精算</a></td>
					<td width="33%"><a href="etc/send_all/list.php">全配信</a></td>
					<td width="33%"><a href="etc/comm/list.php">メッセージ送受信履歴</a></td>
				</tr>
				<tr>
					<td><a href="etc/message/list.php">事務局からのお知らせ</a></td>
					<td><a href="etc/master/menu.php">マスタメンテナンス</a></td>
					<td><a href="etc/batch_log/list.php">バッチログ</a></td>
				</tr>
				<tr>
					<td><a href="etc/send_sum/index.php">全発信数集計</a></td>
					<td></td>
					<td></td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<? page_footer() ?>

</body>
</html>
