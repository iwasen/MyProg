// OutputDevice.cpp: COutputDevice クラスのインプリメンテーション
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "vjb30030.h"
#include "OutputDevice.h"

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

//////////////////////////////////////////////////////////////////////
// 構築/消滅
//////////////////////////////////////////////////////////////////////

COutputDevice::COutputDevice()
{

}

COutputDevice::~COutputDevice()
{

}

//
//	機能	：	出力ファイル存在チェック処理
//
//	引数	：	pQueue - キュー情報
//
//	復帰値	：	結果コード
//
//	機能説明：	外部媒体に出力するファイルが存在するかチェックする。
//
//	備考	：	無し
//
int COutputDevice::CheckDstFile(Queue *pQueue)
{
	HANDLE	hdir;
	WIN32_FIND_DATA	findBuf;
	BOOL bFind;

	// コピー元ディレクトリ名取得
	m_sSrcDir = CreatePathName(g_sOutputDir, pQueue->sShikibetsuCode + pQueue->sVolLabel.SpanExcluding(","));

	hdir = FindFirstFile(CreatePathName(m_sSrcDir, "*"), &findBuf);
	if (hdir == INVALID_HANDLE_VALUE)
		return RC_NODIRECTORY;

	bFind = FALSE;
	do {
		if (findBuf.dwFileAttributes & FILE_ATTRIBUTE_DIRECTORY) {
			if (strcmp(findBuf.cFileName, ".") != 0 && strcmp(findBuf.cFileName, "..") != 0) {
				bFind = TRUE;
				break;
			}
		} else {
			bFind = TRUE;
			break;
		}
	} while (FindNextFile(hdir, &findBuf));

	FindClose(hdir);

	if (!bFind)
		return RC_NOFILE;

	return RC_NORMAL;
}

//
//	機能	：　ボリュームラベル取得処理
//
//	引数	：	pPath - デバイスのルートパス名
//				sVolumeName - 取得されたボリュームラベル
//
//	復帰値	：	TRUE - 正常
//				FALSE - エラー（中止）
//
//	機能説明：	外部媒体のボリュームラベルを取得する。
//
//	備考	：	無し
//
BOOL COutputDevice::GetVolumeName(LPCTSTR pPath, CString &sVolumeName)
{
	char cVolumeName[256];
	DWORD dwMaxCompLen;
	DWORD dwFileSystemFlags;
	BOOL bResult;

	// ボリューム名取得
	while (TRUE) {
		bResult = GetVolumeInformation(
		   pPath,				// ルートディレクトリ名へのポインタ
		   cVolumeName,			// ボリューム名を格納するバッファへのポインタ
		   sizeof(cVolumeName),	// lpVolumeNameBuffer バッファのサイズ
		   NULL,				// ボリュームシリアルナンバーを格納する変数へのポインタ
		   &dwMaxCompLen,		// ファイル名構成要素の最大長を格納する変数へのポインタ
		   &dwFileSystemFlags,	// ファイルシステムフラグを格納する変数へのポインタ
		   NULL,				// ファイルシステム名を格納するバッファへのポインタ
		   0					// lpFileSystemNameBuffer バッファのサイズ
		);
		if (bResult)
			break;
		else {
			if (GetLastError() == ERROR_NOT_READY) {
				if (DispMessage(IDS_MSG_NOT_READY, MB_OKCANCEL, FALSE, pPath) == IDCANCEL)
					return FALSE;
			} else
				return FALSE;
		}
	}

	sVolumeName = cVolumeName;

	return TRUE;
}

//
//	機能	：　エラー取得処理
//
//	引数	：	nErrorCode - エラーコード
//				sErrorMsg - エラー内容
//
//	復帰値	：	無し
//
//	機能説明：	エラー取得のデフォルト処理。
//
//	備考	：	必要に応じて継承クラスでオーバーライドする。
//
void COutputDevice::GetError(int &nErrorCode, CString &sErrorMsg)
{
	nErrorCode = 0;
}
