#pragma once

// PictDoc.h : �w�b�_�[ �t�@�C��
//

#include "PictFrame.h"

/////////////////////////////////////////////////////////////////////////////
// CPictDoc �h�L�������g

class CPictDoc : public CDocument
{
protected:
	CPictDoc();           // ���I�����Ɏg�p�����v���e�N�g �R���X�g���N�^�B
	DECLARE_DYNCREATE(CPictDoc)

// �A�g���r���[�g
public:

// �I�y���[�V����
public:

// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	public:
	virtual void Serialize(CArchive& ar);   // �h�L�������g I/O �ɑ΂��ăI�[�o�[���C�h����܂��B
	virtual BOOL OnOpenDocument(LPCTSTR lpszPathName);
	virtual void SetPathName(LPCTSTR lpszPathName, BOOL bAddToMRU = TRUE);
	virtual BOOL OnCmdMsg(UINT nID, int nCode, void* pExtra, AFX_CMDHANDLERINFO* pHandlerInfo);
	protected:
	virtual BOOL OnNewDocument();

// �C���v�������e�[�V����
public:
	virtual ~CPictDoc();
#ifdef _DEBUG
	virtual void AssertValid() const;
	virtual void Dump(CDumpContext& dc) const;
#endif

	// ���b�Z�[�W �}�b�v�֐��̐���
protected:
	afx_msg void OnPictAutosize();
	afx_msg void OnPictAutosize2();
	afx_msg void OnPictEnlarge();
	afx_msg void OnPictReduce();
	afx_msg void OnPictLeftRotate();
	afx_msg void OnPictRightRotate();
	afx_msg void OnUpdatePictAutosize(CCmdUI* pCmdUI);
	afx_msg void OnUpdatePictAutosize2(CCmdUI* pCmdUI);
	afx_msg void OnClosePict();
	afx_msg void OnSelchangeCombo();
	afx_msg void OnEditchangeCombo();
	DECLARE_MESSAGE_MAP()

public:
	HDIB m_hDIB;
	int m_nAutoSize;
	double m_fPictRatio;

	void ShowPicture(LPCTSTR pTitle, HDIB hDIB, int nAutoSize, double fPictRatio);

private:
	CPictFrame *GetPictFrame();
};
