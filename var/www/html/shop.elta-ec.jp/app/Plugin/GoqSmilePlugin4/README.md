# GoQSmile EC-CUBE4系向けプラグイン
以下、開発環境の構築方法から、プラグインのインストール方法などをまとめています。

- **開発中の注意点**は必ず確認してください。

- 基本仕様はGoQMierucaPlugin4と同じのため、構成や仕様などは[GoQMierucaPlugin4 Wiki](https://github.com/GoQSystemInc/mieruca-eccube-plugin-v4/wiki)を確認してください。

## 開発環境構築
1. [EC-CUBE 4.0](https://github.com/EC-CUBE/ec-cube)を`git clone`
2. `cd path/to/ec-cube` ec-cubeディレクトリに移動
3. .envを作成`cp .env.dist .env`
4. .envの修正（エラーログの設定：8, 9行目）
```
# APP_ENV=prod
# APP_DEBUG=0
APP_ENV=dev
APP_DEBUG=1
```
5. docker-compose.ymlのPostgres、MySQl、Mailcatcherを削除もしくはコメントアウト（使用しないので）
```
### Postgres ################################ 以下

### MySQL ################################## 以下

### Mailcatcher ################################## 以下
```
6. コンテナ起動 `docker-compose up -d`
7. インストールスクリプトを実行（初期化されるので初回以外は実行しないように）
```
docker-compose exec -u www-data ec-cube bin/console eccube:install
```
下記の内容が表示されますが、全てEnterでOKです。
```
EC-CUBE Installer Interactive Wizard
====================================

 If you prefer to not use this interactive wizard, define the environment valiables as follows:
 
  $ export APP_ENV=prod
  $ export APP_DEBUG=0
  $ export DATABASE_URL=database_url
  $ export DATABASE_SERVER_VERSION=server_version
  $ export MAILER_URL=mailer_url
  $ export ECCUBE_AUTH_MAGIC=auth_magic
  ... and more
  $ php bin/console eccube:install --no-interaction
 

 Database Url [sqlite:///var/eccube.db]:
 > 

 Mailer Url [null://localhost]:
 > 

 Auth Magic [xxxxxxxxx]:
 >

!                                                                                                 
! [CAUTION] Execute the installation process. All data is initialized.                            
!                                                                                                 

 Is it OK? (yes/no) [yes]:
```
8. EC-CUBEのサイトがローカルで見れるか確認する。

フロント側 http://localhost:8080/

管理画面 http://localhost:8080/admin
```
デフォルトのID/PW 公式サイトのデモサイトと同じ。
ID: admin
PW: password
```
9. `cd ec-cube/app/Pulgin`に移動
10. このリポジトリを`git clone`、ルートディレクトリ名をGoqSmilePlugin4に変更。（下記コマンドで同時処理できます）
```
git clone https://github.com/GoQSystemInc/smile-eccube-plugin-v4.git ./GoqSmilePlugin4
```
- 以降のコンテナの起動と停止
```
docker-compose up -d
docker-compose down
```

## プラグインのインストール・有効化・無効化・アンインストールの方法
**プラグインインストールの前に認証キーを設定してください。**

`管理画面->オーナーズストア->認証キー発行 画面`
- プラグインをインストール
- プラグインを有効化
```
docker-compose exec ec-cube bin/console eccube:plugin:install --code=GoqSmilePlugin4
docker-compose exec ec-cube bin/console eccube:plugin:enable --code=GoqSmilePlugin4
```
- プラグインを無効化
- プラグインをアンインストール
```
docker-compose exec ec-cube bin/console eccube:plugin:disable --code=GoqSmilePlugin4
docker-compose exec ec-cube bin/console eccube:plugin:uninstall --code=GoqSmilePlugin4
```

## 開発中の注意点
- **localhost上(GUI)**で開発中のプラグインのインストール等を行わないこと。
- ターミナル上で、公式にある下記削除コマンドを実行しないこと。
```
bin/console eccube:plugin:uninstall --code=プラグインコード --uninstall-force=true
```
**実行すると、app/Plugin配下のソースがすべてシステム的に削除される(=ゴミ箱内にも存在しない。。。インストールエラーでも削除されます。。。）。つまり、.gitディレクトリが復帰できなくなり、ローカルブランチやstashした内容が全て消え、それまでの作業が水の泡に。。。**

## デモサイトでの動作確認
デモサイト：	http://eccube4.59system.net/ec/

デモサイト管理：http://eccube4.59system.net/ec/administrator/

1. 任意の場所で`git clone https://github.com/GoQSystemInc/smile-eccube-plugin-v4.git ./GoqSmilePlugin4`
2. `cd path/to/GoqSmilePlugin4` GoqSmilePlugin4ディレクトリに移動
3. ディレクトリ内のフォルダ/ファイルを全て選択して圧縮
4. デモサイト管理の`オーナーズストア/プラグイン/プラグイン一覧`へ
5. ユーザー独自のプラグインの`アップロードして新規追加`を選択
6. ファイルを選択に先ほど圧縮した.zipファイルをドラッグしてインストール
**インストール前、アンインストール後にコンテンツ管理/キャッシュ管理でキャッシュを削除してください**

## 開発が終わったら、docker開発環境を全て削除しましょう。
- docker-composeで作成したEC-CUBEのコンテナ、イメージ、ボリューム、ネットワークを削除します。

` cd path/to/ec-cube` ec-cubeディレクトリで下記コマンドを実行。
```
docker-compose down --rmi all --volumes --remove-orphans
```
