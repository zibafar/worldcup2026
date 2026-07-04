# 🏆 WorldCup Prediction Project (RashtGold)

سیستم پیش‌بینی مسابقات جام جهانی با Laravel + Blade + Docker

---

## 📦 معماری پروژه

| سرویس | توضیح | پورت |
|------|------|------|
| app | PHP / Laravel | 9000 (داخلی) |
| nginx | وب‌سرور | 8080 |
| mysql | دیتابیس | 3307 |
| phpmyadmin | مدیریت دیتابیس | 8081 |

---

## 🚀 اجرای پروژه

```bash
docker compose up -d --build
```

---

## 🔍 بررسی سرویس‌ها

```bash
docker compose ps
```

---

## 🧱 Migration

```bash
docker compose exec app php artisan migrate
```

---

## 🌱 Seeder

```bash
docker compose exec app php artisan db:seed
```

یا:

```bash
docker compose exec app php artisan db:seed --class=WorldCup2026Seeder
```

---

## 🌐 دسترسی‌ها

### سایت
http://localhost:8080/worldcup

### ادمین
http://localhost:8080/admin/worldcup

### phpMyAdmin
http://localhost:8081

```
Server: mysql
User: rashtgold
Password: secret
```

---

## ⚙️ .env

```env
APP_URL=http://localhost:8080

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=rashtgold_worldcup
DB_USERNAME=rashtgold
DB_PASSWORD=secret
```

---

## 🐳 شبکه Docker

rashtgold_net

ارتباط داخلی DB:
DB_HOST=mysql

---

## 🔧 Nginx

- port خارجی: 8080
- port داخلی: 80

```yaml
ports:
  - "8080:80"
```

---

## 🗄️ Database

rashtgold_worldcup

---

## 🧹 پاکسازی

```bash
docker compose down -v
docker system prune -a
```

---

## 🧪 تست

```bash
curl http://localhost:8080/worldcup
```

---

## ⚠️ نکات

- MySQL فقط داخل Docker قابل دسترسی است
- App مستقیم expose نیست
- همه سرویس‌ها داخل یک network هستند
