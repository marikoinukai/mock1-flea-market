
            const select = document.getElementById('js-payment-select');
            const label = document.getElementById('js-payment-label');
            const hidden = document.getElementById('js-payment-hidden');

            if (!select || !label || !hidden) return;

            // option(value) -> 表示名
            const labelMap = {};
            for (const opt of select.options) {
                labelMap[opt.value] = opt.textContent;
            }

            const apply = () => {
                const v = select.value || '';
                hidden.value = v;
                label.textContent = v ? (labelMap[v] ?? '未選択') : '未選択';
            };

            select.addEventListener('change', apply);
            apply(); // 初期反映（リロード時など）
       ();
