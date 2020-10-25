// vjb30020.h : VJB30020 アプリケーションのメイン ヘッダー ファイルです。
//

#if !defined(AFX_VJB30020_H__33D62399_5984_4ECA_94D3_9E326EE00F1F__INCLUDED_)
#define AFX_VJB30020_H__33D62399_5984_4ECA_94D3_9E326EE00F1F__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

#ifndef __AFXWIN_H__
	#error include 'stdafx.h' before including this file for PCH
#endif

#include "resource.h"		// メイン シンボル
#include "global.h"

extern CString g_sQueueDir;
extern int g_nTimer;

/////////////////////////////////////////////////////////////////////////////
// CVjb30020App:
// このクラスの動作の定義に関しては vjb30020.cpp ファイルを参照してください。
//

class CVjb30020App : public CWinApp
{
public:
	CVjb30020App();

// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。
	//{{AFX_VIRTUAL(CVjb30020App)
	public:
	virtual BOOL InitInstance();
	//}}AFX_VIRTUAL

// インプリメンテーション

	//{{AFX_MSG(CVjb30020App)
		// メモ - ClassWizard はこの位置にメンバ関数を追加または削除します。
		//        この位置に生成されるコードを編集しないでください。
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

private:
	HANDLE m_hMutex;

	void ReadIniFile();
};


/////////////////////////////////////////////////////////////////////////////

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ は前行の直前に追加の宣言を挿入します。

#endif // !defined(AFX_VJB30020_H__33D62399_5984_4ECA_94D3_9E326EE00F1F__INCLUDED_)
