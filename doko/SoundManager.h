//
//  SoundManager.h
//  Doko
//
//  Created by aizawa on 11/05/13.
//  Copyright 2011 TRC. All rights reserved.
//

#import <Foundation/Foundation.h>
#import <AVFoundation/AVFoundation.h>


@interface SoundManager : NSObject {
    AVAudioPlayer *playButtonSound;
	AVAudioPlayer *readButtonSound;
    AVAudioPlayer *openButtonSound;
    AVAudioPlayer *closeButtonSound;
}

- (AVAudioPlayer *)initSound:(NSString *)soundFile;
- (void)playPlaySound;
- (void)playReadSound;
- (void)playOpenSound;
- (void)playCloseSound;

@end

extern SoundManager *soundManager;