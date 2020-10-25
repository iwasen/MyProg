//
//  DokoViewController.h
//  Doko
//
//  Created by aizawa on 11/04/25.
//  Copyright 2011 TRC. All rights reserved.
//

#import <UIKit/UIKit.h>
#import <MediaPlayer/MediaPlayer.h>
#import <AVFoundation/AVFoundation.h>
#import "SubmenuView.h"

@interface DokoViewController : UIViewController {
    IBOutlet UIImageView *logoView;
    IBOutlet UIView *titleBaseView;
    IBOutlet UIImageView *titleImageView;
    IBOutlet SubmenuView *submenuView;
    IBOutlet UIView *aboutView;
    IBOutlet UIImageView *howtoView;
    IBOutlet UIView *optionView;
    IBOutlet UIButton *playModeButton;
    IBOutlet UIButton *readModeButton;
    IBOutlet UIButton *aboutButton;
    IBOutlet UIButton *howtoButton;
    IBOutlet UIButton *optionButton;
    IBOutlet UIButton *nihongoButton;
    IBOutlet UIButton *eigoButton;
    IBOutlet UIButton *shokyoButton;
    IBOutlet UIView *shokyoView;
    IBOutlet UIButton *shokyoSuruButton;
    IBOutlet UIButton *shokyoShinaiButton;
    IBOutlet UIImageView *aboutImageView;
    IBOutlet UIImageView *optionImageView;
    IBOutlet UIImageView *shokyoImageView;
    IBOutlet UIImageView *shokyoShimashitaView;
    IBOutlet UIButton *websiteButton;
    IBOutlet UIImageView *submenuImageView;

	MPMoviePlayerController *openingMovie;  // オープニングムービー
	AVAudioPlayer *bgAudio;     // BGM音
    bool inOpeningMovie;        // オープニングムービー再生中フラグ
    bool openingSkipFlag;
}

@property (retain, nonatomic) AVAudioPlayer *bgAudio;

- (void)startOpeningMovie;
- (void)endOpeningMovie;
- (void)setLanguage;
- (void)gotoReadMode;
- (void)gotoPlayMode;
- (IBAction)touchDownReadButton:(id)sender;
- (IBAction)touchUpReadButton:(id)sender;
- (IBAction)touchDownPlayButton:(id)sender;
- (IBAction)touchUpPlayButton:(id)sender;
- (IBAction)submenuButton:(id)sender;
- (IBAction)aboutButton:(id)sender;
- (IBAction)howtoButton:(id)sender;
- (IBAction)optionButton:(id)sender;
- (IBAction)websiteButton:(id)sender;
- (IBAction)nihongoButton:(id)sender;
- (IBAction)eigoButton:(id)sender;
- (IBAction)shokyoButton:(id)sender;
- (IBAction)shokyoSuruButton:(id)sender;
- (IBAction)shokyoShinaiButton:(id)sender;
- (IBAction)aboutCloseButton:(id)sender;
- (void)openAboutView;
- (void)closeAboutView;
- (void)openHowtoView;
- (void)closeHowtoView;
- (void)openOptionView;
- (void)closeOptionView;
- (void)playMenuBgSound;
- (void)animationSubmenuButton;
- (void)wakeup;

@end
