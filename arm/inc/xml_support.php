<?
/******************************************************
* system  : xxx リスク診断
* title   : XMLサポート関数
* content : リクエスト／レスポンス用XMLを処理する関数群
* version : 1.0
* author  : aizawa
*******************************************************/

// ■EUCからUTFにコード変換
function toUTF($str) {
	return mb_convert_encoding(trim($str), 'UTF-8', 'EUC-JP');
}

// ■UTFからEUCにコード変換
function toEUC($str) {
	return trim(mb_convert_encoding($str, 'EUC-JP', 'UTF-8'));
}

// ■DOMから配列へデータコピー
function dom_to_array(&$node, &$ary) {
	for ($child_node = $node->first_child(); is_object($child_node); $child_node = $child_node->next_sibling()) {
		switch ($child_node->node_type()) {
		case XML_ELEMENT_NODE:
			$name = toEUC($child_node->node_name());
			if ($child_node->has_child_nodes()) {
				$ary[$name] = array();
				dom_to_array($child_node, $ary[$name]);
			}
			break;
		case XML_TEXT_NODE:
			$text = toEUC($child_node->get_content());
			if ($text != '')
				$ary = toEUC($text);
			break;
		}
	}
}

// ■配列からDOMへデータコピー
function array_to_dom(&$dom, &$node, &$ary) {
	if (is_array($ary)) {
		foreach ($ary as $tag => $data) {
			$new_node = $dom->create_element(toUTF($tag));
			$node->append_child($new_node);
			if (is_array($data))
				array_to_dom($dom, $new_node, $data);
			elseif (is_object($data)) {
				if (isset($data->attr)) {
					foreach ($data->attr as $key => $val)
						$new_node->set_attribute(toUTF($key), toUTF($val));
				}
				if (isset($data->text))
					$new_node->set_content(toUTF($data->text));
				if (isset($data->child))
					array_to_dom($dom, $new_node, $data->child);
			} else
				$new_node->set_content(toUTF($data));
		}
	}
}
?>