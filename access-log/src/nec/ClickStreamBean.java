package xxx;

import base.*;
import java.sql.*;
import java.text.*;
import java.util.*;
import java.util.Date;
import java.io.*;

/**
 * �N���b�N�X�g���[�������L���O�\���쐬
 */
public class ClickStreamBean implements I_Access_Page {
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
   * �N���b�N�X�g���[�������L���O�\�̒��g���쐬
   */
  public void searchDB(DataBase db, SelectCondBean selectcondBean) {
    try {
      Statement st = db.createStatement();
      NumberFormat numFormat = NumberFormat.getNumberInstance();
      int maxCorner = 0;
      int i;

      selectcondBean.setStreamCounting(0);

      // ����l���[�h��������ڍ׃��[�h�ɕύX
      if (selectcondBean.getMode() == 1) {
        selectcondBean.initCondition(db);
        selectcondBean.setMode("2");
        return;
      }

      // �\���{�^���`�F�b�N
      String tableID = selectcondBean.getStreamTableID();
      if (tableID.length() == 0) {
        String status = "1";
        tableID = getRecycleTableID(st, selectcondBean, status);
        int streamCounting = (tableID == null) ? 1 : 2;
        if (tableID == null || !selectcondBean.getStreamRecycle()) {
          status = "0";
          tableID = getRecycleTableID(st, selectcondBean, status);
        }
        if (tableID == null) {
          selectcondBean.setStreamTableID(Integer.toString(CommonSql.getNextSeq(st, "SQ_STREAMTABLE")));
          selectcondBean.setMode("2");
          selectcondBean.setStreamCounting(streamCounting);
          return;
        }
        selectcondBean.setStreamTableID(tableID);
        if (status.equals("0")) {
          selectcondBean.setMode("2");
          selectcondBean.setStreamErrMsg("��������ŏW�v�������ł��B\\n\\n���΂炭�����Ă���A��������ɂĕ\���{�^�����������Ă��������B");
          return;
        }
      } else {
        // �W�v�e�[�u���X�e�[�^�X�`�F�b�N
        String status = checkTableID(st, selectcondBean, tableID);
        if (status == null) {
          selectcondBean.setMode("2");
          selectcondBean.setStreamErrMsg("�w�肳�ꂽ�����ԍ��͖��o�^�ł��B");
          return;
        } else if (status.equals("0")) {
          selectcondBean.setMode("2");
          selectcondBean.setStreamErrMsg("�w�肳�ꂽ�����ԍ��͌��ݏW�v�������ł��B\\n\\n���΂炭�����Ă���A���������ԍ�����͂��ĕ\���{�^�����������Ă��������B");
          return;
        }
      }

      // �\�����x���擾
      String dispLevel = selectcondBean.getStreamDispLevel();

      // �v���p�e�B�t�@�C������URL�̃z�X�g�����擾
      String urlHost = CommonFunc.getUrlHost();

      // �ő�̃R�[�i�[���i�����̐��j���擾
      StringBuffer sql = new StringBuffer();
      sql.append("select max(decode('-'");
      for (i = 1; i <= 30; i++)
        sql.append(",DSR_CODE" + i + "_CODE," + (i - 1));
      sql.append(",30)) \n");
      sql.append("from S_STREAMRESULT_" + tableID + " \n");
      sql.append("where DSR_CORNERLEVEL_NUM=" + dispLevel + " \n");
      System.out.println(sql);
      ResultSet rs = st.executeQuery(sql.toString());
      if (rs.next())
        maxCorner = rs.getInt(1);

      // �\�f�[�^���擾����SQL�i/*+ RULE */�������Ɗ�Ƒ������w�肵���ꍇ�Ɉُ�Ɏ��Ԃ�������j
      StringBuffer q01 = new StringBuffer();
      q01.append("select /*+ RULE */ sum(DSR_ACCESSCOUNT_NUM) as S_ACCESSCOUNT_NUM \n");
      for (i = 1; i <= maxCorner; i++)
        q01.append(",DSR_CODE" + i + "_CODE \n");
      q01.append("from S_STREAMRESULT_" + tableID + " \n");
      q01.append("where DSR_CORNERLEVEL_NUM=" + dispLevel + " \n");
      CommonSql.setAttributeSql(q01, selectcondBean);
      if (maxCorner > 0) {
        q01.append("group by ");
        for (i = 1; i <= maxCorner; i++) {
          if (i != 1)
            q01.append(",");
          q01.append("DSR_CODE" + i + "_CODE \n");
        }
      }

      StringBuffer q02 = new StringBuffer();
      q02.append("select S_ACCESSCOUNT_NUM \n");
      for (i = 1; i <= maxCorner; i++) {
        q02.append(",t" + i + ".MCN_CORNERNAME_TXT as CORNERNAME" + i);
        q02.append(",t" + i + ".MCN_CORNERCOLOR_CODE as CORNERCOLOR" + i);
        q02.append(",t" + i + ".MCN_TOPPAGEURL_TXT as TOPPAGEURL" + i + "\n");
      }
      q02.append("from (" + q01 + ") q01 \n");
      for (i = 1; i <= maxCorner; i++)
        q02.append(",M_CORNER t" + i + "\n");
      if (maxCorner > 0) {
        q02.append("where ");
        for (i = 1; i <= maxCorner; i++) {
          if (i != 1)
            q02.append("and ");
          q02.append("t" + i + ".MCN_CORNER_CODE(+)=DSR_CODE" + i + "_CODE \n");
        }
      }
      q02.append("order by S_ACCESSCOUNT_NUM DESC \n");

      // �J�����̐ړ����ƃe�[�u����ʖ���u��
      CommonFunc.substitute(q02, "[PF]", "DSR");

      // �K�v�Ȕ͈͂̃f�[�^�݂̂𒊏o
      StringBuffer sql_row = new StringBuffer();
      int pageSize = selectcondBean.getPageSize();
      int offset = selectcondBean.getPageNo() * pageSize;
      sql_row.append(CommonSql.RowRange(q02.toString(), offset, pageSize + 1)); // ���y�[�W�����邩�`�F�b�N���邽�߂P�����ǂ�

      // html�e�[�u���̃w�b�_���쐬
      StringBuffer tags = new StringBuffer();
      tags.append("<tr class='header'>");
      tags.append("<td class='header_no'>No.</td>\n");
      tags.append("<td class='header_txt' nowrap>����</td>\n");
      for (i = 1; i <= maxCorner; i++)
          tags.append("<td class='header_txt' nowrap>�R�[�i�[<br>" + CommonFunc.toFullANS(Integer.toString(i)) + "</td>\n");
      tags.append("</tr>\n");

      // html�e�[�u���̃f�[�^���쐬
      int cornerWidth = getCornerWidth();
      int no = 1;
      System.out.println(sql_row);
      rs = st.executeQuery(sql_row.toString());
      while (rs.next()) {
        tags.append("<tr class='data'>");

        // �ԍ�
        tags.append("<td class='data_no2'>").append(offset + no).append("</td>\n");

        tags.append("<td class='data_access'>").append(numFormat.format(rs.getInt("S_ACCESSCOUNT_NUM"))).append("</td>\n");

        for (i = 1; i <= maxCorner; i++) {
          String cornerName = rs.getString("CORNERNAME" + i);
          if (cornerName == null)
            tags.append("<td bgcolor='#CCCCC'><br></td>\n");
          else
            tags.append("<td align='center' bgcolor='#" + rs.getString("CORNERCOLOR" + i) + "'><a href='" + urlHost + rs.getString("TOPPAGEURL" + i) + "' class='link' target='_blank'>" + addLineFeed(CommonFunc.getLaxxxornerName(cornerName), cornerWidth) + "</a></td> \n");
        }

        tags.append("</tr>\n");

        no++;
      }

      dbdata = tags.toString();
    } catch (Exception e) {
      dbdata = "<font color='red'>�W�v�������ɃG���[���������܂����B<br>" + e + "</font><br>";
			e.printStackTrace();
		}//try
  }

  // �W�v�e�[�u���̌���
  private String getRecycleTableID(Statement st, SelectCondBean selectcondBean, String status) throws SQLException {
    String sql;
    String tableID = null;

    sql = "select max(MSC_REQUESTID_NUM) from M_STREAMCONTROL WHERE MSC_STATUS_CLS='" + status + "'" + getStreamWhere(st, selectcondBean);
    ResultSet rs = st.executeQuery(sql.toString());
    if (rs.next())
      tableID = rs.getString(1);

    return tableID;
  }

  // �W�v�e�[�u��ID���݃`�F�b�N
  private String checkTableID(Statement st, SelectCondBean selectcondBean, String tableID) throws SQLException {
    String sql;

    sql = "select MSC_START_CODE,MSC_MID01_CODE,MSC_MID02_CODE,MSC_MID03_CODE,MSC_END_CODE,MSC_FROMDATE_DATE,MSC_TODATE_DATE,MSC_STATUS_CLS from M_STREAMCONTROL where MSC_REQUESTID_NUM=" + tableID;
    ResultSet rs = st.executeQuery(sql.toString());
    if (rs.next()) {
      selectcondBean.setStreamSelectCorner(0, rs.getString("MSC_START_CODE"));
      selectcondBean.setStreamSelectCorner(1, rs.getString("MSC_MID01_CODE"));
      selectcondBean.setStreamSelectCorner(2, rs.getString("MSC_MID02_CODE"));
      selectcondBean.setStreamSelectCorner(3, rs.getString("MSC_MID03_CODE"));
      selectcondBean.setStreamSelectCorner(4, rs.getString("MSC_END_CODE"));

      Date date;
      SimpleDateFormat formatY = new SimpleDateFormat("yyyy");
      SimpleDateFormat formatM = new SimpleDateFormat("MM");
      SimpleDateFormat formatD = new SimpleDateFormat("dd");

      date = rs.getDate("MSC_FROMDATE_DATE");
      selectcondBean.setDayFromY(formatY.format(date));
      selectcondBean.setDayFromM(formatM.format(date));
      selectcondBean.setDayFromD(formatD.format(date));

      date = rs.getDate("MSC_TODATE_DATE");
      selectcondBean.setDayToY(formatY.format(date));
      selectcondBean.setDayToM(formatM.format(date));
      selectcondBean.setDayToD(formatD.format(date));

      return rs.getString("MSC_STATUS_CLS");
    }
    return null;
  }

  // �X�g���[���R���g���[���}�X�^���������擾
  private String getStreamWhere(Statement st, SelectCondBean selectcondBean) {
    // ���ԓ_���擾�i���ԓ_�͑O�ɋl�߂�j
    String streamMid[] = new String[3];
    for (int n = 0, i = 1; i <= 3; i++) {
      if (selectcondBean.getStreamSelectCorner(i).length() != 0)
        streamMid[n++] = selectcondBean.getStreamSelectCorner(i);
    }

    StringBuffer where = new StringBuffer();
    where.append(" and MSC_START_CODE" + getCondition(selectcondBean.getStreamSelectCorner(0)));
    where.append(" and MSC_MID01_CODE" + getCondition(streamMid[0]));
    where.append(" and MSC_MID02_CODE" + getCondition(streamMid[1]));
    where.append(" and MSC_MID03_CODE" + getCondition(streamMid[2]));
    where.append(" and MSC_END_CODE" + getCondition(selectcondBean.getStreamSelectCorner(4)));
    where.append(" and MSC_FROMDATE_DATE='" + selectcondBean.getDateFrom() + "'");
    where.append(" and MSC_TODATE_DATE='" + selectcondBean.getDateTo() + "'");

    return where.toString();
  }

  // ������
  private String getCondition(String str) {
    if (str == null || str.length() == 0)
      return " is null";
    else
      return "='" + str + "'";
  }

  // �w�蕶�����ŉ��s
  private String addLineFeed(String str, int size) {
    StringBuffer strBuf = new StringBuffer();
    int i;

    strBuf.append("<nobr>");
    int len = str.length();
    for (i = 0; i < len - size; i += size) {
      strBuf.append(str.substring(i, i + size));
      strBuf.append("<wbr>");
    }
    strBuf.append(str.substring(i, Math.min(i + size, len)));
    strBuf.append("</nobr>");

    return strBuf.toString();
  }

  // �R�[�i�[���̕����擾
  private int getCornerWidth() {
    int cornerWidth;

    try {
      // �v���p�e�B�t�@�C������R�[�i�[���̕����擾
			FileInputStream fin = new FileInputStream(new File(WebAppInitializer.propertyPath));
			Properties prop = new Properties();
			prop.load(fin);
			cornerWidth = Integer.parseInt(prop.getProperty("stream_corner_width", "3"));
      fin.close();
    } catch (Exception ex) {
      cornerWidth = 3;
    }

    return cornerWidth;
  }
}
