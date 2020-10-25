// MMViewDoc.h : CMMViewDoc クラスの宣言およびインターフェイスの定義をします。
//
/////////////////////////////////////////////////////////////////////////////

#if !defined(AFX_MMVIEWDOC_H__95FF643B_EE22_11D2_8C18_00104B939DF5__INCLUDED_)
#define AFX_MMVIEWDOC_H__95FF643B_EE22_11D2_8C18_00104B939DF5__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

class CMMViewDoc : public CDocument
{
protected: // シリアライズ機能のみから作成します。
	CMMViewDoc();
	DECLARE_DYNCREATE(CMMViewDoc)

// アトリビュート
public:

// オペレーション
public:

//オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。
	public:
	virtual BOOL OnNewDocument();
	virtual void Serialize(CArchive& ar);
	virtual BOOL OnOpenDocument(LPCTSTR lpszPathName);

// インプリメンテーション
public:
	virtual ~CMMViewDoc();
#ifdef _DEBUG
	virtual void AssertValid() const;
	virtual void Dump(CDumpContext& dc) const;
#endif

protected:

// 生成されたメッセージ マップ関数
protected:
	afx_msg void OnPictNext();
	afx_msg void OnUpdatePictNext(CCmdUI* pCmdUI);
	afx_msg void OnPictPrev();
	afx_msg void OnUpdatePictPrev(CCmdUI* pCmdUI);
	afx_msg void OnViewList();
	afx_msg void OnViewPict();
	afx_msg void OnViewIcon();
	afx_msg void OnUpdateViewPict(CCmdUI* pCmdUI);
	afx_msg void OnUpdateViewIcon(CCmdUI* pCmdUI);
	afx_msg void OnUpdateViewList(CCmdUI* pCmdUI);
	afx_msg void OnUpdateFilePrint(CCmdUI* pCmdUI);
	afx_msg void OnUpdateFilePrintPreview(CCmdUI* pCmdUI);
	afx_msg void OnSetfont();
	DECLARE_MESSAGE_MAP()

public:
	int m_nViewMode;
	int m_nMaxPage;
	int m_nCurrentPage;
	CMmlRead *m_pMMFile;
	CItem *m_pItem;
	CCriticalSection m_CriticalSection1;
	CString m_Text;
	CFont m_Font;

	CItem *GetCurrentItem() {return &m_pItem[m_nCurrentPage];}
	void ReadHeader();
	void ReadPage(int nPage);
	void ReadAllPages();
	void DispStatus();
	void ShowPicture(int nPage);

private:
	CCriticalSection m_CriticalSection2;
	CString m_Title;
	CString m_sFontName;
	int m_nFontSize;

	void CloseFile();
	void ReadCurrentPage();
	void SetStatusInfo(LPCTSTR pInfoMsg);
	void CreateReadThread();
};

/////////////////////////////////////////////////////////////////////////////

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ は前行の直前に追加の宣言を挿入します。

#endif // !defined(AFX_MMVIEWDOC_H__95FF643B_EE22_11D2_8C18_00104B939DF5__INCLUDED_)
