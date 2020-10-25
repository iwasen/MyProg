#include <stdio.h>
#include <graph.h>
#include <stdlib.h>
#include <string.h>
#include <conio.h>
#include <ctype.h>
#include <dos.h>
#include "sg_cpu.h"

void	dsp_tstptn0(short);
void	dsp_tstptn1(short);
void	dsp_tstptn2(short);
void	dsp_tstptn3(short);
void	dsp_bite(short);
void	dsp_reticle(short);
void	dsp_heading_scale1(short);
void	dsp_sensor_heading1(short);
void	dsp_sensor_FOR(short);
void	dsp_sensor_FOR_err(short);
void	dsp_sensor_LOS_symbol(short);
void	dsp_sensor_LOS_digital(short);
void	dsp_laser_status(short);
void	dsp_FOV_gate(short);
void	dsp_sensor_monitor(short);
void	dsp_speed(short);
void	dsp_altitude(short);
void	dsp_message(short);
void	dsp_legend(short);
void	dsp_tsuibi_gate(short);
void	dsp_time(short);
void	dsp_missile_symbol(short);
void	dsp_missile_LOS(short);
void	dsp_missile_heading1(short);
void	dsp_missile_heading2(short);
void	dsp_missile_alxxx(short);
void	dsp_missile_sonzai1(short);
void	dsp_missile_hyouji(short);
void	dsp_missile_sonzai2(short);
void	dsp_IFF_gateA(short);
void	dsp_IFF_gateB(short);
void	dsp_IFF_gateC(short);
void	dsp_shougai_symbol(short);
void	dsp_zukei0(short);
void	dsp_zukei1(short);
void	dsp_zukei2(short);
void	dsp_zukei3(short);
void	dsp_heading_scale2(short);
void	dsp_sensor_heading2(short);
void	dsp_pitch_scale(short);
void	dsp_sensor_pitch(short);
void	dsp_missile_pitch(short);
void	dsp_heading_digital(short);
void	dsp_update_rate(short);
void	dsp_missile_circle1(short);
void	dsp_missile_circle2(short);
void	dsp_missile_v1(short);
void	dsp_missile_c1(short);
void	dsp_missile_v2(short);
void	dsp_missile_c2(short);
void	dsp_missile_digital(short);
static	void	dummy(short);
static	void	reserve(short);

static	void	(*dsp_func[])(short) = {
	dsp_tstptn0,		/* 0 */
	dsp_tstptn1,		/* 1 */
	dsp_tstptn2,		/* 2 */
	dsp_tstptn3,		/* 3 */
	dsp_bite,		/* 4 */
	dummy,			/* 5 */
	dummy,			/* 6 */
	reserve,		/* 7 */
	reserve,		/* 8 */
	reserve,		/* 9 */
	reserve,		/* 10 */
	reserve,		/* 11 */
	reserve,		/* 12 */
	reserve,		/* 13 */
	reserve,		/* 14 */
	reserve,		/* 15 */
	dsp_zukei0,		/* 16 */
	dsp_zukei0,		/* 17 */
	dsp_zukei0,		/* 18 */
	dsp_zukei0,		/* 19 */
	dsp_zukei0,		/* 20 */
	dsp_zukei0,		/* 21 */
	dsp_zukei0,		/* 22 */
	dsp_zukei0,		/* 23 */
	dsp_zukei0,		/* 24 */
	dsp_zukei0,		/* 25 */
	dsp_zukei0,		/* 26 */
	dsp_zukei0,		/* 27 */
	dsp_zukei0,		/* 28 */
	dsp_zukei0,		/* 29 */
	dsp_zukei0,		/* 30 */
	dsp_zukei0,		/* 31 */
	dsp_zukei0,		/* 32 */
	dsp_zukei0,		/* 33 */
	reserve,		/* 34 */
	reserve,		/* 35 */
	reserve,		/* 36 */
	reserve,		/* 37 */
	reserve,		/* 38 */
	reserve,		/* 39 */
	reserve,		/* 40 */
	reserve,		/* 41 */
	reserve,		/* 42 */
	reserve,		/* 43 */
	reserve,		/* 44 */
	reserve,		/* 45 */
	reserve,		/* 46 */
	reserve,		/* 47 */
	dsp_zukei1,		/* 48 */
	dsp_zukei1,		/* 49 */
	dsp_zukei1,		/* 50 */
	dsp_zukei1,		/* 51 */
	dsp_zukei1,		/* 52 */
	dsp_zukei1,		/* 53 */
	dsp_zukei1,		/* 54 */
	dsp_zukei1,		/* 55 */
	dsp_zukei1,		/* 56 */
	dsp_zukei1,		/* 57 */
	dsp_zukei1,		/* 58 */
	dsp_zukei1,		/* 59 */
	dsp_zukei1,		/* 60 */
	dsp_zukei1,		/* 61 */
	dsp_zukei1,		/* 62 */
	dsp_zukei1,		/* 63 */
	dsp_zukei1,		/* 64 */
	dsp_zukei1,		/* 65 */
	reserve,		/* 66 */
	reserve,		/* 67 */
	reserve,		/* 68 */
	reserve,		/* 69 */
	reserve,		/* 70 */
	reserve,		/* 71 */
	reserve,		/* 72 */
	reserve,		/* 73 */
	reserve,		/* 74 */
	reserve,		/* 75 */
	reserve,		/* 76 */
	reserve,		/* 77 */
	reserve,		/* 78 */
	reserve,		/* 79 */
	dsp_zukei3,		/* 80 */
	dsp_zukei3,		/* 81 */
	dsp_zukei3,		/* 82 */
	dsp_zukei3,		/* 83 */
	dsp_zukei3,		/* 84 */
	dsp_zukei3,		/* 85 */
	dsp_zukei3,		/* 86 */
	dsp_zukei3,		/* 87 */
	dsp_zukei3,		/* 88 */
	dsp_zukei3,		/* 89 */
	dsp_zukei3,		/* 90 */
	dsp_zukei3,		/* 91 */
	dsp_zukei3,		/* 92 */
	dsp_zukei3,		/* 93 */
	dsp_zukei3,		/* 94 */
	dsp_zukei3,		/* 95 */
	dsp_zukei3,		/* 96 */
	dsp_zukei3,		/* 97 */
	reserve,		/* 98 */
	reserve,		/* 99 */
	reserve,		/* 100 */
	reserve,		/* 101 */
	reserve,		/* 102 */
	reserve,		/* 103 */
	reserve,		/* 104 */
	reserve,		/* 105 */
	reserve,		/* 106 */
	reserve,		/* 107 */
	reserve,		/* 108 */
	reserve,		/* 109 */
	reserve,		/* 110 */
	reserve,		/* 111 */
	dsp_zukei2,		/* 112 */
	dsp_zukei2,		/* 113 */
	dsp_zukei2,		/* 114 */
	dsp_zukei2,		/* 115 */
	dsp_zukei2,		/* 116 */
	dsp_zukei2,		/* 117 */
	dsp_zukei2,		/* 118 */
	dsp_zukei2,		/* 119 */
	dsp_zukei2,		/* 120 */
	dsp_zukei2,		/* 121 */
	dsp_zukei2,		/* 122 */
	dsp_zukei2,		/* 123 */
	dsp_zukei2,		/* 124 */
	dsp_zukei2,		/* 125 */
	dsp_zukei2,		/* 126 */
	dsp_zukei2,		/* 127 */
	dsp_zukei2,		/* 128 */
	dsp_zukei2,		/* 129 */
	reserve,		/* 130 */
	reserve,		/* 131 */
	reserve,		/* 132 */
	reserve,		/* 133 */
	reserve,		/* 134 */
	reserve,		/* 135 */
	reserve,		/* 136 */
	reserve,		/* 137 */
	reserve,		/* 138 */
	reserve,		/* 139 */
	reserve,		/* 140 */
	reserve,		/* 141 */
	reserve,		/* 142 */
	reserve,		/* 143 */
	reserve,		/* 144 */
	reserve,		/* 145 */
	reserve,		/* 146 */
	reserve,		/* 147 */
	reserve,		/* 148 */
	reserve,		/* 149 */
	reserve,		/* 150 */
	reserve,		/* 151 */
	reserve,		/* 152 */
	reserve,		/* 153 */
	reserve,		/* 154 */
	reserve,		/* 155 */
	reserve,		/* 156 */
	reserve,		/* 157 */
	reserve,		/* 158 */
	reserve,		/* 159 */
	reserve,		/* 160 */
	reserve,		/* 161 */
	reserve,		/* 162 */
	reserve,		/* 163 */
	reserve,		/* 164 */
	reserve,		/* 165 */
	reserve,		/* 166 */
	reserve,		/* 167 */
	reserve,		/* 168 */
	reserve,		/* 169 */
	reserve,		/* 170 */
	reserve,		/* 171 */
	reserve,		/* 172 */
	reserve,		/* 173 */
	reserve,		/* 174 */
	reserve,		/* 175 */
	dsp_update_rate,	/* 176 */
	dsp_time,		/* 177 */
	dsp_heading_digital,	/* 178 */
	dsp_altitude,		/* 179 */
	dsp_altitude,		/* 180 */
	dsp_altitude,		/* 181 */
	dsp_speed,		/* 182 */
	dsp_speed,		/* 183 */
	dsp_speed,		/* 184 */
	dsp_sensor_LOS_digital,	/* 185 */
	dsp_sensor_LOS_digital,	/* 186 */
	dsp_sensor_monitor,	/* 187 */
	dsp_sensor_monitor,	/* 188 */
	dsp_sensor_monitor,	/* 189 */
	dsp_missile_alxxx,	/* 190 */
	reserve,		/* 191 */
	dsp_legend,		/* 192 */
	dsp_legend,		/* 193 */
	dsp_legend,		/* 194 */
	dsp_legend,		/* 195 */
	dsp_legend,		/* 196 */
	reserve,		/* 197 */
	reserve,		/* 198 */
	reserve,		/* 199 */
	dsp_message,		/* 200 */
	dsp_message,		/* 201 */
	dsp_message,		/* 202 */
	reserve,		/* 203 */
	dsp_message,		/* 294 */
	dsp_message,		/* 295 */
	dsp_message,		/* 296 */
	reserve,		/* 297 */
	reserve,		/* 298 */
	reserve,		/* 299 */
	reserve,		/* 200 */
	reserve,		/* 201 */
	reserve,		/* 202 */
	reserve,		/* 203 */
	reserve,		/* 204 */
	reserve,		/* 205 */
	reserve,		/* 206 */
	reserve,		/* 207 */
	reserve,		/* 208 */
	reserve,		/* 209 */
	reserve,		/* 210 */
	reserve,		/* 211 */
	reserve,		/* 212 */
	reserve,		/* 213 */
	reserve,		/* 214 */
	reserve,		/* 215 */
	reserve,		/* 216 */
	reserve,		/* 217 */
	reserve,		/* 218 */
	reserve,		/* 219 */
	reserve,		/* 220 */
	reserve,		/* 221 */
	reserve,		/* 222 */
	reserve,		/* 223 */
	dsp_heading_scale1,	/* 224 */
	dsp_sensor_heading1,	/* 225 */
	dsp_sensor_heading1,	/* 226 */
	dsp_sensor_heading1,	/* 227 */
	dsp_heading_scale2,	/* 228 */
	dsp_heading_scale2,	/* 229 */
	dsp_heading_scale2,	/* 230 */
	dsp_sensor_heading2,	/* 231 */
	dsp_pitch_scale,	/* 232 */
	dsp_sensor_pitch,	/* 233 */
	dsp_sensor_FOR,		/* 234 */
	dsp_sensor_FOR,		/* 235 */
	dsp_sensor_LOS_symbol,	/* 236 */
	dsp_sensor_LOS_symbol,	/* 237 */
	dsp_FOV_gate,		/* 238 */
	dsp_IFF_gateB,		/* 239 */
	dsp_IFF_gateB,		/* 240 */
	dsp_IFF_gateB,		/* 241 */
	dsp_tsuibi_gate,	/* 242 */
	dsp_laser_status,	/* 243 */
	dsp_reticle,		/* 244 */
	reserve,		/* 245 */
	reserve,		/* 246 */
	reserve,		/* 247 */
	reserve,		/* 248 */
	reserve,		/* 249 */
	reserve,		/* 250 */
	reserve,		/* 251 */
	reserve,		/* 252 */
	reserve,		/* 253 */
	reserve,		/* 254 */
	reserve,		/* 255 */
	dsp_shougai_symbol,	/* 256 */
	dsp_shougai_symbol,	/* 257 */
	dsp_shougai_symbol,	/* 258 */
	dsp_shougai_symbol,	/* 259 */
	dsp_shougai_symbol,	/* 260 */
	dsp_shougai_symbol,	/* 261 */
	dsp_shougai_symbol,	/* 262 */
	dsp_shougai_symbol,	/* 263 */
	dsp_shougai_symbol,	/* 264 */
	dsp_shougai_symbol,	/* 265 */
	dsp_shougai_symbol,	/* 266 */
	dsp_shougai_symbol,	/* 267 */
	dsp_shougai_symbol,	/* 268 */
	dsp_shougai_symbol,	/* 269 */
	dsp_shougai_symbol,	/* 270 */
	dsp_shougai_symbol,	/* 271 */
	dsp_shougai_symbol,	/* 272 */
	dsp_shougai_symbol,	/* 273 */
	dsp_shougai_symbol,	/* 274 */
	dsp_shougai_symbol,	/* 275 */
	dsp_shougai_symbol,	/* 276 */
	dsp_shougai_symbol,	/* 277 */
	dsp_shougai_symbol,	/* 278 */
	dsp_shougai_symbol,	/* 279 */
	dsp_shougai_symbol,	/* 280 */
	dsp_shougai_symbol,	/* 281 */
	dsp_shougai_symbol,	/* 282 */
	dsp_shougai_symbol,	/* 283 */
	dsp_shougai_symbol,	/* 284 */
	dsp_shougai_symbol,	/* 285 */
	dsp_shougai_symbol,	/* 286 */
	dsp_shougai_symbol,	/* 287 */
	dsp_shougai_symbol,	/* 288 */
	dsp_shougai_symbol,	/* 289 */
	dsp_shougai_symbol,	/* 290 */
	dsp_shougai_symbol,	/* 291 */
	dsp_shougai_symbol,	/* 292 */
	dsp_shougai_symbol,	/* 293 */
	dsp_shougai_symbol,	/* 294 */
	dsp_shougai_symbol,	/* 295 */
	dsp_shougai_symbol,	/* 296 */
	dsp_shougai_symbol,	/* 297 */
	dsp_shougai_symbol,	/* 298 */
	dsp_shougai_symbol,	/* 299 */
	dsp_shougai_symbol,	/* 300 */
	dsp_shougai_symbol,	/* 301 */
	dsp_shougai_symbol,	/* 302 */
	dsp_shougai_symbol,	/* 303 */
	dsp_shougai_symbol,	/* 304 */
	dsp_shougai_symbol,	/* 305 */
	dsp_shougai_symbol,	/* 306 */
	dsp_shougai_symbol,	/* 307 */
	dsp_shougai_symbol,	/* 308 */
	dsp_shougai_symbol,	/* 309 */
	dsp_shougai_symbol,	/* 310 */
	dsp_shougai_symbol,	/* 311 */
	dsp_shougai_symbol,	/* 312 */
	dsp_shougai_symbol,	/* 313 */
	dsp_shougai_symbol,	/* 314 */
	dsp_shougai_symbol,	/* 315 */
	dsp_shougai_symbol,	/* 316 */
	dsp_shougai_symbol,	/* 317 */
	dsp_shougai_symbol,	/* 318 */
	dsp_shougai_symbol,	/* 319 */
	dsp_shougai_symbol,	/* 320 */
	dsp_shougai_symbol,	/* 321 */
	dsp_shougai_symbol,	/* 322 */
	dsp_shougai_symbol,	/* 323 */
	dsp_shougai_symbol,	/* 324 */
	dsp_shougai_symbol,	/* 325 */
	dsp_shougai_symbol,	/* 326 */
	dsp_shougai_symbol,	/* 327 */
	dsp_shougai_symbol,	/* 328 */
	dsp_shougai_symbol,	/* 329 */
	dsp_shougai_symbol,	/* 330 */
	dsp_shougai_symbol,	/* 331 */
	dsp_shougai_symbol,	/* 332 */
	dsp_shougai_symbol,	/* 333 */
	dsp_shougai_symbol,	/* 334 */
	dsp_shougai_symbol,	/* 335 */
	dsp_shougai_symbol,	/* 336 */
	dsp_shougai_symbol,	/* 337 */
	dsp_shougai_symbol,	/* 338 */
	dsp_shougai_symbol,	/* 339 */
	dsp_shougai_symbol,	/* 340 */
	dsp_shougai_symbol,	/* 341 */
	dsp_shougai_symbol,	/* 342 */
	dsp_shougai_symbol,	/* 343 */
	dsp_shougai_symbol,	/* 344 */
	dsp_shougai_symbol,	/* 345 */
	dsp_shougai_symbol,	/* 346 */
	dsp_shougai_symbol,	/* 347 */
	dsp_shougai_symbol,	/* 348 */
	dsp_shougai_symbol,	/* 349 */
	dsp_shougai_symbol,	/* 350 */
	dsp_shougai_symbol,	/* 351 */
	dsp_shougai_symbol,	/* 352 */
	dsp_shougai_symbol,	/* 353 */
	dsp_shougai_symbol,	/* 354 */
	dsp_shougai_symbol,	/* 355 */
	reserve,		/* 356 */
	reserve,		/* 357 */
	reserve,		/* 358 */
	reserve,		/* 359 */
	reserve,		/* 360 */
	reserve,		/* 361 */
	reserve,		/* 362 */
	reserve,		/* 363 */
	reserve,		/* 364 */
	reserve,		/* 365 */
	reserve,		/* 366 */
	reserve,		/* 367 */
	dsp_sensor_FOR_err,	/* 368 */
	dsp_sensor_FOR_err,	/* 369 */
	dsp_sensor_pitch,	/* 370 */
	dsp_sensor_heading2,	/* 371 */
	dsp_IFF_gateA,		/* 372 */
	dsp_IFF_gateA,		/* 373 */
	dsp_IFF_gateA,		/* 374 */
	reserve,		/* 375 */
	reserve,		/* 376 */
	reserve,		/* 377 */
	reserve,		/* 378 */
	reserve,		/* 379 */
	reserve,		/* 380 */
	reserve,		/* 381 */
	reserve,		/* 382 */
	reserve,		/* 383 */
	dsp_shougai_symbol,	/* 384 */
	dsp_shougai_symbol,	/* 385 */
	dsp_shougai_symbol,	/* 386 */
	dsp_shougai_symbol,	/* 387 */
	dsp_shougai_symbol,	/* 388 */
	dsp_shougai_symbol,	/* 389 */
	dsp_shougai_symbol,	/* 390 */
	dsp_shougai_symbol,	/* 391 */
	dsp_shougai_symbol,	/* 392 */
	dsp_shougai_symbol,	/* 393 */
	dsp_shougai_symbol,	/* 394 */
	dsp_shougai_symbol,	/* 395 */
	dsp_shougai_symbol,	/* 396 */
	dsp_shougai_symbol,	/* 397 */
	dsp_shougai_symbol,	/* 398 */
	dsp_shougai_symbol,	/* 399 */
	dsp_shougai_symbol,	/* 400 */
	dsp_shougai_symbol,	/* 401 */
	dsp_shougai_symbol,	/* 402 */
	dsp_shougai_symbol,	/* 403 */
	dsp_shougai_symbol,	/* 404 */
	dsp_shougai_symbol,	/* 405 */
	dsp_shougai_symbol,	/* 406 */
	dsp_shougai_symbol,	/* 407 */
	dsp_shougai_symbol,	/* 408 */
	dsp_shougai_symbol,	/* 409 */
	dsp_shougai_symbol,	/* 410 */
	dsp_shougai_symbol,	/* 411 */
	dsp_shougai_symbol,	/* 412 */
	dsp_shougai_symbol,	/* 413 */
	dsp_shougai_symbol,	/* 414 */
	dsp_shougai_symbol,	/* 415 */
	dsp_shougai_symbol,	/* 416 */
	dsp_shougai_symbol,	/* 417 */
	dsp_shougai_symbol,	/* 418 */
	dsp_shougai_symbol,	/* 419 */
	dsp_shougai_symbol,	/* 420 */
	dsp_shougai_symbol,	/* 421 */
	dsp_shougai_symbol,	/* 422 */
	dsp_shougai_symbol,	/* 423 */
	dsp_shougai_symbol,	/* 424 */
	dsp_shougai_symbol,	/* 425 */
	dsp_shougai_symbol,	/* 426 */
	dsp_shougai_symbol,	/* 427 */
	dsp_shougai_symbol,	/* 428 */
	dsp_shougai_symbol,	/* 429 */
	dsp_shougai_symbol,	/* 430 */
	dsp_shougai_symbol,	/* 431 */
	dsp_shougai_symbol,	/* 432 */
	dsp_shougai_symbol,	/* 433 */
	dsp_shougai_symbol,	/* 434 */
	dsp_shougai_symbol,	/* 435 */
	dsp_shougai_symbol,	/* 436 */
	dsp_shougai_symbol,	/* 437 */
	dsp_shougai_symbol,	/* 438 */
	dsp_shougai_symbol,	/* 439 */
	dsp_shougai_symbol,	/* 440 */
	dsp_shougai_symbol,	/* 441 */
	dsp_shougai_symbol,	/* 442 */
	dsp_shougai_symbol,	/* 443 */
	dsp_shougai_symbol,	/* 444 */
	dsp_shougai_symbol,	/* 445 */
	dsp_shougai_symbol,	/* 446 */
	dsp_shougai_symbol,	/* 447 */
	dsp_shougai_symbol,	/* 448 */
	dsp_shougai_symbol,	/* 449 */
	dsp_shougai_symbol,	/* 450 */
	dsp_shougai_symbol,	/* 451 */
	dsp_shougai_symbol,	/* 452 */
	dsp_shougai_symbol,	/* 453 */
	dsp_shougai_symbol,	/* 454 */
	dsp_shougai_symbol,	/* 455 */
	dsp_shougai_symbol,	/* 456 */
	dsp_shougai_symbol,	/* 457 */
	dsp_shougai_symbol,	/* 458 */
	dsp_shougai_symbol,	/* 459 */
	dsp_shougai_symbol,	/* 460 */
	dsp_shougai_symbol,	/* 461 */
	dsp_shougai_symbol,	/* 462 */
	dsp_shougai_symbol,	/* 463 */
	dsp_shougai_symbol,	/* 464 */
	dsp_shougai_symbol,	/* 465 */
	dsp_shougai_symbol,	/* 466 */
	dsp_shougai_symbol,	/* 467 */
	dsp_shougai_symbol,	/* 468 */
	dsp_shougai_symbol,	/* 469 */
	dsp_shougai_symbol,	/* 470 */
	dsp_shougai_symbol,	/* 471 */
	dsp_shougai_symbol,	/* 472 */
	dsp_shougai_symbol,	/* 473 */
	dsp_shougai_symbol,	/* 474 */
	dsp_shougai_symbol,	/* 475 */
	dsp_shougai_symbol,	/* 476 */
	dsp_shougai_symbol,	/* 477 */
	dsp_shougai_symbol,	/* 478 */
	dsp_shougai_symbol,	/* 479 */
	dsp_shougai_symbol,	/* 480 */
	dsp_shougai_symbol,	/* 481 */
	dsp_shougai_symbol,	/* 482 */
	dsp_shougai_symbol,	/* 483 */
	reserve,		/* 484 */
	reserve,		/* 485 */
	reserve,		/* 486 */
	reserve,		/* 487 */
	reserve,		/* 488 */
	reserve,		/* 489 */
	reserve,		/* 490 */
	reserve,		/* 491 */
	reserve,		/* 492 */
	reserve,		/* 493 */
	reserve,		/* 494 */
	reserve,		/* 495 */
	dsp_missile_pitch,	/* 496 */
	dsp_missile_heading1,	/* 497 */
	dsp_missile_heading1,	/* 498 */
	dsp_missile_heading1,	/* 499 */
	dsp_missile_heading2,	/* 500 */
	dsp_missile_LOS,	/* 501 */
	dsp_missile_LOS,	/* 502 */
	dsp_missile_sonzai2,	/* 503 */
	dsp_missile_sonzai1,	/* 504 */
	dsp_missile_hyouji,	/* 505 */
	dsp_missile_symbol,	/* 506 */
	dsp_IFF_gateC,		/* 507 */
	dsp_IFF_gateC,		/* 508 */
	dsp_IFF_gateC,		/* 509 */
	dsp_laser_status,	/* 510 */
	reserve,		/* 511 */
	dsp_shougai_symbol,	/* 512 */
	dsp_shougai_symbol,	/* 513 */
	dsp_shougai_symbol,	/* 514 */
	dsp_shougai_symbol,	/* 515 */
	dsp_shougai_symbol,	/* 516 */
	dsp_shougai_symbol,	/* 517 */
	dsp_shougai_symbol,	/* 518 */
	dsp_shougai_symbol,	/* 519 */
	dsp_shougai_symbol,	/* 520 */
	dsp_shougai_symbol,	/* 521 */
	dsp_shougai_symbol,	/* 522 */
	dsp_shougai_symbol,	/* 523 */
	dsp_shougai_symbol,	/* 524 */
	dsp_shougai_symbol,	/* 525 */
	dsp_shougai_symbol,	/* 526 */
	dsp_shougai_symbol,	/* 527 */
	dsp_shougai_symbol,	/* 528 */
	dsp_shougai_symbol,	/* 529 */
	dsp_shougai_symbol,	/* 530 */
	dsp_shougai_symbol,	/* 531 */
	dsp_shougai_symbol,	/* 532 */
	dsp_shougai_symbol,	/* 533 */
	dsp_shougai_symbol,	/* 534 */
	dsp_shougai_symbol,	/* 535 */
	dsp_shougai_symbol,	/* 536 */
	dsp_shougai_symbol,	/* 537 */
	dsp_shougai_symbol,	/* 538 */
	dsp_shougai_symbol,	/* 539 */
	dsp_shougai_symbol,	/* 540 */
	dsp_shougai_symbol,	/* 541 */
	dsp_shougai_symbol,	/* 542 */
	dsp_shougai_symbol,	/* 543 */
	dsp_shougai_symbol,	/* 544 */
	dsp_shougai_symbol,	/* 545 */
	dsp_shougai_symbol,	/* 546 */
	dsp_shougai_symbol,	/* 547 */
	dsp_shougai_symbol,	/* 548 */
	dsp_shougai_symbol,	/* 549 */
	dsp_shougai_symbol,	/* 550 */
	dsp_shougai_symbol,	/* 551 */
	dsp_shougai_symbol,	/* 552 */
	dsp_shougai_symbol,	/* 553 */
	dsp_shougai_symbol,	/* 554 */
	dsp_shougai_symbol,	/* 555 */
	dsp_shougai_symbol,	/* 556 */
	dsp_shougai_symbol,	/* 557 */
	dsp_shougai_symbol,	/* 558 */
	dsp_shougai_symbol,	/* 559 */
	dsp_shougai_symbol,	/* 560 */
	dsp_shougai_symbol,	/* 561 */
	dsp_shougai_symbol,	/* 562 */
	dsp_shougai_symbol,	/* 563 */
	dsp_shougai_symbol,	/* 564 */
	dsp_shougai_symbol,	/* 565 */
	dsp_shougai_symbol,	/* 566 */
	dsp_shougai_symbol,	/* 567 */
	dsp_shougai_symbol,	/* 568 */
	dsp_shougai_symbol,	/* 569 */
	dsp_shougai_symbol,	/* 570 */
	dsp_shougai_symbol,	/* 571 */
	dsp_shougai_symbol,	/* 572 */
	dsp_shougai_symbol,	/* 573 */
	dsp_shougai_symbol,	/* 574 */
	dsp_shougai_symbol,	/* 575 */
	dsp_shougai_symbol,	/* 576 */
	dsp_shougai_symbol,	/* 577 */
	dsp_shougai_symbol,	/* 578 */
	dsp_shougai_symbol,	/* 579 */
	dsp_shougai_symbol,	/* 580 */
	dsp_shougai_symbol,	/* 581 */
	dsp_shougai_symbol,	/* 582 */
	dsp_shougai_symbol,	/* 583 */
	dsp_shougai_symbol,	/* 584 */
	dsp_shougai_symbol,	/* 585 */
	dsp_shougai_symbol,	/* 586 */
	dsp_shougai_symbol,	/* 587 */
	dsp_shougai_symbol,	/* 588 */
	dsp_shougai_symbol,	/* 589 */
	dsp_shougai_symbol,	/* 590 */
	dsp_shougai_symbol,	/* 591 */
	dsp_shougai_symbol,	/* 592 */
	dsp_shougai_symbol,	/* 593 */
	dsp_shougai_symbol,	/* 594 */
	dsp_shougai_symbol,	/* 595 */
	dsp_shougai_symbol,	/* 596 */
	dsp_shougai_symbol,	/* 597 */
	dsp_shougai_symbol,	/* 598 */
	dsp_shougai_symbol,	/* 599 */
	dsp_shougai_symbol,	/* 600 */
	dsp_shougai_symbol,	/* 601 */
	dsp_shougai_symbol,	/* 602 */
	dsp_shougai_symbol,	/* 603 */
	dsp_shougai_symbol,	/* 604 */
	dsp_shougai_symbol,	/* 605 */
	dsp_shougai_symbol,	/* 606 */
	dsp_shougai_symbol,	/* 607 */
	dsp_shougai_symbol,	/* 608 */
	dsp_shougai_symbol,	/* 609 */
	dsp_shougai_symbol,	/* 610 */
	dsp_shougai_symbol,	/* 611 */
	reserve,		/* 612 */
	reserve,		/* 613 */
	reserve,		/* 614 */
	reserve,		/* 615 */
	reserve,		/* 616 */
	reserve,		/* 617 */
	reserve,		/* 618 */
	reserve,		/* 619 */
	reserve,		/* 620 */
	reserve,		/* 621 */
	reserve,		/* 622 */
	reserve,		/* 623 */
	dsp_missile_circle1,	/* 624 */
	dummy,			/* 625 */
	dsp_missile_v1,		/* 626 */
	dsp_missile_c1,		/* 627 */
	dsp_missile_circle2,	/* 628 */
	dummy,			/* 629 */
	dsp_missile_v2,		/* 630 */
	dsp_missile_c2,		/* 631 */
	reserve,		/* 632 */
	reserve,		/* 633 */
	reserve,		/* 634 */
	reserve,		/* 635 */
	reserve,		/* 636 */
	reserve,		/* 637 */
	reserve,		/* 638 */
	reserve,		/* 639 */
	dsp_missile_v1,		/* 640 */
	dsp_missile_c1,		/* 641 */
	dsp_missile_circle1,	/* 642 */
	dummy,			/* 643 */
	dsp_missile_v2,		/* 644 */
	dsp_missile_c2,		/* 645 */
	dsp_missile_circle2,	/* 646 */
	dummy,			/* 647 */
	reserve,		/* 648 */
	reserve,		/* 649 */
	reserve,		/* 650 */
	reserve,		/* 651 */
	reserve,		/* 652 */
	reserve,		/* 653 */
	reserve,		/* 654 */
	reserve,		/* 655 */
	dsp_missile_digital	/* 656 */
};

static	short	tm_ovf;
static	void	test(void);
static	void	t_manual(void);
static	void	t_save(void);
static	void	t_load(void);
static	void	t_help(void);
static	void	t_time(void);
static	void	interrupt far	tm_int(void);
static	void	disp_data(void);

main()
{
	_setvideomode(_98RESSCOLOR);
	_registerfonts("PSCOUN.FON");
	_setcolor(7);
	_setactivepage(1);
	_clearscreen(_GCLEARGRAPH);
	_rectangle(_GBORDER,240, 0, 639, 399);
	_setactivepage(0);
	_clearscreen(_GCLEARGRAPH);
	_rectangle(_GBORDER, 240, 0, 639, 399);
	_setviewport(241, 1, 638, 398);
	test();
	_setvideomode(_98TEXT80);
}

static	void	test(void)
{
	short	loop_sw, c, i;

	rate_1553B = 30;
	loop_sw = 1;
	while (loop_sw) {
		printf("COMMAND ? ");
		c = getch();
		c = toupper(c);
		printf("%c\n", c);
		switch (c) {
		case 'E':
			loop_sw = 0;
			break;
		case 'H':
			t_help();
			break;
		case 'L':
			t_load();
			break;
		case 'M':
			t_manual();
			break;
		case 'R':
			rate_dsp_flag ^= 1;
			calc_dp();
			disp_data();
			break;
		case 'S':
			t_save();
			break;
		case 'T':
			t_time();
			break;
		}
	}
}

static	void	t_manual(void)
{
	short	no, data;
	char	buf[80];

	for (;;) {
		printf("NO. =");
		gets(buf);
		if (buf[0] == '\0')
			break;
		no = atoi(buf);
		if (no >= 0 && no < 512) {
			printf("DATA(%d)=", input_data[no]);
			gets(buf);
			printf("\n");
			if (buf[0] != '\0') {
				data = atoi(buf);
				input_data[no] = data;
				mode_check();
				digital_update_flag = 1;
				calc_dp();
				disp_data();
			}
		}
	}
}

static	void	t_save(void)
{
	char	buf[80];
	FILE	*fp;

	printf("SAVE FILE NAME ? ");
	gets(buf);
	if (buf[0] == '\0')
		return;
	strcat(buf, ".DAT");
	if ((fp = fopen(buf, "wb")) != NULL) {
		fwrite(input_data, 512, 2, fp);
		fclose(fp);
	}
}

static	void	t_load(void)
{
	char	buf[80];
	FILE	*fp;

	printf("LOAD FILE NAME ? ");
	gets(buf);
	if (buf[0] == '\0')
		return;
	strcat(buf, ".DAT");
	if ((fp = fopen(buf, "rb")) != NULL) {
		fread(input_data, 512, 2, fp);
		fclose(fp);
		mode_check();
		digital_update_flag = 1;
		calc_dp();
		disp_data();
	}
}

static	void	t_help(void)
{
	printf("E : プログラム終了\n");
	printf("H : コマンド一覧表示\n");
	printf("L : データロード\n");
	printf("M : データ入力\n");
	printf("S : データセーブ\n");
	printf("T : 計算時間測定\n");
	printf("\n");
}

static	void	t_time(void)
{
	union	REGS	reg;
	struct	SREGS	seg;
	long	counter;
	void	(interrupt far *p)(void);

	tm_ovf = 0;
	reg.h.ah = 0x02;
	reg.x.cx = 100;
	p = tm_int;
	reg.x.bx = FP_OFF(p);
	seg.es = FP_SEG(p);
	int86x(0x1c, &reg, &reg, &seg);
	_enable();
	counter = 0;
	while (tm_ovf == 0) {
		if (counter < 5)
			digital_update_flag = 1;
		mode_check();
		set_video_mode();
		bit_check();
		calc_dp();
		counter++;
	}
	printf("%f mS\n\n", 1000.0 / counter);
}

static	void interrupt far	tm_int(void)
{
	tm_ovf = 1;
}

static	void	disp_data(void)
{
	short	i;
	ushort	bit, *pap;
	static	short	screen;

	screen ^= 1;
	_setactivepage(screen);
	_clearscreen(_GVIEWPORT);

	bit = 0x0001;
	pap = pa;
	for (i = 0; i <= 656; i++) {
		if (*pap & bit)
			(*dsp_func[i])(i);
		bit <<= 1;
		if (bit == 0) {
			pap++;
			bit = 0x0001;
		}
	}
	_setvisualpage(screen);
}

static	void	dummy(short pa)
{
}

static	void	reserve(short pa)
{
	printf("INVALID P/A BIT (%d)\n", pa);
}

void	dsp_str(short x, short y, char *str)
{
	_moveto(199 + (short)(x * 199L / 6345),
				199 - (short)(y * 199L / 6345));
	_outgtext(str);
}

void	scopy(char *cp, ushort *sp, short len)
{
	while (len--) {
		if (*sp != 0)
			*cp++ = *sp;
		else
			*cp++ = '*';
		sp++;
	}
	*cp = '\0';
}

void	font7(void)
{
	_setfont("w10");
}

void	font8(void)
{
	_setfont("w13");
}

void	setcolor(ushort color)
{
	_setcolor(color);
}

void	setlinestyle(ushort mask)
{
	_setlinestyle(mask);
}

void	line(short x1, short y1, short x2, short y2)
{
	_moveto(199 + (short)(x1 * 199L / 6345),
				199 - (short)(y1 * 199L / 6345));
	_lineto(199 + (short)(x2 * 199L / 6345),
				199 - (short)(y2 * 199L / 6345));
}

void	box(short x1, short y1, short x2, short y2, short control)
{
	_rectangle(control, 199 + (short)(x1 * 199L / 6345),
			199 - (short)(y1 * 199L / 6345),
			199 + (short)(x2 * 199L / 6345),
			199 - (short)(y2 * 199L / 6345));
}

void	circle(short x, short y, short r, short control)
{
	r = (r * 199L) / 6345;
	x = 199 + (short)(x * 199L / 6345);
	y = 199 - (short)(y * 199L / 6345);

	_ellipse(control, x - r, y - r, x + r, y + r);
}

void	triangle(short x1, short y1, short x2, short y2, short x3, short y3, short control)
{
	struct	xycoord	coord[3];

	coord[0].xcoord = 199 + (short)(x1 * 199L / 6345);
	coord[0].ycoord = 199 - (short)(y1 * 199L / 6345),
	coord[1].xcoord = 199 + (short)(x2 * 199L / 6345);
	coord[1].ycoord = 199 - (short)(y2 * 199L / 6345),
	coord[2].xcoord = 199 + (short)(x3 * 199L / 6345);
	coord[2].ycoord = 199 - (short)(y3 * 199L / 6345),
	_polygon(control, coord, 3);
	if (control == _GFILLINTERIOR)
		_polygon(_GBORDER, coord, 3);
}

void	fill(short x, short y, ushort color)
{
	_floodfill(199 + (short)(x * 199L / 6345),
			199 - (short)(y * 199L / 6345), color);
}
