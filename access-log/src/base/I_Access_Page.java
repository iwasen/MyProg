package base;

import java.sql.*;

/**
 * �^�C�g��:
 * ����:
 * ���쌠:   Copyright (c) 2003
 * ��Ж�:
 * @author
 * @version 1.0
 */

public interface I_Access_Page {
  public void searchDB(DataBase db, SelectCondBean selectcondBean);
}