//*****************************************************************************************************
//  1. �t�@�C����
//		CGraphDigitalDataRowCol.cpp
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
#include "GraphDigitalDataRowCol.h"
#include "General.h"

static const char *gTitle = "No,Time";

// CGraphDigitalDataRowCol �_�C�A���O

IMPLEMENT_DYNAMIC(CGraphDigitalDataRowCol, CDialogEx)

CGraphDigitalDataRowCol::CGraphDigitalDataRowCol(CWnd* pParent /*=NULL*/)
	: CDialogEx(CGraphDigitalDataRowCol::IDD, pParent)
	, m_pData(NULL)
	, m_nRow(0)
	, m_nCol(0)
	, m_sTitle(_T(""))
	, m_nPeriod(0)
{

}

CGraphDigitalDataRowCol::~CGraphDigitalDataRowCol()
{
}

void CGraphDigitalDataRowCol::DoDataExchange(CDataExchange* pDX)
{
	CDialogEx::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_DATA_LIST_ROW, m_ListRow);
	DDX_Control(pDX, IDC_DATA_LIST_COL, m_Lixxxol);
}


BEGIN_MESSAGE_MAP(CGraphDigitalDataRowCol, CDialogEx)
	ON_BN_CLICKED(IDC_BUTTON_CSV, &CGraphDigitalDataRowCol::OnBnClickedButtonCsv)
	ON_NOTIFY(LVN_GETDISPINFO, IDC_DATA_LIST_COL, &CGraphDigitalDataRowCol::OnLvnGetdispinfoDataLixxxol)
	ON_NOTIFY(LVN_GETDISPINFO, IDC_DATA_LIST_ROW, &CGraphDigitalDataRowCol::OnLvnGetdispinfoDataListRow)
	ON_NOTIFY(LVN_ITEMCHANGED, IDC_DATA_LIST_ROW, &CGraphDigitalDataRowCol::OnLvnItemchangedDataListRow)
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
//		CGraphDigitalDataRowCol::SetDataInfo
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���X�g�\������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		double			*pDat			[I] �f�[�^�̃|�C���^
//		int				nRow			[I]	�s��
//		int				nCol			[I]	��
//		LPCSTR			title			[I]	�^�C�g��
//		CDataDateTime	tm				[I] �v���J�n����
//		double			timeStep		[I] ���ԃX�e�b�v
//		double			freqStep		[I] ���g���X�e�b�v
//		long			period			[I] �o�͔͈�
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphDigitalDataRowCol::SetDataInfo(const double *pDat, int nRow, int nCol, LPCSTR title, CDataDateTime tm, double timeStep, double freqStep, long period)
{
	m_pData = pDat;
	m_nRow = nRow;
	m_nCol = nCol;
	m_sTitle = title;
	m_StartTime = tm;
	m_fStep = timeStep;
	m_nPeriod = period;
	m_fFreqStep = freqStep;

	if (m_pData == NULL) {
		m_nRow = 0;
		m_nCol = 0;
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphDigitalDataRowCol::OnInitDialog
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
BOOL CGraphDigitalDataRowCol::OnInitDialog()
{
	CDialogEx::OnInitDialog();

	// �s�S�̂�I���ł���悤�ɂ���
	m_ListRow.SetExtendedStyle(LVS_EX_FULLROWSELECT);
	m_Lixxxol.SetExtendedStyle(LVS_EX_FULLROWSELECT);

	// ���X�g�w�b�_�ݒ�
	SetListHeader();

	// �f�[�^������ݒ�
	m_ListRow.SetItemCount(m_nRow);
	m_Lixxxol.SetItemCount(m_nCol);
	m_ListRow.SetItemState(0, LVIS_SELECTED | LVIS_FOCUSED, LVIS_SELECTED | LVIS_FOCUSED);

	SetWindowText(m_sTitle);

	// �R���g���[���̈ʒu�𒲐�
	SetCtlPosition(IDC_STATIC_ROW, 0, 0, 0.5, 0);
	SetCtlPosition(IDC_STATIC_COL, 0.5, 0, 0.5, 0);
	SetCtlPosition(IDC_DATA_LIST_ROW, 0, 0, 0.5, 1);
	SetCtlPosition(IDC_DATA_LIST_COL, 0.5, 0, 1, 1);
	SetCtlPosition(IDC_BUTTON_CSV, 1, 1, 1, 1);
	SetCtlPosition(IDCANCEL, 1, 1, 1, 1);

	// �c�[���`�b�v�\���ݒ�
	CGeneral::SetToolTip(m_tooltip, s_aToolTip, this);

	return TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphDigitalDataRowCol::SetListHeader
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
void CGraphDigitalDataRowCol::SetListHeader()
{
	struct LIxxxOLUMN {
		char *pText;
		int fmt;
		int width;
	} tColumnData[] = {
		{"No", LVCFMT_LEFT, 70},
		{"Time", LVCFMT_LEFT, 170}
	}, tColumnData2[] = {
		{"No", LVCFMT_LEFT, 70},
		{"Freq(Hz)", LVCFMT_LEFT, 70},
		{"PSD(G^2/Hz)", LVCFMT_LEFT, 120}
	}, *pColumn;
	int i;
	int nColumn;
	LV_COLUMN lvc;

	// �J�����f�[�^
	pColumn = tColumnData;
	nColumn  = sizeof(tColumnData) / sizeof(LIxxxOLUMN);

	// �J������ݒ�
	for (i = 0; i < nColumn; i++) {
		lvc.mask = LVCF_FMT | LVCF_WIDTH | LVCF_TEXT;
		lvc.fmt = pColumn->fmt;
		lvc.pszText = pColumn->pText;
		lvc.cx = pColumn->width;
		m_ListRow.InsertColumn(i, &lvc);
		pColumn++;
	}

	// �J�����f�[�^
	pColumn = tColumnData2;
	nColumn  = sizeof(tColumnData2) / sizeof(LIxxxOLUMN);

	// �J������ݒ�
	for (i = 0; i < nColumn; i++) {
		lvc.mask = LVCF_FMT | LVCF_WIDTH | LVCF_TEXT;
		lvc.fmt = pColumn->fmt;
		lvc.pszText = pColumn->pText;
		lvc.cx = pColumn->width;
		m_Lixxxol.InsertColumn(i, &lvc);
		pColumn++;
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphDigitalDataRowCol::OnBnClickedButtonCsv
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
void CGraphDigitalDataRowCol::OnBnClickedButtonCsv()
{
	if (m_nRow == 0 || m_nCol == 0)
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
//		CGraphDigitalDataRowCol::WriteFileThread
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
BOOL CGraphDigitalDataRowCol::WriteFileThread(LPVOID pParam, CProgressBar *pProgressBar)
{
	return ((CGraphDigitalDataRowCol *)pParam)->WriteFileThread2(pProgressBar);
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphDigitalDataRowCol::WriteFileThread2
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
BOOL CGraphDigitalDataRowCol::WriteFileThread2(CProgressBar *pProgressBar)
{
	CFile cWFile;
	int	i, j;
	CString str, buf;

	// �������݃t�@�C���I�[�v��
	if (!cWFile.Open(m_sCsvFName, CFile::modeCreate | CFile::modeWrite | CFile::shareDenyWrite)) {
		CGeneral::Alert("�������݃t�@�C���������܂���B");
		return FALSE;
	}

	{
		CWaitCursor	cursor;
// update 2008/03/18 by hama�@��
		CDataDateTime	tm;
		// �^�C�g���s�ҏW
		str = gTitle;
		for (i = 0; i < m_nCol; i++) {
			buf.Format(",%.3fHz", i * m_fFreqStep);
			str += buf;
		}
		str += "\n";
		cWFile.Write(str, str.GetLength());
		tm = m_StartTime;
// update 2008/03/18 by hama�@��

		// �f�[�^�������[�v
		for (i = 0; i < m_nRow; i++) {
			str = "";
			buf.Format("%d", i+1);
			str += buf;
// update 2008/03/18 by hama�@��
//			buf.Format(",%.3f", i * m_FreqStep);
//			str += buf;
			tm += (long)(m_fStep * i);
			buf.Format(",%s", tm.GetStr());
			str += buf;
			for (j = 0; j < m_nCol; j++) {
//				buf.Format(",%e", m_pData[m_nCol * i + (m_nCol - j - 1)]);
				buf.Format(",%e", m_pData[m_nCol * i + j]);
// update 2008/03/18 by hama�@��
				str += buf;
			}
			str += "\n";
			cWFile.Write(str, str.GetLength());

			// �v���O���X�o�[���X�V
			if (!pProgressBar->SetProgress((i + 1) * 10 / m_nRow)) {
				// �f�[�^�t�@�C�����N���[�Y
				cWFile.Close();
				return FALSE;
			}

		}
		// �f�[�^�t�@�C�����N���[�Y
		cWFile.Close();
	}

	AfxMessageBox("CSV�t�@�C���ւ̏o�͂��������܂����B",MB_ICONINFORMATION|MB_OK);

	return TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphDigitalDataRowCol::OnLvnGetdispinfoDataLixxxol
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
void CGraphDigitalDataRowCol::OnLvnGetdispinfoDataLixxxol(NMHDR *pNMHDR, LRESULT *pResult)
{
	NMLVDISPINFO *pDispInfo = reinterpret_cast<NMLVDISPINFO*>(pNMHDR);

 	if (pDispInfo->item.mask & LVIF_TEXT) {
		int sel = m_ListRow.GetNextItem(-1, LVNI_ALL | LVNI_SELECTED);
		if (sel != -1) {
			CString str;
			switch(pDispInfo->item.iSubItem) {
			case 0:
				str.Format("%d_%d", sel + 1, pDispInfo->item.iItem + 1);
				lstrcpy(pDispInfo->item.pszText, (LPCSTR)str);
				break;
			case 1:
				str.Format("%.3f", pDispInfo->item.iItem * m_fFreqStep);
				lstrcpy(pDispInfo->item.pszText, (LPCSTR)str);
				break;
			case 2:
				if (m_pData != NULL) {
					str.Format("%e", m_pData[sel * m_nCol + pDispInfo->item.iItem]);
					lstrcpy(pDispInfo->item.pszText, (LPCSTR)str);
				}
				break;
			}
		}
	}

	*pResult = 0;
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphDigitalDataRowCol::OnLvnGetdispinfoDataListRow
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
void CGraphDigitalDataRowCol::OnLvnGetdispinfoDataListRow(NMHDR *pNMHDR, LRESULT *pResult)
{
	NMLVDISPINFO *pDispInfo = reinterpret_cast<NMLVDISPINFO*>(pNMHDR);

 	if (pDispInfo->item.mask & LVIF_TEXT) {
		CString str;
		CDataDateTime	tm;
		switch(pDispInfo->item.iSubItem) {
		case 0:
			str.Format("%d", pDispInfo->item.iItem + 1);
			lstrcpy(pDispInfo->item.pszText, (LPCSTR)str);
			break;
		case 1:
			tm = m_StartTime;
			tm += (long)(m_fStep * pDispInfo->item.iItem);
			str.Format("%s", tm.GetStr());
			lstrcpy(pDispInfo->item.pszText, (LPCSTR)str);
			break;
		}
	}

	*pResult = 0;
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphDigitalDataRowCol::OnLvnItemchangedDataListRow
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		LPNMLISTVIEW���b�Z�[�W�n���h��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		NMHDR		*pNMHDR		[I] �\�����
//		LRESULT		*pResult	[O] ���ʏ��
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphDigitalDataRowCol::OnLvnItemchangedDataListRow(NMHDR *pNMHDR, LRESULT *pResult)
{
	LPNMLISTVIEW pNMLV = reinterpret_cast<LPNMLISTVIEW>(pNMHDR);

	// �s���X�g�ɕω���������
	if (pNMLV->uNewState != 0) {
		// �񃊃X�g���ĕ`��
		m_Lixxxol.DeleteAllItems();
		m_Lixxxol.SetItemCount(m_nCol);
	}

	*pResult = 0;
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphDigitalDataRowCol::OnDestroy
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
void CGraphDigitalDataRowCol::OnDestroy()
{
	CDialogEx::OnDestroy();

	EndDialog(IDCANCEL);
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphDigitalDataRowCol::PreTranslateMessage
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
BOOL CGraphDigitalDataRowCol::PreTranslateMessage(MSG* pMsg)
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
