package base;

/**
 * <p>�^�C�g��: �N���b�N�X�g���[���̕\�����x���̑I���v���_�E����\������</p>
 * <p>����: </p>
 * <p>���쌠: Copyright (c) 2003</p>
 * <p>��Ж�: </p>
 * @author aizawa
 * @version 1.0
 */
public class LevelSelect {
  static public String getLevelSelect(String level, String name) {
    StringBuffer strbuf = new StringBuffer();

    // <select>�^�O
    strbuf.append("<select name='" + name + "' onchange='change_level()'>\n");

    // ��P�K�w�`��U�K�w
    for (int i = 1; i <= 6; i++) {
      strbuf.append(CommonFunc.getOptionTag(Integer.toString(i), "��" + CommonFunc.toFullANS(Integer.toString(i)) + "�K�w�܂�", level));
    }

    strbuf.append("</select>\n");

    return strbuf.toString();
  }
}