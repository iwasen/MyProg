#pragma once

// FindView.h : ヘッダー ファイル
//

/////////////////////////////////////////////////////////////////////////////
// CFindView ビュー

class CFindView : public CListView
{
protected:
	CFindView();           // 動的生成に使用されるプロテクト コンストラクタ
	DECLARE_DYNCREATE(CFindView)

// アトリビュート
public:

// オペレーション
public:

// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。

	protected:
	virtual void OnDraw(CDC* pDC);      // このビューを描画するためにオーバーライドしました。
	virtual void OnUpdate(CView* pSender, LPARAM lHint, CObject* pHint);

// インプリメンテーション
protected:
	virtual ~CFindView();
#ifdef _DEBUG
	virtual void AssertValid() const;
	virtual void Dump(CDumpContext& dc) const;
#endif

	// 生成されたメッセージ マップ関数
protected:
	afx_msg int OnCreate(LPCREATESTRUCT lpCreateStruct);
	afx_msg void OnColumnclick(NMHDR* pNMHDR, LRESULT* pResult);
	afx_msg void OnItemchanged(NMHDR* pNMHDR, LRESULT* pResult);
	afx_msg void OnDestroy();
	DECLARE_MESSAGE_MAP()

protected:
	int m_nSortDir;
	int m_nSortItem;

	void InsertColumns();
	CString GetFilter(CString &keywords, int nAndOr);
	BOOL FindKeyword(LPCTSTR filter);
	void DeleteAllItems();
	static int CALLBACK CompareItems(LPARAM lParam1, LPARAM lParam2, LPARAM lParamSort);
};
