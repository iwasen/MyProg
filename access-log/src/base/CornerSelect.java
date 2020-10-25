package base;

import java.sql.*;

/**
 * <p>�^�C�g��: �R�[�i�[�̑I�����X�g��\������</p>
 * <p>����: </p>
 * <p>���쌠: Copyright (c) 2003</p>
 * <p>��Ж�: </p>
 * @author aizawa
 * @version 1.0
 */

public class CornerSelect {
  // �w��R�[�i�[�I��p�v���_�E��
  static public String getTargetCornerSelect(DataBase db, SelectCondBean selectCondBean, String name) throws SQLException {
    StringBuffer strbuf = new StringBuffer();
    Statement st = db.createStatement();
    StringBuffer sql;

    // �R�[�i�[�R�[�h�A����R�[�h�A�T�u���[�h�擾
    String cornerCode = selectCondBean.getTargetCorner();
    String groupCode = selectCondBean.getGroup();
    String subMode = selectCondBean.getSubMode();

    // <select>�^�O
    strbuf.append("<select name='" + name + "' onchange='document.form1.submit()'>\n");

    // "�I�����Ă�������"�ǉ�
    strbuf.append("<option value=''>�I�����Ă�������</option>\n");

    // ���Ԏ擾�ΏۃR�[�i�[�̈ꗗ�擾
    sql = new StringBuffer();
    sql.append("select MGC_CORNER_CODE,NVL(MCN_CORNERNAME_TXT,MGC_CORNER_CODE) as MCN_CORNERNAME_TXT \n");
    sql.append(",to_char(MGC_STARTDH_DATE,'YYYY/MM/DD') as START_DATE \n");
    sql.append(",to_char(MGC_ENDDH_DATE,'YYYY/MM/DD') as END_DATE \n");
    sql.append("from M_CORNER,M_GETHOUR_CORNER \n");
    sql.append("where MCN_CORNER_CODE(+)=MGC_CORNER_CODE \n");
    if (subMode.equals("GROUP") && groupCode.length() > 0)
      sql.append("and MGC_GROUP_CODE='" + groupCode + "' \n");
    sql.append("order by MGC_CORNER_CODE \n");
    ResultSet rs = st.executeQuery(sql.toString());
    while (rs.next()) {
      strbuf.append(CommonFunc.getOptionTag("CORNER:" + rs.getString("MGC_CORNER_CODE"), CommonFunc.escapeHtml(rs.getString("MCN_CORNERNAME_TXT")) + " (" + rs.getString("START_DATE") + "\u301c" + rs.getString("END_DATE") + ")", cornerCode));
    }

    // ���Ԏ擾�Ώ�URL�̈ꗗ�擾
    sql = new StringBuffer();
    sql.append("select MGU_URL_TXT \n");
    sql.append(",to_char(MGU_STARTDH_DATE,'YYYY/MM/DD') as START_DATE \n");
    sql.append(",to_char(MGU_ENDDH_DATE,'YYYY/MM/DD') as END_DATE \n");
    sql.append("from M_GETHOUR_URL \n");
    if (subMode.equals("GROUP") && groupCode.length() > 0)
      sql.append("where MGU_GROUP_CODE='" + groupCode + "' \n");
    sql.append("order by MGU_URL_TXT \n");
    rs = st.executeQuery(sql.toString());
    while (rs.next()) {
      strbuf.append(CommonFunc.getOptionTag("URL:" + rs.getString("MGU_URL_TXT"), CommonFunc.escapeHtml(rs.getString("MGU_URL_TXT")) + " (" + rs.getString("START_DATE") + "\u301c" + rs.getString("END_DATE") + ")", cornerCode));
    }

    strbuf.append("</select>\n");

    return strbuf.toString();
  }

  // �J�ڌ��I��p�v���_�E��
  static public String getMoveFromCornerSelect(DataBase db, SelectCondBean selectCondBean, String name) throws SQLException {
    StringBuffer strbuf = new StringBuffer();
    Statement st = db.createStatement();
    StringBuffer sql;

    // �R�[�i�[�R�[�h�A����R�[�h�A�T�u���[�h�擾
    String cornerCode = selectCondBean.getMoveFromCorner();
    String groupCode = selectCondBean.getGroup();
    String subMode = selectCondBean.getSubMode();

    // <select>�^�O
    strbuf.append("<select name='" + name + "' onchange='document.form1.submit()'>\n");

    // "�I�����Ă�������"�ǉ�
    strbuf.append("<option value=''>�I�����Ă�������</option>\n");

    // ���Ԏ擾�ΏۃR�[�i�[�̈ꗗ�擾
    sql = new StringBuffer();
    sql.append("select MMFC_CORNER_CODE,NVL(MCN_CORNERNAME_TXT,MMFC_CORNER_CODE) as MCN_CORNERNAME_TXT \n");
    sql.append(",to_char(MMFC_STARTDATE_DATE,'YYYY/MM/DD') as START_DATE \n");
    sql.append(",to_char(MMFC_ENDDATE_DATE,'YYYY/MM/DD') as END_DATE \n");
    sql.append("from M_CORNER,M_MOVEFR_CORNER \n");
    sql.append("where MCN_CORNER_CODE(+)=MMFC_CORNER_CODE \n");
    if (subMode.equals("GROUP") && groupCode.length() > 0)
      sql.append("and MMFC_GROUP_CODE='" + groupCode + "' \n");
    sql.append("order by MCN_CORNER_CODE \n");
    ResultSet rs = st.executeQuery(sql.toString());
    while (rs.next()) {
      strbuf.append(CommonFunc.getOptionTag("CORNER:" + rs.getString("MMFC_CORNER_CODE"), CommonFunc.escapeHtml(rs.getString("MCN_CORNERNAME_TXT")) + " (" + rs.getString("START_DATE") + "\u301c" + rs.getString("END_DATE") + ")", cornerCode));
    }

    // ���Ԏ擾�Ώ�URL�̈ꗗ�擾
    sql = new StringBuffer();
    sql.append("select MMFU_URL_TXT \n");
    sql.append(",to_char(MMFU_STARTDATE_DATE,'YYYY/MM/DD') as START_DATE \n");
    sql.append(",to_char(MMFU_ENDDATE_DATE,'YYYY/MM/DD') as END_DATE \n");
    sql.append("from M_MOVEFR_URL \n");
    if (subMode.equals("GROUP") && groupCode.length() > 0)
      sql.append("where MMFU_GROUP_CODE='" + groupCode + "' \n");
    sql.append("order by MMFU_URL_TXT \n");
    rs = st.executeQuery(sql.toString());
    while (rs.next()) {
      strbuf.append(CommonFunc.getOptionTag("URL:" + rs.getString("MMFU_URL_TXT"), CommonFunc.escapeHtml(rs.getString("MMFU_URL_TXT")) + " (" + rs.getString("START_DATE") + "\u301c" + rs.getString("END_DATE") + ")", cornerCode));
    }

    strbuf.append("</select>\n");

    return strbuf.toString();
  }

  // �J�ڐ�I��p�v���_�E��
  static public String getMoveToCornerSelect(DataBase db, SelectCondBean selectCondBean, String name) throws SQLException {
    StringBuffer strbuf = new StringBuffer();
    Statement st = db.createStatement();
    StringBuffer sql;

    // �R�[�i�[�R�[�h�A����R�[�h�A�T�u���[�h�擾
    String cornerCode = selectCondBean.getMoveToCorner();
    String groupCode = selectCondBean.getGroup();
    String subMode = selectCondBean.getSubMode();

    // <select>�^�O
    strbuf.append("<select name='" + name + "' onchange='document.form1.submit()'>\n");

    // "�I�����Ă�������"�ǉ�
    strbuf.append("<option value=''>�I�����Ă�������</option>\n");

    // ���Ԏ擾�ΏۃR�[�i�[�̈ꗗ�擾
    sql = new StringBuffer();
    sql.append("select MMTC_CORNER_CODE,NVL(MCN_CORNERNAME_TXT,MMTC_CORNER_CODE) as MCN_CORNERNAME_TXT \n");
    sql.append(",to_char(MMTC_STARTDATE_DATE,'YYYY/MM/DD') as START_DATE \n");
    sql.append(",to_char(MMTC_ENDDATE_DATE,'YYYY/MM/DD') as END_DATE \n");
    sql.append("from M_CORNER,M_MOVETO_CORNER \n");
    sql.append("where MCN_CORNER_CODE(+)=MMTC_CORNER_CODE \n");
    if (subMode.equals("GROUP") && groupCode.length() > 0)
      sql.append("and MMTC_GROUP_CODE='" + groupCode + "' \n");
    sql.append("order by MCN_CORNER_CODE \n");
    ResultSet rs = st.executeQuery(sql.toString());
    while (rs.next()) {
      strbuf.append(CommonFunc.getOptionTag("CORNER:" + rs.getString("MMTC_CORNER_CODE"), CommonFunc.escapeHtml(rs.getString("MCN_CORNERNAME_TXT")) + " (" + rs.getString("START_DATE") + "\u301c" + rs.getString("END_DATE") + ")", cornerCode));
    }

    // ���Ԏ擾�Ώ�URL�̈ꗗ�擾
    sql = new StringBuffer();
    sql.append("select MMTU_URL_TXT \n");
    sql.append(",to_char(MMTU_STARTDATE_DATE,'YYYY/MM/DD') as START_DATE \n");
    sql.append(",to_char(MMTU_ENDDATE_DATE,'YYYY/MM/DD') as END_DATE \n");
    sql.append("from M_MOVETO_URL \n");
    if (subMode.equals("GROUP") && groupCode.length() > 0)
      sql.append("where MMTU_GROUP_CODE='" + groupCode + "' \n");
    sql.append("order by MMTU_URL_TXT \n");
    rs = st.executeQuery(sql.toString());
    while (rs.next()) {
      strbuf.append(CommonFunc.getOptionTag("URL:" + rs.getString("MMTU_URL_TXT"), CommonFunc.escapeHtml(rs.getString("MMTU_URL_TXT")) + " (" + rs.getString("START_DATE") + "\u301c" + rs.getString("END_DATE") + ")", cornerCode));
    }

    strbuf.append("</select>\n");

    return strbuf.toString();
  }

  // �N���b�N�X�g���[���p�v���_�E��
  static public String getStreamCornerSelect(DataBase db, SelectCondBean selectCondBean, int index) throws SQLException {
    StringBuffer strbuf = new StringBuffer();
    Statement st = db.createStatement();
    StringBuffer sql;
    String parentCorner = selectCondBean.getStreamParentCorner(index);
    String selectCorner = selectCondBean.getStreamSelectCorner(index);
    int depth = CommonFunc.getCornerDepth(parentCorner) + 1;

    // <select>�^�O
    strbuf.append("<select name='streamCorner" + index + "' onchange='change_corner(" + index + ")'>\n");

    // "�I�����Ă�������"�ǉ�
    strbuf.append("<option value='1:" + depth + ":'>�R�[�i�[��I�����Ă�������</option>\n");

    sql = new StringBuffer();
    sql.append("select MCN_CORNER_CODE,MCN_CORNERNAME_TXT,MCN_DEPTH_NUM,MCN_LASTLEVEL_FLG \n");
    sql.append("from M_CORNER \n");
    sql.append("where comm.getParentCornerCode(MCN_CORNER_CODE)='" + parentCorner + "' \n");
    sql.append("order by MCN_DEPTH_NUM,MCN_SORT" + depth + "_NUM \n");
    ResultSet rs = st.executeQuery(sql.toString());

    while (rs.next()) {
      String corner = rs.getString("MCN_CORNER_CODE");
      String value = rs.getInt("MCN_LASTLEVEL_FLG") + ":" + rs.getString("MCN_DEPTH_NUM") + ":" + corner;
      strbuf.append("<option value='" + value + "'" + CommonFunc.selectedFunc(corner, selectCorner) + ">" + CommonFunc.escapeHtml(rs.getString("MCN_CORNERNAME_TXT")) + "</option>\n");
    }

    strbuf.append("</select>\n");

    return strbuf.toString();
  }
}