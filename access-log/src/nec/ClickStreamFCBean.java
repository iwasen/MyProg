package xxx;

import base.*;
import java.sql.*;

/**
 * クリックストリーム集計テーブルを作成
 */
public class ClickStreamFCBean {
  public void searchDB(DataBase db, SelectCondBean selectcondBean) {
    try {
      Statement st = db.createStatement();

      // テーブルIDを取得
      String tableID = selectcondBean.getStreamTableID();

      // 始点、終点、中間点を取得（中間点は前に詰める）
      String streamStart = BlankToNull(selectcondBean.getStreamSelectCorner(0));
      String streamEnd = BlankToNull(selectcondBean.getStreamSelectCorner(4));
      String streamMid[] = new String[3];
      for (int n = 0, i = 1; i <= 3; i++) {
        if (selectcondBean.getStreamSelectCorner(i).length() != 0)
          streamMid[n++] = BlankToNull(selectcondBean.getStreamSelectCorner(i));
      }

      // ストアド(CreateStreamTableFC)を実行
      StringBuffer sql = new StringBuffer();
      sql.append("declare \n");
      sql.append("ret integer; \n");
      sql.append("begin \n");
      sql.append("ret:=CreateStreamTableFC( \n");
      sql.append(" " + streamStart);
      sql.append("," + streamEnd);
      sql.append("," + streamMid[0]);
      sql.append("," + streamMid[1]);
      sql.append("," + streamMid[2]);
      sql.append(",'" + selectcondBean.getDateFrom() + "'");
      sql.append(",'" + selectcondBean.getDateTo() + "'");
      sql.append("," + tableID + "); \n");
      sql.append("end; \n");
      System.out.println(sql);
      st.execute(sql.toString());

      // テーブルIDを保存
      selectcondBean.setStreamTableID(tableID);
    } catch (Exception e) {
      e.printStackTrace();
    }
  }

  // ブランクをnullに変換
  private String BlankToNull(String str) {
    if (str == null || str.length() == 0)
      return "null";
    else
      return "'" + str + "'";
  }
}