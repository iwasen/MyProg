#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		ManualBunkatsuDlg.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		CManualBunkatsuDlg �N���X�̃C���^�[�t�F�C�X
//----------------------------------------------------------------------------------------------------
//  3. ���l
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************

#include "DialogEx.h"
#include "ManualEditDocument.h"
#include "LixxxtrlEx1.h"

// �H��/�v�f��ƌQ�̕����폜�_�C�A���O
class CManualBunkatsuDlg : public CDialogEx
{
	// ��Ǝ҃��X�g�f�[�^
	struct SSagyoshaList {
		int nSublineId;
		int nSagyoshaId;
	};
	typedef CArrayEx <SSagyoshaList, SSagyoshaList&> CSagyoshaListArray;

	// ���X�g�{�b�N�X�ݒ�f�[�^
	struct SListData {
// Modify ... ( ADD )
		int nSagyoNo;		// ���No
// By Y.Itabashi (xxxxx) 2007.02.19
		CString sName;		// ���ږ�
		double fTime;		// ��Ǝ���
		int nId;			// ����ID
		SGraphBlock *pGraphBlock;
	};
	typedef CArrayEx <SListData, SListData&> CListDataArray;

// �R���X�g���N�V����
public:
	CManualBunkatsuDlg(CWnd* pParent = NULL);   // �W���̃R���X�g���N�^

// �_�C�A���O �f�[�^
	//{{AFX_DATA(CManualBunkatsuDlg)
	enum { IDD = IDD_MANUAL_BUNKATSU };
	CButton	m_cButtonPreview;
	CButton	m_cButtonSettei;
	CButton	m_cButtonRightMove;
	CButton	m_cButtonLeftMove;
	CStatic	m_cStaticTotalTime2;
	CStatic	m_cStaticTotalTime1;
	CComboBox	m_cComboYosoSagyogun;
	CComboBox	m_cComboKotei;
	CComboBox	m_cComboSagyosha;
	CLixxxtrlEx1	m_cList2;
	CLixxxtrlEx1	m_cList1;
	//}}AFX_DATA

	int m_nKishuId;					// �@��ID
	int m_nSagyoshaId;				// ��Ǝ�ID
	CString m_sKoteiName;			// �H����
	CString m_sYosoSagyogunName;	// �v�f��ƌQ��
	CManualEditDocument *m_pDoc;	// �h�L�������g�N���X�ւ̃|�C���^

// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	//{{AFX_VIRTUAL(CManualBunkatsuDlg)
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g
	//}}AFX_VIRTUAL

// �C���v�������e�[�V����
protected:

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
	//{{AFX_MSG(CManualBunkatsuDlg)
	virtual BOOL OnInitDialog();
	afx_msg void OnButtonLeftMove();
	afx_msg void OnButtonRightMove();
	afx_msg void OnButtonSettei();
	afx_msg void OnSelchangeComboSagyosha();
	afx_msg void OnSelchangeComboKotei();
	afx_msg void OnSelchangeComboYosoSagyogun();
	afx_msg void OnButtonPreview();
	virtual void OnCancel();
	afx_msg void OnClickList1(NMHDR* pNMHDR, LRESULT* pResult);
	afx_msg void OnItemchangedList1(NMHDR* pNMHDR, LRESULT* pResult);
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

	int m_nMode;					// �\�����[�h
	BOOL m_bPreview;				// �v���r���[���s�t���O
	SYosoSagyogun *m_pYosoSagyogun;	// �v�f��ƌQ�|�C���^
	CSagyoshaListArray m_aSagyoshaList;	// ��Ǝ҃��X�g�f�[�^
	CListDataArray m_aListData;		// ���X�g�{�b�N�X�f�[�^�z��

	void ExecBunkatsuSakujo();
	void KoteiBunkatsu();
	void YosoSagyogunBunkatsu();
	void SetSagyoshaList();
	void SetComboList();
	void SetKoteiList();
	void SetYosoSagyogunList();
	void SetYosoSagyoList();
	void SetComboBox(CComboBox &cComboBox);
	void SetListBox();
	void DispTotalTimeAll();
	void DispTotalTime(double fTime, CStatic &cStatic);
	double CalcTotalTime(CLixxxtrlEx1 &cList);
	void EnableButtons();
	void MoveListBox(CLixxxtrlEx1 &cListFrom, CLixxxtrlEx1 &cListTo);
	void SetLixxxolumn(CLixxxtrlEx1 &cList);
	BOOL ListDataCheck();
};

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ �͑O�s�̒��O�ɒǉ��̐錾��}�����܂��B
