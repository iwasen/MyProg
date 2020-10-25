// vjb30040Dlg.h : �w�b�_�[ �t�@�C��
//

#if !defined(AFX_VJB30040DLG_H__3FD25357_9ABD_11D4_ADFB_00105A67EE03__INCLUDED_)
#define AFX_VJB30040DLG_H__3FD25357_9ABD_11D4_ADFB_00105A67EE03__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

/////////////////////////////////////////////////////////////////////////////
// CVjb30040Dlg �_�C�A���O

class CVjb30040Dlg : public CDialog
{
// �\�z
public:
	CVjb30040Dlg(CWnd* pParent = NULL);	// �W���̃R���X�g���N�^

// �_�C�A���O �f�[�^
	//{{AFX_DATA(CVjb30040Dlg)
	enum { IDD = IDD_VJB30040_DIALOG };
	CEdit	m_EDIT_SHIKIBETSU_CODE;
	CComboBox	m_COMBO_SRV_NAME;
	CComboBox	m_COMBO_DEVICE_NAME;
	CEdit	m_EDIT_VOL_SU;
	CStatic	m_LABEL_VOL_SU;
	CEdit	m_EDIT_VOL_FILE;
	CStatic	m_LABEL_FILE_NAME;
	CStatic	m_LABEL_VOL_NAME;
	CButton	m_RADIO_LABEL_ARI;
	CButton	m_RADIO_LABEL_NASHI;
	int		m_iRadioLabel;
	int		m_iComboDeviceName;
	CString	m_sEditVolFile;
	CString	m_sEditShikibetsuCode;
	CString	m_sEditVolSu;
	//}}AFX_DATA

	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	//{{AFX_VIRTUAL(CVjb30040Dlg)
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);	// DDX/DDV �̃T�|�[�g
	//}}AFX_VIRTUAL

// �C���v�������e�[�V����
protected:
	HICON m_hIcon;

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
	//{{AFX_MSG(CVjb30040Dlg)
	virtual BOOL OnInitDialog();
	afx_msg void OnPaint();
	afx_msg HCURSOR OnQueryDragIcon();
	afx_msg void OnSelchangeComboDeviceName();
	afx_msg void OnRadioLabelAri();
	afx_msg void OnRadioLabelNashi();
	afx_msg void OnButtonZikou();
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

private:
	void SetInitInstance();										// ��ʏ����\����Ԑݒ菈��
	void SetDeviceName();										// DEVICE���ݒ菈��
	void SetServerName();										// �o�͐�o�b�`�T�[�o���ݒ菈��
	bool CheckData();											// ���͔}�́A�o�͐�o�b�`�T�[�o�A�}�̎��ʃR�[�h���̓`�F�b�N����
	void Execute();												// ���s����
	int InputDevice(Param *pParam, class CInputDevice *pInputDevice);	// �O���}�̓��͏���
	void ExecuteFileCopyPrg(Param *pParam);						// �t�@�C���R�s�[�v���O�����N������
	bool CheckDeviceInfo();										// DEVICE���Ƃ̓��̓`�F�b�N����
	void DeleteEditInfo(int p_iCode);							// �G�f�B�b�g���e��������
	void SetInitRadio();										// ���W�I�{�^��������Ԑݒ菈��
	bool CheckNyuryokuData(CWnd *p_Wnd);						// �I�u�W�F�N�g�f�[�^���̓`�F�b�N����
	bool CheckDeviceCodeData();									// �}�̎��ʃR�[�h���͒l�Ó����`�F�b�N
	bool CheckVolFileVolsuData();								// ���͒l�Ó����`�F�b�N����
	bool CheckVolNyuryokuData(CString &p_csVolLabelData); 
	bool CheckLabelAriVolData(CString &p_csVolLabelData);		// Vol���x�������͒l�Ó����`�F�b�N����
	bool CheckLabelNashiVolSu();								// Vol�{�����͒l�Ó����`�F�b�N����
	bool CheckLabelNashiFileNameRecord();						// �t�@�C����/���R�[�h�����͒l�Ó����`�F�b�N����
	bool CheckSlashData(CString &p_csFileNameRecord);			// "/"������`�F�b�N����
	bool CheckLabelAriNashiKanmaData(CString &p_csFileNameRecord, int p_nFlg); // �t�@�C����/���R�[�h���AVOL���x�����̓��͒l�`�F�b�N����
	bool CheckStartEndSlashData(CString &p_csNyuryokuData, int &p_nFileLen); // �擪�A�y�сA����������"/"�����񂩂ǂ����`�F�b�N����
	void WriteLogFile(Param *pParam, LPCTSTR pLogFile, LPCTSTR pStatus);	// ���O�t�@�C����������
};

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ �͑O�s�̒��O�ɒǉ��̐錾��}�����܂��B

#endif // !defined(AFX_VJB30040DLG_H__3FD25357_9ABD_11D4_ADFB_00105A67EE03__INCLUDED_)
