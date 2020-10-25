#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		FftSpectrumDraw.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�X�y�N�g����̓O���t�̕`��N���X��`
//----------------------------------------------------------------------------------------------------
//  3. ���l
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2007.08.09 S.Aizawa �V�K�쐬
//*****************************************************************************************************

#include "BaseDraw.h"

class CFftSpectrumDraw : public CBaseDraw
{
public:
	CFftSpectrumDraw(CDC *pDC = NULL, int nPenWidth = 1);

	void DrawGraph(CDC &dc, CRect rectView, const double *pDataX, const double *pDataY, const double *pDataZ, const double *pDataG, int nData, double fSampleRate, double fFreqMin, double fFreqMax, BOOL bFreqLog, double fLevelMin, double fLevelMax, BOOL bLevelLog);

protected:
	void SetScaleRect(CDC &dc);
	void DrawScale(CDC &dc, double fFreqMin, double fFreqMax, BOOL bFreqLog, double fLevelMin, double fLevelMax, BOOL bLevelLog);
	void DrawData(CDC &dc, const double *pData, int nData, double fSampleRate, double fFreqMin, double fFreqMax, BOOL bFreqLog, double fLevelMin, double fLevelMax, BOOL bLevelLog, CPen &penGraphLine);

public:
	CPen m_penGraphDataX;
	CPen m_penGraphDataY;
	CPen m_penGraphDataZ;
	CPen m_penGraphDataG;

protected:
	CPen m_penScale;
	CPen m_penGray;
	CPen m_penLightGray;
	CFont m_fontGraph, m_fontGraph2, m_fontGraph3;
};
