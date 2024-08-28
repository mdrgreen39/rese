# Rese(リーズ)

## 概要説明
- 企業のグループ会社専用の飲食店予約サービス

## 作成した目的
- 外部の飲食店予約サービスは手数料を取られるので自社で予約サービスを持ちたい。初年度でのユーザー数10,000人達成。現行のサービスは機能や画面が複雑で使いづらい。ターゲットユーザー20~30代の社会人。

## 機能一覧
- 会員登録
- ログイン
- ログアウト
- メールアドレス認証(検証)
- ユーザー情報習得
- ユーザー飲食店お気に入り一覧習得
- ユーザー飲食店予約情報習得
- 飲食店一覧習得
- 飲食店詳細習得
- 飲食店お気に入り追加
- 飲食店お気に入り削除
- 飲食店予約情報追加
- 飲食店予約情報削除
- 飲食店予約情報更新
- QRコード発行・称号
- Stripe決済機能
- 店舗評価・コメント機能
- エリアで検索する
- ジャンルで検索する
- 店名で検索する
- キーワードで検索する

## 使用技術
- php 8.2.8
- Laravel 10.48.16
- mysql 8.0.37

## テーブル設計

## ER図

## 環境構築
**Dockerビルド**
1. `git clone git@github.com:mdrgreen39/atte.git`
2. DockerDesktopアプリを立ち上げる
3. `docker-compose up -d --build`

> *MacのM1・M2チップのPCの場合、`no matching manifest for linux/arm64/v8 in the manifest list entries`のメッセージが表示されビルドができないことがあります。
エラーが発生する場合は、docker-compose.ymlファイルの「mysql」内に「platform」の項目を追加で記載してください*
``` bash
mysql:
    platform: linux/x86_64(この文を追加)
    image: mysql:8.0.37
    environment:
```

**Laravel環境構築**
1. `docker-compose exec php bash`
2. `composer install`
3. 「env」ディレクトリを作成
4. 「env」ディレクトリに「.env.local」「.env.production」2つのファイルを作成
   - 「.env.example」ファイルを「.env.local」「.env.production」ファイルに命名変更。または、新しく「.env.local」 「.env.production」ファイルを作成
5. 「.env.local」「.env.production」に以下の環境変数を追加
- .env.local
``` text
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass
```
``` text
QUEUE_CONNECTION=database
```

- .env.production
``` text
APP_NAME=Rese
APP_ENV=production
APP_DEBUG=false
APP_URL=
```
``` text
DB_CONNECTION=mysql
DB_HOST=
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
QUEUE_CONNECTION=database
```
``` text
QUEUE_CONNECTION=database
```
- メール設定 (「.env.local」「.env.production」共通)
  - gmailの設定で以下の設定を行う必要があります。<br>
    - 2段階認証プロセスを「オン」にして「アプリパスワード」を作成、そのパスワードを指定します。<br>
    - 設定方法詳細：[Googleアカウントヘルプ](https://support.google.com/accounts/answer/185833?hl=ja&authuser=1)

``` text
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=       //送信元のメールアドレス
MAIL_PASSWORD=       //アプリパスワード
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=   //送信元のメールアドレス
MAIL_FROM_NAME=""    //メールの送信者に表示される名前
```
  - 本番環境でのメール送信テスト
    -「.env.production」に`MAIL_TEST_MODE=false`を追加
    - テストを行う場合は「true」にし、テストが終了したら「false」に変更
``` text
MAIL_TEST_MODE=false
```

5. アプリケーションキーの作成
``` bash
php artisan key:generate
```

6. マイグレーションの実行
``` bash
php artisan migrate
```

7. シーディングの実行
``` bash
php artisan db:seed
```

## URL
- 開発環境：http://localhost/
- phpMyAdmin:http://localhost:8080/
- 開発環境でのメールテスト：http://localhost/test-email

## 他