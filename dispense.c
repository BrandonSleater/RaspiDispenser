/**
 * Author: Brandon Sleater
 * Desc:   This program will rotate a servo clockwise/counterclockwise
 *         through an interrupt
 *
 * Resource: Based off LinuxCircle.com example
 */

#include <wiringPi.h>

#define SERVO_PIN  1
#define BUTTON_PIN 7

static int servoMode = 1;
static unsigned int lastInterrupt = 0;

void rotate(int pos) {

  // Start the pulse, delay the length, then kill it
  digitalWrite(SERVO_PIN, HIGH);
  delayMicroseconds(pos);
  digitalWrite(SERVO_PIN, LOW);

  // Ensure full rotation
  delayMicroseconds(8000);
}


void handler(void) {

  unsigned int currentInterrupt = millis();

  // Software debounce because capacitors suck
  if (currentInterrupt - lastInterrupt > 1000) {

    int pos = 0;

    if (servoMode) {

      // Forward
      for (pos = 700; pos < 900; pos += 5) {
        rotate(pos);
      }
    } else {

      // Reverse
      for (pos = 2500; pos > 2150; pos -= 5) {
        rotate(pos);
      }
    }

    servoMode = !servoMode;
  }

  lastInterrupt = currentInterrupt;
}


int main () {

  // Init wiringPi
  wiringPiSetup();

  // Init interrupt handler
  wiringPiISR(BUTTON_PIN, INT_EDGE_FALLING, &handler);

  // Init servo
  pinMode(SERVO_PIN, OUTPUT);

  while (1) {
    // Don't die plz
  }

  return 0;
}
