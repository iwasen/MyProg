<?
/******************************************************
' System :きかせて・net共通
' Content:デコード処理
'******************************************************/

// adminステータス
function decode_admin_status($code, $def='設定なし') {
	switch ($code) {
	case 0:
		return '有効';
	case 9:
		return '無効';
	}
	return $def;
}

// モニター種別
function decode_monitor_type($code, $def='設定なし') {
	switch ($code) {
	case 1:
		return 'モニター';
	case 2:
		return 'Ｍパートナー';
	}
	return $def;
}

// モニター状態
function decode_monitor_status($code, $def='設定なし') {
	switch ($code) {
	case 0:
		return '有効';
	case 2:
		return '不達';
	case 8:
		return 'テスト';
	case 9:
		return '退会';
	}
	return $def;
}

// マーケター種別
function decode_marketer_type($code, $def='設定なし') {
	switch ($code) {
	case 1:
		return 'メールニュース登録';
	case 2:
		return '無料登録';
	case 3:
		return 'Myページ登録';
	case 4:
		return 'アシスタント';
	}
	return $def;
}

// マーケター状態
function decode_marketer_status($code, $def='設定なし') {
	switch ($code) {
	case 0:
		return '有効';
	case 2:
		return '不達状態';
	case 8:
		return 'テスト';
	case 9:
		return '退会';
	}
	return $def;
}

// 性別
function decode_sex($code, $def='設定なし') {
	switch ($code) {
	case 1:
		return '男性';
	case 2:
		return '女性';
	}
	return $def;
}

// 未既婚
function decode_mikikon($code, $def='設定なし') {
	switch ($code) {
	case 1:
		return '未婚';
	case 2:
		return '既婚';
	case 3:
		return 'その他';
	}
	
	return $def;
}

// AND/OR
function decode_andor($code, $def='設定なし') {
	switch ($code) {
	case 'A':
		return 'AND';
	case 'O':
		return 'OR';
	}
	return $def;
}

// 地域
function decode_area($code) {
	if ($code != '') {
		$sql = "SELECT ar_area_name FROM m_area WHERE ar_area_cd=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}

// 職業
function decode_shokugyou($code, $name='設定なし') {
	if ($code != '') {
		$sql = "SELECT sg_shokugyou_name FROM m_shokugyou WHERE sg_shokugyou_cd=$code";
		$name = db_fetch1($sql, $def);
	}
	return $name;
}

// 業種
function decode_gyoushu($code) {
	if ($code != '') {
		$sql = "SELECT gs_gyoushu_name FROM m_gyoushu WHERE gs_gyoushu_cd=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}

// 職種
function decode_shokushu($code) {
	if ($code != '') {
		$sql = "SELECT ss_shokushu_name FROM m_shokushu WHERE ss_shokushu_cd=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}

// エージェント
function decode_agent($code) {
	if ($code != '') {
		$sql = "SELECT ag_name1 || ' ' || ag_name2 FROM t_agent WHERE ag_agent_id=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}

// 家族続柄
function decode_family_rel($code) {
	if ($code != '') {
		$sql = "SELECT fr_name FROM m_family_rel WHERE fr_family_rel_cd=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}

// 登録状態
function decode_regist($code, $def='不明') {
	switch ($code) {
	case 1:
		return '未登録';
	case 2:
		return '登録済み';
	}
	return $def;
}

// アンケート種別
function decode_enq_type($code, $def='不明') {
	switch ($code) {
	case '0':
		return 'アンケート無し';
	case '1':
		return 'メールアンケート';
	case '2':
		return 'Ｗｅｂアンケート';
	}
	return $def;
}

// アンケート種別（短縮）
function decode_enq_type2($code, $def='不明') {
	switch ($code) {
	case '0':
		return '無し';
	case '1':
		return 'メール';
	case '2':
		return 'Ｗｅｂ';
	}
	return $def;
}
// jeon_start seq=77
function decode_enq_type3($code,$kind, $def='不明') {
	if($code=='1'&& $kind=='5')return 'メールアンケート（ファインディングあり）';
	else if($code=='1'&& $kind=='6')return 'メールアンケート（本アンケートのみ）';
	else if($code=='2'&& $kind=='5')return 'Webアンケート（ファインディングあり）';
	else if($code=='2'&& $kind=='6')return 'Webアンケート（本アンケートのみ）';
	else return 'アンケート無し';	
}

//jeon_end seq=77
// アンケート状態
/* 修正前
function decode_enq_status($code, $def='不明') {
    switch ($code) {
    case 0:
        return '作成中';
    case 1:
        return '作成完了';
    case 2:
        return '申請中';
    case 3:
        return '事務局チェック中';
    case 4:
        return '承認済み';
    case 5:
        return '実施中';
    case 6:
        return '一時停止';
    case 7:
        return '終了';
    case 8:
        return '再申請';
    case 9:
        return '削除済み';
    }
    return $def;
}
*/
// 修正後
// edited by kthink - delete me
// サブステータス名称対応
function decode_enq_status($code, $def='不明', $sub_status=-1) {
    switch ($code) {
    case 0:
        return '作成中';
    case 1:
        return '作成完了';
    case 2:
        return '申請中';
    case 3:
        if ( $sub_status == 1 )
            return '事務局確認中';
        elseif ( $sub_status == 2 )
            return 'データ格納確認中';
        elseif ( $sub_status == 3 )
            return '事務局承認待ち';

        return '事務局チェック中';
    case 4:
        return '承認済み';
    case 5:
        return '実施中';
    case 6:
        return '一時停止';
    case 7:
        return '終了';
    case 8:
        return '再申請';
    case 9:
        return '削除済み';
    }
    return $def;
}

// 交換商品
function decode_seisan_item($code) {
	switch ($code) {
	case 1:
		return 'VISAギフト券';
	case 2:
		return '図書券';	
	case 3:
		return 'WebMoney';
	//jeon_start seq=ad1	
	case 4:
		return '楽天銀行';
	//jeon_start seq=ad1		
	case 5:
		return '義援金';
	}
	return '不明';
}

// 子供の有無
function decode_have_child($code, $def='設定なし') {
	switch ($code) {
	case 1:
		return '有り';
	case 2:
		return '無し';
	}
	return $def;
}

// 住居形態
function decode_housing_form($code, $def='設定なし') {
	switch ($code) {
	case 1:
		return '一戸建て';
	case 2:
		return '集合住宅';
	}
	return $def;
}

// 車の所有
function decode_have_car($code, $def='設定なし') {
	switch ($code) {
	case 1:
		return '免許と車を所有している';
	case 2:
		return '免許のみ所有している';
	case 3:
		return '免許は所有していない';
	}
	return $def;
}

// 複数一括デコード
function multi_decode($decode_func, $codes, $def = '', $separator = '　') {
	foreach (explode(',', $codes) as $code)
		$ary[] = $decode_func($code, $def);

	return join($separator, $ary);
}

// -----------SEQ82 START 2006/06/23 by CHOI-------------------
// 通常/Not条件
function decode_not_cond($code) {
	switch ($code) {
	case 'f':
		return '通常';
	case 't':
		return 'Not条件';
	}
	return '';
}
// -----------SEQ82 END 2006/06/23 by CHOI----------------------

// AND/OR
function decode_and_or($code) {
	switch ($code) {
	case 'A':
		return 'AND';
	case 'O':
		return 'OR';
	case 'F':
		return '完全一致';
	}
	return '';
}

// 配信カテゴリ
function decode_job_category($code) {
	switch ($code) {
	case 1:
		return 'きかせて';
	case 2:
		return 'はいめーる';
	}
	return '';
}

// HTMLメール
function decode_html_mail($code) {
	switch ($code) {
	case DBTRUE:
		return '受信する';
	case DBFALSE:
		return '受信しない';
	}
	return '';
}

// はいめーる受信フラグ
function decode_haimail_flag($code) {
	switch ($code) {
	case DBTRUE:
		return '受信する';
	case DBFALSE:
		return '受信しない';
	}
	return '';
}

// 配偶者の有無
function decode_spouse_flg($code, $def='設定なし') {
	switch ($code) {
	case 1:
		return '有り';
	case 2:
		return '無し';
	case 3:
		return 'その他';
	}
	return $def;
}

// シニア層の有無
function decode_senior_flg($code, $def='設定なし') {
	switch ($code) {
	case 1:
		return '有り';
	case 2:
		return '無し';
	}
	return $def;
}

// 子供の性別
function decode_child_sex($code, $def='設定なし') {
	switch ($code) {
	case 1:
		return '男子';
	case 2:
		return '女子';
	}
	return $def;
}

// 子供の学年・職業
function decode_child_gakunen($code, $name='設定なし') {
	if ($code != '') {
		$sql = "SELECT gk_name FROM m_gakunen WHERE gk_gakunen_cd=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}

// 質問タイプデコード
function decode_question_type($code) {
	switch ($code) {
	case 1:
		return 'ラジオボタン（単一回答）';
	case 2:
		return 'チェックボックス（複数回答）';
	case 3:
		return 'フリー回答（長文）';
	case 4:
		return 'マトリクス（単一回答）';
	case 5:
		return 'マトリクス（複数回答）';
	case 6:
		return '数値回答';
	case 7:
		return 'プルダウン（単一回答）';
	case 8:
		return 'フリー回答（一行）';
	}
}

// 質問タイプデコード（省略形）
function decode_question_type2($code) {
	switch ($code) {
	case 1:
		return 'SA';
	case 2:
		return 'MA';
	case 3:
		return 'FA';
	case 4:
		return 'MatrixSA';
	case 5:
		return 'MatrixMA';
	case 6:
		return 'NA';
	case 7:
		return 'SA';
	case 8:
		return 'FA';
	}
}
?>