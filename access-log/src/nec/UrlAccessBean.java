package xxx;

import base.*;
import java.sql.*;
import java.io.*;
import java.text.*;
import java.util.*;
import javax.servlet.*;
import javax.servlet.http.*;

/**
 * <p>�^�C�g��: URL���̃A�N�Z�X���\������</p>
 * <p>����: </p>
 * <p>���쌠: Copyright (c) 2003</p>
 * <p>��Ж�: </p>
 * @author aizawa
 * @version 1.0
 */

public class UrlAccessBean {
  private String cornerName = "�w��Ȃ�";
  private String categoryName = "�w��Ȃ�";
  private String dbdata;

  private String date;
  private String cornerCode;
  private String categoryCode;
  private String attributeCode;
  private int pageNo;
  private boolean forwardPage;
  private boolean backPage;

  private DataBase db;
  private Statement st;

  public String getCornerName() {
    return cornerName;
  }

  public String getCategoryName() {
    return categoryName;
  }

  public String getDbdata() {
    return dbdata;
  }

  public String getDate() {
    return date;
  }

  public String getCornerCode() {
    return cornerCode;
  }

  public String getCategoryCode() {
    return date;
  }

  public int getPageNo() {
    return pageNo;
  }

  public String getDisableForwardPage() {
    return forwardPage ? "" : " disabled";
  }

  public String getDisableBackPage() {
    return backPage ? "" : " disabled";
  }

  public void SearchDB(HttpServletRequest request) {
    try {
      // �p�����[�^�擾
      date = request.getParameter("date");
      cornerCode = request.getParameter("corner");
      categoryCode = request.getParameter("category");
      attributeCode = request.getParameter("attribute");
      pageNo = Integer.parseInt(CommonFunc.nvl(request.getParameter("pageno"), "0"));

      // �y�[�W�؂�ւ�
      if (request.getParameter("Back") != null)
        pageNo--;
      if (request.getParameter("Forward") != null)
        pageNo++;

      // DB�ڑ�
      db = new DataBase();
      st = db.createStatement();

      // �R�[�i�[���擾
      getCornerName(cornerCode);

      // �Ǝ햼�擾
      getCategoryName(categoryCode);

      // �������ʎ擾
      getSearchResult(date, cornerCode, categoryCode, pageNo);
    } catch(Exception e) {
			e.printStackTrace();
    }
  }

  // �R�[�i�[���擾
  private void getCornerName(String cornerCode) {
    StringBuffer strbuf = new StringBuffer();
    int index = 1;

    while (true) {
      // ����"/"��T���B������ΏI��
      index = cornerCode.indexOf("/", index);
      if (index == -1)
        break;

      // ��؂蕶���t��
      if (strbuf.length() != 0)
        strbuf.append("�F");

      // �R�[�i�[����A��
      strbuf.append(CommonFunc.getLaxxxornerName(CodeToName.CNToName(db, cornerCode.substring(0, ++index))));
    }

    if (strbuf.length() != 0)
      cornerName = strbuf.toString();
  }

  // �Ǝ햼�擾
  private void getCategoryName(String categoryCode) throws SQLException {
    StringBuffer strbuf = new StringBuffer();

    if (categoryCode != null && categoryCode.length() != 0) {
      // �擪��"."�̏ꍇ�A��ƃR�[�h�Ƃ݂Ȃ�
      if (categoryCode.charAt(0) == '.') {
        // ��ƃR�[�h�̏ꍇ
        String sql = "select MBC_DISPNAME_TXT,MCM_DISPNAME_TXT \n"
            + "from M_BUSINESSCATEGORY,M_COMPANY \n"
            + "where MCM_COMPANY_CODE='" + categoryCode.substring(1) + "' and MBC_BUSINESSCATEGORY_CODE=MCM_BUSINESSCATEGORY_CODE";
        ResultSet rs = st.executeQuery(sql);
        if (rs.next())
          strbuf.append(rs.getString("MBC_DISPNAME_TXT")).append("�F").append(rs.getString("MCM_DISPNAME_TXT"));
      } else if (!categoryCode.equals("/")) {
        // �Ǝ�R�[�h�̏ꍇ
        strbuf.append(CodeToName.BCToName(db, categoryCode));
      }
    }

    if (strbuf.length() != 0)
      categoryName = strbuf.toString();
  }

  // �������ʎ擾
  private void getSearchResult(String date, String cornerCode, String categoryCode, int pageNo)
      throws Exception {
    StringBuffer html = new StringBuffer();
    String dateFrom;
    String dateTo;

    // ���t�͈͎擾
    int n = date.indexOf("-");
    if (n == -1) {
      dateFrom = date;
      dateTo = date;
    } else {
      dateFrom = date.substring(0, n);
      dateTo = date.substring(n + 1);
    }

    // SQL����
    StringBuffer sql = new StringBuffer();
    sql.append("select SDUR_URL_TXT,sum(SDUR_ACCESSCOUNT_NUM) as ACCESS_CNT \n");
    sql.append("from S_DATE_URL_RANK \n");
    sql.append("where \n");

    // ���Ԃ̏���
    if (dateFrom.equals(dateTo))
      sql.append("SDUR_ACCESSDATE_DATE='" + dateFrom + "' \n");
    else
      sql.append("SDUR_ACCESSDATE_DATE between '" + dateFrom + "' and '" + dateTo + "' \n");

    // �Ǝ�܂��͊�ƃR�[�h�̏���
    if (categoryCode != null && categoryCode.length() != 0) {
      if (categoryCode.charAt(0) == '.')
        sql.append("and SDUR_COMPANY_CODE='" + categoryCode.substring(1) + "' \n");
      else if (!categoryCode.equals("/"))
        sql.append("and SDUR_BUSINESSCATEGORY_CODE='" + categoryCode + "' \n");
    }

    // �R�[�i�[�R�[�h�̏���
    if (cornerCode != null && cornerCode.length() != 0)
      sql.append("and SDUR_CORNER_CODE='" + cornerCode + "' \n");

    // ��Ƒ�������
    StringBuffer q01 = new StringBuffer();
    q01.append("and exists (select * from M_COMPANY where MCM_COMPANY_CODE=SDUR_COMPANY_CODE \n");
    StringTokenizer token = new StringTokenizer(attributeCode, "/");
    int i = 0;
    boolean attributeFlag = false;
    while (token.hasMoreTokens()) {
      i++;
      String attr = token.nextToken();
      if (!attr.equals("0")) {
        q01.append("and MCM_ATTRIBUTE" + i + "_CODE='").append(attr).append("' \n");
        attributeFlag = true;
      }
    }
    q01.append(") \n");
    if (attributeFlag)
      sql.append(q01);

    sql.append("group by SDUR_URL_TXT \n");
    sql.append("order by ACCESS_CNT DESC \n");

    // �K�v�Ȕ͈͂̃f�[�^�݂̂𒊏o
    StringBuffer sql_row = new StringBuffer();
    int pageSize = 20;
    int offset = pageNo * pageSize;
    sql_row.append(CommonSql.RowRange(sql.toString(), offset, pageSize + 1)); // ���y�[�W�����邩�`�F�b�N���邽�߂P�����ǂ�

    // �v���p�e�B�t�@�C������URL�̃z�X�g�����擾
    String urlHost = CommonFunc.getUrlHost();

    int no = 1;
    forwardPage = false;
    backPage = (offset != 0);
    System.out.println(sql_row);
    ResultSet rs = st.executeQuery(sql_row.toString());
    while (rs.next()) {
      // ���y�[�W�����邩�`�F�b�N
      if (pageSize != 0 && no > pageSize) {
        forwardPage = true;
        break;
      }

      String url = urlHost + rs.getString("SDUR_URL_TXT");
      html.append("<tr class='url'>\n");
      html.append("<td class='url_no'>" + (offset + no) + "</td>\n");
      html.append("<td class='url_url'><a href='javascript:open_url(\"" + url + "\")' class='link'>" + url + "</a></td>\n");
      html.append("<td class='url_access'>" + rs.getInt("ACCESS_CNT") + "</td>\n");
      html.append("</tr>\n");

      no++;
    }

    dbdata = html.toString();
  }
}