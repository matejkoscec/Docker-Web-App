int relay_signal_pin = 4;

//Testni string, vrijeme povlači iz eeprom sa lokacije 0 na koju je pohranjena aktivna opcija
String _string="07300810081509050955101511001105115011551240124513300730081008150905095510151100110511501155124012451330#";

int ring_time;
int ring_time_array[200];

void setup()
{
  Serial.begin(9600);
  
  pinMode(relay_signal_pin, OUTPUT);
  
  activeRingingSetup();
  
  Serial.println("vrijednosti");
  for (int i = 0; i < 200; i++)
  {
    if (ring_time_array[i] != 0) Serial.println(ring_time_array[i]);
  }
}

void loop()
{
  timeCheck();
}

void activeRingingSetup()
{
  String temp[_string.length()];
  int j = 0;
  for (int i = 0; i < _string.length(); ++i)
  { 
    if (_string[i] == '#') break;

    temp[i].concat(_string[i]);
    if (i % 2 != 0)
    {
      ring_time_array[j] = temp[i - 1].toInt() * 10 + temp[i].toInt();
      j++;
    }
  }
}


void timeCheck()
{
  for (int i = 0; i < 400; i += 2)
  {
    if (ring_time_array[i] != 0)
    {
      if (true /*_time.hour == ring_time_array[i] && _time.min == ring_time_array[i + 1] && _time.sec >= 0 && _time.sec <= 3*/)
      {
        long unsigned int previousTime = millis();
        while (millis() - previousTime < 3000) digitalWrite(relay_signal_pin, HIGH);
        while (millis() - previousTime < 6000) digitalWrite(relay_signal_pin, LOW);
      }
    }
    else break;
  }
}
