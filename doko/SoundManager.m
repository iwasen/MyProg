//
//  SoundManager.m
//  Doko
//
//  Created by aizawa on 11/05/13.
//  Copyright 2011 TRC. All rights reserved.
//

#import "SoundManager.h"

SoundManager *soundManager;

@implementation SoundManager

// 初期化処理
- (id)init
{
    self = [super init];
    if (self != nil) {
        playButtonSound = [self initSound:@"kddk1a_btn_asob"];
        readButtonSound = [self initSound:@"kddk1a_btn_yomu"];
        openButtonSound = [self initSound:@"kddk1a_bar_indexbtn"];
        closeButtonSound = [self initSound:@"kddk1a_bar_menubtn"];
    }
    return self;
}

- (void)dealloc
{
    [playButtonSound release];
    [readButtonSound release];
    [openButtonSound release];
    [closeButtonSound release];
    [super dealloc];
}

// サウンドファイル名からAVAudioPlayerを作成
- (AVAudioPlayer *)initSound:(NSString *)soundFile
{
    NSString *path = [[NSBundle mainBundle] pathForResource:soundFile ofType:@"mp3"];
	return [[AVAudioPlayer alloc] initWithContentsOfURL:[NSURL fileURLWithPath:path] error:nil];
}

// あそぶモードボタンの音を再生
- (void)playPlaySound
{
    playButtonSound.currentTime = 0;
    [playButtonSound play];
}

// よむモードボタンの音を再生
- (void)playReadSound
{
    readButtonSound.currentTime = 0;
    [readButtonSound play];    
}

// 開くアクションの音を再生
- (void)playOpenSound
{
    openButtonSound.currentTime = 0;
    [openButtonSound play];
}

// 閉じるアクションの音を再生
- (void)playCloseSound
{
    closeButtonSound.currentTime = 0;
    [closeButtonSound play];
}

@end
