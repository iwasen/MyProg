#pragma once

// RefNetDlg.h : �w�b�_�[ �t�@�C��
//

/////////////////////////////////////////////////////////////////////////////
// CRefNetDlg �_�C�A���O

class CRefNetDlg : public CDialog
{
// �R���X�g���N�V����
public:
	CRefNetDlg(CWnd* pParent = NULL);   // �W���̃R���X�g���N�^

// �_�C�A���O �f�[�^
	enum { IDD = IDD_REF_NET };
	CListBox	m_cLibList;


// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g

// �C���v�������e�[�V����
protected:

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
	virtual BOOL OnInitDialog();
	virtual void OnOK();
	afx_msg void OnDblclkLiblist();
	DECLARE_MESSAGE_MAP()

public:
	CString m_sServerName;
	int m_nChType;
	CString m_sDirectory;

private:
	int m_nItem;
	CString m_sPath[20];

	BOOL SetLibList();
	void AddRefList(CPcserve &pcserve, CString &dirName);
};
