document.addEventListener('DOMContentLoaded', () => {

  const input = document.querySelector('.sell-image__input');

  if (!input) return; // 出品画面以外では何もしない

    const drop = document.querySelector('.sell-image__drop');
    const btn  = document.querySelector('.sell-image__btn'); // ボタンを隠す

  // プレビュー用imgを自動生成
  const preview = document.createElement('img');
  preview.style.maxHeight = '100%';
  preview.style.objectFit = 'contain';
  preview.style.display = 'none';

  drop.appendChild(preview);

  input.addEventListener('change', (e) => {

    const file = e.target.files[0];

      // キャンセル → 元に戻す
    if (!file) {
      preview.style.display = 'none';
      preview.src = '';
      if (btn) btn.style.display = 'inline-flex';
      return;
    }

    const reader = new FileReader();

    reader.onload = (ev) => {
      preview.src = ev.target.result;
      preview.style.display = 'block';
    if (btn) btn.style.display = 'none'; // 文字を消す
    };

    reader.readAsDataURL(file);
  });

});
