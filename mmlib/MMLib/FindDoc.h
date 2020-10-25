#pragma once

// FindDoc.h : �w�b�_�[ �t�@�C��
//

#include "FindFrame.h"

/////////////////////////////////////////////////////////////////////////////
// CFindDoc �h�L�������g

class CFindDoc : public CDocument
{
protected:
	CFindDoc();           // ���I�����Ɏg�p�����v���e�N�g �R���X�g���N�^�B
	DECLARE_DYNCREATE(CFindDoc)

// �A�g���r���[�g
public:

// �I�y���[�V����
public:

// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	public:
	virtual void Serialize(CArchive& ar);   // �h�L�������g I/O �ɑ΂��ăI�[�o�[���C�h����܂��B
	virtual BOOL OnOpenDocument(LPCTSTR lpszPathName);
	protected:
	virtual BOOL OnNewDocument();

// �C���v�������e�[�V����
public:
	virtual ~CFindDoc();
#ifdef _DEBUG
	virtual void AssertValid() const;
	virtual void Dump(CDumpContext& dc) const;
#endif

	// ���b�Z�[�W �}�b�v�֐��̐���
protected:
	afx_msg void OnCloseFind();
	afx_msg void OnMakeIndex();
	afx_msg void OnFind();
	DECLARE_MESSAGE_MAP()

public:
	CFindFrame *m_pFindFrame;
	CMMLibDoc *m_pMMLibDoc;
	CString m_sKeyword;
	int m_nAndOr;
};
