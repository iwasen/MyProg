//**********************************************************************
//		快楽市場.co.jp バスケットシステム
//
//		Title:		マルチパートフォーム処理
//		FileName:	MultiPart.java
//		Creator:	Shinichi Aizawa
//		CreateDate:	2000/04/14
//**********************************************************************

import java.io.IOException;
import java.io.ByteArrayOutputStream;
import java.util.Hashtable;
import java.io.File;
import java.io.FileOutputStream;
import java.io.FileNotFoundException;
import java.util.Enumeration;
import javax.servlet.ServletInputStream;
import javax.servlet.http.HttpServletRequest;

// multi part フォーム処理クラス
public class MultiPart {
	private String boundary;
	private Hashtable table;

	// コンストラクタ
	public MultiPart(HttpServletRequest request) throws IOException {

		// ContentTypeチェック
		CheckContentType(request);

		// multi part部解析
		table = Parse(request);
	}

	// ContentTypeチェック処理
	private void CheckContentType(HttpServletRequest request) throws IOException {
		// By default, 1Mb max file size per file;
		int maxSize = 1024*1024*1; // 1MB
		if (!request.getContentType().toLowerCase().startsWith("multipart/form-data")) {
			// FORM の ContentType をチェック。
			// このエラーは発生しないはず。
			throw new IllegalArgumentException("FORM で設定された content-type が正しくありません。");
		}

		// サイズのチェック。
		if (request.getContentLength() > maxSize) {
			throw new IllegalArgumentException("サイズが大きすぎます。"+maxSize+" 以下にして下さい。");
		}

		// boundary のチェック。
		// RFC1867 非対応のブラウザで発生するかも。
		int ind = request.getContentType().indexOf("boundary=");
		if (ind == -1) {
			throw new IllegalArgumentException("RFC1867(Form Based File Upload) 対応ブラウザをお使い下さい。");
		}

		boundary = request.getContentType().substring(ind+9);

		if (boundary == null) {
			throw new IllegalArgumentException("boundary で何も指定されていません。");
		}
	}

	// multi part部解析処理
	private Hashtable Parse(HttpServletRequest request) throws IOException {
		final String enc = "iso-2022-jp";
		ServletInputStream in = request.getInputStream();
		int buffSize = 1024*8; // 8K
		Hashtable hash = new Hashtable();
		int result;
		String line;
		String lowerline;
		String boundaryStr = "--"+boundary;
		ByteArrayOutputStream content;
		String filename;
		String contentType;
		String name;
		String value;

		byte[] b = new byte[buffSize];

		result = in.readLine(b,0,b.length);
		// failure.
		if (result == -1) 
			throw new IllegalArgumentException("InputStream truncated");
		line = new String(b,0,result,enc);
		// failure.
		if (!line.startsWith(boundaryStr)) 
			throw new IllegalArgumentException("MIME boundary missing: "+line);
		while (true) {
			// Some initialization
			filename = null;
			contentType = null;
			content = new ByteArrayOutputStream();
			name = null;

			// get next line (should be content disposition)
			result = in.readLine(b,0,b.length);
			if (result == -1) return hash;
			line = new String(b,0,result-2,enc);
			lowerline = line.toLowerCase();
			if (!lowerline.startsWith("content-disposition"))
				// don't know what to do, so we'll keep looking...
				continue;
			// determine what the disposition is
			int ind = lowerline.indexOf("content-disposition: ");
			int ind2 = lowerline.indexOf(";");
			if (ind == -1 || ind2 == -1) 
				throw new IllegalArgumentException("Content Disposition line misformatted: "+line);
			String disposition = lowerline.substring(ind+21,ind2);
			if (!disposition.equals("form-data"))
				throw new IllegalArgumentException("Content Disposition of "+disposition+" is not supported");
			// determine what the name is
			int ind3 = lowerline.indexOf("name=\"",ind2);
			int ind4 = lowerline.indexOf("\"",ind3+7);
			if (ind3 == -1 || ind4 == -1) 
				throw new IllegalArgumentException("Content Disposition line misformatted: "+line);
			name = line.substring(ind3+6,ind4);
			// determine filename, if any
			int ind5 = lowerline.indexOf("filename=\"",ind4+2);
			int ind6 = lowerline.indexOf("\"",ind5+10);
			if (ind5 != -1 && ind6 != -1) {
				filename = line.substring(ind5+10,ind6);
				int ind7 = filename.lastIndexOf("\\");
				filename = filename.substring(ind7+1);
			}

			// Whew!  We now move onto the next line, which
			// will either be blank, or Content-Type, followed by blank.
			result = in.readLine(b,0,b.length);
			if (result == -1) return hash;
			line = new String(b,0,result-2,enc); // -2 to remove \r\n
			lowerline = line.toLowerCase();
			if (lowerline.startsWith("content-type")) {
				int ind7 = lowerline.indexOf(" ");
				if (ind7 == -1) 
					throw new IllegalArgumentException("Content-Type line misformatted: "+line);
				contentType = lowerline.substring(ind7+1);
				//  read blank header line
				result = in.readLine(b,0,b.length);
				if (result == -1) return hash;
				line = new String(b,0,result-2,enc); // -2 to remove \r\n
				if (line.length() != 0) {
					throw new IllegalArgumentException("Unexpected line in MIMEpart header: "+line);
				}
			} else if (line.length() != 0) {
				throw new IllegalArgumentException("Misformatted line following disposition: "+line);
			}
						
			//read content, implement readahead by one line
			boolean readingContent = true;
			boolean firstLine = true;
			byte[] buffbytes = new byte[buffSize];
			int buffnum = 0;

			result = in.readLine(b,0,b.length);
			if (result == -1) return hash;
			line = new String(b,0,result,enc); 
			if (!line.startsWith(boundaryStr)) {
				System.arraycopy(b,0,buffbytes,0,result);
				buffnum = result;
				result = in.readLine(b,0,b.length);
				if (result == -1) return hash;
				line = new String(b,0,result,enc); 
				firstLine = false;
				if (line.startsWith(boundaryStr)) {
					readingContent = false;
				}
			} else {
				readingContent = false;
			}

			while (readingContent) {
				content.write(buffbytes,0,buffnum);
				System.arraycopy(b,0,buffbytes,0,result);
				buffnum = result;
				result = in.readLine(b,0,b.length);
				if (result == -1) return hash;
				line = new String(b,0,result,enc); 
				if (line.startsWith(boundaryStr)) readingContent = false;
			}
			if (!firstLine) {
				// -2 to trim \r\n
				if (buffnum>2)
					content.write(buffbytes,0,buffnum-2); 
			}

			//now set appropriate variable, populate hashtable
			if (filename == null) {
				if (hash.get(name) == null) {
					String[] values = new String[1];
					values[0] = content.toString(enc);
					hash.put(name,values);
				} else {
					Object prevobj = hash.get(name);
					if (prevobj instanceof String[]) {
						String[] prev = (String[])prevobj;
						String[] newStr = new String[prev.length+1];
						System.arraycopy(prev,0,newStr,0,prev.length);
						newStr[prev.length] = content.toString(enc);
						hash.put(name,newStr);
					} else {
						//now what? I think this breaks the standard.
						throw new IllegalArgumentException("failure in parseMulti hashtable building code");
					}
				}
			} else if (!filename.equals("")) {
				// Yes, we don't return Hashtable[] for multiple files of same name.  AFAIK, that's not allowed.
				Hashtable filehash = new Hashtable(4);
				filehash.put("name",name);
				filehash.put("filename",filename);
				if (contentType == null) contentType = "application/octet-stream";
				filehash.put("content-type",contentType);
				filehash.put("content",content.toByteArray());
				hash.put(name,filehash);
			}
		}
	}

	// 受信ファイル書き込み処理
	public void CreateFile(String path) throws IOException {
		String filename = null;
		byte[] bytes = null;

		for (Enumeration fields = table.keys(); fields.hasMoreElements(); ) {
			String name = (String)fields.nextElement();

			Object obj = table.get(name);
			if (obj instanceof Hashtable) {
					// its a file!
				Hashtable filehash = (Hashtable)obj;
				filename = (String)filehash.get("filename");
				String contentType = (String)filehash.get("content-type");
				obj = filehash.get("content");
				bytes = (byte[])obj;

				if (filename == null)
					throw new FileNotFoundException("filename("+filename+") is incorrect");
				File userFile = new File(path,filename);
				FileOutputStream out = new FileOutputStream(userFile);
				try {
					out.write(bytes, 0, bytes.length);
				} catch (IOException e) {
					throw e;
				}
				out.close();
			}
		}
	}

	// パラメータ取得処理
	public String GetParameter(String paramname) {
		for (Enumeration fields = table.keys(); fields.hasMoreElements(); ) {
			String name = (String)fields.nextElement();

			if (name.equals(paramname)) {
				Object obj = table.get(name);
				if (obj instanceof String[]) {
					return ((String[])obj)[0];
				} else if (obj instanceof Hashtable) {
					Hashtable filehash = (Hashtable)obj;
					return (String)filehash.get("filename");
				}
			}
		}
		return null;
	}
}

