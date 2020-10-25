// vjb30040.h : VJB30040 アプリケーションのメイン ヘッダー ファイルです。
//

#if !defined(AFX_VJB30040_H__3FD25355_9ABD_11D4_ADFB_00105A67EE03__INCLUDED_)
#define AFX_VJB30040_H__3FD25355_9ABD_11D4_ADFB_00105A67EE03__INCLUDED_

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
#define RC_VOLUMENAME	1				// ボリュームラベルが違う
#define RC_IOERROR		2				// I/O エラー
#define RC_CANCEL		3				// 処理がキャンセルされた

// 入力パラメータ
struct Param {
	int nBaitaiShubetsu;
	CString sShubetsuCode;
	CString sShikibetsuCode;
	CString sBatchServer;
	CString sVolLabel;
	BOOL bLabel;
	int nVolCount;
};

// グローバル変数
extern CString g_sPathFPD;
extern CString g_sPathMOD;
extern CString g_sInputDir;
extern CString g_sDatDir;
extern CString g_sLogDir;
extern CString g_BatchServerFile;

/////////////////////////////////////////////////////////////////////////////
// CVjb30040App:
// このクラスの動作の定義に関しては vjb30040.cpp ファイルを参照してください。
//

class CVjb30040App : public CWinApp
{
public:
	CVjb30040App();

// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。
	//{{AFX_VIRTUAL(CVjb30040App)
	public:
	virtual BOOL InitInstance();
	//}}AFX_VIRTUAL

// インプリメンテーション

	//{{AFX_MSG(CVjb30040App)
		// メモ - ClassWizard はこの位置にメンバ関数を追加または削除します。
		//        この位置に生成されるコードを編集しないでください。
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

private:
	void ReadIniFile();
};


/////////////////////////////////////////////////////////////////////////////

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ は前行の直前に追加の宣言を挿入します。

#endif // !defined(AFX_VJB30040_H__3FD25355_9ABD_11D4_ADFB_00105A67EE03__INCLUDED_)
