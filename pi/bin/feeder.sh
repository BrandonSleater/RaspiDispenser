#!/bin/bash

PROJECT_DIR=$HOME/whitehouse/project

# Ensure the audio is setup
sudo modprobe snd_bcm2835
sudo amixer -q cset numid=3 1

# Turn on the song 
mpg321 -q $PROJECT_DIR/assets/$2 &
sleep 3

# Open it up
sudo $PROJECT_DIR/bin/dispense $1

# Ensure nothing is still playing
sudo kill `pgrep mpg321`
