document.addEventListener('DOMContentLoaded', function () {
    // Dark mode variables untuk Chart.js
    const isDark = document.documentElement.classList.contains('dark');
    const gridColor = isDark ? '#1f2937' : '#f3f4f6';
    const textColor = isDark ? '#9ca3af' : '#6b7280';

    const canvas = document.getElementById('salesChart');
    if (canvas) {
        const labels = JSON.parse(canvas.dataset.labels);
        const values = JSON.parse(canvas.dataset.values);

        new Chart(canvas, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Total Penjualan (Rp)',
                    data: values,
                    borderWidth: 3,
                    borderColor: '#2563eb',
                    backgroundColor: function(context) {
                        const chart = context.chart;
                        const {ctx, chartArea} = chart;
                        if (!chartArea) return 'rgba(37, 99, 235, 0.04)';
                        const gradient = ctx.createLinearGradient(0, chartArea.top, 0, chartArea.bottom);
                        gradient.addColorStop(0, 'rgba(37, 99, 235, 0.08)');
                        gradient.addColorStop(1, 'rgba(37, 99, 235, 0)');
                        return gradient;
                    },
                    fill: true,
                    tension: 0.35,
                    pointBackgroundColor: '#2563eb',
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        grid: { color: 'transparent' },
                        ticks: { color: textColor, font: { size: 11 } }
                    },
                    y: {
                        grid: { color: gridColor },
                        ticks: { color: textColor, font: { size: 11 } }
                    }
                }
            }
        });
    }

    // Animasi angka Counter Umum
    document.querySelectorAll('.counter').forEach(counter => {
        const target = parseInt(counter.dataset.target) || 0;
        let count = 0;
        const step = Math.ceil(target / 40) || 1;

        const interval = setInterval(() => {
            count += step;
            if (count >= target) {
                count = target;
                clearInterval(interval);
            }
            counter.innerText = count.toLocaleString('id-ID');
        }, 20);
    });

    // Animasi angka Counter Mata Uang Rupiah
    document.querySelectorAll('.counter-rupiah').forEach(counter => {
        const target = parseInt(counter.dataset.target) || 0;
        let count = 0;
        const step = Math.ceil(target / 40) || 1;

        const interval = setInterval(() => {
            count += step;
            if (count >= target) {
                count = target;
                clearInterval(interval);
            }
            counter.innerText = 'Rp ' + count.toLocaleString('id-ID');
        }, 20);
    });
});
