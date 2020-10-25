// MMView.h : MMVIEW アプリケーションのメイン ヘッダー ファイル
//

#if !defined(AFX_MMVIEW_H__95FF6435_EE22_11D2_8C18_00104B939DF5__INCLUDED_)
#define AFX_MMVIEW_H__95FF6435_EE22_11D2_8C18_00104B939DF5__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

#ifndef __AFXWIN_H__
	#error include 'stdafx.h' before including this file for PCH
#endif

#include "resource.h"       // メイン シンボル
#include "pcscl.h"
#include "mmdb.h"
#include "Mml.h"
#include "Item.h"
#include "Global.h"

/////////////////////////////////////////////////////////////////////////////
// CMMViewApp:
// このクラスの動作の定義に関しては MMView.cpp ファイルを参照してください。
//

class CMMViewApp : public CWinApp
{
public:
	CMMViewApp();

// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。
	public:
	virtual BOOL InitInstance();

// インプリメンテーション
	afx_msg void OnAppAbout();
	afx_msg void OnOnlineUpdate();
	afx_msg void OnUpdateOnlineUpdate(CCmdUI* pCmdUI);
	afx_msg void OnOnlineManual();
	DECLARE_MESSAGE_MAP()

public:
	HCURSOR	m_hCursorArrow;
	HCURSOR	m_hCursorHandOpen;
	HCURSOR	m_hCursorHandClose;

private:
	HICON m_hIconApp;
	BOOL m_bOnlineUpdate;
	CString m_ExeFileName;
};


/////////////////////////////////////////////////////////////////////////////

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ は前行の直前に追加の宣言を挿入します。

#endif // !defined(AFX_MMVIEW_H__95FF6435_EE22_11D2_8C18_00104B939DF5__INCLUDED_)
