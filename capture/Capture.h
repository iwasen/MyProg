// Capture.h : CAPTURE アプリケーションのメイン ヘッダー ファイル
//

#ifndef __AFXWIN_H__
	#error include 'stdafx.h' before including this file for PCH
#endif

#include "resource.h"       // メイン シンボル

#define MODE_AUTO		0
#define MODE_PREVIEW	1
#define MODE_STOP		2

/////////////////////////////////////////////////////////////////////////////
// CCaptureApp:
// このクラスの動作の定義に関しては Capture.cpp ファイルを参照してください。
//

class CCaptureApp : public CWinApp
{
public:
	CCaptureApp();

// オーバーライド
	// ClassWizard は仮想関数を生成しオーバーライドします。
	//{{AFX_VIRTUAL(CCaptureApp)
	public:
	virtual BOOL InitInstance();
	//}}AFX_VIRTUAL

// インプリメンテーション

	//{{AFX_MSG(CCaptureApp)
	afx_msg void OnAppAbout();
		// メモ - ClassWizard はこの位置にメンバ関数を追加または削除します。
		//        この位置に生成されるコードを編集しないでください。
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()
};


/////////////////////////////////////////////////////////////////////////////

