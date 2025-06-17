#!/bin/bash

echo -n "📌 ادخل الرابط الوهمي اللي تختاره: "
read CUSTOM_LINK

# تحقق من وجود القالب
if [ ! -f templates/default.html ]; then
  echo "❗ ملف القالب مش موجود!"
  exit 1
fi

# تجهيز الصفحة
cp templates/default.html temp.html
sed -i "s|{{CUSTOM_LINK}}|$CUSTOM_LINK|g" temp.html

# تشغيل السيرفر المحلي على 8080
php -S 127.0.0.1:8080 > /dev/null 2>&1 &

sleep 2

# فتح نفق SSH بـ localhost.run
echo "🚀 جاري إنشاء رابط عام باستخدام localhost.run..."
ssh -o StrictHostKeyChecking=no -R 80:localhost:8080 nokey@localhost.run
