/**
 * Author: Brandon Sleater
 * Desc:   This program will open and close a feeder gate
 */

#include <wiringPi.h>

#define SERVO_PIN  1

static const int delay     = 8000;
static const int increment = 5;

/* Servo positions */
static const int open_start  = 700;
static const int open_end    = 900;
static const int close_start = 2500;
static const int close_end   = 2150;


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
  delayMicroseconds(delay);
}

/**
 * Opens the gate
 */
void open(void) 
{
  int pos = 0;

  for (pos = open_start; pos < open_end; pos += increment) 
  {
    rotate(pos);
  }
}

/**
 * Closes the gate
 */
void close(void) 
{
  int pos = 0;

  for (pos = close_start; pos > close_end; pos -= increment) 
  {
    rotate(pos);
  }
}

/**
 * Open the gate, pause, close the gate.
 */
void dispense(int amount)
{
	open()
	delayMicroseconds(amount);
	close();
}

/**
 * Core build.
 */
int main (int argc, char *argv[]) 
{
	if (argc == 1)
	{
		int amount = atoi(argv[0]);

  	// Initialize wiringPi and the raspi
	  wiringPiSetup();
	  pinMode(SERVO_PIN, OUTPUT);

	  // Startup
	  dispense(amount);
	}

  return 0;
}
