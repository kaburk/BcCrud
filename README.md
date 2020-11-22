# BcCrud
baserCMS4系のプラグイン作成用のベースプラグインです。

ブログのようなCRUD（Create（生成）、Read（読み取り）、Update（更新）、Delete（削除））の機能があります。

* 記事一覧、詳細
* カテゴリ
* タグ

※ カテゴリ、タグにアイキャッチと説明欄を追加しています。

※ タグはブログとは異なり、コンテンツ毎に設定可能です

-------

## 使い方

プラグインをインストール後、有効化すると、管理画面のコンテンツ管理右クリックメニュー一番下「その他」に「BcCrud」が追加されます。

あとの操作はほぼブログと同じです。

## フロント側Viewのカスタマイズ

フロント側はbaserCMS4系の標準テーマ（bc_sample）用のViewがデフォルトでセットされています。

フロント側をカスタマイズする場合はブログと同じようにプラグイン内のdefaultフォルダをテーマに複製して使います。

    app/Plugin/BcCrud/View/BcCrud/default
    　　↓（フォルダごとコピー）
    theme/{テーマ名}/BcCrud/default
    
    theme/{テーマ名}/BcCrud/hogefuga1
    theme/{テーマ名}/BcCrud/hogefuga2
    　　：

複数設置もOKです。

管理画面の設定でコンテンツテンプレートを指定してください。

-------

License
-------
Lincensed under the MIT lincense since version 2.0
