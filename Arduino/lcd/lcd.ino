#include <LiquidCrystal_I2C.h>
#include <Wire.h>

LiquidCrystal_I2C lcd(0x27, 20, 4);

int button_up = 2;
int button_down = 3;
int button_ok = 18;
int button_ring = 19;
bool _ring = false;

int pointerIndex = 0;
bool mainDisplayActive = true;
bool settingsMenuActive = false;
bool dateTimeSettingsActive = false;
bool ringMenuActive = false;
bool ringSelectMenuActive = false;
bool exitSelectActive = false;

void setup()
{
  pinMode(4, OUTPUT);
  pinMode(button_up, INPUT_PULLUP);
  pinMode(button_down, INPUT_PULLUP);
  pinMode(button_ok, INPUT_PULLUP);
  pinMode(button_ring, INPUT_PULLUP);
  attachInterrupt(digitalPinToInterrupt(button_up), pointerUp, FALLING);
  attachInterrupt(digitalPinToInterrupt(button_down), pointerDown, FALLING);
  attachInterrupt(digitalPinToInterrupt(button_ok), select, FALLING);
  attachInterrupt(digitalPinToInterrupt(button_ring), ring, FALLING);

  //initialStartup();
  mainDisplay();
  
}

void loop()
{
  // put your main code here, to run repeatedly:

}



void initialStartup()
{
  lcd.init();
  lcd.backlight();
  lcd.clear();
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
  lcd.clear();
  lcd.setCursor(0, 0);
  String dan = getDayOfWeek();
  lcd.print(dan);
  lcd.print(", ");
  //lcd.print(rtc.getDateStr());
  lcd.print(".");
  lcd.setCursor(0, 1);
  //lcd.print(rtc.getTimeStr());
  lcd.setCursor(0, 2);
  //lcd.print(time_get.option_name);
  lcd.setCursor(0, 3);
  //lcd.print(ring_time_array[nextRingIndex]);
  //lcd.print(ring_time_array[nextRingIndex + 1]);
}


String getDayOfWeek()
{
  /*switch (rtc.getDOWStr())
  {
    case "Monday":
      return "Pon.";
      break;
    case "Tuesday":
      return "Uto.";
      break;
    case "Wednesday":
      return "Sri.";
      break;
    case "Thursday":
      return "Cet.";
      break;
    case "Friday":
      return "Pet.";
      break;
    case "Saturday":
      return "Sub.";
      break;
    default:
      return "Ned.";
      break;
  }
  */
  return "";
}


void settingsMenu()
{
  switch(pointerIndex)
  {
    case 0:
      lcd.setCursor(0, 0);
      lcd.print(">Datum i vrijeme");
      lcd.setCursor(0, 1);
      lcd.print("Odabir zvonjave");
      lcd.setCursor(0, 2);
      lcd.print("Izlaz");
      break;
    case 1:
      lcd.setCursor(0, 0);
      lcd.print("Datum i vrijeme");
      lcd.setCursor(0, 1);
      lcd.print(">Odabir zvonjave");
      lcd.setCursor(0, 2);
      lcd.print("Izlaz");
      break;
    case 2:
      lcd.setCursor(0, 0);
      lcd.print("Datum i vrijeme");
      lcd.setCursor(0, 1);
      lcd.print("Odabir zvonjave");
      lcd.setCursor(0, 2);
      lcd.print(">Izlaz");
      break;
  }
}


void ring()
{
  digitalWrite(4, _ring);
  _ring = !_ring;
}


void pointerUp()
{
  pointerIndex--;
  
  if (ringMenuActive && pointerIndex < 2)
  {
    pointerIndex = 2;
    return;
  }
  
  if (pointerIndex < 0) pointerIndex = 0;
}

void pointerDown()
{
  pointerIndex++;

  if (settingsMenuActive) if (pointerIndex > 2) pointerIndex = 2;

  if (dateTimeSettingsActive) if (pointerIndex > 3) pointerIndex = 3;

  if (ringMenuActive) if (pointerIndex > 3) pointerIndex = 3;
  
  if (ringSelectMenuActive) if (pointerIndex > 4/*recordsNum*/) pointerIndex = 4/*recordsNum*/;

}


void select()
{
  
}
