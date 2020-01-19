#include <LiquidCrystal_I2C.h>
#include <Wire.h>

LiquidCrystal_I2C lcd(0x27, 20, 4);

int button_up = 2;
int button_down = 3;
int button_ok = 18;
int button_ring = 19;

void setup()
{
  pinMode(button_up, INPUT_PULLUP);
  pinMode(button_down, INPUT_PULLUP);
  pinMode(button_ok, INPUT_PULLUP);
  pinMode(button_ring, INPUT_PULLUP);
  //attachInterrupt(digitalPinToInterrupt(button_up), funkcija, FALLING);
  //attachInterrupt(digitalPinToInterrupt(button_down), funkcija, FALLING);
  //attachInterrupt(digitalPinToInterrupt(button_ok), funkcija, FALLING);
  //attachInterrupt(digitalPinToInterrupt(button_ring), funkcija, FALLING);

  initialStartup();
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
  delay(5000);
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
    }
    delay(1000);
  }
  delay(1000);
}


void mainDisplay()
{
  
}
