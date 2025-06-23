# Update Test プラグイン

このリポジトリは WordPress プラグイン「Update Test」のサンプル実装です。

## 機能
- WordPress管理画面で有効化・無効化が可能
- GitHub Pages上の `update.json` を利用した自動アップデート対応
- GitHub Actionsによる自動リリース・自動バージョン管理

## 自動アップデートの仕組み
- `autoapudate.php` で `update.json` を参照し、バージョンが上がっていれば自動で更新通知
- `update.json` の例:

```
{
  "version": "1.2.3",
  "package": "https://github.com/m-g-n/update_test/releases/download/v1.2.3/update_test.zip",
  "url": "https://github.com/m-g-n/update_test"
}
```

## 開発・リリースフロー
1. `update_test.php` の Version を更新し main ブランチに push
2. GitHub Actions が自動でタグ・リリース・zip作成・update.json更新を実行
3. ユーザーは WordPress 管理画面から自動更新可能

## ライセンス
MIT License

## 履歴
- 1.0.0: 初版リリース
- 1.0.1: 自動で更新ファイルが作れるようにテスト
- 1.0.4: 受信側から自動更新が補足されるかのテスト