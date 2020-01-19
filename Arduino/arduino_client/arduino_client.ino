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

int temp = 0;
String _string = "";

byte mac[] = { 0xDE, 0xAD, 0xBE, 0xEF, 0xFE, 0xED };

// Set the static IP address to use if the DHCP fails to assign
IPAddress ip(192, 168, 0, 177);
IPAddress myDns(192, 168, 0, 1);

// initialize the library instance:
EthernetClient client;

char server[] = "192.168.1.11";

unsigned long lastConnectionTime = 0;           // last time you connected to the server, in milliseconds
const unsigned long postingInterval = 5000;     // delay between updates, in milliseconds


//EEPROM




//RTC




//Relay




//I2C_LCD





//----------------------------------------------------------------------------------------------------
//void setup, loop
//----------------------------------------------------------------------------------------------------



void setup()
{
  Serial.begin(9600);
  while (!Serial) { ; }

  ethernetSetup();
}

void loop()
{

  //fetch server string
  if (client.available())
  {
    char c = client.read();
    Serial.write(c);
    
    if (c == '#' && temp == 1) 
    {
      temp = 0;
      _string += c;
      Serial.println(_string);
      _string = "";
    }
    else if (c == '#') temp = 1;
    if (temp == 1) _string += c;
  }

  // if ten seconds have passed since your last connection,
  // then connect again and send data:
  if (millis() - lastConnectionTime > postingInterval) httpRequest();

}



//----------------------------------------------------------------------------------------------------
//Funkcije
//----------------------------------------------------------------------------------------------------



// Ethernet funkcije

void ethernetSetup()
{
  // start the Ethernet connection:
  Serial.println("Initialize Ethernet with DHCP:");
  if (Ethernet.begin(mac) == 0)
  {
    Serial.println("Failed to configure Ethernet using DHCP");
    // Check for Ethernet hardware present
    if (Ethernet.hardwareStatus() == EthernetNoHardware) 
    {
      Serial.println("Ethernet shield was not found.  Sorry, can't run without hardware. :(");
      while (true)
      {
        delay(1); // do nothing, no point running without Ethernet hardware
      }
    }
    if (Ethernet.linkStatus() == LinkOFF)
    {
      Serial.println("Ethernet cable is not connected.");
    }
    // try to congifure using IP address instead of DHCP:
    Ethernet.begin(mac, ip, myDns);
    Serial.print("My IP address: ");
    Serial.println(Ethernet.localIP());
  } 
  else
  {
    Serial.print("  DHCP assigned IP ");
    Serial.println(Ethernet.localIP());
  }
  // give the Ethernet shield a second to initialize:
  delay(1000);
}



void httpRequest()
{
  client.stop();

  if (client.connect(server, 8080))
  {
    Serial.println("\n\nconnecting...");
    // send the HTTP POST request:
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
  else
  {
    Serial.println("connection failed");
  }
}



//EEPROM funkcije





//RTC funckije





//Relay funkcije





//I2C_LCD funkcije
