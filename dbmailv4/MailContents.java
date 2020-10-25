
/**
 * É^ÉCÉgÉã:     DB Mail Version 3<p>
 * ê‡ñæ:         <p>
 * íòçÏå†:       Copyright (c) aizawa<p>
 * âÔé–ñº:       Space Technology Co.,Ltd.<p>
 * @author aizawa
 * @version 1.0
 */

import java.io.*;
import java.sql.*;
import java.util.*;
import javax.mail.*;
import javax.mail.internet.*;
import javax.activation.*;

public class MailContents {

  String mail_type;
  MimeBodyPart text_part;
  MimeMultipart multi_part;

  public void setAttachment(Conxxxtion conxxxtion, long id, String database) throws Exception
  {
    Hashtable att_table = new Hashtable();
    Blob blob;
    byte[] bin;
//                InputStream is = null;
    Attachment attachment;
    Enumeration enum;
    MimeBodyPart file_part;
    MimeBodyPart related_part;
    boolean mixed = false;
    MimetypesFileTypeMap file_type = new MimetypesFileTypeMap();
    String content_type;

    Statement statement = conxxxtion.createStatement();
    String sql = "SELECT SF_file_name,SF_content_id,SF_file_content,SF_file_id FROM T_SEND_MAIL_FILE WHERE SF_send_mail_id=" + id + " ORDER BY SF_file_id";
    ResultSet resultset = statement.executeQuery(sql);
    while (resultset.next()) {
      attachment = new Attachment();

      attachment.file_name = resultset.getString("SF_file_name");
      attachment.content_id = resultset.getString("SF_content_id");

      if (database.compareToIgnoreCase("postgresql") == 0) {
        ResultSetMetaData rsmd = resultset.getMetaData();
        String ct = rsmd.getColumnTypeName(3);
        if (ct.equals("oid")) {
          conxxxtion.setAutoCommit(false);
          try {
            blob = resultset.getBlob("SF_file_content");
            attachment.bin = blob.getBytes(0, (int) blob.length());
          }
          finally {
            conxxxtion.setAutoCommit(true);
          }
        } else if (ct.equals("bytea")) {
          attachment.bin = resultset.getBytes("SF_file_content");
        }
      } else if (database.compareToIgnoreCase("mssqlserver") == 0) {
        bin = resultset.getBytes("SF_file_content");
        attachment.is = new ByteArrayInputStream(bin);
        conxxxtion.setAutoCommit(true);
      } else {
        blob = resultset.getBlob("SF_file_content");
        attachment.bin = blob.getBytes(1, (int)blob.length());
      }

      att_table.put(resultset.getString("SF_file_id"), attachment);
    }

    // multipart/related
    for (enum = att_table.elements(); enum.hasMoreElements(); ) {
      attachment = (Attachment)enum.nextElement();
      if (attachment.content_id != null) {
        if (multi_part == null) {
          text_part = new MimeBodyPart();
          multi_part = new MimeMultipart("related");
          multi_part.addBodyPart(text_part);
        }
        file_part = new MimeBodyPart();
        content_type = file_type.getContentType(attachment.file_name);
        if (attachment.is != null)
          file_part.setDataHandler(new DataHandler(new ByteArrayDataSource(attachment.is, content_type)));
        else
            file_part.setDataHandler(new DataHandler(new ByteArrayDataSource(attachment.bin, content_type)));
//				file_part.setContent(attachment.bin, "application/octet-stream");
        file_part.setHeader("Content-ID", attachment.content_id);
        multi_part.addBodyPart(file_part);
      }
    }

    // multipart/mixed
    for (enum = att_table.elements(); enum.hasMoreElements(); ) {
      attachment = (Attachment)enum.nextElement();
      if (attachment.content_id == null) {
        if (multi_part == null) {
          text_part = new MimeBodyPart();
          multi_part = new MimeMultipart();
          multi_part.addBodyPart(text_part);
        } else if (!mixed) {
          related_part = new MimeBodyPart();
          related_part.setContent(multi_part);
          multi_part = new MimeMultipart();
          multi_part.addBodyPart(related_part);
          mixed = true;
        }
        file_part = new MimeBodyPart();
        content_type = file_type.getContentType(attachment.file_name);
        if (attachment.is != null)
          file_part.setDataHandler(new DataHandler(new ByteArrayDataSource(attachment.is, content_type)));
        else
            file_part.setDataHandler(new DataHandler(new ByteArrayDataSource(attachment.bin, content_type)));
//				file_part.setContent(attachment.bin, "application/octet-stream");
        file_part.setFileName(MimeUtility.encodeText(attachment.file_name, "iso-2022-jp", "B"));
        multi_part.addBodyPart(file_part);
      }
    }
  }

  public void setText(MimeMessage msg, String text, String mail_type) throws Exception
  {
    if (multi_part == null) {
      if (mail_type == null || mail_type.equals("0")) {
        msg.setText(text, "iso-2022-jp");
      } else if (mail_type.equals("1")) {
        msg.setContent(text, "text/html; charset=iso-2022-jp");
      }
    } else {
      if (mail_type == null || mail_type.equals("0")) {
        text_part.setText(text, "iso-2022-jp");
        msg.setContent(multi_part);
      } else if (mail_type.equals("1")) {
        text_part.setContent(text, "text/html; charset=iso-2022-jp");
        msg.setContent(multi_part);
      }
    }
  }

  private class Attachment
  {
    public String file_name;
    public String content_id;
    public byte[] bin;
    public InputStream is;
  }
}