import { Chart, ArcElement, Tooltip, Legend, DoughnutController } from 'chart.js';

Chart.register(ArcElement, Tooltip, Legend, DoughnutController);

export const createCurrencyEngine = (rates, meta) => {
    const convert = (amount, from, to) => {
        if (from === to) {
            return Number(amount);
        }

        return (Number(amount) * rates[from]) / rates[to];
    };

    const monthlyEquivalent = (price, billingCycle) =>
        billingCycle === 'Yearly' ? Number(price) / 12 : Number(price);

    const formatPrecise = (amount, currency) => {
        const m = meta[currency];
        const negative = amount < 0;
        const absolute = Math.abs(Number(amount));

        const raw = absolute.toFixed(6).replace(/\.?0+$/, '');
        const [intPart, decPart = ''] = raw.split('.');

        const intFormatted = Number(intPart).toLocaleString('en-US').replace(/,/g, m.thousandSep);

        const body = decPart !== '' ? `${intFormatted}${m.decimalSep}${decPart}` : intFormatted;

        return `${negative ? '-' : ''}${m.symbol} ${body}`;
    };

    const monthlyTotal = (items, displayCurrency) =>
        items
            .filter((sub) => sub.is_active)
            .reduce((sum, sub) => {
                const monthly = monthlyEquivalent(sub.price, sub.billing_cycle);

                return sum + convert(monthly, sub.currency, displayCurrency);
            }, 0);

    const spendingByCategory = (items, displayCurrency) => {
        const groups = {};

        items
            .filter((sub) => sub.is_active)
            .forEach((sub) => {
                const category = sub.category || 'Other';
                const monthly = monthlyEquivalent(sub.price, sub.billing_cycle);
                const value = convert(monthly, sub.currency, displayCurrency);

                groups[category] = (groups[category] || 0) + value;
            });

        return Object.entries(groups)
            .map(([category, total]) => ({ category, total }))
            .sort((a, b) => b.total - a.total);
    };

    return {
        convert,
        monthlyEquivalent,
        formatPrecise,
        monthlyTotal,
        spendingByCategory,
    };
};

export const initDashboardCurrency = () => {
    const root = document.getElementById('dashboard-currency-root');

    if (!root) {
        return;
    }

    const config = JSON.parse(root.dataset.currencyConfig || '{}');
    const subscriptions = JSON.parse(root.dataset.subscriptions || '[]');
    const chartColors = JSON.parse(root.dataset.chartColors || '[]');
    const saveUrl = root.dataset.saveCurrencyUrl;

    const engine = createCurrencyEngine(config.rates, config.meta);
    const currencySelect = document.getElementById('display-currency');
    const categorySelect = document.getElementById('category-filter');
    const totalEl = document.getElementById('monthly-total-display');
    const hintEl = document.getElementById('currency-hint');
    const filterEmpty = document.getElementById('filter-empty');
    const grid = document.getElementById('subscription-grid');
    const chartCanvas = document.getElementById('category-chart');

    let chartInstance = null;

    if (categorySelect && root.dataset.initialCategory) {
        categorySelect.value = root.dataset.initialCategory;
    }

    const updateCard = (card, displayCurrency) => {
        const price = Number(card.dataset.price);
        const currency = card.dataset.currency;
        const billingCycle = card.dataset.billingCycle;
        const showOriginal = currency !== displayCurrency;

        const priceConverted = engine.convert(price, currency, displayCurrency);
        const monthlyConverted = engine.convert(
            engine.monthlyEquivalent(price, billingCycle),
            currency,
            displayCurrency
        );

        card.querySelector('[data-card-currency-badge]').textContent = displayCurrency;
        card.querySelector('[data-card-price]').textContent = engine.formatPrecise(
            priceConverted,
            displayCurrency
        );

        const monthlyEl = card.querySelector('[data-card-monthly]');
        monthlyEl.textContent = `~${engine.formatPrecise(monthlyConverted, displayCurrency)}/mo`;

        const originalEl = card.querySelector('[data-card-original]');
        originalEl.textContent = `Originally ${engine.formatPrecise(price, currency)}${
            billingCycle === 'Yearly' ? ' /yr' : ''
        }`;
        originalEl.classList.toggle('hidden', !showOriginal);
    };

    const filterCards = (categoryFilter) => {
        let visible = 0;

        document.querySelectorAll('[data-subscription-card]').forEach((card) => {
            const match = !categoryFilter || card.dataset.category === categoryFilter;

            card.classList.toggle('hidden', !match);

            if (match) {
                visible += 1;
            }
        });

        if (filterEmpty && grid) {
            filterEmpty.classList.toggle('hidden', visible > 0);
            grid.classList.toggle('hidden', visible === 0);
        }
    };

    const updateChart = (displayCurrency, categoryFilter) => {
        if (!chartCanvas) {
            return;
        }

        const filtered = subscriptions.filter((sub) => {
            if (!sub.is_active) {
                return false;
            }

            if (categoryFilter && sub.category !== categoryFilter) {
                return false;
            }

            return true;
        });

        const chartData = engine.spendingByCategory(filtered, displayCurrency);

        if (chartData.length === 0) {
            chartCanvas.parentElement?.classList.add('hidden');

            return;
        }

        chartCanvas.parentElement?.classList.remove('hidden');

        const labels = chartData.map((row) => row.category);
        const values = chartData.map((row) => row.total);

        if (chartInstance) {
            chartInstance.data.labels = labels;
            chartInstance.data.datasets[0].data = values;
            chartInstance.update();

            return;
        }

        chartInstance = new Chart(chartCanvas, {
            type: 'doughnut',
            data: {
                labels,
                datasets: [
                    {
                        data: values,
                        backgroundColor: chartColors,
                        borderColor: '#1e293b',
                        borderWidth: 2,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { color: '#94a3b8', boxWidth: 12 },
                    },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => {
                                const value = engine.formatPrecise(ctx.raw, displayCurrency);

                                return ` ${ctx.label}: ${value}`;
                            },
                        },
                    },
                },
            },
        });
    };

    const render = (displayCurrency, categoryFilter = '') => {
        const items = subscriptions.filter(
            (sub) => !categoryFilter || sub.category === categoryFilter
        );

        const total = engine.monthlyTotal(items, displayCurrency);

        totalEl.textContent = engine.formatPrecise(total, displayCurrency);
        hintEl.textContent = `Totals convert active bills to ${displayCurrency} using static rates (no rounding).`;

        document.querySelectorAll('[data-subscription-card]').forEach((card) => {
            updateCard(card, displayCurrency);
        });

        filterCards(categoryFilter);
        updateChart(displayCurrency, categoryFilter);
    };

    const savePreference = (currency) => {
        if (!saveUrl) {
            return;
        }

        const token = document.querySelector('meta[name="csrf-token"]')?.content;

        fetch(saveUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json',
                ...(token ? { 'X-CSRF-TOKEN': token } : {}),
            },
            body: JSON.stringify({ currency }),
        }).catch(() => {});
    };

    const getCategoryFilter = () => categorySelect?.value || '';

    currencySelect?.addEventListener('change', () => {
        const currency = currencySelect.value;

        render(currency, getCategoryFilter());
        savePreference(currency);
    });

    categorySelect?.addEventListener('change', () => {
        render(currencySelect?.value || 'IDR', getCategoryFilter());
    });

    render(currencySelect?.value || 'IDR', getCategoryFilter());
};
