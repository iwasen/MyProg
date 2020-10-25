// InputDevice.cpp: CInputDevice クラスのインプリメンテーション
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "vjb30040.h"
#include "InputDevice.h"

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

//////////////////////////////////////////////////////////////////////
// 構築/消滅
//////////////////////////////////////////////////////////////////////

CInputDevice::CInputDevice()
{
	m_pUpdateWnd = NULL;
}

CInputDevice::~CInputDevice()
{

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
BOOL CInputDevice::GetVolumeName(LPCTSTR pPath, CString &sVolumeName)
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
void CInputDevice::GetError(int &nErrorCode, CString &sErrorMsg)
{
	nErrorCode = 0;
}

//
//	機能	：　リフレッシュウィンド設定処理
//
//	引数	：	pWnd - リフレッシュするウィンドウ
//
//	復帰値	：	無し
//
//	機能説明：	コピー中にリフレッシュするウィンドウを設定する。
//
//	備考	：	無し
//
void CInputDevice::SetUpdateWindow(CWnd *pWnd)
{
	m_pUpdateWnd = pWnd;
}
