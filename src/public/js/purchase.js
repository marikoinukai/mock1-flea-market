document.addEventListener('DOMContentLoaded', () => {
  const select = document.getElementById('js-payment-select');
  const label = document.getElementById('js-payment-label');
  const hidden = document.getElementById('js-payment-hidden');
  if (!select || !label || !hidden) return;

  const labelMap = {};
  for (const opt of select.options) {
    labelMap[opt.value] = (opt.textContent || '').trim();
  }

  const apply = () => {
    const v = select.value || '';
    hidden.value = v;
    label.textContent = v ? (labelMap[v] ?? '未選択') : '未選択';
  };

  select.addEventListener('change', apply);
  apply();
});
