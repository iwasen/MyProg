#if !defined(AFX_MMLISTVIEW_H__87692B76_F14C_11D2_8C1F_00104B939DF5__INCLUDED_)
#define AFX_MMLISTVIEW_H__87692B76_F14C_11D2_8C1F_00104B939DF5__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000
// MMListView.h : �w�b�_�[ �t�@�C��
//

/////////////////////////////////////////////////////////////////////////////
// CMMListView �r���[

class CMMListView : public CListView
{
protected:
	CMMListView();           // ���I�����Ɏg�p�����v���e�N�g �R���X�g���N�^
	DECLARE_DYNCREATE(CMMListView)

// �A�g���r���[�g
public:

// �I�y���[�V����
public:

// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B

	public:
	virtual void OnInitialUpdate();
	protected:
	virtual void OnDraw(CDC* pDC);      // ���̃r���[��`�悷�邽�߂ɃI�[�o�[���C�h���܂����B
	virtual void OnUpdate(CView* pSender, LPARAM lHint, CObject* pHint);
	virtual BOOL OnPreparePrinting(CPrintInfo* pInfo);
	virtual void OnPrint(CDC* pDC, CPrintInfo* pInfo);

// �C���v�������e�[�V����
protected:
	virtual ~CMMListView();
#ifdef _DEBUG
	virtual void AssertValid() const;
	virtual void Dump(CDumpContext& dc) const;
#endif

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
protected:
	afx_msg int OnCreate(LPCREATESTRUCT lpCreateStruct);
	afx_msg void OnColumnclick(NMHDR* pNMHDR, LRESULT* pResult);
	afx_msg void OnDblclk(NMHDR* pNMHDR, LRESULT* pResult);
	afx_msg void OnDetailCdate();
	afx_msg void OnUpdateDetailCdate(CCmdUI* pCmdUI);
	afx_msg void OnDetailCode();
	afx_msg void OnUpdateDetailCode(CCmdUI* pCmdUI);
	afx_msg void OnDetailCreator();
	afx_msg void OnUpdateDetailCreator(CCmdUI* pCmdUI);
	afx_msg void OnDetailName();
	afx_msg void OnUpdateDetailName(CCmdUI* pCmdUI);
	afx_msg void OnDetailRemark1();
	afx_msg void OnUpdateDetailRemark1(CCmdUI* pCmdUI);
	afx_msg void OnDetailRemark2();
	afx_msg void OnUpdateDetailRemark2(CCmdUI* pCmdUI);
	afx_msg void OnDetailUdate();
	afx_msg void OnUpdateDetailUdate(CCmdUI* pCmdUI);
	DECLARE_MESSAGE_MAP()

private:
	int m_nColumn;
	BOOL m_bDetailFlag[DETAIL_NUM];
	int m_nDetailWidth[DETAIL_NUM];
	int m_nSortItem;
	int m_nSortDir;

	void DrawItems();
	void InsertColumns();
	static int CALLBACK CompareItems(LPARAM lParam1, LPARAM lParam2, LPARAM lParamSort);
};

/////////////////////////////////////////////////////////////////////////////

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ �͑O�s�̒��O�ɒǉ��̐錾��}�����܂��B

#endif // !defined(AFX_MMLISTVIEW_H__87692B76_F14C_11D2_8C1F_00104B939DF5__INCLUDED_)
