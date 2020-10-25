#pragma once

// PermissionDlg.h : �w�b�_�[ �t�@�C��
//

/////////////////////////////////////////////////////////////////////////////
// CPermissionDlg �_�C�A���O

class CPermissionDlg : public CDialog
{
// �R���X�g���N�V����
public:
	CPermissionDlg(CWnd* pParent = NULL);   // �W���̃R���X�g���N�^

// �_�C�A���O �f�[�^
	enum { IDD = IDD_PERMISSION };
	CComboBox	m_cLibrary;
	int		m_iPermission;


// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g

// �C���v�������e�[�V����
protected:

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
	virtual BOOL OnInitDialog();
	virtual void OnOK();
	afx_msg void OnSelchangeLibrary();
	DECLARE_MESSAGE_MAP()

public:
	CItemFolder *m_pItemFolder;
	DWORD m_dwPermission;

private:
	void SetRadioButton();
	void GetRadioButton();
};
