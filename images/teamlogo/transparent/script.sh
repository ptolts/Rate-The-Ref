#!/bin/sh

convert $1 \( +clone -fx 'p{0,0}' \) -compose Difference  -composite -modulate 100,0  -alpha off  difference.png

convert difference.png  -threshold 1%  threshold_mask.png

convert $1  threshold_mask.png -alpha Off -compose CopyOpacity -composite new_$1

rm difference.png
rm threshold_mask.png
