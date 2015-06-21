package ookkii.what2doandroid;
/*
import src.ookkii.what2do.android.Override;
import src.ookkii.what2do.android.SuppressLint;
import src.ookkii.what2do.android.WebView;
*/
import android.os.Bundle;
import android.annotation.SuppressLint;
import android.app.Activity;
import android.util.Log;
import android.view.Menu;
import android.webkit.WebView;
import android.webkit.WebViewClient;


import ookkii.what2doandroid.MyLocation.LocationResult;

import android.location.Location;



public class MainActivity extends Activity {

	WebView myWebView;
    	
	private static final String TAG = "OOKKII LOG";
    @SuppressLint("SetJavaScriptEnabled")
	@Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
        myWebView = (WebView) findViewById(R.id.webview);
        //WebSettings webSettings = myWebView.getSettings();
        //webSettings.setJavaScriptEnabled(true);      
        myWebView.getSettings().setJavaScriptEnabled(true);  
        myWebView.loadUrl("http://www.worxup.com/~chrisho/play.html");
        
        MyLocation myLocation = new MyLocation();
        myLocation.getLocation(this, locationResult); 
        Log.v(TAG, Double.toString(lng));
        myWebView.loadUrl("javascript:setPosition('"+lng+"','"+lat+"');");
        
   //// page finish load stuff
        myWebView.setWebViewClient(new WebViewClient() {
   		   public void onPageFinished(WebView view, String url) {
   			   	Log.v(TAG, Double.toString(lng));
   	        	myWebView.loadUrl("javascript:setPosition('"+lng+"','"+lat+"');");  
   	        	//locationManager.removeUpdates(locationListener);
   		    }
   		});	
   //// 	   	        
    }
    
    @Override 
    public void onStart() {
    	super.onStart();
    	myWebView = (WebView) findViewById(R.id.webview);
        //WebSettings webSettings = myWebView.getSettings();
        //webSettings.setJavaScriptEnabled(true);      
        myWebView.getSettings().setJavaScriptEnabled(true);  
        myWebView.loadUrl("http://www.worxup.com/~chrisho/play.html");
        
        MyLocation myLocation = new MyLocation();
        myLocation.getLocation(this, locationResult); 
        Log.v(TAG, Double.toString(lng));
        myWebView.loadUrl("javascript:setPosition('"+lng+"','"+lat+"');");
        
   //// page finish load stuff
        myWebView.setWebViewClient(new WebViewClient() {
   		   public void onPageFinished(WebView view, String url) {
   			   	Log.v(TAG, Double.toString(lng));
   	        	myWebView.loadUrl("javascript:setPosition('"+lng+"','"+lat+"');");  
   	        	//locationManager.removeUpdates(locationListener);
   		    }
   		});	
   //// 	      	
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        getMenuInflater().inflate(R.menu.activity_main, menu);      
        return true;
    }

////location thing
	double lng;
	double lat;   
	LocationResult locationResult = new LocationResult(){

        @Override
        public void gotLocation(Location location){
            //Got the location!
        	lat = location.getLatitude();
        	lng = location.getLongitude();
        	Log.v(TAG, Double.toString(lng));
            myWebView.loadUrl("javascript:setPosition('"+lng+"','"+lat+"');");        	
        }
        
    };
////  
}
