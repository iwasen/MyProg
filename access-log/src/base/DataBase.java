package base;
import java.io.*;
import java.sql.*;
import java.util.*;

/**
 * WEB-INSIGHT<br>
 * �f�[�^�x�[�X�A�N�Z�X����
 @author	Shinichi Aizawa 2000/03/21<br>
			�V�K�쐬<br>
			Toshinobu Yanahara 2000/12/22 update<br>
			���p�A����
 */
public class DataBase extends Object
{
  private Conxxxtion con;
  private Statement  stmt;
  private ResultSet rsContrxxxaster;
  static public String propPath;

  /**
  * �R���X�g���N�^
  */
  public DataBase() throws SQLException
  {
    String db_url;
    String db_user;
    String db_pass;

    try {
      // �v���p�e�B�t�@�C������DB���擾
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
      System.out.println("DB�ڑ��G���[�ł��B");
    }
  }

  /**
  * �t�@�C�i���C�U
  */
  protected void finalize() throws SQLException
  {
    close();
  }

  /**
  * DB�N���[�Y
  */
  public void close() throws SQLException
  {
    if (con != null) {
      con.close();
      con = null;
    }
  }

  /**
  * Statement�쐬����
  */
  public Statement createStatement() throws SQLException
  {
    // Statement��������΍쐬�iStatement�͎g���܂킷�j
    if (stmt == null)
      stmt = con.createStatement();

    return stmt;
  }

  /**
  * �R���g���[���}�X�^�ǂݍ��݁i���낢��ȏꏊ�ŉ��x��DB�ɃA�N�Z�X����̂�����邽�߂����ňꊇ���ēǂށj
  */
  public ResultSet getContrxxxaster() throws SQLException {
    if (rsContrxxxaster == null) {
      rsContrxxxaster = con.createStatement().executeQuery("select * from M_CONTROL");
      rsContrxxxaster.next();
    }

    return rsContrxxxaster;
  }
}
