//
//  PlayViewController.h
//  Play
//
//  Created by akiko on 11/04/15.
//  Copyright 2011 有限会社テクノルート. All rights reserved.
//

#import <UIKit/UIKit.h>
#import <AVFoundation/AVFoundation.h>
#import <MediaPlayer/MediaPlayer.h>
#import <AudioToolbox/AudioToolbox.h>
#import "MyScrollView.h"
#import "IndexView.h"
#import "DataManager.h"

@interface PlayModeViewController : UIViewController <UIScrollViewDelegate> {
    IBOutlet UIView *guideView;             // 操作ガイドビュー
    IBOutlet UIImageView *guideImageView;   // 操作ガイドイメージビュー
    IBOutlet UIButton *startButton;         // スタートボタン
    IBOutlet UIView *maskView;              // サムネイルのマスクビュー
    IBOutlet MyScrollView *scrollView;      // スクロールビュー
    IBOutlet UIView *bgView;                // 背景ビュー
    IBOutlet UIView *atariView;             // 当たりボタンビュー
    IBOutlet UIView *baseView;              // テキスト表示ビュー
    IBOutlet IndexView *indexView;             // インデックスビュー
    IBOutlet UIButton *indexButton;         // インデックス表示ボタン
    IBOutlet UIButton *endButton;           // あそぶモード終了ボタン
    IBOutlet UIImageView *completeJpnImageView;    // コンプリート文字ビュー（日本語）
    IBOutlet UIImageView *completeEngImageView;    // コンプリート文字ビュー（英語）

    MPMoviePlayerController *bgMovie;       // 背景動画
    AVAudioPlayer *bgmAudio;                //　BGM
    AVAudioPlayer *atariAudio;              //　当たりSE
    AVAudioPlayer *itemSEAudio;             //　アイテムSE
    AVAudioPlayer *itemAudio;               //　アイテム名音声
    AVAudioPlayer *completeAudio;           //　コンプリート音
    AVAudioPlayer *dokoAudio;               //　「どこ？」

    int currentPage;                        // 現在ページ
    int itemCount;                          // アイテム名読み上げカウンタ
    bool readStopFlag;                      // よむモード終了フラグ
    NSTimer *readTextTimer;                 // アイテム名読み上げタイマー
    int completeCounter;                    // コンプリートアニメーションカウンタ
    UIImageView *completeImageView;         // コンプリート文字ビュー
    bool completeStopFlag;                  // コンプリート停止フラグ
    bool enableTouch;                       // タッチ有効フラグ

    PlayFindItem *playFindItem;             // 探すアイテム情報
    
    NSMutableArray *atariButtonArray;       // 当たりボタンの配列
    NSMutableArray *textViewArray;          // アイテム名表示用ビューの配列
}

@property (retain, nonatomic) AVAudioPlayer *bgmAudio;
@property (retain, nonatomic) AVAudioPlayer *atariAudio;
@property (retain, nonatomic) AVAudioPlayer *itemSEAudio;
@property (retain, nonatomic) AVAudioPlayer *itemAudio;
@property (retain, nonatomic) AVAudioPlayer *completeAudio;
@property (retain, nonatomic) AVAudioPlayer *dokoAudio;

- (void)displayGuide;
- (void)continueGuide;
- (void)endContinueGuide;
- (void)endContinueGuide2;
- (void)startPlayFirst;
- (void)startPlayMode;
- (void)stopPlayMode;
- (void)setupBgView;
- (void)readItemName;
- (void)readTextTimer:(NSTimer *)timer;
- (void)setItemText;
- (void)setItemButton;
- (void)setFindItem;
- (void)clickItem:(id)inSender;
- (void)viewAtariParticle:(CGPoint)point;
- (void)endAtariParticle:(UIImageView *)atariAnimation;
- (void)playComplete;
- (void)playComplete2;
- (void)playComplete3;
- (void)playComplete4;
- (void)playComplete5;
- (void)returnMenu;
- (void)endReturnMenu;
- (void)selectPage:(int)page;
- (IBAction)touchStartButton:(id)sender;
- (IBAction)indexButton:(id)sender;
- (IBAction)endButton:(id)sender;

@end
