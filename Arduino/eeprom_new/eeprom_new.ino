#include <EEPROM.h>

struct eepromRecord
{
  int location;
  String option_name;
  String option_value;
  String ring_enable;
};

int struct_size = sizeof(eepromRecord);

const int max_records = 100;
eepromRecord time_set[max_records], time_get;

String temp_string = "#wIzmjena?0730080508150850090009350955103010401115112512001210124512501325133514101420145515051540160016351645172017301805?10010111101111#";

void setup()
{
  Serial.begin(9600);

  for (int i = 0; i < 100; i++) EEPROM.write(i, 0);
  eepromManage(temp_string);
}

void loop()
{
  ;
}


void eepromManage(String string)
{
  char option_name[50] = "";
  char option_value[150] = "";
  char ring_enable[15] = "";
  int i = 2;
  int j = 0;
  while (string[i] != '?')
  {
    option_name[j] = string[i];
    j++;
    i++;
  }
  i++;
  j = 0;
  while (string[i] != '?')
  {
    option_value[j] = string[i];
    j++;
    i++;
  }
  i++;
  j = 0;
  while (string[i] != '#')
  {
    ring_enable[j] = string[i];
    j++;
    i++;
  }

  //Serial.println(option_name);
  //Serial.println(option_value);
  //Serial.println(ring_enable);

  if (string[1] == 'w')
  {
    int writeIndex = getWriteIndex();
    EEPROM.write(writeIndex, writeIndex);
    EEPROM.write(writeIndex + sizeof(int), option_name);
    EEPROM.write(writeIndex + sizeof(String), option_value);
    EEPROM.write(writeIndex + sizeof(String), ring_enable);
  }

  updateStruct();
}


void updateStruct()
{
  for (int i = 0; i < struct_size * max_records; i += struct_size)
  {
    time_set[i / struct_size].location = EEPROM.read(i);
    time_set[i / struct_size].option_name = EEPROM.read(i + sizeof(int));
    time_set[i / struct_size].option_value = EEPROM.read(i + sizeof(String));
    time_set[i / struct_size].ring_enable = EEPROM.read(i + sizeof(String));
    Serial.println(time_set[i / struct_size].location);
    Serial.println(time_set[i / struct_size].option_name);
    Serial.println(time_set[i / struct_size].option_value);
    Serial.println(time_set[i / struct_size].ring_enable);
  }
}


int getWriteIndex()
{
  return 0;
}


void moveRecords (int index)
{

}
