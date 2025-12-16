/**
 * Chart Utilities
 * Helper functions for Chart.js integration
 */

window.ChartUtils = {
    /**
     * Create line chart
     * @param {string} canvasId - Canvas element ID
     * @param {object} config - Chart configuration
     * @returns {Chart} - Chart instance
     */
    createLineChart(canvasId, config) {
        const ctx = document.getElementById(canvasId);
        if (!ctx) return null;

        return new Chart(ctx, {
            type: 'line',
            data: config.data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: config.legendPosition || 'top',
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: config.beginAtZero !== false
                    }
                },
                ...config.options
            }
        });
    },

    /**
     * Create bar chart
     * @param {string} canvasId - Canvas element ID
     * @param {object} config - Chart configuration
     * @returns {Chart} - Chart instance
     */
    createBarChart(canvasId, config) {
        const ctx = document.getElementById(canvasId);
        if (!ctx) return null;

        return new Chart(ctx, {
            type: 'bar',
            data: config.data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: config.horizontal ? 'y' : 'x',
                plugins: {
                    legend: {
                        position: config.legendPosition || 'top',
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: config.beginAtZero !== false
                    }
                },
                ...config.options
            }
        });
    },

    /**
     * Create pie chart
     * @param {string} canvasId - Canvas element ID
     * @param {object} config - Chart configuration
     * @returns {Chart} - Chart instance
     */
    createPieChart(canvasId, config) {
        const ctx = document.getElementById(canvasId);
        if (!ctx) return null;

        return new Chart(ctx, {
            type: 'pie',
            data: config.data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: config.legendPosition || 'right',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((context.parsed / total) * 100).toFixed(1);
                                label += context.parsed + ' (' + percentage + '%)';
                                return label;
                            }
                        }
                    }
                },
                ...config.options
            }
        });
    },

    /**
     * Update chart data
     * @param {Chart} chart - Chart instance
     * @param {object} newData - New data
     */
    updateChart(chart, newData) {
        if (!chart) return;
        chart.data = newData;
        chart.update();
    },

    /**
     * Destroy chart
     * @param {Chart} chart - Chart instance
     */
    destroyChart(chart) {
        if (chart) {
            chart.destroy();
        }
    }
};

