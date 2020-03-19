#include <EEPROM.h>

struct time_
{
  int location;
  String option_name;
  String option_value;
  String ring_enable;
};

int struct_size = sizeof(time_);

const int max_records = 100;
time_ time_set[max_records], time_get;

String _string="#wSkraćeno?0730081508200905091009551015110011051150115512401245133013351420142515101515160016051650171017551800184518501935?11111111111111#";

void setup() {
  Serial.begin(9600);

  updateStruct();

  //Upis probnih podataka
  eepromManage("#c#");
  
  time_set[0] = {
    0,
    "active",
    "07300810081509050955101511001105115011551240124513300730081008150905095510151100110511501155124012451330#",
    "11111111111111"
  };

  EEPROM.put(time_set[0].location, time_set[0]);
  
  eepromManage(_string);
  Serial.println("eeprom manage gotov\n");

  _string="#wtest3?07300810081509050955101511001105115011551240124513300730081008150905095510151100110511501155124012451330?1111#";
  eepromManage(_string);
  Serial.println("eeprom manage gotov\n");

  _string="#dSkraćeno#";
  eepromManage(_string);
  Serial.println("eeprom manage gotov\n");

}

void loop() {

}



void eepromManage(String string)
{
  int write_index = getWriteIndex();
  Serial.println(write_index);
  
  if (string[1] == 'c')
  {
    Serial.print("\nclear\n");
    for (int i = 0; i < EEPROM.length(); i++) EEPROM.write(i, 0);
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
  
  if (string[1] == 'r')
  {
    Serial.print("\nread\n");
    for (int i = struct_size; i < struct_size * max_records; i += struct_size)
    {
      EEPROM.get(i, time_get);
      
      if (time_get.option_name.compareTo(string.substring(2)) == 0)
      {
        string = "_string=";
        char temp[200];
        time_get.option_name.toCharArray(temp, 200);
        string.concat(temp);
        string.concat("?");
        time_get.option_value.toCharArray(temp, 200);
        string.concat(temp);
        Serial.println(string);
        //httpRequest();
        return;
      }
    }
    string ="_string=#x#";
    Serial.println(string);
    //httpRequest();
  }
  
  if (string[1] == 'w')
  {
    if (write_index == NULL) return;
    
    time_get.location = write_index;
    time_get.option_name = "";
    time_get.option_value = "";
    time_get.ring_enable = "";
    Serial.print("\nwrite\n");
    int i = 2;
    while (string[i] != '?')
    {
      time_get.option_name += string[i];
      i++;
    }
    i++;
    while (string[i] != '?')
    {
      time_get.option_value += string[i];
      i++;
    }
    i++;
    while (string[i] != '#')
    {
      time_get.ring_enable += string[i];
      i++;
    }
    
    EEPROM.put(time_get.location, time_get);
  }

  if (string[1] == 'a')
  { 
    if (write_index == NULL) return;
    
    time_get.location = 0;
    time_get.option_name = "";
    time_get.option_value = "";
    time_get.ring_enable = "";
    Serial.print("\nwrite\n");
    int i = 2;
    while (string[i] != '?')
    {
      time_get.option_name += string[i];
      i++;
    }
    i++;
    while (string[i] != '?')
    {
      time_get.option_value += string[i];
      i++;
    }
    i++;
    while (string[i] != '#')
    {
      time_get.ring_enable += string[i];
      i++;
    }
    
    EEPROM.put(time_get.location, time_get);
  }
  
  updateStruct();
  return;
}


void updateStruct()
{
  for (int i = 0; i < struct_size * 10; i += struct_size)
  {
    EEPROM.get(i, time_set[i / struct_size]);
    Serial.println(time_set[i / struct_size].location);
    Serial.println(time_set[i / struct_size].option_name);
    Serial.println(time_set[i / struct_size].option_value);
    Serial.println(time_set[i / struct_size].ring_enable);
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


void moveRecords (int index)
{ 
  for (int i = index / struct_size; i < 100; i++)
  {
    if (time_set[i].location != 0) time_set[i].location -= struct_size;
  }
  
  for (int i = index; i < struct_size * max_records; i += struct_size) EEPROM.put(i, time_set[i / struct_size + 1]);

  updateStruct();

  return;
}
