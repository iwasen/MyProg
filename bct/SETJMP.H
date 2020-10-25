/*
	setjmp.h - header non-local jumps
*/

typedef	int	jmp_buf[2];

int	setjmp(jmp_buf);
void	longjmp(jmp_buf, int);
