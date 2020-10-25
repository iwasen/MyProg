// SelectStartTimeDlg.cpp : �����t�@�C��
//

#include "stdafx.h"
#include "MMA_G.h"
#include "SelectStartTimeDlg.h"
#include "General.h"


// CSelectStartTimeDlg �_�C�A���O

IMPLEMENT_DYNAMIC(CSelectStartTimeDlg, CDialog)

CSelectStartTimeDlg::CSelectStartTimeDlg(CWnd* pParent /*=NULL*/)
	: CDialog(CSelectStartTimeDlg::IDD, pParent)
{
	m_nSelectIndex = 0;
}

CSelectStartTimeDlg::~CSelectStartTimeDlg()
{
}

void CSelectStartTimeDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_COMBO_SENSOR, m_cComboSensor);
	DDX_Control(pDX, IDOK, m_cButtonOK);
}


BEGIN_MESSAGE_MAP(CSelectStartTimeDlg, CDialog)
	ON_CBN_SELCHANGE(IDC_COMBO_SENSOR, &CSelectStartTimeDlg::OnCbnSelchangeComboSensor)
END_MESSAGE_MAP()


// CSelectStartTimeDlg ���b�Z�[�W �n���h��

//*****************************************************************************************************
//  1. �֐���
//		CSelectStartTimeDlg::GetMaiFileName
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�v���J�n������I�����A���̃t�@�C������Ԃ�
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CString			&sRsuNo			[I] RSU No.
//		CStringArray	&saFileName		[I] �I������mai�t�@�C�����̔z��
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		CString		�I������mai�t�@�C����
//*****************************************************************************************************
CString CSelectStartTimeDlg::GetMaiFileName(const CString &sRsuNo, const CStringArray &saFileName)
{
	LPCTSTR pSeparator = "-_.";
	CString sStartTime;
	CString sEndTime;
	int i, j;

	// �t�@�C��������J�n���������o��
	for (i = 0; i < saFileName.GetSize(); i++) {
		const CString &sFileName = saFileName[i];

		CStringArray saParts;
		CGeneral::StrSplit(sFileName, pSeparator, saParts);

		if (saParts[0] == sRsuNo) {
			sStartTime.Format("%s/%s/%s %s:%s:%s.%s", saParts[1], saParts[2], saParts[3], saParts[4], saParts[5], saParts[6], saParts[7]);
			sEndTime = saParts[8];

			int nSize = (int)m_aoMaiFile.GetSize();
			for (j = 0; j < nSize; j++) {
				if (sStartTime == m_aoMaiFile[j].sStartTime) {
					if (sEndTime > m_aoMaiFile[j].sEndTime) {
						m_aoMaiFile[j].sFileName = sFileName;
						m_aoMaiFile[j].sEndTime = sEndTime;
					}
					break;
				}
			}

			if (j == nSize) {
				SMaiFile oMaiFile;
				oMaiFile.sFileName = sFileName;
				oMaiFile.sStartTime = sStartTime;
				oMaiFile.sEndTime = sEndTime;
				m_aoMaiFile.Add(oMaiFile);
			}
		}
	}

	// ����RSU�ňقȂ�J�n�����̃t�@�C������������_�C�A���O��\�����đI��������
	if (m_aoMaiFile.GetSize() > 1)
		DoModal();

	return m_aoMaiFile[m_nSelectIndex].sFileName;
}

//*****************************************************************************************************
//  1. �֐���
//		CSelectStartTimeDlg::OnInitDialog
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�_�C�A���O����������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL	TRUE
//*****************************************************************************************************
BOOL CSelectStartTimeDlg::OnInitDialog()
{
	CDialog::OnInitDialog();

	for (int i = 0; i < m_aoMaiFile.GetSize(); i++) {
		int nIndex = m_cComboSensor.AddString(m_aoMaiFile[i].sStartTime);
		m_cComboSensor.SetItemData(nIndex, i);
	}

	return TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CSelectStartTimeDlg::OnCbnSelchangeComboSensor
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�t�@�C�����R���{�{�b�N�X�I������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CSelectStartTimeDlg::OnCbnSelchangeComboSensor()
{
	// �J�n������I��������OK�{�^����L���ɂ���
	m_cButtonOK.EnableWindow(m_cComboSensor.GetCurSel() != CB_ERR);
}

//*****************************************************************************************************
//  1. �֐���
//		CSelectStartTimeDlg::OnOK
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		OK�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CSelectStartTimeDlg::OnOK()
{
	// �I�������J�n�����̃C���f�b�N�X��ۑ����A�_�C�A���O�����
	int nIndex = m_cComboSensor.GetCurSel();
	if (nIndex != CB_ERR) {
		m_nSelectIndex = (int)m_cComboSensor.GetItemData(nIndex);

		CDialog::OnOK();
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CSelectStartTimeDlg::OnCancel
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�L�����Z���{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CSelectStartTimeDlg::OnCancel()
{
	// �������Ȃ�(ESC�܂��͕���{�^���ŏI������̂�h���j
}
