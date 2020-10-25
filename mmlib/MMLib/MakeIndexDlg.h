#pragma once

// MakeIndexDlg.h : �w�b�_�[ �t�@�C��
//

/////////////////////////////////////////////////////////////////////////////
// CMakeIndexDlg �_�C�A���O

class CMakeIndexDlg : public CDialog
{
// �R���X�g���N�V����
public:
	CMakeIndexDlg(CWnd* pParent = NULL);   // �W���̃R���X�g���N�^

// �_�C�A���O �f�[�^
	enum { IDD = IDD_MAKE_INDEX };
	CListBox	m_cLibraryList;


// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g

// �C���v�������e�[�V����
protected:

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
	virtual BOOL OnInitDialog();
	virtual void OnOK();
	afx_msg void OnAllSelect();
	DECLARE_MESSAGE_MAP()

public:
	CMMLibDoc *m_pDoc;

protected:
	int SetLibraryListBox();
	BOOL CreateAllIndex();
	BOOL CreateKeywordIndex(CPcserve &pcserve, CPSDB &db);
	BOOL WriteKeywordIndex(CItemFolder *pItemFolder, CPSDB &db);
	BOOL WriteSub(CPSDB *pDB, CPSDB &db, char type, LPCTSTR pLibraryID, LPCTSTR pLibraryName);
};
