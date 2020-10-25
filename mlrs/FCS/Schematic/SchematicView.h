#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		SchematicView.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		接続系統図・機能系統図・機能説明図のFlash表示用ビュー処理
//----------------------------------------------------------------------------------------------------
//  3. 備考
//*****************************************************************************************************

#include "CShockwaveFlash.h"
#include "MenuCommon.h"

//#define _DEVELOP		// 開発用機能を有効にする（無効にするにはコメントアウトする）

// CSchematicView ウィンドウ

class CSchematicView : public CWnd
{
	DECLARE_DYNCREATE(CSchematicView)
	DECLARE_EVENTSINK_MAP()

// コンストラクション
public:
	CSchematicView();

// 属性
protected:
	CShockwaveFlash m_cFlash;		// Flashウィンドウ
	class CSchematicFrame *m_pSchematicFrm;	// フレームウィンドウのポインタ
	CSize m_sizeDefault;			// デフォルトのビューサイズ
	CSize m_sizeView;				// 現在のビューサイズ
	CSize m_sizeStage;				// Flashのステージサイズ
	CRect m_rectFlash;				// Flashウィンドウの位置と大きさ
	CMenuCommon *m_pMenuDlg;		// 操作メニューへのポインタ
	double m_fZoomRatio;			// ズーム倍率
	BOOL m_bInScroll;				// スクロール中フラグ（再入防止用）
	CPoint m_pointScroll;			// スクロール中のマウスカーソル位置
	HCURSOR m_hCursorHandClose;		// 手を閉じた形のマウスカーソル
	HCURSOR m_hCursorSave;			// 変更前のマウスカーソル保存用
	BOOL m_bScroll;					// マウスによるスクロールフラグ
	BOOL m_bQuickMode;				// クイック動作モードフラグ（テスト用）

// 操作
public:
	void ShowSchematic(CRuntimeClass *pMenuClass, const CString &sFlashFileName, int nActionCode);

// オーバーライド
	protected:
	virtual BOOL PreCreateWindow(CREATESTRUCT& cs);

// 実装
public:

protected:
	void FlashAction(int nActionCode, CUIntArray *pParams);
	void InitFlashRect();
	int GetNextScrollPos(int nBar, UINT nSBCode, UINT nPos);
	void ScrollFlashWindow();
	void SetZoomRatio(double fZoomRatio);
	void FSCommandFlash(LPCTSTR command, LPCTSTR args);
	void CallFlashFunction(LPCTSTR pFunc, LPCTSTR pParams);

	// 生成された、メッセージ割り当て関数
protected:
	DECLARE_MESSAGE_MAP()
	virtual void PostNcDestroy();
	virtual BOOL PreTranslateMessage(MSG* pMsg);
	afx_msg void OnPaint();
	afx_msg void OnSize(UINT nType, int cx, int cy);
	afx_msg void OnHScroll(UINT nSBCode, UINT nPos, CScrollBar* pScrollBar);
	afx_msg void OnVScroll(UINT nSBCode, UINT nPos, CScrollBar* pScrollBar);
	afx_msg int OnCreate(LPCREATESTRUCT lpCreateStruct);
	afx_msg void OnLButtonDown(UINT nFlags, CPoint point);
	afx_msg void OnLButtonUp(UINT nFlags, CPoint point);
	afx_msg void OnMouseMove(UINT nFlags, CPoint point);
	afx_msg LRESULT OnUserZoomIn(WPARAM wParam, LPARAM lParam);
	afx_msg LRESULT OnUserZoomOut(WPARAM wParam, LPARAM lParam);
	afx_msg LRESULT OnUserSizeReset(WPARAM wParam, LPARAM lParam);
	afx_msg LRESULT OnUserPrint(WPARAM wParam, LPARAM lParam);
	afx_msg LRESULT OnUserExit(WPARAM wParam, LPARAM lParam);
	afx_msg LRESULT OnUserAction(WPARAM wParam, LPARAM lParam);
	afx_msg LRESULT OnUserPause(WPARAM wParam, LPARAM lParam);
	afx_msg LRESULT OnUserSlow(WPARAM wParam, LPARAM lParam);
	afx_msg LRESULT OnUserNormal(WPARAM wParam, LPARAM lParam);
#ifdef _DEVELOP
	afx_msg void OnRButtonUp(UINT nFlags, CPoint point);
	afx_msg void OnQuickMode();
	afx_msg void OnScreenCopy();
	afx_msg void OnStartAll();
	void ScreenShot(CWnd *pWnd);
#endif
};

