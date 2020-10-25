package base;

/**
 * <p>タイトル: クリックストリームの表示レベルの選択プルダウンを表示する</p>
 * <p>説明: </p>
 * <p>著作権: Copyright (c) 2003</p>
 * <p>会社名: </p>
 * @author aizawa
 * @version 1.0
 */
public class LevelSelect {
  static public String getLevelSelect(String level, String name) {
    StringBuffer strbuf = new StringBuffer();

    // <select>タグ
    strbuf.append("<select name='" + name + "' onchange='change_level()'>\n");

    // 第１階層～第６階層
    for (int i = 1; i <= 6; i++) {
      strbuf.append(CommonFunc.getOptionTag(Integer.toString(i), "第" + CommonFunc.toFullANS(Integer.toString(i)) + "階層まで", level));
    }

    strbuf.append("</select>\n");

    return strbuf.toString();
  }
}