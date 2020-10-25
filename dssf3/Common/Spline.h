#pragma once

class CSpline {
public:
	CSpline();
	~CSpline();

	void MakeTable(const double x[], const double y[], int n);
	double Spline(double t);

protected:
	void FreeBuffers();

	double *m_x, *m_y, *m_z;
	int m_n;
};
