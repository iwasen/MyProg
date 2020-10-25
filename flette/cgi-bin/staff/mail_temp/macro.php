#!/usr/local/bin/php4
<?
/******************************************************
' System :fletteスタッフページ
' Content:差し込みマクロ一覧画面
'******************************************************/

?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>flette</title>
<style TYPE="text/css">
<!--
td { white-space: nowrap; }
.header { background-color: #6699ff; color:#ffffff; }
.macro { background-color: #ccecff; }
-->
</style>
</head>
<body>

<table border=0 cellspacing=0 cellpadding=0>
	<tr>
		<td>■差込マクロ文字列一覧</td>
		<td align="right"><font size="-1"><a href="javascript:window.close()">閉じる</a></font></td>
	</tr>
	<tr>
		<td colspan=2>
			<table border=1 cellspacing=0 cellpadding=2>
				<tr class="header">
					<th>差し込まれる内容</th>
					<th>差込マクロ文字列</th>
				</tr>
				<tr>
					<td class="macro">予約申し込み連番</td>
					<td >%SEQ%</td>
				</tr>
					<td class="macro">予約申し込み日時</td>
					<td >%DATE%</td>
				</tr>
				<tr>
					<td class="macro">申し込みコース</td>
					<td >%COURSE%</td>
				</tr>
				<tr>
					<td class="macro">申し込み店舗</td>
					<td >%SHOP%</td>
				</tr>
				<tr>
					<td class="macro">第1希望部屋</td>
					<td >%CHOICE_ROOM_1%</td>
				</tr>
				<tr>
					<td class="macro">第1希望日時</td>
					<td >%CHOICE_DATE_1%</td>
				</tr>
				<tr>
					<td class="macro">第1希望カウンセラー性別</td>
					<td >%CHOICE_SEX_1%</td>
				</tr>
				<tr>
				<tr>
					<td class="macro">第2希望部屋</td>
					<td >%CHOICE_ROOM_2%</td>
				</tr>
				<tr>
					<td class="macro">第2希望日時</td>
					<td >%CHOICE_DATE_2%</td>
				</tr>
				<tr>
					<td class="macro">第2希望カウンセラー性別</td>
					<td >%CHOICE_SEX_2%</td>
				</tr>
				<tr>
					<td class="macro">第3希望部屋</td>
					<td >%CHOICE_ROOM_3%</td>
				</tr>
				<tr>
					<td class="macro">第3希望日時</td>
					<td >%CHOICE_DATE_3%</td>
				</tr>
				<tr>
					<td class="macro">第3希望カウンセラー性別	</td>
					<td >%CHOICE_SEX_3%</td>
				</tr>
				<tr>
					<td class="macro">申込者姓</td>
					<td >%NAME_1%</td>
				</tr>
				<tr>
					<td class="macro">申込者名</td>
					<td >%NAME_2%</td>
				</tr>
				<tr>
					<td class="macro">申込者姓カナ</td>
					<td >%KANA_1%</td>
				</tr>
				<tr>
					<td class="macro">申込者名カナ</td>
					<td >%KANA_2%</td>
				</tr>
				<tr>
					<td class="macro">申込者メールアドレス</td>
					<td >%MAIL_ADDR%</td>
				</tr>
				<tr>
					<td class="macro">希望連絡先</td>
					<td >%CONTACT_POINT%</td>
				</tr>
				<tr>
					<td class="macro">希望連絡先（指定無しは改行）</td>
					<td >%CONTACT_POINT_2%</td>
				</tr>
				<tr>
					<td class="macro">連絡希望時間帯</td>
					<td >%CONTACT_TIME%</td>
				</tr>
				<tr>
					<td class="macro">連絡希望時間帯メモ</td>
					<td >%CONTACT_TIME_MEMO%</td>
				</tr>
<!--
				<tr>
					<td class="macro">生年月日</td>
					<td >%BIRTHDAY%</td>
				</tr>
-->
				<tr>
					<td class="macro">相談内容</td>
					<td >%DATA%</td>
				</tr>
				<tr>
					<td class="macro">相談内容（指定無しは改行）</td>
					<td >%DATA_2%</td>
				</tr>
				<tr>
					<td class="macro">カウンセラー変更希望</td>
					<td >%CHANGE%</td>
				</tr>
				<tr>
					<td class="macro">その他ご要望など</td>
					<td >%COMMENT%</td>
				</tr>
				<tr>
					<td class="macro">その他ご要望など（改行無し）</td>
					<td >%COMMENT_2%</td>
				</tr>
				<tr>
					<td class="macro">全データをCSV形式に接続</td>
					<td >%CSV%</td>
				</tr>
				<tr>
					<td class="macro">お問い合わせの件名</td>
					<td >%INQUIRY_SUBJECT%</td>
				</tr>
				<tr>
					<td class="macro">お問い合わせの内容</td>
					<td >%INQUIRY_CONTENT%</td>
				</tr>
				<tr>
					<td class="macro">問い合わせ者氏名</td>
					<td >%INQUIRY_NAME%</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</body>
</html>
