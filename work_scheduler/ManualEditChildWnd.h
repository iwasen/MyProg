#if !defined(AFX_MANUALEDITCHILDWND_H__0970CE9B_D0BF_4BED_97AF_67494666FD64__INCLUDED_)
#define AFX_MANUALEDITCHILDWND_H__0970CE9B_D0BF_4BED_97AF_67494666FD64__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000
// ManualEditChildWnd.h : ヘッダー ファイル
//

#include "ManualEditKishuBar.h"

/////////////////////////////////////////////////////////////////////////////
// CManualEditChildWnd フレーム

class CManualEditChildWnd : public CMDIChildWnd
{
	DECLARE_DYNCREATE(CManualEditChildWnd)
protected:
	CManualEditChildWnd();           // 動的生成に使用されるプロテクト コンストラクタ。

// アトリビュート
public:

// オペレーション
public:
// Modify ... ( ADD )
	int m_nGraphKishu;				// 表示中の機種
	int	m_nRefIndex;				// 参照工程の番号
// By Y.Itabashi (xxxxx) 2007.02.26

// Modify ... ( ADD )
	void DestroyKishuBar();				// ダイアログバー閉じる
// By Y.Itabashi (xxxxx) 2007.02.26

// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します

	//{{AFX_VIRTUAL(CManualEditChildWnd)
	protected:
	virtual BOOL PreCreateWindow(CREATESTRUCT& cs);
	//}}AFX_VIRTUAL

// インプリメンテーション
protected:
	virtual ~CManualEditChildWnd();

	// 生成されたメッセージ マップ関数
	//{{AFX_MSG(CManualEditChildWnd)
	afx_msg int OnCreate(LPCREATESTRUCT lpCreateStruct);
	afx_msg void OnSysCommand(UINT nID, LPARAM lParam);
	afx_msg void OnSelchangeComboKishu();
	afx_msg void OnBackRefKotei();
	afx_msg void OnNextRefKotei();
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

// Modify ... ( ADD )
	CManualEditKishuBar m_wndDlgBar;			// ダイアログバー
// By Y.Itabashi (xxxxx) 2007.02.26

};

/////////////////////////////////////////////////////////////////////////////

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ は前行の直前に追加の宣言を挿入します。

#endif // !defined(AFX_MANUALEDITCHILDWND_H__0970CE9B_D0BF_4BED_97AF_67494666FD64__INCLUDED_)
