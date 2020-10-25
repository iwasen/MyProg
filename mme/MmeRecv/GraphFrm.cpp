//*****************************************************************************************************
//  1. �t�@�C����
//		GraphFrm.cpp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�O���t�̃t���[���E�B���h�E�N���X�̎���
//----------------------------------------------------------------------------------------------------
//  3. ���l
//		����
//*****************************************************************************************************

#include "stdafx.h"
#include "MmeRecv.h"
#include "GraphFrm.h"
#include <math.h>

#define WM_READY_GRAPH	(WM_APP + 0)

#define SCROLL_MAX	2000

// CGraphFrm

IMPLEMENT_DYNCREATE(CGraphFrm, CFrameWnd)

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrm::CGraphFrm
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�R���X�g���N�^
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
CGraphFrm::CGraphFrm()
{
	m_hThread = NULL;
	m_bExit = FALSE;
	m_fTimeZoom = 1;
	m_fLevelZoom = 1;
	m_fTimeOffset = 0;
	m_fLevelOffset = 0;
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrm::~CGraphFrm
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�f�X�g���N�^
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
CGraphFrm::~CGraphFrm()
{
	// ���[�J�X���b�h�X���b�h�̏I����҂�
	if (m_hThread != NULL) {
		m_bExit = TRUE;
		m_oEvent.SetEvent();
		::WaitForSingleObject(m_hThread, 1000);
	}
}


BEGIN_MESSAGE_MAP(CGraphFrm, CFrameWnd)
	ON_WM_CREATE()
	ON_COMMAND(IDM_CLOSE, &CGraphFrm::OnClose)
	ON_MESSAGE(WM_READY_GRAPH, &CGraphFrm::OnReadyGraph)
END_MESSAGE_MAP()


// CGraphFrm ���b�Z�[�W �n���h��

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrm::PreCreateWindow
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�E�B���h�E�쐬�O����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CREATESTRUCT		&cs			[I] �E�B���h�E�쐬���\����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL	TRUE�F����@FALSE�F�G���[
//*****************************************************************************************************
BOOL CGraphFrm::PreCreateWindow(CREATESTRUCT& cs)
{
	if( !CFrameWnd::PreCreateWindow(cs) )
		return FALSE;

	cs.dwExStyle &= ~WS_EX_CLIENTEDGE;
	cs.lpszClass = AfxRegisterWndClass(0);

	return TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrm::OnCreate
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�E�B���h�E�쐬����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		LPCREATESTRUCT		lpCreateStruct			[I] �E�B���h�E�쐬���\���̂ւ̃|�C���^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		int		0�F����@-1�F�G���[
//*****************************************************************************************************
int CGraphFrm::OnCreate(LPCREATESTRUCT lpCreateStruct)
{
	static const UINT indicators[] = {
		IDS_STATUS_BAR,
	};

	if (CFrameWnd::OnCreate(lpCreateStruct) == -1)
		return -1;

	// �r���[�E�B���h�E���쐬
	if (!m_wndGraphView.CreateEx(WS_EX_CLIENTEDGE, NULL, NULL, WS_CHILD | WS_VISIBLE | WS_HSCROLL | WS_VSCROLL, CRect(0, 0, 0, 0), this, AFX_IDW_PANE_FIRST, NULL)) {
		TRACE0("�r���[ �E�B���h�E���쐬�ł��܂���ł����B\n");
		return -1;
	}

	// �X�N���[���o�[�ݒ�
	SCROLLINFO si;
	memset(&si, 0, sizeof(si));
	si.cbSize = sizeof(si);
	si.fMask = SIF_PAGE | SIF_RANGE;
	si.nPage = SCROLL_MAX;
	si.nMax = SCROLL_MAX;
	m_wndGraphView.SetScrollInfo(SB_HORZ, &si, FALSE);
	m_wndGraphView.SetScrollInfo(SB_VERT, &si, FALSE);

	// �X�e�[�^�X�o�[���쐬
	if (!m_wndStatusBar.Create(this) || !m_wndStatusBar.SetIndicators(indicators, sizeof(indicators) / sizeof(UINT))) {
		TRACE0("�X�e�[�^�X �o�[�̍쐬�Ɏ��s���܂����B\n");
		return -1;
	}
	m_wndStatusBar.SetPaneStyle(0, SBPS_NOBORDERS | SBPS_STRETCH);

	// �_�C�A���O�o�[���쐬
	if (!m_wndGraphBar.Create(this, IDD_GRAPH_BAR, CBRS_BOTTOM, (UINT)-1)) {
		TRACE0("�_�C�A���O �o�[�̍쐬�Ɏ��s���܂����B\n");
		return -1;
	}

	return 0;
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrm::OnClose
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		Close�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphFrm::OnClose()
{
	DestroyWindow();
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrm::DispGraph
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		��M�t�@�C���̃O���t�\������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		LPCTSTR		pReceiveFilePath	[I] ��M�t�@�C��(.s)�t�@�C���p�X
//		SGraphLine	&oGraphLine			[I] �O���t�f�[�^���C���\�����
//		LPCTSTR		pTitle				[I] �E�B���h�E�^�C�g��
//		CWnd		*pParentWnd			[I] �e�E�B���h�E�|�C���^
//		double		fTempRange1			[I] ���x�͈͉���
//		double		fTempRange2			[I] ���x�͈͏��
//		double		fHeaterRange1		[I] �q�[�^�d������
//		double		fHeaterRange2		[I] �q�[�^�d�����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphFrm::DispGraph(LPCTSTR pReceiveFilePath, SGraphLine &oGraphLine, LPCTSTR pTitle, CWnd *pParentWnd, double fTempRange1, double fTempRange2, double fHeaterRange1, double fHeaterRange2)
{
	m_sReceiveFilePath = pReceiveFilePath;
	m_oGraphLine = oGraphLine;
	m_fTempRange1 = fTempRange1;
	m_fTempRange2 = fTempRange2;
	m_fHeaterRange1 = fHeaterRange1;
	m_fHeaterRange2 = fHeaterRange2;

	// �t���[�������\�[�X���烍�[�h���č쐬
	LoadFrame(IDR_GRAPHFRAME, WS_OVERLAPPEDWINDOW, pParentWnd, NULL);
	SetIcon(AfxGetApp()->LoadIcon(IDR_GRAPHFRAME), FALSE);

	// �E�B���h�E�^�C�g���ݒ�
	SetWindowText(pTitle);

	// �X�e�[�^�X�o�[�Ƀt�@�C���ǂݍ��ݒ����Z�b�g
	m_wndStatusBar.SetPaneText(0, "Reading File");

	// �\���ƍX�V
	ShowWindow(SW_SHOW);

	// �t�@�C���ǂݍ��݃X���b�h�J�n�i��M�����ɉe�����y�ڂ��Ȃ��悤�ɕʃX���b�h�ōs���j
	m_hThread = AfxBeginThread(ReadFileThreadEntry, this, THREAD_PRIORITY_LOWEST)->m_hThread;
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrm::ReadFileThreadEntry
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		��M(.s)�t�@�C���ǂݍ��݃X���b�h�G���g��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		LPVOID		pParam		[I] �X���b�h�p�����[�^(this�|�C���^�j
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		UINT		0
//*****************************************************************************************************
UINT CGraphFrm::ReadFileThreadEntry(LPVOID pParam)
{
	((CGraphFrm *)pParam)->ReadFileThread();
	return 0;
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrm::ReadFileThread
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		��M(.s)�t�@�C���ǂݍ��݃X���b�h����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphFrm::ReadFileThread()
{
	// �t�@�C���ǂݍ��ݏ���
	ReadFile(m_sReceiveFilePath);

	// ���C���X���b�h�ɓǂݍ��݊�����ʒm
	if (!m_bExit)
		PostMessage(WM_READY_GRAPH);

	m_hThread = NULL;
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrm::ReadFile
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		��M(.s)�t�@�C���ǂݍ��ݏ���
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		LPCTSTR		pReceiveNamePath		[I] ��M�t�@�C���p�X
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphFrm::ReadFile(LPCTSTR pReceiveNamePath)
{
	CFile oFile;
	struct {
		char aTime[8];
		BYTE aUserHeader[USER_HEADER_SIZE];
	} oHeaderBuf;
	BYTE aAnalogDataBuf[ANALOG_DATA_SIZE];
	SUserHeader oUserHeader;
	SAnalogData oAnalogData;
	SMmeStatusData oMmeStatusData;
	int nHour, nMinute, nSecond;

	// �ǂݍ��݃t�@�C�����I�[�v��
	if (!oFile.Open(pReceiveNamePath, CFile::modeRead | CFile::shareDenyWrite))
		return;

	// �t�@�C������f�[�^��ǂݏo��
	while (!m_bExit) {
		// �w�b�_������ǂݍ���
		if (oFile.Read(&oHeaderBuf, sizeof(oHeaderBuf)) != sizeof(oHeaderBuf))
			break;

		// �f�[�^��M�����t�H�[�}�b�g�`�F�b�N
		if (sscanf_s(oHeaderBuf.aTime, "%2d:%2d:%2d", &nHour, &nMinute, &nSecond) != 3)
			return;

		// ���[�U�w�b�_�������o��
		g_oMmeData.GetUserHeader(oHeaderBuf.aUserHeader, oUserHeader);
		switch (oUserHeader.nPacketType) {
		case PACKET_TELEMETRY:
			// �Z�O�����g�J�E���g��0�Ȃ�΃A�i���O�f�[�^��ǂݍ���
			if (oUserHeader.nSegmentCount == 0) {
				if (oFile.Read(aAnalogDataBuf, sizeof(aAnalogDataBuf)) != sizeof(aAnalogDataBuf))
					return;

				g_oMmeData.GetAnalogData(aAnalogDataBuf, oUserHeader.oTelemetry.oStatusWord, oAnalogData);
				m_oGraphData.AddGraphData(oUserHeader.oTimestamp, oUserHeader.oTelemetry.oStatusWord, oAnalogData);
			}

			// �f�W�^���f�[�^�����͓ǂݔ�΂�
			oFile.Seek(oUserHeader.oTelemetry.nDataNum * DIGITAL_DATA_SIZE, CFile::current);
			break;
		case PACKET_MME_STATUS:
			// MME�X�e�[�^�X�f�[�^�̃A�i���O�f�[�^�܂ł̃f�[�^��ǂݔ�΂�
			oFile.Seek(40, CFile::current);

			// �A�i���O�f�[�^��ǂݍ���
			if (oFile.Read(aAnalogDataBuf, sizeof(aAnalogDataBuf)) != sizeof(aAnalogDataBuf))
				return;

			// �O���t�ɒǉ�
			g_oMmeData.GetAnalogData(aAnalogDataBuf, oUserHeader.oMmeStatus.oStatusWord, oAnalogData);
			m_oGraphData.AddGraphData(oUserHeader.oTimestamp, oUserHeader.oMmeStatus.oStatusWord, oAnalogData);

			// �O��HALT���G���[����ǂݔ�΂�
			oFile.Seek(24, CFile::current);
			break;
		case PACKET_ERROR_STATUS:
			// �G���[�X�e�[�^�X�̃f�[�^������ǂݔ�΂�
			oFile.Seek(oUserHeader.oErrorStatus.nDataNum, CFile::current);
			break;
		case PACKET_MESSAGE:
			// ���b�Z�[�W�̃f�[�^������ǂݔ�΂�
			oFile.Seek(oUserHeader.oMessage.nDataNum, CFile::current);
			break;
		}
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrm::DispGraph
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		��M���̃O���t�\������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CGraphData	&oGraphData			[I] �O���t�f�[�^
//		SGraphLine	&oGraphLine			[I] �O���t�f�[�^���C���\�����
//		LPCTSTR		pTitle				[I] �E�B���h�E�^�C�g��
//		CWnd		*pParentWnd			[I] �e�E�B���h�E�|�C���^
//		double		fTempRange1			[I] ���x�͈͉���
//		double		fTempRange2			[I] ���x�͈͏��
//		double		fHeaterRange1		[I] �q�[�^�d������
//		double		fHeaterRange2		[I] �q�[�^�d�����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphFrm::DispGraph(CGraphData &oGraphData, SGraphLine &oGraphLine, LPCTSTR pTitle, CWnd *pParentWnd, double fTempRange1, double fTempRange2, double fHeaterRange1, double fHeaterRange2)
{
	m_pGraphData = &oGraphData;
	m_oGraphLine = oGraphLine;
	m_fTempRange1 = fTempRange1;
	m_fTempRange2 = fTempRange2;
	m_fHeaterRange1 = fHeaterRange1;
	m_fHeaterRange2 = fHeaterRange2;

	// �t���[�������\�[�X���烍�[�h���č쐬
	LoadFrame(IDR_GRAPHFRAME, WS_OVERLAPPEDWINDOW, pParentWnd, NULL);

	// �E�B���h�E�^�C�g����ݒ�
	SetWindowText(pTitle);

	// �\���ƍX�V
	ShowWindow(SW_SHOW);

	// �O���t�f�[�^�R�s�[�X���b�h���J�n�i��M�����ɉe�����y�ڂ��Ȃ��悤�ɕʃX���b�h�ōs���j
	m_hThread = AfxBeginThread(CopyGraphDataThreadEntry, this, THREAD_PRIORITY_LOWEST)->m_hThread;
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrm::CopyGraphDataThreadEntry
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�O���t�f�[�^�R�s�[�X���b�h�G���g��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		LPVOID		pParam		[I] �X���b�h�p�����[�^(this�|�C���^�j
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		UINT		0
//*****************************************************************************************************
UINT CGraphFrm::CopyGraphDataThreadEntry(LPVOID pParam)
{
	((CGraphFrm *)pParam)->CopyGraphDataThread();
	return 0;
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrm::CopyGraphDataThread
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�O���t�f�[�^�R�s�[�X���b�h����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphFrm::CopyGraphDataThread()
{
	// �O���t�f�[�^���R�s�[
	m_oGraphData = *m_pGraphData;

	// �R�s�[�I�������C���X���b�h�ɒʒm
	if (!m_bExit)
		PostMessage(WM_READY_GRAPH);

	m_hThread = NULL;
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrm::OnReadyGraph
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�O���t������������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		WPARAM		wParam			[I] ���b�Z�[�W�p�����[�^
//		LPARAM		lParam			[I] ���b�Z�[�W�p�����[�^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		LRESULT		0
//*****************************************************************************************************
LRESULT CGraphFrm::OnReadyGraph(WPARAM wParam, LPARAM lParam)
{
	// Time Range���擾�i�ŏITimestamp���30���ȏ�O�̃f�[�^�͖�������j
	int nDataNum = m_oGraphData.GetDataNum();
	if (nDataNum != 0) {
		m_fTimeRange2 = (double)m_oGraphData.GetGraphData(nDataNum - 1).oTimestamp.GetTime();

		for (int i = 0; i < nDataNum; i++) {
			m_fTimeRange1 = (double)m_oGraphData.GetGraphData(i).oTimestamp.GetTime();
			if (m_fTimeRange2 - m_fTimeRange1 < 30 * 24 * 60 * 60)
				break;
		}
	}

	// Data Line��\��
	m_wndGraphBar.DispDataLine(m_oGraphLine);

	// �O���t���X�V
	UpdateGraph();

	// �X�e�[�^�X�o�[�ɏ���������\��
	m_wndStatusBar.SetPaneText(0, "Ready");

	return 0;
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrm::UpdateGraph
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�O���t�\���X�V����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphFrm::UpdateGraph()
{
	double fTimeSpan = m_fTimeRange2 - m_fTimeRange1;

	// �����͈͂̕␳
	if (m_fTimeOffset < 0)
		m_fTimeOffset = 0;
	else if (m_fTimeRange1 + fTimeSpan * m_fTimeOffset + 1 / m_fTimeZoom * fTimeSpan > m_fTimeRange2)
		m_fTimeOffset = 1 - 1 / m_fTimeZoom;

	double fTimeRange1 = m_fTimeRange1 + fTimeSpan * m_fTimeOffset;
	double fTimeRange2 = fTimeRange1 + fTimeSpan / m_fTimeZoom;

	double fTempSpan = m_fTempRange2 - m_fTempRange1;

	// �c���͈͂̕␳
	if (m_fLevelOffset < 0)
		m_fLevelOffset = 0;
	else if (m_fTempRange1 + fTempSpan * m_fLevelOffset + 1 / m_fLevelZoom * fTempSpan > m_fTempRange2)
		m_fLevelOffset = 1 - 1 / m_fLevelZoom;

	double fTempRange1 = m_fTempRange1 + fTempSpan * m_fLevelOffset;
	double fTempRange2 = fTempRange1 + fTempSpan / m_fLevelZoom;

	double fHeaterSpan = m_fHeaterRange2 - m_fHeaterRange1;
	double fHeaterRange1 = m_fHeaterRange1 + fHeaterSpan * m_fLevelOffset;
	double fHeaterRange2 = fHeaterRange1 + fHeaterSpan / m_fLevelZoom;

	// ���X�N���[���o�[�̐ݒ�
	SCROLLINFO si;
	memset(&si, 0, sizeof(si));
	si.cbSize = sizeof(si);
	si.fMask = SIF_PAGE | SIF_POS;
	si.nPage = (int)(SCROLL_MAX / m_fTimeZoom);
	si.nPos = (int)(m_fTimeOffset * SCROLL_MAX);
	m_wndGraphView.SetScrollInfo(SB_HORZ, &si);

	// �c�X�N���[���o�[�̐ݒ�
	si.nPage = (int)(SCROLL_MAX / m_fLevelZoom);
	si.nPos = SCROLL_MAX - si.nPage - (int)(m_fLevelOffset * SCROLL_MAX);
	m_wndGraphView.SetScrollInfo(SB_VERT, &si);

	// �����͈͂̕\��
	m_wndGraphBar.DispHorizontalAxis(fTimeRange1, fTimeRange2);

	// �c���͈͂̕\��
	m_wndGraphBar.DispVerticalAxis(fTempRange1, fTempRange2, fHeaterRange1, fHeaterRange2);

	// �O���t�\��
	m_wndGraphView.DispGraph(
			m_oGraphData,
			m_oGraphLine,
			fTimeRange1, fTimeRange2,
			fTempRange1, fTempRange2,
			fHeaterRange1, fHeaterRange2);

	m_wndGraphView.UpdateWindow();
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrm::ChangeGraphLine
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�O���t�f�[�^���C���ύX����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		SGraphLine		&oGraphLine			[I] �O���t�f�[�^���C�����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphFrm::ChangeGraphLine(const SGraphLine &oGraphLine)
{
	m_oGraphLine = oGraphLine;

	UpdateGraph();
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrm::ScrollTime
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�����X�N���[������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		double		fScrollPos			[I] �X�N���[���ʒu
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphFrm::ScrollTime(double fScrollPos)
{
	m_fTimeOffset = fScrollPos;

	UpdateGraph();
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrm::ZoomTime
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�����Y�[������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		double		fZoomPos			[I] �Y�[���ʒu
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphFrm::ZoomTime(double fZoomPos)
{
	m_fTimeZoom = exp(fZoomPos * 5);

	UpdateGraph();
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrm::ScrollLevel
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�c���X�N���[������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		double		fScrollPos			[I] �X�N���[���ʒu
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphFrm::ScrollLevel(double fScrollPos)
{
	m_fLevelOffset = fScrollPos;

	UpdateGraph();
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrm::ZoomLevel
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�c���Y�[������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		double		fZoomPos			[I] �Y�[���ʒu
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphFrm::ZoomLevel(double fZoomPos)
{
	double fCenter = m_fLevelOffset + 0.5 / m_fLevelZoom;
	m_fLevelZoom = exp(fZoomPos * 5);
	m_fLevelOffset = fCenter - 0.5 / m_fLevelZoom;

	UpdateGraph();
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrm::MouseScroll
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�}�E�X�ɂ��X�N���[������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		double		fTimeScroll			[I] �����X�N���[���ʒu
//		double		fLevelScroll		[I] �c���X�N���[���ʒu
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphFrm::MouseScroll(double fTimeScroll, double fLevelScroll)
{
	m_fTimeOffset += fTimeScroll / m_fTimeZoom;
	m_fLevelOffset += fLevelScroll / m_fLevelZoom;

	UpdateGraph();
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrm::MouseZoom
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�}�E�X�ɂ��Y�[������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		double		fTimePos1		[I] �����Y�[���J�n�ʒu
//		double		fTimePos2		[I] �����Y�[���I���ʒu
//		double		fLevelPos1		[I] �c���Y�[���J�n�ʒu
//		double		fLevelPos2		[I] �c���Y�[���I���ʒu
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphFrm::MouseZoom(double fTimePos1, double fTimePos2, double fLevelPos1, double fLevelPos2)
{
	if (fTimePos1 > fTimePos2) {
		double fTimePos = fTimePos1;
		fTimePos1 = fTimePos2;
		fTimePos2 = fTimePos;
	}

	if (fLevelPos1 > fLevelPos2) {
		double fLevelPos = fLevelPos1;
		fLevelPos1 = fLevelPos2;
		fLevelPos2 = fLevelPos;
	}

	m_fTimeOffset += fTimePos1 / m_fTimeZoom;
	m_fTimeZoom /= fTimePos2 - fTimePos1;

	m_fLevelOffset += fLevelPos1 / m_fLevelZoom;
	m_fLevelZoom /= fLevelPos2 - fLevelPos1;

	m_wndGraphBar.SetTimeZoom(log(m_fTimeZoom) / 5);
	m_wndGraphBar.SetLevelZoom(log(m_fLevelZoom) / 5);

	UpdateGraph();
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrm::ZoomReset
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�Y�[����������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphFrm::ZoomReset()
{
	m_fTimeZoom = 1;
	m_fLevelZoom = 1;

	m_wndGraphBar.SetTimeZoom(0);
	m_wndGraphBar.SetLevelZoom(0);

	UpdateGraph();
}
