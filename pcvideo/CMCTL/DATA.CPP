#include <windows.h>
#include <mmsystem.h>
#include <string.h>
#include "cmctldll.h"

int	colorTable[3] = {8, 16, 24};
VIDEOSIZE videoSize[16] = {
	{40, 30},
	{80, 60},
	{120, 90},
	{160, 120},
	{200, 150},
	{240, 180},
	{280, 210},
	{320, 240},
	{360, 270},
	{400, 300},
	{440, 330},
	{480, 360},
	{520, 390},
	{560, 420},
	{600, 450},
	{640, 480}
};
HINSTANCE hWinSock;
