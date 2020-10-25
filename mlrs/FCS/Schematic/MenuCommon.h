#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		MenuCommon.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		接続系統図・機能系統図・機能説明図の操作メニューの共通親クラス
//----------------------------------------------------------------------------------------------------
//  3. 備考
//*****************************************************************************************************

#include "DialogEx.h"

// CMenuCommon ダイアログ

class CMenuCommon : public CDialogEx
{
	DECLARE_DYNAMIC(CMenuCommon)

public:
	CMenuCommon(UINT nID, CWnd* pParent);   // 標準コンストラクタ

	virtual void ActionEnd(int nActionNo) {}
	void EnableSpeedButton(BOOL bStart);

	CRect m_rectClient;			// クライアント領域の位置と大きさ

protected:
	void SendAction(int nActionNo, CUIntArray *pParamArray = NULL);
	LRESULT SendMessageParent(UINT nMessage, WPARAM wParam = 0, LPARAM lParam = 0);
	void EnableButton(UINT nID, BOOL bEnable);

	BOOL m_bSlowSpeed;

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

	DECLARE_MESSAGE_MAP()
	virtual BOOL OnInitDialog();
	virtual void OnCancel();
	afx_msg void OnBnClickedButtonZoomIn();
	afx_msg void OnBnClickedButtonZoomOut();
	afx_msg void OnBnClickedButtonSizeReset();
	afx_msg void OnBnClickedButtonPrint();
	afx_msg void OnBnClickedButtonExit();
	afx_msg void OnNcLButtonDblClk(UINT nHitTest, CPoint point);
	afx_msg void OnBnClickedButtonPause();
	afx_msg void OnBnClickedButtonSlow();
	afx_msg void OnBnClickedButtonNormal();
};
