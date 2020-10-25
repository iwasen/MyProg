//*****************************************************************************************************
//  1. �t�@�C����
//		GraphDigitalData.cpp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�O���t�\���f�W�^�����l�\���_�C�A���O�N���X�̎���
//----------------------------------------------------------------------------------------------------
//  3. ���l
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2008.01.01 M.Hama �V�K�쐬
//*****************************************************************************************************

#include "stdafx.h"
#include "MMA_G.h"
#include "GraphDigitalData.h"
#include "General.h"
#include <math.h>

// CSV�t�@�C���̍��ږ�
static const char *gTitle[] = {
	"No,Freq(Hz),X-PSD(G^2/Hz),Y-PSD(G^2/Hz),Z-PSD(G^2/Hz),G-PSD(G^2/Hz)\n",					// �X�y�N�g���o�͗p
	"No,Freq(Hz),X-Acceleration(G),Y-Acceleration(G),Z-Acceleration(G),G-Acceleration(G)\n"		// 1/3�I�N�^�[�u�o�͗p
};

// CGraphDigitalData �_�C�A���O

IMPLEMENT_DYNAMIC(CGraphDigitalData, CDialogEx)

//*****************************************************************************************************
//  1. �֐���
//		CGraphDigitalData::CGraphDigitalData
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�R���X�g���N�^
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CWnd		*pParent		[I] �e�E�B���h�E�|�C���^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
CGraphDigitalData::CGraphDigitalData(CWnd *pParent)
	: CDialogEx(CGraphDigitalData::IDD, pParent)
	, m_nCount(0)
	, m_pX(NULL)
	, m_sCsvFName(_T(""))
	, m_nPeriod(0)
{

}

CGraphDigitalData::~CGraphDigitalData()
{
}

void CGraphDigitalData::DoDataExchange(CDataExchange* pDX)
{
	CDialogEx::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_DATA_LIST, m_List);
	DDX_Control(pDX, IDC_BUTTON_CSV, m_Button_Csv);
}


BEGIN_MESSAGE_MAP(CGraphDigitalData, CDialogEx)
	ON_NOTIFY(LVN_GETDISPINFO, IDC_DATA_LIST, &CGraphDigitalData::OnLvnGetdispinfoDataList)
	ON_BN_CLICKED(IDC_BUTTON_CSV, &CGraphDigitalData::OnBnClickedButtonCsv)
	ON_WM_DESTROY()
END_MESSAGE_MAP()

// �c�[���`�b�v�\���f�[�^
static const SToolTipData s_aToolTip[] = {
	{IDC_BUTTON_CSV, "�\������Ă��鐔�l�f�[�^��CSV�t�@�C���ɏo�͂��܂��B"},
	{IDCANCEL, "���̃_�C�A���O����܂��B"},
	{0, NULL}
};

//*****************************************************************************************************
//  1. �֐���
//		CGraphDigitalData::SetDataInfo
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���X�g�\������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		int			mode			[I]	0:�X�y�N�g���f�[�^�o�́@1:1/3�I�N�^�[�u�f�[�^�o��
//		int			nCount			[I]	�f�[�^��
//		double		*pX				[I] X�f�[�^�̃|�C���^
//		double		*pY				[I] Y�f�[�^�̃|�C���^
//		double		*pZ				[I] Z�f�[�^�̃|�C���^
//		double		*pG				[I] ���̓f�[�^�̃|�C���^
//		LPCSTR		title			[I] �^�C�g��
//		long		period			[I] �o�͔͈�
//		double		step			[I] ���g���̃X�e�b�v
//		double		*pStepTable		[I] ���g���̃e�[�u��
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphDigitalData::SetDataInfo(int mode, int nCount, const double *pX, const double *pY, const double *pZ, const double *pG, LPCSTR title, long period, double step, const double *pStepTable)
{
	m_nMode = mode;
	m_nCount = nCount;
	m_pX = pX;
	m_pY = pY;
	m_pZ = pZ;
	m_pG = pG;
	m_nPeriod = period;
	m_fStep = step;
	m_pStepTable = pStepTable;

	if (m_pX == NULL || m_pY == NULL || m_pZ == NULL || m_pG == NULL)
		m_nCount = 0;

	m_sTitle = title;
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphDigitalData::OnDestroy
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�E�B���h�E�j������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphDigitalData::OnDestroy()
{
	CDialogEx::OnDestroy();

	EndDialog(IDCANCEL);
}


//*****************************************************************************************************
//  1. �֐���
//		CGraphDigitalData::OnLvnGetdispinfoDataList
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		LVN_GETDISPINFO���b�Z�[�W�n���h��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		NMHDR		*pNMHDR		[I] �\�����
//		LRESULT		*pResult	[O] ���ʏ��
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphDigitalData::OnLvnGetdispinfoDataList(NMHDR *pNMHDR, LRESULT *pResult)
{
	NMLVDISPINFO *pDispInfo = reinterpret_cast<NMLVDISPINFO*>(pNMHDR);

	double fFreq;
	int nFreqDigit;
	CString sFormat;

	if (pDispInfo->item.mask & LVIF_TEXT) {
		CString str;
		switch(pDispInfo->item.iSubItem) {
		case 0:
			str.Format("%d", pDispInfo->item.iItem + 1);
			lstrcpy(pDispInfo->item.pszText, (LPCSTR)str);
			break;
		case 1:
			nFreqDigit = 3;
			switch(m_nMode) {
			case 0:
				fFreq = m_fStep * pDispInfo->item.iItem;
				break;
			case 1:
				fFreq = m_pStepTable[pDispInfo->item.iItem * 2 + 1];

				if (fFreq > 0 && fFreq < 1e-2)
					nFreqDigit = 1 - (int)floor(log10(fFreq));
				break;
			}

			sFormat.Format("%%.%df", nFreqDigit);
			str.Format(sFormat, fFreq);
			lstrcpy(pDispInfo->item.pszText, (LPCSTR)str);
			break;
		case 2:
			if (m_pX != NULL) {
				str.Format("%e", m_pX[pDispInfo->item.iItem]);
				lstrcpy(pDispInfo->item.pszText, (LPCSTR)str);
			}
			break;
		case 3:
			if (m_pY != NULL) {
				str.Format("%e", m_pY[pDispInfo->item.iItem]);
				lstrcpy(pDispInfo->item.pszText, (LPCSTR)str);
			}
			break;
		case 4:
			if (m_pZ != NULL) {
				str.Format("%e", m_pZ[pDispInfo->item.iItem]);
				lstrcpy(pDispInfo->item.pszText, (LPCSTR)str);
			}
			break;
		case 5:
			if (m_pG != NULL) {
				str.Format("%e", m_pG[pDispInfo->item.iItem]);
				lstrcpy(pDispInfo->item.pszText, (LPCSTR)str);
			}
			break;
		}
	}
	*pResult = 0;
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphDigitalData::OnInitDialog
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�_�C�A���O����������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL		TRUE
//*****************************************************************************************************
BOOL CGraphDigitalData::OnInitDialog()
{
	CDialogEx::OnInitDialog();

	// �s�S�̂�I���ł���悤�ɂ���
	m_List.SetExtendedStyle(LVS_EX_FULLROWSELECT);

	// ���X�g�w�b�_�ݒ�
	SetListHeader();

	// �f�[�^������ݒ�
	m_List.SetItemCount(m_nCount);

	SetWindowText(m_sTitle);

	// �R���g���[���̈ʒu�𒲐�
	SetCtlPosition(IDC_DATA_LIST, 0, 0, 1, 1);
	SetCtlPosition(IDC_BUTTON_CSV, 1, 1, 1, 1);
	SetCtlPosition(IDCANCEL, 1, 1, 1, 1);

	// �c�[���`�b�v�\���ݒ�
	CGeneral::SetToolTip(m_tooltip, s_aToolTip, this);

	return TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphDigitalData::SetListHeader
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���X�g�w�b�_�ݒ菈��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphDigitalData::SetListHeader()
{
	struct LIxxxOLUMN {
		char *pText;
		int fmt;
		int width;
	} tColumnData[][6] = {
		{
			// �X�y�N�g���\���p
			{"No", LVCFMT_LEFT, 70},
			{"Freq(Hz)", LVCFMT_LEFT, 70},
			{"X-PSD(G^2/Hz)", LVCFMT_LEFT, 120},
			{"Y-PSD(G^2/Hz)", LVCFMT_LEFT, 120},
			{"Z-PSD(G^2/Hz)", LVCFMT_LEFT, 120},
			{"G-PSD(G^2/Hz)", LVCFMT_LEFT, 120}
		}, {
			// 1/3�I�N�^�[�u�\���p
			{"No", LVCFMT_LEFT, 70},
			{"Freq(Hz)", LVCFMT_LEFT, 70},
			{"X-Acceleration(G)", LVCFMT_LEFT, 120},
			{"Y-Acceleration(G)", LVCFMT_LEFT, 120},
			{"Z-Acceleration(G)", LVCFMT_LEFT, 120},
			{"G-Acceleration(G)", LVCFMT_LEFT, 120}
		}
	}, *pColumn;
	int i;
	LV_COLUMN lvc;

	// �J�����f�[�^
	pColumn = tColumnData[m_nMode];

	// �J������ݒ�
	for (i = 0; i < 6; i++) {
		lvc.mask = LVCF_FMT | LVCF_WIDTH | LVCF_TEXT;
		lvc.fmt = pColumn->fmt;
		lvc.pszText = pColumn->pText;
		lvc.cx = pColumn->width;
		m_List.InsertColumn(i, &lvc);
		pColumn++;
	}
}


//*****************************************************************************************************
//  1. �֐���
//		CGraphDigitalData::OnBnClickedButtonCsv
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		CSV�{�^������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphDigitalData::OnBnClickedButtonCsv()
{
	if (m_nCount == 0)
		return;

#if 0
	if (m_Period > 60 * 60 + 1) {
		CGeneral::Alert("���Ԕ͈́F�P���ԕ��܂łɂ��Ă��������B");
		return;
	}
#endif

	// �t�@�C���I���_�C�A���O���J��
	CFileDialog cFDlg(FALSE,
						"csv",
						"*.csv",
						 OFN_HIDEREADONLY | OFN_OVERWRITEPROMPT ,
						"CSV(*.csv)|*.csv|�S�Ẵt�@�C�� (*.*)|*.*||",
						this
						);
	// �L�����Z���Ȃ�Ή������Ȃ�
	if (cFDlg.DoModal() == IDCANCEL) {
		return;
	}

	m_sCsvFName = cFDlg.GetPathName();
	CProgressBar cProgressBar;
	// �v���O���X�o�[��\�����A�ʃX���b�h�Ńt�@�C����ǂݍ���
	cProgressBar.ExecProcess("�t�@�C���������ݒ�", 10, this, WriteFileThread);
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphDigitalData::WriteFileThread
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�t�@�C���������݃X���b�h�̓����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		LPVOID			pParam			[I] CDataFile�N���X�ւ̃|�C���^
//		CProgressBar	*pProgressBar	[I] �v���O���X�o�[�N���X�ւ̃|�C���^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL	TRUE�F����@FALSE�F�G���[
//*****************************************************************************************************
BOOL CGraphDigitalData::WriteFileThread(LPVOID pParam, CProgressBar *pProgressBar)
{
	return ((CGraphDigitalData *)pParam)->WriteFileThread2(pProgressBar);
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphDigitalData::WriteFileThread2
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�t�@�C���������݃X���b�h�̖{��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CProgressBar	*pProgressBar	[I] �v���O���X�o�[�N���X�ւ̃|�C���^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL	TRUE�F����@FALSE�F�G���[
//*****************************************************************************************************
BOOL CGraphDigitalData::WriteFileThread2(CProgressBar *pProgressBar)
{
	CFile cWFile;
	int	i;
	CString str;
	double fFreq;
	int nFreqDigit;
	CString sFormat;

	// �������݃t�@�C���I�[�v��
	if (!cWFile.Open(m_sCsvFName, CFile::modeCreate | CFile::modeWrite | CFile::shareDenyWrite)) {
		CGeneral::Alert("�������݃t�@�C���������܂���B");
		return FALSE;
	}

	{
		CWaitCursor	cursor;
		// �f�[�^������0�łȂ���΁C�w�b�_��������
		if (m_nCount != 0) {
			str = gTitle[m_nMode];
			cWFile.Write(str, str.GetLength());
		}
		// �f�[�^�������[�v
		for (i = 0; i < m_nCount; i++) {
			nFreqDigit = 3;
			switch(m_nMode) {
			case 0:
				fFreq = m_fStep * i;
				break;
			case 1:
				fFreq = m_pStepTable[i * 2 + 1];

				if (fFreq > 0 && fFreq < 1e-2)
					nFreqDigit = 1 - (int)floor(log10(fFreq));
				break;
			}
			sFormat.Format("%%d,%%.%df,%%e,%%e,%%e,%%e\n", nFreqDigit);
			str.Format(sFormat
					, i + 1
					, fFreq
					, m_pX[i]
					, m_pY[i]
					, m_pZ[i]
					, m_pG[i]);
			cWFile.Write(str, str.GetLength());

			if ((i % 100) == 0) {
				// �v���O���X�o�[���X�V
				if (!pProgressBar->SetProgress((i + 1) * 10 / m_nCount)) {
					// �f�[�^�t�@�C�����N���[�Y
					cWFile.Close();
					return FALSE;
				}
			}
		}
		// �f�[�^�t�@�C�����N���[�Y
		cWFile.Close();
	}
	AfxMessageBox("CSV�t�@�C���ւ̏o�͂��������܂����B", MB_ICONINFORMATION|MB_OK);
	return TRUE;
}


//*****************************************************************************************************
//  1. �֐���
//		CGraphDigitalData::PreTranslateMessage
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�E�B���h�E���b�Z�[�W����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		MSG*	pMsg			[I] MSG�\���̂ւ̃|�C���^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL	�e�N���X�̕Ԓl�����̂܂ܕԂ�
//*****************************************************************************************************
BOOL CGraphDigitalData::PreTranslateMessage(MSG* pMsg)
{
	// �c�[���`�b�v��\�����邽�߂̏���
	switch(pMsg->message){
	case WM_LBUTTONDOWN:            
	case WM_LBUTTONUP:              
	case WM_MOUSEMOVE:
		if (m_tooltip.m_hWnd)
			m_tooltip.RelayEvent(pMsg);
		break;
	}

	return CDialogEx::PreTranslateMessage(pMsg);
}
