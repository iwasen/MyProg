#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		GraphData.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�O���t�f�[�^�N���X�̒�`
//----------------------------------------------------------------------------------------------------
//  3. ���l
//		����
//*****************************************************************************************************

#include "MmeData.h"

#define GRAPH_X			0
#define GRAPH_Y			1
#define GRAPH_Z			2
#define GRAPH_SB		3
#define GRAPH_HEATER	4
#define N_GRAPH			5

// �O���t�\���p�f�[�^
struct SGraphData {
	CTime oTimestamp;
	struct {
		UINT nRange;
		float aData[N_GRAPH];
	} aUnit[N_UNIT];
};

struct SGraphLine {
	BOOL bEnable[N_UNIT][N_GRAPH];
};

class CGraphData
{
	typedef CArray<SGraphData, SGraphData &> ArrayGraphData;
public:
	CGraphData(void);

	CGraphData &operator=(CGraphData &oSrc);
	void SetBufSize(int nBufSize);
	void Reset();
	void AddGraphData(const CTime &oTimestamp, const SStatusWord &oStatusWord, const SAnalogData &oAnalogData);
	SGraphData &GetGraphData(int nIndex);
	int GetDataNum();

protected:
	ArrayGraphData m_aGraphData;
	int m_nBufSize;
	int m_nDataNum;
	int m_nDataIndex;
};
