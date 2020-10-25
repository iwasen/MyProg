#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		ManualEditView.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		CManualEditView クラスのインターフェイス
//----------------------------------------------------------------------------------------------------
//  3. 備考
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************

#include "ManualEditChildWnd.h"
#include "ManualEditDocument.h"
#include "ManualEditSublineInfo.h"

// 作業座標データ種別
#define COORDINATE_KIND_SAGYO			0
#define COORDINATE_KIND_JIDO			1
#define COORDINATE_KIND_HANJIDO			2
#define COORDINATE_KIND_JIDO_KYOTSU		3
#define COORDINATE_KIND_HANJIDO_KYOTSU	4
#define COORDINATE_KIND_KYOTSU_KOTEI	5

// サブライン別座標データ
struct SZahyoSubline {
	int nSublineId;				// サブラインID
	int nLeft;					// 左端
	int nRight;					// 右端
	CManualEditSublineInfo cSublineInfo; 
};
typedef CArrayEx <SZahyoSubline, SZahyoSubline&> CZahyoSublineArray;

// キット化サブライン区切り座標データ
struct SZahyoKittingSubline {
	CPoint pointLine;
	CString sSiblineName1;
	CString sSiblineName2;
};
typedef CArrayEx <SZahyoKittingSubline, SZahyoKittingSubline&> CZahyoKittingSublineArray;

// グラフ表示座標データ
struct SZahyoGraph {
	CRect rect;					// 座標
	CString sText;				// 表示テキスト
	int nKind;					// 表示種別
	int nKishuId;
};
typedef CArrayEx <SZahyoGraph, SZahyoGraph&> CZahyoGraphArray;

// 共通工程枠の座標
struct SZahyoKyotsuKoteiFrame {
	CRect rect;
};
typedef CArrayEx <SZahyoKyotsuKoteiFrame, SZahyoKyotsuKoteiFrame&> CZahyoKyotsuKoteiFrameArray;

// 手動編成共通ビュー
class CManualEditView : public CScrollView
{
protected:
	CManualEditView();           // 動的生成に使用されるプロテクト コンストラクタ。
	DECLARE_DYNCREATE(CManualEditView)

// アトリビュート
public:

// オペレーション
public:

// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。
	//{{AFX_VIRTUAL(CManualEditView)
	protected:
	virtual void OnInitialUpdate();     // 構築後、最初の処理。
	virtual void OnDraw(CDC* pDC);
	//}}AFX_VIRTUAL

// インプリメンテーション
protected:

	// 生成されたメッセージ マップ関数
	//{{AFX_MSG(CManualEditView)
	afx_msg void OnHScroll(UINT nSBCode, UINT nPos, CScrollBar* pScrollBar);
	afx_msg void OnVScroll(UINT nSBCode, UINT nPos, CScrollBar* pScrollBar);
	//}}AFX_MSG
	afx_msg void OnGetToolTipText(NMHDR* pNMH, LRESULT* pRes);
	DECLARE_MESSAGE_MAP()

protected:
	int m_nLeftMargin;			// 左マージン
	int m_nTopMargin;			// 上マージン
	int m_nRightMargin;			// 右マージン
	int m_nBottomMargin;		// 下マージン
	int m_nBarWidth;			// バーの幅
	int m_nLeftSpacing;			// 縦軸と一番左のバーの間のスペース
	int m_nBarSpacing;			// バー間のスペース
	int m_moveHight;			// 編成画面の縦軸移動 20040214 xxxxx kuwa
	CZahyoSublineArray m_aZahyoSubline;	// サブライン別座標データ
	CZahyoGraphArray m_aZahyoGraph;	// グラフ表示座標データ
	CZahyoKittingSublineArray m_aZahyoKittingSubline;	// キット化サブライン区切り座標データ
	CZahyoKyotsuKoteiFrameArray m_aZahyoKyotsuKoteiFrame;	// 共通工程座標データ
	CZahyoSagyoshaArray m_aZahyoSagyosha;
	CRect m_rectView;			// ビュー全体の大きさ
	CRect m_rectGraph;			// グラフ部分の大きさ
	CPen m_penGraph;			// グラフ描画用のペン
	CPen m_penAxis;				// 軸描画用のペン
	CPen m_penTact;				// 目標タクト描画用のペン
	CPen m_penTactLimit;		// 目標タクトの上下限描画用のペン
	CPen m_penSublineBar;		// サブライン間区切り線用のペン
	CPen m_penKyotsuKotei;		// 共通工程描画用のペン
	CPen m_penKittingLine;		// サブライン・キット化ライン間区切り線描画用のペン
	CPen m_penKittingSubline;	// キット化作業サブライン間区切り線描画用のペン
	CFont m_fontGraph;			// 文字描画用フォント
	CFont m_fontH;				// 横軸描画用フォント
	CFont m_fontV;				// 縦軸描画用フォント
	CFont m_fontTime;			// 作業時間描画用フォント
	CBrush m_brushJido;			// 自動工程描画用ブラシ
	CBrush m_brushHanJido;		// 半自動工程描画用ブラシ
	CBrush m_brushKyotsuJido;		// 自動工程（共通工程）描画用ブラシ
	CBrush m_brushKyotsuHanJido;	// 半自動工程（共通工程）描画用ブラシ
	CBrush m_brushGraph[MAX_KISHU];	// 機種別のバー描画用ブラシ
	CBitmap m_bmpKyotsuKotei;	// 共通工程マークのビットマップ
	CDC m_dcCompatible;			// 共通工程マーク描画用DC
	BOOL m_bInitialize;			// 初期化済みフラグ
	int m_nGraphSublineNum;		// グラフ表示するサブラインの数
	CSize m_sizeKyotsuKotei;	// 共通工程マークのサイズ
	double m_fScaleFactor;		// １分あたりのピクセル数

	CManualEditDocument *GetDocument() { return (CManualEditDocument *)CView::GetDocument(); }
	void InitializeZahyoData();
	void ClearZahyoData();
	void AddZahyoGraph(const CRect &rect, CString &sText, int nKind, int nKishuId = 0);
	int AddKyotsuKoteiFrame(const CRect &rect);
	void AddZahyoSagyosha(const CRect &rect, int nSublineId, int nSagyoshaId);
	SZahyoSagyosha *CheckZahyoSagyosha(CPoint point);
	void DrawGraph(CDC *pDC, BOOL bDrawText);
	void DrawKyotsuKoteiFrame(CDC *pDC);
	void DrawVerticalAxisLine(CDC *pDC);
	void DrawHorizontalAxisLine(CDC *pDC);
	void DrawTactLine(CDC *pDC);
	void DrawVerticalAxisText(CDC *pDC, LPCTSTR pText);
	void DrawLegends(CDC *pDC);
	void DrawLeftTriangle(CDC *pDC, const CRect &rect);
	void DrawRightTriangle(CDC *pDC, const CRect &rect);
	void DrawTextCenter(CDC *pDC, const CString &sText, CRect rect);
	void DrawTime(CDC *pDC, SGraphSubline &cGraphSubline, int nLeft, double fTime);
	void DrawSublineBoundLine(CDC *pDC);
	void DrawKittingSubline(CDC *pDC);
	int OnToolHitTest(CPoint point, TOOLINFO * pTI) const;
	void SetScaleFactor(BOOL bAdjust, int nKishuId);
	double GetMaxTime(int nKishuId);
	void SetSlimMode();
	void SetSlim(int &nScale);
	int GetTotalSagyoshaNum();
	int GetKittingSagyoshaNum();
	void DispSublineInfo();
	void SetSublineInfoPos();
	void SetSublineInfoPos2();
	void HideSublineInfo();
	int GetScaleHeight(double fMinute);
	void DispCenterSagyosha(int nSublineId, int nSagyoshaId);
	SZahyoSubline *GetZahyoSubline(CPoint point);
};

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ は前行の直前に追加の宣言を挿入します。
