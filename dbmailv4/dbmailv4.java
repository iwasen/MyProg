
/**
 * タイトル:     メール配信プログラム Version4<p>
 * 説明:         <p>
 * 著作権:       Copyright (c) aizawa<p>
 * 会社名:       Space Technology Co.,Ltd.<p>
 * @author aizawa
 * @version 1.0
 */

import java.io.*;
import java.util.*;
import java.util.Date;
import java.sql.*;
import javax.mail.internet.*;
import org.postgresql.largeobject.*;

public class dbmailv4
{
  final String STATUS_READY = "1";
  final String STATUS_SENDING = "2";
  final String STATUS_END = "3";
  final String STATUS_ERROR = "4";
  final String STATUS_RETRY = "8";
  final String STATUS_FATAL = "9";

  static private String my_hostname;
  static private String[] mail_servers;
  private String database;
  private String url;
  private String user;
  private String password;
  private String mode;
  private String mail_from;
  private String delete_data;
  private String monitor;
  private int loop_time;
  private int delete_interval;
  private boolean vacuum;
  private boolean end_sql;
  private Conxxxtion conxxxtion;
  private String sql_date;
  private String uniq_id;
  private int max_send_count;

  // コンストラクタ
  public dbmailv4(String dbconf) throws Exception
  {
    read_db_conf(dbconf);
  }

  // 共通設定ファイル読み込み
  static private void read_common_conf() throws Exception
  {
    FileInputStream fs;
//		try {
      fs = new FileInputStream("dbmailv4.conf");
//		} catch (Exception e) {
//			fs = new FileInputStream("D:\\xxxxxxx\\haimail\\dbmailv3\\dbmailv3.conf");
//		}
    Properties prop = new Properties();
    int n;
    String mail_server;

    prop.load(fs);

    // 自分のサーバ名取得
    my_hostname = prop.getProperty("Host");

    // メールサーバ名取得
    n = 0;
    while (true) {
      mail_server = prop.getProperty("MailServer" + (n + 1));
      if (mail_server == null)
        break;
      n++;
    }

    mail_servers = new String[n];
    n = 0;
    while (true) {
      mail_server = prop.getProperty("MailServer" + (n + 1));
      if (mail_server == null)
        break;
      mail_servers[n++] = mail_server;
    }
  }

  // サイト別情報ファイル読み込み
  private void read_db_conf(String dbconf) throws Exception
  {
    FileInputStream fs;
//		try {
      fs = new FileInputStream(dbconf);
//		} catch (Exception ex) {
//			fs = new FileInputStream("D:\\xxxxxxx\\haimail\\dbmailv3\\" + dbconf);
//		}
    Properties prop = new Properties();

    prop.load(fs);

    database = prop.getProperty("Database");
    url = prop.getProperty("Url");
    user = prop.getProperty("UserName");
    password = prop.getProperty("Password");
    mode = prop.getProperty("Mode");
    mail_from = prop.getProperty("MailFrom");
    delete_data = prop.getProperty("DeleteData", "0");
    monitor = prop.getProperty("MonitorAddress");
    loop_time = Integer.valueOf(prop.getProperty("LoopTime", "0")).intValue();
    delete_interval = Integer.valueOf(prop.getProperty("DeleteInterval", "1")).intValue();
    vacuum = prop.getProperty("Vacuum", "0").equals("1") ? true : false;
    end_sql = prop.getProperty("EndSql", "0").equals("1") ? true : false;
    max_send_count = Integer.valueOf(prop.getProperty("MaxSendCount", "100000")).intValue();
  }

  // DBに接続
  public void conxxxt_db() throws Exception
  {
    String driver;

    if (database.compareToIgnoreCase("oracle") == 0) {
      driver = "oracle.jdbc.driver.OracleDriver";
      sql_date = "SYSDATE";
      uniq_id = "ROWID";
    } else if (database.compareToIgnoreCase("postgresql") == 0) {
      driver = "org.postgresql.Driver";
      sql_date = "current_timestamp";
      uniq_id = "oid";
    } else if (database.compareToIgnoreCase("mssqlserver") == 0) {
      driver = "com.microsoft.jdbc.sqlserver.SQLServerDriver";
      sql_date = "current_timestamp";
      uniq_id = "SL_to";
    } else {
      Exception ex = new Exception("Unknown database.");
      throw ex;
    }

    Class.forName(driver);
    conxxxtion = DriverManager.getConxxxtion(url, user, password);
  }

  // DBから切断
  protected void close_db() throws Exception
  {
    if (conxxxtion != null) {
      if (!conxxxtion.isClosed())
        conxxxtion.close();
    }
  }

  // メール配信
  protected void send_mail() throws Exception
  {
    long id;
    String sql, subject, from, personal, replyto, to, body, mail_header, mail_type;
    Date send_date;
    String org_status;
    String status;
    boolean act;
    Calendar check_time = Calendar.getInstance();
    boolean send_ok = false;
    int len;

    // 処理終了時間取得
    check_time.add(Calendar.SECOND, loop_time);

    Statement statement = conxxxtion.createStatement();

    // 送信可のレコードを検索
    do {
      act = false;

      // 送信可能データ取得
      sql = "SELECT SD_send_mail_id,SD_status FROM T_SEND_MAIL_DATA WHERE SD_status='" + STATUS_READY + "' OR SD_status='" + STATUS_RETRY + "' ORDER BY SD_send_mail_id";
      ResultSet resultset = statement.executeQuery(sql);
      if (resultset.next()) {
        // 送信メールID取得
        id = resultset.getLong("SD_send_mail_id");
        org_status = resultset.getString("SD_status");
        resultset.close();

        // 配信開始処理
        if (start_send_mail(id)) {
          // メール配信データ取得
          sql = "SELECT SD_body,SD_send_mail_id,SD_subject,SD_from,SD_reply_to,SD_status,SD_start_date,SD_end_date,SD_mail_header,SD_send_date,SD_server,SD_personal,SD_mail_type FROM T_SEND_MAIL_DATA WHERE SD_send_mail_id=" + id;
          resultset = statement.executeQuery(sql);
          if (resultset.next()) {
            try {
              if (mode.equals("1")) {
                Clob clob = resultset.getClob("SD_body");
                body = toJIS(clob.getSubString(1, (int)clob.length()));
              } else if (mode.equals("2")) {
                byte[] b = resultset.getBytes("SD_body");
                body = toJIS(new String(b, "UTF-8"));
                //body = toJIS(resultset.getString("SD_body"));
              } else {
                body = toJIS(resultset.getString("SD_body")) + "\n";
              }

              len = body.length();
              if (len > 0 && !body.substring(len - 1, len).equals("\n"))
                body += "\r\n";

              id = resultset.getLong("SD_send_mail_id");
              subject = toJIS(resultset.getString("SD_subject"));
              from = resultset.getString("SD_from");
              replyto = resultset.getString("SD_reply_to");
              mail_header = resultset.getString("SD_mail_header");
              send_date = resultset.getTimestamp("SD_send_date");
              personal = toJIS(resultset.getString("SD_personal"));
              mail_type = resultset.getString("SD_mail_type");
            } catch (Exception ex) {
              end_send_mail(id, STATUS_FATAL);
              throw ex;
            }

            try {
              // メール配信
              status = send_mail_list(id, subject, from, personal, replyto, mail_header, send_date, mail_type, body);

              // 配信終了処理
              end_send_mail(id, status);

              // 処理ＯＫ
              act = true;
              send_ok = true;
            } catch (Exception ex) {
              end_send_mail(id, org_status.equals(STATUS_READY) ? STATUS_RETRY : STATUS_FATAL);
              throw ex;
            }
          }
        }
      }

      resultset.close();
    } while (act && loop_time > 0 && check_time.after(Calendar.getInstance()));

    if (vacuum && send_ok && database.compareToIgnoreCase("postgresql") == 0) {
      sql = "VACUUM T_SEND_MAIL_LIST";
      statement.executeUpdate(sql);

      sql = "VACUUM T_SEND_MAIL_EMBED";
      statement.executeUpdate(sql);
    }

    statement.close();
  }

  // メール配信開始処理
  protected boolean start_send_mail(long id) throws Exception
  {
    Statement statement = conxxxtion.createStatement();

    // ステータスとサーバ名セット
    String sql = "UPDATE T_SEND_MAIL_DATA SET SD_status='" + STATUS_SENDING + "',SD_server='" + my_hostname + "' WHERE SD_send_mail_id=" + id;
    statement.executeUpdate(sql);
    boolean result = false;

    // ２秒ウエイト
    if (mail_servers.length >= 2)
      java.lang.Thread.sleep(2000);

    // サーバ名が自分かチェック
    sql = "SELECT SD_start_date FROM T_SEND_MAIL_DATA WHERE SD_send_mail_id=" + id + "AND SD_status='" + STATUS_SENDING + "' AND SD_server='" + my_hostname + "'";
    ResultSet resultset = statement.executeQuery(sql);
    if (resultset.next()) {
      if (resultset.getTimestamp(1) == null) {
        sql = "UPDATE T_SEND_MAIL_DATA SET SD_start_date=" + sql_date + " WHERE SD_send_mail_id=" + id;
        statement.executeUpdate(sql);
      }
      result = true;
    }

    resultset.close();
    statement.close();

    return result;
  }

  // メール配信終了処理
  protected void end_send_mail(long id, String status) throws Exception
  {
    Statement statement = conxxxtion.createStatement();
    String sql = "UPDATE T_SEND_MAIL_DATA SET SD_end_date=" + sql_date + ",SD_status='" + status + "' WHERE SD_send_mail_id=" + id;
    statement.executeUpdate(sql);
    statement.close();
  }

  // メール送信
  protected String send_mail_list(long id, String subject, String from, String personal, String replyto, String mail_header, Date send_date, String mail_type, String body)
        throws Exception {
    String status = STATUS_END;
    int count = 0;
    int index = 0;
    int nactive = 0;
    int i;
    boolean send_ok;
    String to;
    String row_id;
    int nservers = mail_servers.length;
    MailServer[] mail_server = new MailServer[nservers];
    ResultSet resultset2;
    long sub_seq;
    MailContents mail_contents = new MailContents();
    String[] rowid_list = new String[delete_interval];
    long[] subseq_list = new long[delete_interval];
    int delete_count = 0;
    String variable_name;
    String embed_text;
    String select_id = uniq_id.equalsIgnoreCase("SL_to") ? "" : uniq_id + ",";
    String to_addr;

    Statement statement = conxxxtion.createStatement();
    String sql = "SELECT " + select_id + "SL_to,SL_sub_seq,SL_mail_header FROM T_SEND_MAIL_LIST WHERE SL_send_mail_id=" + id;
    ResultSet resultset = statement.executeQuery(sql);

    sql = "SELECT SE_variable_name,SE_embed_text FROM T_SEND_MAIL_EMBED WHERE SE_send_mail_id=" + id + " AND SE_sub_seq=?";
    PreparedStatement pstmt_emb_sel = conxxxtion.prepareStatement(sql);

    mail_contents.setAttachment(conxxxtion, id, database);

    // メールサーバに接続
    for (i = 0; i < nservers; i++) {
      try {
        mail_server[nactive] = new MailServer(mail_servers[i], mail_from, subject, from, personal, replyto, send_date, mail_type);
        mail_server[nactive].setBody(mail_contents, body, mail_type);
        mail_server[nactive].setHeader(mail_header);
        nactive++;
      } catch (Exception ex) {
        error_report(ex, "send_mail_list");
      }
    }

    // メール配信
    if (nactive > 0) {
      while (resultset.next()) {
        row_id = resultset.getString(uniq_id);
        to = uniq_id.equalsIgnoreCase("SL_to") ? row_id : resultset.getString("SL_to");
        sub_seq = resultset.getLong("SL_sub_seq");
        mail_header = resultset.getString("SL_mail_header");

        send_ok = false;
        for (i = 0; i < nactive && !send_ok; i++) {
          try {
            to_addr = null;

            // 変数埋め込み
            if (sub_seq != 0) {
              StringBuffer body2 = new StringBuffer(body);
              pstmt_emb_sel.setLong(1, sub_seq);
              resultset2 = pstmt_emb_sel.executeQuery();
              while (resultset2.next()) {
                variable_name = resultset2.getString("SE_variable_name");
                embed_text = resultset2.getString("SE_embed_text");
                if (embed_text == null)
                  embed_text = "";
                if (variable_name.equals("%%FROM_ADDR%%"))
                  mail_server[index].setFrom2(embed_text, personal);
                else if (variable_name.equals("%%TO_ADDR%%"))
                  to_addr = embed_text;
                else if (variable_name.equals("%%SUBJECT%%"))
                  mail_server[index].setSubject(toJIS(embed_text));
                else
                  replace(body2, variable_name, embed_text);
              }
              resultset2.close();
              mail_server[index].setBody(mail_contents, body2.toString(), mail_type);
            }

            // 追加メールヘッダセット
            mail_server[index].setHeader(mail_header);

            // メール送信
            mail_server[index].sendMessage(to, to_addr);

            // 追加メールヘッダ削除
            mail_server[index].removeHeader(mail_header);

            count++;
            send_ok = true;
          } catch (AddressException ex) {
            break;
          } catch (Exception ex) {
          }

          index = (index + 1) % nactive;
        }

        if (send_ok) {
          // 送信済み削除
          rowid_list[delete_count] = row_id;
          subseq_list[delete_count] = sub_seq;
          if (++delete_count >= delete_interval) {
            delete_list(id, rowid_list, subseq_list, delete_count);
            delete_count = 0;
          }
        } else {
          status = STATUS_ERROR;
        }

        if (count == max_send_count) {
          status = STATUS_READY;
          break;
        }
      }

      // 未削除の送信リスト削除
      if (delete_count != 0)
        delete_list(id, rowid_list, subseq_list, delete_count);

      // モニターアドレスへ送信
      if (monitor != null && !status.equals(STATUS_READY)) {
        try {
          mail_server[0].setSubject(subject + " [" + count + "]");
          mail_server[0].setBody(mail_contents, body, mail_type);
          mail_server[0].sendMessage(monitor, null);
        } catch (Exception ex) {
        }
      }

      // 送信終了処理
      if (status.equals(STATUS_END)) {
        // 終了時SQL実行
        if (end_sql) {
          sql = "SELECT SD_end_sql FROM T_SEND_MAIL_DATA WHERE SD_send_mail_id=" + id;
          ResultSet resultset3 = statement.executeQuery(sql);
          if (resultset3.next()) {
            String end_sql = resultset3.getString("SD_end_sql");
            if (end_sql != null) {
              try {
                statement.executeUpdate(end_sql);
              } catch (Exception ex) {
              }
            }
          }
          resultset3.close();
        }

        // 送信データ削除
        if (delete_data.equals("1"))
          delete_data(id);
      }
    } else
      status = STATUS_READY;

    resultset.close();
    statement.close();

    return status;
  }

  // 文字列置換
  static public void replace(StringBuffer input, String pattern, String replacement)
  {
    int len = pattern.length();
    int index = 0;

    while ((index = input.indexOf(pattern, index)) != -1) {
      input.replace(index, index + len, replacement);
      index += replacement.length();
    }
  }

  // 送信済みデータ削除
  protected void delete_data(long id) throws Exception
  {
    Statement statement = conxxxtion.createStatement();
    String sql;

    // 送信メールデータレコードを削除
    sql = "DELETE FROM T_SEND_MAIL_DATA WHERE SD_send_mail_id=" + id;
    statement.executeUpdate(sql);

    // 添付ファイルのラージオブジェクトを削除
    if (database.compareToIgnoreCase("postgresql") == 0) {
      sql = "SELECT SF_file_content FROM T_SEND_MAIL_FILE WHERE SF_send_mail_id=" + id;
      ResultSet rs = statement.executeQuery(sql);
      if (rs.next()) {
        ResultSetMetaData rsmd = rs.getMetaData();
        String ct = rsmd.getColumnTypeName(1);
        if (ct.equals("oid")) {
          java.lang.reflect.Method m = conxxxtion.getClass().getMethod("getLargeObjectAPI", null);
          LargeObjectManager lom = (LargeObjectManager)m.invoke(conxxxtion, null);

          do {
            int oid = rs.getInt("sf_file_content");
            if (oid != 0) {
              lom.delete(oid);
            }
          } while (rs.next());
        }
      }
      rs.close();
    }

    // 添付ファイルレコードを削除
    sql = "DELETE FROM T_SEND_MAIL_FILE WHERE SF_send_mail_id=" + id;
    statement.executeUpdate(sql);

    statement.close();
  }

  // 送信済みリスト削除
  protected void delete_list(long id, String[] rowid_list, long[] subseq_list, int delete_count) throws Exception
  {
    Statement statement = conxxxtion.createStatement();
    String sql;
    String where_id;

    if (uniq_id.equalsIgnoreCase("SL_to"))
        where_id = "SL_send_mail_id=" + id + " AND ";
    else
        where_id = "";

    if (delete_count == 1) {
      sql = "DELETE FROM T_SEND_MAIL_LIST WHERE " + where_id + uniq_id + "='" + rowid_list[0] + "'";
      statement.executeUpdate(sql);

      if (subseq_list[0] != 0) {
        sql = "DELETE FROM T_SEND_MAIL_EMBED WHERE SE_send_mail_id=" + id+ " AND SE_sub_seq=" + subseq_list[0];
        statement.executeUpdate(sql);
      }
    } else if (delete_count > 1) {
      String row_id = "", sub_seq = "";

      for (int i = 0; i < delete_count; i++) {
        if (row_id.length() != 0)
          row_id += ",";
        row_id += "'" + rowid_list[i] + "'";

        if (subseq_list[i] != 0) {
          if (sub_seq.length() != 0)
            sub_seq += ",";
          sub_seq += subseq_list[i];
        }
      }
      sql = "DELETE FROM T_SEND_MAIL_LIST WHERE " + where_id + uniq_id + " IN (" + row_id + ")";
      statement.executeUpdate(sql);

      if (sub_seq.length() != 0) {
        sql = "DELETE FROM T_SEND_MAIL_EMBED WHERE SE_send_mail_id=" + id + " AND SE_sub_seq IN (" + sub_seq + ")";
        statement.executeUpdate(sql);
      }
    }
    statement.close();
  }

  // 文字化け対策用コード変換
  protected static String toJIS(String s) {
    if (s == null)
      return null;

    StringBuffer sb = new StringBuffer();
    char c;
    for (int i = 0; i < s.length(); i++) {
      c  = s.charAt(i);
      switch (c) {
//			case 0xff3c:        // FULLWIDTH REVERSE SOLIDUS ->
//				c = 0x005c;     // REVERSE SOLIDUS
//				break;
      case 0xff5e:        // FULLWIDTH TILDE ->
        c = 0x301c;     // WAVE DASH
        break;
      case 0x2225:        // PARALLEL TO ->
        c = 0x2016;     // DOUBLE VERTICAL LINE
        break;
      case 0xff0d:        // FULLWIDTH HYPHEN-MINUS ->
        c = 0x2212;     // MINUS SIGN
        break;
      case 0xffe0:        // FULLWIDTH CENT SIGN ->
        c = 0x00a2;     // CENT SIGN
        break;
      case 0xffe1:        // FULLWIDTH POUND SIGN ->
        c = 0x00a3;     // POUND SIGN
        break;
      case 0xffe2:        // FULLWIDTH NOT SIGN ->
        c = 0x00ac;     // NOT SIGN
        break;
      case 0x2015:
        c = 0x2014;
        break;
      }
      sb.append(c);
    }
    return new String(sb);
  }

  // エラー報告処理
  private static void error_report(Exception ex, String func_name)
  {
    System.err.println(func_name);
    System.err.println(new Date().toString());
    ex.printStackTrace();
  }

  // メイン処理
  public static void main(String args[])
  {
    int i;

    try {
      read_common_conf();

      // 負荷を分散させるためランダムなウェイト
      if (mail_servers.length >= 2) {
        Random random = new Random();
        java.lang.Thread.sleep(random.nextInt(2000));
      }

      for (i = 0; i < args.length; i++) {
        dbmailv4 dbm = new dbmailv4(args[i]);
        try {
          dbm.conxxxt_db();
          dbm.send_mail();
        } catch (Exception ex) {
          error_report(ex, "main");
        }
        dbm.close_db();
      }
    } catch (Exception ex) {
      error_report(ex, "main");
    }
  }
}
