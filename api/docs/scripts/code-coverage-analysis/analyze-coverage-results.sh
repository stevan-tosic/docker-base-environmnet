#/bin/bash

if [ $# -lt 4 ]
then
    echo -e "This script requires 4 arguments and is not meant to be used directly. Check analyze-coverage.sh and it's usage example."
    exit
fi

declare -a TYPES=("lines" "methods" "classes")
LAST_MASTER_BUILD=$(find $1/master/. -type d -printf "%f\n" | grep -vE "\.|$2" | sort -rn | head -n 1)
EMAIL_SUBJECT="Build $2 code coverage results"

if [ -z "$LAST_MASTER_BUILD" ]; then
    TABLE_CAPTION="Jenkins code coverage in build $2"
    CURRENT_ROW="<td>Current</td><td>$2</td>"

    for TYPE in "${TYPES[@]}"
    do
        CURRENT=$(cat $1/$2/${TYPE})
        CURRENT_ROW="${CURRENT_ROW}<td>${CURRENT}%</td>"
    done

    TABLE_CONTENT="${TABLE_CONTENT}<tr>${CURRENT_ROW}</tr>"
else
    TABLE_CAPTION="Jenkins code coverage changes from last master build (${LAST_MASTER_BUILD}) to build $2"
    CURRENT_ROW="<td>Current</td><td>$2</td>"
    LAST_ROW="<td>Previous</td><td>${LAST_MASTER_BUILD}</td>"
    DIFF_ROW="<td colspan=2><strong>Difference</strong></td>"
    TABLE_CAPTION_CLASS="green"

    for TYPE in "${TYPES[@]}"
    do
        CURRENT=$(cat $1/$2/${TYPE})
        CURRENT_ROW="${CURRENT_ROW}<td>${CURRENT}%</td>"

        LAST=$(cat $1/${LAST_MASTER_BUILD}/${TYPE})
        LAST_ROW="${LAST_ROW}<td>${LAST}%</td>"

        if (( $(echo "${CURRENT} < ${LAST}" | bc -l) )); then
            COLOR="red"
            TABLE_CAPTION_CLASS="red"
        else
            COLOR="green"
        fi

        DIFF=$(awk "BEGIN{ printf(\"%2.2f\", ${CURRENT} - ${LAST}) }")
        DIFF_ROW="${DIFF_ROW}<td class='${COLOR}'>${DIFF}%</td>"
    done

    TABLE_CONTENT="${TABLE_CONTENT}<tr>${CURRENT_ROW}</tr><tr>${LAST_ROW}</tr><tr>${DIFF_ROW}</tr>"
fi

GIT_CONTENT=$(./get-commit-info.sh $3 $4)

EMAIL_CONTENT="
<html>
<head>
    <style>
        table.coverage-info {
            width: 100%;
            border-collapse: collapse;
        }

        table.git-info {
            margin-top: 15px;
            width: 100%;
            border-collapse: collapse;
        }

        caption {
            padding: 6px;
            font-weight: bold;
            font-size: 120%;
            margin-bottom: 7px;
        }

        table td, table th {
            padding: 6px;
            border: 1px solid #999;
        }

        table.coverage-info td, table.coverage-info th {
            text-align: center;
        }

        table.git-info td.name {
            font-weight: bold;
            width: 20%;
        }

        .green {
            color: green;
            font-weight: bold;
        }

        .red {
            color: red;
            font-weight: bold;
        }

        div.git-info span {
            font-weight: bold;
            width: 120px;
        }

        pre {
            font-family: arial,sans-serif;
            margin: 0px;
        }
    </style>
</head>
<body>
    <table class='coverage-info'>
        <caption class='${TABLE_CAPTION_CLASS}'>${TABLE_CAPTION}</caption>
        <thead>
            <tr>
                <th>Build</th>
                <th>Build number</th>
                <th>Line coverage</th>
                <th>Method coverage</th>
                <th>Class coverage</th>
            </tr>
        </thead>
        <tbody>
            ${TABLE_CONTENT}
        </tbody>
    </table>

    <table class='git-info'>
        <caption>Commit info</caption>
        <tbody>
            ${GIT_CONTENT}
        </tbody>
    </table>
</body>
</html>
"

echo "${EMAIL_CONTENT}" | mail -s "$(echo -e "${EMAIL_SUBJECT}\nContent-Type: text/html")" uros.ivanovic@tms-outsource.com
