// SetFile.h : �w�b�_�[ �t�@�C��
//

/////////////////////////////////////////////////////////////////////////////
// CSetFile �_�C�A���O

class CSetFile : public CDialog
{
// �R���X�g���N�V����
public:
	CSetFile(CWnd* pParent = NULL);   // �W���̃R���X�g���N�^

// �_�C�A���O �f�[�^
	//{{AFX_DATA(CSetFile)
	enum { IDD = IDD_SET_FILE };
	CString	m_OutputFile;
	int		m_OutputRate;
	CString	m_OutputFile2;
	//}}AFX_DATA


// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��𐶐����I�[�o�[���C�h���܂��B
	//{{AFX_VIRTUAL(CSetFile)
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g
	//}}AFX_VIRTUAL

// �C���v�������e�[�V����
protected:

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
	//{{AFX_MSG(CSetFile)
	virtual BOOL OnInitDialog();
	virtual void OnOK();
	afx_msg void OnSelectFile();
	afx_msg void OnSelectFile2();
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()
};
