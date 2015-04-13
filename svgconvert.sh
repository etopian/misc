for i in *eps; do inkscape $i --export-plain-svg=$i.svg  -h512 -w512; done
for i in *svg; do rsvg-convert -a -w 512 -f svg $i -o $i.svg; done
rename s/.eps.svg// *svg
