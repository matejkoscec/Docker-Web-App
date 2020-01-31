//----------------------------------------------------------------------------------------------------
//Setup
//----------------------------------------------------------------------------------------------------

#include <SPI.h>
#include <Ethernet.h>
#include <EEPROM.h>
#include <DS3231.h>
#include <Wire.h>
#include <LiquidCrystal_I2C.h>


//Ethernet shield

int stringStart = 0;
String _string = "";
String temp_string = "";

byte mac[] = { 0xDE, 0xAD, 0xBE, 0xEF, 0xFE, 0xED };

// Set the static IP address to use if the DHCP fails to assign
IPAddress ip(192, 168, 1, 69);
IPAddress myDns(192, 168, 1, 1);

// initialize the library instance:
EthernetClient client;

char server[] = "192.168.1.11";

unsigned long lastConnectionTime = 0;           // last time you connected to the server, in milliseconds
const unsigned long postingInterval = 15000;     // delay between updates, in milliseconds
unsigned long lastConnectionAttempt = 0;

bool httpRequestActive = false;
bool serverAvailable = false;


//EEPROM

struct time_
{
  int location;
  String option_name;
  String option_value;
};

uint8_t struct_size = sizeof(time_);

const int max_records = 100;
time_ time_set[max_records], time_get;


//RTC

DS3231 rtc(SDA, SCL);
Time _time;


//Relay

int relay_signal_pin = 4;

int nextRingIndex = 0;
int ring_time_array[200];


//LCD

LiquidCrystal_I2C lcd(0x27, 20, 4);

int button_up = 2;
int button_down = 3;
int button_ok = 18;
int button_ring = 19;
bool _ring = LOW;
bool allowButton = true;

long unsigned now = millis();

int pointerIndex = 0;
int eepromIndex = struct_size;

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


//----------------------------------------------------------------------------------------------------
//void setup, loop
//----------------------------------------------------------------------------------------------------



void setup()
{
  Serial.begin(9600);
  pinMode(relay_signal_pin, OUTPUT);

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
  lcd.clear();

  rtc.begin();
  
  time_set[0] = {
    0,
    "Standardno",
    "07300815090509551015110011051150115512401245133013351420142515101515160016201705171017551800184518501935#"
  };
  EEPROM.put(time_set[0].location, time_set[0]);

  time_set[1] = {
    struct_size,
    "Standardno",
    "07300815090509551015110011051150115512401245133013351420142515101515160016201705171017551800184518501935#"
  };
  EEPROM.put(time_set[1].location, time_set[1]);
  
  updateStruct();

  activeRingingSetup();
  for (int i = 0; i < 200; i++) Serial.println(ring_time_array[i]);
  getNextRingIndex();

  initialStartup();
  mainDisplay();
}

void loop()
{
  if (!httpRequestActive)
  {
    if (!(timeSettingsActive || dateSettingsActive))
    {
      getNextRingIndex();
      timeCheck();
    }

    uiRefresh();
  }
  else if (millis() - lastConnectionTime >= 1000) httpRequestActive = false;

  connectionCheck();
  if (serverAvailable)
  {
    readServerData();
  
    if (millis() - lastConnectionTime > postingInterval)
    {
      httpRequest();
      httpRequestActive = true;
    }
  }
}



//----------------------------------------------------------------------------------------------------
//Funkcije
//----------------------------------------------------------------------------------------------------


//----------------------------------------------------------------------------------------------------
// Ethernet funkcije


void readServerData()
{
  if (client.available())
  {
    char c = client.read();
    Serial.write(c);

    if (c == '#' && stringStart == 1)
    {
      stringStart = 0;
      temp_string += c;
      client.stop();
      httpRequestActive = false;
      eepromManage(temp_string);
      temp_string = "";
    }
    else if (c == '#') stringStart = 1;
    if (stringStart == 1) temp_string += c;
  }
}

void ethernetSetup()
{
  // start the Ethernet connection:
  //Serial.println("Initialize Ethernet with DHCP:");
  if (Ethernet.begin(mac) == 0)
  {
    //Serial.println("Failed to configure Ethernet using DHCP");
    lcd.clear();
    lcd.setCursor(0, 0);
    lcd.print(" DHCP nije dostupan ");
    delay(2000);
    lcd.clear();
    lcd.setCursor(0, 0);
    lcd.print("Povezivanje na mrezu");
    // Check for Ethernet hardware present
    if (Ethernet.hardwareStatus() == EthernetNoHardware)
    {
      Serial.println("Ethernet shield was not found.  Sorry, can't run without hardware. :(");
      lcd.setCursor(0, 1);
      lcd.print("  Eth. shield N/A  ");
      delay(1000);
    }
    if (Ethernet.linkStatus() == LinkOFF)
    {
      Serial.println("Ethernet cable is not connected.");
      lcd.setCursor(0, 1);
      lcd.print("  Kabel nije spojen ");
      delay(1000);
    }
    // try to congifure using IP address instead of DHCP:
    Ethernet.begin(mac, ip, myDns);
    Serial.print("My IP address: ");
    Serial.println(Ethernet.localIP());
    lcd.setCursor(0, 0);
    lcd.print("Dodjela staticke IP ");
    lcd.setCursor(0, 1);
    lcd.print("adr.: ");
    lcd.print(Ethernet.localIP());
  }
  else
  {
    //Serial.print("  DHCP assigned IP ");
    lcd.setCursor(0, 0);
    lcd.print("  DHCP dodijeljena  ");
    lcd.setCursor(0, 1);
    lcd.print(" adr.: ");
    lcd.print(Ethernet.localIP());
  }
  // give the Ethernet shield a second to initialize:
  delay(1000);
}


void connectionCheck()
{
  if (millis() - lastConnectionAttempt > 30000)
  {
    lastConnectionAttempt = millis();

    if (client.connect(server, 8080))
    {
      lcd.clear();
      serverAvailable = true;
    }
    else
    {
      serverAvailable = false;
    }
  }
}


void httpRequest()
{
  client.stop();

  if (client.connect(server, 8080))
  {
    client.println("POST /index.php HTTP/1.1");
    client.println("Host: 192.168.1.11:8080");
    client.println("User-Agent: arduino-ethernet");
    client.println("Connection: close");
    client.println("Content-Type: application/x-www-form-urlencoded;");
    client.print("Content-Length: ");
    client.println(_string.length());
    client.println();
    client.println(_string);
    client.println();

    lastConnectionTime = millis();
  }
  else Serial.println("connection failed");
}


//----------------------------------------------------------------------------------------------------
//EEPROM funkcije

void eepromManage(String string)
{
  int write_index = getWriteIndex();
  Serial.println(write_index);

  if (string[1] == 'c')
  {
    Serial.print("\nclear\n");
    for (int i = struct_size * 2; i < EEPROM.length(); i++) EEPROM.write(i, 0);
    setup();
  }

  if (string[1] == 'd')
  {
    Serial.print("\ndelete\n");
    string.remove(string.length() - 1);
    for (int i = 0; i < struct_size * max_records; i += struct_size)
    {
      EEPROM.get(i, time_get);
      if (time_get.option_name.compareTo(string.substring(2)) == 0)
      {
        moveRecords(i);
        break;
      }
    }
  }

  /*if (string[1] == 'r')
    {
    Serial.print("\nread\n");
    EEPROM.get(0, time_get);
    string.remove(string.length() - 1);
    if (time_get.option_name.compareTo(string.substring(2)) == 0)
    {
      string = "_string=";
      char temp[200];
      time_get.option_name.toCharArray(temp, 200);
      string.concat(temp);
      string.concat("?");
      time_get.option_value.toCharArray(temp, 200);
      string.concat(temp);
      _string = string;
      _string.remove(_string.length() - 1);
      return;
    }
    _string ="_string=x";
    }*/

  if (string[1] == 'w')
  {
    if (write_index == NULL) return;

    time_get.location = write_index;
    Serial.print("\nwrite\n");
    int t = 0;
    for (int i = 2; i < string.length(); i++)
    {
      if (t == 0 && string[i] != '?') time_get.option_name.concat(string[i]);
      else
      {
        time_get.option_name.concat('\0');
        if (t == 0) i++;
        t = 1;
      }

      if (t == 1 && (string[i] != '#' || string[i] != '\0')) time_get.option_value.concat(string[i]);
      else if (t == 1)
      {
        break;
      }
    }

    EEPROM.put(time_get.location, time_get);
  }

  if (string[1] == 'a')
  {
    if (write_index == NULL) return;

    time_get.location = 0;
    Serial.print("\active\n");
    int t = 0;
    for (int i = 2; i < string.length(); i++)
    {
      if (t == 0 && string[i] != '?') time_get.option_name.concat(string[i]);
      else
      {
        time_get.option_name.concat('\0');
        if (t == 0) i++;
        t = 1;
      }

      if (t == 1 && (string[i] != '#' || string[i] != '\0')) time_get.option_value.concat(string[i]);
      else if (t == 1)
      {
        break;
      }
    }

    EEPROM.put(time_get.location, time_get);
  }

  updateStruct();
  EEPROM.get(0, time_get);
  return;
}


void updateStruct()
{
  for (int i = 0; i < struct_size * max_records; i += struct_size)
  {
    EEPROM.get(i, time_set[i / struct_size]);
    //Serial.println(time_set[i / struct_size].location);
    //Serial.println(time_set[i / struct_size].option_name);
    //Serial.println(time_set[i / struct_size].option_value);
  }
}


int getWriteIndex()
{
  int temp = 0;
  for (int i = struct_size; i < struct_size * max_records; i += struct_size)
  {
    EEPROM.get(i, time_get);
    if (time_get.location == 0)
    {
      Serial.print("write index: ");
      return temp + struct_size;
    }
    temp = time_get.location;
  }
  return NULL;
}


void moveRecords (int index)
{
  updateStruct();

  for (int i = index / struct_size; i < 100; i++)
  {
    if (time_set[i].location != 0) time_set[i].location -= struct_size;
  }

  for (int i = index; i < struct_size * max_records; i += struct_size) EEPROM.put(i, time_set[i / struct_size + 1]);

  return;
}







//----------------------------------------------------------------------------------------------------
//Relay funkcije

void activeRingingSetup()
{
  EEPROM.get(0, time_get);
  String temp[time_get.option_value.length()];
  int j = 0;
  for (int i = 0; i < time_get.option_value.length(); ++i)
  {
    if (time_get.option_value[i] == '#') break;

    temp[i].concat(time_get.option_value[i]);
    if (i % 2 != 0)
    {
      ring_time_array[j] = temp[i - 1].toInt() * 10 + temp[i].toInt();
      j++;
    }
  }
}


void getNextRingIndex()
{
  _time = rtc.getTime();
  for (int i = 0; i < 200; i += 2)
  {
    if ((ring_time_array[i] >= _time.hour && ring_time_array[i + 1] > _time.min) || ring_time_array[i] > _time.hour)
    {
      nextRingIndex = i;
      return;
    }
    nextRingIndex = 0;
  }
}


void timeCheck()
{
  _time = rtc.getTime();
  for (int i = 0; i < 400; i += 2)
  {
    if (_time.hour == ring_time_array[i] && _time.min == ring_time_array[i + 1] && _time.sec < 1)
    {
      if (ring_time_array[i] != 0 && ring_time_array[i + 1] != 0 && ring_time_array[i + 2] != 0 && ring_time_array[i + 3] != 0)
      {
        nextRingIndex = i + 2;
        long unsigned previousTime = millis();
        while (millis() - previousTime < 3000) digitalWrite(relay_signal_pin, HIGH);
        while (millis() - previousTime < 6000) digitalWrite(relay_signal_pin, LOW);
      }
    }
  }
}


//----------------------------------------------------------------------------------------------------
//LCD funkcije

void uiRefresh()
{
  if (!runMainDisplay)
  {
    mainDisplayActive = true;
    runMainDisplay = true;
  }

  if (millis() - now >= 10) digitalWrite(4, LOW);

  if (mainDisplayActive) mainDisplay();
  if (settingsActive) settings();
  if (dateSettingsActive && millis() - now >= 1500)
  {
    dateSettings();
    now = millis();
  }
  if (timeSettingsActive && millis() - now >= 1000)
  {
    timeSettings();
    now = millis();
  }
  if (ringMenuActive) ringMenu();

  if (digitalRead(button_up) == HIGH && digitalRead(button_down) == HIGH && digitalRead(button_ok) == HIGH && millis() - now >= 100) allowButton = true;
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
  digitalWrite(4, HIGH);
  now = millis();
}


void pointerUp()
{
  now = millis();
  
  if (allowButton)
  {
    pointerIndex--;

    allowButton = false;

    if (mainDisplayActive)
    {
      pointerIndex = 0;
    }

    if (settingsActive) if (pointerIndex < 0)
      {
        pointerIndex = 0;
      }

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

    if (ringMenuActive)
    {
      if (pointerIndex < 0)
      {
        pointerIndex = 0;
        eepromIndex -= struct_size;
        if (eepromIndex <= 0) eepromIndex = struct_size;
      }
    }
  }
}

void pointerDown()
{
  now = millis();
  
  if (allowButton)
  {
    pointerIndex++;

    allowButton = false;

    if (mainDisplayActive)
    {
      pointerIndex = 0;
    }

    if (settingsActive) if (pointerIndex > 3)
      {
        pointerIndex = 3;
      }

    if (dateSettingsActive)
    {
      pointerIndex--;
      if (pointerIndex == 0) changeDOW(1);
      if (pointerIndex == 1) changeDay(1);
      if (pointerIndex == 2) changeMonth(1);
      if (pointerIndex == 3) changeYear(1);
    }

    if (ringMenuActive)
    {
      if (pointerIndex == 1)
      {
        pointerIndex = 0;
        eepromIndex += struct_size;
      }
      if (pointerIndex >= 4) pointerIndex = 3;
    }

    if (timeSettingsActive)
    {
      pointerIndex--;
      if (pointerIndex == 0) changeHours(1);
      if (pointerIndex == 1) changeMinutes(1);
      if (pointerIndex == 2) changeSeconds(1);
    }
  }
}


void select()
{
  now = millis();
  
  if (allowButton)
  {
    lcdNotCleared = true;

    allowButton = false;

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

    if (ringMenuActive)
    {
      if (pointerIndex == 2) EEPROM.put(0, time_get);
      if (pointerIndex == 3)
      {
        ringMenuActive = false;
        settingsActive = true;
        pointerIndex = 0;
        return;
      }
      if (pointerIndex == 0) pointerIndex = 2;
    }

    if (settingsActive)
    {
      settingsActive = false;
      if (pointerIndex == 0) dateSettingsActive = true;
      if (pointerIndex == 1) timeSettingsActive = true;
      if (pointerIndex == 2) ringMenuActive = true;
      if (pointerIndex == 3) mainDisplayActive = true;
      pointerIndex = 0;
      eepromIndex = struct_size;
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
  delay(3000);
  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print("    Konf. mreznih   ");
  lcd.setCursor(0, 1);
  lcd.print("     postavki...    ");
  delay(1000);

  ethernetSetup();

  client.stop();
  lcd.setCursor(0, 2);
  lcd.print(" Spajanje na server ");
  for (int i = 1; i <= 5; i++)
  {
    if (client.connect(server, 8080))
    {
      serverAvailable = true;
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
  lcd.clear();
}


void mainDisplay()
{
  lcdClear();

  EEPROM.get(0, time_get);
  lcd.setCursor(0, 0);
  String dan = getDOW();
  lcd.print(dan);
  lcd.print(", ");
  lcd.print(rtc.getDateStr());
  lcd.print(".");
  lcd.setCursor(0, 1);
  lcd.print(rtc.getTimeStr());
  lcd.setCursor(12, 1);
  if (serverAvailable) lcd.print("       ");
  else lcd.print("!Server");
  lcd.setCursor(0, 2);
  for (int i = 0; i < 20; i++) if (isAlphaNumeric(time_get.option_name[i]) || time_set[eepromIndex / struct_size].option_name[i] == ' ') lcd.print(time_get.option_name[i]);
  lcd.setCursor(0, 3);
  lcd.print("Zvono: ");
  if (ring_time_array[nextRingIndex] == 0) lcd.print("00");
  else lcd.print(ring_time_array[nextRingIndex]);
  lcd.print(":");
  if (ring_time_array[nextRingIndex + 1] == 0) lcd.print("00");
  else if (ring_time_array[nextRingIndex + 1] < 10)
  {
    lcd.print("0");
    lcd.print(ring_time_array[nextRingIndex + 1]);
  }
  else lcd.print(ring_time_array[nextRingIndex + 1]);
  lcd.print(" (");
  _time = rtc.getTime();
  int minutes;
  if (ring_time_array[nextRingIndex] < _time.hour) minutes = (24 - _time.hour) * 60 - 60 + (60 - _time.min) + ring_time_array[nextRingIndex] * 60 + ring_time_array[nextRingIndex + 1];
  else minutes = (ring_time_array[nextRingIndex] - _time.hour) * 60 + (ring_time_array[nextRingIndex + 1] - _time.min);
  lcd.print(minutes);
  lcd.print("min)");
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

  EEPROM.get(eepromIndex, time_get);

  if (time_get.location == 0)
  {
    eepromIndex -= struct_size;
    EEPROM.get(eepromIndex, time_get);
  }

  lcd.setCursor(0, 0);
  lcd.print("<");
  int len = 0;
  for (int i = 0; i < 20; i++)
  {
    len++;
    if (!(isAlphaNumeric(time_set[eepromIndex / struct_size].option_name[i]) || time_set[eepromIndex / struct_size].option_name[i] == ' ')) break;
  }
  time_set[eepromIndex / struct_size].option_name.remove(len - 1);
  lcd.print(time_set[eepromIndex / struct_size].option_name);
  lcd.print(">");
  int whiteSpaces = 18 - len;
  for (int i = 0; i < whiteSpaces; i++) lcd.print(" ");

  lcd.setCursor(0, 1);
  lcd.print(time_set[eepromIndex / struct_size].option_value[0]);
  lcd.print(time_set[eepromIndex / struct_size].option_value[1]);
  lcd.print(":");
  lcd.print(time_set[eepromIndex / struct_size].option_value[2]);
  lcd.print(time_set[eepromIndex / struct_size].option_value[3]);
  lcd.print("-");
  lcd.print(time_set[eepromIndex / struct_size].option_value[4]);
  lcd.print(time_set[eepromIndex / struct_size].option_value[5]);
  lcd.print(":");
  lcd.print(time_set[eepromIndex / struct_size].option_value[6]);
  lcd.print(time_set[eepromIndex / struct_size].option_value[7]);
  lcd.print("-");
  lcd.print(time_set[eepromIndex / struct_size].option_value[8]);
  lcd.print(time_set[eepromIndex / struct_size].option_value[9]);
  lcd.print(":");
  lcd.print(time_set[eepromIndex / struct_size].option_value[10]);
  lcd.print(time_set[eepromIndex / struct_size].option_value[11]);
  lcd.print("-..");

  if (pointerIndex == 1) pointerIndex = 2;
  if (pointerIndex == 2)
  {
    lcd.setCursor(0, 2);
    lcd.print(">Postavi kao aktivno ");
    lcd.setCursor(0, 3);
    lcd.print("Izlaz ");
  }
  else if (pointerIndex == 3)
  {
    lcd.setCursor(0, 2);
    lcd.print("Postavi kao aktivno ");
    lcd.setCursor(0, 3);
    lcd.print(">Izlaz ");
  }
  else
  {
    lcd.setCursor(0, 2);
    lcd.print("Postavi kao aktivno ");
    lcd.setCursor(0, 3);
    lcd.print("Izlaz ");
  }
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
  String _hours = rtc.getTimeStr();
  _hours.remove(2);
  String _minutes = rtc.getTimeStr();
  _minutes = _minutes.substring(3);
  _minutes.remove(2);
  String _seconds = rtc.getTimeStr();
  _seconds = _seconds.substring(6);
  if (num == 0) rtc.setTime(_hours.toInt() - 1, _minutes.toInt(), _seconds.toInt());
  if (num == 1) rtc.setTime(_hours.toInt() + 1, _minutes.toInt(), _seconds.toInt());
}

void changeMinutes(int num)
{
  String _hours = rtc.getTimeStr();
  _hours.remove(2);
  String _minutes = rtc.getTimeStr();
  _minutes = _minutes.substring(3);
  _minutes.remove(2);
  String _seconds = rtc.getTimeStr();
  _seconds = _seconds.substring(6);
  if (num == 0) rtc.setTime(_hours.toInt(), _minutes.toInt() - 1, _seconds.toInt());
  if (num == 1) rtc.setTime(_hours.toInt(), _minutes.toInt() + 1, _seconds.toInt());
}

void changeSeconds(int num)
{
  String _hours = rtc.getTimeStr();
  _hours.remove(2);
  String _minutes = rtc.getTimeStr();
  _minutes = _minutes.substring(3);
  _minutes.remove(2);
  String _seconds = rtc.getTimeStr();
  _seconds = _seconds.substring(6);
  if (num == 0) rtc.setTime(_hours.toInt(), _minutes.toInt(), _seconds.toInt() - 1);
  if (num == 1) rtc.setTime(_hours.toInt(), _minutes.toInt(), _seconds.toInt() + 1);
}
