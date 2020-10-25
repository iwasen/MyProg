// spline.cpp : �C���v�������e�[�V���� �t�@�C��
//

#include "stdafx.h"
#include "Spline.h"

/***********************************************************
	spline.c -- �X�v���C�����
***********************************************************/

CSpline::CSpline()
{
	m_x = NULL;
	m_y = NULL;
	m_z = NULL;
	m_n = 0;
}

CSpline::~CSpline()
{
	FreeBuffers();
}

void CSpline::MakeTable(const double x[], const double y[], int n)
{
	int i;
	double t;
	double *h = new double[n];
	double *d = new double[n];

	FreeBuffers();
	m_x = new double[n];
	m_y = new double[n];
	m_z = new double[n];
	m_n = n;

	memcpy(m_x, x, n * sizeof(double));
	memcpy(m_y, y, n * sizeof(double));

	m_z[0] = 0;  m_z[n - 1] = 0;  /* ���[�_�ł� y''(x) / 6 */
    for (i = 0; i < n - 1; i++) {
		h[i    ] =  m_x[i + 1] - m_x[i];
		d[i + 1] = (m_y[i + 1] - m_y[i]) / h[i];
	}
	m_z[1] = d[2] - d[1] - h[0] * m_z[0];
	d[1] = 2 * (m_x[2] - m_x[0]);
	for (i = 1; i < n - 2; i++) {
		t = h[i] / d[i];
		m_z[i + 1] = d[i + 2] - d[i + 1] - m_z[i] * t;
		d[i + 1] = 2 * (m_x[i + 2] - m_x[i]) - h[i] * t;
	}
	m_z[n - 2] -= h[n - 2] * m_z[n - 1];
	for (i = n - 2; i > 0; i--)
		m_z[i] = (m_z[i] - h[i] * m_z[i + 1]) / d[i];

	delete [] h;
	delete [] d;
}

double CSpline::Spline(double t)
{
	int i, j, k;
	double d, h;

	i = 0;  j = m_n - 1;
	while (i < j) {
		k = (i + j) / 2;
		if (m_x[k] < t) i = k + 1;  else j = k;
	}
	if (i > 0) i--;
	h = m_x[i + 1] - m_x[i];  d = t - m_x[i];
	return (((m_z[i + 1] - m_z[i]) * d / h + m_z[i] * 3) * fabs(d)
		+ ((m_y[i + 1] - m_y[i]) / h
		- (m_z[i] * 2 + m_z[i + 1]) * h)) * d + m_y[i];
}

void CSpline::FreeBuffers()
{
	if (m_x != NULL) {
		delete [] m_x;
		m_x = NULL;
	}

	if (m_y != NULL) {
		delete [] m_y;
		m_y = NULL;
	}

	if (m_z != NULL) {
		delete [] m_z;
		m_z = NULL;
	}
}
