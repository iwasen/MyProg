// vjb30050.h : VJB30050 アプリケーションのメイン ヘッダー ファイルです。
//

#if !defined(AFX_VJB30050_H__39C04A94_BAC8_4B0A_A626_B3C62533925B__INCLUDED_)
#define AFX_VJB30050_H__39C04A94_BAC8_4B0A_A626_B3C62533925B__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

#ifndef __AFXWIN_H__
	#error include 'stdafx.h' before including this file for PCH
#endif

#include "resource.h"		// メイン シンボル
#include "global.h"

// リターンコード
#define RC_NORMAL		0				// 正常終了
#define RC_PARAMETER	1				// パラメータが不正
#define RC_STATUS		2				// キューファイルの状態コードが不正
#define RC_VOLUMENAME	3				// ボリュームラベルが違う
#define RC_IOERROR		4				// I/O エラー
#define RC_CANCEL		5				// 処理がキャンセルされた
#define RC_MULTIFILEVOL	6				// ラベル無しのマルチファイル／マルチボリューム

struct Param {
	CString sShubetsuCode;
	CString sBatchServer;
	CString sShikibetsuCode;
	CString sVolLabel;
};

extern CString g_sLogDir;
extern CString g_sInputDir;
extern CString g_sDatDir;
extern CString g_sBatchUNC;

/////////////////////////////////////////////////////////////////////////////
// CVjb30050App:
// このクラスの動作の定義に関しては vjb30050.cpp ファイルを参照してください。
//

class CVjb30050App : public CWinApp
{
public:
	CVjb30050App();

// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。
	//{{AFX_VIRTUAL(CVjb30050App)
	public:
	virtual BOOL InitInstance();
	//}}AFX_VIRTUAL

// インプリメンテーション

	//{{AFX_MSG(CVjb30050App)
		// メモ - ClassWizard はこの位置にメンバ関数を追加または削除します。
		//        この位置に生成されるコードを編集しないでください。
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

private:
	int m_nRetCode;

	int Main(int argc, char *argv[]);
	void ReadIniFile();
	void WriteLogFile(Param *pParam, LPCTSTR pLogFile, LPCTSTR pStatus);
};


/////////////////////////////////////////////////////////////////////////////

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ は前行の直前に追加の宣言を挿入します。

#endif // !defined(AFX_VJB30050_H__39C04A94_BAC8_4B0A_A626_B3C62533925B__INCLUDED_)
