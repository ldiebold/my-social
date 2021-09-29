#!/bin/sh
IMAGE_FILE=$1
AUDIO_FILE=$2
OUT=$3

# Tested, and works on YouTube but NOT with VLC
ffmpeg -y -i $IMAGE_FILE \
  -loop 1 -framerate 1 -i \
  $AUDIO_FILE -c:v libx264 -preset veryslow -crf 0 -c:a copy -shortest \
  $OUT
