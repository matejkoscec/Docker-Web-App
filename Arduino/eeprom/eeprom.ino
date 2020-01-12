#include <EEPROM.h>

struct time_
{
  int location;
  String option_name;
  String option_value;
};

int struct_size = sizeof(time_);

const int max_records = 100;
time_ time_set[max_records], time_get;

String _string="#wtest2?0047768";

void setup() {
  Serial.begin(9600);

  time_set[0] = {
    0,
    "active",
    "0123456789"
  };

  EEPROM.put(time_set[0].location, time_set[0]);
  
  //updateStruct();
  delay(1000);
  for (int i = 0; i < struct_size * max_records; i+= struct_size)
  {
    EEPROM.get(i, time_get);
    Serial.println(time_get.location);
    Serial.println(time_get.option_name);
    Serial.println(time_get.option_value);
    Serial.println();
  }
  int write_index = getWriteIndex();
  Serial.println(write_index);
  Serial.println("\n");
  eepromManage(_string, write_index);
  //updateStruct();
  
  Serial.println("eeprom manage gotov\n");

  for (int i = 0; i < struct_size * max_records; i+= struct_size)
  {
    EEPROM.get(i, time_get);
    Serial.println(time_get.location);
    Serial.println(time_get.option_name);
    Serial.println(time_get.option_value);
    Serial.println();
  }
  
  delay(500);
}

void loop() {

}

void updateStruct()
{
  for (int i = 0; i < struct_size * max_records; i+= struct_size)
  {
    EEPROM.get(i, time_set[i / struct_size]);
    Serial.println(time_set[i / struct_size].location);
    Serial.println(time_set[i / struct_size].option_name);
    Serial.println(time_set[i / struct_size].option_value);
    Serial.println();
  }
}

int getWriteIndex()
{
  int temp = 0;
  for (int i = struct_size; i < struct_size * max_records; i+= struct_size)
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



void eepromManage(String string, int write_index)
{
  if (string[1] == 'c')
  {
    Serial.print("\nclear\n");
    for (int i = 0; i < EEPROM.length(); i++) EEPROM.write(i, 0);
  }

  if (string[1] == 'd')
  {
    Serial.print("\ndelete\n");
    for (int i = 0; i < struct_size * max_records; i += struct_size)
    {
      EEPROM.get(i, time_get);
      if (time_get.option_name == string.substring(2))
      {
        moveRecords(i - struct_size);
        break;
      }
    }
  }
  
  if (string[1] == 'r')
  {
    Serial.print("\read\n");
    for (int i = struct_size; i < struct_size * max_records; i += struct_size)
    {
      EEPROM.get(i, time_get);
      if (time_get.option_name == string.substring(2)) string = "_string=" + time_get.option_name + "?" + time_get.option_value;
      else string ="_string=#x#";
    }
    Serial.println(string);
    //httpRequest();
  }
  
  if (string[1] == 'w')
  {
    time_get.location = write_index;
    time_get.option_name = "";
    time_get.option_value = "";
    Serial.print("\nwrite\n");
    int t = 0;
    for (int i = 2; i < 100; i++)
    {
      if (t == 0 && string[i] != '?') time_get.option_name += string[i];
      else
      {
        time_get.option_name += '\0';
        t = 1;
      }
      
      if (t == 1 && (string[i] != '#' || string[i] != '\0')) time_get.option_value += string[i];
      else if (t == 1)
      {
        time_get.option_value += '\0';
        break;
      }
    }
    Serial.println("van loopa");
    Serial.println("stavio u niz");
    Serial.println(time_get.location);
    Serial.println(time_get.option_name);
    Serial.println(time_get.option_value);
    Serial.println();
    
    EEPROM.put(time_get.location, time_get);
    Serial.println("stavio u eeprom");
  }

  return;
}



void moveRecords (int index)
{
  for (int i = index / struct_size; i < 100; i++) time_set[i].location -= struct_size;
  
  for (int i = index; i < struct_size * max_records; i += struct_size)
  {
    EEPROM.put(i, time_set[i / struct_size + 1]);
  }
  
  return;
}
