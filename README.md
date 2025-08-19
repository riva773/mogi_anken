# 模擬フリマアプリ（mogi_anken）

## 環境構築

```bash
git clone https://github.com/riva773/mogi_anken.git
cd mogi_anken

cp -n src/.env.example src/.env
# 以降の「.env 設定例」を参考に、DB とメール(MailHog)の値を確認/調整してください

docker compose up -d --build
docker compose exec php composer install
docker compose exec php php artisan key:generate      APP_KEY を生成（暗号化・セッションに必須）
docker compose exec php php artisan storage:link
docker compose exec php php artisan migrate --seed
```

## .env 設定例

```bash
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass


MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=noreply@example.com
MAIL_FROM_NAME="Furima App"
QUEUE_CONNECTION=sync
```

## 使用技術

- PHP：8.1
- Web サーバ：nginx 1.21.1
- DB：MySQL 8.0.26
- Mail：MailHog

## ER 図
