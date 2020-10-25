//
//  DocoDemoViewController.m
//  DocoDemo
//
//  Created by akiko on 11/04/15.
//  Copyright 2011 有限会社テクノルート. All rights reserved.
//

#import "PlayModeViewController.h"
#import "SoundManager.h"

static NSString *sounfEffectFile[FIND_ITEM_NUM] = {
    @"doko_word01in",
    @"doko_word02in",
    @"doko_word03in",
    @"doko_word04in",
    @"doko_word05in",
    @"doko_word06in"
};

@implementation PlayModeViewController

@synthesize bgmAudio, atariAudio, itemSEAudio, itemAudio, completeAudio, dokoAudio;

- (void)dealloc
{
    // Outlet release
    [guideView release];
    [guideImageView release];
    [startButton release];
    [maskView release];
    [scrollView release];
    [bgView release];
    [atariView release];
    [baseView release];
    [indexView release];
    [indexButton release];
    [endButton release];
    [completeJpnImageView release];
    [completeEngImageView release];
    
    // その他release
    [bgMovie release];
    [atariButtonArray release];
    [textViewArray release];
    self.bgmAudio = nil;
    self.atariAudio = nil;
    self.itemSEAudio = nil;
    self.itemAudio = nil;
    self.completeAudio = nil;
    self.dokoAudio = nil;
    
    [super dealloc];
}

- (void)didReceiveMemoryWarning
{
    // Releases the view if it doesn't have a superview.
    [super didReceiveMemoryWarning];
    
    // Release any cached data, images, etc that aren't in use.
}

#pragma mark - View lifecycle

// Implement viewDidLoad to do additional setup after loading the view, typically from a nib.
- (void)viewDidLoad
{
    [super viewDidLoad];
    
    // 現在ページを取得
    currentPage = dataManager.playCurrentPage;

    // 配列を初期化
    atariButtonArray = [[NSMutableArray alloc] init];
    textViewArray = [[NSMutableArray alloc] init];

    // スクロールビューの設定
    scrollView.minimumZoomScale = 1.0;
    scrollView.maximumZoomScale = 5.0;
    scrollView.delegate = self; 
    scrollView.contentSize = bgView.frame.size;

    // インデックスビューの設定
    indexView.delegate = self;
    [indexView closeIndex:NO :nil];

    // BGムービー初期化
    bgMovie = [[MPMoviePlayerController alloc] init];

    // 初回は操作ガイド表示
    if (!dataManager.playContinueFlag) {
        [self displayGuide];
        dataManager.playContinueFlag = YES;
    } else
        [self continueGuide];
}

// 操作ガイド表示
- (void)displayGuide
{
    guideImageView.image = [dataManager getImage:@"kddk1j_guide_asob" :@"kddk1e_guide_asobu"];
    [startButton setImage:[dataManager getImage:@"kddk1j_btn_guide_n___x423y666w172h35" :@"kddk1e_btn_guide_n___x423y666w172h35"] forState:UIControlStateNormal];
    [startButton setImage:[dataManager getImage:@"kddk1j_btn_guide_r___x423y666w172h35" :@"kddk1e_btn_guide_r___x423y666w172h35"] forState:UIControlStateHighlighted];
    [self.view addSubview:guideView];
}

// スタートボタンタッチ
- (void)touchStartButton:(id)sender
{
    [UIView animateWithDuration:0.5
                     animations:^ {
                         guideView.alpha = 0.0;                         
                     }
                     completion:^ (BOOL finished) {
                         // 操作ガイド画面を閉じる
                         [guideView removeFromSuperview];
                         
                         // あそぶモード開始
                         [self startPlayFirst];
                     }];
}

// ２回目以降
- (void)continueGuide
{
    // インデックス表示
    [indexView openIndex:-1 :NO];
    
    // 現在ページのサムネイル用をマスクするためのビュー
    CGRect rect = indexView.frame;
    indexView.frame = CGRectMake(0, SCREEN_HEIGHT - rect.size.height, rect.size.width, rect.size.height);
    indexView.userInteractionEnabled = NO;
    indexView.hidden = NO;
    UIView *thumbnail = [indexView getThumbnailView:currentPage];
    maskView.frame = thumbnail.frame;
    maskView.alpha = 0.0;
    [indexView addSubview:maskView];
    
    // 現在ページのサムネイルを3回点滅
    [UIView animateWithDuration:0.2
                          delay:0.5
                        options:UIViewAnimationOptionRepeat | UIViewAnimationOptionAutoreverse
                     animations:^ {
                         [UIView setAnimationRepeatCount:3.0];
                         maskView.alpha = 0.7;
                     }
                     completion:^ (BOOL finished) {
                         [self endContinueGuide];
                     }];
}

// インデックス消去
- (void)endContinueGuide
{
    [indexView closeIndex:YES :@selector(endContinueGuide2)];
}

// マスクビュー消去
- (void)endContinueGuide2
{
    [maskView removeFromSuperview];    
    [self startPlayFirst];
}

// あそぶモードの開始
- (void)startPlayFirst
{
    endButton.hidden = NO;
    indexButton.hidden = NO;
    baseView.hidden = NO;
    indexView.userInteractionEnabled = YES;
    scrollView.userInteractionEnabled = YES;
    enableTouch = YES;

    [self startPlayMode];
}

// あそぶモード開始
- (void)startPlayMode
{
    // 現在ページ保存
    dataManager.playCurrentPage = currentPage;
    
    readStopFlag = NO;

    [self setupBgView];
    [self setFindItem];

    scrollView.zoomScale = 1.0;

    // viewフェードイン
    self.view.alpha = 0.0;
    [UIView animateWithDuration:2.0
                     animations:^ {
                         self.view.alpha = 1.0;                         
                     }
                     completion:^ (BOOL finished) {
                         [self readItemName];
                     }];
}

// あそぶモード停止
- (void)stopPlayMode
{
    // 当たりボタンを削除
    for (UIButton *atariButton in atariButtonArray)
        [atariButton removeFromSuperview];
    [atariButtonArray removeAllObjects];

    // アイテム名テキストビューを削除
    for (UIButton *textView in textViewArray)
        [textView removeFromSuperview];
    [textViewArray removeAllObjects];

    // 停止フラグセット
    readStopFlag = YES;
    
    // 読み上げ待ち停止
    [NSObject cancelPreviousPerformRequestsWithTarget:self];

    // 読み上げタイマー停止
    [readTextTimer invalidate];
    readTextTimer = nil;

    // コンプリート停止
    [completeImageView removeFromSuperview];
    completeStopFlag = YES;
    
    // 各サウンド停止
    [itemAudio stop];
    [itemSEAudio stop];
    [bgMovie stop];
    [bgmAudio stop];
}

// 背景動画、BGM再生
- (void)setupBgView
{
    // BGM再生
    self.bgmAudio = [[soundManager initSound:[dataManager getBgSoundFile:currentPage]] autorelease];
    bgmAudio.numberOfLoops = -1;
    bgmAudio.volume = 1.0;
    [bgmAudio play];
    
    // 背景動画再生
    NSString *moviePath = [[NSBundle mainBundle] pathForResource:[dataManager getBgMovieFile:currentPage] ofType:@"mp4"];
    bgMovie.contentURL = [NSURL fileURLWithPath:moviePath];
    bgMovie.view.frame = CGRectMake(0, 0, SCREEN_WIDTH, SCREEN_HEIGHT);
    bgMovie.repeatMode = MPMovieRepeatModeOne;
    bgMovie.controlStyle = MPMovieControlStyleNone;
    bgMovie.view.userInteractionEnabled = NO;
    [bgView addSubview:bgMovie.view];
    [bgMovie play];
}

//　アイテム名読み上げ待ち
- (void)readItemName
{
    // アイテム表示まで2秒待つ
    [self performSelector:@selector(readItemName2) withObject:nil afterDelay:2.0];
}

//　アイテム名読み上げタイマーセット
- (void)readItemName2
{
    // アイテム名読み上げカウンタクリア
    itemCount = 0;

    // １つ目のアイテム読み上げ
    [self setItemText];
    itemCount++;

    // ２つ目以降のアイテム読み上げタイマー設定
    readTextTimer = [NSTimer scheduledTimerWithTimeInterval:2.8
                                             target:self
                                           selector:@selector(readTextTimer:)
                                           userInfo:nil
                                            repeats:YES];
}

//　アイテム名読み上げタイマー
- (void)readTextTimer:(NSTimer *)timer
{
    if (readStopFlag) {
        [timer invalidate];
        readTextTimer = nil;
        return;
    }
    
    if (itemCount < FIND_ITEM_NUM) {
        // アイテム読み上げ
        [self setItemText];
        
        itemCount++;
    } else {
        // タイマー停止
        [timer invalidate];
        readTextTimer = nil;
        
        // 「どこ」読み上げ
        self.dokoAudio = [[soundManager initSound:dataManager.lang == LANG_JPN ? @"kddk1j_re_doko" : @"kddk1e_re_doko"] autorelease];
        [dokoAudio play];

        // 当たり済みのアイテムを消す
        [UIView animateWithDuration:0.5
                         animations:^ {
                             for (int i = 0; i < FIND_ITEM_NUM; i++) {
                                 if (playFindItem[i].findFlag)
                                     [[textViewArray objectAtIndex:i] setAlpha:0.0];
                             }                             
                         }];
        
        //　アイテムボタン生成
        [self setItemButton];
        
        completeStopFlag = NO;
    }
}

//　アイテム名（テキスト表示/音声再生）
- (void)setItemText
{
    if (readStopFlag)
        return;
    
    self.itemAudio = [[soundManager initSound:[dataManager getPlaySoundFile:currentPage :playFindItem[itemCount].itemIndex]] autorelease];
    itemAudio.numberOfLoops = 0;
    itemAudio.volume = 1.0;
    
    self.itemSEAudio = [[soundManager initSound:sounfEffectFile[itemCount]] autorelease];
    itemSEAudio.numberOfLoops = 0;
    itemSEAudio.volume = 1.0;
    
    [itemSEAudio play];
    [itemAudio play];
    
    [[textViewArray objectAtIndex:itemCount] setAlpha:0.0];
    [UIView animateWithDuration:0.5
                     animations:^ {
                         [[textViewArray objectAtIndex:itemCount] setAlpha:1.0];                         
                     }];
}

//　当たりボタン生成
- (void)setItemButton
{
    for (int i = 0; i < FIND_ITEM_NUM; i++) {
        if (!playFindItem[i].findFlag)
            [[atariButtonArray objectAtIndex:i] setHidden:NO];
    }
}

// 当たりボタン、アイテム名表示
- (void)setFindItem
{
    int i;
    CGSize textSize[FIND_ITEM_NUM];

    playFindItem = [dataManager getPlayFindItem:currentPage];

    // 全部回答済みなら再度選択
    for (i = 0; i < FIND_ITEM_NUM; i++) {
        if (!playFindItem[i].findFlag)
            break;
    }
    if (i == FIND_ITEM_NUM)
        [dataManager initPlayFindPage:currentPage];

    UIFont *font;
    int margin;
    int fontSize = 26;
    int totalTextWidth;
    for (;;) {
        // フォント作成
        font = [UIFont fontWithName:@"HiraKakuProN-W6" size:fontSize];
        
        // 全部のテキストの長さを求める
        totalTextWidth = 0;
        for (i = 0; i < FIND_ITEM_NUM; i++) {
            CGSize bounds = CGSizeMake(SCREEN_WIDTH, SCREEN_HEIGHT);
            textSize[i] = [[dataManager getPlayItemName:currentPage :playFindItem[i].itemIndex] sizeWithFont:font constrainedToSize:bounds lineBreakMode:UILineBreakModeClip];

            totalTextWidth += textSize[i].width;
        }

        // テキスト間のマージン
        margin = (SCREEN_WIDTH - totalTextWidth) / (FIND_ITEM_NUM + 1);
        
        if (margin > 20)
            break;
        else {
            // 入りきらなければフォントサイズを小さくする
            fontSize--;
        }
    }

    [atariButtonArray removeAllObjects];
    [textViewArray removeAllObjects];
    int x = margin;
    for (i = 0; i < FIND_ITEM_NUM; i++) {
        // 当たりボタン生成
        UIButton *atariButton = [UIButton buttonWithType:UIButtonTypeRoundedRect];
        atariButton.frame = [dataManager getPlayAtariRect:currentPage :playFindItem[i].itemIndex];
        [atariButton addTarget:self action:@selector(clickItem:) forControlEvents:UIControlEventTouchUpInside];
        atariButton.hidden = YES;
//        atariButton.alpha = 0.5;    // for test
        [atariView addSubview:atariButton];
        [atariButtonArray addObject:atariButton];

        // アイテム名表示
        CGRect rect = CGRectMake(x, 20, textSize[i].width, textSize[i].height);
        UILabel *textView = [[UILabel alloc] initWithFrame:rect];
        textView.font = font;
        textView.text = [dataManager getPlayItemName:currentPage :playFindItem[i].itemIndex];
        textView.alpha = 0.0;
        textView.textColor = [UIColor whiteColor];
        textView.backgroundColor = [UIColor clearColor];
        [baseView addSubview:textView];
        [textViewArray addObject:textView];
        [textView release];
        
        x += textSize[i].width + margin;
    }
}

// アイテムクリック
- (void)clickItem:(id)inSender
{
    int i;
    for (i = 0; i < FIND_ITEM_NUM; i++) {
        if (inSender == [atariButtonArray objectAtIndex:i])
            break;
    }
    if (i == FIND_ITEM_NUM)
        return;
    
    playFindItem[i].findFlag = YES;
    [[atariButtonArray objectAtIndex:i] setHidden:YES];

    // 当たりパーティクル再生
    CGRect rect = [dataManager getPlayAtariRect:currentPage :playFindItem[i].itemIndex];
    [self viewAtariParticle:CGPointMake(rect.origin.x + rect.size.width / 2, rect.origin.y + rect.size.height / 2)];

    // 当たり効果音再生
    self.atariAudio = [[soundManager initSound:@"doko_word_ok"] autorelease];
    atariAudio.numberOfLoops = 0;
    atariAudio.volume = 1.0;
    [atariAudio play];

    // 当たりアイテム名再生
    self.itemAudio = [[soundManager initSound:[dataManager getPlaySoundFile:currentPage :playFindItem[i].itemIndex]] autorelease];
    itemAudio.numberOfLoops = 0;
    itemAudio.volume = 1.0;
    [itemAudio play];

    // 当たりテキスト消去
    [UIView animateWithDuration:3.0
                     animations:^ {
                         [[textViewArray objectAtIndex:i] setAlpha:0.0];                         
                     }];

    // コンプリートチェック
    for (i = 0; i < FIND_ITEM_NUM; i++) {
        if (!playFindItem[i].findFlag)
            break;
    }
    if (i == FIND_ITEM_NUM)
        [self performSelector:@selector(playComplete) withObject:nil afterDelay:3.0];
}

//　当たりパーティクル再生
- (void)viewAtariParticle:(CGPoint)point
{
    NSArray *imageArray = [[NSArray alloc] initWithObjects:
                           [UIImage imageNamed:@"fx_atari_a0001.png"],
                           [UIImage imageNamed:@"fx_atari_a0002.png"],
                           [UIImage imageNamed:@"fx_atari_a0003.png"],
                           [UIImage imageNamed:@"fx_atari_a0004.png"],
                           [UIImage imageNamed:@"fx_atari_a0005.png"],
                           [UIImage imageNamed:@"fx_atari_a0006.png"],
                           [UIImage imageNamed:@"fx_atari_a0007.png"],
                           [UIImage imageNamed:@"fx_atari_a0008.png"],
                           [UIImage imageNamed:@"fx_atari_a0009.png"],
                           [UIImage imageNamed:@"fx_atari_a0010.png"],
                           [UIImage imageNamed:@"fx_atari_a0011.png"],
                           [UIImage imageNamed:@"fx_atari_a0012.png"],
                           [UIImage imageNamed:@"fx_atari_a0013.png"],
                           [UIImage imageNamed:@"fx_atari_a0014.png"],
                           [UIImage imageNamed:@"fx_atari_a0015.png"],
                           [UIImage imageNamed:@"fx_atari_a0016.png"],
                           [UIImage imageNamed:@"fx_atari_a0017.png"],
                           [UIImage imageNamed:@"fx_atari_a0018.png"],
                           nil];
    CGRect rect = CGRectMake(point.x - 143, point.y - 102, 288, 288);
    UIImageView *atariAnimation = [[UIImageView alloc] initWithFrame:rect];
    atariAnimation.animationImages = imageArray;
    atariAnimation.animationRepeatCount = 1;
    atariAnimation.animationDuration = 3.0;
    [bgView addSubview:atariAnimation];
    [atariAnimation startAnimating];
    [imageArray release];
    
    [self performSelector:@selector(endAtariParticle:) withObject:atariAnimation afterDelay:3.0];
}

// 当たりパーティクル再生終了
- (void)endAtariParticle:(UIImageView *)atariAnimation
{
    [atariAnimation removeFromSuperview];
    [atariAnimation release];
}

// コンプリート画面処理
- (void)playComplete
{
    if (completeStopFlag)
        return;
    
    if (dataManager.lang == LANG_JPN) {
        completeImageView = completeJpnImageView;
        completeImageView.frame = CGRectMake(244, 351, completeImageView.frame.size.width, completeImageView.frame.size.height);
    } else {
        completeImageView = completeEngImageView;
        completeImageView.frame = CGRectMake(215, 351, completeImageView.frame.size.width, completeImageView.frame.size.height);
    }
    completeImageView.alpha = 0.0;        
    [self.view addSubview:completeImageView];
    
    [UIView animateWithDuration:0.5
                     animations:^ {
                         completeImageView.alpha = 1.0;                         
                     }
                     completion:^ (BOOL finished) {
                         [self playComplete2];
                     }];
}

// コンプリート画面処理2
- (void)playComplete2
{
    if (completeStopFlag)
        return;
    
    // BGM停止
    [bgmAudio stop];
    
    // コンプリート音
    self.completeAudio = [[soundManager initSound:@"kddk1a_comp"] autorelease];
    [completeAudio play];

    [UIView animateWithDuration:1.5
                          delay:4.0
                        options:0
                     animations:^ {
                         completeImageView.alpha = 0.0;                         
                     }
                     completion:^ (BOOL finished) {
                         [self playComplete4];
                     }];

    completeCounter = 0;
    for (int i = 0; i < FIND_ITEM_NUM; i++) {
        CGPoint point;
        [self performSelector:@selector(playComplete3) withObject:nil afterDelay:i * 0.3];
        [self viewAtariParticle:point];
    }
}

// コンプリート画面処理3
- (void)playComplete3
{
    if (completeStopFlag)
        return;
    
    CGRect rect = [dataManager getPlayAtariRect:currentPage :playFindItem[completeCounter++].itemIndex];
    CGPoint point = CGPointMake(rect.origin.x + rect.size.width / 2, rect.origin.y + rect.size.height / 2);
    [self viewAtariParticle:point];    
}

// コンプリート画面処理4
- (void)playComplete4
{
    if (completeStopFlag)
        return;
    
    [UIView animateWithDuration:2.0
                     animations:^ {
                         self.view.alpha = 0.0;                         
                     }
                     completion:^ (BOOL finished) {
                         [self playComplete5];
                     }];
}

// コンプリート画面処理5
- (void)playComplete5
{
    if (completeStopFlag)
        return;
    
    [completeImageView removeFromSuperview];
    
    if (++currentPage == PAGE_NUM) {
        dataManager.playCurrentPage = 0;
        [self returnMenu];
    } else {    
        [self stopPlayMode];
        [self startPlayMode];
    }
}

//　メニューに戻る
- (void)returnMenu
{
    readStopFlag = YES;
    enableTouch = NO;
    self.view.userInteractionEnabled = NO;
    
    [UIView animateWithDuration:0.5
                     animations:^ {
                         self.view.alpha = 0.0;                         
                     }
                     completion:^ (BOOL finished) {
                         [self endReturnMenu];
                     }];
}

// メニューに戻る
- (void)endReturnMenu
{
    // あそぶモード停止
    [self stopPlayMode];
    
    // 現在の状態を保存
    [dataManager saveData];
    
    // 親コントローラに通知
    [self.parentViewController performSelector:@selector(playMenuBgSound)];
    
    // 元の画面に戻る
    [self dismissModalViewControllerAnimated:NO];
}

// 画面をダブルクリック
- (void)touchesBegan:(NSSet *)touches withEvent:(UIEvent *)event
{
    if (!enableTouch)
        return;
    
    UITouch *touch = [touches anyObject];
    switch ([touch tapCount]) {
        case 1:
            if (indexView.openFlag) {
                [soundManager playCloseSound];
                [indexView closeIndex:YES :nil];
            }
            break;
        case 2:
            if (scrollView.zoomScale != 1.0) {
                [scrollView zoomToRect:CGRectMake(0, 0, SCREEN_WIDTH, SCREEN_HEIGHT) animated:YES];
            } else {
                CGPoint point = [touch locationInView:bgMovie.view];
                [scrollView zoomToRect:CGRectMake(point.x / 2, point.y / 2, SCREEN_WIDTH / 2, SCREEN_HEIGHT / 2) animated:YES];
            }
            break;
//        case 3: // for test
//            [scrollView zoomToRect:CGRectMake(0, 0, SCREEN_WIDTH, SCREEN_HEIGHT) animated:YES];
//            [bgView bringSubviewToFront:atariView];
//            break;
    }
}

// インデックスボタンクリック
- (IBAction)indexButton:(id)sender
{
    if (indexView.openFlag) {
        [soundManager playCloseSound];
        [indexView closeIndex:YES: nil];
    } else {
        [soundManager playOpenSound];
        [indexView openIndex:currentPage:YES];
    }
}

// ページ替え
- (void)selectPage:(int)page
{
    currentPage = page;

    [indexView closeIndex:YES :@selector(endSelectPage)];
}

- (void)endSelectPage
{
    [UIView animateWithDuration:0.5
                     animations:^ {
                         self.view.alpha = 0.0;
                     }
                     completion:^(BOOL finished) {
                         [self stopPlayMode];
                         [self startPlayMode];
                     }];
}

// メニューへ戻るボタンクリック
- (IBAction)endButton:(id)sender
{
    // 閉じる音
    [soundManager playCloseSound];

    [self returnMenu];
}

- (UIView *)viewForZoomingInScrollView:(UIScrollView *)_scrollView 
{
	return bgView;
}

- (void)viewDidUnload
{
    [super viewDidUnload];
    // Release any retained subviews of the main view.
    // e.g. self.myOutlet = nil;
}

- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)interfaceOrientation
{
    // Return YES for supported orientations
    return interfaceOrientation == UIInterfaceOrientationLandscapeLeft || interfaceOrientation == UIInterfaceOrientationLandscapeRight;
}

@end
