# Rese(リーズ)

## 概要説明
このアプリケーションは、飲食店の予約管理システムです。ユーザーは飲食店を検索し、予約を行い、評価やレビューを投稿できます。

## 作成した目的
- 本アプリケーションは、企業のグループ会社専用の飲食店予約サービスを提供することを目的としています。外部の飲食店予約サービスでは手数料が発生するため、企業として自社で予約サービスを構築することでコスト削減を図ります。初年度の目標として、ユーザー数10,000人を達成することを目指しています。
- 現在のサービスは機能や画面が複雑で使いづらいため、ターゲットユーザーである20〜30代の社会人がスムーズに利用できるように、シンプルで直感的なインターフェースを提供することにも重点を置いています。

## 機能一覧
- ユーザー関連
  - **会員登録**: 新しいユーザーがアカウントを作成し、サービスを利用開始できます。
  - **ログイン機能**: 登録済みのユーザーがアカウントにアクセスできます。
  - **ログアウト機能**: ユーザーが安全にアカウントからログアウトできます。
  - **メールアドレス認証(検証)**: ユーザーが登録時にメールアドレスを確認することで、アカウントの安全性を高めます。
  - **ユーザー情報習得**: ユーザーの基本情報を取得し、マイページにてお気に入り一覧と予約一覧を表示します。
  - **ユーザー飲食店お気に入り一覧習得**: ユーザーがお気に入りに追加した飲食店の一覧を取得します。
  - **ユーザー飲食店予約情報習得**: ユーザーが予約した飲食店の情報を取得します。
  - **飲食店一覧習得**: 利用可能な飲食店の一覧を表示します。
  - **飲食店詳細習得**: 特定の飲食店の詳細情報を表示します。
  - **検索機能(エリア・ジャンル・店名・キーワード)**: ユーザーが希望の飲食店を簡単に検索できます。
  - **飲食店お気に入り追加**: ユーザーが飲食店をお気に入りに追加できます。
  - **飲食店お気に入り削除**: ユーザーがお気に入りから飲食店を削除できます。
  - **飲食店予約情報追加**: ユーザーは飲食店の予約を行うことができます。
  - **飲食店予約情報削除**: ユーザーは飲食店の予約をキャンセルすることができます。
  - **飲食店予約情報更新**: ユーザーは既存の予約内容を変更することができます。
  - **店舗評価・コメント機能**: ユーザーが利用した飲食店に対して評価やコメントを投稿できます。
  - **Stripe決済機能**: ユーザーが飲食店の予約に対してオンラインで決済を行うことができます。
- 管理者関連
  - **店舗代表者登録**: 管理者は管理画面を通じて店舗代表者を登録できます。
  - **お知らせメール送信**: 管理者は管理画面からユーザーまたは店舗代表者にお知らせメールを送信できます。
- 店舗代表者関連
  - **店舗情報作成**: 店舗代表者は管理画面から新しい店舗情報を作成できます。
  - **店舗情報更新**: 店舗代表者は登録した店舗情報を編集できます。
  - **店舗予約情報確認**: 店舗代表者は自店舗の予約状況を確認できます。
  - **QRコードによる来店確認機能**: 店舗代表者はQRコードを読み込んで予約した人の来店確認をすることができる。
- その他
  - **予約情報のリマインダーメール送信**: ユーザーに対して予約当日にリマインダーメールが自動で送信されます。
  - **未登録ユーザー向けのページ閲覧機能(飲食店一覧習得・飲食店詳細習得)**: 未登録のユーザーは、飲食店の一覧や詳細情報を閲覧することができます。これにより、アカウントを作成する前に、提供されているサービスや店舗情報を確認することができます。

## 使用技術
- php 8.2.8
- Laravel 10.48.16
- mysql 8.0.37

## テーブル設計

## ER図

## 環境構築
### Dockerビルド
#### 1. `git clone git@github.com:mdrgreen39/rese.git`
#### 2. DockerDesktopアプリを立ち上げる
#### 3. `docker-compose up -d --build`

> *MacのM1・M2チップのPCの場合、`no matching manifest for linux/arm64/v8 in the manifest list entries`のメッセージが表示されビルドができないことがあります。
エラーが発生する場合は、docker-compose.ymlファイルの「mysql」内に「platform」の項目を追加で記載してください*
``` bash
mysql:
    platform: linux/x86_64(この文を追加)
    image: mysql:8.0.37
    environment:
```

### Laravel環境構築
#### 1. `docker-compose exec php bash`
#### 2. `composer install`
#### 3. `.env.local` `.env.production`に環境変数を追加
- `env`ディレクトリを作成
- `env`ディレクトリに`.env.local` `.env.production`2つのファイルを作成
> `.env.example`ファイルを`.env.local` `.env.production`ファイルに命名変更。または、新しく`.env.local` `.env.production`ファイルを作成

**`.env.local`**
``` text
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass
QUEUE_CONNECTION=database
LIVEWIRE_DEBUG=true
```
**`.env.production`**
``` text
APP_NAME=Rese
APP_ENV=production
APP_DEBUG=false
APP_URL=
DB_CONNECTION=mysql
DB_HOST=
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
QUEUE_CONNECTION=database
LIVEWIRE_DEBUG=false
```

### 4. メール設定
このプロジェクトでは、ローカル環境と本番環境で異なるメール設定を使用します
- ローカル環境では、メール送信のテストに **Mailtrap** を使用します。以下の手順に従って設定してください。
  - Mailtrapの設定手順
     1. [Mailtrap公式サイト](https://mailtrap.io/)にアクセスし、アカウントを作成します。
     2. SMTP設定情報を取得します。
      - SMTP Settings タブををクリック
      - Integrations セレクトボックスで、Laravel 9+ を選択
      - copy ボタンをクリックして、クリップボードに .env の情報を保存
     3. mailtrap からコピーした情報を、プロジェクトの `.env.local` ファイルに貼り付ます。

```text
MAIL_DRIVER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=
MAIL_PASSWORD=　　　　　　
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=　　//送信元のメールアドレス
MAIL_FROM_NAME="${APP_NAME}"   //メールの送信者に表示される名前
```
- 本番環境では、メール送信のテストに **Gmail** を使用します。以下の手順に従って設定してください。
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
MAIL_FROM_NAME="${APP_NAME}"  //メールの送信者に表示される名前
```
- 本番環境でのメール送信テスト
  -「.env.production」に`MAIL_TEST_MODE=false`を追加
  - テストを行う場合は「true」にし、テストが終了したら「false」に変更
``` text
MAIL_TEST_MODE=false
```

#### 5. Stripe設定
このプロジェクトでは、決済機能としてStripeを使用します。ローカル環境（テスト環境）と本番環境で異なる設定を行う必要があります
  - テスト環境のの設定手順
    1. [Stripe公式サイト](https://stripe.com/jp)にアクセスし、アカウントを作成します。
    2. アカウントが作成できたら、ダッシュボードにログインします。
    3.「開発者」セクションに移動し、テスト用のAPIキーを取得します。
    - テスト用の公開可能キーとシークレットキーが表示されます。
    4. 環境変数（`.env.local`ファイル）に以下のようにAPIキーを設定します。
``` text
STRIPE_KEY=テスト用公開可能キー
STRIPE_SECRET=テスト用シークレットキー
```
  - テスト環境のの設定手順
      1. [Stripe公式サイト](https://stripe.com/jp)にアクセスし、アカウントを作成します（もしまだ作成していない場合）。
      2. アカウントが作成できたら、ダッシュボードにログインします。
      3.「開発者」セクションに移動し、本番環境用のAPIキーを取得します。
      - ここで公開可能キーとシークレットキーを確認できます（本番環境のキーを使用してください）。
      4. 環境変数（`.env.production`ファイル）に以下のようにAPIキーを設定します。
``` text
STRIPE_KEY=本番用公開可能キー
STRIPE_SECRET=本番用シークレットキー
```

#### 6. ストレージ設定
- ローカル環境
``` bash
php artisan storage:link
```
> *注意事項:
ローカル環境でのテスト時には、ファイルストレージのパーミッションに注意してください。適切に設定されていないと、QRコードの保存や読み込みが正常に行われないことがあります。*

**解決策**
  1. パーミッションの設定: 次のコマンドを実行して、`storage `ディレクトリのパーミッションを適切に設定してください。
``` bash
chmod -R 775 storage
```
  2. 所有者の確認: ストレージディレクトリの所有者がウェブサーバーのユーザー（通常は `www-data` や `nginx` など）になっているかを確認します。<br>
    確認するコマンド例：
``` bash
ls -la storage
```
> 出力例:`drwxrwxr-x  2 user group 4096 Oct 11 12:00 app`
   3. 問題が解決されない場合: 必要に応じて、サーバーの設定を見直し、適切なパーミッションが設定されているか再確認してください。

- 本番環境
  - このプロジェクトでは、S3を使用します。S3を利用するために、`.env.production`ファイルに以下の設定を追加してください。
``` text
FILESYSTEM_DRIVER=s3
AWS_ACCESS_KEY_ID=your_access_key
AWS_SECRET_ACCESS_KEY=your_secret_key
AWS_DEFAULT_REGION=your_region
AWS_BUCKET=your_bucket_name
AWS_URL=https://your_bucket.s3.amazonaws.com
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