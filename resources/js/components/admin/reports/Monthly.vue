<template>
    <div class="py-4">
        <h5 class="page-title text-primary">Monthly Sales Report</h5>
        <div class="d-flex mb-4">
            <select v-model="selectedMonth" class="form-select form-select-sm w-auto">
                <option v-for="month in month_period" :key="month" :value="month">{{ month }}</option>
            </select>
            <button
                class="btn btn-sm btn-outline-primary ms-2"
                @click.prevent="onChangeMonth"
            >
                Apply
            </button>
        </div>

        <div v-if="!has_datasets" class="py-4 text-danger">
            There is no sales in this month.
        </div>

        <div class="row">
            <div class="col-12 mb-3">
                <span class="fw-bold text-white px-3 py-2 rounded d-inline-block h5 bg-secondary">Sales Total - {{ total }}</span>
            </div>
            <div class="col col-md-6">
                <div class="px-2 py-4 shadow">
                    <h5 class="text-primary">Sales Chart</h5>
                    <Bar id="sales-chart" :options="sales.options" :data="sales.data" />
                </div>
            </div>

            <div class="col col-md-6">
                <div class="px-2 py-4 shadow">
                    <h5 class="text-primary">Sales By Category</h5>
                    <Bar id="cates-chart" :options="cates.options" :data="cates.data" />
                </div>
            </div>
        </div>

    </div>
</template>

<script>
import { Bar } from 'vue-chartjs';
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
} from 'chart.js';
ChartJS.register(
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale);
import Chart from "chart.js/auto";
export default {
    components: {
        Bar,
    },
    data() {
        return {
            month_period: [],
            selectedMonth: "",
            has_datasets: false,
            total : 0,
            sales: {
                options: {
                    responsive: true,
                },
                data: {
                    labels: [],
                    datasets: []
                },
            },
            cates: {
                options: {
                    responsive: true,
                },
                data: {
                    labels: [],
                    datasets: [],
                }
            }
        };
    },
    created() {
        axios.get(`/wapi/month-periods`).then(resp => {
            this.month_period = resp.data.months;
            this.selectedMonth = resp.data.current;
            this.updateChart();
        });
    },
    methods: {
        updateChart() {
            axios.get(`/wapi/sales`, { params: {month: this.selectedMonth} }).then(resp => {
                this.has_datasets = resp.data.sales.labels.length;
                this.total = resp.data.sales.total;
                this.sales.data = {
                    'labels' : resp.data.sales.labels,
                    'datasets' : [{
                        'label' : 'Total Sales - ' + resp.data.sales.total,
                        'backgroundColor': '#820000',
                        'data' : resp.data.sales.datasets,
                    }]
                };
                this.cates.data = {
                    'labels' : resp.data.category.labels,
                    'datasets' : [{
                        'label' : 'Total Sales',
                        'backgroundColor': '#212529',
                        'data' : resp.data.category.datasets,
                        'barPercentage': .4
                    }]
                };
            })
        },
        onChangeMonth() {
            this.updateChart();
        }
    }
};
</script>

