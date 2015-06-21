//
//  ViewController.h
//  WebView
//
//

#import <UIKit/UIKit.h>
#import <CoreLocation/CoreLocation.h>

//@interface ViewController : UIViewController
@interface ViewController : UIViewController <CLLocationManagerDelegate> {
    CLLocationManager *locationManager;
}
@property (strong, nonatomic) IBOutlet UIWebView *viewWeb;

@end
