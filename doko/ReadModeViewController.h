//
//  ReadModeViewController.h
//  Doko
//
//  Created by 相沢 伸一 on 11/04/26.
//  Copyright 2011 TRC. All rights reserved.
//

#import <UIKit/UIKit.h>
#import <MediaPlayer/MediaPlayer.h>
#import <AVFoundation/AVFoundation.h>
#import "IndexView.h"
#import "DataManager.h"

@interface SentenceData : NSObject {
    NSString *sentence;
    NSString *soundFile;
    int viewIndex;
    int lineIndex;
    CGSize viewSize;
}

@property(nonatomic, retain) NSString *sentence;
@property(nonatomic, retain) NSString *soundFile;
@property(nonatomic, assign) int viewIndex;
@property(nonatomic, assign) int lineIndex;
@property(nonatomic, assign) CGSize viewSize;

@end


@interface ReadData : NSObject {
    UILabel *textView;
    NSString *soundFile;
}

@property (assign, nonatomic) UILabel *textView;
@property (retain, nonatomic) NSString *soundFile;

@end


@interface ReadModeViewController : UIViewController <AVAudioPlayerDelegate, UIScrollViewDelegate> {
    IBOutlet UIView *guideView;
    IBOutlet UIImageView *guideImageView;   // 操作ガイドイメージビュー
    IBOutlet UIButton *startButton;         // スタートボタン
    IBOutlet UIScrollView *scrollView;
    IBOutlet IndexView *indexView;
    IBOutlet UIView *maskView;
    IBOutlet UIButton *indexButton;
    IBOutlet UIButton *endButton;
    IBOutlet UIImageView *endMarkView;      // ページ終了マークビュー
    
    AVAudioPlayer *bgmAudio;                //　BGM
	AVAudioPlayer *readAudio;               // テキスト読む上げ用プレーヤ
    MPMoviePlayerController *bgMovie;       // BGムービー
    UIFont *textFont;                       // テキスト用フォント
    UIFont *midashiFont;                    // 小見出し用フォント
    NSMutableArray *sentenceArray;          // 語句の配列
    NSMutableArray *readDataArray;          // 読み上げデータの配列
    int currentPage;                        // 現在ページ
    int sentenceCounter;                    // 語句読み上げカウンタ
    int readCounter;                        // テキスト読み上げカウンタ
    bool pageControlUsed;                   // ページ制御中フラグ
    UIView *bgViews[PAGE_NUM];              // ページ毎のBGビュー配列
    UIImageView *bgImageViews[PAGE_NUM];    // ページ毎の静止画ビュー配列
    bool scrollFlag;                        // スクロール中フラグ
    bool enableTouch;                       // タッチ有効フラグ
}

@property (retain, nonatomic) AVAudioPlayer *bgmAudio;
@property (retain, nonatomic) AVAudioPlayer *readAudio;

- (void)displayGuide;
- (void)continueGuide;
- (void)endContinueGuide;
- (void)endContinueGuide2;
- (void)startReadFirst;
- (void)createBgView;
- (void)startReadMode:(bool)fadeIn;
- (void)stopReadMode;
- (void)startBgMovie:(bool)fadeIn;
- (void)stopBgMovie;
- (void)setBgImage:(int)page;
- (void)startTextData;
- (void)stopTextData;
- (void)readTextData;
- (void)setTextData;
- (void)selectPage:(int)page;
- (void)returnMenu:(bool)fadeout;
- (void)endReturnMenu;
- (IBAction)touchStartButton:(id)sender;
- (IBAction)indexButton:(id)sender;
- (IBAction)endButton:(id)sender;

@end
