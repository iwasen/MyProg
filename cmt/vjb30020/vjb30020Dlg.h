// vjb30020Dlg.h : �w�b�_�[ �t�@�C��
//

#if !defined(AFX_VJB30020DLG_H__36752E37_6F02_439D_B346_3C43E7E5420A__INCLUDED_)
#define AFX_VJB30020DLG_H__36752E37_6F02_439D_B346_3C43E7E5420A__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

#define LEN_SHUBETSU_CODE	3			// �}�̎�ʃR�[�h��
#define LEN_SHIKIBETSU_CODE	6			// �}�̎��ʃR�[�h��
#define LEN_VOLUME_LABEL	6			// �{�����[�����x����
#define LEN_TOTAL	(LEN_SHUBETSU_CODE + LEN_SHIKIBETSU_CODE + LEN_VOLUME_LABEL)
#define MAX_VOLUME_LABEL	7			// �ő�{�����[�����x����

// �L���[�t�@�C�����
struct Queue {
	CString sFileName;
	CString sShubetsuCode;
	CString sShikibetsuCode;
	CString sVolLabel;
	CString sCreateDate;
	char cStatus;
	BOOL bAvail;
};

/////////////////////////////////////////////////////////////////////////////
// CVjb30020Dlg �_�C�A���O

class CVjb30020Dlg : public CDialog
{
// �\�z
public:
	CVjb30020Dlg(CWnd* pParent = NULL);	// �W���̃R���X�g���N�^

// �_�C�A���O �f�[�^
	//{{AFX_DATA(CVjb30020Dlg)
	enum { IDD = IDD_VJB30020_DIALOG };
	CButton	m_cNonCheck;
	CLixxxtrl	m_cQueueList;
	//}}AFX_DATA

	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	//{{AFX_VIRTUAL(CVjb30020Dlg)
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);	// DDX/DDV �̃T�|�[�g
	//}}AFX_VIRTUAL

// �C���v�������e�[�V����
protected:
	HICON m_hIcon;

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
	//{{AFX_MSG(CVjb30020Dlg)
	virtual BOOL OnInitDialog();
	afx_msg void OnPaint();
	afx_msg HCURSOR OnQueryDragIcon();
	virtual void OnOK();
	afx_msg void OnNoncheck();
	afx_msg void OnReload();
	afx_msg void OnTimer(UINT nIDEvent);
	afx_msg void OnDestroy();
	afx_msg void OnClickQueueList(NMHDR* pNMHDR, LRESULT* pResult);
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

private:
	void Initialize();
	void SetLixxxolumn();
	void SetQueueList(BOOL nSendMessage);
	Queue *GetQueueData(CString sFileName);
	void DeleteQueueList();
	void AddQueueList(Queue *pQueue);
	CString GetStatusText(char status);
	void Execute(char status);
	void ExecuteCopyProgram(Queue *pQueue);
	void ChangeStatus(Queue *pQueue, char status, int nItem);
	CString FormatDate(Queue *pQueue);
	void EnableNonCheck();
};

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ �͑O�s�̒��O�ɒǉ��̐錾��}�����܂��B

#endif // !defined(AFX_VJB30020DLG_H__36752E37_6F02_439D_B346_3C43E7E5420A__INCLUDED_)
