#include <LiquidCrystal_I2C.h>
#include <Wire.h>
#include <DS3231.h>

LiquidCrystal_I2C lcd(0x27, 20, 4);
DS3231 rtc(SDA, SCL);
Time _time;

int button_up = 2;
int button_down = 3;
int button_ok = 18;
int button_ring = 19;
bool _ring = LOW;
bool allowButton = true;

long unsigned int now = millis();

int pointerIndex = 0;
bool lcdNotCleared = false;
bool runMainDisplay = false;
bool mainDisplayActive = false;
bool settingsActive = false;
bool dateSettingsActive = false;
bool timeSettingsActive = false;
bool ringMenuActive = false;
bool exitSelectActive = false;

byte arrowDown[] = { 0x04, 0x04, 0x04, 0x04, 0x15, 0x0E, 0x04, 0x00 };
byte arrowUp[] = { 0x04, 0x0E, 0x15, 0x04, 0x04, 0x04, 0x04, 0x00 };

void setup()
{
  Serial.begin(9600);
  pinMode(4, OUTPUT);
  pinMode(button_up, INPUT_PULLUP);
  pinMode(button_down, INPUT_PULLUP);
  pinMode(button_ok, INPUT_PULLUP);
  pinMode(button_ring, INPUT_PULLUP);
  Serial.println(digitalRead(button_ring));
  attachInterrupt(digitalPinToInterrupt(button_up), pointerUp, LOW);
  attachInterrupt(digitalPinToInterrupt(button_down), pointerDown, LOW);
  attachInterrupt(digitalPinToInterrupt(button_ok), select, LOW);
  attachInterrupt(digitalPinToInterrupt(button_ring), ring, LOW);

  lcd.init();
  lcd.backlight();
  lcd.clear();
  lcd.createChar(0, arrowDown);
  lcd.createChar(1, arrowUp);

  rtc.begin();
  rtc.setDOW(FRIDAY);
  rtc.setTime(02, 12, 12);
  rtc.setDate(12, 2, 2020);

  //initialStartup();
  lcd.clear();
  mainDisplay();

}

void loop()
{
  // put your main code here, to run repeatedly:
  if (!runMainDisplay)
  {
    mainDisplayActive = true;
    runMainDisplay = true;
  }

  if (millis() - now >= 10) digitalWrite(4, LOW);

  if (digitalRead(button_up) == HIGH && digitalRead(button_down) == HIGH && digitalRead(button_ok) == HIGH) allowButton = true;

  if (mainDisplayActive) mainDisplay();
  if (settingsActive) settings();
  if (dateSettingsActive && millis() - now >= 1500)
  {
    dateSettings();
    now = millis();
  }
  if (timeSettingsActive && millis() - now >= 1500)
  {
    timeSettings();
    now = millis();
  }
  if (ringMenuActive) ringMenu();

}



void lcdClear()
{
  if (lcdNotCleared)
  {
    lcd.clear();
    lcdNotCleared = false;
  }
}


//Buttons
void ring()
{
  Serial.println(digitalRead(button_ring));
  digitalWrite(4, HIGH);
  now = millis();
}


void pointerUp()
{
  if (allowButton)
  {
    allowButton = false;

    pointerIndex--;

    if (mainDisplayActive) pointerIndex = 0;

    if (dateSettingsActive)
    {
      pointerIndex++;
      if (pointerIndex == 0) changeDOW(0);
      if (pointerIndex == 1) changeDay(0);
      if (pointerIndex == 2) changeMonth(0);
      if (pointerIndex == 3) changeYear(0);
    }

    if (timeSettingsActive)
    {
      pointerIndex++;
      if (pointerIndex == 0) changeHours(0);
      if (pointerIndex == 1) changeMinutes(0);
      if (pointerIndex == 2) changeSeconds(0);
    }
    
  }
}

void pointerDown()
{
  if (allowButton)
  {
    allowButton = false;

    pointerIndex++;

    if (mainDisplayActive) pointerIndex = 0;

    if (settingsActive) if (pointerIndex > 3) pointerIndex = 3;

    if (dateSettingsActive)
    {
      pointerIndex--;
      if (pointerIndex == 0) changeDOW(1);
      if (pointerIndex == 1) changeDay(1);
      if (pointerIndex == 2) changeMonth(1);
      if (pointerIndex == 3) changeYear(1);
    }

    if (timeSettingsActive)
    {
      pointerIndex--;
      if (pointerIndex == 0) changeHours(0);
      if (pointerIndex == 1) changeMinutes(0);
      if (pointerIndex == 2) changeSeconds(0);
    }
  }
}


void select()
{
  if (allowButton)
  {
    Serial.println(mainDisplayActive);
    Serial.println(settingsActive);
    Serial.println(dateSettingsActive);
    Serial.println(timeSettingsActive);
    Serial.println(ringMenuActive);
    lcdNotCleared = true;

    allowButton = false;
    Serial.println("select");

    if (mainDisplayActive)
    {
      mainDisplayActive = false;
      settingsActive = true;
      pointerIndex = 0;
      return;
    }

    if (dateSettingsActive)
    {
      pointerIndex++;
      if (pointerIndex > 4)
      {
        dateSettingsActive = false;
        settingsActive = true;
        pointerIndex = 0;
        return;
      }
    }

    if (timeSettingsActive)
    {
      pointerIndex++;
      if (pointerIndex > 3)
      {
        timeSettingsActive = false;
        settingsActive = true;
        pointerIndex = 0;
        return;
      }
    }
    
    if (settingsActive)
    {
      settingsActive = false;
      if (pointerIndex == 0) dateSettingsActive = true;
      if (pointerIndex == 1) timeSettingsActive = true;
      if (pointerIndex == 2) ringMenuActive = true;
      if (pointerIndex == 3) mainDisplayActive = true;
      pointerIndex = 0;
      return;
    }
  }
}


//Menus


void initialStartup()
{
  lcd.setCursor(0, 0);
  lcd.print(" +----------------+ ");
  lcd.setCursor(0, 1);
  lcd.print(" | arduino_client | ");
  lcd.setCursor(0, 2);
  lcd.print(" +----------------+ ");
  lcd.setCursor(0, 3);
  lcd.print("      Welcome!      ");
  delay(1000);
  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print("Povezivanje na mrezu");
  lcd.setCursor(0, 1);
  lcd.print("=>");
  //ethernetSetup();
  //client.stop();
  lcd.setCursor(0, 2);
  lcd.print(" Spajanje na server ");
  for (int i = 1; i <= 5; i++)
  {
    if (false /*client.connect(server, 8080);*/)
    {
      lcd.setCursor(0, 2);
      lcd.print("  Spojen na server  ");
      lcd.setCursor(0, 3);
      lcd.print("PHP7.4-Apache Debian");
      break;
    }
    else
    {
      lcd.setCursor(0, 3);
      lcd.print("    Pokusaj ");
      lcd.print(i);
      lcd.print("...   ");
    }
    if (i == 5)
    {
      delay(1000);
      lcd.setCursor(0, 3);
      lcd.print("Server nije dostupan");
      break;
    }
    delay(1000);
  }
  delay(2000);
}


void mainDisplay()
{
  lcdClear();

  lcd.setCursor(0, 0);
  String dan = getDOW();
  lcd.print(dan);
  lcd.print(", ");
  lcd.print(rtc.getDateStr());
  lcd.print(".");
  lcd.setCursor(0, 1);
  lcd.print(rtc.getTimeStr());
  lcd.setCursor(0, 2);
  //lcd.print(time_get.option_name);
  lcd.setCursor(0, 3);
  //lcd.print(ring_time_array[nextRingIndex]);
  //lcd.print(ring_time_array[nextRingIndex + 1]);
}


void settings()
{
  lcdClear();

  switch (pointerIndex)
  {
    case 0:
      lcd.setCursor(0, 0);
      lcd.print(">Datum ");
      lcd.setCursor(0, 1);
      lcd.print("Vrijeme ");
      lcd.setCursor(0, 2);
      lcd.print("Odabir zvonjave ");
      lcd.setCursor(0, 3);
      lcd.print("Izlaz ");
      break;
    case 1:
      lcd.setCursor(0, 0);
      lcd.print("Datum ");
      lcd.setCursor(0, 1);
      lcd.print(">Vrijeme ");
      lcd.setCursor(0, 2);
      lcd.print("Odabir zvonjave ");
      lcd.setCursor(0, 3);
      lcd.print("Izlaz ");
      break;
    case 2:
      lcd.setCursor(0, 0);
      lcd.print("Datum ");
      lcd.setCursor(0, 1);
      lcd.print("Vrijeme ");
      lcd.setCursor(0, 2);
      lcd.print(">Odabir zvonjave ");
      lcd.setCursor(0, 3);
      lcd.print("Izlaz ");
      break;
    case 3:
      lcd.setCursor(0, 0);
      lcd.print("Datum ");
      lcd.setCursor(0, 1);
      lcd.print("Vrijeme ");
      lcd.setCursor(0, 2);
      lcd.print("Odabir zvonjave ");
      lcd.setCursor(0, 3);
      lcd.print(">Izlaz ");
      break;
  }
  return;
}


void dateSettings()
{
  lcdClear();

  lcd.setCursor((20 - getDOWFull().length()) / 2 - 3, 0);
  lcd.print("   ");
  String dan = getDOWFull();
  lcd.print(dan);
  lcd.print("   ");
  lcd.setCursor(0, 1);
  lcd.print("         ");
  if (pointerIndex == 0) lcd.write(1);
  lcd.print("         ");
  if (pointerIndex == 1) lcd.setCursor(1, 1);
  if (pointerIndex == 2) lcd.setCursor(4, 1);
  if (pointerIndex == 3) lcd.setCursor(7, 1);
  lcd.print("   ");
  if (pointerIndex >= 1 && pointerIndex <= 3) lcd.write(0);
  lcd.print("       ");
  lcd.setCursor(4, 2);
  lcd.print(rtc.getDateStr());
  lcd.print(".");
  if (pointerIndex == 4)
  {
    lcd.setCursor(5, 3);
    lcd.print(">Potvrdi.<");
  }
  else
  {
    lcd.setCursor(5, 3);
    lcd.print(" Potvrdi. ");
  }
}


void timeSettings()
{
  lcdClear();
  
  if (pointerIndex == 0) lcd.setCursor(3, 0);
  if (pointerIndex == 1) lcd.setCursor(6, 0);
  if (pointerIndex == 2) lcd.setCursor(9, 0);
  lcd.print("   ");
  if (pointerIndex >= 0 && pointerIndex <= 2) lcd.write(0);
  lcd.print("       ");
  lcd.setCursor(6, 1);
  lcd.print(rtc.getTimeStr());
  if (pointerIndex == 3)
  {
    lcd.setCursor(5, 2);
    lcd.print(">Potvrdi.<");
  }
  else
  {
    lcd.setCursor(5, 2);
    lcd.print(" Potvrdi. ");
  }
}


void ringMenu()
{
  lcdClear();
}


String getDOW()
{
  String DOW = rtc.getDOWStr();

  if (DOW == "Monday") return "Pon.";
  if (DOW == "Tuesday") return "Uto.";
  if (DOW == "Wednesday") return "Sri.";
  if (DOW == "Thursday") return "Cet.";
  if (DOW == "Friday") return "Pet.";
  if (DOW == "Saturday") return "Sub.";
  if (DOW == "Sunday") return "Ned.";

  return "";
}

String getDOWFull()
{
  String DOW = rtc.getDOWStr();
  
  if (DOW == "Monday") return "Ponedjeljak";
  if (DOW == "Tuesday") return "Utorak";
  if (DOW == "Wednesday") return "Srijeda";
  if (DOW == "Thursday") return "Cetvrtak";
  if (DOW == "Friday") return "Petak";
  if (DOW == "Saturday") return "Subota";
  if (DOW == "Sunday") return "Nedjelja";

  return "";
}

void changeDOW(int num)
{
  String DOW = rtc.getDOWStr();

  if (num == 0)
  {
    if (DOW == "Tuesday") rtc.setDOW(MONDAY);
    if (DOW == "Wednesday") rtc.setDOW(TUESDAY);
    if (DOW == "Thursday") rtc.setDOW(WEDNESDAY);
    if (DOW == "Friday") rtc.setDOW(THURSDAY);
    if (DOW == "Saturday") rtc.setDOW(FRIDAY);
    if (DOW == "Sunday") rtc.setDOW(SATURDAY);
  }
  if (num == 1)
  {
    if (DOW == "Monday") rtc.setDOW(TUESDAY);
    if (DOW == "Tuesday") rtc.setDOW(WEDNESDAY);
    if (DOW == "Wednesday") rtc.setDOW(THURSDAY);
    if (DOW == "Thursday") rtc.setDOW(FRIDAY);
    if (DOW == "Friday") rtc.setDOW(SATURDAY);
    if (DOW == "Saturday") rtc.setDOW(SUNDAY);
  }
}

void changeDay(int num)
{
  String _day = rtc.getDateStr();
  _day.remove(2);
  String _month = rtc.getDateStr();
  _month = _month.substring(3);
  _month.remove(2);
  String _year = rtc.getDateStr();
  _year = _year.substring(6);
  if (num == 0) rtc.setDate(_day.toInt() - 1, _month.toInt(), _year.toInt());
  if (num == 1) rtc.setDate(_day.toInt() + 1, _month.toInt(), _year.toInt());
}

void changeMonth(int num)
{
  String _day = rtc.getDateStr();
  _day.remove(2);
  String _month = rtc.getDateStr();
  _month = _month.substring(3);
  _month.remove(2);
  String _year = rtc.getDateStr();
  _year = _year.substring(6);
  if (num == 0) rtc.setDate(_day.toInt(), _month.toInt() - 1, _year.toInt());
  if (num == 1) rtc.setDate(_day.toInt(), _month.toInt() + 1, _year.toInt());
}

void changeYear(int num)
{
  String _day = rtc.getDateStr();
  _day.remove(2);
  String _month = rtc.getDateStr();
  _month = _month.substring(3);
  _month.remove(2);
  String _year = rtc.getDateStr();
  _year = _year.substring(6);
  if (num == 0) rtc.setDate(_day.toInt(), _month.toInt(), _year.toInt() - 1);
  if (num == 1) rtc.setDate(_day.toInt(), _month.toInt(), _year.toInt() + 1);
}

void changeHours(int num)
{
  String _hours = rtc.getDateStr();
  _hours.remove(2);
  String _minutes = rtc.getDateStr();
  _minutes = _minutes.substring(3);
  _minutes.remove(2);
  String _seconds = rtc.getDateStr();
  _seconds = _seconds.substring(6);
  if (num == 0) rtc.setTime(_hours.toInt() - 1, _minutes.toInt(), _seconds.toInt());
  if (num == 1) rtc.setTime(_hours.toInt() + 1, _minutes.toInt(), _seconds.toInt());
}

void changeMinutes(int num)
{
  String _hours = rtc.getDateStr();
  _hours.remove(2);
  String _minutes = rtc.getDateStr();
  _minutes = _minutes.substring(3);
  _minutes.remove(2);
  String _seconds = rtc.getDateStr();
  _seconds = _seconds.substring(6);
  if (num == 0) rtc.setTime(_hours.toInt(), _minutes.toInt() - 1, _seconds.toInt());
  if (num == 1) rtc.setTime(_hours.toInt(), _minutes.toInt() + 1, _seconds.toInt());
}

void changeSeconds(int num)
{
  String _hours = rtc.getDateStr();
  _hours.remove(2);
  String _minutes = rtc.getDateStr();
  _minutes = _minutes.substring(3);
  _minutes.remove(2);
  String _seconds = rtc.getDateStr();
  _seconds = _seconds.substring(6);
  if (num == 0) rtc.setTime(_hours.toInt(), _minutes.toInt(), _seconds.toInt() - 1);
  if (num == 1) rtc.setTime(_hours.toInt(), _minutes.toInt(), _seconds.toInt() + 1);
}
