#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		ManualMoveDlg.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		CManualMoveDlg �N���X�̃C���^�[�t�F�C�X
//----------------------------------------------------------------------------------------------------
//  3. ���l
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************

#include "DialogEx.h"
#include "ManualEditDocument.h"
#include "LixxxtrlEx1.h"

// �H��/�v�f��ƌQ�̈ړ��_�C�A���O
class CManualMoveDlg : public CDialogEx
{
	// ��Ǝ҃��X�g�f�[�^
	struct SSagyoshaList {
		int nSublineId;
		int nSagyoshaId;
	};
	typedef CArrayEx <SSagyoshaList, SSagyoshaList&> CSagyoshaListArray;

	// ���X�g�{�b�N�X�ݒ�f�[�^
	struct CListData {
		CString sName;		// ���ږ�
		double fTime;		// ��Ǝ���
// Modify ... ( ADD )
		CString sKishuName;		// �@��ID
		int nKyotsuKoteiNo;		// ���ʍH���O���[�vNo
// By Y.Itabashi (xxxxx) 2007.02.07
		int nId;			// ����ID
		int nOrg;			// �ړ��O
		SGraphBlock *pGraphBlock;
		BOOL bMoveFalg;
	};
	typedef CArrayEx <CListData, CListData&> CListDataArray;

// �R���X�g���N�V����
public:
	CManualMoveDlg(CWnd* pParent = NULL);   // �W���̃R���X�g���N�^

// �_�C�A���O �f�[�^
	//{{AFX_DATA(CManualMoveDlg)
	enum { IDD = IDD_MANUAL_MOVE };
	CButton	m_cButtonPreview;
	CButton	m_cButtonSettei;
	CStatic	m_cStaticTotalTime2;
	CStatic	m_cStaticTotalTime1;
	CComboBox	m_cComboSagyosha2;
	CComboBox	m_cComboSagyosha1;
// Modify ... ( CHANGE )
	CLixxxtrlEx		m_cList2;
	CLixxxtrlEx		m_cList1;
//	CLixxxtrlEx1	m_cList2;
//	CLixxxtrlEx1	m_cList1;
// By Y.Itabashi (xxxxx) 2007.02.07
	int		m_nRadioKotei;
	//}}AFX_DATA

	int m_nMode;					// �H���^�v�f��ƌQ���[�h
	int m_nKishuId;					// �@��ID
// Modify ... ( ADD )
	int m_nGraphKishu;				// �\���@��ID
// By Y.Itabashi (xxxxx) 2007.02.26
	CString m_sSagyosha;			// ��ƎҖ�
	CManualEditDocument *m_pDoc;	// �h�L�������g�N���X�ւ̃|�C���^
// Modify ... ( ADD )
	int m_nSublineIdFrom;			// �ړ����T�u���C��
	int m_nSublineIdTo;			// �ړ���T�u���C��
// By Y.Itabashi (xxxxx) 2007.02.07

// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	//{{AFX_VIRTUAL(CManualMoveDlg)
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g
	//}}AFX_VIRTUAL

// �C���v�������e�[�V����
protected:

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
	//{{AFX_MSG(CManualMoveDlg)
	virtual BOOL OnInitDialog();
	afx_msg void OnSelchangeComboSagyosha1();
	afx_msg void OnSelchangeComboSagyosha2();
	afx_msg void OnButtonSettei();
	afx_msg void OnButtonPreview();
	virtual void OnCancel();
	afx_msg void OnRadioKotei();
	afx_msg void OnRadioYosoSagyogun();
	afx_msg void OnItemchangedList1(NMHDR* pNMHDR, LRESULT* pResult);
	afx_msg void OnItemchangedList2(NMHDR* pNMHDR, LRESULT* pResult);
	afx_msg void OnClickList1(NMHDR* pNMHDR, LRESULT* pResult);
	afx_msg void OnClickList2(NMHDR* pNMHDR, LRESULT* pResult);
	//}}AFX_MSG
	afx_msg LRESULT OnDragDrop(WPARAM wParam, LPARAM lParam);
	afx_msg void OnBeginDrag(WPARAM wParam, LPARAM lParam);
	DECLARE_MESSAGE_MAP()

	BOOL m_bEdit;					// �ύX�L���t���O
	CSagyoshaListArray m_aSagyoshaList;	// ��Ǝ҃��X�g�f�[�^
	CListDataArray m_aListData1;	// �����X�g�{�b�N�X�ݒ�f�[�^�z��
	CListDataArray m_aListData2;	// �E���X�g�{�b�N�X�ݒ�f�[�^�z��
// Modify ... ( ADD )
	BOOL m_bSelectIvent;			// �I���������[�h
// By Y.Itabashi (xxxxx) 2007.02.07
// Modify ... ( ADD )
	BOOL m_bMove;					// �ړ������σt���O
// By Y.Itabashi (xxxxx) 2007.02.12

// Modify ... ( CHANGE )
	void SetSagyoshaList();
	void SetListAll();
	void SetList(CListDataArray &aListData, CComboBox &cComboSagyosha, CLixxxtrlEx &cList, int nOrg);
//	void SetList(CListDataArray &aListData, CComboBox &cComboSagyosha, CLixxxtrlEx1 &cList, int nOrg);
	void SetKoteiList(CListDataArray &aListData, CComboBox &cComboSagyosha, CLixxxtrlEx &cList, int nOrg);
//	void SetKoteiList(CListDataArray &aListData, CComboBox &cComboSagyosha, CLixxxtrlEx1 &cList, int nOrg);
	void SetYosoSagyogunList(CListDataArray &aListData, CComboBox &cComboSagyosha, CLixxxtrlEx &cList, int nOrg);
//	void SetYosoSagyogunList(CListDataArray &aListData, CComboBox &cComboSagyosha, CLixxxtrlEx1 &cList, int nOrg);
	void SetListBox(CListDataArray &aListData, CLixxxtrlEx &cList);
//	void SetListBox(CListDataArray &aListData, CLixxxtrlEx1 &cList);
	void DispTotalTimeAll();
	void DispTotalTime(double fTime, CStatic &cStatic);
	double CalcTotalTime(CLixxxtrlEx &cList);
//	double CalcTotalTime(CLixxxtrlEx1 &cList);
	void EnableButtons();
	BOOL MoveCheck(BOOL bLimitCheck) ;
	void GetMoveData(CLixxxtrlEx &cList, CMoveDataArray &aMoveData);
//	void GetMoveData(CLixxxtrlEx1 &cList, CMoveDataArray &aMoveData);
//	void SetLixxxolumn(CLixxxtrlEx1 &cList);
	void MoveItem(HWND hWndDrag, HWND hWndDrop);
	void AutoKyotsuKoteiSelect( CLixxxtrlEx &cList, int Row );
	void AutoAutoKoteiSelect( CLixxxtrlEx &cList );
	BOOL CheckMoveIndex( CLixxxtrlEx &cList );
// By Y.Itabashi (xxxxx) 2007.02.07
};

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ �͑O�s�̒��O�ɒǉ��̐錾��}�����܂��B
