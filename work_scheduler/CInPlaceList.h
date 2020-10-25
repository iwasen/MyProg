#if !defined(AFX_CINPLACELIST_H__A125C131_9824_11D5_8E80_00E0292A193B__INCLUDED_)
#define AFX_CINPLACELIST_H__A125C131_9824_11D5_8E80_00E0292A193B__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000
// CInPlaceList.h : ヘッダー ファイル
//

/////////////////////////////////////////////////////////////////////////////
// CInPlaceList ウィンドウ

class CInPlaceList : public CComboBox
{
// コンストラクション
public:
//	CInPlaceList();
	CInPlaceList(int iItem, int iSubItem, CStringList *plstItems, int nSel);

// アトリビュート
public:

// オペレーション
public:

// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。
	//{{AFX_VIRTUAL(CInPlaceList)
	public:
	virtual BOOL PreTranslateMessage(MSG* pMsg);
	//}}AFX_VIRTUAL

// インプリメンテーション
public:
	virtual ~CInPlaceList();

	// 生成されたメッセージ マップ関数
protected:
	//{{AFX_MSG(CInPlaceList)
	afx_msg int OnCreate(LPCREATESTRUCT lpCreateStruct);
	afx_msg void OnChar(UINT nChar, UINT nRepCnt, UINT nFlags);
	afx_msg void OnCloseup();
	afx_msg void OnNcDestroy();
	afx_msg void OnKillFocus(CWnd* pNewWnd);
	//}}AFX_MSG

	DECLARE_MESSAGE_MAP()
private:
	int 	m_iItem;
	int 	m_iSubItem;
	CStringList m_lstItems;
	int 	m_nSel;
	BOOL	m_bESC;
};

/////////////////////////////////////////////////////////////////////////////

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ は前行の直前に追加の宣言を挿入します。

#endif // !defined(AFX_CINPLACELIST_H__A125C131_9824_11D5_8E80_00E0292A193B__INCLUDED_)
