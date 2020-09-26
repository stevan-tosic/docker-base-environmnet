#/bin/sh

if [ $# -lt 1 ]
then
    echo -e "This script requires 1 argument and is not meant to be used directly. Check analyze-coverage.sh and it's usage example."
    exit
fi

grep "success small" $1 | awk -F"[<>%]" '{ print $5; }' | awk 'NR==1 || NR==3 || NR==5'
