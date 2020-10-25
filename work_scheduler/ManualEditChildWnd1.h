#if !defined(AFX_MANUALEDITCHILDWND1_H__850F24F1_6480_41CE_8BC8_DFDB0DBB3807__INCLUDED_)
#define AFX_MANUALEDITCHILDWND1_H__850F24F1_6480_41CE_8BC8_DFDB0DBB3807__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000
// ManualEditChildWnd1.h : ヘッダー ファイル
//

#include "ManualEditChildWnd.h"
#include "SplitterEx.h"

/////////////////////////////////////////////////////////////////////////////
// CManualEditChildWnd1 フレーム

class CManualEditChildWnd1 : public CManualEditChildWnd
{
	DECLARE_DYNCREATE(CManualEditChildWnd1)
protected:
	CManualEditChildWnd1();           // 動的生成に使用されるプロテクト コンストラクタ。

// アトリビュート
public:

// オペレーション
public:

// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します

	//{{AFX_VIRTUAL(CManualEditChildWnd1)
	protected:
	virtual BOOL OnCreateClient(LPCREATESTRUCT lpcs, CCreateContext* pContext);
	//}}AFX_VIRTUAL

// インプリメンテーション
protected:
	virtual ~CManualEditChildWnd1();

	// 生成されたメッセージ マップ関数
	//{{AFX_MSG(CManualEditChildWnd1)
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

	CSplitterEx m_wndSplitter;
};

/////////////////////////////////////////////////////////////////////////////

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ は前行の直前に追加の宣言を挿入します。

#endif // !defined(AFX_MANUALEDITCHILDWND1_H__850F24F1_6480_41CE_8BC8_DFDB0DBB3807__INCLUDED_)
