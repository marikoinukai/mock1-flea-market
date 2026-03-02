document.addEventListener("DOMContentLoaded", () => {
  // プロフィール編集ページ以外では何もしない
  const input = document.querySelector("#icon-input");
  const preview = document.querySelector("#icon-preview");
  if (!input || !preview) return;

  const placeholder = document.querySelector(".profile-icon__placeholder");

  input.addEventListener("change", (e) => {
    const file = e.target.files && e.target.files[0];
    if (!file) return;

    // 画像以外は対象外
    if (!file.type.startsWith("image/")) return;

    const reader = new FileReader();
    reader.onload = (ev) => {
      preview.src = ev.target.result;
      preview.style.display = "block";
      if (placeholder) placeholder.style.display = "none";
    };
    reader.readAsDataURL(file);
  });
});
