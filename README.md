# フリマアプリ

## 環境構築

```bash
git clone https://github.com/riva773/mogi_anken.git
cd mogi_anken

cp -n src/.env.example src/.env
# 以降の「.env 設定例」を参考に、DB とメール(MailHog)の値を確認/調整してください

docker compose up -d --build
docker compose exec php composer install
docker compose exec php php artisan key:generate
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

# テスト実行方法

このプロジェクトは **SQLite（メモリ）** を使って高速・無依存でテストが実行できるようにしています。
MySQL や MailHog の起動は不要です。

```bash
# 念のため設定キャッシュをクリア
docker compose exec php php artisan config:clear

# テスト実行
docker compose exec php php artisan test -v
```

## 使用技術

- PHP：8.1
- Web サーバ：nginx 1.21.1
- DB：MySQL 8.0.26
- Mail：MailHog

## URL 一覧

- 商品一覧画面: http://localhost/
- 商品詳細画面: http://localhost/item/{item_id}
- 会員登録画面: http://localhost/register
- ログイン画面: http://localhost/login
- 商品購入画面: http://localhost/orders/create/{item_id}
- 住所変更画面: http://localhost/users/address/{item_id}/edit
- プロフィール画面: http://localhost/mypage
- プロフィール編集画面: http://localhost/mypage/profile/edit
- 商品出品画面: http://localhost/sell
- マイリスト画面: http://localhost/?page=mylist
- 購入商品一覧: http://localhost/mypage?page=buy
- 出品商品一覧: http://localhost/mypage?page=sell

## ER 図

<img width="979" height="934" alt="Image" src="https://github.com/user-attachments/assets/21743559-f31b-4bc4-86b3-7726d060fc58" />
