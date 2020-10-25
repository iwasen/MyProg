#pragma once

// RestoreDlg.h : �w�b�_�[ �t�@�C��
//

/////////////////////////////////////////////////////////////////////////////
// CRestoreDlg �_�C�A���O

class CRestoreDlg : public CDialog
{
// �R���X�g���N�V����
public:
	CRestoreDlg(CWnd* pParent = NULL);   // �W���̃R���X�g���N�^

// �_�C�A���O �f�[�^
	enum { IDD = IDD_RESTORE };
	CButton	m_cCancel;
	CProgressCtrl	m_cProgress;
	CButton	m_cDelete;
	CLixxxtrl	m_cBackupList;


// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g

// �C���v�������e�[�V����
protected:

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
	virtual BOOL OnInitDialog();
	virtual void OnOK();
	afx_msg void OnColumnclickBackupList(NMHDR* pNMHDR, LRESULT* pResult);
	afx_msg void OnDelete();
	afx_msg void OnDestroy();
	DECLARE_MESSAGE_MAP()

private:
	int m_nSortItem;
	int m_nSortDir;

	void SetBackupList();
	static int CALLBACK CompareItems(LPARAM lParam1, LPARAM lParam2, LPARAM lParamSort);
	CMMPcsDB *CreateRestoreLibrary();
	void RemoveBackupItem();
};
