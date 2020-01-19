#include <DS3231.h>

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
