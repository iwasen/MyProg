#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		ManualEditView2.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		CManualEditView2 クラスのインターフェイス
//----------------------------------------------------------------------------------------------------
//  3. 備考
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************

#include "ManualEditView.h"

// 手動編成画面の右上ビュー（一覧）
class CManualEditView2 : public CManualEditView
{
protected:
	CManualEditView2();           // 動的生成に使用されるプロテクト コンストラクタ。
	DECLARE_DYNCREATE(CManualEditView2)

// アトリビュート
public:

// オペレーション
public:

// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。
	//{{AFX_VIRTUAL(CManualEditView2)
	public:
	virtual void OnInitialUpdate();
	protected:
	virtual void OnDraw(CDC* pDC);      // このビューを描画するためにオーバーライドしました。
	virtual void OnUpdate(CView* pSender, LPARAM lHint, CObject* pHint);
	//}}AFX_VIRTUAL

// インプリメンテーション
protected:

	// 生成されたメッセージ マップ関数
	//{{AFX_MSG(CManualEditView2)
	afx_msg void OnLButtonDown(UINT nFlags, CPoint point);
	afx_msg void OnSize(UINT nType, int cx, int cy);
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

	void MakeGraph();
	void MakeGraphSub(SGraphSubline &cGraphSubline, int &nLeft);
	void SetViewSize();
	void DrawHorizontalAxisText(CDC *pDC);
	void DrawHorizontalAxisTextSub(CDC *pDC, SGraphSubline &cGraphSubline, int &nLeft, BOOL bAlternate, int &nCounter);
	void SetScaleParamaeter();
};

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ は前行の直前に追加の宣言を挿入します。
