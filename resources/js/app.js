import { initDashboardCurrency } from './currency';

const formatThousands = (value) => {
    const digits = String(value).replace(/\D/g, '');

    if (digits === '') {
        return '';
    }

    return digits.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
};

const parseFormattedNumber = (value) => value.replace(/\./g, '');

const initPasswordToggles = () => {
    document.querySelectorAll('[data-toggle-password]').forEach((button) => {
        const input = document.getElementById(button.dataset.togglePassword);

        if (!input) {
            return;
        }

        const iconShow = button.querySelector('[data-icon-show]');
        const iconHide = button.querySelector('[data-icon-hide]');

        button.addEventListener('click', () => {
            const isHidden = input.type === 'password';

            input.type = isHidden ? 'text' : 'password';
            button.setAttribute('aria-pressed', String(isHidden));
            button.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');
            iconShow?.classList.toggle('hidden', isHidden);
            iconHide?.classList.toggle('hidden', !isHidden);
        });
    });
};

const initPriceInputs = () => {
    document.querySelectorAll('[data-price-input]').forEach((displayInput) => {
        const hiddenInput = document.getElementById(displayInput.dataset.priceTarget);

        if (!hiddenInput) {
            return;
        }

        if (displayInput.value) {
            displayInput.value = formatThousands(displayInput.value);
            hiddenInput.value = parseFormattedNumber(displayInput.value);
        }

        displayInput.addEventListener('input', () => {
            const digits = parseFormattedNumber(displayInput.value);

            hiddenInput.value = digits;
            displayInput.value = formatThousands(digits);

            const end = displayInput.value.length;
            displayInput.setSelectionRange(end, end);
        });

        displayInput.addEventListener('blur', () => {
            if (displayInput.value === '') {
                hiddenInput.value = '';
            }
        });
    });

    document.querySelectorAll('form:has([data-price-input])').forEach((form) => {
        form.addEventListener('submit', () => {
            form.querySelectorAll('[data-price-input]').forEach((displayInput) => {
                const hiddenInput = document.getElementById(displayInput.dataset.priceTarget);

                if (hiddenInput) {
                    hiddenInput.value = parseFormattedNumber(displayInput.value);
                }
            });
        });
    });
};

document.addEventListener('DOMContentLoaded', () => {
    initPasswordToggles();
    initPriceInputs();
    initDashboardCurrency();
});
