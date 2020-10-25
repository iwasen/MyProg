#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		SchematicFrm.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		接続系統図・機能系統図・機能説明図のFlash表示用フレームウィンドウ処理
//----------------------------------------------------------------------------------------------------
//  3. 備考
//*****************************************************************************************************

#include "SchematicView.h"

class CSchematicFrame : public CFrameWnd
{
	friend CSchematicView;
protected: 
	DECLARE_DYNAMIC(CSchematicFrame)

// 属性
public:
	CTypedPtrList <CPtrList, SSchematicWindow *> m_lSchematicWindow;	// 図表データリスト

protected:
	CString m_sWindowTitle;			// ウィンドウタイトル
	CString m_sFlashFileName;		// Flashファイル名
	CRuntimeClass *m_pViewClass;	// ビュークラス
	CRuntimeClass *m_pMenuClass;	// 操作メニュークラス

// 操作
public:
	void CreateFrameWindow(int nActionCode);

protected:
	void GetSchematicInfo();

// オーバーライド
public:
	virtual BOOL PreCreateWindow(CREATESTRUCT& cs);

// 実装
public:

protected:  // コントロール バー用メンバ
	CStatusBar  m_wndStatusBar;
	CSchematicView    *m_pWndView;

// 生成された、メッセージ割り当て関数
protected:
	DECLARE_MESSAGE_MAP()
	afx_msg int OnCreate(LPCREATESTRUCT lpCreateStruct);
	afx_msg void OnDestroy();
	afx_msg void OnClose();
public:
	afx_msg void OnScreenCopySize();
};


