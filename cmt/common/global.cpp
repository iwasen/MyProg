#include "stdafx.h"
#include <lm.h>

#define	IDS_PROGRAM_ID	1				// プログラムIDリソース番号
#define INI_DIR		"VJBINIDIR"			// INIファイルのディレクトリが設定された環境変数名
#define INI_FILE	"VJB30010.INI"		// INIファイル名
#define INI_DEF_FILE	".\\baitai.ini"	// 環境変数が設定されていない場合のINIファイル名

//
//	機能	：	iniファイル読み込み処理（文字）
//
//	引数	：	pSection - セクション名
//			：	pEntry - エントリ名
//			：	pDefault - デフォルトデータ
//
//	復帰値	：	パス名
//
//	機能説明：	iniファイルから指定された文字データを取り出す。
//
//	備考	：	無し
//
CString GetIniData(LPCTSTR pSection, LPCTSTR pEntry, LPCTSTR pDefault)
{
	char iniFile[256];
	char buf[256];

	// 環境変数よりINIファイル名取得
	if (GetEnvironmentVariable(INI_DIR, iniFile, sizeof(iniFile)) == 0)
		strcpy(iniFile, INI_DEF_FILE);
	else
		strcat(iniFile, INI_FILE);

	GetPrivateProfileString(pSection, pEntry, pDefault, buf, sizeof(buf), iniFile);
	return buf;
}

//
//	機能	：	iniファイル読み込み処理（数値）
//
//	引数	：	pSection - セクション名
//			：	pEntry - エントリ名
//			：	nDefault - デフォルトデータ
//
//	復帰値	：	パス名
//
//	機能説明：	iniファイルから指定された数値データを取り出す。
//
//	備考	：	無し
//
int GetIniData(LPCTSTR pSection, LPCTSTR pEntry, int nDefault)
{
	char iniFile[256];

	// 環境変数よりINIファイル名取得
	if (GetEnvironmentVariable(INI_DIR, iniFile, sizeof(iniFile)) == 0)
		strcpy(iniFile, INI_DEF_FILE);
	else
		strcat(iniFile, INI_FILE);

	return GetPrivateProfileInt(pSection, pEntry, nDefault, iniFile);
}

//
//	機能	：	パス名作成処理
//
//	引数	：	pDirName - ディレクトリ名
//			：	pFileName - ファイル名
//
//	復帰値	：	パス名
//
//	機能説明：	ディレクトリ名とファイル名からパス名を作成する。
//
//	備考	：	無し
//
CString CreatePathName(LPCTSTR pDirName, LPCTSTR pFileName)
{
	CString sPathName;

	sPathName = pDirName;
	if (sPathName.Right(1) == "\\")
		return sPathName + pFileName;
	else
		return sPathName + "\\" + pFileName;
}

//
//	機能	：	ディレクトリ削除処理
//
//	引数	：	pDirName - ディレクトリ名
//			：	bDeleteTop - 最上位ディレクトリを削除するかどうかの指定
//
//	復帰値	：	TRUE - 正常
//				FALSE - エラー
//
//	機能説明：	指定されたディレクト以下をサブディレクトリも含めて削除する。
//
//	備考	：	無し
//
BOOL DeleteDirectory(LPCTSTR pDirName, BOOL bDeleteTop)
{
	HANDLE	hdir;
	WIN32_FIND_DATA	findBuf;
	CString sPathName;

	hdir = FindFirstFile(CreatePathName(pDirName, "*"), &findBuf);
	if (hdir != INVALID_HANDLE_VALUE) {
		do {
			sPathName = CreatePathName(pDirName, findBuf.cFileName);
			if (findBuf.dwFileAttributes & FILE_ATTRIBUTE_DIRECTORY) {
				if (strcmp(findBuf.cFileName, ".") != 0 && strcmp(findBuf.cFileName, "..") != 0) {
					if (!DeleteDirectory(sPathName, TRUE)) {
						FindClose(hdir);
						return FALSE;
					}
				}
			} else {
				if (!DeleteFile(sPathName)) {
					FindClose(hdir);
					return FALSE;
				}
			}
		} while (FindNextFile(hdir, &findBuf));

		FindClose(hdir);
	}

	if (bDeleteTop && strlen(pDirName) > 3) {
		if (!RemoveDirectory(pDirName))
			return FALSE;
	}

	return TRUE;
}

//
//	機能	：	ディレクトリコピー処理
//
//	引数	：	pSrcDir - コピー元ディレクトリ名
//			：	pDstDir - コピー先ディレクトリ名
//			：	pWnd - コピー中にリフレッシュするウィンドウ
//
//	復帰値	：	TRUE - 正常
//				FALSE - エラー
//
//	機能説明：	指定されたディレクト以下をサブディレクトリも含めてコピーする。
//
//	備考	：	無し
//
BOOL CopyDirectory(LPCTSTR pSrcDir, LPCTSTR pDstDir, CWnd *pWnd)
{
	CString sSrcFile, sDstFile;
	HANDLE	hdir;
	WIN32_FIND_DATA	findBuf;

	hdir = FindFirstFile(CreatePathName(pSrcDir, "*"), &findBuf);
	if (hdir != INVALID_HANDLE_VALUE) {
		do {
			sSrcFile = CreatePathName(pSrcDir, findBuf.cFileName);
			sDstFile = CreatePathName(pDstDir, findBuf.cFileName);
			if (findBuf.dwFileAttributes & FILE_ATTRIBUTE_DIRECTORY) {
				if (strcmp(findBuf.cFileName, ".") != 0 && strcmp(findBuf.cFileName, "..") != 0) {
					if (!CreateDirectory(sDstFile, NULL)) {
						FindClose(hdir);
						return FALSE;
					}

					if (!CopyDirectory(sSrcFile, sDstFile, pWnd)) {
						FindClose(hdir);
						return FALSE;
					}
				}
			} else {
				if (!CopyFile(sSrcFile, sDstFile, FALSE)) {
					FindClose(hdir);
					return FALSE;
				}

				if (pWnd != NULL)
					pWnd->UpdateWindow();
			}
		} while (FindNextFile(hdir, &findBuf));

		FindClose(hdir);
	}

	return TRUE;
}

//
//	機能	：	メッセージ送信処理
//
//	引数	：	pComputerName - 送信先コンピュータ名
//			：	pMessage - 送信するメッセージ
//
//	復帰値	：	無し
//
//	機能説明：	指定されたコンピュータにメッセージを送信する。
//
//	備考	：	無し
//
void NetSendMessage(LPCTSTR pComputerName, LPCTSTR pMessage)
{
	WCHAR wbuf1[128], wbuf2[1024];

	if (*pComputerName != '\0') {
		MultiByteToWideChar(CP_ACP, 0, pComputerName, -1, wbuf1, sizeof(wbuf1));
		int n = MultiByteToWideChar(CP_ACP, 0, pMessage, -1, wbuf2, sizeof(wbuf2));
		NetMessageBufferSend(NULL, wbuf1, NULL, (LPBYTE)wbuf2, n * 2);
	}
}

//
//	機能	：	プロセス終了待ち処理
//
//	引数	：	hProcess - プロセスハンドル
//
//	復帰値	：	プロセスの終了コード
//
//	機能説明：	指定されたプロセスが終了するまで待つ。
//
//	備考	：	無し
//
DWORD ProcessWait(HANDLE hProcess)
{
    DWORD dwExitCode;

    while (TRUE) {
        GetExitCodeProcess(hProcess ,&dwExitCode) ;

		if (dwExitCode != STILL_ACTIVE )
			break;

		Sleep(1000);
    }

    CloseHandle(hProcess);

    return dwExitCode;
}

//
//	機能	：	メッセージ表示処理
//
//	引数	：	nMsgID - メッセージID
//			：	nBottumIcon - ボタン種別 ＆ アイコン種別
//			：	bSendMsg - クライアントにメッセージ送信の有無
//			：	... - メッセージに埋め込む情報
//
//	復帰値	：	押されたボタン
//
//	機能説明：	指定したメッセージIDのメッセージを表示する。
//
//	備考	：	無し
//
int DispMessage(UINT nMsgID, int nBottum, BOOL bSendMsg, ...)
{
	CString sMsg, sCode, sType, sBody;
	CString sCaption, sText;
	CString sProgramID;
	va_list args;
	va_start(args, bSendMsg);

	// リソースからメッセージ内容を取得
	sMsg.LoadString(nMsgID);

	// メッセージを分解
	sCode = sMsg.SpanExcluding("\n");
	sBody.FormatV(sMsg.Mid(sCode.GetLength() + 1), args);

	// 種類を判断
	switch (sCode[sCode.GetLength() - 1]) {
	case 'I':
		sType = "情報";
		nBottum |= MB_ICONINFORMATION;
		break;
	case 'W':
		sType = "警告";
		nBottum |= MB_ICONEXCLAMATION;
		break;
	case 'E':
		sType = "エラー";
		nBottum |= MB_ICONSTOP;
		break;
	case 'S':
		sType = "重大なエラー";
		nBottum |= MB_ICONSTOP;
		break;
	case 'Q':
		sType = "確認";
		nBottum |= MB_ICONQUESTION;
		break;
	case 'P':
		sType = "状態";
		break;
	default:
		sType = "メッセージ";
		break;
	}

	// タイトル編集
	sProgramID.LoadString(IDS_PROGRAM_ID);
	sCaption.Format("%s(%s)", sType, sProgramID);
	
	// メッセージ編集
	sText.Format("%s\n\n%s", sCode, sBody);
	
	// 運用管理クライアントにメッセージ送信
	if (bSendMsg)
		NetSendMessage(GetIniData("OperatorClient", "ComputerName", ""), sText);

	// メッセージを表示
	return AfxGetMainWnd()->MessageBox(sText, sCaption, nBottum);
}
