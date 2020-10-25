// EcuRecv.h : ECURECV アプリケーションのメイン ヘッダー ファイルです。
//

#if !defined(AFX_ECURECV_H__8CC9B145_B139_11D3_8056_7A2C79000000__INCLUDED_)
#define AFX_ECURECV_H__8CC9B145_B139_11D3_8056_7A2C79000000__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

#ifndef __AFXWIN_H__
	#error include 'stdafx.h' before including this file for PCH
#endif

#include "resource.h"		// メイン シンボル

/////////////////////////////////////////////////////////////////////////////
// CEcuRecvApp:
// このクラスの動作の定義に関しては EcuRecv.cpp ファイルを参照してください。
//

class CEcuRecvApp : public CWinApp
{
public:
	CEcuRecvApp();

// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。
	//{{AFX_VIRTUAL(CEcuRecvApp)
	public:
	virtual BOOL InitInstance();
	//}}AFX_VIRTUAL

// インプリメンテーション

	//{{AFX_MSG(CEcuRecvApp)
		// メモ - ClassWizard はこの位置にメンバ関数を追加または削除します。
		//        この位置に生成されるコードを編集しないでください。
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()
};


/////////////////////////////////////////////////////////////////////////////

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ は前行の直前に追加の宣言を挿入します。

#endif // !defined(AFX_ECURECV_H__8CC9B145_B139_11D3_8056_7A2C79000000__INCLUDED_)
