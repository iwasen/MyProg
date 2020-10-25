// setting.h : �w�b�_�[ �t�@�C��
//


/////////////////////////////////////////////////////////////////////////////
// CSettingDlg �_�C�A���O

class CSettingDlg : public CDialog
{
// �R���X�g���N�^
public:
	CSettingDlg(CWnd* pParent = NULL);	// �W���̃R���X�g���N�^

// �_�C�A���O �f�[�^
	//{{AFX_DATA(CSettingDlg)
	enum { IDD = IDD_SETTING };
	CComboBox	m_cVoiceOutputDevice;
	CComboBox	m_cVoiceInputDevice;
	int		m_iVoiceBufferNum;
	//}}AFX_DATA

// �C���v�������e�[�V����
protected:
	virtual void DoDataExchange(CDataExchange* pDX);	// DDX/DDV �T�|�[�g

	// ���b�Z�[�W�}�b�v�֐��̐���
	//{{AFX_MSG(CSettingDlg)
	virtual BOOL OnInitDialog();
	virtual void OnOK();
	virtual void OnCancel();
	afx_msg void OnClickedMaxspeed();
	afx_msg void OnClickedResize();
	afx_msg void OnClickedFixedsize();
	afx_msg void OnLicense();
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

public:
	int	Open(SETTINGS *, LPCTSTR);

private:
	CString	caption;

	void InitVoiceSetting();
};

/////////////////////////////////////////////////////////////////////////////
// CSetHostDlg �_�C�A���O

class CSetHostDlg : public CDialog
{
// �R���X�g���N�^
public:
	CSetHostDlg(CWnd* pParent = NULL);	// �W���̃R���X�g���N�^

// �_�C�A���O �f�[�^
	//{{AFX_DATA(CSetHostDlg)
	enum { IDD = IDD_SETHOST };
		// ����: ClassWizard �͂��̈ʒu�Ƀf�[�^�����o��ǉ����܂��B
	//}}AFX_DATA

// �C���v�������e�[�V����
protected:
	virtual void DoDataExchange(CDataExchange* pDX);	// DDX/DDV �T�|�[�g

	// ���b�Z�[�W�}�b�v�֐��̐���
	//{{AFX_MSG(CSetHostDlg)
	virtual BOOL OnInitDialog();
	virtual void OnOK();
	virtual void OnCancel();
	afx_msg void OnClickedAppend();
	afx_msg void OnClickedChange();
	afx_msg void OnClickedDelete();
	afx_msg void OnDblclkHostlist();
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

private:
	void DispHostData();
};