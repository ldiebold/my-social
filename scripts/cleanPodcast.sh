echo "input file name: $1";
echo "output file name: $2";
echo "hard limiter: $3";
echo "seconds to trim from end: $4";

mkdir -p ./tmp

# Enrich and amplify the audio
# $2
sox --temp ./tmp $1 ./tmp/tmp.wav \
	bass +6 \
	treble +3 \
	norm \
	compand 0,0 $3,$3,0,$3,0,0 0 -90 0 \
	norm

# Get the last 2 seconds of the audio file
# noise-audio.wav
sox ./tmp/tmp.wav ./noise-audio.wav trim -$4 -0.1

# Create a noise profile with end of audio
# noise.noise-profile
sox ./noise-audio.wav -n noiseprof ./noise.noise-profile

# perform noise reduction
# $1
sox ./tmp/tmp.wav ./tmp/tmp2.wav noisered ./noise.noise-profile 0.21

sox ./tmp/tmp2.wav $2 trim 0 -$4

# Cleanup
rm -rf ./tmp
rm ./noise.noise-profile
rm ./noise-audio.wav