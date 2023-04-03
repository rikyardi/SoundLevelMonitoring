#include <DFPlayer_Mini_Mp3.h>
#include <SoftwareSerial.h>
#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <ESP8266WebServer.h>
#include <Wire.h>
#include <WiFiClient.h>
#include <LiquidCrystal_I2C.h>

#define SENSOR_PIN A0

SoftwareSerial mp3Serial(0, 2); // RX, TX
LiquidCrystal_I2C lcd(0x27, 16, 2);//atau 0x3F
WiFiClient wificlient;
HTTPClient http;

const int sampleWindow = 50;                              // Sample window width in mS (50 mS = 20Hz)
unsigned int sample;

const char *ssid = "";  //ENTER YOUR WIFI SETTINGS
const char *password = "";
const char *host = "";
String myHost = String(host);

void setup () {
  Serial.begin(9600);

  lcd.begin(16, 2);
  lcd.init();
  lcd.backlight();

  lcd.setCursor(0, 0);
  lcd.print("Connecting");

  Serial.println("Connecting to ");
  Serial.println(ssid);

  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED)
  {
    delay(500);
    Serial.print(".");
    lcd.print(".");
  }
  lcd.clear();

  lcd.setCursor(0, 0);
  lcd.print("Connected !");
  delay(500);
  
  lcd.clear();
  Serial.println("");
  Serial.println("WiFi connected");

  mp3Serial.begin(9600);
  mp3_set_serial(mp3Serial);
  // Delay is required before accessing player. From my experience it's ~1 sec
  delay(1000);
  mp3_set_volume(25);
}

void loop (){
  unsigned long startMillis = millis();                  // Start of sample window
  float peakToPeak = 0;                                  // peak-to-peak level

  unsigned int signalMax = 0;                            //minimum value
  unsigned int signalMin = 1024;                         //maximum value

  // collect data for 50 mS
  while (millis() - startMillis < sampleWindow)
  {
    sample = analogRead(SENSOR_PIN);                    //get reading from microphone
    if (sample < 1024)                                  // toss out spurious readings
    {
      if (sample > signalMax)
      {
        signalMax = sample;                           // save just the max levels
      }
      else if (sample < signalMin)
      {
        signalMin = sample;                           // save just the min levels
      }
    }
  }

  peakToPeak = signalMax - signalMin;                    // max - min = peak-peak amplitude
  int db = map(peakToPeak, 20, 900, 40, 90);         //calibrate for deciBels
  String decibel = String(db);

  lcd.setCursor(0,0);
  lcd.print("Kebisingan: " + decibel + "dB");
  Serial.println(decibel);

  http.begin(wificlient, myHost+"getData.php?data=" + decibel);
  Serial.println(myHost+"getData.php?data=" + decibel);
  int httpCode = http.GET();            //Send the request
  String payload = http.getString();
  
  Serial.println(httpCode);   //Print HTTP return code
  Serial.println(payload);

  lcd.setCursor(0,1);
  if(httpCode == 200){
    lcd.print("Sukses Upload");
  }else{
    lcd.print("Gagal Upload");
  }

  if(db > 55){
    mp3_play(1);
    delay(3000);
    mp3_stop();
  }
  http.end();  //Close connection'
  delay(1000);
}
