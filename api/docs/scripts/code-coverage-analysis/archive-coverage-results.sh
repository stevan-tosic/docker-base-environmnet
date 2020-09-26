#/bin/bash

if [ $# -lt 6 ]
then
    echo -e "This script requires 6 arguments and is not meant to be used directly. Check analyze-coverage.sh and it's usage example."
    exit
fi

rm -rf $1/$2 && mkdir -p $1/$2
echo $4 >> $1/$2/lines
echo $5 >> $1/$2/methods
echo $6 >> $1/$2/classes

echo -e "COMMIT_BRANCH is: $3"
if [ "$3" == "origin/master" ]; then
    echo $4 >> $1/master/$2/lines
    echo $5 >> $1/master/$2/methods
    echo $6 >> $1/master/$2/classes
fi
