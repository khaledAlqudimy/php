#!/bin/bash
# ═══════════════════════════════════════════════════════════════
#  قطعة وقصة — سكريبت النشر النهائي على Hostinger
#  المرحلة 11: نشر الإنتاج
#  الاستخدام: bash deploy.sh
# ═══════════════════════════════════════════════════════════════

set -e

echo ""
echo "═══════════════════════════════════════════════"
echo "   قطعة وقصة — النشر النهائي على Hostinger"
echo "═══════════════════════════════════════════════"
echo ""

# ─── 1. التحقق من وجود .env ───
if [ ! -f ".env" ]; then
    echo "❌ ملف .env غير موجود!"
    echo "   شغّل: cp .env.example .env"
    echo "   ثم عدّل القيم وأعد تشغيل السكريبت"
    exit 1
fi

echo "✅ ملف .env موجود"

# ─── 2. تثبيت المكتبات للإنتاج ───
echo ""
echo "📦 تثبيت مكتبات Composer..."
composer install --optimize-autoloader --no-dev --no-interaction

# ─── 3. توليد مفتاح التشفير إن لم يكن موجوداً ───
if ! grep -q "APP_KEY=base64" .env; then
    echo ""
    echo "🔑 توليد مفتاح التشفير..."
    php artisan key:generate --force
fi

# ─── 4. ضبط بيئة الإنتاج ───
echo ""
echo "⚙️  ضبط بيئة الإنتاج..."

# تأكد من الإعدادات الحرجة
sed -i 's/APP_ENV=.*/APP_ENV=production/' .env
sed -i 's/APP_DEBUG=.*/APP_DEBUG=false/' .env

echo "✅ APP_ENV=production"
echo "✅ APP_DEBUG=false"

# ─── 5. قاعدة البيانات ───
echo ""
echo "🗄️  قاعدة البيانات..."
php artisan migrate --force
echo "✅ Migrations اكتملت"

php artisan db:seed --force
echo "✅ Seeders اكتملت"

# ─── التحقق من قاعدة البيانات ───
php artisan db:verify
echo ""

# ─── 6. ربط مجلد الصور ───
echo "🖼️  ربط مجلد الصور..."
php artisan storage:link 2>/dev/null || echo "   (الرابط موجود مسبقاً)"
echo "✅ Storage linked"

# ─── 7. تحسين الأداء (Cache) ───
echo ""
echo "🚀 تحسين الأداء..."
php artisan config:cache
echo "✅ Config cached"

php artisan route:cache
echo "✅ Routes cached"

php artisan view:cache
echo "✅ Views cached"

php artisan event:cache 2>/dev/null || true

# ─── 8. صلاحيات المجلدات ───
echo ""
echo "🔐 ضبط صلاحيات المجلدات..."
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
echo "✅ Permissions set"

# ─── 9. التحقق النهائي ───
echo ""
echo "═══════════════════════════════════════════════"
echo "   اختبارات ما بعد النشر"
echo "═══════════════════════════════════════════════"
echo ""

# قراءة URL من .env
APP_URL=$(grep "^APP_URL=" .env | cut -d= -f2)

echo "📋 اختبارات يدوية مطلوبة:"
echo ""
echo "   1. افتح: $APP_URL"
echo "      المتوقع: الصفحة الرئيسية تعمل بشكل صحيح"
echo ""
echo "   2. افتح: $APP_URL/admin/login"
echo "      المتوقع: صفحة دخول لوحة الإدارة"
echo ""
echo "   3. سجّل دخول بالبريد: \$ADMIN_EMAIL من .env"
echo "      المتوقع: الدخول للوحة التحكم"
echo ""
echo "   4. افتح: $APP_URL/shop"
echo "      المتوقع: عرض المنتجات (فارغة في البداية)"
echo ""
echo "   5. أضف منتجاً من لوحة الإدارة"
echo "      المتوقع: يظهر في المتجر"
echo ""
echo "═══════════════════════════════════════════════"
echo ""
echo "⚠️  تذكر:"
echo "   • غيّر كلمة مرور المدير فوراً"
echo "   • فعّل SSL من cPanel"
echo "   • أضف صورك ومحتواك الحقيقي"
echo ""
echo "🎉 تم النشر بنجاح! موقع قطعة وقصة جاهز."
echo ""
