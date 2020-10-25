<?
/******************************************************
' System :きかせて・net共通
' Content:差し込み文字処理
'******************************************************/

// 興味ジャンルを差し込み
function replace_kyoumi_genre($mail_format, $search_id, &$body) {
	$replace = '';
	$prefix = ':　　';

	if ($search_id) {
		$pos = strpos($body, '%KYOUMI_GENRE%');
		if ($pos !== false) {
			$sql = "SELECT sr_genre FROM t_search WHERE sr_search_id=$search_id";
			$genre = db_fetch1($sql);
			if ($genre != '') {
				$sql = "SELECT kg_name FROM m_genre WHERE kg_genre_cd IN ($genre) ORDER BY kg_order";
				$result = db_exec($sql);
				$nrow = pg_numrows($result);
				for ($i = 0; $i < $nrow; $i++) {
					$fetch = pg_fetch_row($result, $i);
					$genre_ary[] = "「{$fetch[0]}」";
				}

				if ($mail_format == 0) {
					$pos -= strrpos($body, "\n", $pos - strlen($body));
					foreach ($genre_ary as $genre_text) {
						if ($pos + strlen($genre_text) > 72) {
							$replace .= "\r\n$prefix";
							$pos = strlen($prefix);
						}
						$replace .= $genre_text;
						$pos += strlen($genre_text);
					}
				} else {
					$replace = implode('', $genre_ary);
				}
			}
		}
	}

	$body = str_replace('%KYOUMI_GENRE%', $replace, $body);
}

?>