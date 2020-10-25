//*****************************************************************************************************
//  1. �t�@�C����
//		DigitalDataDlg.cpp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�f�W�^�����l�\���_�C�A���O�N���X�̎���
//----------------------------------------------------------------------------------------------------
//  3. ���l
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2007.08.09 S.Aizawa �V�K�쐬
//*****************************************************************************************************

#include "stdafx.h"
#include "MMA_G.h"
#include "DigitalDataDlg.h"
#include "TimeRangeDlg.h"
#include "General.h"

#define PAGE_SIZE	1000		// 1�y�[�W����̃f�[�^����

// CDigitalDataDlg �_�C�A���O

IMPLEMENT_DYNAMIC(CDigitalDataDlg, CDialogEx)

//*****************************************************************************************************
//  1. �֐���
//		CDigitalDataDlg::CDigitalDataDlg
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
CDigitalDataDlg::CDigitalDataDlg(CWnd *pParent)
	: CDialogEx(CDigitalDataDlg::IDD, pParent)
{
}

void CDigitalDataDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialogEx::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_LIST_DATA, m_cListData);
	DDX_Control(pDX, IDC_EDIT_PAGE_NO, m_cEditPageNo);
	DDX_Control(pDX, IDC_BUTTON_PREV_PAGE, m_cButtonPrevPage);
	DDX_Control(pDX, IDC_BUTTON_NEXT_PAGE, m_cButtonNextPage);
	DDX_Control(pDX, IDC_COMBO_SAMPLE_RATE, m_cComboSampleRate);
}


BEGIN_MESSAGE_MAP(CDigitalDataDlg, CDialogEx)
	ON_BN_CLICKED(IDC_BUTTON_PREV_PAGE, &CDigitalDataDlg::OnBnClickedButtonPrevPage)
	ON_BN_CLICKED(IDC_BUTTON_NEXT_PAGE, &CDigitalDataDlg::OnBnClickedButtonNextPage)
	ON_CBN_SELCHANGE(IDC_COMBO_SAMPLE_RATE, &CDigitalDataDlg::OnCbnSelchangeComboSampleRate)
	ON_BN_CLICKED(IDC_BUTTON_PRINT, &CDigitalDataDlg::OnBnClickedButtonPrint)
	ON_BN_CLICKED(IDC_BUTTON_SELECT_ALL, &CDigitalDataDlg::OnBnClickedButtonSelectAll)
	ON_WM_DESTROY()
END_MESSAGE_MAP()

// �c�[���`�b�v�\���f�[�^
static const SToolTipData s_aToolTip[] = {
	{IDC_BUTTON_PREV_PAGE, "�O�y�[�W�̃f�[�^��\�����܂��B"},
	{IDC_BUTTON_NEXT_PAGE, "���y�[�W�̃f�[�^��\�����܂��B"},
	{IDC_BUTTON_SELECT_ALL, "�S�f�[�^��I�����Ĉ���ł���悤�ɂ��܂��B"},
	{IDC_BUTTON_PRINT, "�I�����Ă���f�[�^��������܂��B"},
	{IDCANCEL, "���̃_�C�A���O����܂��B"},
	{0, NULL}
};

//*****************************************************************************************************
//  1. �֐���
//		CDigitalDataDlg::DispList
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���X�g�\������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		LPCTSTR		pFilePath		[I] ���t�@�C���̃p�X��
//		int			nDataType		[I] �f�[�^�̎�ށiMMA or MME�j
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CDigitalDataDlg::DispList(LPCTSTR pFilePath, int nDataType)
{
	// �f�[�^���(MMA or MME)�ۑ�
	m_cDataFile.m_nDataType = nDataType;

	// ���t�@�C���ǂݍ���
	if (!m_cDataFile.ReadInfoFile(pFilePath)) {
		delete this;
		return;
	}

	// ���Ԕ͈͐ݒ�_�C�A���O�\��
	CTimeRangeDlg cTimeRangeDlg;
	cTimeRangeDlg.m_dStarttime = m_cDataFile.m_dStarttime;
	cTimeRangeDlg.m_nDataSec = (int)m_cDataFile.m_fTotalSecond;
	cTimeRangeDlg.m_sKind = "���l�f�[�^";
	if (cTimeRangeDlg.DoModal() == IDCANCEL) {
		delete this;
		return;
	}

	// �p�����[�^��ۑ�
	m_sFilePath = pFilePath;
	m_nBeginTime = cTimeRangeDlg.m_nDispBegin;
	m_nEndTime = cTimeRangeDlg.m_nDispEnd;
	m_cDataFile.m_dInStarttime = cTimeRangeDlg.m_dInStarttime;
	m_cDataFile.m_dInEndtime = cTimeRangeDlg.m_dInEndtime;

	// �f�[�^�ǂݍ���
	if (!m_cDataFile.ReadDataFile(m_nBeginTime, m_nEndTime, TRUE, TRUE, FALSE)) {
		delete this;
		return;
	}

	// �_�C�A���O�\��
	DoModeless();
}

//*****************************************************************************************************
//  1. �֐���
//		CDigitalDataDlg::OnInitDialog
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
BOOL CDigitalDataDlg::OnInitDialog()
{
	CDialogEx::OnInitDialog();

	// �s�S�̂�I���ł���悤�ɂ���
	m_cListData.SetExtendedStyle(LVS_EX_FULLROWSELECT);

	// ���X�g�w�b�_�ݒ�
	SetListHeader();

	// �f�[�^�\��
	DispData(1);

	// �T���v�����O���g���̃R���{�{�b�N�X��ݒ�
	CGeneral::SetSampleRateList(m_cComboSampleRate, m_cDataFile.m_fSampleRate);

	// �R���g���[���̈ʒu�𒲐�
	SetCtlPosition(IDC_LIST_DATA, 0, 0, 1, 1);
	SetCtlPosition(IDC_STATIC_PAGE_CHANGE, 0, 1, 0, 1);
	SetCtlPosition(IDC_BUTTON_PREV_PAGE, 0, 1, 0, 1);
	SetCtlPosition(IDC_BUTTON_NEXT_PAGE, 0, 1, 0, 1);
	SetCtlPosition(IDC_STATIC_SAMPLE_RATE, 0, 1, 0, 1);
	SetCtlPosition(IDC_COMBO_SAMPLE_RATE, 0, 1, 0, 1);
	SetCtlPosition(IDC_EDIT_PAGE_NO, 0, 1, 0, 1);
	SetCtlPosition(IDC_STATIC_PRINT, 0, 1, 0, 1);
	SetCtlPosition(IDC_BUTTON_SELECT_ALL, 0, 1, 0, 1);
	SetCtlPosition(IDC_BUTTON_PRINT, 0, 1, 0, 1);
	SetCtlPosition(IDCANCEL, 1, 1, 1, 1);

	// �c�[���`�b�v�\���ݒ�
	CGeneral::SetToolTip(m_tooltip, s_aToolTip, this);

	return TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CDigitalDataDlg::SetListHeader
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
void CDigitalDataDlg::SetListHeader()
{
	struct LIxxxOLUMN {
		char *pText;
		int fmt;
		int width;
	} tColumnDataMma[] = {
		{"No", LVCFMT_LEFT, 70},
// 077FD050-GYM961-0_002 2007/9/6 modified by yG ->
//		{"Time", LVCFMT_LEFT, 150},
		{"Time(YYYY/MM/DD hh:mm:ss.tttt)", LVCFMT_LEFT, 150},
// 077FD050-GYM961-0_002 2007/9/6 modified by yG <-
		{"X-Axis(��g)", LVCFMT_LEFT, 90},
		{"Y-Axis(��g)", LVCFMT_LEFT, 90},
		{"Z-Axis(��g)", LVCFMT_LEFT, 90},
		{"D-Temp(��)", LVCFMT_LEFT, 75},
		{"X-Temp(��)", LVCFMT_LEFT, 75},
		{"Y-Temp(��)", LVCFMT_LEFT, 75},
		{"Z-Temp(��)", LVCFMT_LEFT, 75}
	}, tColumnDataMme[] = {
		{"No", LVCFMT_LEFT, 70},
		{"Time(YYYY/MM/DD hh:mm:ss.tttt)", LVCFMT_LEFT, 150},
		{"X-Axis(��g)", LVCFMT_LEFT, 90},
		{"Y-Axis(��g)", LVCFMT_LEFT, 90},
		{"Z-Axis(��g)", LVCFMT_LEFT, 90},
	}, tColumnDataAsu[] = {
		{"No", LVCFMT_LEFT, 70},
		{"Time(YYYY/MM/DD hh:mm:ss.tttt)", LVCFMT_LEFT, 150},
		{"X-Axis(��g)", LVCFMT_LEFT, 90},
		{"Y-Axis(��g)", LVCFMT_LEFT, 90},
		{"Z-Axis(��g)", LVCFMT_LEFT, 90},
	}, *pColumn;
	int i;
	int nColumn;
	LV_COLUMN lvc;

	// �J�����f�[�^
	switch (m_cDataFile.m_nDataType) {
	case DATA_TYPE_MMA:
		pColumn = tColumnDataMma;
		nColumn  = sizeof(tColumnDataMma) / sizeof(LIxxxOLUMN);
		break;
	case DATA_TYPE_MME:
		pColumn = tColumnDataMme;
		nColumn  = sizeof(tColumnDataMme) / sizeof(LIxxxOLUMN);
		break;
	case DATA_TYPE_ASU:
		pColumn = tColumnDataMme;
		nColumn  = sizeof(tColumnDataMme) / sizeof(LIxxxOLUMN);
		break;
	default:
		return;
	}

	// �J������ݒ�
	for (i = 0; i < nColumn; i++) {
		lvc.mask = LVCF_FMT | LVCF_WIDTH | LVCF_TEXT;
		lvc.fmt = pColumn->fmt;
		lvc.pszText = pColumn->pText;
		lvc.cx = pColumn->width;
		m_cListData.InsertColumn(i, &lvc);
		pColumn++;
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CDigitalDataDlg::DispData
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�f�[�^�\������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		int		nPageNo		[I] �\������y�[�W�ԍ�
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CDigitalDataDlg::DispData(int nPageNo)
{
	CString sText;

	// �y�[�W�ԍ��ۑ�
	m_nPageNo = nPageNo;

	// �y�[�W���擾
	m_nPageNum = (m_cDataFile.m_nDataSize / m_cDataFile.m_nDownRate + PAGE_SIZE - 1) / PAGE_SIZE;

	// �ĕ`��֎~
	m_cListData.SetRedraw(FALSE);

	// �S�Ẵf�[�^������
	m_cListData.DeleteAllItems();

	// �f�[�^�̃C���f�b�N�X�擾
	int nIndex = (nPageNo - 1) * PAGE_SIZE * m_cDataFile.m_nDownRate;

	// ���X�g�R���g���[���Ƀf�[�^��\��
	for (int i = 0; i < PAGE_SIZE && nIndex < m_cDataFile.m_nDataSize; i++, nIndex += m_cDataFile.m_nDownRate) {
		sText.Format("%d", nIndex / m_cDataFile.m_nDownRate + 1);
		m_cListData.InsertItem(i, sText);

		CDataDateTime Begin;
		Begin.SetTime(m_cDataFile.m_dInStarttime.m_YY
			, m_cDataFile.m_dInStarttime.m_MM
			, m_cDataFile.m_dInStarttime.m_DD
			, (int)((m_nBeginTime + nIndex / m_cDataFile.m_fSampleRate) * 1000));
		sText = Begin.GetStrYMD();
		sText += CGeneral::FormatTime(m_nBeginTime + nIndex / m_cDataFile.m_fSampleRate, 4);
		m_cListData.SetItemText(i, 1, sText);

		sText.Format("%f", m_cDataFile.m_pDataX[nIndex]);
		m_cListData.SetItemText(i, 2, sText);

		sText.Format("%f", m_cDataFile.m_pDataY[nIndex]);
		m_cListData.SetItemText(i, 3, sText);

		sText.Format("%f", m_cDataFile.m_pDataZ[nIndex]);
		m_cListData.SetItemText(i, 4, sText);

		if (m_cDataFile.m_nDataType == DATA_TYPE_MMA) {
			sText.Format("%.2f", m_cDataFile.m_pTempDACM[nIndex]);
			m_cListData.SetItemText(i, 5, sText);

			sText.Format("%.2f", m_cDataFile.m_pTempX[nIndex]);
			m_cListData.SetItemText(i, 6, sText);

			sText.Format("%.2f", m_cDataFile.m_pTempY[nIndex]);
			m_cListData.SetItemText(i, 7, sText);

			sText.Format("%.2f", m_cDataFile.m_pTempZ[nIndex]);
			m_cListData.SetItemText(i, 8, sText);
		}
	}

	// �ĕ`�拖��
	m_cListData.SetRedraw(TRUE);

	// �y�[�W�ԍ��\��
	m_cEditPageNo.Format("%d / %d", nPageNo, m_nPageNum);

	// �O�Ł^���Ń{�^���L����
	m_cButtonPrevPage.EnableWindow(m_nPageNo > 1);
	m_cButtonNextPage.EnableWindow(m_nPageNo < m_nPageNum);
}

//*****************************************************************************************************
//  1. �֐���
//		CDigitalDataDlg::OnBnClickedButtonPrevPage
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�O�Ń{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CDigitalDataDlg::OnBnClickedButtonPrevPage()
{
	if (m_nPageNo > 1)
		DispData(m_nPageNo - 1);
}

//*****************************************************************************************************
//  1. �֐���
//		CDigitalDataDlg::OnBnClickedButtonNextPage
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���Ń{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CDigitalDataDlg::OnBnClickedButtonNextPage()
{
	if (m_nPageNo < m_nPageNum)
		DispData(m_nPageNo + 1);
}

//*****************************************************************************************************
//  1. �֐���
//		CDigitalDataDlg::OnCbnSelchangeComboSampleRate
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�T���v�����g���R���{�{�b�N�X�I��ύX����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CDigitalDataDlg::OnCbnSelchangeComboSampleRate()
{
	int nIndex = m_cComboSampleRate.GetCurSel();
	if (nIndex >= 0) {
		// �Ԉ������[�g�擾
		m_cDataFile.m_nDownRate = (int)m_cComboSampleRate.GetItemData(nIndex);

		// �O���t�\�����X�V
		DispData(1);
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CDigitalDataDlg::OnBnClickedButtonPrint
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		����{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CDigitalDataDlg::OnBnClickedButtonPrint()
{
	int i;

	// �I������Ă���s�����邩�`�F�b�N
	int nItem = m_cListData.GetItemCount();
	for (i = 0; i < nItem; i++) {
		if (m_cListData.GetItemState(i, LVIS_SELECTED))
			break;
	}
	if (i == nItem) {
		CGeneral::Alert("�������f�[�^��I�����Ă��������B");
		return;
	}

	CString sTitle = "�f�W�^���f�[�^";
	CPrintDialog dlg(FALSE);

	// ����J�n�_�C�A���O�\��
	if(dlg.DoModal() == IDOK) {
		// ����pCDC�擾
		CDC	dc;
		dc.Attach(dlg.GetPrinterDC());

		// ����T�C�Y�擾
		int nWidth = dc.GetDeviceCaps(HORZRES);
		int nHeight = dc.GetDeviceCaps(VERTRES);

		// �}�[�W���ݒ�
		CRect rectView(0, 0, nWidth, nHeight);
		rectView.DeflateRect(nWidth / 25, nHeight / 15, nWidth / 25, nHeight / 20);

		// DOCINFO��p��
		DOCINFO	docinfo;
		memset(&docinfo, 0, sizeof(docinfo));
		docinfo.cbSize  = sizeof(docinfo);
		docinfo.lpszDocName = sTitle;

		// ����J�n
		dc.StartDoc(&docinfo);

		// ���X�g���
		PrintData(dc, rectView, sTitle, m_cListData);

		// ����I��
		dc.EndDoc();
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CDigitalDataDlg::PrintData
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���X�g�f�[�^�������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CDC			&dc				[I] �f�o�C�X�R���e�L�X�g
//		CRect		rectView		[I] ����͈�
//		LPCTSTR		pTitle			[I] �^�C�g��
//		CLixxxtrl	&cLixxxtrl		[I] ������郊�X�g�R���g���[��
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CDigitalDataDlg::PrintData(CDC &dc, CRect rectView, LPCTSTR pTitle, CLixxxtrl &cLixxxtrl)
{
	int i, j, k;
	int x, y;
	char textBuf[32];

	// �t�H���g�쐬
	CFont fontTitle;
	fontTitle.CreatePointFont(80, "MS UI Gothic", &dc);

	// �t�H���g��I��
	CFont *pFontOrg = dc.SelectObject(&fontTitle);

	// �y�����쐬
	CPen penBlack(PS_SOLID, 2, RGB(0, 0, 0));

	// �y���A�u���V��I��
	CPen *pPenOrg = dc.SelectObject(&penBlack);
	dc.SelectStockObject(NULL_BRUSH);

	// �t�H���g�̃T�C�Y���擾
	TEXTMETRIC tm;
	dc.GetTextMetrics(&tm);

	// �����ԃX�y�[�X�ݒ�
	int nLeftSpace = tm.tmAveCharWidth / 2;
	int nTopSpace = tm.tmHeight / 4;
	int nBottomSpace = tm.tmHeight / 4;

	// �P�s�̍������擾
	int nLineHeight = tm.tmHeight + nTopSpace + nBottomSpace;

	// �I������Ă���s�����擾
	CUIntArray aSelectedIndex;
	int nItem = cLixxxtrl.GetItemCount();
	for (i = 0; i < nItem; i++) {
		if (cLixxxtrl.GetItemState(i, LVIS_SELECTED))
			aSelectedIndex.Add(i);
	}
	int nLineNum = (int)aSelectedIndex.GetCount();

	// �P�y�[�W�̍s�����擾
	int nPageLine = rectView.Height() / nLineHeight;

	// �y�[�W�����擾
	int nPageNum = (nLineNum + nPageLine - 1) / nPageLine;

	// �J���������擾
	CHeaderCtrl *pHeaderCtrl = cLixxxtrl.GetHeaderCtrl();
	int nColumnNum = pHeaderCtrl->GetItemCount();

	// �S�J�����̕��̍��v���擾
	HDITEM hditem;
	int nListWidth = 0;
	for (i = 0; i < nColumnNum; i++) {
		hditem.mask = HDI_WIDTH;
		pHeaderCtrl->GetItem(i, &hditem);
		nListWidth += hditem.cxy;
	}

	CString	sBegin, sEnd, sStart;
	CString	sPrTitle, sPrTitle2;

	CDataDateTime Start;
	Start.SetTime(m_cDataFile.m_dInStarttime.m_YY
		, m_cDataFile.m_dInStarttime.m_MM
		, m_cDataFile.m_dInStarttime.m_DD
		, (int)(m_cDataFile.m_fStartSecond * 1000));
	sStart = Start.GetStrYMDHMS();
	sBegin = cLixxxtrl.GetItemText(aSelectedIndex[0], 1);
	sEnd = cLixxxtrl.GetItemText(aSelectedIndex[nLineNum - 1], 1);
	sPrTitle.Format("Title:%s ����J�n����:%s ����͈�:%s-%s"
		, pTitle
		, sStart, sBegin, sEnd);

	switch (m_cDataFile.m_nDataType) {
	case DATA_TYPE_MMA:
		sPrTitle2.Format("SensorID:%d SampleRate:%.2f Cutoff:%d Gain:%d �ʒu:%s"
			, m_cDataFile.m_nSensorId
			, m_cDataFile.m_fSampleRate
			, m_cDataFile.m_nGain
			, m_cDataFile.m_nCutoff
			, m_cDataFile.m_SensorPos);
		break;
	case DATA_TYPE_MME:
		sPrTitle2.Format("SensorID:%d SampleRate:%g �ʒu:%s"
			, m_cDataFile.m_nSensorId
			, m_cDataFile.m_fSampleRate
			, m_cDataFile.m_SensorPos);
		break;
	case DATA_TYPE_ASU:
		sPrTitle2.Format("SensorID:%d SampleRate:%g �ʒu:%s"
			, m_cDataFile.m_nSensorId
			, m_cDataFile.m_fSampleRate
			, m_cDataFile.m_SensorPos);
		break;
	}

	// �y�[�W�̃��[�v
	int nIndex = 0;
	for (i = 0; i < nPageNum; i++) {
		y = rectView.top;

		// �y�[�W����J�n
		dc.StartPage();

		// �^�C�g�������
		PrintInfo(dc, rectView, sPrTitle, sPrTitle2);

		// �w�b�_�����
		x = rectView.left;
		for (j = 0; j < nColumnNum; j++) {
			hditem.mask = HDI_WIDTH | HDI_TEXT;
			hditem.pszText = textBuf;
			hditem.cchTextMax = sizeof(textBuf);
			pHeaderCtrl->GetItem(j, &hditem);

			dc.TextOut(x + nLeftSpace, y + nTopSpace, textBuf);

			x += hditem.cxy * rectView.Width() / nListWidth;
		}
		y += nLineHeight;

		// �s�̃��[�v
		for (j = 0; j < nPageLine && nIndex < nLineNum; j++) {
			x = rectView.left;

			// �J�����̃��[�v
			for (k = 0; k < nColumnNum; k++) {
				dc.TextOut(x + nLeftSpace, y + nTopSpace, cLixxxtrl.GetItemText(aSelectedIndex[nIndex], k));

				hditem.mask = HDI_WIDTH;
				pHeaderCtrl->GetItem(k, &hditem);
				x += hditem.cxy * rectView.Width() / nListWidth;
			}

			nIndex++;
			y += nLineHeight;
		}

		// �r�������
		dc.Rectangle(rectView.left, rectView.top, rectView.right, y);
		dc.MoveTo(rectView.left, rectView.top + nLineHeight);
		dc.LineTo(rectView.right, rectView.top + nLineHeight);
		x = rectView.left;
		for (j = 0; j < nColumnNum - 1; j++) {
			hditem.mask = HDI_WIDTH;
			pHeaderCtrl->GetItem(j, &hditem);
			x += hditem.cxy * rectView.Width() / nListWidth;

			dc.MoveTo(x, rectView.top);
			dc.LineTo(x, y);
		}

		// �y�[�W����I��
		dc.EndPage();
	}

	// �`��I�u�W�F�N�g�̑I��������
	dc.SelectObject(pFontOrg);
	dc.SelectObject(pPenOrg);
}

//*****************************************************************************************************
//  1. �֐���
//		CDigitalDataDlg::PrintInfo
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�^�C�g�����������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CDC			&dc				[I] �f�o�C�X�R���e�L�X�g
//		CRect		rectView		[I] ����͈�
//		LPCTSTR		pTitle			[I] �^�C�g��
//		LPCTSTR		pTitle2			[I] �^�C�g���Q
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CDigitalDataDlg::PrintInfo(CDC &dc, CRect rectView, LPCTSTR pTitle, LPCTSTR pTitle2)
{
	// �t�H���g�쐬
	CFont fontTitle;
	fontTitle.CreatePointFont(80, "MS UI Gothic", &dc);

	// �t�H���g��I��
	CFont *pFontOrg = dc.SelectObject(&fontTitle);

	// �^�C�g���̃T�C�Y���擾
	CSize sizeText = dc.GetOutputTextExtent(pTitle);

	// �^�C�g�������
	dc.TextOut(rectView.CenterPoint().x - sizeText.cx / 2, rectView.top - sizeText.cy * 4, pTitle);
	dc.TextOut(rectView.CenterPoint().x - sizeText.cx / 2, (int)(rectView.top - sizeText.cy * 2.5), pTitle2);

	// �t�H���g�̑I��������
	dc.SelectObject(pFontOrg);
}

//*****************************************************************************************************
//  1. �֐���
//		CDigitalDataDlg::OnBnClickedButtonSelectAll
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�S�I���{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CDigitalDataDlg::OnBnClickedButtonSelectAll()
{
	int nItem = m_cListData.GetItemCount();
	for (int i = 0; i < nItem; i++)
		m_cListData.SetItemState(i, LVIS_SELECTED, LVIS_SELECTED);

	m_cListData.SetFocus();
}

void CDigitalDataDlg::OnCancel()
{
	DestroyWindow();
}

//*****************************************************************************************************
//  1. �֐���
//		CDigitalDataDlg::PreTranslateMessage
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
BOOL CDigitalDataDlg::PreTranslateMessage(MSG* pMsg)
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
