// call.h : �w�b�_�[ �t�@�C��
//


/////////////////////////////////////////////////////////////////////////////
// CCallWnd �_�C�A���O

class CCallWnd : public CDialog
{
// �R���X�g���N�^
public:
	CCallWnd(CWnd* pParent = NULL);	// �W���̃R���X�g���N�^

// �_�C�A���O �f�[�^
	//{{AFX_DATA(CCallWnd)
	enum { IDD = IDD_CALL };
		// ����: ClassWizard �͂��̈ʒu�Ƀf�[�^�����o��ǉ����܂��B
	//}}AFX_DATA

protected:
	virtual void DoDataExchange(CDataExchange* pDX);
	virtual void PostNcDestroy();

	// ���b�Z�[�W�}�b�v�֐��̐���
	//{{AFX_MSG(CCallWnd)
	virtual BOOL OnInitDialog();
	afx_msg void OnClose();
	virtual void OnCancel();
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()
};
/////////////////////////////////////////////////////////////////////////////
// CAnswerWnd �_�C�A���O

class CAnswerWnd : public CDialog
{
// �R���X�g���N�^
public:
	CAnswerWnd(CWnd* pParent = NULL);	// �W���̃R���X�g���N�^

// �_�C�A���O �f�[�^
	//{{AFX_DATA(CAnswerWnd)
	enum { IDD = IDD_ANSWER };
		// ����: ClassWizard �͂��̈ʒu�Ƀf�[�^�����o��ǉ����܂��B
	//}}AFX_DATA

// �C���v�������e�[�V����
protected:
	virtual void DoDataExchange(CDataExchange* pDX);
	virtual void PostNcDestroy();

	// ���b�Z�[�W�}�b�v�֐��̐���
	//{{AFX_MSG(CAnswerWnd)
	virtual BOOL OnInitDialog();
	afx_msg void OnClose();
	virtual void OnCancel();
	virtual void OnOK();
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()
};