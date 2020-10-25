// remote.h : �w�b�_�[ �t�@�C��
//


/////////////////////////////////////////////////////////////////////////////
// CRemoteDlg �_�C�A���O

class CRemoteDlg : public CDialog
{
// �R���X�g���N�^
public:
	CRemoteDlg(CWnd* pParent = NULL, PUSER pUser = NULL);

// �_�C�A���O �f�[�^
	//{{AFX_DATA(CRemoteDlg)
	enum { IDD = IDD_REMOTE };
	CString	m_sDownLoadFile;
	//}}AFX_DATA

// �C���v�������e�[�V����
protected:
	virtual void DoDataExchange(CDataExchange* pDX);	// DDX/DDV �T�|�[�g

	// ���b�Z�[�W�}�b�v�֐��̐���
	//{{AFX_MSG(CRemoteDlg)
	virtual BOOL OnInitDialog();
	afx_msg void OnClickedRemoteChat();
	afx_msg void OnClickedRemoteVoice();
	afx_msg void OnClickedRemoteRestart();
	afx_msg void OnClickedRemoteExit();
	afx_msg void OnClickedRemoteSvoice();
	afx_msg void OnRemoteExec();
	afx_msg void OnRemoteDownload();
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

private:
	PUSER	pUser;
};