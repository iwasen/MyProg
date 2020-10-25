package xxx;

import base.*;
import java.sql.*;
import java.text.*;
import java.io.*;
import java.util.*;

/**
 * �A�N�Z�X�惉���L���O�O���t���쐬
 */
public class RefererOutBean implements I_Access_Page {
  private String dbdata = "DB value";
  private boolean forwardPage;
  private boolean backPage;

  public String getDbdata() {
    return dbdata;
  }

  public String getDisableForwardPage() {
    return forwardPage ? "" : " disabled";
  }

  public String getDisableBackPage() {
    return backPage ? "" : " disabled";
  }

  /**
   * �A�N�Z�X�惉���L���O�O���t�̒��g���쐬
   */
  public void searchDB(DataBase db, SelectCondBean selectcondBean) {
    try {
      // �ΏۃR�[�i�[���I������Ă��Ȃ���Ή������Ȃ�
      String targetCorner = selectcondBean.getMoveFromCorner();
      if (targetCorner.length() == 0)
        return;

      Statement st = db.createStatement();
      ResultSet rs;
      NumberFormat numFormat = NumberFormat.getNumberInstance();
      int i;

      // �ΏۃR�[�i�[ or URL���擾
      String table = selectcondBean.getMoveFromCornerTable();
      String corner = selectcondBean.getMoveFromCornerOrUrl();

      // �v���p�e�B�t�@�C������URL�̃z�X�g�����擾
      String urlHost = CommonFunc.getUrlHost();

      //�e�[�u���ʂ̃w�b�_�iURL�E�R�[�i�[�j
      String prefix = "", urlCorner = "";
      if (table.equals("CORNER")) {
        prefix = "SMFC";
        urlCorner = "[PF]_FROM[TB]_CODE";
      } else {
        prefix = "SMFU";
        urlCorner = "[PF]_FROM[TB]_TXT";
      }

      // �s���Ƃ̃A�N�Z�X�����擾���邽�߂̃T�u�N�G��
      StringBuffer q01 = new StringBuffer();
      q01.append("select ");
      q01.append("[PF]_TOCORNER_CODE as S_CORNER_CODE \n");
      q01.append(",sum([PF]_ACCESSCOUNT_NUM) as ALLCOUNT \n");
      q01.append("from S_MOVEFR_[TB] \n");
      q01.append("where ");
      q01.append("[PF]_ACCESSDATE_DATE between '" + selectcondBean.getDateFrom() + "' and '" + selectcondBean.getDateTo() + "' \n");
      q01.append("and " + urlCorner + "='" + corner + "' \n");
      q01.append("and [PF]_TOCORNER_CODE<>'-' \n");
      q01.append("group by [PF]_TOCORNER_CODE");

      // �J�����̐ړ����ƃe�[�u����ʖ���u��
      CommonFunc.substitute(q01, "[PF]", prefix);
      CommonFunc.substitute(q01, "[TB]", table);

      // �S�A�N�Z�X���ƍő�A�N�Z�X�����擾���邽�߂�SQL
      StringBuffer sql_ttl = new StringBuffer();
      sql_ttl.append("select SUM(ALLCOUNT) as TOTAL,MAX(ALLCOUNT) as MAXCOUNT \n");
      sql_ttl.append("from (" + q01 + ") \n");

      // ���sSQL�쐬
      StringBuffer q02 = new StringBuffer();
      q02.append("select nvl(MCN_CORNERNAME_TXT,S_CORNER_CODE) as S_CORNER_TXT,coalesce(MCN_TOPPAGEURL_TXT,MPO_LINKURL_TXT,S_CORNER_CODE) as S_LINKURL_TXT,q01.* \n");
      q02.append("from M_CORNER,M_PORTAL,(" + q01 + ") q01 \n");
      q02.append("where S_CORNER_CODE=MCN_CORNER_CODE(+) and S_CORNER_CODE=MPO_PORTAL_CODE(+) \n");
      q02.append("order by NVL(ALLCOUNT,0) DESC,S_CORNER_CODE \n");

      // �K�v�Ȕ͈͂̃f�[�^�݂̂𒊏o
      StringBuffer sql_row = new StringBuffer();
      int pageSize;
      int offset;
      pageSize = selectcondBean.getPageSize();
      offset = selectcondBean.getPageNo() * pageSize;
      sql_row.append(CommonSql.RowRange(q02.toString(), offset, pageSize + 1)); // ���y�[�W�����邩�`�F�b�N���邽�߂P�����ǂ�

      // html�e�[�u���̃w�b�_���쐬
      StringBuffer tags= new StringBuffer();
      tags.append("<tr class='header'>");
      tags.append("<td class='header_no'>No.</td>\n");
      tags.append("<td class='header_txt'>�R�[�i�[����t�q�k</td>\n");
      tags.append("<td class='header_txt'>�A�N�Z�X</td>\n");
      tags.append("<td class='header_txt'>��</td>\n");
      tags.append("<td class='header_txt'>�O���t</td>\n");
      tags.append("</tr>\n");

      // html�e�[�u���̍��v���쐬
      int total = 0, maxCount = 0;
      System.out.println(sql_ttl);
      rs = st.executeQuery(sql_ttl.toString());
      if (rs.next()) {
        // �S�A�N�Z�X���ƍő�A�N�Z�X���擾
        total = rs.getInt("TOTAL");
        maxCount = rs.getInt("MAXCOUNT");

        // �s�J�n
        tags.append("<tr class='total'>");

        // �ԍ��i�����Ȃ��j
        tags.append("<td><br></td>");

        // "���v"
        tags.append("<td class='total_txt'>���v</td>\n");

        // ���v�A�N�Z�X���ƃp�[�Z���g
        tags.append("<td class='total_num'>").append(numFormat.format(total)).append("</td>\n");
        tags.append("<td class='total_num'>").append(CommonFunc.getParcent(total, total)).append("</td>\n");

        // �O���t�i�����Ȃ��j
        tags.append("<td><br></td>");

        // �s�I��
        tags.append("</tr>\n");
      }

      // html�e�[�u���̃f�[�^���쐬
      int subTotal = 0;
      int no = 1;
      forwardPage = false;
      backPage = (offset != 0);
      System.out.println(sql_row);
      rs = st.executeQuery(sql_row.toString());
      while(rs.next()) {
        // ���y�[�W�����邩�`�F�b�N
        if (pageSize != 0 && no > pageSize) {
          forwardPage = true;
          break;
        }

        tags.append("<tr class='data'>");

        // �ԍ�
        tags.append("<td class='data_no'>").append(no + offset).append("</td>\n");

        // URL�擾�i�擪��"/"�Ȃ�Web�z�X�g����t���j
        String url = rs.getString("S_LINKURL_TXT");
        if (url.charAt(0) == '/')
          url = urlHost + url;

        // �R�[�i�[�����擾�i�擪��"/"�Ȃ�Web�z�X�g����t���j
        String cornerName = rs.getString("S_CORNER_TXT");
        if (cornerName.charAt(0) == '/')
          cornerName = urlHost + cornerName;
        else
          cornerName = CommonFunc.getLaxxxornerName(cornerName);

        // �R�[�i�[����URL�̃����N
        tags.append("<td class='data_corner'>");
        tags.append("<a href='" + url + "' class='link' target='_blank'>" + cornerName + "</a>\n");
        tags.append("</td>\n");

        // ���v�A�N�Z�X���ƃp�[�Z���g
        int allCount = rs.getInt("ALLCOUNT");
        tags.append("<td class='data_access'>").append(numFormat.format(allCount)).append("</td>\n");
        tags.append("<td class='data_percent'>").append(CommonFunc.getParcent(total, allCount)).append("</td>\n");
        subTotal += allCount;

        // �_�O���t
        tags.append("<td class='data_graph'>");
        tags.append(CommonFunc.genGraphImage(allCount, maxCount));//GIF�̕����v�Z
        tags.append("</td>\n");

        tags.append("</tr>\n");

        no++;
      }

      // html�e�[�u���̏��v���쐬
      {
        // �s�J�n
        tags.append("<tr class='subtotal'>");

        // �ԍ��i�����Ȃ��j
        tags.append("<td><br></td>");

        // "���v"
        tags.append("<td class='subtotal_txt'>���v</td>\n");

        // ���v�A�N�Z�X���ƃp�[�Z���g
        tags.append("<td class='subtotal_num'>").append(numFormat.format(subTotal)).append("</td>\n");
        tags.append("<td class='subtotal_num'>").append(CommonFunc.getParcent(total, subTotal)).append("</td>\n");

        // �O���t�i�����Ȃ��j
        tags.append("<td><br></td>");

        // �s�I��
        tags.append("</tr>\n");
      }

      dbdata = tags.toString();
    } catch (SQLException e) {
      dbdata = "<font color='red'>�W�v�������ɃG���[���������܂����B<br>" + e + "</font><br>";
			e.printStackTrace();
		}//try
  }
}
