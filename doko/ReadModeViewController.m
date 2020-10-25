//
//  ReadModeViewController.m
//  Doko
//
//  Created by 相沢 伸一 on 11/04/26.
//  Copyright 2011 TRC. All rights reserved.
//

#import "ReadModeViewController.h"
#import "SoundManager.h"

#define TEXT1_HEIGHT 690
#define TEXT2_HEIGHT 720
#define PAGE_SPACE  30
#define ZOOM_SPEED  10.0

@implementation SentenceData

@synthesize sentence, soundFile, viewIndex, lineIndex, viewSize;

- (void)dealloc
{
    [sentence release];
    [soundFile release];
    [super dealloc];
}

@end


@implementation ReadData

@synthesize textView, soundFile;

- (void)dealloc
{
    [textView removeFromSuperview];
    [textView release];
    [soundFile release];
    [super dealloc];
}

@end


@implementation ReadModeViewController

@synthesize bgmAudio, readAudio;

- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil
{
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {
        // Custom initialization
    }
    return self;
}

- (void)dealloc
{
    // Outlet release
    [guideView release];
    [guideImageView release];
    [startButton release];
    [scrollView release];
    [indexView release];
    [maskView release];
    [indexButton release];
    [endButton release];
    [endMarkView release];

    // その他release
    [bgMovie release];
    [textFont release];
    [midashiFont release];
    [sentenceArray release];
    [readDataArray release];
    self.bgmAudio = nil;
    self.readAudio = nil;
    
    [super dealloc];
}

- (void)didReceiveMemoryWarning
{
    // Releases the view if it doesn't have a superview.
    [super didReceiveMemoryWarning];
    
    // Release any cached data, images, etc that aren't in use.
}

#pragma mark - View lifecycle

- (void)viewDidLoad
{
    [super viewDidLoad];

    // 現在ページ取得
    currentPage = dataManager.readCurrentPage;

    // フォント作成
    textFont = [[UIFont fontWithName:@"HiraKakuProN-W6" size:20] retain];
    midashiFont = [[UIFont fontWithName:@"HiraKakuProN-W6" size:26] retain];

    // 配列初期化
    sentenceArray = [[NSMutableArray alloc] init];
    readDataArray = [[NSMutableArray alloc] init];

    // BGムービー初期化
    bgMovie = [[MPMoviePlayerController alloc] init];

    // スクロールビュー設定
    scrollView.delegate = self;
    scrollView.frame = CGRectMake(0, 0, SCREEN_WIDTH + PAGE_SPACE, SCREEN_HEIGHT);
    scrollView.contentSize = CGSizeMake((SCREEN_WIDTH + PAGE_SPACE) * (PAGE_NUM + 2), SCREEN_HEIGHT);

    // インデックス設定
    indexView.delegate = self;
    [indexView closeIndex:NO :nil];

    // 初回は操作ガイド表示
    if (!dataManager.readContinueFlag) {
        [self displayGuide];
        dataManager.readContinueFlag = YES;
    } else
        [self continueGuide];
}

// 操作ガイド表示
- (void)displayGuide
{
    guideImageView.image = [dataManager getImage:@"kddk1j_guide_yomu" :@"kddk1e_guide_yomu"];
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
                         
                         // 読むモード開始
                         [self startReadFirst];
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

    [self startReadFirst];
}

// よむモードの開始
- (void)startReadFirst
{
    endButton.hidden = NO;
    indexButton.hidden = NO;
    indexView.userInteractionEnabled = YES;
    scrollView.userInteractionEnabled = YES;
    enableTouch = YES;

    [self createBgView];
    [self startReadMode:YES];
}

// BGビュー作成
- (void)createBgView
{
    UIImageView *imageView;

    // BGビュー作成
    for (int i = 0; i < PAGE_NUM; i++) {
        UIView *view = [[UIView alloc] initWithFrame:CGRectMake((i + 1) * (SCREEN_WIDTH + PAGE_SPACE), 0, SCREEN_WIDTH, SCREEN_HEIGHT)];
        view.clipsToBounds = YES;
        [scrollView addSubview:view];
        bgViews[i] = view;
        [view release];
    }

    // 1ページ目の前にタイトル画面を入れる
    UIImage *image = [dataManager getImage:@"kddk1j_op" :@"kddk1e_op"];
    imageView = [[UIImageView alloc] initWithImage:image];
    imageView.frame = CGRectMake(0, 0, SCREEN_WIDTH, SCREEN_HEIGHT);
    [scrollView addSubview:imageView];
    [imageView release];

    // 最終ページ目の次にタイトル画面を入れる
    imageView = [[UIImageView alloc] initWithImage:image];
    imageView.frame = CGRectMake((PAGE_NUM + 1) * (SCREEN_WIDTH + PAGE_SPACE), 0, SCREEN_WIDTH, SCREEN_HEIGHT);
    [scrollView addSubview:imageView];
    [imageView release];
}

// 「よむモード」開始処理
- (void)startReadMode:(bool)fadeIn
{
    // ページ位置設定
    pageControlUsed = YES;
    [scrollView setContentOffset:CGPointMake((currentPage + 1) * (SCREEN_WIDTH + PAGE_SPACE), 0)];

    // 現在ページ保存
    dataManager.readCurrentPage = currentPage;
    
    // BGムービー再生
    [self startBgMovie:!fadeIn];

    // BGM再生
    self.bgmAudio = [[soundManager initSound:[dataManager getBgSoundFile:currentPage]] autorelease];
    bgmAudio.numberOfLoops = -1;
    bgmAudio.volume = 1.0;
    [bgmAudio play];

    if (fadeIn) {
        // viewフェードイン
        self.view.alpha = 0.0;
        [UIView animateWithDuration:2.0
                         animations:^ {
                             self.view.alpha = 1.0;                             
                         }
                         completion:^ (BOOL finished) {
                             [self startTextData];
                         }];
    } else {
        // テキスト再生
        [self startTextData];
    }
}

// 「よむモード」停止処理
- (void)stopReadMode
{
    [self stopTextData];
    [self stopBgMovie];
    [bgmAudio stop];
}

// BGムービー開始
- (void)startBgMovie:(bool)fadeIn
{
    // 背景静止画設定
    [self setBgImage:currentPage - 2];
    [self setBgImage:currentPage - 1];
    [self setBgImage:currentPage];
    [self setBgImage:currentPage + 1];
    [self setBgImage:currentPage + 2];
    
    // BGムービー設定
    NSString *path = [[NSBundle mainBundle] pathForResource:[dataManager getBgMovieFile:currentPage] ofType:@"mp4"];
    bgMovie.contentURL = [NSURL fileURLWithPath:path];
    CGPoint zoomPoint = [dataManager getReadZoomPoint:currentPage];
    float zoomRatio = [dataManager getReadZoomRatio:currentPage];
    bgMovie.view.frame = CGRectMake((SCREEN_WIDTH / 2 - zoomPoint.x) * zoomRatio, (SCREEN_HEIGHT / 2 - zoomPoint.y) * zoomRatio, SCREEN_WIDTH, SCREEN_HEIGHT);
    CGAffineTransform tf = bgMovie.view.transform;
    bgMovie.view.transform = CGAffineTransformScale(tf, zoomRatio, zoomRatio);
    bgMovie.controlStyle = MPMovieControlStyleNone;
    bgMovie.repeatMode = MPMovieRepeatModeOne;
    bgMovie.view.userInteractionEnabled = NO;
    bgImageViews[currentPage].alpha = fadeIn ? 1.0 : 0.0;
    [bgViews[currentPage] insertSubview:bgMovie.view atIndex:0];
    [bgMovie play];

    // ズームアウト
    [UIView animateWithDuration:ZOOM_SPEED
                          delay:2.0
                        options:UIViewAnimationOptionAllowUserInteraction
                     animations:^ {
                         bgMovie.view.transform = CGAffineTransformScale(tf, 1.0, 1.0);
                         bgMovie.view.frame = CGRectMake(0, 0, SCREEN_WIDTH, SCREEN_HEIGHT);                         
                     }
                     completion:NULL];

    // ムービーフェードイン
    if (fadeIn) {
        [UIView animateWithDuration:0.5
                              delay:0.7
                            options:0
                         animations:^ {
                             bgImageViews[currentPage].alpha = 0.0;                             
                         }
                         completion:NULL];
    }
}

// BGムービー停止
- (void)stopBgMovie
{
    // BGムービー停止
    [bgMovie stop];

    // ページ終了マーク消去
    [endMarkView removeFromSuperview];
}

// 背景の静止画を設定
- (void)setBgImage:(int)page
{
    if (page < 0 || page >= PAGE_NUM)
        return;
    
    UIImageView *imageView;
    NSString *path;
    if (bgImageViews[page] == nil) {
        path = [[NSBundle mainBundle] pathForResource:[dataManager getBgImageFile:page] ofType:@"png"];
        imageView = [[UIImageView alloc] initWithImage:[UIImage imageWithContentsOfFile:path]];
        CGPoint zoomPoint = [dataManager getReadZoomPoint:page];
        float zoomRatio = [dataManager getReadZoomRatio:page];
        imageView.frame = CGRectMake(SCREEN_WIDTH / 2 - zoomPoint.x * zoomRatio, SCREEN_HEIGHT / 2 - zoomPoint.y * zoomRatio, SCREEN_WIDTH * zoomRatio, SCREEN_HEIGHT * zoomRatio);
        [bgViews[page] addSubview:imageView];
        bgImageViews[page] = imageView;
        [imageView release];        
    } else
        bgImageViews[page].alpha = 1.0;
}

// テキスト表示／再生開始
- (void)startTextData
{
    sentenceCounter = 0;
    [self readTextData];
    [self setTextData];
}

// テキスト表示／再生停止
- (void)stopTextData
{
    [readAudio stop];
    self.readAudio = nil;
    [sentenceArray removeAllObjects];
    [readDataArray removeAllObjects];
}

// テキストファイル読み込み
- (void)readTextData
{
    NSString *path = [[NSBundle mainBundle] pathForResource:[dataManager getReadTextFile:currentPage] ofType:@"txt"];
    NSString *text = [NSString stringWithContentsOfFile:path encoding:NSShiftJISStringEncoding error:nil];
    NSArray *lines = [text componentsSeparatedByString:@"\r\n"];
    int viewIndex = 0;
    int lineIndex = 0;
    
    [sentenceArray removeAllObjects];
    
    for (NSString *lineText in lines) {
        lineText = [lineText stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceCharacterSet]];
        if (lineText.length != 0) {
            int sentenceIndex = 0;
            NSArray *sentences = [lineText componentsSeparatedByString:dataManager.lang == LANG_JPN ? @"," : @"@"];
            for (NSString *sentence in sentences) {
                if (sentence.length != 0) {
                    SentenceData *sentenceData = [[SentenceData alloc] init];
                    sentenceData.sentence = sentence;
                    sentenceData.soundFile = [dataManager getReadSentenceSoundFile:currentPage :viewIndex :lineIndex :sentenceIndex];
                    sentenceData.viewIndex = viewIndex;
                    sentenceData.lineIndex = lineIndex;
                    CGSize bounds = CGSizeMake(SCREEN_WIDTH, SCREEN_HEIGHT);
                    sentenceData.viewSize = [sentenceData.sentence sizeWithFont:viewIndex == 0 ? midashiFont : textFont constrainedToSize:bounds lineBreakMode:UILineBreakModeTailTruncation];
                    [sentenceArray addObject:sentenceData];
                    [sentenceData release];
                    sentenceIndex++;
                }
            }
            lineIndex++;
        } else {
            lineIndex = 0;
            viewIndex++;
        }
    }
}

// テキストデータ表示／再生設定
- (void)setTextData
{
    if (readCounter >= readDataArray.count) {
        if (sentenceCounter >= sentenceArray.count) {
            if (sentenceArray.count != 0)
                [bgViews[currentPage] addSubview:endMarkView];
            return;
        } else {
            [readDataArray removeAllObjects];

            SentenceData *sentenceData = [sentenceArray objectAtIndex:sentenceCounter];
            int viewIndex = sentenceData.viewIndex;

            int width1 = 0, width2 = 0;
            int sentenceCounter2 = sentenceCounter;
            while (sentenceCounter2 < sentenceArray.count) {
                sentenceData = [sentenceArray objectAtIndex:sentenceCounter2];
                
                if (viewIndex != sentenceData.viewIndex)
                    break;

                if (sentenceData.lineIndex == 0)
                    width1 += sentenceData.viewSize.width;
                else
                    width2 += sentenceData.viewSize.width;
                
                sentenceCounter2++;
            }
            
            int leftMargin = (SCREEN_WIDTH - MAX(width1, width2)) / 2;
            int x = leftMargin;
            int lineIndex = 0;
            
            while (sentenceCounter < sentenceArray.count) {
                sentenceData = [sentenceArray objectAtIndex:sentenceCounter];

                if (viewIndex != sentenceData.viewIndex)
                    break;
                
                if (lineIndex != sentenceData.lineIndex)
                    x = leftMargin;

                ReadData *readData = [[ReadData alloc] init];
                readData.textView = [[UILabel alloc] init];
                readData.soundFile = sentenceData.soundFile;

                [readData.textView setFont:sentenceCounter == 0 ? midashiFont : textFont];
                [readData.textView setFrame:CGRectMake(x, sentenceData.lineIndex == 0 ? TEXT1_HEIGHT : TEXT2_HEIGHT, sentenceData.viewSize.width, sentenceData.viewSize.height)];
                readData.textView.text = sentenceData.sentence;
                readData.textView.textColor = [UIColor whiteColor];
                readData.textView.backgroundColor = [UIColor clearColor];
                readData.textView.shadowColor = [UIColor blackColor];
                readData.textView.shadowOffset = CGSizeMake(1, 1);
                [bgViews[currentPage] addSubview:readData.textView];
                [readDataArray addObject:readData];
                [readData release];

                sentenceCounter++;

                x += sentenceData.viewSize.width;
                lineIndex = sentenceData.lineIndex;
            }

            readCounter = 0;
        }
    }

    ReadData *readData = [readDataArray objectAtIndex:readCounter];

    // 文字の色替え
    [readData.textView setTextColor:[UIColor colorWithRed:1.0 green:0.6 blue:0.7 alpha:1.0]];
    
    // 音声再生
    self.readAudio = [[soundManager initSound:readData.soundFile] autorelease];
    readAudio.delegate = self;
    [readAudio play];
    
    readCounter++;
}

// 音声再生終了
- (void)audioPlayerDidFinishPlaying: (AVAudioPlayer*)avap successfully: (BOOL)flag
{
    [self setTextData];
}

// スクロールによるページ替え
- (void)scrollViewDidScroll:(UIScrollView *)sender
{
    // ページ制御中なら何もしない
    if (pageControlUsed)
        return;

    // スクロール中でなければ効果音再生
    if (!scrollFlag) {
        scrollFlag = YES;
        [soundManager playOpenSound];
    }

    // スクロールが終わったらそのページを開始
    int offset = floor(scrollView.contentOffset.x);
    if (offset % (SCREEN_WIDTH + PAGE_SPACE) == 0) {
        scrollFlag = NO;
        
        int n = offset / (SCREEN_WIDTH + PAGE_SPACE);
        if (n == 0 || n == PAGE_NUM + 1) {
            [self returnMenu:NO];
        } else {
            if (currentPage != n - 1) {
                currentPage = n - 1;
                [self stopReadMode];
                [self startReadMode:NO];
                if (indexView.openFlag)
                    [indexView setCurrentPage:currentPage];
            }
        }
    }
}

- (void)scrollViewWillBeginDragging:(UIScrollView *)scrollView {
    pageControlUsed = NO;
}

- (void)scrollViewDidEndDecelerating:(UIScrollView *)scrollView {
    pageControlUsed = NO;
}

// メインメニューに戻る
- (void)returnMenu:(bool)fadeout
{
    if (fadeout) {
        enableTouch = NO;
        self.view.userInteractionEnabled = NO;
        [UIView animateWithDuration:0.5
                         animations:^ {
                             self.view.alpha = 0.0;                             
                         }
                         completion:^ (BOOL finished) {
                             [self endReturnMenu];
                         }];
    } else
        [self performSelector:@selector(endReturnMenu) withObject:nil afterDelay:0.0];
}

// メインメニューに戻る
- (void)endReturnMenu
{
    indexView.delegate = nil;
    
    // よむモード停止処理
    [self stopReadMode];
    
    // 現在の状態を保存
    [dataManager saveData];
    
    // 親コントローラに通知
    [self.parentViewController performSelector:@selector(playMenuBgSound)];
    
    // この画面を閉じる
    [self dismissModalViewControllerAnimated:NO];    
}

// インデックスからページ選択
- (void)selectPage:(int)page
{
    currentPage = page;
 
    [soundManager playOpenSound];
    [indexView closeIndex:YES :@selector(endSelectPage)];
}

// ページ選択後よむモード開始
- (void)endSelectPage
{
    [UIView animateWithDuration:0.5
                     animations:^ {
                         self.view.alpha = 0.0;
                     }
                     completion:^(BOOL finished) {
                         [self stopReadMode];
                         [self startReadMode:YES];                             
                     }];
}

// インデックスボタン処理
- (IBAction)indexButton:(id)sender
{
    if (indexView.openFlag) {
        // インデックスが開いているなら閉じる
        [soundManager playCloseSound];
        [indexView closeIndex:YES :nil];
    } else {
        // インデックスが閉じているなら開く
        [soundManager playOpenSound];
        [indexView openIndex:currentPage:YES];
    }
}

// 終了ボタン
- (IBAction)endButton:(id)sender
{
    // 閉じる音
    [soundManager playCloseSound];
    
    [self returnMenu:YES];
}

// 画面タッチ
- (void)touchesBegan:(NSSet *)touches withEvent:(UIEvent *)event
{
    if (!enableTouch)
        return;

    UITouch *touch = [touches anyObject];
    if ([touch tapCount] == 1) {
        // インデックス表示中なら閉じる
        if (indexView.openFlag) {
            [soundManager playCloseSound];
            [indexView closeIndex:YES :nil];
        }
    }
}

- (void)viewDidUnload
{
    [super viewDidUnload];
    // Release any retained subviews of the main view.
    // e.g. self.myOutlet = nil;
}

- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)interfaceOrientation
{
    return interfaceOrientation == UIInterfaceOrientationLandscapeLeft || interfaceOrientation == UIInterfaceOrientationLandscapeRight;
}

@end
