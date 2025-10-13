# Laravel 12 Todo Application with Docker

Laravel 12 + PostgreSQL + Nginx + PgAdmin4 の Docker 開発環境

## 環境構成

- **Laravel Framework**: 12.x
- **PHP**: 8.3-fpm
- **PostgreSQL**: 16
- **PgAdmin4**: Latest
- **Nginx**: Alpine

## プロジェクト構成

```
.
├── docker/
│   ├── nginx/
│   │   ├── Dockerfile
│   │   └── default.conf
│   └── php/
│       ├── Dockerfile
│       └── php.ini
├── src/                    # Laravel プロジェクトディレクトリ
├── docker-compose.yml
└── README.md
```

## セットアップ手順

### 1. Docker コンテナの起動

```bash
docker-compose up -d
```

### 2. 依存パッケージのインストール (初回のみ)

```bash
docker-compose exec web composer install
```

### 3. アプリケーションキーの生成 (既に生成済み)

```bash
docker-compose exec web php artisan key:generate
```

### 4. データベースマイグレーション

```bash
docker-compose exec web php artisan migrate
```

### 5. ストレージリンクの作成

```bash
docker-compose exec web php artisan storage:link
```

## アクセス URL

- **Laravel アプリケーション**: http://localhost:8000
- **Nginx**: http://localhost
- **PgAdmin4**: http://localhost:5050
  - Email: `admin@example.com`
  - Password: `admin`

## データベース接続情報

### Laravel から PostgreSQL への接続

`.env` ファイルに以下の設定が記述されています:

```
DB_CONNECTION=pgsql
DB_HOST=postgres
DB_PORT=5432
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass
```

### PgAdmin4 から PostgreSQL への接続

1. http://localhost:5050 にアクセス
2. ログイン (admin@example.com / admin)
3. 新しいサーバーを追加:
   - Name: `Laravel DB`
   - Host: `postgres`
   - Port: `5432`
   - Username: `laravel_user`
   - Password: `laravel_pass`

## PHP 設定 (php.ini)

主な設定内容:

- **タイムゾーン**: Asia/Tokyo
- **エラー表示**: On (開発環境)
- **メモリ制限**: 512M
- **アップロード最大サイズ**: 64M
- **実行時間制限**: 300秒
- **OPcache**: 有効化

詳細は `docker/php/php.ini` を参照してください。

## よく使うコマンド

### コンテナの起動・停止

```bash
# 起動
docker-compose up -d

# 停止
docker-compose down

# 再起動
docker-compose restart
```

### Laravel コマンド実行

```bash
# Artisan コマンド
docker-compose exec web php artisan [command]

# Composer コマンド
docker-compose exec web composer [command]

# Tinker
docker-compose exec web php artisan tinker
```

### ログ確認

```bash
# すべてのコンテナのログ
docker-compose logs -f

# 特定のコンテナのログ
docker-compose logs -f web
docker-compose logs -f postgres
```

## トラブルシューティング

### パーミッションエラーが発生する場合

```bash
docker-compose exec web chmod -R 777 storage bootstrap/cache
```

### データベース接続エラーが発生する場合

1. PostgreSQL コンテナが起動しているか確認
```bash
docker-compose ps
```

2. `.env` の DB 設定を確認

3. キャッシュをクリア
```bash
docker-compose exec web php artisan config:clear
docker-compose exec web php artisan cache:clear
```

## 本番環境への移行時の注意点

本番環境にデプロイする際は、以下の設定を変更してください:

1. **php.ini** (`docker/php/php.ini`):
   - `display_errors = Off`
   - `error_reporting = E_ALL & ~E_DEPRECATED & ~E_STRICT`

2. **.env**:
   - `APP_ENV=production`
   - `APP_DEBUG=false`
   - `APP_KEY` を本番用に再生成

3. **docker-compose.yml**:
   - 環境変数を環境ファイルで管理
   - ボリュームをバックアップ対象に追加
