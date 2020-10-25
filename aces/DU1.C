#include <stddef.h>
#include <signal.h>
#include "\rs232c\rs232c.h"

main()
{
	int	c, err;
	RSPRM	rsprm;
	u_char	rbif[6];
	int	counter;
	static	u_char	sbuf[10];
	int	ibit = 0;
	int	key = 0;

	rsprm.sync_mode = ASYNC;
	rsprm.duplex = DUPLEX_FULL;
	rsprm.blt = BLT_9600;
	rsprm.parity = PARITY_ODD;
	rsprm.chr_len = CHR_8;
	rsprm.stp_len = STP_1;
	rsprm.flow_ctrl = FLOW_NONE;
	rsprm.dtr = DTR_ON;
	rsprm.buf_size = 1024;
	rsprm.bufp = NULL;

	if ((err = rs1_open(&rsprm)) != 0) {
		printf("err %d\n", err);
		exit(1);
	}
	tm_open();
	signal(SIGINT, SIG_IGN);

	for (;;) {
		if (kbhit()) {
			c = toupper(getch());
			if (c == 0x1b)
				break;
			else if (c >= '1' && c <= '5') {
				sbuf[1] |= 1 << (c - '1');
				key = 1;
				tm_set(2, 50);
			} else if (c >= '6' && c <= '9') {
				sbuf[0] |= 0x40 >> (c - '6');
				key = 1;
				tm_set(2, 50);
			} else if (c == '0') {
				sbuf[0] |= 0x04;
				key = 1;
				tm_set(2, 50);
			} else if (c == 'N') {
				sbuf[3] |= 0x01;
				key = 1;
				tm_set(2, 50);
			} else if (c == ' ') {
				sbuf[0] = sbuf[1] = sbuf[2] = 0;
				sbuf[3] &= 0xf0;
			} else if (c == 'D')
				sbuf[3] ^= 0x20;
			else if (c == 'U')
				sbuf[3] ^= 0x10;
			else if (c == 'A') {
				sbuf[0] = 0xff;
				sbuf[1] = 0xff;
				sbuf[2] = 0xff;
				sbuf[3] |= 0x0f;
				key = 1;
				tm_set(2, 50);
			}
		}

		if (rs1_rxc()) {
			c = rs1_get();
			rbif[counter++] = c;
			if (counter == 6) {
				printf("*");
				counter = 0;
				if (rbif[4] & 0x80) {
					printf("\xxxHO MODE\n");
					rs1_sendreq(rbif, 6);
				} else {
					if (rbif[5] & 0x01) {
						printf("\nI-BIT\n");
						tm_set(1, 200);
						ibit = 1;
					} else if (rbif[5] & 0x02) {
						printf("\nR-BIT\n");
						tm_set(1, 200);
						ibit = 1;
					} else {
						if (rs1_cts())
							rs1_sendreq(sbuf, 10);
					}
				}
			}
			tm_set(0, 2);
		}

		if (tm_check(0))
			counter = 0;
		if (ibit) {
			if (tm_check(1)) {
				if (rs1_cts())
					rs1_sendreq(sbuf, 10);
				ibit = 0;
			}
		}
		if (key) {
			if (tm_check(2)) {
				sbuf[0] = sbuf[1] = sbuf[2] = 0;
				sbuf[3] &= 0xf0;
				key = 0;
			}
		}
	}
	rs1_close();
	tm_close();
}
