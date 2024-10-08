# アプリ名
StadiumStories
## 概要
今回は下記の使用技術を使い、1人で要件定義、フロント・バックエンド・デプロイを行いました。
作成背景は、自分がよく野球観戦に行くのということで、いったことのない球場に行くときに行き方がわからない時があった。
そんな時にルートをあらかじめ検索し、保存できたら自分もほかのユーザーも快適に球場に足を運べると思う。
そのためにGoogleMapAPIを使用し、球場を保存し、球場から駅まで(2地点間)のルートの保存を可能にした。
また、球場に売っている飲食を高価であるので道沿いの店を自動検索し、そこで購入することで快適な観戦が可能になると思う。

## 使用技術
PHP:8.2.21 
Laravel:10.48.20
Tailwind:3.1.0
Mysql:10.5.25-MariaDB  

Javascript
HTML
CSS  

デプロイ：Heroku
インフラ：Amazon AWS  
[![使用技術アイコン](https://skillicons.dev/icons?i=php,laravel,tailwind,heroku,Amazon AWS,Javascript,HTML,CSS)](https://skillicons.dev)

## URL・テストユーザー
URL:https://stadiumstories-8d9ef140c985.herokuapp.com  
テストユーザー
- name:testname
- email:test@test.com
  
## 機能
- 投稿周りのCRUD
- いいね機能
- コメント機能
- 画像投稿
- Map機能
- 二地点間の経路表示
- 経路から半径500メートル内の店舗表示
- ログイン機能
- プロフィール機能
  

## 工夫点
- 球場から最寄りの駅の二地点間の経路表示・保存をGoogleMapAPIを使用し、可能にしたところ
- 経路から半径500メートル内の飲食店、コンビニエンスストア、スーパーマーケットを分けて自動取得できるところ
- 自動取得した店舗に対し、野球ファン目線のお店選びや投稿を可能にした

## 苦労した点
- ルートに表示されている店舗ごとに対する投稿を可能にするためのリレーション
- 二地点間のルートを決めるためのデータベース設計

## 今後の展望
- 今後つけたい機能・やりたいこと
- 球場のフードを投稿するためのもう一つの投稿機能
- ユーザーの好みに合った店舗表示

## ER図・ワイヤーフレームなど
![スクリーンショット 2024-09-30 154235](https://github.com/user-attachments/assets/41aa6916-e85c-4a5b-b4bc-0783827fdbf5)
