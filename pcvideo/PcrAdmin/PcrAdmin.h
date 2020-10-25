// PcrAdmin.h : PCRADMIN アプリケーションのメイン ヘッダー ファイルです。
//

#ifndef __AFXWIN_H__
	#error include 'stdafx.h' before including this file for PCH
#endif

#include "resource.h"		// メイン シンボル

/////////////////////////////////////////////////////////////////////////////
// CPcrAdminApp:
// このクラスの動作の定義に関しては PcrAdmin.cpp ファイルを参照してください。
//

class CPcrAdminApp : public CWinApp
{
public:
	CPcrAdminApp();

// オーバーライド
	// ClassWizard は仮想関数を生成しオーバーライドします。
	//{{AFX_VIRTUAL(CPcrAdminApp)
	public:
	virtual BOOL InitInstance();
	//}}AFX_VIRTUAL

// インプリメンテーション

	//{{AFX_MSG(CPcrAdminApp)
		// メモ - ClassWizard はこの位置にメンバ関数を追加または削除します。
		//        この位置に生成されるコードを編集しないでください。
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()
};


/////////////////////////////////////////////////////////////////////////////

