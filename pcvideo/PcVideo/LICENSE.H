// license.h : �w�b�_�[ �t�@�C��
//


/////////////////////////////////////////////////////////////////////////////
// CLicenseWnd �_�C�A���O

class CLicenseWnd : public CDialog
{
// �R���X�g���N�^
public:
	CLicenseWnd(CWnd* pParent = NULL);	// �W���̃R���X�g���N�^

// �_�C�A���O �f�[�^
	//{{AFX_DATA(CLicenseWnd)
	enum { IDD = IDD_LICENSE };
		// ����: ClassWizard �͂��̈ʒu�Ƀf�[�^�����o��ǉ����܂��B
	//}}AFX_DATA

// �C���v�������e�[�V����
protected:
	virtual void DoDataExchange(CDataExchange* pDX);	// DDX/DDV �T�|�[�g

	// ���b�Z�[�W�}�b�v�֐��̐���
	//{{AFX_MSG(CLicenseWnd)
		// ����: ClassWizard �͂��̈ʒu�Ƀ����o�֐���ǉ����܂��B
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()
};