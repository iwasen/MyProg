// EcuRecvDlg.h : �w�b�_�[ �t�@�C��
//

#if !defined(AFX_ECURECVDLG_H__8CC9B147_B139_11D3_8056_7A2C79000000__INCLUDED_)
#define AFX_ECURECVDLG_H__8CC9B147_B139_11D3_8056_7A2C79000000__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

/////////////////////////////////////////////////////////////////////////////
// CEcuRecvDlg �_�C�A���O

class CEcuRecvDlg : public CDialog
{
// �\�z
public:
	CEcuRecvDlg(CWnd* pParent = NULL);	// �W���̃R���X�g���N�^

// �_�C�A���O �f�[�^
	//{{AFX_DATA(CEcuRecvDlg)
	enum { IDD = IDD_ECURECV_DIALOG };
	CEdit	m_cSaveFolder;
	CButton	m_cExit;
	CButton	m_cStop;
	CButton	m_cStart;
	CString	m_sReceiveStatus;
	CString	m_sSaveFolder;
	CString	m_sTexxxount;
	CString	m_sReceiveCount;
	//}}AFX_DATA

	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	//{{AFX_VIRTUAL(CEcuRecvDlg)
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);	// DDX/DDV �̃T�|�[�g
	//}}AFX_VIRTUAL

// �C���v�������e�[�V����
protected:
	HICON m_hIcon;

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
	//{{AFX_MSG(CEcuRecvDlg)
	virtual BOOL OnInitDialog();
	afx_msg void OnSysCommand(UINT nID, LPARAM lParam);
	afx_msg void OnPaint();
	afx_msg HCURSOR OnQueryDragIcon();
	afx_msg void OnStart();
	afx_msg void OnStop();
	virtual void OnCancel();
	//}}AFX_MSG
	afx_msg LONG OnReceiveNotify(WPARAM wParam, LPARAM lParam);
	DECLARE_MESSAGE_MAP()

private:
	void EnableControl(BOOL bStart);
	BOOL SaveFolderCheck(CString &sSaveFolder);
	void DeleteSaveFile(CString &sSaveFolder);
};

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ �͑O�s�̒��O�ɒǉ��̐錾��}�����܂��B

#endif // !defined(AFX_ECURECVDLG_H__8CC9B147_B139_11D3_8056_7A2C79000000__INCLUDED_)
