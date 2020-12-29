#include <ArduinoJson.h>
#include <Arduino.h>
#include <WiFi.h>
#include "soc/soc.h"
#include "soc/rtc_cntl_reg.h"
#include "esp_camera.h"
#include <Stepper.h>

#include "config.h"

WiFiClient client;

unsigned long prevMillis = 0;   // last time image was sent
Stepper platformStepper = Stepper(stepsPerRevolution, stepPlatform1, stepPlatform3, stepPlatform2, stepPlatform4);
Stepper elevationStepper = Stepper(stepsPerRevolution, stepElevation1, stepElevation3, stepElevation2, stepElevation4);

void loop() {
  String result=sendPhoto();
  int idx=result.indexOf("RESPONSE:");
  if (idx) {
    String json;
    for (int i=idx+9;result[i]!='\n' && i<result.length();++i) {
      json+=result[i];
    }
    result=json;
    DynamicJsonDocument obj(1024);
    deserializeJson(obj, json);
    int mv=obj["platform"];
    if (mv) {
      stepperOff();
      platformStepper.step(mv);
      stepperOff();
    }
    mv=obj["elevation"];
    if (mv) {
      stepperOff();
      elevationStepper.step(mv);
      stepperOff();
    }
  }
  unsigned long currentMillis = millis();
  unsigned long diff=currentMillis-prevMillis;
  if (diff<timerInterval) {
    delay(timerInterval-diff);
  }
  prevMillis=millis();
}

void setup() {
  stepperOff();
  platformStepper.setSpeed(10);
  elevationStepper.setSpeed(10);
  WRITE_PERI_REG(RTC_CNTL_BROWN_OUT_REG, 0); 

  WiFi.mode(WIFI_STA);
  WiFi.begin(ssid, password);  
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
  }

  camera_config_t config;
  config.ledc_channel = LEDC_CHANNEL_0;
  config.ledc_timer = LEDC_TIMER_0;
  config.pin_d0 = 5;
  config.pin_d1 = 18;
  config.pin_d2 = 19;
  config.pin_d3 = 21;
  config.pin_d4 = 36;
  config.pin_d5 = 39;
  config.pin_d6 = 34;
  config.pin_d7 = 35;
  config.pin_xclk = 0;
  config.pin_pclk = 22;
  config.pin_vsync = 25;
  config.pin_href = 23;
  config.pin_sscb_sda = 26;
  config.pin_sscb_scl = 27;
  config.pin_pwdn = 32;
  config.pin_reset = -1;
  config.xclk_freq_hz = 20000000;
  config.pixel_format = PIXFORMAT_JPEG;

  config.frame_size = FRAMESIZE_UXGA;
  config.jpeg_quality = 2;  // 5 works. 0 does not.
  config.fb_count = 2;
  
  // camera init
  esp_err_t err = esp_camera_init(&config);
  if (err != ESP_OK) {
    delay(1000);
    ESP.restart();
  }
  prevMillis=millis();
}

String sendPhoto() {
  String getAll;
  String getBody;

  camera_fb_t * fb = NULL;
  fb = esp_camera_fb_get();
  if(!fb) {
    delay(1000);
    ESP.restart();
  }
  

  if (client.connect(serverIP.c_str(), serverPort)) {
    String head = "--multipart\r\nContent-Disposition: form-data; name=\"image\"; filename=\"esp32-cam.jpg\"\r\nContent-Type: image/jpeg\r\n\r\n";
    String tail = "\r\n--multipart--\r\n";

    uint32_t imageLen = fb->len;
    uint32_t extraLen = head.length() + tail.length();
    uint32_t totalLen = imageLen + extraLen;
  
    client.println("POST " + serverPath + " HTTP/1.1");
    client.println("Host: " + serverName);
    client.println("Content-Length: " + String(totalLen));
    client.println("Content-Type: multipart/form-data; boundary=multipart");
    client.println();
    client.print(head);
  
    uint8_t *fbBuf = fb->buf;
    size_t fbLen = fb->len;
    for (size_t n=0; n<fbLen; n=n+1024) {
      if (n+1024 < fbLen) {
        client.write(fbBuf, 1024);
        fbBuf += 1024;
      }
      else if (fbLen%1024>0) {
        size_t remainder = fbLen%1024;
        client.write(fbBuf, remainder);
      }
    }   
    client.print(tail);
    
    esp_camera_fb_return(fb);
    
    int timoutTimer = 10000;
    long startTimer = millis();
    boolean state = false;
    
    while ((startTimer + timoutTimer) > millis()) {
      delay(100);      
      while (client.available()) {
        char c = client.read();
        getBody += String(c);
        startTimer = millis();
      }
      if (getBody.length()>0) { break; }
    }
    client.stop();
  }
  else {
    getBody = "Connection to " + serverName +  " failed.";
  }
  return getBody;
}

void stepperOff() {
  pinMode(stepPlatform1, OUTPUT);
  pinMode(stepPlatform2, OUTPUT);
  pinMode(stepPlatform3, OUTPUT);
  pinMode(stepPlatform4, OUTPUT);
  pinMode(stepElevation1, OUTPUT);
  pinMode(stepElevation2, OUTPUT);
  pinMode(stepElevation3, OUTPUT);
  pinMode(stepElevation4, OUTPUT);

  // { platform
  digitalWrite(stepPlatform1,LOW);
  digitalWrite(stepPlatform2,LOW);
  digitalWrite(stepPlatform3,LOW);
  digitalWrite(stepPlatform4,LOW);
  // }
  // { elevation
  digitalWrite(stepElevation1,LOW);
  digitalWrite(stepElevation2,LOW);
  digitalWrite(stepElevation3,LOW);
  digitalWrite(stepElevation4,LOW);
  // }
}
