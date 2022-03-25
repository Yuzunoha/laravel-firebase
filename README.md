## 特記事項

- [const.php](/html/39liveapi/config/const.php)
  - "管理者", "本人確認 OK", "BAN" 等の定数定義がある。
- [DatabaseSeeder.php](/html/39liveapi/database/seeders/DatabaseSeeder.php)
  - テスト用にダミーのライブデータや、ギフトデータ、管理者アカウント等をシーディングしている。

## 使うツール

- make
- docker
- docker-compose
- 検証環境
  - ![image](https://user-images.githubusercontent.com/22877094/142595452-df5e0cd5-1577-4b9e-a7f5-583c74dda6e9.png)

## デプロイする手順

- `make init` を実行する
  - マイグレーションとシーディングも実行される
  - コンテナが起動すると port80 で待ち受ける

## Docker コンテナの情報を表示する手順

- `make ps` を実行する

## Docker コンテナを停止する手順

- `make down` を実行する

## Docker コンテナを停止した後に起動する手順

- `make up` を実行する

## Swagger

- [docs/swagger.yml](/docs/swagger.yml)

## デモをコールする手順

- ローカルの [frontend/index.html](/frontend/index.html) をブラウザで開く
  - 接続先のサーバは [frontend/main.js](/frontend/main.js) 内の host に記載する

## MySQL コマンドラインに接続する手順

- `make mysql` を実行する
  - ![image](https://user-images.githubusercontent.com/22877094/155843209-47cdfea5-91d9-4ae7-bd48-42b68f5b964e.png)
