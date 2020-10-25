// LLogDlg.cpp : 実装ファイル
//

#include "stdafx.h"
#include "LLog.h"
#include "LLogDlg.h"
#include "DlgComment.h"
#include "DlgEvent.h"
#include "DlgPhoto.h"
#include "DlgTrain.h"
#include "DlgGpsLogger.h"
#include "DlgKeitaiGps.h"
#include "DlgBoard.h"
#include "DlgWalking.h"
#include "DlgSearch.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#endif


// アプリケーションのバージョン情報に使われる CAboutDlg ダイアログ

class CAboutDlg : public CDialog
{
public:
	CAboutDlg();

// ダイアログ データ
	enum { IDD = IDD_ABOUTBOX };

	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

// 実装
protected:
	DECLARE_MESSAGE_MAP()
};

CAboutDlg::CAboutDlg() : CDialog(CAboutDlg::IDD)
{
}

void CAboutDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
}

BEGIN_MESSAGE_MAP(CAboutDlg, CDialog)
END_MESSAGE_MAP()


// CLLogDlg ダイアログ


CLLogDlg::CLLogDlg(CWnd* pParent /*=NULL*/)
	: CDialog(CLLogDlg::IDD, pParent)
{
	m_hIcon = ::AfxGetApp()->LoadIcon(IDR_MAINFRAME);
	m_pSearchFlag = NULL;
}

CLLogDlg::~CLLogDlg()
{
	if (m_pSearchFlag != NULL)
		delete [] m_pSearchFlag;
}

void CLLogDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_LIST_MONTH, m_cListMonth);
	DDX_Control(pDX, IDC_CALENDAR, m_cCalendar);
	DDX_Control(pDX, IDC_LIST_EVENT, m_cListEvent);
	DDX_Control(pDX, IDC_LIST_TRAIN, m_cListTrain);
	DDX_Control(pDX, IDC_STATIC_PHOTO, m_cStaticPhoto);
	DDX_Control(pDX, IDC_LIST_KEITAI_GPS, m_cListKeitaiGps);
	DDX_Control(pDX, IDC_STATIC_GPS_LOGGER, m_cStaticGpsLogger);
	DDX_Control(pDX, IDC_COMBO_PHOTO, m_cComboPhoto);
	DDX_Control(pDX, IDC_COMBO_GPS_LOGGER, m_cComboGpsLogger);
	DDX_Control(pDX, IDC_BUTTON_PHOTO_PREV, m_cButtonPhotoPrev);
	DDX_Control(pDX, IDC_BUTTON_PHOTO_NEXT, m_cButtonPhotoNext);
	DDX_Control(pDX, IDC_STATIC_PHOTO_NUM, m_cStaticPhotoNum);
	DDX_Control(pDX, IDC_EDIT_PHOTO_TIME, m_cEditPhotoTime);
	DDX_Control(pDX, IDC_EDIT_SEARCH, m_cEditSearch);
	DDX_Control(pDX, IDC_LIST_BOARD, m_cListBoard);
	DDX_Control(pDX, IDC_EDIT_COMMENT, m_cEditComment);
	DDX_Control(pDX, IDC_EDIT_PHOTO_FOLDER, m_cEditPhotoFolder);
	DDX_Control(pDX, IDC_BUTTON_GOOGLE_EARTH, m_cButtonGoogleEarth);
	DDX_Control(pDX, IDC_BUTTON_GOOGLE_MAP, m_cButtonGoogleMap);
	DDX_Control(pDX, IDC_BUTTON_PHOTO_MAP, m_cButtonPhotoMap);
	DDX_Control(pDX, IDC_EDIT_WALKING_STEP, m_cEditWalkingStep);
	DDX_Control(pDX, IDC_EDIT_WALKING_CALORIE, m_cEditWalkingCalorie);
	DDX_Control(pDX, IDC_EDIT_WALKING_FAT, m_cEditWalkingFat);
	DDX_Control(pDX, IDC_EDIT_WALKING_DISTANCE, m_cEditWalkingDistance);
	DDX_Control(pDX, IDC_EDIT_WALKING_TIME, m_cEditWalkingTime);
	DDX_Control(pDX, IDC_EDIT_WALKING_SPEED, m_cEditWalkingSpeed);
}

BEGIN_MESSAGE_MAP(CLLogDlg, CDialog)
	ON_WM_SYSCOMMAND()
	ON_WM_PAINT()
	ON_WM_QUERYDRAGICON()
	//}}AFX_MSG_MAP
	ON_NOTIFY(LVN_ITEMCHANGED, IDC_LIST_MONTH, &CLLogDlg::OnLvnItemchangedListMonth)
	ON_NOTIFY(MCN_SELCHANGE, IDC_CALENDAR, &CLLogDlg::OnMcnSelchangeCalendar)
	ON_CBN_SELCHANGE(IDC_COMBO_PHOTO, &CLLogDlg::OnCbnSelchangeComboPhoto)
	ON_BN_CLICKED(IDC_BUTTON_GOOGLE_EARTH, &CLLogDlg::OnBnClickedButtonGoogleEarth)
	ON_CBN_SELCHANGE(IDC_COMBO_GPS_LOGGER, &CLLogDlg::OnCbnSelchangeComboGpsLogger)
	ON_WM_CTLCOLOR()
	ON_LBN_DBLCLK(IDC_LIST_EVENT, &CLLogDlg::OnLbnDblclkListEvent)
	ON_LBN_DBLCLK(IDC_LIST_KEITAI_GPS, &CLLogDlg::OnLbnDblclkListKeitaiGps)
	ON_LBN_DBLCLK(IDC_LIST_TRAIN, &CLLogDlg::OnLbnDblclkListTrain)
	ON_STN_DBLCLK(IDC_STATIC_GPS_LOGGER, &CLLogDlg::OnStnDblclickStaticGpsLogger)
	ON_STN_DBLCLK(IDC_STATIC_PHOTO, &CLLogDlg::OnStnDblclickStaticPhoto)
	ON_NOTIFY(NM_CUSTOMDRAW, IDC_LIST_MONTH, &CLLogDlg::OnNMCustomdrawListMonth)
	ON_BN_CLICKED(IDC_BUTTON_PHOTO_PREV, &CLLogDlg::OnBnClickedButtonPhotoPrev)
	ON_BN_CLICKED(IDC_BUTTON_PHOTO_NEXT, &CLLogDlg::OnBnClickedButtonPhotoNext)
	ON_BN_CLICKED(IDC_BUTTON_SEARCH, &CLLogDlg::OnBnClickedButtonSearch)
	ON_LBN_DBLCLK(IDC_LIST_BOARD, &CLLogDlg::OnLbnDblclkListBoard)
	ON_STN_DBLCLK(IDC_EDIT_COMMENT, &CLLogDlg::OnStnDblclickEditComment)
	ON_BN_CLICKED(IDC_BUTTON_GOOGLE_MAP, &CLLogDlg::OnBnClickedButtonGoogleMap)
	ON_BN_CLICKED(IDC_BUTTON_PHOTO_MAP, &CLLogDlg::OnBnClickedButtonPhotoMap)
	ON_NOTIFY(LVN_COLUMNCLICK, IDC_LIST_MONTH, &CLLogDlg::OnLvnColumnclickListMonth)
	ON_BN_CLICKED(IDC_BUTTON_WALKING, &CLLogDlg::OnBnClickedButtonWalking)
	ON_BN_CLICKED(IDC_BUTTON_WALKING_SUM, &CLLogDlg::OnBnClickedButtonWalkingSum)
END_MESSAGE_MAP()


// CLLogDlg メッセージ ハンドラ

BOOL CLLogDlg::OnInitDialog()
{
	CDialog::OnInitDialog();

	// "バージョン情報..." メニューをシステム メニューに追加します。

	// IDM_ABOUTBOX は、システム コマンドの範囲内になければなりません。
	ASSERT((IDM_ABOUTBOX & 0xFFF0) == IDM_ABOUTBOX);
	ASSERT(IDM_ABOUTBOX < 0xF000);

	CMenu* pSysMenu = GetSystemMenu(FALSE);
	if (pSysMenu != NULL)
	{
		CString strAboutMenu;
		strAboutMenu.LoadString(IDS_ABOUTBOX);
		if (!strAboutMenu.IsEmpty())
		{
			pSysMenu->AppendMenu(MF_SEPARATOR);
			pSysMenu->AppendMenu(MF_STRING, IDM_ABOUTBOX, strAboutMenu);
		}
	}

	// このダイアログのアイコンを設定します。アプリケーションのメイン ウィンドウがダイアログでない場合、
	//  Framework は、この設定を自動的に行います。
	SetIcon(m_hIcon, TRUE);			// 大きいアイコンの設定
	SetIcon(m_hIcon, FALSE);		// 小さいアイコンの設定

	if (!m_oDataBase.Open()) {
		EndDialog(IDCANCEL);
		return TRUE;
	}

	m_cListMonth.SetExtendedStyle(LVS_EX_FULLROWSELECT);

	SetMonthListHeader();
	ChangeCalendar(FALSE);
	DispBoard();
	m_cListMonth.ShowScrollBar(SB_VERT, TRUE);
//	CheckWalking();

	return TRUE;  // フォーカスをコントロールに設定した場合を除き、TRUE を返します。
}

void CLLogDlg::OnSysCommand(UINT nID, LPARAM lParam)
{
	if ((nID & 0xFFF0) == IDM_ABOUTBOX)
	{
		CAboutDlg dlgAbout;
		dlgAbout.DoModal();
	}
	else
	{
		CDialog::OnSysCommand(nID, lParam);
	}
}

// ダイアログに最小化ボタンを追加する場合、アイコンを描画するための
//  下のコードが必要です。ドキュメント/ビュー モデルを使う MFC アプリケーションの場合、
//  これは、Framework によって自動的に設定されます。

void CLLogDlg::OnPaint()
{
	if (IsIconic())
	{
		CPaintDC dc(this); // 描画のデバイス コンテキスト

		SendMessage(WM_ICONERASEBKGND, reinterpret_cast<WPARAM>(dc.GetSafeHdc()), 0);

		// クライアントの四角形領域内の中央
		int cxIcon = GetSystemMetrics(SM_CXICON);
		int cyIcon = GetSystemMetrics(SM_CYICON);
		CRect rect;
		GetClientRect(&rect);
		int x = (rect.Width() - cxIcon + 1) / 2;
		int y = (rect.Height() - cyIcon + 1) / 2;

		// アイコンの描画
		dc.DrawIcon(x, y, m_hIcon);
	}
	else
	{
		CDialog::OnPaint();
	}
}

// ユーザーが最小化したウィンドウをドラッグしているときに表示するカーソルを取得するために、
//  システムがこの関数を呼び出します。
HCURSOR CLLogDlg::OnQueryDragIcon()
{
	return static_cast<HCURSOR>(m_hIcon);
}

void CLLogDlg::SetMonthListHeader()
{
	struct LIxxxOLUMN {
		char *pText;
		int fmt;
		int width;
	} tColumnData[] = {
		{"日付", LVCFMT_CENTER, 96},
		{"日記", LVCFMT_CENTER, 39},
		{"定型", LVCFMT_CENTER, 39},
		{"電車", LVCFMT_CENTER, 39},
		{"携帯", LVCFMT_CENTER, 39},
		{"写真", LVCFMT_CENTER, 39},
		{"GPS", LVCFMT_CENTER, 39},
		{"歩数", LVCFMT_CENTER, 39}
	}, *pColumn;
	int i;
	int nColumn;
	LV_COLUMN lvc;

	// カラムデータ
	pColumn = tColumnData;
	nColumn  = sizeof(tColumnData) / sizeof(LIxxxOLUMN);

	// カラムを設定
	for (i = 0; i < nColumn; i++) {
		lvc.mask = LVCF_FMT | LVCF_WIDTH | LVCF_TEXT;
		lvc.fmt = pColumn->fmt;
		lvc.pszText = pColumn->pText;
		lvc.cx = pColumn->width;
		m_cListMonth.InsertColumn(i, &lvc);
		pColumn++;
	}
}

void CLLogDlg::DispData(CTime oDate, BOOL bForce)
{
	if (bForce || oDate != m_oCurrentDate) {
		m_oCurrentDate = oDate;

		SYSTEMTIME st;
		m_oCurrentDate.GetAsSystemTime(st);
		st.wDay = 1;
		CTime oYM(st);
		if (bForce || (oYM != m_oCurrentYM && m_cEditSearch.IsEmpty())) {
			m_oCurrentYM = oYM;
			DispMonthList();
		}

		m_cCalendar.SetCurSel(m_oCurrentDate);

		for (int i = 0; i < m_cListMonth.GetItemCount(); i++) {
			if (m_cListMonth.GetItemData(i) == m_oCurrentDate.GetTime()) {
				m_cListMonth.SetItemState(i, LVIS_SELECTED | LVIS_FOCUSED , LVIS_SELECTED | LVIS_FOCUSED);
				m_cListMonth.EnsureVisible(i, FALSE);
				break;
			}
		}

		DispDayData();
	}
}

void CLLogDlg::DispMonthList()
{
	m_cListMonth.DeleteAllItems();

	CString sSearch;
	m_cEditSearch.GetWindowText(sSearch);

	CString sSql;
	if (sSearch.IsEmpty()) {
		CString sMonthDate;

		int days = 0;
		for (CTime oDate = m_oCurrentYM; oDate.GetMonth() == m_oCurrentYM.GetMonth(); oDate += CTimeSpan(1, 0, 0, 0))
			days++;

		sSql.Format("SELECT cl_date,"
					 "(SELECT COUNT(*) FROM t_comment WHERE cm_date=cl_date) AS cm_count,"
					 "(SELECT COUNT(*) FROM t_event WHERE ev_date=cl_date) AS ev_count,"
					 "(SELECT COUNT(*) FROM t_train WHERE tr_date=cl_date) AS tr_count,"
					 "(SELECT COUNT(*) FROM t_photo WHERE ph_date=cl_date) AS ph_count,"
					 "(SELECT COUNT(*) FROM t_keitai_gps WHERE kg_date=cl_date) AS kg_count,"
					 "(SELECT COUNT(*) FROM t_gps_logger WHERE gl_date=cl_date) AS gl_count,"
					 "(SELECT wk_step / 1000 FROM t_walking WHERE wk_date=cl_date) AS wk_count,"
					 "FALSE AS cm_search_flag,"
					 "FALSE AS ev_search_flag,"
					 "FALSE AS tr_search_flag,"
					 "FALSE AS ph_search_flag,"
					 "FALSE AS kg_search_flag,"
					 "FALSE AS gl_search_flag "
					"FROM (SELECT date %s + arr.i AS cl_date FROM generate_series(0,%d) AS arr(i)) T "
					"ORDER BY cl_date", m_oDataBase.SqlDate(m_oCurrentYM), days - 1);
	} else {
		CString sSearchEqu = m_oDataBase.SqlText(sSearch);

		CString sCommentLike;
		CString sPhotoLike;
		CString sKeitaiGpsLike;
		CString sGpsLoggerLike;
		MakeLikeSearch(sSearch, sCommentLike, sPhotoLike, sKeitaiGpsLike, sGpsLoggerLike);

		sSql.Format("SELECT s1_date AS cl_date,"
					 "(SELECT COUNT(*) FROM t_comment WHERE cm_date=s1_date) AS cm_count,"
					 "(SELECT COUNT(*) FROM t_event WHERE ev_date=s1_date) AS ev_count,"
					 "(SELECT COUNT(*) FROM t_train WHERE tr_date=s1_date) AS tr_count,"
					 "(SELECT COUNT(*) FROM t_photo WHERE ph_date=s1_date) AS ph_count,"
					 "(SELECT COUNT(*) FROM t_keitai_gps WHERE kg_date=s1_date) AS kg_count,"
					 "(SELECT COUNT(*) FROM t_gps_logger WHERE gl_date=s1_date) AS gl_count,"
					 "(SELECT wk_step / 1000 FROM t_walking WHERE wk_date=s1_date) AS wk_count,"
					 "(EXISTS (SELECT * FROM t_comment WHERE cm_date=s1_date AND %s)) AS cm_search_flag,"
					 "(EXISTS (SELECT * FROM t_event WHERE ev_date=s1_date AND ev_event_id IN (SELECT em_event_id FROM m_event WHERE em_text=%s))) AS ev_search_flag,"
					 "(EXISTS (SELECT * FROM t_train WHERE tr_date=s1_date AND (tr_from_line=%s OR tr_from_station=%s OR tr_to_line=%s OR tr_to_station=%s))) AS tr_search_flag,"
					 "(EXISTS (SELECT * FROM t_photo WHERE ph_date=s1_date AND (%s))) AS ph_search_flag,"
					 "(EXISTS (SELECT * FROM t_keitai_gps WHERE kg_date=s1_date AND (%s))) AS kg_search_flag,"
					 "(EXISTS (SELECT * FROM t_gps_logger JOIN t_gps_comment ON gc_gps_logger_id=gl_seq_no WHERE gl_date=s1_date AND %s)) AS gl_search_flag "
					"FROM ("
					 "SELECT cm_date AS s1_date FROM t_comment WHERE %s UNION "
					 "SELECT ev_date AS s1_date FROM t_event WHERE ev_event_id IN (SELECT em_event_id FROM m_event WHERE em_text=%s) UNION "
					 "SELECT tr_date AS s1_date FROM t_train WHERE tr_from_line=%s OR tr_from_station=%s OR tr_to_line=%s OR tr_to_station=%s UNION "
					 "SELECT ph_date AS s1_date FROM t_photo WHERE %s UNION "
					 "SELECT kg_date AS s1_date FROM t_keitai_gps WHERE %s UNION "
					 "SELECT gl_date AS si_date FROM t_gps_logger JOIN t_gps_comment ON gc_gps_logger_id=gl_seq_no WHERE %s"
					 ") T "
					"ORDER BY s1_date",
					sCommentLike, sSearchEqu, sSearchEqu, sSearchEqu, sSearchEqu, sSearchEqu, sPhotoLike, sKeitaiGpsLike, sGpsLoggerLike,
					sCommentLike, sSearchEqu, sSearchEqu, sSearchEqu, sSearchEqu, sSearchEqu, sPhotoLike, sKeitaiGpsLike, sGpsLoggerLike);
	}

	CDbResult oDbResult;
	if (!m_oDataBase.Select(sSql, oDbResult))
		return;

	CTime oDate;
	CString sDate;
	static char *aWeek[] = {"日", "月", "火", "水", "木", "金", "土"};
	int nRec = oDbResult.GetCount();

	if (m_pSearchFlag != NULL)
		delete [] m_pSearchFlag;
	m_pSearchFlag = new DWORD[nRec];
	memset(m_pSearchFlag, 0, nRec * sizeof(DWORD));

	for (int i = 0; i < nRec; i++) {
		oDate = oDbResult.GetDateValue(i, "cl_date");
		sDate.Format("%04d/%02d/%02d (%s)", oDate.GetYear(), oDate.GetMonth(), oDate.GetDay(), aWeek[oDate.GetDayOfWeek() - 1]);
		m_cListMonth.SetItemData(m_cListMonth.InsertItem(i, sDate), (ULONG)oDate.GetTime());
		if (oDbResult.GetIntValue(i, "cm_count") != 0)
			m_cListMonth.SetItemText(i, 1, oDbResult.GetTextValue(i, "cm_count"));
		if (oDbResult.GetIntValue(i, "ev_count") != 0)
			m_cListMonth.SetItemText(i, 2, oDbResult.GetTextValue(i, "ev_count"));
		if (oDbResult.GetIntValue(i, "tr_count") != 0)
			m_cListMonth.SetItemText(i, 3, oDbResult.GetTextValue(i, "tr_count"));
		if (oDbResult.GetIntValue(i, "kg_count") != 0)
			m_cListMonth.SetItemText(i, 4, oDbResult.GetTextValue(i, "kg_count"));
		if (oDbResult.GetIntValue(i, "ph_count") != 0)
			m_cListMonth.SetItemText(i, 5, oDbResult.GetTextValue(i, "ph_count"));
		if (oDbResult.GetIntValue(i, "gl_count") != 0)
			m_cListMonth.SetItemText(i, 6, oDbResult.GetTextValue(i, "gl_count"));
		if (!oDbResult.IsNull(i, "wk_count"))
			m_cListMonth.SetItemText(i, 7, oDbResult.GetTextValue(i, "wk_count"));

		if (oDbResult.GetBoolValue(i, "cm_search_flag"))
			m_pSearchFlag[i] |= 0x01;
		if (oDbResult.GetBoolValue(i, "ev_search_flag"))
			m_pSearchFlag[i] |= 0x02;
		if (oDbResult.GetBoolValue(i, "tr_search_flag"))
			m_pSearchFlag[i] |= 0x04;
		if (oDbResult.GetBoolValue(i, "kg_search_flag"))
			m_pSearchFlag[i] |= 0x08;
		if (oDbResult.GetBoolValue(i, "ph_search_flag"))
			m_pSearchFlag[i] |= 0x10;
		if (oDbResult.GetBoolValue(i, "gl_search_flag"))
			m_pSearchFlag[i] |= 0x20;
	}
}

void CLLogDlg::MakeLikeSearch(CString sSearch, CString &sCommentLike, CString &sPhotoLike, CString &sKeitaiGpsLike, CString &sGpsLoggerLike)
{
	sSearch.Replace("　", " ");
	CString sSearchLike = "";
	int nCurPos= 0;
	CString sResToken;
	while (true) {
		sResToken = sSearch.Tokenize(" ", nCurPos);
		if (sResToken == "")
			break;

		if (sSearchLike != "")
			sSearchLike += " AND ";

		sSearchLike += "\t ILIKE " + m_oDataBase.SqlText('%' + sResToken + '%');
	}

	sCommentLike = sSearchLike;
	sCommentLike.Replace("\t", "cm_comment");

	CString sPhotoLike1, sPhotoLike2;
	sPhotoLike1 = sSearchLike;
	sPhotoLike1.Replace("\t", "ph_folder_name");
	sPhotoLike2 = sSearchLike;
	sPhotoLike2.Replace("\t", "ph_comment");
	sPhotoLike.Format("%s OR %s", sPhotoLike1, sPhotoLike2);

	CString sKeitaiGpsLike1, sKeitaiGpsLike2;
	sKeitaiGpsLike1 = sSearchLike;
	sKeitaiGpsLike1.Replace("\t", "kg_address");
	sKeitaiGpsLike2 = sSearchLike;
	sKeitaiGpsLike2.Replace("\t", "kg_comment");
	sKeitaiGpsLike.Format("%s OR %s", sKeitaiGpsLike1, sKeitaiGpsLike2);

	sGpsLoggerLike = sSearchLike;
	sGpsLoggerLike.Replace("\t", "gc_comment");
}

void CLLogDlg::DispDayData()
{
	CString sSql;
	CDbResult oDbResult;
	CString sDate = m_oDataBase.SqlDate(m_oCurrentDate);
	int nRec, i;
	CString sText;

	sSql.Format("SELECT cm_comment FROM t_comment WHERE cm_date=%s ORDER BY cm_seq_no", sDate);
	if (m_oDataBase.Select(sSql, oDbResult))
		m_cEditComment.SetWindowText(oDbResult.GetCount() > 0 ? oDbResult.GetTextValue(0, "cm_comment") : "");

	sSql.Format("SELECT em_text FROM t_event JOIN m_event ON ev_event_id=em_event_id WHERE ev_date=%s ORDER BY ev_seq_no", sDate);
	if (m_oDataBase.Select(sSql, oDbResult)) {
		m_cListEvent.ResetContent();
		nRec = oDbResult.GetCount();
		for (i = 0; i < nRec; i++)
			m_cListEvent.AddString(oDbResult.GetTextValue(i, "em_text"));
		if (nRec == 0)
			m_cListEvent.AddString("");
	}

	sSql.Format("SELECT tr_from_line,tr_from_station,tr_to_line,tr_to_station FROM t_train WHERE tr_date=%s ORDER BY tr_seq_no", sDate);
	if (m_oDataBase.Select(sSql, oDbResult)) {
		m_cListTrain.ResetContent();
		nRec = oDbResult.GetCount();
		for (i = 0; i < nRec; i++) {
			sText.Format("%s %s → %s %s", oDbResult.GetTextValue(i, "tr_from_line"), oDbResult.GetTextValue(i, "tr_from_station"), oDbResult.GetTextValue(i, "tr_to_line"), oDbResult.GetTextValue(i, "tr_to_station"));
			m_cListTrain.AddString(sText);
		}
		if (nRec == 0)
			m_cListTrain.AddString("");
	}

	sSql.Format("SELECT kg_datetime,kg_address FROM t_keitai_gps WHERE kg_date=%s ORDER BY kg_seq_no", sDate);
	if (m_oDataBase.Select(sSql, oDbResult)) {
		m_cListKeitaiGps.ResetContent();
		nRec = oDbResult.GetCount();
		for (i = 0; i < nRec; i++) {
			CString sAddress = oDbResult.GetTextValue(i, "kg_address");
			if (sAddress.GetLength() == 0)
				sAddress = "不明";
			sText.Format("%s %s", CString(oDbResult.GetTextValue(i, "kg_datetime")).Mid(11, 5), sAddress);
			m_cListKeitaiGps.AddString(sText);
		}
		if (nRec == 0)
			m_cListKeitaiGps.AddString("");
	}

	sSql.Format("SELECT ph_seq_no,ph_file_name FROM t_photo WHERE ph_date=%s ORDER BY ph_datetime,ph_file_name", sDate);
	if (m_oDataBase.Select(sSql, oDbResult)) {
		m_cComboPhoto.ResetContent();
		nRec = oDbResult.GetCount();
		for (i = 0; i < nRec; i++) {
			m_cComboPhoto.SetItemData(m_cComboPhoto.AddString(oDbResult.GetTextValue(i, "ph_file_name")), oDbResult.GetIntValue(i, "ph_seq_no"));
		}
		if (nRec != 0)
			m_cComboPhoto.SetCurSel(0);
		OnCbnSelchangeComboPhoto();
	}

	sSql.Format("SELECT gl_seq_no,gl_file_name FROM t_gps_logger WHERE gl_date=%s ORDER BY gl_seq_no", sDate);
	if (m_oDataBase.Select(sSql, oDbResult)) {
		m_cComboGpsLogger.ResetContent();
		nRec = oDbResult.GetCount();
		for (i = 0; i < nRec; i++) {
			m_cComboGpsLogger.SetItemData(m_cComboGpsLogger.AddString(oDbResult.GetTextValue(i, "gl_file_name")), oDbResult.GetIntValue(i, "gl_seq_no"));
		}
		if (nRec != 0)
			m_cComboGpsLogger.SetCurSel(0);
		OnCbnSelchangeComboGpsLogger();
	}

	sSql.Format("SELECT wk_step,wk_calorie,wk_fat,wk_distance,wk_time,wk_speed FROM t_walking WHERE wk_date=%s", sDate);
	if (m_oDataBase.Select(sSql, oDbResult)) {
		if (oDbResult.GetCount() > 0) {
			m_cEditWalkingStep.SetWindowText(oDbResult.GetTextValue(0, "wk_step"));
			m_cEditWalkingCalorie.SetWindowText(oDbResult.GetTextValue(0, "wk_calorie"));
			m_cEditWalkingFat.SetWindowText(oDbResult.GetTextValue(0, "wk_fat"));
			m_cEditWalkingDistance.SetWindowText(oDbResult.GetTextValue(0, "wk_distance"));
			m_cEditWalkingTime.SetWindowText(oDbResult.GetTextValue(0, "wk_time"));
			m_cEditWalkingSpeed.SetWindowText(oDbResult.GetTextValue(0, "wk_speed"));
		} else {
			m_cEditWalkingStep.Blank();
			m_cEditWalkingCalorie.Blank();
			m_cEditWalkingFat.Blank();
			m_cEditWalkingDistance.Blank();
			m_cEditWalkingTime.Blank();
			m_cEditWalkingSpeed.Blank();
		}
	}
}

void CLLogDlg::OnLvnItemchangedListMonth(NMHDR *pNMHDR, LRESULT *pResult)
{
	LPNMLISTVIEW pNMLV = reinterpret_cast<LPNMLISTVIEW>(pNMHDR);

	if (pNMLV->uNewState & LVIS_SELECTED)
		DispData(pNMLV->lParam);

	*pResult = 0;
}

void CLLogDlg::OnMcnSelchangeCalendar(NMHDR *pNMHDR, LRESULT *pResult)
{
	LPNMSELCHANGE pSelChange = reinterpret_cast<LPNMSELCHANGE>(pNMHDR);

	if (CTime(pSelChange->stSelStart) != m_oCurrentDate) {
		BOOL bForce = FALSE;

		if (!m_cEditSearch.IsEmpty()) {
			m_cEditSearch.SetWindowText("");
			bForce = TRUE;
		}

		ChangeCalendar(bForce);
	}

	*pResult = 0;
}

void CLLogDlg::ChangeCalendar(BOOL bForce)
{
	CTime oDate;
	m_cCalendar.GetCurSel(oDate);

	DispData(oDate, bForce);
}

void CLLogDlg::OnCbnSelchangeComboPhoto()
{
	CString sPhotoNum;
	CString sPhotoFolder;
	CString sPhotoTime;

	int nIndex = m_cComboPhoto.GetCurSel();
	if (nIndex != CB_ERR) {
		int nSeqNo = m_cComboPhoto.GetItemData(nIndex);

		CString sSql;
		CDbResult oDbResult;

		sSql.Format("SELECT ph_date,ph_folder_name,ph_file_name,ph_datetime,ph_location_flag FROM t_photo WHERE ph_seq_no=%d", nSeqNo);
		if (m_oDataBase.Select(sSql, oDbResult)) {
			if (oDbResult.GetCount() != 0) {
				m_cStaticPhoto.DispJpeg(oDbResult.GetTextValue(0, "ph_folder_name"), oDbResult.GetTextValue(0, "ph_file_name"), oDbResult.GetDateValue(0, "ph_date"), FALSE);
			}
		}

		sPhotoNum.Format("%d/%d", nIndex + 1, m_cComboPhoto.GetCount());
		sPhotoFolder = oDbResult.GetTextValue(0, "ph_folder_name");
		sPhotoTime = CString(oDbResult.GetTextValue(0, "ph_datetime")).Right(8);
		m_cButtonPhotoMap.SetWindowText(oDbResult.IsNull(0, "ph_location_flag") ? "map" : ((CString)"map" + oDbResult.GetTextValue(0, "ph_location_flag")));
		m_cButtonPhotoMap.EnableWindow(TRUE);
	} else {
		m_cStaticPhoto.Clear();
		m_cButtonPhotoMap.SetWindowText("map");
		m_cButtonPhotoMap.EnableWindow(FALSE);
	}

	m_cButtonPhotoPrev.EnableWindow(nIndex != CB_ERR && nIndex > 0);
	m_cButtonPhotoNext.EnableWindow(nIndex != CB_ERR && nIndex < m_cComboPhoto.GetCount() - 1);
	m_cStaticPhotoNum.SetWindowText(sPhotoNum);
	m_cEditPhotoFolder = sPhotoFolder;
	m_cEditPhotoTime = sPhotoTime;
}

void CLLogDlg::OnBnClickedButtonGoogleEarth()
{
	int nIndex = m_cComboGpsLogger.GetCurSel();
	if (nIndex != CB_ERR) {
		int nSeqNo = m_cComboGpsLogger.GetItemData(nIndex);

		CString sSql;
		CDbResult oDbResult;

		sSql.Format("SELECT gl_point_data,gl_file_name,gl_point_num FROM t_gps_logger WHERE gl_seq_no=%d", nSeqNo);
		if (m_oDataBase.Select(sSql, oDbResult)) {
			CDbByteData oCDbByteData;
			oDbResult.GetByteaValue(0, "gl_point_data", oCDbByteData);
			CGpsData oGpsData;
			oGpsData.ReadGpsData(oCDbByteData, oDbResult.GetIntValue(0, "gl_point_num"));

			CString sTempPath;
			::GetTempPath(MAX_PATH, sTempPath.GetBuffer(MAX_PATH));
			CString sKmlFile;
			sKmlFile.Format("%s\\%s.kml", sTempPath, oDbResult.GetTextValue(0, "gl_file_name"));
			oGpsData.OutputKml(sKmlFile);

			::ShellExecute(NULL, "open", sKmlFile, NULL, NULL, SW_SHOWNORMAL);
		}
	}
}

void CLLogDlg::OnBnClickedButtonGoogleMap()
{
	int nIndex = m_cComboGpsLogger.GetCurSel();
	if (nIndex != CB_ERR) {
		int nSeqNo = m_cComboGpsLogger.GetItemData(nIndex);

		CString sUrl;
		sUrl.Format("%s/gl_gmap.php?seq_no=%d", URL_LLOG, nSeqNo);
		::ShellExecute(NULL, "open", sUrl, NULL, NULL, SW_SHOWNORMAL);
	}
}

void CLLogDlg::OnCbnSelchangeComboGpsLogger()
{
	int nIndex = m_cComboGpsLogger.GetCurSel();
	if (nIndex != CB_ERR) {
		int nSeqNo = m_cComboGpsLogger.GetItemData(nIndex);

		CString sSql;
		CDbResult oDbResult;

		sSql.Format("SELECT gl_point_data,gl_point_num FROM t_gps_logger WHERE gl_seq_no=%d", nSeqNo);
		if (m_oDataBase.Select(sSql, oDbResult)) {
			if (oDbResult.GetCount() != 0) {
				CDbByteData oCDbByteData;
				oDbResult.GetByteaValue(0, "gl_point_data", oCDbByteData);
				CGpsData oGpsData;
				oGpsData.ReadGpsData(oCDbByteData, oDbResult.GetIntValue(0, "gl_point_num"));
				m_cStaticGpsLogger.DispMap(0, 0, &oGpsData.m_oArrayGpsData);
			}
		}
		m_cButtonGoogleEarth.EnableWindow(TRUE);
		m_cButtonGoogleMap.EnableWindow(TRUE);
	} else {
		m_cStaticGpsLogger.Clear();
		m_cButtonGoogleEarth.EnableWindow(FALSE);
		m_cButtonGoogleMap.EnableWindow(FALSE);
	}
}

HBRUSH CLLogDlg::OnCtlColor(CDC* pDC, CWnd* pWnd, UINT nCtlColor)
{
	if (nCtlColor == CTLCOLOR_STATIC && pWnd->GetDlgCtrlID() == IDC_EDIT_COMMENT)
		return (HBRUSH)::GetStockObject( WHITE_BRUSH );


	return CDialog::OnCtlColor(pDC, pWnd, nCtlColor);
}

void CLLogDlg::OnStnDblclickEditComment()
{
	CDlgComment dlg;
	DispDialog(dlg);
}

void CLLogDlg::OnLbnDblclkListEvent()
{
	CDlgEvent dlg;
	DispDialog(dlg);
}

void CLLogDlg::OnLbnDblclkListKeitaiGps()
{
	CDlgKeitaiGps dlg;
	DispDialog(dlg);
}

void CLLogDlg::OnLbnDblclkListTrain()
{
	CDlgTrain dlg;
	DispDialog(dlg);
}

void CLLogDlg::OnStnDblclickStaticGpsLogger()
{
	CDlgGpsLogger dlg;
	DispDialog(dlg);
}

void CLLogDlg::OnStnDblclickStaticPhoto()
{
	CDlgPhoto dlg;
	dlg.m_nSelectIndex = m_cComboPhoto.GetCurSel();
	DispDialog(dlg);
}

void CLLogDlg::OnBnClickedButtonWalking()
{
	CDlgWalking dlg;
	DispDialog(dlg);
}

void CLLogDlg::DispDialog(CDlgCommon &dlg)
{
	if (dlg.DispDialog(m_oDataBase, m_oCurrentDate) == IDOK)
		DispData(m_oCurrentDate, TRUE);
}

void CLLogDlg::OnLbnDblclkListBoard()
{
	CDlgBoard dlg;
	if (dlg.DispDialog(m_oDataBase, m_oCurrentDate) == IDOK)
		DispBoard();
}

void CLLogDlg::OnNMCustomdrawListMonth(NMHDR *pNMHDR, LRESULT *pResult)
{
	LPNMLVCUSTOMDRAW pNMCD = reinterpret_cast<LPNMLVCUSTOMDRAW>(pNMHDR);
	CString sDate;

	switch(pNMCD->nmcd.dwDrawStage) {
	case CDDS_PREPAINT:
		*pResult = CDRF_NOTIFYITEMDRAW;
		break;
	case CDDS_ITEMPREPAINT:
		*pResult = CDRF_NOTIFYITEMDRAW;
		break;
	case CDDS_ITEMPREPAINT | CDDS_SUBITEM:
		*pResult = CDRF_DODEFAULT;

		if (pNMCD->iSubItem == 0) {
			CTime oDate(pNMCD->nmcd.lItemlParam);
			switch (oDate.GetDayOfWeek()) {
			case 1:
				pNMCD->clrText = RGB(0xff,0x00,0x00);
				break;
			case 7:
				pNMCD->clrText = RGB(0x00,0x00,0xff);
				break;
			default:
				pNMCD->clrText = RGB(0x00,0x00,0x00);
				break;
			}
		} else {
			if (m_pSearchFlag[pNMCD->nmcd.dwItemSpec] & (0x01 << (pNMCD->iSubItem - 1)))
				pNMCD->clrText = RGB(0xff,0x00,0x00);
			else
				pNMCD->clrText = RGB(0x00,0x00,0x00);
		}

		pNMCD->clrTextBk = pNMCD->nmcd.dwItemSpec % 2 ? RGB(255, 255, 255) : RGB(230, 255, 230);

		*pResult = CDRF_NEWFONT;
		break;
	default:
		*pResult = CDRF_DODEFAULT;
		break;
	}
}

void CLLogDlg::OnBnClickedButtonPhotoPrev()
{
	int nIndex = m_cComboPhoto.GetCurSel();
	if (nIndex != CB_ERR && nIndex > 0) {
		m_cComboPhoto.SetCurSel(--nIndex);
		OnCbnSelchangeComboPhoto();

		if (nIndex > 0)
			PreloadPhoto(nIndex - 1);
	}
}

void CLLogDlg::OnBnClickedButtonPhotoNext()
{
	int nIndex = m_cComboPhoto.GetCurSel();
	if (nIndex != CB_ERR && nIndex < m_cComboPhoto.GetCount() - 1) {
		m_cComboPhoto.SetCurSel(++nIndex);
		OnCbnSelchangeComboPhoto();

		if (nIndex < m_cComboPhoto.GetCount() - 1)
			PreloadPhoto(nIndex + 1);
	}
}

void CLLogDlg::PreloadPhoto(int nIndex)
{
	CString sSql;
	CDbResult oDbResult;

	int nSeqNo = m_cComboPhoto.GetItemData(nIndex);
	sSql.Format("SELECT ph_date,ph_folder_name,ph_file_name,ph_datetime FROM t_photo WHERE ph_seq_no=%d", nSeqNo);
	if (m_oDataBase.Select(sSql, oDbResult)) {
		if (oDbResult.GetCount() != 0) {
			m_cStaticPhoto.PreloadJpeg(oDbResult.GetTextValue(0, "ph_folder_name"), oDbResult.GetTextValue(0, "ph_file_name"), oDbResult.GetDateValue(0, "ph_date"), FALSE);
		}
	}
}

void CLLogDlg::OnBnClickedButtonSearch()
{
	if (m_cEditSearch.IsEmpty()) {
		CDlgSearch dlg;
		if (dlg.DoModal() == IDOK) {
			CString sFile, sUrl;
			switch (dlg.m_nSearchType) {
			case SEARCH_PHOTO:
				sFile = "ph_search.php";
				break;
			case SEARCH_GPS_LOGGER:
				sFile = "gl_search.php";
				break;
			case SEARCH_KEITAI_GPS:
				sFile = "kg_search.php";
				break;
			}
			sUrl.Format("%s/%s", URL_LLOG, sFile);
			::ShellExecute(NULL, "open", sUrl, NULL, NULL, SW_SHOWNORMAL);
		}
	} else {
		CString sSql;
		CString sSearch = m_cEditSearch;
		CString sSearchEqu = m_oDataBase.SqlText(sSearch);

		CString sCommentLike;
		CString sPhotoLike;
		CString sKeitaiGpsLike;
		CString sGpsLoggerLike;
		MakeLikeSearch(sSearch, sCommentLike, sPhotoLike, sKeitaiGpsLike, sGpsLoggerLike);

		sSql.Format("SELECT s1_date "
					"FROM ("
					 "SELECT cm_date AS s1_date FROM t_comment WHERE %s UNION "
					 "SELECT ev_date AS s1_date FROM t_event WHERE ev_event_id IN (SELECT em_event_id FROM m_event WHERE em_text=%s) UNION "
					 "SELECT tr_date AS s1_date FROM t_train WHERE tr_from_line=%s OR tr_from_station=%s OR tr_to_line=%s OR tr_to_station=%s UNION "
					 "SELECT ph_date AS s1_date FROM t_photo WHERE %s UNION "
					 "SELECT kg_date AS s1_date FROM t_keitai_gps WHERE %s"
					 ") T ", sCommentLike, sSearchEqu, sSearchEqu, sSearchEqu, sSearchEqu, sSearchEqu, sPhotoLike, sKeitaiGpsLike);

		CDbResult oDbResult;
		if (!m_oDataBase.Select(sSql, oDbResult))
			return;

		if (oDbResult.GetCount() != 0)
			DispData(oDbResult.GetDateValue(0, "s1_date"), TRUE);
		else {
			::AfxMessageBox("一致するデータはありません。", MB_OK | MB_ICONINFORMATION);
			m_cEditSearch.Blank();
			m_cEditSearch.SetFocus();
		}
	}
}

void CLLogDlg::DispBoard()
{
	CString sSql;
	CDbResult oDbResult;

	sSql = "SELECT bo_message FROM t_board WHERE bo_del_flag=FALSE ORDER BY bo_seq_no DESC";
	if (m_oDataBase.Select(sSql, oDbResult)) {
		m_cListBoard.ResetContent();
		int nRec = oDbResult.GetCount();
		for (int i = 0; i < nRec; i++) {
			m_cListBoard.AddString(oDbResult.GetTextValue(i, "bo_message"));
		}
		if (nRec == 0)
			m_cListBoard.AddString("");
	}
}

void CLLogDlg::OnBnClickedButtonPhotoMap()
{
	CString sUrl;

	int nIndex = m_cComboPhoto.GetCurSel();
	if (nIndex != CB_ERR) {
		int nSeqNo = m_cComboPhoto.GetItemData(nIndex);
		sUrl.Format("%s/ph_gmap.php?seq_no=%d", URL_LLOG, nSeqNo);
		::ShellExecute(NULL, "open", sUrl, NULL, NULL, SW_SHOWNORMAL);
	}
}

void CLLogDlg::OnLvnColumnclickListMonth(NMHDR *pNMHDR, LRESULT *pResult)
{
	LPNMLISTVIEW pNMLV = reinterpret_cast<LPNMLISTVIEW>(pNMHDR);

	m_nSortColumn = pNMLV->iSubItem;
	m_cListMonth.SortItems(CompareListMonth, (DWORD_PTR)this);

	*pResult = 0;
}

int CALLBACK CLLogDlg::CompareListMonth(LPARAM lParam1, LPARAM lParam2, LPARAM lParamSort)
{
	return ((CLLogDlg *)lParamSort)->CompareListMonth(lParam1, lParam2);
}

int CLLogDlg::CompareListMonth(LPARAM lParam1, LPARAM lParam2)
{
	if (m_nSortColumn == 0)
		return lParam1 - lParam2;
	else {
		LVFINDINFO lvi;
		lvi.flags = LVFI_PARAM;
		lvi.lParam = lParam1;
		int nIndex1 = m_cListMonth.FindItem(&lvi);
		lvi.lParam = lParam2;
		int nIndex2 = m_cListMonth.FindItem(&lvi);

		return atoi(m_cListMonth.GetItemText(nIndex2, m_nSortColumn)) - atoi(m_cListMonth.GetItemText(nIndex1, m_nSortColumn));
	}
}

void CLLogDlg::CheckWalking()
{
	CString sSql;
	CDbResult oDbResult;

	sSql = "SELECT MAX(wk_date) AS wk_max_date FROM t_walking";
	if (m_oDataBase.Select(sSql, oDbResult)) {
		CTime oTimeMax = oDbResult.GetDateValue(0, "wk_max_date");
		if (oTimeMax + CTimeSpan(12, 0, 0, 0) < CTime::GetCurrentTime()) {
			::AfxMessageBox("歩数計のデータを入力してください。", MB_OK | MB_ICONEXCLAMATION);
		}
	}
}

void CLLogDlg::OnBnClickedButtonWalkingSum()
{
	CString sSql;

	sSql.Format("SELECT SUM(wk_step) AS sum_step,SUM(wk_calorie) AS sum_calorie,SUM(wk_fat) AS sum_fat ,SUM(wk_distance) AS sum_distance,SUM(wk_time) AS sum_time "
				"FROM t_walking "
				"WHERE to_char(wk_date,'YYYYMM')=%s", m_oDataBase.SqlYM(m_oCurrentYM));

	CDbResult oDbResult;
	if (!m_oDataBase.Select(sSql, oDbResult))
		return;

	double distance = oDbResult.GetFloatValue(0, "sum_distance");
	double time = oDbResult.GetFloatValue(0, "sum_time");
	double speed = 0;
	if (time != 0)
		speed = distance / (time / 60);

	if (!OpenClipboard()){
		::AfxMessageBox("クリップボードが開けません");
		return;
	}
	EmptyClipboard();

	CString msg;
	msg.Format("%s\t%s\t%s\t%s\t%s\t%s",
			m_oCurrentYM.Format("%Y/%m/01"),
			oDbResult.GetTextValue(0, "sum_step"),
			oDbResult.GetTextValue(0, "sum_calorie"),
			oDbResult.GetTextValue(0, "sum_fat"),
			oDbResult.GetTextValue(0, "sum_distance"),
			oDbResult.GetTextValue(0, "sum_time"));
	DWORD dwCount = (DWORD)msg.GetLength() + 1;
	HGLOBAL hg = GlobalAlloc(GHND | GMEM_SHARE , dwCount);

	PTSTR strMem = (PTSTR)GlobalLock(hg);
	lstrcpy(strMem , msg);
	GlobalUnlock(hg);
	    
	SetClipboardData(CF_TEXT, hg);
	CloseClipboard();
	
	msg.Format("歩数　：%s 歩\nｶﾛﾘｰ ：%s kcal\n脂肪　：%s g\n距離　：%s km\n時間　：%s min\n速度　：%.2f km/h",
			oDbResult.GetTextValue(0, "sum_step"),
			oDbResult.GetTextValue(0, "sum_calorie"),
			oDbResult.GetTextValue(0, "sum_fat"),
			oDbResult.GetTextValue(0, "sum_distance"),
			oDbResult.GetTextValue(0, "sum_time"),
			speed);
	::MessageBox(m_hWnd, msg, "歩数合計", MB_OK);
}
