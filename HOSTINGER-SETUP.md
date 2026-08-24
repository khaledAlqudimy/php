# دليل النشر على Hostinger Business

## المتطلبات
- Hostinger Business (PHP 8.2+)
- MySQL قاعدة بيانات (من cPanel)
- Terminal / SSH أو File Manager

---

## الخطوة 1 — رفع الملفات

### الطريقة أ: عبر Git (موصى بها)
```bash
# من Terminal في Hostinger
cd ~/domains/your-domain.com
git clone https://github.com/your-username/qet3a.git .
```

### الطريقة ب: رفع مباشر
1. ارفع ملفات المشروع عبر File Manager أو FTP
2. ضعها في مجلد `~/domains/your-domain.com/`

---

## الخطوة 2 — إنشاء قاعدة البيانات

1. افتح **cPanel → MySQL Databases**
2. أنشئ قاعدة بيانات باسم: `u123456_qet3a`
3. أنشئ مستخدم: `u123456_qet3a_user` بكلمة مرور قوية
4. أضف المستخدم للقاعدة بصلاحية **All Privileges**

---

## الخطوة 3 — إعداد .env

```bash
cp .env.example .env
nano .env
```

عدّل هذه القيم:
```
APP_URL=https://your-domain.com
DB_DATABASE=u123456_qet3a
DB_USERNAME=u123456_qet3a_user
DB_PASSWORD=كلمة_المرور_الحقيقية
ADMIN_PASSWORD=كلمة_مرور_قوية_للمدير
```

---

## الخطوة 4 — تثبيت المكتبات

```bash
composer install --optimize-autoloader --no-dev
```

---

## الخطوة 5 — إعداد Laravel

```bash
# توليد مفتاح التشفير
php artisan key:generate

# إنشاء جداول قاعدة البيانات
php artisan migrate --force

# إضافة بيانات أولية (مدير + تصنيفات)
php artisan db:seed --force

# ربط مجلد الصور
php artisan storage:link

# تحسين الأداء
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## الخطوة 6 — ضبط Document Root

من **cPanel → Domains → Manage**:
- غيّر Document Root إلى: `public_html/public`

أو إذا كان الموقع الرئيسي، تأكد أن `.htaccess` في الجذر موجود (مرفق مع المشروع).

---

## الخطوة 7 — تفعيل SSL

من **cPanel → SSL/TLS → Force HTTPS Redirect**

---

## ✅ اختبارات ما بعد النشر

افتح هذه الروابط وتحقق:

| الرابط | المتوقع |
|---|---|
| `https://your-domain.com` | صفحة Laravel الترحيبية (مؤقتاً) |
| `https://your-domain.com/admin/login` | صفحة دخول الإدارة |
| `https://your-domain.com/up` | `{"status":"ok"}` |

---

## بيانات الدخول الافتراضية

```
رابط الإدارة: https://your-domain.com/admin/login
البريد: admin@qet3a-w-qesa.sa
كلمة المرور: (ما وضعته في ADMIN_PASSWORD)
```

⚠️ **غيّر كلمة مرور المدير فوراً بعد أول دخول**

---

## مشاكل شائعة على Hostinger

| المشكلة | الحل |
|---|---|
| 500 Server Error | تحقق من `.env` وصلاحيات مجلد `storage/` (755) |
| لا تظهر الصور | شغّل `php artisan storage:link` مرة أخرى |
| خطأ في قاعدة البيانات | تحقق من بيانات `.env` في phpMyAdmin |
| صفحة بيضاء | `APP_DEBUG=true` مؤقتاً لرؤية الخطأ، ثم أعده `false` |
