// IRMethod.cpp: CIRMethod �N���X�̃C���v�������e�[�V����
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "IRMethod.h"

//////////////////////////////////////////////////////////////////////
// �\�z/����
//////////////////////////////////////////////////////////////////////

CIRMethod::CIRMethod()
{
}

CIRMethod::~CIRMethod()
{
	// virtual���p�������邽�ߕK�v
}

void CIRMethod::PolarityCheck(int nData, double *pData)
{
	int i;
	double fMin = 0, fMax = 0;

	for (i = 0; i < nData; i++) {
		if (pData[i] < fMin)
			fMin = pData[i];
		if (pData[i] > fMax)
			fMax = pData[i];
	}

	if (-fMin > fMax) {
		for (i = 0; i < nData; i++)
			pData[i] = -pData[i];
	}
}
