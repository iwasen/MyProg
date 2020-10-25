CC=CL
MM=S

LIBRARY : rs232c_$(MM).lib

rs_$(MM).obj : rs.c rs232c.h
	$(CC) /c /J /O /A$(MM) /Fors_$(MM) rs.c

rs1_$(MM).obj : rs1.c rs232c.h
	$(CC) /c /J /O /A$(MM) /Fors1_$(MM) rs1.c

rs2_$(MM).obj : rs2.c rs232c.h
	$(CC) /c /J /O /A$(MM) /Fors2_$(MM) rs2.c

rs3_$(MM).obj : rs3.c rs232c.h
	$(CC) /c /J /O /A$(MM) /Fors3_$(MM) rs3.c

timer_$(MM).obj : timer.c rs232c.h
	$(CC) /c /J /O /A$(MM) /Fotimer_$(MM) timer.c

rs232c_$(MM).lib : rs_$(MM).obj rs1_$(MM).obj rs2_$(MM).obj rs3_$(MM).obj \
		   timer_$(MM).obj
	command /c del rs232c_$(MM).lib
	lib rs232c_$(MM)+rs_$(MM)+rs1_$(MM)+rs2_$(MM)+rs3_$(MM)+timer_$(MM);
