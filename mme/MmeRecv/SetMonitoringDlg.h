#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		SetMonitoringDlg.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���퉷�x�E�d���͈͐ݒ�_�C�A���O�N���X�̒�`
//----------------------------------------------------------------------------------------------------
//  3. ���l
//		����
//*****************************************************************************************************

#include "myctrl.h"
#include "MmeData.h"


// CSetMonitoringDlg �_�C�A���O

class CSetMonitoringDlg : public CDialog
{
	DECLARE_DYNAMIC(CSetMonitoringDlg)

public:
	CSetMonitoringDlg(CWnd* pParent = NULL);   // �W���R���X�g���N�^

// �_�C�A���O �f�[�^
	enum { IDD = IDD_SET_MONITORING };

	double (*m_pNormalTempRange1)[N_UNIT][N_TEMP];
	double (*m_pNormalTempRange2)[N_UNIT][N_TEMP];
	double (*m_pNormalVoltageRange1)[N_UNIT];
	double (*m_pNormalVoltageRange2)[N_UNIT];

protected:
	CEditEx m_cEditTempRange1[N_UNIT][N_TEMP];
	CEditEx m_cEditTempRange2[N_UNIT][N_TEMP];
	CEditEx m_cEditVoltageRange1[N_UNIT];
	CEditEx m_cEditVoltageRange2[N_UNIT];

	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g

	DECLARE_MESSAGE_MAP()
	virtual BOOL OnInitDialog();
	virtual void OnOK();
};
