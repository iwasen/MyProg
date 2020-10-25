//
//  DokoViewController.m
//  Doko
//
//  Created by aizawa on 11/04/25.
//  Copyright 2011 TRC. All rights reserved.
//

#import "DokoViewController.h"
#import "PlayModeViewController.h"
#import "ReadModeViewController.h"
#import "SoundManager.h"

@implementation DokoViewController

@synthesize bgAudio;

- (void)dealloc
{
    // Outlet
    [logoView release];
    [titleBaseView release];
    [titleImageView release];
    [submenuView release];
    [aboutView release];
    [howtoView release];
    [optionView release];
    [playModeButton release];
    [readModeButton release];
    [aboutButton release];
    [howtoButton release];
    [optionButton release];
    [nihongoButton release];
    [eigoButton release];
    [shokyoButton release];
    [shokyoView release];
    [shokyoSuruButton release];
    [shokyoShinaiButton release];
    [aboutImageView release];
    [optionImageView release];
    [shokyoImageView release];
    [shokyoShimashitaView release];
    [websiteButton release];
    [submenuImageView release];

    self.bgAudio = nil;

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
    
    [self setLanguage];
    
    // サブメニュー消去
    [submenuView closeSubmenu:NO];

    if (openingSkipFlag) {
        // メモリ不足で２度目に呼ばれた場合
        [self.view addSubview:titleBaseView];
        [self animationSubmenuButton];
    } else {
        openingSkipFlag = YES;

        // ロゴ表示
        [self.view addSubview:logoView];

        // ロゴをフェードアウト
        [UIView animateWithDuration:0.5
                              delay:0.5
                            options:0
                         animations:^ {
                             logoView.alpha = 0.0;
                         }
                         completion:^(BOOL finished) {
                             [self startOpeningMovie];
                         }];
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
    // Return YES for supported orientations
    return interfaceOrientation == UIInterfaceOrientationLandscapeLeft || interfaceOrientation == UIInterfaceOrientationLandscapeRight;
}

// オープニングムービー開始
- (void)startOpeningMovie
{
    // ロゴを消去
    [logoView removeFromSuperview];

    // オープニングムービー再生
	NSString *moviePath = [[NSBundle mainBundle] pathForResource:dataManager.lang == LANG_JPN ? @"kddk1j_op" : @"kddk1e_op" ofType:@"mp4"];
	NSURL *url = [NSURL fileURLWithPath:moviePath];
	openingMovie = [[MPMoviePlayerController alloc] initWithContentURL:url];
	[[NSNotificationCenter defaultCenter] addObserver:self
											 selector:@selector(endOpeningMovie)
												 name:MPMoviePlayerPlaybackDidFinishNotification
											   object:openingMovie];
	openingMovie.view.frame = CGRectMake(0, 0, SCREEN_WIDTH, SCREEN_HEIGHT);
	openingMovie.controlStyle = MPMovieControlStyleNone;
    openingMovie.view.userInteractionEnabled = NO;
	[self.view addSubview:openingMovie.view];
	[openingMovie play];

    // オープニングムービー再生中フラグセット
    inOpeningMovie = YES;    
}

// スリープ解除
- (void)wakeup
{
    if (inOpeningMovie) {
        [[NSNotificationCenter defaultCenter] removeObserver:self 
                                                        name:MPMoviePlayerPlaybackDidFinishNotification
                                                      object:openingMovie];
        [openingMovie stop];
        [[NSNotificationCenter defaultCenter] addObserver:self
                                                 selector:@selector(endOpeningMovie)
                                                     name:MPMoviePlayerPlaybackDidFinishNotification
                                                   object:openingMovie];
        [openingMovie play];
    }
}

// オープニングムービー終了
- (void)endOpeningMovie
{
    // オープニングムービー再生中フラグリセット
    inOpeningMovie = NO;

    // タイトル画面を表示
    titleBaseView.alpha = 0.0;
    [self.view addSubview:titleBaseView];

    // タイトル画面フェードイン
    [UIView animateWithDuration:1.0
                     animations:^ {
                         titleBaseView.alpha = 1.0;
                     }
                     completion:^(BOOL finished) {
                         [[NSNotificationCenter defaultCenter] removeObserver:self 
                                                                         name:MPMoviePlayerPlaybackDidFinishNotification
                                                                       object:openingMovie];
                         [openingMovie.view removeFromSuperview];
                         [openingMovie release];
                         openingMovie = nil;
                     }];
    
    // BGM再生
    [self playMenuBgSound];
    
    // サブメニューボタンアニメーション
    [self animationSubmenuButton];
}

// 画面タッチ
- (void)touchesBegan:(NSSet *)touches withEvent:(UIEvent *)event
{
    // オープニングムービー再生中なら停止
    if (inOpeningMovie) {
        [openingMovie stop];
    }

    // サブメニュー表示中なら消去
    if (aboutButton.selected)
        ;
    else if (howtoButton.selected)
        [self closeHowtoView];
    else if(optionButton.selected)
        [self closeOptionView];
    else if (submenuView.openFlag) {
        [soundManager playCloseSound];
        [submenuView closeSubmenu:YES];
    }
}

// 言語設定
- (void)setLanguage
{
    titleImageView.image = [dataManager getImage:@"kddk1j_op" :@"kddk1e_op"];
    [playModeButton setImage:[dataManager getImage:@"kddk1j_btn_asob___x100y340w376h229" :@"kddk1e_btn_asob___x100y340w376h229"] forState:UIControlStateNormal];
    [playModeButton setImage:[dataManager getImage:@"kddk1j_btn_asob_r___x100y340w376h229" :@"kddk1e_btn_asob_r___x100y340w376h229"] forState:UIControlStateHighlighted];
    [readModeButton setImage:[dataManager getImage:@"kddk1j_btn_yomu___x532y340w376h229" :@"kddk1e_btn_yomu___x100y340w376h229"] forState:UIControlStateNormal];
    [readModeButton setImage:[dataManager getImage:@"kddk1j_btn_yomu_r___x100y340w376h229" :@"kddk1e_btn_yomu_r___x100y340w376h229"] forState:UIControlStateHighlighted];
    [aboutButton setImage:[dataManager getImage:@"kddk1j_subber_btn_about_n___x62y706w240h52" :@"kddk1e_subber_btn_about_n___x62y706w240h52"] forState:UIControlStateNormal];
    [aboutButton setImage:[dataManager getImage:@"kddk1j_subber_btn_about_r___x62y706w240h52" :@"kddk1e_subber_btn_about_r___x62y706w240h52"] forState:UIControlStateHighlighted];
    [aboutButton setImage:[dataManager getImage:@"kddk1j_subber_btn_about_c___x62y706w240h52" :@"kddk1e_subber_btn_about_c___x62y706w240h52"] forState:UIControlStateSelected];
    [howtoButton setImage:[dataManager getImage:@"kddk1j_subber_btn_howto_n___x410y704w200h62" :@"kddk1e_subber_btn_howto_n___x410y704w200h62"] forState:UIControlStateNormal];
    [howtoButton setImage:[dataManager getImage:@"kddk1j_subber_btn_howto_r___x410y704w200h62" :@"kddk1e_subber_btn_howto_r___x410y704w200h62"] forState:UIControlStateHighlighted];
    [howtoButton setImage:[dataManager getImage:@"kddk1j_subber_btn_howto_c___x410y704w200h62" :@"kddk1e_subber_btn_howto_c___x410y704w200h62"] forState:UIControlStateSelected];
    [optionButton setImage:[dataManager getImage:@"kddk1j_subber_btn_option_n___x790y706w134h56" :@"kddk1e_subber_btn_option_n___x790y706w134h56"] forState:UIControlStateNormal];
    [optionButton setImage:[dataManager getImage:@"kddk1j_subber_btn_option_r___x790y706w134h56" :@"kddk1c_subber_btn_option_r___x790y706w134h56"] forState:UIControlStateHighlighted];
    [optionButton setImage:[dataManager getImage:@"kddk1j_subber_btn_option_c___x790y706w134h56" :@"kddk1c_subber_btn_option_c___x790y706w134h56"] forState:UIControlStateSelected];
    aboutImageView.image = [dataManager getImage:@"kddk1j_subberpopup_about___x62y18w909h683" :@"kddk1e_subberpopup_about___x62y18w909h683"];
    howtoView.image = [dataManager getImage:@"kddk1j_subberpopup_howto___x62y80w901h622" :@"kddk1e_subberpopup_howto___x62y80w901h622"];
    optionImageView.image = [dataManager getImage:@"kddk1j_subberpopup_option___x510y158" :@"kddk1e_subberpopup_option___x510y158"];
    [nihongoButton setImage:[dataManager getImage:@"kddk1j_subberpopup_option_r_japanese___x164y72w128h49" :@"kddk1e_subberpopup_option_r_japanese___x164y72w128h49"] forState:UIControlStateSelected];
    [eigoButton setImage:[dataManager getImage:@"kddk1j_subberpopup_option_r_english___x292y72w121h49" :@"kddk1e_subberpopup_option_r_english___x292y72w121h49"] forState:UIControlStateSelected];
    shokyoImageView.image = [dataManager getImage:@"kddk1j_subberpopup_option_r_erase_menu___x653y323w291h252" :@"kddk1e_subberpopup_option_r_erase_menu___x653y323w291h252"];
    shokyoShimashitaView.image = [dataManager getImage:@"kddk1j_subberpopup_option_r_erase_menu_deleted___x726y499w151h27" :@"kddk1e_subberpopup_option_r_erase_menu_deleted___x726y499w151h27"];
    [shokyoSuruButton setImage:[dataManager getImage:@"kddk1j_subberpopup_option_r_erase_menu_yes___x673y425w124h49" :@"kddk1e_subberpopup_option_r_erase_menu_yes___x673y425w124h49"] forState:UIControlStateSelected];
    [shokyoShinaiButton setImage:[dataManager getImage:@"kddk1j_subberpopup_option_r_erase_menu_no___x798y425w128h49" :@"kddk1e_subberpopup_option_r_erase_menu_no___x798y425w128h49"] forState:UIControlStateSelected];
    [websiteButton setImage:[dataManager getImage:@"kddk1j_btn_website_n___x737y569w187h28" :@"kddk1e_btn_website_n___x737y569w187h28"] forState:UIControlStateNormal];
    [websiteButton setImage:[dataManager getImage:@"kddk1j_btn_website02_r___x737y569w187h28" :@"kddk1e_btn_website_r___x737y569w187h28"] forState:UIControlStateHighlighted];
}

// よむモードボタンダウン
- (void)touchDownReadButton:(id)sender
{
    // 効果音再生
    [soundManager playReadSound];
}

// よむモードボタンアップ
- (void)touchUpReadButton:(id)sender
{
    // タイトル画面をフェードアウト
    [UIView animateWithDuration:1.0
                     animations:^ {
                         self.view.alpha = 0.0;                         
                     }
                     completion:^ (BOOL finished) {
                         [self gotoReadMode];
                     }];
}

// よむモード開始
- (void)gotoReadMode
{
    // サブメニューによる画面を閉じる
    [self closeAboutView];
    [self closeHowtoView];
    [self closeOptionView];

    // よむモードの画面へ遷移
    ReadModeViewController *readMode = [[ReadModeViewController alloc]
                                        initWithNibName:@"ReadModeViewController"
                                        bundle:[NSBundle mainBundle]];
    [self presentModalViewController:readMode animated:NO];
    [readMode release];

    // フェードアウトしたalpha値を戻しておく
    self.view.alpha = 1.0;

    // サブメニューを閉じる
    [submenuView closeSubmenu:NO];

    // BGM停止
    [bgAudio stop];
    self.bgAudio = nil;
}

// あそぶモードボタンダウン
- (void)touchDownPlayButton:(id)sender
{
    // 効果音再生
    [soundManager playPlaySound];
}

// あそぶモードボタンアップ
- (void)touchUpPlayButton:(id)sender
{
    // タイトル画面をフェードアウト
    [UIView animateWithDuration:1.0
                     animations:^ {
                         self.view.alpha = 0.0;                         
                     }
                     completion:^ (BOOL finished) {
                         [self gotoPlayMode];
                     }];
}

// あそぶモード開始
- (void)gotoPlayMode
{
    // サブメニューによる画面を閉じる
    [self closeAboutView];
    [self closeHowtoView];
    [self closeOptionView];
    
    // あそぶモードの画面へ遷移
    PlayModeViewController *playMode = [[PlayModeViewController alloc]
                                            initWithNibName:@"PlayModeViewController"
                                            bundle:[NSBundle mainBundle]];
    [self presentModalViewController:playMode animated:NO];
    [playMode release];

    // フェードアウトしたalpha値を戻しておく
    self.view.alpha = 1.0;

    // サブメニューを閉じる
    [submenuView closeSubmenu:NO];

    // BGM停止
    [bgAudio stop];
    self.bgAudio = nil;
}

// サブメニューボタン
- (void)submenuButton:(id)sender
{
    if (submenuView.openFlag) {
        // オープン中ならクローズ
        [soundManager playCloseSound];
        [submenuView closeSubmenu:YES];
    } else {
        // クローズ中ならオープン
        [soundManager playOpenSound];
        [submenuView openSubmenu:YES];
    }
}

// ほんについてボタン
- (IBAction)aboutButton:(id)sender
{
    [self openAboutView];
}

// あそびかたボタン
- (IBAction)howtoButton:(id)sender
{
    [self openHowtoView];    
}

// 設定ボタン
- (IBAction)optionButton:(id)sender
{
    [self openOptionView];
}

// ほんについての画面を表示
- (void)openAboutView
{
    if (!aboutButton.selected) {
        [soundManager playOpenSound];
        [self closeHowtoView];
        [self closeOptionView];
        aboutView.frame = CGRectMake(62, 18, aboutView.frame.size.width, aboutView.frame.size.height);
        [self.view addSubview:aboutView];
        aboutButton.selected = YES;
        playModeButton.enabled = FALSE;
        readModeButton.enabled = FALSE;
    }
}

// ほんについての画面を閉じる
- (void)closeAboutView
{
    if (aboutButton.selected) {
        [soundManager playCloseSound];
        [aboutView removeFromSuperview];
        aboutButton.selected = NO;    
        playModeButton.enabled = YES;
        readModeButton.enabled = YES;
    }
}

// あそびかたの画面を表示
- (void)openHowtoView
{
    if (!howtoButton.selected) {
        [soundManager playOpenSound];
        [self closeAboutView];
        [self closeOptionView];
        howtoView.frame = CGRectMake(62, 80, howtoView.frame.size.width, howtoView.frame.size.height);
        [self.view addSubview:howtoView];
        howtoButton.selected = YES;
        playModeButton.enabled = FALSE;
        readModeButton.enabled = FALSE;
    }
}

// あそびかたの画面を閉じる
- (void)closeHowtoView
{
    if (howtoButton.selected) {
        [soundManager playCloseSound];
        [howtoView removeFromSuperview];
        howtoButton.selected = NO;    
        playModeButton.enabled = YES;
        readModeButton.enabled = YES;
    }
}

// 設定画面を開く
- (void)openOptionView
{
    if (!optionButton.selected) {
        [soundManager playOpenSound];
        [self closeAboutView];
        [self closeHowtoView];
        nihongoButton.selected = dataManager.lang == LANG_JPN;
        eigoButton.selected = dataManager.lang == LANG_ENG;
        shokyoButton.selected = NO;
        optionView.frame = CGRectMake(510, 158, optionView.frame.size.width, optionView.frame.size.height);
        [self.view addSubview:optionView];
        optionButton.selected = YES;
        playModeButton.enabled = FALSE;
        readModeButton.enabled = FALSE;
        optionView.userInteractionEnabled = YES;
    }
}

// 設定画面を閉じる
- (void)closeOptionView
{
    if (optionButton.selected) {
        [soundManager playCloseSound];
        [optionView removeFromSuperview];
        [shokyoView removeFromSuperview];
        optionButton.selected = NO;    
        playModeButton.enabled = YES;
        readModeButton.enabled = YES;
    }
}

// BGM再生
- (void)playMenuBgSound
{
    // BGM再生
	self.bgAudio = [[soundManager initSound:@"kddk1a_loop_menu"] autorelease];
    bgAudio.numberOfLoops = -1;
    [bgAudio play];
}

// ウェブサイトへボタン
- (IBAction)websiteButton:(id)sender
{
    [dataManager saveData];
    [[UIApplication sharedApplication] openURL:[NSURL URLWithString:dataManager.lang == LANG_JPN ? @"http://www.bookclub.kodansha.co.jp/books/topics/doko/index.html" : @"http://children.kodansha.co.jp/en/index.html"]];
}

// 日本語ボタン
- (IBAction)nihongoButton:(id)sender
{
    nihongoButton.selected = YES;
    eigoButton.selected = NO;
    if (dataManager.lang != LANG_JPN) {
        dataManager.lang = LANG_JPN;
        [self setLanguage];
        [dataManager clearData];
    }
}

// 英語ボタン
- (IBAction)eigoButton:(id)sender
{
    nihongoButton.selected = NO;
    eigoButton.selected = YES;    
    if (dataManager.lang != LANG_ENG) {
        dataManager.lang = LANG_ENG;
        [self setLanguage];
        [dataManager clearData];
    }
}

// 消去ボタン
- (IBAction)shokyoButton:(id)sender
{
    shokyoView.frame = CGRectMake(653, 323, shokyoView.frame.size.width, shokyoView.frame.size.height);
    shokyoSuruButton.selected = NO;
    shokyoShinaiButton.selected = NO;
    shokyoShimashitaView.hidden = YES;
    [self.view addSubview:shokyoView];
    optionView.userInteractionEnabled = NO;
}

// 消去するボタン
- (IBAction)shokyoSuruButton:(id)sender
{
    shokyoSuruButton.selected = YES;
    shokyoShinaiButton.selected = NO;

    [dataManager clearData];
    shokyoShimashitaView.hidden = NO;
}

// 消去しないボタン
- (IBAction)shokyoShinaiButton:(id)sender
{
    shokyoSuruButton.selected = NO;
    shokyoShinaiButton.selected = YES;
    [shokyoView removeFromSuperview];
    optionView.userInteractionEnabled = YES;
}

// ほんについて閉じるボタン
- (IBAction)aboutCloseButton:(id)sender
{
    [self closeAboutView];
}

// サブメニューボタンアニメーション
- (void)animationSubmenuButton
{
    [UIView animateWithDuration:1.5
                          delay:0
                        options:UIViewAnimationOptionAllowUserInteraction | UIViewAnimationOptionRepeat | UIViewAnimationOptionAutoreverse
                     animations:^ {
                         submenuImageView.alpha = 0.0;                         
                     }
                     completion:NULL];
}

- (void)viewWillDisappear:(BOOL)animated
{
    openingSkipFlag = YES;
    if (openingMovie) {
        [openingMovie stop];
        [openingMovie.view removeFromSuperview];
    }
}

@end
