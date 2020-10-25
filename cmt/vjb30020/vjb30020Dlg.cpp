// vjb30020Dlg.cpp : �C���v�������e�[�V���� �t�@�C��
//

#include "stdafx.h"
#include "vjb30020.h"
#include "vjb30020Dlg.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

// ��ԃR�[�h
#define STATUS_READY		'N'
#define STATUS_EXECUTE		'1'
#define STATUS_NONCHECK		'2'
#define STATUS_EXECUTING	'3'
#define STATUS_IOERROR		'7'
#define STATUS_EXECUTEERROR	'8'

// �L���[�t�@�C���f�[�^��
#define QF_LEN_DATE						14
#define QF_LEN_BAITAI_SHUBETSU_CODE		3
#define QF_LEN_BAITAI_SHIKIBETSU_CODE	6
#define QF_LEN_STATUS					1
#define QF_LEN_VOLUMELABEL				6

// �L���[�t�@�C���f�[�^�ʒu
#define QF_POS_DATE						0
#define QF_POS_BAITAI_SHUBETSU_CODE		14
#define QF_POS_BAITAI_SHIKIBETSU_CODE	17
#define QF_POS_STATUS					23
#define QF_POS_VOLUMELABEL				24

// �^�C�}�[ID
#define TIMER_QUEUE			1

/////////////////////////////////////////////////////////////////////////////
// CVjb30020Dlg �_�C�A���O

CVjb30020Dlg::CVjb30020Dlg(CWnd* pParent /*=NULL*/)
	: CDialog(CVjb30020Dlg::IDD, pParent)
{
	//{{AFX_DATA_INIT(CVjb30020Dlg)
		// ����: ���̈ʒu�� ClassWizard �ɂ���ă����o�̏��������ǉ�����܂��B
	//}}AFX_DATA_INIT
	// ����: LoadIcon �� Win32 �� DestroyIcon �̃T�u�V�[�P���X��v�����܂���B
	m_hIcon = AfxGetApp()->LoadIcon(IDR_MAINFRAME);
}

void CVjb30020Dlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	//{{AFX_DATA_MAP(CVjb30020Dlg)
	DDX_Control(pDX, IDC_NONCHECK, m_cNonCheck);
	DDX_Control(pDX, IDC_QUEUE_LIST, m_cQueueList);
	//}}AFX_DATA_MAP
}

BEGIN_MESSAGE_MAP(CVjb30020Dlg, CDialog)
	//{{AFX_MSG_MAP(CVjb30020Dlg)
	ON_WM_PAINT()
	ON_WM_QUERYDRAGICON()
	ON_BN_CLICKED(IDC_NONCHECK, OnNoncheck)
	ON_BN_CLICKED(IDC_RELOAD, OnReload)
	ON_WM_TIMER()
	ON_WM_DESTROY()
	ON_NOTIFY(NM_CLICK, IDC_QUEUE_LIST, OnClickQueueList)
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CVjb30020Dlg ���b�Z�[�W �n���h��

//
//	�@�\	�F	�_�C�A���O�J�n����
//
//	����	�F	����
//
//	���A�l	�F	TRUE - �t�H�[�J�X�̐ݒ�
//
//	�@�\�����F	�}�̏o�͊Ǘ��_�C�A���O�̏������������s���B
//
//	���l	�F	�_�C�A���O�\�����Ƀt���[�����[�N���Ă΂��B
//
BOOL CVjb30020Dlg::OnInitDialog()
{
	CDialog::OnInitDialog();

	// ���̃_�C�A���O�p�̃A�C�R����ݒ肵�܂��B�t���[�����[�N�̓A�v���P�[�V�����̃��C��
	// �E�B���h�E���_�C�A���O�łȂ����͎����I�ɐݒ肵�܂���B
	SetIcon(m_hIcon, TRUE);			// �傫���A�C�R����ݒ�
	SetIcon(m_hIcon, FALSE);		// �������A�C�R����ݒ�

	// ����������
	Initialize();

	// ���X�g�J�����ݒ�
	SetLixxxolumn();

	// �L���[���X�g�̕\��
	SetQueueList(FALSE);
	
	return TRUE;  // TRUE ��Ԃ��ƃR���g���[���ɐݒ肵���t�H�[�J�X�͎����܂���B
}

// �����_�C�A���O�{�b�N�X�ɍŏ����{�^����ǉ�����Ȃ�΁A�A�C�R����`�悷��
// �R�[�h���ȉ��ɋL�q����K�v������܂��BMFC �A�v���P�[�V������ document/view
// ���f�����g���Ă���̂ŁA���̏����̓t���[�����[�N�ɂ�莩���I�ɏ�������܂��B

void CVjb30020Dlg::OnPaint() 
{
	if (IsIconic())
	{
		CPaintDC dc(this); // �`��p�̃f�o�C�X �R���e�L�X�g

		SendMessage(WM_ICONERASEBKGND, (WPARAM) dc.GetSafeHdc(), 0);

		// �N���C�A���g�̋�`�̈���̒���
		int cxIcon = GetSystemMetrics(SM_CXICON);
		int cyIcon = GetSystemMetrics(SM_CYICON);
		CRect rect;
		GetClientRect(&rect);
		int x = (rect.Width() - cxIcon + 1) / 2;
		int y = (rect.Height() - cyIcon + 1) / 2;

		// �A�C�R����`�悵�܂��B
		dc.DrawIcon(x, y, m_hIcon);
	}
	else
	{
		CDialog::OnPaint();
	}
}

// �V�X�e���́A���[�U�[���ŏ����E�B���h�E���h���b�O���Ă���ԁA
// �J�[�\����\�����邽�߂ɂ������Ăяo���܂��B
HCURSOR CVjb30020Dlg::OnQueryDragIcon()
{
	return (HCURSOR) m_hIcon;
}

//
//	�@�\	�F	���s�{�^���N���b�N����
//
//	����	�F	����
//
//	���A�l	�F	����
//
//	�@�\�����F	�O���}�̏o�͂����s����B
//
//	���l	�F	����
//
void CVjb30020Dlg::OnOK() 
{
	// ���s����
	Execute(STATUS_EXECUTE);
}

//
//	�@�\	�F	���x���m���`�F�b�N���s�{�^���N���b�N����
//
//	����	�F	����
//
//	���A�l	�F	����
//
//	�@�\�����F	���x���m���`�F�b�N�ŊO���}�̏o�͂����s����B
//
//	���l	�F	����
//
void CVjb30020Dlg::OnNoncheck() 
{
	// ���x���m���`�F�b�N���s����
	Execute(STATUS_NONCHECK);
}

//
//	�@�\	�F	�ŐV�̏�ԂɍX�V�{�^���N���b�N����
//
//	����	�F	����
//
//	���A�l	�F	����
//
//	�@�\�����F	�L���[�t�@�C����ǂݍ��݁A�\�����ŐV�̏�ԂɍX�V����B
//
//	���l	�F	����
//
void CVjb30020Dlg::OnReload() 
{
	// �L���[���X�g�\��
	SetQueueList(FALSE);
}

//
//	�@�\	�F	�^�C�}�[����
//
//	����	�F	nIDEvent - �^�C�}�[ID
//
//	���A�l	�F	����
//
//	�@�\�����F	�L���[�t�@�C����ǂݍ��݁A�\�����ŐV�̏�ԂɍX�V����B
//
//	���l	�F	����
//
void CVjb30020Dlg::OnTimer(UINT nIDEvent) 
{
	// �L���[���X�g�\��
	SetQueueList(TRUE);

	CDialog::OnTimer(nIDEvent);
}

//
//	�@�\	�F	�_�C�A���O�I������
//
//	����	�F	����
//
//	���A�l	�F	����
//
//	�@�\�����F	�L���[���X�g���폜����B�i����������̂��߁j
//
//	���l	�F	����
//
void CVjb30020Dlg::OnDestroy() 
{
	// �L���[���X�g�폜
	DeleteQueueList();

	CDialog::OnDestroy();
}

//
//	�@�\	�F	����������
//
//	����	�F	����
//
//	���A�l	�F	����
//
//	�@�\�����F	�����X�V�^�C�}�[���Z�b�g����B
//
//	���l	�F	����
//
void CVjb30020Dlg::Initialize()
{
	// �L���[�t�@�C���Ď��^�C�}�[�ݒ�
	if (g_nTimer != 0)
		SetTimer(TIMER_QUEUE, g_nTimer * 60 * 1000, NULL);
}

//
//	�@�\	�F	�L���[���X�g�J�����ݒ菈��
//
//	����	�F	����
//
//	���A�l	�F	����
//
//	�@�\�����F	�L���[���X�g�̃J��������ݒ肷��B
//
//	���l	�F	����
//
void CVjb30020Dlg::SetLixxxolumn()
{
	struct LIxxxOLUMN {
		char *text;
		int fmt;
		int width;
	} tColumn[] = {
		{"���", LVCFMT_LEFT, 100},
		{"�}�̎��ʃR�[�h", LVCFMT_LEFT, 100},
		{"Vol���x����", LVCFMT_LEFT, 100},
		{"�}�̎��", LVCFMT_LEFT, 100},
		{"�쐬����", LVCFMT_LEFT, 130}
	};
	int i;
	LV_COLUMN lvc;

	// �s�I���X�^�C��
	m_cQueueList.SetExtendedStyle(LVS_EX_FULLROWSELECT);

	// �e�J������ݒ�
	for (i = 0; i < sizeof(tColumn) / sizeof(LIxxxOLUMN); i++) {
		lvc.mask = LVCF_FMT | LVCF_WIDTH | LVCF_TEXT;
		lvc.fmt = tColumn[i].fmt;
		lvc.pszText = tColumn[i].text;
		lvc.cx = tColumn[i].width;
		m_cQueueList.InsertColumn(i, &lvc);
	}
}

//
//	�@�\	�F	�L���[���X�g���ݒ菈��
//
//	����	�F	bSendMessage - �^�p�Ǘ��N���C�A���g�ɒʒm�t���O
//
//	���A�l	�F	����
//
//	�@�\�����F	�L���[�t�@�C����ǂݍ��݁A�L���[���X�g�ɕ\������B
//
//	���l	�F	����
//
void CVjb30020Dlg::SetQueueList(BOOL bSendMessage)
{
	HANDLE hDir;
	WIN32_FIND_DATA findBuf;
	Queue *pQueue, *pQueueNew;
	int nItem;
	int i;

	// �������h�~���邽�ߍĕ`����֎~
	//m_cQueueList.LockWindowUpdate();

	// �L���t���O���N���A
	nItem = m_cQueueList.GetItemCount();
	for (i = 0; i < nItem; i++) {
		pQueue = (Queue *)m_cQueueList.GetItemData(i);
		pQueue->bAvail = FALSE;
	}

	// �L���[�t�@�C���̌���
	hDir = FindFirstFile(g_sQueueDir + "\\*", &findBuf);
	if (hDir == INVALID_HANDLE_VALUE)
		return;

	do {
		// ���X�g�ɒǉ�
		if (!(findBuf.dwFileAttributes & FILE_ATTRIBUTE_DIRECTORY)) {
			pQueueNew = GetQueueData(findBuf.cFileName);
			nItem = m_cQueueList.GetItemCount();
			for (i = 0; i < nItem; i++) {
				pQueue = (Queue *)m_cQueueList.GetItemData(i);
				if (pQueue->sCreateDate == pQueueNew->sCreateDate
						&& pQueue->sShubetsuCode == pQueueNew->sShubetsuCode
						&& pQueue->sShikibetsuCode == pQueueNew->sShikibetsuCode) {
					pQueue->sFileName = pQueueNew->sFileName;
					pQueue->cStatus = pQueueNew->cStatus;
					pQueue->bAvail = TRUE;
					m_cQueueList.SetItemText(i, 0, GetStatusText(pQueue->cStatus));
					delete pQueueNew;
					break;
				}
			}
			if (i == nItem) {
				// �L���[���X�g�ɒǉ�
				AddQueueList(pQueueNew);

				// ���b�Z�[�W�\��
				if (bSendMessage) {
					DispMessage(IDS_MSG_OUTPUT_REQUEST, MB_OK, TRUE,
							pQueueNew->sShikibetsuCode, pQueueNew->sVolLabel, pQueueNew->sShubetsuCode,
							FormatDate(pQueueNew));
				}
			}
		}
	} while (FindNextFile(hDir, &findBuf));

	// �n���h���̃N���[�Y
	FindClose(hDir);

	// �L���łȂ����ڂ��폜
	nItem = m_cQueueList.GetItemCount();
	for (i = 0; i < nItem; i++) {
		pQueue = (Queue *)m_cQueueList.GetItemData(i);
		if (!pQueue->bAvail) {
			delete pQueue;
			m_cQueueList.DeleteItem(i);
			i--;
			nItem--;
		}
	}

	// �ĕ`�������
	//m_cQueueList.UnlockWindowUpdate();

	// ���x���m���`�F�b�N���s�̗L���^�����ݒ�
	EnableNonCheck();
}

//
//	�@�\	�F	�L���[���擾����
//
//	����	�F	sFileName - �L���[�t�@�C����
//
//	���A�l	�F	�L���[���
//
//	�@�\�����F	�L���[�t�@�C���t�@�C��������L���[�����擾����B
//
//	���l	�F	����
//
Queue *CVjb30020Dlg::GetQueueData(CString sFileName)
{
	Queue *pQueue = new Queue;

	// �L���[�t�@�C��������e�p�����[�^���擾
	pQueue->sFileName = sFileName;
	pQueue->sShubetsuCode = sFileName.Mid(QF_POS_BAITAI_SHUBETSU_CODE, QF_LEN_BAITAI_SHUBETSU_CODE);
	pQueue->sShikibetsuCode = sFileName.Mid(QF_POS_BAITAI_SHIKIBETSU_CODE, QF_LEN_BAITAI_SHIKIBETSU_CODE);
	pQueue->sVolLabel = sFileName.Mid(QF_POS_VOLUMELABEL);
	pQueue->sCreateDate = sFileName.Left(QF_LEN_DATE);
	pQueue->cStatus = sFileName[QF_POS_STATUS];
	pQueue->bAvail = TRUE;

	return pQueue;
}

//
//	�@�\	�F	�L���[���X�g�폜����
//
//	����	�F	����
//
//	���A�l	�F	����
//
//	�@�\�����F	�L���[���X�g�̓��e��S�č폜����B
//
//	���l	�F	����
//
void CVjb30020Dlg::DeleteQueueList()
{
	// �L���[���X�g�̑S�폜
	while (m_cQueueList.GetItemCount() != 0) {
		delete (Queue *)m_cQueueList.GetItemData(0);
		m_cQueueList.DeleteItem(0);
	}
}

//
//	�@�\	�F	�L���[���X�g�ǉ�����
//
//	����	�F	pQueue - �L���[���
//
//	���A�l	�F	����
//
//	�@�\�����F	�L���[��񂩂�L���[���X�g�ɒǉ�����B
//
//	���l	�F	����
//
void CVjb30020Dlg::AddQueueList(Queue *pQueue)
{
	// �L���[���X�g�ɍ��ڂ�ǉ�
	m_cQueueList.InsertItem(0, GetStatusText(pQueue->cStatus));
	m_cQueueList.SetItemText(0, 1, pQueue->sShikibetsuCode);
	m_cQueueList.SetItemText(0, 2, pQueue->sVolLabel.Left(LEN_VOLUME_LABEL));
	m_cQueueList.SetItemText(0, 3, pQueue->sShubetsuCode);
	m_cQueueList.SetItemText(0, 4, FormatDate(pQueue));

	// �L���[�f�[�^�̕ۑ�
	m_cQueueList.SetItemData(0, (DWORD)pQueue);
}

//
//	�@�\	�F	��ԕ\�������擾����
//
//	����	�F	status - ��ԃR�[�h
//
//	���A�l	�F	��ԕ\������
//
//	�@�\�����F	��ԃR�[�h�����ԕ\���������擾����B
//
//	���l	�F	����
//
CString CVjb30020Dlg::GetStatusText(char status)
{
	CString str;

	// ��ԃR�[�h�ɑΉ����镶������擾
	switch (status) {
	case STATUS_READY:
		str = "��";
		break;
	case STATUS_EXECUTE:
		str = "���s";
		break;
	case STATUS_NONCHECK:
		str = "���������s";
		break;
	case STATUS_EXECUTING:
		str = "���s��";
		break;
	case STATUS_IOERROR:
		str = "I/O�G���[";
		break;
	case STATUS_EXECUTEERROR:
		str = "���s�G���[";
		break;
	default:
		str = "�s��";
		break;
	}

	return str;
}

//
//	�@�\	�F	�O���}�̏o�͎��s����
//
//	����	�F	status - ��ԃR�[�h
//
//	���A�l	�F	��ԕ\������
//
//	�@�\�����F	�L���[�����X�V���A�O���}�̏o�̓v���O�������N������B
//
//	���l	�F	����
//
void CVjb30020Dlg::Execute(char status)
{
	// �L���[���X�g����I������Ă��鍀�ڂ��擾
	POSITION pos = m_cQueueList.GetFirstSelectedItemPosition();
	if (pos == NULL) {
		DispMessage(IDS_MSG_DEVICE_SELECT, MB_OK, FALSE);
		return;
	}

	// �L���[�f�[�^���擾
	int nItem = m_cQueueList.GetNextSelectedItem(pos);
	Queue *pQueue = (Queue *)m_cQueueList.GetItemData(nItem);

	// ���łɎ��s���̏ꍇ�̓��b�Z�[�W�\��
	if (pQueue->cStatus == STATUS_EXECUTE || pQueue->cStatus == STATUS_NONCHECK || pQueue->cStatus == STATUS_EXECUTING) {
		DispMessage(IDS_MSG_EXECUTING, MB_OK, FALSE);
		return;
	}

	// ���s�m�F���b�Z�[�W�\��
	if (status == STATUS_EXECUTE) {
		if (DispMessage(IDS_MSG_DEVICE_SET, MB_OKCANCEL, FALSE,
					pQueue->sShikibetsuCode, pQueue->sVolLabel, pQueue->sShubetsuCode) == IDCANCEL)
			return;
	} else {
		if (DispMessage(IDS_MSG_LABEL_NONCHECK, MB_OKCANCEL, FALSE,
					pQueue->sShikibetsuCode, pQueue->sVolLabel, pQueue->sShubetsuCode,
					FormatDate(pQueue), pQueue->sShubetsuCode) == IDCANCEL)
			return;
	}

	// ��ԃR�[�h��1�i���s�j�ɐݒ�
	ChangeStatus(pQueue, status, nItem);

	// �O���}�̏o�̓v���O�������N��
	ExecuteCopyProgram(pQueue);
}

//
//	�@�\	�F	�O���}�̏o�̓v���O�����N������
//
//	����	�F	pQueue - �L���[���
//
//	���A�l	�F	����
//
//	�@�\�����F	�L���[���ɏ]���ĊO���}�̏o�̓v���O�������N������B
//
//	���l	�F	����
//
void CVjb30020Dlg::ExecuteCopyProgram(Queue *pQueue)
{
	CString sCmdLine;
	char cCurrentDir[256];
	STARTUPINFO si;
	PROCESS_INFORMATION pi;

#if _DEBUG
	sCmdLine.Format("%s %s", "vjb30030\\debug\\vjb30030.exe", (LPCTSTR)pQueue->sFileName);
#else
	sCmdLine.Format("%s %s", "vjb30030.exe", (LPCTSTR)pQueue->sFileName);
#endif
	memset(&si, 0, sizeof(si));
	si.cb = sizeof(si);
	si.dwFlags = STARTF_USESHOWWINDOW;
	si.wShowWindow = SW_SHOW;
	GetCurrentDirectory(sizeof(cCurrentDir), cCurrentDir);
	CreateProcess(NULL, (LPTSTR)(LPCTSTR)sCmdLine, NULL, NULL, FALSE, NORMAL_PRIORITY_CLASS, NULL, cCurrentDir, &si, &pi);
	CloseHandle(pi.hProcess);
	CloseHandle(pi.hThread);
}

//
//	�@�\	�F�@��ԃR�[�h�ύX����
//
//	����	�F	pQueue - �L���[���
//				status - ��ԃR�[�h
//				nItem - �L���[���X�g�ʒu
//
//	���A�l	�F	����
//
//	�@�\�����F	�L���[�t�@�C������уL���[���X�g�̏�ԃR�[�h��ύX����B
//
//	���l	�F	����
//
void CVjb30020Dlg::ChangeStatus(Queue *pQueue, char status, int nItem)
{
	CString sOldFileName, sNewFileName;

	// �L���[�t�@�C���̃p�X�����擾
	sOldFileName = CreatePathName(g_sQueueDir, pQueue->sFileName);

	// ��ԃR�[�h��ύX
	pQueue->sFileName.SetAt(QF_POS_STATUS, status);
	pQueue->cStatus = status;

	// �ύX��̃L���[�t�@�C���̃p�X�����擾
	sNewFileName = CreatePathName(g_sQueueDir, pQueue->sFileName);

	// �t�@�C�����ύX
	if (MoveFile(sOldFileName, sNewFileName))
		m_cQueueList.SetItemText(nItem, 0, GetStatusText(status));
}

//
//	�@�\	�F�@���t�f�[�^�t�H�[�}�b�g����
//
//	����	�F	pQueue - �L���[���
//
//	���A�l	�F	�\���p���t����
//
//	�@�\�����F	�L���[�t�@�C���̓��t�f�[�^��\���p�ɕҏW����B
//
//	���l	�F	����
//
CString CVjb30020Dlg::FormatDate(Queue *pQueue)
{
	// ���t��\���p�ɕҏW
	return pQueue->sCreateDate.Mid(0, 4) + "/"
				+ pQueue->sCreateDate.Mid(4, 2) + "/"
				+ pQueue->sCreateDate.Mid(6, 2) + " "
				+ pQueue->sCreateDate.Mid(8, 2) + ":"
				+ pQueue->sCreateDate.Mid(10, 2) + ":"
				+ pQueue->sCreateDate.Mid(12, 2);
}

//
//	�@�\	�F�@�L���[���X�g�N���b�N����
//
//	����	�F	pNMHDR - ���g�p
//				pResult - ����
//
//	���A�l	�F	����
//
//	�@�\�����F	���x���m���`�F�b�N���s�{�^���̗L���^������ݒ肷��B
//
//	���l	�F	����
//
void CVjb30020Dlg::OnClickQueueList(NMHDR* pNMHDR, LRESULT* pResult) 
{
	// ���x���m���`�F�b�N���s�{�^���̗L���^�����̐ݒ�
	EnableNonCheck();
	
	*pResult = 0;
}

//
//	�@�\	�F�@���x���m���`�F�b�N���s�{�^���̗L���^�����ݒ菈��
//
//	����	�F	����
//
//	���A�l	�F	����
//
//	�@�\�����F	�L���[���X�g�őI������Ă���f�[�^�ɂ��A
//				���x���m���`�F�b�N���s�{�^���̗L���^������ݒ肷��B
//
//	���l	�F	����
//
void CVjb30020Dlg::EnableNonCheck()
{
	int i, nItem;
	BOOL bEnable = FALSE;

	// �L���[���X�g����I������Ă��鍀�ڂ��擾
	nItem = m_cQueueList.GetItemCount();
	for (i = 0; i < nItem; i++) {
		if (m_cQueueList.GetItemState(i, LVIS_SELECTED) != 0)
			break;
	}

	// ��ԃR�[�h��8�i���s�G���[�j�ł���ΗL��
	if (i < nItem) {
		Queue *pQueue = (Queue *)m_cQueueList.GetItemData(i);
		if (pQueue->cStatus == STATUS_EXECUTEERROR)
			bEnable = TRUE;
	}
	m_cNonCheck.EnableWindow(bEnable);
}
