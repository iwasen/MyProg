#pragma once

// BackupDlg.h : �w�b�_�[ �t�@�C��
//

/////////////////////////////////////////////////////////////////////////////
// CBackupDlg �_�C�A���O

class CBackupDlg : public CDialog
{
// �R���X�g���N�V����
public:
	CBackupDlg(CWnd* pParent = NULL);   // �W���̃R���X�g���N�^

// �_�C�A���O �f�[�^
	enum { IDD = IDD_BACKUP };
	CButton	m_cDelete;
	CButton	m_cCancel;
	CLixxxtrl	m_cBackupList;
	CComboBox	m_cLibrary;
	CProgressCtrl	m_cProgress;

// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g

// �C���v�������e�[�V����
protected:
	// �������ꂽ���b�Z�[�W �}�b�v�֐�
	virtual BOOL OnInitDialog();
	virtual void OnOK();
	afx_msg void OnDelete();
	afx_msg void OnColumnclickBackupList(NMHDR* pNMHDR, LRESULT* pResult);
	afx_msg void OnDestroy();
	DECLARE_MESSAGE_MAP()

public:
	CItemFolder *m_pItemFolder;

private:
	int m_nSortItem;
	int m_nSortDir;

	void SetBackupList();
	static int CALLBACK CompareItems(LPARAM lParam1, LPARAM lParam2, LPARAM lParamSort);
	void RemoveBackupItem();
};
