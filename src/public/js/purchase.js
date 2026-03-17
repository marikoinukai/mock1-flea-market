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

document.addEventListener('DOMContentLoaded', function () {
    const trigger = document.getElementById('js-payment-trigger');
    const menu = document.getElementById('js-payment-menu');
    const hiddenInput = document.getElementById('js-payment-hidden');
    const triggerLabel = document.getElementById('js-payment-trigger-label');
    const summaryLabel = document.getElementById('js-payment-label');
    const options = document.querySelectorAll('.purchase-payment-custom__option');
    const wrapper = document.querySelector('.purchase-payment-custom');

    if (!trigger || !menu || !hiddenInput || !triggerLabel || !summaryLabel) {
        return;
    }

    trigger.addEventListener('click', function () {
        menu.classList.toggle('is-hidden');
        wrapper.classList.toggle('is-open');
    });

    options.forEach(function (option) {
        option.addEventListener('click', function () {
            const value = option.dataset.value;
            const label = option.dataset.label;

            hiddenInput.value = value;
            triggerLabel.textContent = label;
            summaryLabel.textContent = label;

            options.forEach(function (opt) {
                opt.classList.remove('is-selected');
            });

            option.classList.add('is-selected');
            menu.classList.add('is-hidden');
            wrapper.classList.remove('is-open');

        });
    });

    document.addEventListener('click', function (e) {
        if (!e.target.closest('.purchase-payment-custom')) {
            menu.classList.add('is-hidden');
            wrapper.classList.remove('is-open');
        }
    });
});
