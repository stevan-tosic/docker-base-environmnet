#/bin/sh

if [ $# -lt 2 ]
then
    echo -e "This script requires 2 arguments and is not meant to be used directly. Check analyze-coverage.sh and it's usage example."
    exit
fi

pushd $1 > /dev/null
git log -1 --pretty=format:"
<tr>
    <td class='name'>Commit:</td>
    <td><a href='$2/commit/%h'>%h</a></td>
</tr>
<tr>
    <td class='name'>Commiter:</td>
    <td>%an (%ae)</td>
</tr>
<tr>
    <td class='name'>Commit date:</td>
    <td>%ai</td>
</tr>
<tr>
    <td class='name'>Commit subject:</td>
    <td><pre>%s</pre></td>
</tr>
<tr>
    <td class='name'>Commit body:</td>
    <td><pre>%b</pre></td>
</tr>
"
popd > /dev/null