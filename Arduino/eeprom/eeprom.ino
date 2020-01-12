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

String _string="#wtest1?07300810081509050955101511001105115011551240124513300730081008150905095510151100110511501155124012451330#";

void setup() {
  Serial.begin(9600);

  updateStruct();

  //Upis probnih podataka
  eepromManage("#c#");
  
  time_set[0] = {
    0,
    "active",
    "07300810081509050955101511001105115011551240124513300730081008150905095510151100110511501155124012451330#"
  };

  EEPROM.put(time_set[0].location, time_set[0]);
  
  eepromManage(_string);
  Serial.println("eeprom manage gotov\n");
  
  _string="#wtest2?07300810081509050955101511001105115011551240124513300730081008150905095510151100110511501155124012451330#";
  eepromManage(_string);
  Serial.println("eeprom manage gotov\n");

  _string="#dtest2";
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
    Serial.print("\nwrite\n");
    int t = 0;
    for (int i = 2; i < string.length(); i++)
    {
      if (t == 0 && string[i] != '?') time_get.option_name += string[i];
      else
      {
        time_get.option_name += '\0';
        if (t == 0) i++;
        t = 1;
      }
      
      if (t == 1 && (string[i] != '#' || string[i] != '\0')) time_get.option_value += string[i];
      else if (t == 1)
      {
        time_get.option_value += '\0';
        break;
      }
    }
    
    EEPROM.put(time_get.location, time_get);
  }

  updateStruct();
  return;
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
