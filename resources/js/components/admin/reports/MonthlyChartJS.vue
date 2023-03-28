<template>
    <div class="py-4">
        <h5 class="page-title text-primary">Sales Chart</h5>
        <div class="filter-container">
            <select v-model="selectedMonth" class="form-select form-select-sm">
                <option v-for="month in month_period" :key="month" :value="month">{{ month }}</option>
            </select>
            <button
                class="btn btn-sm btn-outline-primary filter-btn"
                @click.prevent="onChangeMonth"
            >
                Apply
            </button>
        </div>

        <div v-if="!has_datasets" class="py-4 text-danger">
            There is no sales in this month.
        </div>

        <div class="mb-2 py-2">
            <div class="d-flex flex-wrap mb-2">
                <div
                    class="me-2 mb-2 text-center px-4 py-1 bg-info text-white rounded"
                >
                    <p class="mb-1">ကျသင့်ငွေ</p>
                    <span class="fw-bold"
                        >{{
                            new Intl.NumberFormat().format(
                                sales_data.net_total - sales_data.deli_fee
                            )
                        }}
                        Ks</span
                    >
                </div>
                <div
                    class="me-2 mb-2 text-center px-4 py-1 bg-secondary text-white rounded"
                >
                    <p class="mb-1">Deli</p>
                    <span class="fw-bold"
                        >{{
                            new Intl.NumberFormat().format(sales_data.deli_fee)
                        }}
                        Ks</span
                    >
                </div>
                <div
                    class="me-2 mb-2 text-center px-4 py-1 bg-success text-white rounded"
                >
                    <p class="mb-1">လက်ခံရရှိငွေ</p>
                    <span class="fw-bold"
                        >{{
                            new Intl.NumberFormat().format(sales_data.received)
                        }}
                        Ks</span
                    >
                </div>
                <div
                    class="me-2 mb-2 text-center px-4 py-1 bg-danger text-white rounded"
                >
                    <p class="mb-1">ကျန်ငွေ</p>
                    <span class="fw-bold"
                        >{{
                            new Intl.NumberFormat().format(sales_data.balance)
                        }}
                        Ks</span
                    >
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <canvas id="salesChart" width="100%" height="300px"></canvas>
            </div>
        </div>

        <div class="pt-4">
            <h5 class="page-title text-primary">Sales By Category</h5>

            <div class="row">
                <div class="col-12">
                    <canvas
                        id="categoryChart"
                        width="100%"
                        height="300px"
                    ></canvas>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Chart from "chart.js/auto";
import moment from "moment";
export default {
    data() {
        return {
            month_period: [],
            sales_chart: "",
            category_chart: "",
            selectedMonth: "",
            has_datasets: false,
            configs: {
                monthLabels: [
                    "Jan",
                    "Feb",
                    "March",
                    "Apr",
                    "May",
                    "Jun",
                    "Jul",
                    "Aug",
                    "Sep",
                    "Oct",
                    "Nov",
                    "Dec"
                ]
            },
            sales_data: {
                balance: 0,
                deli_fee: 0,
                net_total: 0,
                received: 0,
                total: 0
            }
        };
    },
    mounted() {
        //create sales chart
        var ele = document.getElementById("salesChart").getContext("2d");
        this.sales_chart = new Chart(ele, {
            type: "line",
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        suggestedMin: -10,
                        suggestedMax: 500
                    },
                    x: {
                        title: {
                            text: "Date",
                            display: true,
                            color: "rgba(0,6,112,1)"
                        }
                    }
                }
            }
        });

        //create category chart
        var category = document.getElementById("categoryChart").getContext("2d");
        this.category_chart = new Chart(category, {
            type: "bar",
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        suggestedMin: -10,
                        suggestedMax: 500
                    },
                }
            }
        });

        this.newFunc();
        this.calculateReport();
    },
    created() {
        axios.get(`/wapi/month-periods`).then(resp => {
            this.month_period = resp.data.months;
            this.selectedMonth = resp.data.current;
        });
    },
    methods: {
        newFunc() {
            axios.get(`/wapi/sales`, { params: {month: this.selectedMonth} }).then(resp => {
                console.log(resp.data);
                // console.log(this.sales_chart);
                let data = {
                    labels: resp.data.sales.labels,
                    datasets: [
                        {
                            label: "Sale",
                            data: resp.data.sales.datasets,
                            borderColor: "rgba(168, 168, 193, 1)",
                            backgroundColor: "rgba(240,240, 247, 1)",
                            tension: 0.1,
                            borderWidth: 1
                        }
                    ]
                };
                this.sales_chart.data = data;
                this.sales_chart.update('active');
            })
        },
        onChangeMonth() {
            // this.updateChart();
            this.calculateReport();
        },
        calculateReport() {
            // let month = moment(this.selectedMonth._i, "MMM YYYY").format(
            //     "DD-MM-YYYY"
            // );
            // console.log(this.selectedMonth);
            let params = {
                month: this.selectedMonth ? this.selectedMonth : ""
            };
            axios.get(`/wapi/sales-data`, { params: params }).then(resp => {
                this.sales_data = resp.data;
            });
        },
        updateChart() {
            // let month = moment(this.selectedMonth._i, "YYYY/MM").format(
            //     "DD-MM-YYYY"
            // );
            let params = {
                month: this.selectedMonth ? this.selectedMonth : ""
            };
            axios.get(`/wapi/sales`, { params: params }).then(resp => {
                console.log(resp.data);
                this.has_datasets = resp.data.sales.labels.length
                    ? true
                    : false;

                //sales chart
                let data = {
                    labels: resp.data.sales.labels,
                    datasets: [
                        {
                            label: "Sale",
                            data: resp.data.sales.datasets,
                            borderColor: "rgba(168, 168, 193, 1)",
                            backgroundColor: "rgba(240,240, 247, 1)",
                            tension: 0.1,
                            borderWidth: 1
                        }
                    ]
                };
                this.sales_chart.data = data;
                this.sales_chart.update();

                //category chart
                // let category_data = {
                //     labels: resp.data.category.labels,
                //     datasets: [
                //         {
                //             label: "Total Qty By Category",
                //             data: resp.data.category.datasets,
                //             borderColor: "rgba(168, 168, 193, 1)",
                //             backgroundColor: "rgba(240,240, 247, 1)",
                //             tension: 0.1,
                //             borderWidth: 1
                //         }
                //     ]
                // };
                // this.category_chart.data = category_data;
                // this.category_chart.update();
            });
        }
    }
};
</script>

<style>
.input {
    min-width: 300px;
    max-width: 350px !important;
}
.filter-container {
    display: flex;
    align-items: center;
}

.filter-btn {
    margin-left: 10px;
}

@media (max-width: 480px) {
    .filter-container {
        flex-direction: column;
        align-items: flex-start;
    }
    .filter-btn {
        margin-top: 10px;
        margin-left: 0px;
    }
}
</style>
