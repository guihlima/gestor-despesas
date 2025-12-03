import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();


// resources/js/app.js

document.addEventListener('DOMContentLoaded', () => {
    // Função que formata número (string ou number) para "1.234,56"
    window.formatBRL = function (value) {
        const n = typeof value === 'number' ? value : Number(String(value).replace(',', '.'));
        if (isNaN(n)) return '0,00';

        return n.toLocaleString('pt-BR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        });
    };

    // Máscara para inputs com data-currency="brl"
    window.attachBRLCurrencyMask = function (input) {
        const format = () => {
            let digits = input.value.replace(/\D/g, ''); // só números

            // remove zeros à esquerda
            digits = digits.replace(/^0+/, '');

            // se ficou vazio, volta para 0,00
            if (!digits) {
                input.value = '';
                return;
            }

            // garante pelo menos 2 dígitos para centavos
            if (digits.length === 1) {
                digits = '0' + digits; // "5" -> "05"
            }

            const cents = digits.slice(-2);
            let ints = digits.slice(0, -2);

            if (!ints) {
                ints = '0';
            }

            // milhar
            ints = ints.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

            input.value = `${ints},${cents}`;
        };

        input.addEventListener('input', format);
        input.addEventListener('blur', format);

        // formata valor inicial, se houver
        if (input.value) {
            format();
        }
    };

    // aplica a máscara em todos os inputs marcados
    document
        .querySelectorAll('input[data-currency="brl"]')
        .forEach((input) => window.attachBRLCurrencyMask(input));
});
