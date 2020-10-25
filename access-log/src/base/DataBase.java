package base;
import java.io.*;
import java.sql.*;
import java.util.*;

/**
 * WEB-INSIGHT<br>
 * データベースアクセス処理
 @author	Shinichi Aizawa 2000/03/21<br>
			新規作成<br>
			Toshinobu Yanahara 2000/12/22 update<br>
			引用、改訂
 */
public class DataBase extends Object
{
  private Conxxxtion con;
  private Statement  stmt;
  private ResultSet rsContrxxxaster;
  static public String propPath;

  /**
  * コンストラクタ
  */
  public DataBase() throws SQLException
  {
    String db_url;
    String db_user;
    String db_pass;

    try {
      // プロパティファイルからDB情報取得
			FileInputStream fin = new FileInputStream(new File(WebAppInitializer.propertyPath));
			Properties prop = new Properties();
			prop.load(fin);
			db_url = prop.getProperty("db_url", "jdbc:oracle:thin:@localhost:1521:xxx1");
      db_user = prop.getProperty("db_user", "user02");
      db_pass = prop.getProperty("db_pass", "user02");
      fin.close();
    } catch (Exception ex) {
      ex.printStackTrace();
      System.out.println("Property file not found.");
      return;
    }

    try {
      Class.forName("oracle.jdbc.driver.OracleDriver");
      con = DriverManager.getConxxxtion(db_url, db_user, db_pass);
    } catch (Exception ex) {
      ex.printStackTrace();
      System.out.println("DB接続エラーです。");
    }
  }

  /**
  * ファイナライザ
  */
  protected void finalize() throws SQLException
  {
    close();
  }

  /**
  * DBクローズ
  */
  public void close() throws SQLException
  {
    if (con != null) {
      con.close();
      con = null;
    }
  }

  /**
  * Statement作成処理
  */
  public Statement createStatement() throws SQLException
  {
    // Statementが無ければ作成（Statementは使いまわす）
    if (stmt == null)
      stmt = con.createStatement();

    return stmt;
  }

  /**
  * コントロールマスタ読み込み（いろいろな場所で何度もDBにアクセスするのを避けるためここで一括して読む）
  */
  public ResultSet getContrxxxaster() throws SQLException {
    if (rsContrxxxaster == null) {
      rsContrxxxaster = con.createStatement().executeQuery("select * from M_CONTROL");
      rsContrxxxaster.next();
    }

    return rsContrxxxaster;
  }
}
