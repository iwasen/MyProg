package base;

import java.sql.*;

/**
 * タイトル:
 * 説明:
 * 著作権:   Copyright (c) 2003
 * 会社名:
 * @author
 * @version 1.0
 */

public interface I_Access_Page {
  public void searchDB(DataBase db, SelectCondBean selectcondBean);
}