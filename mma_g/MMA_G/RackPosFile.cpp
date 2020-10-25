#include "StdAfx.h"
#include "RackPosFile.h"
#include "string.h"
#include "General.h"


#define DATA_KIND_NUM		0
#define DATA_KIND_SEL_STR	1
#define DATA_KIND_DISP_STR	2
#define DATA_KIND_DIRX_STR	3
#define DATA_KIND_DIRY_STR	4
#define DATA_KIND_DIRZ_STR	5
#define DATA_KIND_POS_X		6
#define DATA_KIND_POS_Y		7
#define DATA_KIND_POS_Z		8
// 2008/4/25 appended by yG for rpw addition ->
#define	DATA_KIND_POS_ROLL	9
#define	DATA_KIND_POS_PITCH	10
#define	DATA_KIND_POS_YAW	11
// <- 2008/4/25 appended by yG for rpw addition
#define DATA_KIND_RSU_ID	12
#define DATA_KIND_CATEGORY	13
#define DATA_KIND_MEASUREMENT_KIND	14

#define ERR_ID_MAX			7
static CString strErrMsg[ERR_ID_MAX] = 
{
	"No Error",
	"ファイルヘッダ1が不正です。",
	"ファイルヘッダ2が不正です。",
	"ファイルヘッダで定義されている数と，記述されているデータの数が違います。",
	"未定義のデータが記述されています。",
	"データの構文が不正です。",
	"Rack位置定義ファイルがありません。"
};

CRackPosFile::CRackPosFile(void)
: m_uiDefixxxount(0)
, m_bIsHeader1Read(false)
, m_bIsHeader2Read(false)
, m_iDefCounter(0)
{
	g_pSensorInfo = NULL;
}

CRackPosFile::~CRackPosFile(void)
{
}

int CRackPosFile::ReadFile(void)
{

	CStdioFile a_pFile;
	CFileException e;
	if(!a_pFile.Open(RACK_POS_FILE_NAME,CFile::modeRead,&e)){
		return 6;
	}

	CString a_strLineData;

	int iRet = 0;

	while(a_pFile.ReadString(a_strLineData)){
		a_strLineData = a_strLineData.Trim();
		
		// 空白行は無視
		if( a_strLineData == "" ){
			continue;
		}
		// 1文字目がコメント文字(;)の場合は無視
		if( a_strLineData.Mid(0,1) == COMMENT_STRING ){
			continue;
		}
		// 行データの中に，ファイルヘッダ1情報有り
		else if( a_strLineData.Find(FILE_HEADER_1) != -1 ){
			m_bIsHeader1Read = true;
		}
		// 行データの中に，ファイルヘッダ2情報有り
		else if( a_strLineData.Find(FILE_HEADER_2) != -1){
			if( m_bIsHeader1Read ){
				InterpretHeader2(a_strLineData);
				if( m_uiDefixxxount == 0 ){
					break;
				}
				g_pSensorInfo = new SENSOR_INFO[m_uiDefixxxount+1];
				g_iInfoNum = m_uiDefixxxount+1;
				m_bIsHeader2Read = true;
				MakeLastData();
			}
			else{
				return 1;
			}
		}
		// その他のデータ行 → 行を解析
		else{
			if( !m_bIsHeader1Read ){
				return 1;
			}

			if( m_bIsHeader2Read ){
				iRet = InterpretData(a_strLineData);
				if(iRet != 0){
					return iRet;
				}
			}
			else{
				return 2;
			}
		}
	}

	if(!m_bIsHeader1Read){
		return 1;
	}

	if(!m_bIsHeader2Read){
		return 2;
	}

	// 実際に読み込んだ数と，ヘッダで定義されている数が違う
	if( m_iDefCounter != m_uiDefixxxount ){
		return 3;
	}
	
	return 0;
}

int CRackPosFile::InterpretHeader2(CString in_strLineData)
{
	in_strLineData = DeleteCommentString(in_strLineData);

	int a_iIndex = in_strLineData.Find("=");

	in_strLineData = in_strLineData.Mid(a_iIndex+1);
	in_strLineData = in_strLineData.Trim();

	m_uiDefixxxount = atoi(in_strLineData);

	return 0;
}

CString CRackPosFile::DeleteCommentString(CString in_strLineData)
{
	CString a_retString;
	a_retString = in_strLineData;

	int a_iIndex = in_strLineData.Find(COMMENT_STRING);

	if( a_iIndex != -1 ){
		a_retString = in_strLineData.Left(a_iIndex);
	}

	return a_retString;
}

int CRackPosFile::InterpretData(CString in_strLineData)
{
	in_strLineData = DeleteCommentString(in_strLineData);

	int a_iDataKind = GetDataKind(in_strLineData);
	// エラー(未定義のデータ)
	if(a_iDataKind == -1){
		return 4;
	}
	
	int a_iIndex = in_strLineData.Find("=");
	in_strLineData = in_strLineData.Mid(a_iIndex+1);
	// エラー(構文が間違っている)
	if( in_strLineData == -1 ){
		return 5;
	}

	in_strLineData = in_strLineData.Trim();
	int iRet = InterpretDataByDataType(a_iDataKind,in_strLineData);

	return iRet;
}

int CRackPosFile::GetDataKind(CString in_strLineData)
{
	if( in_strLineData.Find(RACK_POS_NUM) != -1 ){
		return DATA_KIND_NUM;
	}
	else if( in_strLineData.Find(RACK_POS_SEL_STR) != -1 ){
		return DATA_KIND_SEL_STR;
	}
	else if( in_strLineData.Find(RACK_POS_DISP_STR) != -1 ){
		return DATA_KIND_DISP_STR;
	}
	else if( in_strLineData.Find(RACK_POS_DIRX_STR) != -1 ){
		return DATA_KIND_DIRX_STR;
	}
	else if( in_strLineData.Find(RACK_POS_DIRY_STR) != -1 ){
		return DATA_KIND_DIRY_STR;
	}
	else if( in_strLineData.Find(RACK_POS_DIRZ_STR) != -1 ){
		return DATA_KIND_DIRZ_STR;
	}
	else if( in_strLineData.Find(RACK_POS_X) != -1 ){
		return DATA_KIND_POS_X;
	}
	else if( in_strLineData.Find(RACK_POS_Y) != -1 ){
		return DATA_KIND_POS_Y;
	}
	else if( in_strLineData.Find(RACK_POS_Z) != -1 ){
		return DATA_KIND_POS_Z;
	}
// 2008/4/25 appended by yG for rpw addition ->
	else if( in_strLineData.Find(RACK_POS_ROLL) != -1 ){
		return DATA_KIND_POS_ROLL;
	}
	else if( in_strLineData.Find(RACK_POS_PITCH) != -1 ){
		return DATA_KIND_POS_PITCH;
	}
	else if( in_strLineData.Find(RACK_POS_YAW) != -1 ){
		return DATA_KIND_POS_YAW;
	}
// <- 2008/4/25 appended by yG for rpw addition
	else if (in_strLineData.Find(RACK_POS_RSU_ID) != -1) {
		return DATA_KIND_RSU_ID;
	}
	else if (in_strLineData.Find(RACK_POS_CATEGORY) != -1) {
		return DATA_KIND_CATEGORY;
	}
	else if (in_strLineData.Find(RACK_POS_MEASUREMENT_KIND) != -1) {
		return DATA_KIND_MEASUREMENT_KIND;
	}
	else{
		return -1;
	}
}

int CRackPosFile::InterpretDataByDataType(int in_iDataType, CString in_strLineData)
{
	switch(in_iDataType){
		case DATA_KIND_NUM:
			m_iDefCounter++;
			if( m_iDefCounter > (int)m_uiDefixxxount ){
				return 3;
			}
			g_pSensorInfo[m_iDefCounter-1].idx = atoi(in_strLineData);
			break;
		case DATA_KIND_SEL_STR:
			g_pSensorInfo[m_iDefCounter-1].sel_dat = new char[in_strLineData.GetLength()+1];
			memcpy(g_pSensorInfo[m_iDefCounter-1].sel_dat, in_strLineData.GetBuffer(0), in_strLineData.GetLength()+1);
			break;
		case DATA_KIND_DISP_STR:
			g_pSensorInfo[m_iDefCounter-1].dat = new char[in_strLineData.GetLength()+1];
			memcpy(g_pSensorInfo[m_iDefCounter-1].dat, in_strLineData.GetBuffer(0), in_strLineData.GetLength()+1);
			break;
		case DATA_KIND_DIRX_STR:
			g_pSensorInfo[m_iDefCounter-1].dir_x  = new char[in_strLineData.GetLength()+1];
			memcpy(g_pSensorInfo[m_iDefCounter-1].dir_x , in_strLineData.GetBuffer(0), in_strLineData.GetLength()+1);
			break;
		case DATA_KIND_DIRY_STR:
			g_pSensorInfo[m_iDefCounter-1].dir_y  = new char[in_strLineData.GetLength()+1];
			memcpy(g_pSensorInfo[m_iDefCounter-1].dir_y , in_strLineData.GetBuffer(0), in_strLineData.GetLength()+1);
			break;
		case DATA_KIND_DIRZ_STR:
			g_pSensorInfo[m_iDefCounter-1].dir_z  = new char[in_strLineData.GetLength()+1];
			memcpy(g_pSensorInfo[m_iDefCounter-1].dir_z, in_strLineData.GetBuffer(0), in_strLineData.GetLength()+1);
			break;
		case DATA_KIND_POS_X:
			g_pSensorInfo[m_iDefCounter-1].x = atof(in_strLineData);
			break;
		case DATA_KIND_POS_Y:
			g_pSensorInfo[m_iDefCounter-1].y = atof(in_strLineData);
			break;
		case DATA_KIND_POS_Z:
			g_pSensorInfo[m_iDefCounter-1].z = atof(in_strLineData);
			break;
// 2008/4/25 appended by yG for rpw addition ->
		case DATA_KIND_POS_ROLL:
			g_pSensorInfo[m_iDefCounter-1].roll = atof(in_strLineData);
			break;
		case DATA_KIND_POS_PITCH:
			g_pSensorInfo[m_iDefCounter-1].pitch = atof(in_strLineData);
			break;
		case DATA_KIND_POS_YAW:
			g_pSensorInfo[m_iDefCounter-1].yaw = atof(in_strLineData);
			break;
// <- 2008/4/25 appended by yG for rpw addition ->
		case DATA_KIND_RSU_ID:
			g_pSensorInfo[m_iDefCounter-1].rsu_id = atoi(in_strLineData);
			break;
		case DATA_KIND_CATEGORY:
			g_pSensorInfo[m_iDefCounter-1].category  = new char[in_strLineData.GetLength()+1];
			memcpy(g_pSensorInfo[m_iDefCounter-1].category , in_strLineData.GetBuffer(0), in_strLineData.GetLength()+1);
			break;
		case DATA_KIND_MEASUREMENT_KIND:
			g_pSensorInfo[m_iDefCounter-1].measurement_kind  = new char[in_strLineData.GetLength()+1];
			memcpy(g_pSensorInfo[m_iDefCounter-1].measurement_kind , in_strLineData.GetBuffer(0), in_strLineData.GetLength()+1);
			break;
	}

	return 0;
}

int CRackPosFile::MakeLastData(void)
{
	for(int i = 0 ; i<= (int)m_uiDefixxxount; i++){
		g_pSensorInfo[i].idx = -1;
		g_pSensorInfo[i].sel_dat = NULL;
		g_pSensorInfo[i].dat = NULL;
		g_pSensorInfo[i].dir_x = NULL;
		g_pSensorInfo[i].dir_y = NULL;
		g_pSensorInfo[i].dir_z = NULL;
		g_pSensorInfo[i].x = 0.0;
		g_pSensorInfo[i].y = 0.0;
		g_pSensorInfo[i].z = 0.0;
// 2008/4/25 appended by yG for rpw addition ->
		g_pSensorInfo[i].roll = 0.0;
		g_pSensorInfo[i].pitch = 0.0;
		g_pSensorInfo[i].yaw = 0.0;
// <- 2008/4/25 appended by yG for rpw addition
		g_pSensorInfo[i].rsu_id = 0;
		g_pSensorInfo[i].category = NULL;
		g_pSensorInfo[i].measurement_kind = NULL;
	}
	return 0;
}

CString CRackPosFile::GetLastError(int in_iErrID)
{
	if(  in_iErrID < ERR_ID_MAX ){
		return strErrMsg[in_iErrID];
	}
	else{
		return "未定義エラー";
	}
}
