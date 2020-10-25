#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		ManualEditView3.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		CManualEditView3 クラスのインターフェイス
//----------------------------------------------------------------------------------------------------
//  3. 備考
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************

#include "ManualEditView.h"

// 手動編成画面の下側ビュー（全情報）
class CManualEditView3 : public CManualEditView
{
protected:
	CManualEditView3();           // 動的生成に使用されるプロテクト コンストラクタ。
	DECLARE_DYNCREATE(CManualEditView3)

// アトリビュート
public:

// オペレーション
public:

// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。
	//{{AFX_VIRTUAL(CManualEditView3)
	public:
	virtual void OnInitialUpdate();
	protected:
	virtual void OnDraw(CDC* pDC);      // このビューを描画するためにオーバーライドしました。
	virtual void OnUpdate(CView* pSender, LPARAM lHint, CObject* pHint);
	//}}AFX_VIRTUAL

// インプリメンテーション
protected:

	// 生成されたメッセージ マップ関数
	//{{AFX_MSG(CManualEditView3)
	afx_msg void OnSize(UINT nType, int cx, int cy);
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

	void MakeGraph();
	int MakeZahyoSagyo(int nSublineId, CGraphBlockArray &cArray, int nSagyoshaId, int nKishuId, int nLeft, int nTop, int &nKittingSublineId);
	void SetViewSize();
	void DrawHorizontalAxisText(CDC *pDC);
	void DrawTotalTime(CDC *pDC);
	void SetScaleParamaeter();
};

/////////////////////////////////////////////////////////////////////////////

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ は前行の直前に追加の宣言を挿入します。
