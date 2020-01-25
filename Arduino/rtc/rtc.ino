bool allowButton = true;
unsigned int currentTime = millis();

void setup()
{
  Serial.begin(9600);
  pinMode(19, INPUT_PULLUP);
  interrupts();
  attachInterrupt(digitalPinToInterrupt(19), test, FALLING);
  
}

void loop()
{
  if (millis() - currentTime >= 750)
  {
    currentTime = millis();
    allowButton = true;
  }
}

void test()
{
  if (allowButton)
  {
    Serial.println(digitalRead(19));
    allowButton = false;
  }
}

/*#include <DS3231.h>

DS3231 rtc(SDA, SCL);
Time _time;

void setup()
{
  Serial.begin(9600);
  
  rtc.begin();
  
  rtc.setDOW(MONDAY);
  rtc.setTime(02, 12, 12);
  rtc.setDate(31, 2, 2020);
}

void loop()
{
  _time = rtc.getTime();
  
  Serial.print(rtc.getDOWStr());
  Serial.print(" ");
  
  Serial.print(rtc.getDateStr());
  Serial.print(" -- ");

  Serial.println(rtc.getTimeStr());
  
  delay (1000);
  
}
*/
/*
int pointerIndex = 0;
bool mainDisplayActive = false;
bool settingsMenuActive = false;
bool dateSettingsActive = false;
bool timeSettingsActive = false;
bool ringMenuActive = false;

*/
