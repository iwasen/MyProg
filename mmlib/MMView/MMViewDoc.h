// MMViewDoc.h : CMMViewDoc �N���X�̐錾����уC���^�[�t�F�C�X�̒�`�����܂��B
//
/////////////////////////////////////////////////////////////////////////////

#if !defined(AFX_MMVIEWDOC_H__95FF643B_EE22_11D2_8C18_00104B939DF5__INCLUDED_)
#define AFX_MMVIEWDOC_H__95FF643B_EE22_11D2_8C18_00104B939DF5__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

class CMMViewDoc : public CDocument
{
protected: // �V���A���C�Y�@�\�݂̂���쐬���܂��B
	CMMViewDoc();
	DECLARE_DYNCREATE(CMMViewDoc)

// �A�g���r���[�g
public:

// �I�y���[�V����
public:

//�I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	public:
	virtual BOOL OnNewDocument();
	virtual void Serialize(CArchive& ar);
	virtual BOOL OnOpenDocument(LPCTSTR lpszPathName);

// �C���v�������e�[�V����
public:
	virtual ~CMMViewDoc();
#ifdef _DEBUG
	virtual void AssertValid() const;
	virtual void Dump(CDumpContext& dc) const;
#endif

protected:

// �������ꂽ���b�Z�[�W �}�b�v�֐�
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
// Microsoft Visual C++ �͑O�s�̒��O�ɒǉ��̐錾��}�����܂��B

#endif // !defined(AFX_MMVIEWDOC_H__95FF643B_EE22_11D2_8C18_00104B939DF5__INCLUDED_)
