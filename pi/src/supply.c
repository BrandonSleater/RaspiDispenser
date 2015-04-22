#include <stdio.h>
#include <wiringPi.h>
 
#define ECHO 4
#define TRIG 5
 
 
int getDistance(void) {
	
  int distance;
  long finish, start;

  // Start the module
  digitalWrite(TRIG, HIGH);
  delayMicroseconds(20);
  digitalWrite(TRIG, LOW);

  // Wait for start
  while(digitalRead(ECHO) == LOW);
  start = micros();

  // Wait for finish
  while(digitalRead(ECHO) == HIGH);
  finish = micros() - start;

  // Per datasheet: Take length of pulse and divide to get centimeter conversion
  distance = finish / 58;

  return distance;
}
 
int main(void) {

  wiringPiSetup();
  pinMode(TRIG, OUTPUT);
  pinMode(ECHO, INPUT);

  // Must start low
  digitalWrite(TRIG, LOW);
  delay(30);

  printf("%d", getDistance());
  fflush(stdin);

  return 0;
}
