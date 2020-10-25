#if !defined(AFX_MANUALEDITSUBLINEINFO_H__9CBD6336_4083_48E2_8A75_FB3AB27C5D33__INCLUDED_)
#define AFX_MANUALEDITSUBLINEINFO_H__9CBD6336_4083_48E2_8A75_FB3AB27C5D33__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000
// ManualEditSublineInfo.h : ヘッダー ファイル
//

#define MAX_LINES	3

/////////////////////////////////////////////////////////////////////////////
// CManualEditSublineInfo ウィンドウ

class CManualEditSublineInfo : public CWnd
{
// コンストラクション
public:
	CManualEditSublineInfo();

// アトリビュート
public:

// オペレーション
public:
	void Create(CWnd *pParentWnd);
	void SetText(LPCTSTR pText1, LPCTSTR pText2, LPCTSTR pText3);

// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。

	//{{AFX_VIRTUAL(CManualEditSublineInfo)
	//}}AFX_VIRTUAL

// インプリメンテーション
public:
	virtual ~CManualEditSublineInfo();

	// 生成されたメッセージ マップ関数
protected:
	//{{AFX_MSG(CManualEditSublineInfo)
	afx_msg BOOL OnEraseBkgnd(CDC* pDC);
	afx_msg void OnPaint();
	afx_msg void OnLButtonDown(UINT nFlags, CPoint point);
	afx_msg void OnLButtonUp(UINT nFlags, CPoint point);
	afx_msg void OnMouseMove(UINT nFlags, CPoint point);
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

	CString m_sText[MAX_LINES];
	CFont m_fontText;
	CRect m_rectMove;
	CPoint m_pointMove;
	BOOL m_bMoving;
};

/////////////////////////////////////////////////////////////////////////////

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ は前行の直前に追加の宣言を挿入します。

#endif // !defined(AFX_MANUALEDITSUBLINEINFO_H__9CBD6336_4083_48E2_8A75_FB3AB27C5D33__INCLUDED_)
