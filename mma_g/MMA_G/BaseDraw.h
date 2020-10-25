#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		BaseDraw.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�O���t�`�拤�ʊ��N���X�̒�`
//----------------------------------------------------------------------------------------------------
//  3. ���l
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2007.08.09 S.Aizawa �V�K�쐬
//*****************************************************************************************************

#define SCALE_X		0
#define SCALE_Y		1
#define SCALE_Z		2
#define SCALE_TEMP	3

class CBaseDraw
{
public:
	CRect m_rectView;
	CRect m_rectScale;

protected:
	double GetLinearScaleStep(double fRange, int nPixel, int *pStep = NULL);
	double GetLogScaleStep(double fValue);
	double GetScaleStartValue(double fMinValue, double fStep);
};
