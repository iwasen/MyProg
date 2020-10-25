#if !defined(AFX_LOGINDLG_H__506F2DF3_E5AB_11D2_8C08_00104B939DF5__INCLUDED_)
#define AFX_LOGINDLG_H__506F2DF3_E5AB_11D2_8C08_00104B939DF5__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000
// LoginDlg.h : �w�b�_�[ �t�@�C��
//

extern void ReceiveLoginRequest(PLOGINREQ pLoginReq, PSOCK pSockSend);

/////////////////////////////////////////////////////////////////////////////
// CLoginDlg �_�C�A���O

class CLoginDlg : public CDialog
{
// �R���X�g���N�V����
public:
	CLoginDlg(CWnd* pParent = NULL);   // �W���̃R���X�g���N�^

// �_�C�A���O �f�[�^
	//{{AFX_DATA(CLoginDlg)
	enum { IDD = IDD_LOGIN };
	CEdit	m_cUserID;
	CButton	m_cOK;
	CEdit	m_cPassword;
	CString	m_sPassword;
	BOOL	m_bSavePassword;
	CString	m_sServerName;
	CString	m_sUserID;
	//}}AFX_DATA


// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	//{{AFX_VIRTUAL(CLoginDlg)
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g
	//}}AFX_VIRTUAL

// �C���v�������e�[�V����
protected:

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
	//{{AFX_MSG(CLoginDlg)
	virtual BOOL OnInitDialog();
	virtual void OnOK();
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

public:
	DWORD m_ServerID;

protected:
	void EncodePassword(LPCTSTR pPasswordIn, LPSTR pPasswordOut);
	void DecodePassword(LPCTSTR pPasswordIn, LPSTR pPasswordOut);
};

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ �͑O�s�̒��O�ɒǉ��̐錾��}�����܂��B

#endif // !defined(AFX_LOGINDLG_H__506F2DF3_E5AB_11D2_8C08_00104B939DF5__INCLUDED_)
