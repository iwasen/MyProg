package jp.co.troot.llog;

import android.os.StrictMode;
import java.sql.Conxxxtion;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.Statement;
import java.util.Date;
import java.util.Locale;
import java.text.SimpleDateFormat;

class Database {
	private Conxxxtion mCon;
	private Statement mStmt;

	Database() throws Exception {
		StrictMode.setThreadPolicy(new StrictMode.ThreadPolicy.Builder().permitAll().build());
		Class.forName("org.postgresql.Driver");

		mCon = DriverManager.getConxxxtion("jdbc:postgresql://inet.troot.co.jp/llog", "postgres", "");
		mStmt = mCon.createStatement();
	}

	ResultSet query(String sql) throws Exception {
		return mStmt.executeQuery(sql);
	}

	void exec(String sql) throws Exception {
		mStmt.execute(sql);
	}

	void close() throws Exception {
		mStmt.close();
		mCon.close();
	}

	static String sqlDate(Date date) {
		SimpleDateFormat sdf = new SimpleDateFormat("''yyyy'-'MM'-'dd''", Locale.JAPANESE);
		return sdf.format(date);
	}

	static String sqlString(String str) {
        if (str.length() == 0)
            return "null";
        else
    	    return "'" + str.replace("'", "\\'") + "'";
	}
/*
    static String sqlNumber(String str) {
        if (str.length() == 0)
            return "null";
        else
            return str;
    }
*/
    static String sqlStringArray(String[] array) {
        return "'{\"" + MyUtils.join(array, "\",\"") + "\"}'";
    }
/*
    public static String sqlBoolean(boolean b) {
        return b ? "'t'" : "'f'";
    }
*/
}
