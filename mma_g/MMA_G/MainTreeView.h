#pragma once


// CMainTreeView �r���[

class CMainTreeView : public CTreeView
{
	DECLARE_DYNCREATE(CMainTreeView)

protected:
	CMainTreeView();           // ���I�����Ŏg�p����� protected �R���X�g���N�^
	virtual ~CMainTreeView();

public:
#ifdef _DEBUG
	virtual void AssertValid() const;
#ifndef _WIN32_WCE
	virtual void Dump(CDumpContext& dc) const;
#endif
#endif

protected:
	DECLARE_MESSAGE_MAP()
	afx_msg void OnTvnSelchanged(NMHDR *pNMHDR, LRESULT *pResult);
	virtual BOOL PreCreateWindow(CREATESTRUCT& cs);
	virtual void OnUpdate(CView* /*pSender*/, LPARAM /*lHint*/, CObject* /*pHint*/);
	int SelectItem(HTREEITEM hTree, LPCTSTR pPath);
};


