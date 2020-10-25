#pragma once

#include "GlobalData.h"


#define RACK_POS_FILE_NAME	"rack_pos.def"
#define FILE_HEADER_1		"%DEFINE_RACK_POSITION_FILE"
#define FILE_HEADER_2		"%DEFINE_NO"
#define COMMENT_STRING		";"
#define RACK_POS_NUM		"<No.>"
#define RACK_POS_SEL_STR	"[SELECTION_STRING]"
#define RACK_POS_DISP_STR	"[DISPLAY_STRING]"
#define RACK_POS_DIRX_STR	"[DIR_X_STRING]"
#define RACK_POS_DIRY_STR	"[DIR_Y_STRING]"
#define RACK_POS_DIRZ_STR	"[DIR_Z_STRING]"
#define RACK_POS_X			"[POSITION_X]"
#define RACK_POS_Y			"[POSITION_Y]"
#define RACK_POS_Z			"[POSITION_Z]"

// 2008/4/25 modified by yG for rpw addition ->
#define RACK_POS_ROLL		"[POSITION_ROLL]"
#define RACK_POS_PITCH		"[POSITION_PITCH]"
#define RACK_POS_YAW		"[POSITION_YAW]"
//#define DATA_KIND_NUM_MAX	9
//#define DATA_KIND_NUM_MAX	12
// <- 2008/4/25 modified by yG for rpw addition
#define RACK_POS_RSU_ID		"[RSU_ID]"
#define RACK_POS_CATEGORY	"[CATEGORY]"
#define RACK_POS_MEASUREMENT_KIND	"[MEASUREMENT_KIND]"
#define DATA_KIND_NUM_MAX	15

class CRackPosFile
{
public:
	CRackPosFile(void);
	~CRackPosFile(void);
	int ReadFile(void);
private:
	unsigned int m_uiDefixxxount;
	bool m_bIsHeader1Read;
	bool m_bIsHeader2Read;
	int InterpretHeader2(CString in_strLineData);
	CString DeleteCommentString(CString in_strLineData);
public:
	int InterpretData(CString in_strLineData);
	int GetDataKind(CString in_strLineData);
private:
	int m_iDefCounter;
	int InterpretDataByDataType(int in_iDataType, CString in_strLineData);
public:
	int MakeLastData(void);
	CString GetLastError(int in_iErrID);
};
