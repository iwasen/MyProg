//
//  DokoAppDelegate.h
//  Doko
//
//  Created by aizawa on 11/04/25.
//  Copyright 2011 TRC. All rights reserved.
//

#import <UIKit/UIKit.h>

@class DokoViewController;

@interface DokoAppDelegate : NSObject <UIApplicationDelegate> {

}

@property (nonatomic, retain) IBOutlet UIWindow *window;

@property (nonatomic, retain) IBOutlet DokoViewController *viewController;

@end
