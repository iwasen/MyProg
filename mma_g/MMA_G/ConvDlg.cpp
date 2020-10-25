// ConvDlg.cpp : 実装ファイル
//

#include "stdafx.h"
#include "MMA_G.h"
#include "GlobalData.h"
#include "ConvDlg.h"
#include "General.h"
#include "Complement.h"
#include "shlwapi.h"

// CConvDlg ダイアログ

IMPLEMENT_DYNAMIC(CConvDlg, CDialog)

CConvDlg::CConvDlg(CWnd* pParent /*=NULL*/)
	: CDialog(CConvDlg::IDD, pParent)
	, m_MaiPath(_T(""))
	, m_pDoc(NULL)
	, m_GoEnable(false)
{
}

CConvDlg::~CConvDlg()
{
}

void CConvDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_EDIT_MAI, m_Edit_ref);
	DDX_Control(pDX, IDC_EDIT_UNITID, m_Edit_UnitId);
	DDX_Control(pDX, IDC_EDIT_SENSORID, m_Edit_SensorId);
	DDX_Control(pDX, IDC_EDIT_SENSORID2, m_Edit_SampleRate);
	DDX_Control(pDX, IDC_EDIT_START, m_Edit_StartTime);
	DDX_Control(pDX, IDC_EDIT_END, m_Edit_EndTime);
	DDX_Control(pDX, IDC_BUTTON_CONV, m_Button_Go);
	DDX_Control(pDX, IDCANCEL, m_Button_Cancel);
	DDX_Control(pDX, IDC_BUTTON_REF, m_Button_Ref);
	DDX_Control(pDX, IDC_COMBO_SENSOR, m_Combo_Sensor);
	DDX_Control(pDX, IDC_EDIT_QUALITY, m_Edit_Quality);
	DDX_Control(pDX, IDC_EDIT_ISS, m_Edit_Config);
	DDX_Control(pDX, IDC_EDIT_ISS2, m_Edit_Config2);
	DDX_Control(pDX, IDC_EDIT_COMMENT, m_Edit_Comment);
	DDX_Control(pDX, IDC_EDIT_DOWNLINK, m_Downlink);
	DDX_Control(pDX, IDC_COMBO_CATEGORY, m_Combo_Category);
	DDX_Control(pDX, IDC_COMBO_MEASUREMENT_KIND, m_Combo_MeasurementKind);
	DDX_Control(pDX, IDC_MEAS_TIME, m_Edit_MeasTime);
}


BEGIN_MESSAGE_MAP(CConvDlg, CDialog)
	ON_BN_CLICKED(IDC_BUTTON_CONV, &CConvDlg::OnBnClickedButtonConv)
	ON_BN_CLICKED(IDC_BUTTON_REF, &CConvDlg::OnBnClickedButtonRef)
	ON_BN_CLICKED(IDCANCEL, &CConvDlg::OnBnClickedCancel)
	ON_EN_UPDATE(IDC_EDIT_DOWNLINK, &CConvDlg::OnEnUpdateEditDownlink)
	ON_EN_UPDATE(IDC_EDIT_QUALITY, &CConvDlg::OnEnUpdateEditQuality)
	ON_EN_UPDATE(IDC_EDIT_ISS, &CConvDlg::OnEnUpdateEditIss)
	ON_EN_UPDATE(IDC_EDIT_S_YY, &CConvDlg::OnEnUpdateEditSYy)
	ON_EN_UPDATE(IDC_EDIT_S_MM, &CConvDlg::OnEnUpdateEditSMm)
	ON_EN_UPDATE(IDC_EDIT_S_DD, &CConvDlg::OnEnUpdateEditSDd)
	ON_EN_UPDATE(IDC_EDIT_S_H, &CConvDlg::OnEnUpdateEditSH)
	ON_EN_UPDATE(IDC_EDIT_S_M, &CConvDlg::OnEnUpdateEditSM)
	ON_EN_UPDATE(IDC_EDIT_S_S, &CConvDlg::OnEnUpdateEditSS)
	ON_EN_UPDATE(IDC_EDIT_E_YY, &CConvDlg::OnEnUpdateEditEYy)
	ON_EN_UPDATE(IDC_EDIT_E_MM, &CConvDlg::OnEnUpdateEditEMm)
	ON_EN_UPDATE(IDC_EDIT_E_DD, &CConvDlg::OnEnUpdateEditEDd)
	ON_EN_UPDATE(IDC_EDIT_E_H, &CConvDlg::OnEnUpdateEditEH)
	ON_EN_UPDATE(IDC_EDIT_E_M, &CConvDlg::OnEnUpdateEditEM)
	ON_EN_UPDATE(IDC_EDIT_E_S, &CConvDlg::OnEnUpdateEditES)
	ON_CBN_SELCHANGE(IDC_COMBO_SENSOR, &CConvDlg::OnCbnSelchangeComboSensor)
	ON_CBN_EDITCHANGE(IDC_COMBO_SENSOR, &CConvDlg::OnCbnEditchangeComboSensor)
	ON_EN_UPDATE(IDC_EDIT_ISS2, &CConvDlg::OnEnUpdateEditIss2)
	ON_CBN_SELCHANGE(IDC_COMBO_CATEGORY, &CConvDlg::OnCbnSelchangeComboCategory)
	ON_CBN_EDITCHANGE(IDC_COMBO_CATEGORY, &CConvDlg::OnCbnEditchangeComboCategory)
	ON_CBN_SELCHANGE(IDC_COMBO_MEASUREMENT_KIND, &CConvDlg::OnCbnSelchangeComboMeasurementKind)
	ON_CBN_EDITCHANGE(IDC_COMBO_MEASUREMENT_KIND, &CConvDlg::OnCbnEditchangeComboMeasurementKind)
END_MESSAGE_MAP()

// ツールチップ表示データ
static const SToolTipData s_aToolTip[] = {
	{IDC_BUTTON_REF, "ダウンリンクデータの存在するフォルダを選択するためのダイアログを開きます。"},
	{IDC_BUTTON_CONV, "設定された内容で、工学値変換を実行します。"},
	{IDCANCEL, "このダイアログを閉じます。"},
	{0, NULL}
};

// CConvDlg メッセージ ハンドラ

// ダイアログ初期化関数
BOOL CConvDlg::OnInitDialog()
{
	CDialog::OnInitDialog();

	int	i;

	for (i = 0; g_pSensorInfo[i].idx != -1; i++) {
		m_Combo_Sensor.InsertString(g_pSensorInfo[i].idx, g_pSensorInfo[i].sel_dat);
	}

	// 初期は実行ボタンGRAY
	m_Button_Go.EnableWindow(m_GoEnable);

	// ツールチップ表示設定
	CGeneral::SetToolTip(m_tooltip, s_aToolTip, this);

	// カテゴリ選択肢を設定
	CGeneral::SetComboCategory(m_Combo_Category, "");

	// 計測種類選択肢を設定
	CGeneral::SetComboMeasurementKind(m_Combo_MeasurementKind, "");

	return TRUE;
}

// 処理中にGRAYにする
void CConvDlg::KeyOff(void)
{
	m_Button_Go.EnableWindow(FALSE);
	m_Button_Cancel.EnableWindow(FALSE);
	m_Button_Ref.EnableWindow(FALSE);
}

// GRAYを解除する
void CConvDlg::KeyOn(void)
{
	m_Button_Go.EnableWindow(m_GoEnable);
	m_Button_Cancel.EnableWindow(TRUE);
	m_Button_Ref.EnableWindow(TRUE);
}

// 参照ボタン押下処理
void CConvDlg::OnBnClickedButtonRef()
{
    BROWSEINFO bInfo;
    LPITEMIDLIST pIDList;
	char	Buf[_MAX_PATH];
    
	strcpy_s(Buf, gDownlinkReferencePath);
    // BROWSEINFO構造体に値を設定
    bInfo.hwndOwner             = m_hWnd;		           // ダイアログの親ウインドウのハンドル
    bInfo.pidlRoot              = NULL;			           // ルートフォルダを示すITEMIDLISTのポインタ (NULLの場合デスクトップフォルダが使われます）
    bInfo.pszDisplayName        = Buf;          // 選択されたフォルダ名を受け取るバッファのポインタ
    bInfo.lpszTitle             = "ダウンリンクファイル存在フォルダ指定";				   // ツリービューの上部に表示される文字列 
    bInfo.ulFlags               = BIF_RETURNONLYFSDIRS;    // 表示されるフォルダの種類を示すフラグ
    bInfo.lpfn                  = BrowseCallbackProc;      // BrowseCallbackProc関数のポインタ
    bInfo.lParam                = (LPARAM)Buf;             // コールバック関数に渡す値
    
    // フォルダ選択ダイアログを表示
    pIDList = ::SHBrowseForFolder(&bInfo);
    if(pIDList != NULL){
        // ItemIDListをパス名に変換します
        if(!::SHGetPathFromIDList(pIDList, Buf)){
            // エラー処理
        }

        // 最後にpIDListのポイントしているメモリを解放します
        ::CoTaskMemFree( pIDList );
		m_Edit_ref.SetWindowText(Buf);

		// maiファイル読み込み処理
		Read();
    }
}

// フォルダ選択ダイアログコールバック関数
int CALLBACK CConvDlg::BrowseCallbackProc(HWND hwnd, UINT uMsg, LPARAM lParam, LPARAM lpData)
{
	if (uMsg == BFFM_INITIALIZED)
		::SendMessage(hwnd, BFFM_SETSELECTION, (WPARAM)TRUE, lpData);

	return 0;
}

// maiファイル読み込み処理
void CConvDlg::Read()
{
	CWaitCursor cursor;

	KeyOff();

	m_Edit_ref.GetWindowText(m_MaiPath);

	// 指定パスのmaiファイルを読み、開始時刻終了時刻をセットする
	if (m_maiFile.ReadMaiFile((LPCSTR)m_MaiPath, &m_startTime, &m_endTime) != 0) {
		// ファイル内容表示処理
		DspData();
	} else {
		m_Edit_ref.SetWindowText("");
	}

	KeyOn();
}

// ファイル内容表示処理
void CConvDlg::DspData(void)
{
	CString	str;

	// UnitId表示
	str.Format("%d", m_maiFile.m_RsuId);
	m_Edit_UnitId.SetWindowText(str);

	// Sensor Id表示
	str.Format("%d", m_maiFile.m_SensorId);
	m_Edit_SensorId.SetWindowText(str);

	// サンプルレート表示
	str.Format("%.2lf", m_maiFile.m_SampleRate);
	m_Edit_SampleRate.SetWindowText(str);

	// 開始時刻表示
	str.Format("%04d/%02d/%02d %02d:%02d:%02d"
		, m_startTime.m_YY, m_startTime.m_MM, m_startTime.m_DD
		, m_startTime.m_hh, m_startTime.m_mm, m_startTime.m_ss);
	m_Edit_StartTime.SetWindowText(str);

	// 終了時刻表示
	str.Format("%04d/%02d/%02d %02d:%02d:%02d"
		, m_endTime.m_YY, m_endTime.m_MM, m_endTime.m_DD
		, m_endTime.m_hh, m_endTime.m_mm, m_endTime.m_ss);
	m_Edit_EndTime.SetWindowText(str);

	// 計測時間表示
	COleDateTime a_oDateStart(m_startTime.m_YY, m_startTime.m_MM, m_startTime.m_DD
							, m_startTime.m_hh, m_startTime.m_mm, m_startTime.m_ss);
	COleDateTime a_oDateEnd(m_endTime.m_YY, m_endTime.m_MM, m_endTime.m_DD
							, m_endTime.m_hh, m_endTime.m_mm, m_endTime.m_ss);
	COleDateTimeSpan a_oMeasTime = a_oDateEnd - a_oDateStart;

	// 1月や1年間も計測を続けていることは考えにくいので，ここでは
	// 最大でも，何日間かのデータしかないと仮定する。
	// （年や月のことは考えない）
	LONG a_lDays = a_oMeasTime.GetDays();
	LONG a_lHour = a_oMeasTime.GetHours();
	LONG a_lMin  = a_oMeasTime.GetMinutes();
	LONG a_lSec  = a_oMeasTime.GetSeconds();

	if(a_lDays > 0){
		a_lHour += a_lDays * 24;
	}

	str.Format("%dhours  %02dmin  %02dsec", a_lHour, a_lMin, a_lSec);
	m_Edit_MeasTime.SetWindowText(str);

}

// 変換実行ボタン処理
void CConvDlg::OnBnClickedButtonConv()
{
	CWaitCursor cursor;

	// 入力データ取得、チェック、データ保存
	if (GetInputData() != 0)
		return;

	// Event Logsフォルダ存在チェック
	if (!::PathFileExists(m_MaiPath + "\\Event Logs")) {
		if (AfxMessageBox("Event Logsフォルダがありません。変換処理を継続しますか？", MB_YESNO | MB_ICONQUESTION) == IDNO) {
			AfxMessageBox("変換処理を中止します。\nEvent Logsフォルダをコピーしてから再度実行してください。", MB_OK | MB_ICONINFORMATION);
			return;
		}
	}

	if (AfxMessageBox("変換処理を実行しますか？", MB_YESNO) != IDYES)
		return;

	KeyOff();

	// 補間処理
	Complement complement;
	if (complement.ComplementData(m_MaiPath, m_maiFile.m_maiFname)) {
		// Data Quality Measureにメッセージ追加
		m_maiFile.m_Idb.m_dataquality += ", Termperature Data Error was found and complemented.";

		// maiファイル再読み込み
		m_maiFile.ReadMaiFile2(m_MaiPath + "\\" + m_maiFile.m_maiFname, TRUE);
	}

	// 指定時刻の出力ファイルを書き込む
	m_maiFile.WriteData((LPCSTR)m_MaiPath, 0, m_endTime - m_startTime);

	m_pDoc->UpdateAllViews(NULL);

	AfxMessageBox("工学値変換が終了しました", MB_ICONINFORMATION | MB_OK);

	KeyOn();
}

// 入力項目を取得する
int CConvDlg::GetInputData(void)
{
	CString str, str2;

	UpdateData(TRUE);

	// ダウンリンクデータ指定チェック
	m_Edit_ref.GetWindowText(str);
	if (str.GetLength() == 0) {
		CGeneral::Alert("ダウンリンクデータを指定してください。");
		return 1;
	}

	// Sensor Coordinate System取得
	int	sidx;
	if ((sidx = m_Combo_Sensor.GetCurSel()) == -1) {
		CGeneral::Alert("Sensor Coordinate System：いずれか選択してください。");
		return 5;
	}

	// カテゴリを取得
	m_Combo_Category.GetWindowText(str);
	str = str.Trim();
	if( str == _T("") ){
		CGeneral::Alert("カテゴリを選択してください。");
		return 11;
	}
	if (!CGeneral::CheckFileName("カテゴリ", str))
		return 11;

	// 計測種類を取得
	m_Combo_MeasurementKind.GetWindowText(str);
	str = str.Trim();
	if( str == _T("") ){
		CGeneral::Alert("計測種類を選択してください。");
		return 12;
	}
	if (!CGeneral::CheckFileName("計測種類", str))
		return 12;

	// ダウンリンク日文字列を取得
	m_Downlink.GetWindowText(str);
	str = str.Trim();
	// ダウンリンク日が空欄の場合は，エラーとする。
	if( str == _T("") ){
		CGeneral::Alert("ダウンリンク日が空欄です。ダウンリンク日を入力してください。");
		return 6;
	}
/*
	// Quality Measure文字列を取得
	m_Edit_Quality.GetWindowText(str);
	str = str.Trim();
	// Data Quality Measureが空欄の場合は，エラーとする。
	if( str == _T("") ){
		CGeneral::Alert("Data Quality Measureが空欄です。Data Quality Measureを入力してください。");
		return 7;
	}

	// ISS Config文字列を取得
	m_Edit_Config.GetWindowText(str);
	str = str.Trim();
	// ISS Configurationが空欄の場合は，エラーとする。
	if( str == _T("") ){
		CGeneral::Alert("ISS ConfigurationのIncrementが空欄です。ISS ConfigurationのIncrementを入力してください。");
		return 8;
	}
	m_Edit_Config2.GetWindowText(str);
	str = str.Trim();
	// ISS Configurationが空欄の場合は，エラーとする。
	if( str == _T("") ){
		CGeneral::Alert("ISS ConfigurationのFlightが空欄です。ISS ConfigurationのFlightを入力してください。");
		return 9;
	}
*/
	// 工学値データ書き込みフォルダ作成
	CString	pos, category, period, measurement_kind;
//	pos = SensorInfo[sidx].dat;
	pos = g_pSensorInfo[sidx].dat;
	m_Combo_Category.GetWindowText(category);
	m_Combo_MeasurementKind.GetWindowText(measurement_kind);
	period.Format("%04d%02d%02d%02d%02d%02d-%04d%02d%02d%02d%02d%02d"
		, m_startTime.m_YY
		, m_startTime.m_MM
		, m_startTime.m_DD
		, m_startTime.m_hh
		, m_startTime.m_mm
		, m_startTime.m_ss
		, m_endTime.m_YY
		, m_endTime.m_MM
		, m_endTime.m_DD
		, m_endTime.m_hh
		, m_endTime.m_mm
		, m_endTime.m_ss);
	if(m_maiFile.mkDir(pos, category, measurement_kind)){
		return 10;
	}

	// 入力データを保存
	// 情報クリア
	m_maiFile.m_Idb.DelAll();
	// センサーIDX
	m_maiFile.m_Idb.SetSensorIdx(sidx);
	// データ範囲
	m_maiFile.m_Idb.SetPeriod(period);
	// ダウンリンク日
	m_Downlink.GetWindowText(str);
	m_maiFile.m_Idb.SetDownlink(str);
	// ネットワークID（UNIT ID上位４桁）
	m_maiFile.m_Idb.SetNetworkid(m_maiFile.m_NetworkId);
	// ユニットID（UNIT ID下位４桁）
	m_maiFile.m_Idb.SetRsuId(m_maiFile.m_RsuId);
	// センサーID
	m_maiFile.m_Idb.SetSensorId(m_maiFile.m_SensorId);
	// タイムゼロ
	str.Format("%04d%02d%02d%02d%02d%02d"
		, m_startTime.m_YY
		, m_startTime.m_MM
		, m_startTime.m_DD
		, m_startTime.m_hh
		, m_startTime.m_mm
		, m_startTime.m_ss);
	m_maiFile.m_Idb.SetTimeZero(str);
	// サンプルレート
	m_maiFile.m_Idb.SetSampleRate(m_maiFile.m_SampleRate);
	// 保存パス名
	m_maiFile.m_Idb.SetPath(m_maiFile.m_ConvDataPath);
	// Sensor Co Sys
	m_maiFile.m_Idb.SetSensorcosys(pos);
	// Data Co Sys
	m_maiFile.m_Idb.SetDatacosys(pos);
	// Data Quality
	m_Edit_Quality.GetWindowText(str);
	m_maiFile.m_Idb.SetDataquality(str);
	// ISS Config
	m_Edit_Config.GetWindowText(str);
	m_Edit_Config2.GetWindowText(str2);
	CString str3;
	if (!str.IsEmpty() || !str2.IsEmpty()) {
		str3 = "Increment: ";
		str3 += str;
		str3 += ", Flight: ";
		str3 += str2;
	} else
		str3.Empty();
	m_maiFile.m_Idb.SetIssconfig(str3);
	// コメント
	m_Edit_Comment.GetWindowText(str);
	m_maiFile.m_Idb.SetComment(str);
	// カテゴリ
	m_Combo_Category.GetWindowText(str);
	m_maiFile.m_Idb.SetCategory(str);
	// 計測種類
	m_Combo_MeasurementKind.GetWindowText(str);
	m_maiFile.m_Idb.SetMeasurementKind(str);

	// スタートタイムYYYY（年）MM(月)DD(日)SSSSSSSS（ミリ秒）
	m_maiFile.m_Idb.SetStartTime(
		m_startTime.m_YY
		, m_startTime.m_MM
		, m_startTime.m_DD
		, (m_startTime.m_hh * 60 * 60 + m_startTime.m_mm * 60 + m_startTime.m_ss) * 1000);

	// 入力開始時間を引き渡す
	m_maiFile.m_In_startTime = m_startTime;

	return 0;
}

bool CConvDlg::CheckData(void)
{
	CString str;

	UpdateData(TRUE);

	// ダウンリンクデータ指定チェック
	m_Edit_ref.GetWindowText(str);
	if (str.IsEmpty())
		return false;

	// Sensor Coordinate System取得
	if (m_Combo_Sensor.GetCurSel() == -1)
		return false;

	// カテゴリを取得
	m_Combo_Category.GetWindowText(str);
	if (str.IsEmpty())
		return false;

	// 計測種類を取得
	m_Combo_MeasurementKind.GetWindowText(str);
	if (str.IsEmpty())
		return false;

	// ダウンリンク日文字列を取得
	m_Downlink.GetWindowText(str);
	if (str.IsEmpty())
		return false;
/*
	// Quality Measure文字列を取得
	m_Edit_Quality.GetWindowText(str);
	if (str.IsEmpty())
		return false;

	// ISS Config文字列を取得
	m_Edit_Config.GetWindowText(str);
	if (str.IsEmpty())
		return false;

	// ISS Config文字列を取得
	m_Edit_Config2.GetWindowText(str);
	if (str.IsEmpty())
		return false;
*/
	return true;
}

void CConvDlg::OnBnClickedCancel()
{
	// データリセット
	m_maiFile.ResetData();

	OnCancel();
}

void CConvDlg::SetDocPtr(CDocument *pDoc)
{
	m_pDoc = pDoc;
}

void CConvDlg::OnOK()
{
	// TODO: ここに特定なコードを追加するか、もしくは基本クラスを呼び出してください。
	// 何もしないようにする
}

void CConvDlg::UpdateGoButton(void)
{
	m_GoEnable = CheckData();

	m_Button_Go.EnableWindow(m_GoEnable);
}

void CConvDlg::OnEnUpdateEditDownlink()
{
	UpdateGoButton();
}
void CConvDlg::OnEnUpdateEditQuality()
{
	UpdateGoButton();
}

void CConvDlg::OnEnUpdateEditIss()
{
	UpdateGoButton();
}

void CConvDlg::OnEnUpdateEditIss2()
{
	UpdateGoButton();
}

void CConvDlg::OnEnUpdateEditSYy()
{
	UpdateGoButton();
}

void CConvDlg::OnEnUpdateEditSMm()
{
	UpdateGoButton();
}

void CConvDlg::OnEnUpdateEditSDd()
{
	UpdateGoButton();
}

void CConvDlg::OnEnUpdateEditSH()
{
	UpdateGoButton();
}

void CConvDlg::OnEnUpdateEditSM()
{
	UpdateGoButton();
}

void CConvDlg::OnEnUpdateEditSS()
{
	UpdateGoButton();
}

void CConvDlg::OnEnUpdateEditEYy()
{
	UpdateGoButton();
}

void CConvDlg::OnEnUpdateEditEMm()
{
	UpdateGoButton();
}

void CConvDlg::OnEnUpdateEditEDd()
{
	UpdateGoButton();
}

void CConvDlg::OnEnUpdateEditEH()
{
	UpdateGoButton();
}

void CConvDlg::OnEnUpdateEditEM()
{
	UpdateGoButton();
}

void CConvDlg::OnEnUpdateEditES()
{
	UpdateGoButton();
}

void CConvDlg::OnCbnSelchangeComboSensor()
{
	UpdateGoButton();
}

void CConvDlg::OnCbnEditchangeComboSensor()
{
	UpdateGoButton();
}

void CConvDlg::OnCbnSelchangeComboCategory()
{
	UpdateGoButton();
}

void CConvDlg::OnCbnEditchangeComboCategory()
{
	UpdateGoButton();
}

void CConvDlg::OnCbnSelchangeComboMeasurementKind()
{
	UpdateGoButton();
}

void CConvDlg::OnCbnEditchangeComboMeasurementKind()
{
	UpdateGoButton();
}

//*****************************************************************************************************
//  1. 関数名
//		CConvDlg::PreTranslateMessage
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ウィンドウメッセージ処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		MSG*	pMsg			[I] MSG構造体へのポインタ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL	親クラスの返値をそのまま返す
//*****************************************************************************************************
BOOL CConvDlg::PreTranslateMessage(MSG* pMsg)
{
	// ツールチップを表示するための処理
	switch(pMsg->message){
	case WM_LBUTTONDOWN:            
	case WM_LBUTTONUP:              
	case WM_MOUSEMOVE:
		if (m_tooltip.m_hWnd)
			m_tooltip.RelayEvent(pMsg);
		break;
	}

	return CDialog::PreTranslateMessage(pMsg);
}
