#pragma once

#include "listvwex.h"
#include "MyCtrl.h"

class CSasView : public CListView
{
public:
	BOOL CheckSelect(long impulseID);
	long GetNextItem(long impulseID);
	long GetPrevItem(long impulseID);

protected: // �V���A���C�Y�@�\�݂̂���쐬���܂��B
	CSasView();
	DECLARE_DYNCREATE(CSasView)

	CSasDoc* GetDocument();

#ifdef _DEBUG
	virtual void AssertValid() const;
	virtual void Dump(CDumpContext& dc) const;
#endif

	void SetHeader(int nHeader);
	void DrawDB();
	void DrawFolder();
	void DrawIR();
	void DrawACF();
	void DrawNMS();
	int GetSelectedItem();
	void DispIR(int nItem);
	void DispACF(int nItem);
	void DispNMS(int nItem);
	static int CALLBACK CompareItems(LPARAM lParam1, LPARAM lParam2, LPARAM lParamSort);
	void DeleteItem(long nDataID);

	int m_nSortItem;
	int m_nSortDir;
	CMyEdit m_cEditTitle;

	DECLARE_MESSAGE_MAP()
	virtual void OnDraw(CDC* pDC);  // ���̃r���[��`�悷��ۂɃI�[�o�[���C�h����܂��B
	virtual BOOL PreCreateWindow(CREATESTRUCT& cs);
	virtual void OnInitialUpdate(); // �\�z��̍ŏ��̂P�x�����Ăяo����܂��B
	virtual BOOL OnPreparePrinting(CPrintInfo* pInfo);
	virtual void OnBeginPrinting(CDC* pDC, CPrintInfo* pInfo);
	virtual void OnEndPrinting(CDC* pDC, CPrintInfo* pInfo);
	virtual void OnUpdate(CView* pSender, LPARAM lHint, CObject* pHint);
	afx_msg int OnCreate(LPCREATESTRUCT lpCreateStruct);
	afx_msg void OnDblclk(NMHDR* pNMHDR, LRESULT* pResult);
	afx_msg void OnParamDisp();
	afx_msg void OnUpdateParamDisp(CCmdUI* pCmdUI);
	afx_msg void OnColumnclick(NMHDR* pNMHDR, LRESULT* pResult);
	afx_msg void OnFileOpen();
	afx_msg void OnUpdateFileOpen(CCmdUI* pCmdUI);
	afx_msg void OnFileDelete();
	afx_msg void OnUpdateFileDelete(CCmdUI *pCmdUI);
	afx_msg void OnNMRclick(NMHDR *pNMHDR, LRESULT *pResult);
	afx_msg void OnFileChangeTitle();
	afx_msg void OnUpdateFileChangeTitle(CCmdUI *pCmdUI);
	afx_msg void OnLvnBeginlabeledit(NMHDR *pNMHDR, LRESULT *pResult);
	afx_msg void OnLvnEndlabeledit(NMHDR *pNMHDR, LRESULT *pResult);
};

#ifndef _DEBUG  // ChasView.cpp �t�@�C�����f�o�b�O���̎��g�p����܂��B
inline CSasDoc* CSasView::GetDocument()
   { return (CSasDoc*)m_pDocument; }
#endif
