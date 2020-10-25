/****************************************
*					*
*	basic library			*
*					*
****************************************/

#include "baslib.h"

#define PI	3.14159265358979323846
#define PID2	1.57079632679489661923	/* PI divided by 2 */
#define PID4	0.78539816339744830962	/* PI divided by 4 */
#define I_PI	0.31830988618379067154	/* Inverse of PI */
#define I_PID2	0.63661977236758134308	/* Inverse of PID2 */
#define	ROOT2	1.414213562373059
#define	EXP	2.718281828459045
#define abs(x) ((x)<0?-(x):(x))


#pragma	module	sqr@

float b_sqr(x)
float	x;
{
	double	y, y1;

	if (x == 0)
		return((float)0);

	y = x / 2;
	do {
		y1 = y;
		y = 0.5 * (y + x / y);
	} while (abs(y - y1)/y > 1e-7);

	return((float)y);
}


#pragma	module	exp@

float b_exp(x)
float	x;
{
	double	y, yn, xn, k;
	int	i;

	i = 1;
	y = k = xn = 1;
	do {
		xn *= x;
		k *= i;
		yn = xn / k;
		y += yn;
		i++;
	} while (abs(yn / y) > 1e-7);

	return((float)y);
}


#pragma	module	sin@

float b_sin(x)
float	x;
{
	double	y, y2, y3, y5, y7, y9;

	if (x == 0)
		return((float)0);
	x += PID2;
	if (x < 0)
		x = -x;
	x = x - (int)(x / (2*PI)) * (2*PI);
	if (x > PI)
		x = 2*PI - x;
	x -= PID2;

	y = I_PID2 * x;
	y2 = y * y;
	y3 = y * y2;
	y5 = y3 * y2;
	y7 = y5 * y2;
	y9 = y7 * y2;

	return((float)(1.5707963*y - 0.64596371*y3 + 0.079689679*y5
		- 0.0046737656*y7 + 0.00015148419*y9));
}


#pragma	module	cos@

float b_cos(x)
float	x;
{
	float	b_sin(float);

	return(b_sin(x + PID2));
}


#pragma	module	tan@

float b_tan(x)
float	x;
{
	return((float)(b_sin(x) / b_cos(x)));
}


#pragma	module	log@

float b_log(x)
float	x;
{
	int	n;
	double	y, y2, y3, y5;

	n = 0;
	if (x < 1) {
		do {
			x *= EXP;
			n--;
		} while (x < 1);
	} else {
		do {
			x /= EXP;
			n++;
		} while (x > 2);
	}
	y = (x - ROOT2) / (x + ROOT2) * 5.828427124746;
	y2 = y * y;
	y3 = y * y2;
	y5 = y3 * y2;
	return((float)(0.34657359028 + 0.343145791438 * y + 0.003366358167 * y3 + 0.000061399745 * y5 + n));
}


#pragma	module	atn@

float b_atn(x)
float	x;
{
	static	float	atn2[] = {
		0.7853980000,
		0.4636480000,
		0.2449790000,
		0.1243550000,
		0.0624188000,
		0.0312398000,
		0.0156237000,
		0.0078123400,
		0.0039062300,
		0.0019531200,
		0.0009765620,
		0.0004882810,
		0.0002441410,
		0.0001220700,
		0.0000610352,
		0.0000305176,
		0.0000152588,
		0.0000076294,
		0.0000038147,
		0.0000019074,
		0.0000009537,
		0.0000004768,
		0.0000002384,
		0.0000001192,
	};
	float	n2, y, y1, z;
	int	k;
	
	n2 = 1;
	y = 1;
	z = 0;
	for (k = 0; k < 24; k++) {
		y1 = y;
		if (x < 0) {
			y = y - n2 * x;
			x = x + n2 * y1;
			z = z - atn2[k];
		} else {
			y = y + n2 * x;
			x = x - n2 * y1;
			z = z + atn2[k];
		}
	n2 = n2 / 2;
	}
	return(z);
}
