/**
 * Author: Brandon Sleater
 * Desc:   This program will open and close a feeder cover.
 */

#include <stdio.h>
#include <wiringPi.h>

#define SERVO_PIN 1

static const int cover_delay = 8000;
static const int increment   = 5;

/* Servo positions */
static const int close_start = 700;
static const int close_end   = 900;
static const int open_start  = 2500;
static const int open_end    = 2300;


/**
 * Start a pulse, delay by the position value 
 * passed, kill the pulse, then delay.
 */
void rotate(int pos) 
{
  digitalWrite(SERVO_PIN, HIGH);
  delayMicroseconds(pos);
  digitalWrite(SERVO_PIN, LOW);

  // Ensure full rotation
  delayMicroseconds(gate_delay);
}

/**
 * Closes the cover.
 */
void close(void) 
{
  int pos = 0;

  for (pos = close_start; pos < close_end; pos += increment) 
  {
    rotate(pos);
  }
}

/**
 * Opens the cover.
 */
void open(void) 
{
  int pos = 0;

  for (pos = open_start; pos > open_end; pos -= increment) 
  {
    rotate(pos);
  }
}

/**
 * Open and release food
 */
void dispense(int recess)
{
  open();
  delay(recess);
  close();
}

int main (int argc, char *argv[]) 
{
  // If time value not passed, default to 5 seconds
  int recess = 5000;

  if (argc == 2) 
  {
    recess = atoi(argv[1]);
  }

  wiringPiSetup();

  // Init servo
  pinMode(SERVO_PIN, OUTPUT);

  dispense(recess);

  return 0;
}
