package base;

import base.*;
import javax.servlet.*;
import javax.servlet.http.*;
import java.io.*;
import java.util.*;

/**
 * <p>タイトル: </p>
 * <p>説明: </p>
 * <p>著作権: Copyright (c) 2003</p>
 * <p>会社名: </p>
 * @author 未入力
 * @version 1.0
 */

public class WebAppInitializer extends HttpServlet {
  public static String propertyPath;

  public void init() throws ServletException {
    // プロパティファイルのパスを設定
    propertyPath = getServletContext().getRealPath("/WEB-INF/server.properties");
  }
}