#!/bin/bash

# ard controller
CTL="${BASEURL}index.php?/module/ard/"

# Get the scripts in the proper directories
"${CURL[@]}" "${CTL}get_script/ard.py" -o "${MUNKIPATH}preflight.d/ard.py"

# Check exit status of curl
if [ $? = 0 ]; then
	# Make executable
	chmod a+x "${MUNKIPATH}preflight.d/ard.py"

	# Set preference to include this file in the preflight check
	setreportpref "ard" "${CACHEPATH}ard.plist"

else
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}preflight.d/ard.py"

	# Signal that we had an error
	ERR=1
fi