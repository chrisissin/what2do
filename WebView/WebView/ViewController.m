//
//  ViewController.m
//  WebView
//
//  Created by Chris Air on 8/21/12.
//  Copyright (c) 2012 Chris Air. All rights reserved.
//

#import "ViewController.h"

@interface ViewController ()

@end

@implementation ViewController
@synthesize viewWeb;

-(id)init {
    self = [super init];
    if(self) {
        NSLog(@"_init: %@", [self class]);
//        viewWeb.delegate = self;
    }
    return self;
}

- (void)viewDidLoad
{
    viewWeb.delegate = self;
    NSString *fullURL = @"http://www.worxup.com/~chrisho/play.html";
    NSURL *url = [NSURL URLWithString:fullURL];
    NSURLRequest *requestObj = [NSURLRequest requestWithURL:url];
    [viewWeb loadRequest:requestObj];
    //[viewWeb stringByEvaluatingJavaScriptFromString:@"setPosition(12,22);"];
    
    [super viewDidLoad];
}

- (void)webViewDidFinishLoad:(UIWebView *)webView
{
////LOCATION THING    
//    [viewWeb stringByEvaluatingJavaScriptFromString:@"setPosition(13,22);"];
    locationManager = [[CLLocationManager alloc] init];
    locationManager.delegate = self;
    locationManager.desiredAccuracy = kCLLocationAccuracyBest;
    locationManager.distanceFilter = kCLDistanceFilterNone;
    [locationManager startUpdatingLocation];
    [locationManager stopUpdatingLocation];
    CLLocation *location = [locationManager location];
    // Configure the new event with information from the location
    
    float longitude=location.coordinate.longitude;
    float latitude=location.coordinate.latitude;
    
    NSLog(@"dLongitude : %f", longitude);
    NSLog(@"dLatitude : %f", latitude);
    NSString *jssttring = [NSString stringWithFormat:@"%@%f%@%f%@",@"setPosition(",longitude , @",", latitude,  @");"];
    [viewWeb stringByEvaluatingJavaScriptFromString:jssttring];
////
}
/*
////LOACTION THING
-(void) locationManager: (CLLocationManager *)manager didUpdateToLocation: (CLLocation *) newLocation
           fromLocation: (CLLocation *) oldLocation{

////LOCATION THING
    // locationManager update as location
    locationManager = [[CLLocationManager alloc] init];
    locationManager.delegate = self;
    locationManager.desiredAccuracy = kCLLocationAccuracyBest;
    locationManager.distanceFilter = kCLDistanceFilterNone;
    [locationManager startUpdatingLocation];
    [locationManager stopUpdatingLocation];
    CLLocation *location = [locationManager location];
    // Configure the new event with information from the location
    
    float longitude=location.coordinate.longitude;
    float latitude=location.coordinate.latitude;
    
    NSLog(@"dLongitude : %f", longitude);
    NSLog(@"dLatitude : %f", latitude);
    NSString *jssttring = [NSString stringWithFormat:@"%@%f%@%f%@",@"setPosition(",longitude , @",", latitude,  @");"];
    [viewWeb stringByEvaluatingJavaScriptFromString:jssttring];
////
    
}
////
*/ 
- (void)viewDidUnload
{
    [self setViewWeb:nil];
    [super viewDidUnload];
    // Release any retained subviews of the main view.
}

- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)interfaceOrientation
{
    if ([[UIDevice currentDevice] userInterfaceIdiom] == UIUserInterfaceIdiomPhone) {
        return (interfaceOrientation != UIInterfaceOrientationPortraitUpsideDown);
    } else {
        return YES;
    }
}

@end
