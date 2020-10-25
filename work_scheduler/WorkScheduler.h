#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		WorkScheduler.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		CWorkSchedulerApp クラスのインターフェイス
//----------------------------------------------------------------------------------------------------
//  3. 備考
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************

#ifndef __AFXWIN_H__
	#error include 'stdafx.h' before including this file for PCH
#endif

#include "resource.h"		// メイン シンボル
#include "Global.h"
#include "ArrayEx.h"
#include "Const.h"
#include "System.h"
#include "DataManager.h"

#define COLOR_MENU_BUTTON		RGB(0x55, 0xff, 0xff)
#define COLOR_SUBMENU_BUTTON	RGB(0xff, 0xff, 0x99)

extern CConst *g_pConst;				// 定数クラス
extern CSystem *g_pSystem;				// システム管理クラス
extern CDataManager *g_pDataManager;	// データ管理オブジェクトポインタ

// プログラム本体のCwinAppクラス
class CWorkSchedulerApp : public CWinApp
{
public:
// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。
	//{{AFX_VIRTUAL(CWorkSchedulerApp)
	public:
	virtual BOOL InitInstance();
	virtual int ExitInstance();
	//}}AFX_VIRTUAL

// インプリメンテーション

	//{{AFX_MSG(CWorkSchedulerApp)
		// メモ - ClassWizard はこの位置にメンバ関数を追加または削除します。
		//        この位置に生成されるコードを編集しないでください。
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

public:
	CMultiDocTemplate* m_pManualDocTemplate1;	// 手動編成ドキュメントテンプレートへのポインタ
	CMultiDocTemplate* m_pManualDocTemplate2;	// 手動編成ドキュメントテンプレートへのポインタ
	CMultiDocTemplate* m_pManualDocTemplate3;	// 手動編成ドキュメントテンプレートへのポインタ
};


//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ は前行の直前に追加の宣言を挿入します。
