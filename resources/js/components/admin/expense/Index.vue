<template>
    <div class="expense-container">
        <div class="row">
            <div class="col-md-4">
                <!-- date -->
                <div class="border rounded shadow px-2 py-4 mb-3">
                    <div class="form-group mb-3">
                        <label for="">Date</label>
                        <input type="date" v-model="form.date" class="form-control" @change="onChangeDate">
                    </div>

                    <span v-if="out_of_date" class="text-danger">Out of Date</span>
                </div>

                <!-- Form -->
                <div class="border rounded shadow px-2 py-4 mb-3">
                    <h5 class="text-primary fw-bold">Add Name</h5>

                    <div class="form-group mb-3">
                        <label for="">Type</label>
                        <select v-model="form.type_id" class="form-select">
                            <option value="">Select Type</option>
                            <option v-for="data_type in types" :key="data_type.id"  :value="data_type.id">{{ data_type.name }}</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="">Desc</label>
                        <input type="text" v-model="form.name" class="form-control">
                    </div>

                    <div class="form-group mb-3">
                        <label for="">Supplier/Shop</label>
                        <select class="form-select" v-model="form.supplier_id">
                            <option value="">Select Suplier/Shop</option>
                            <option v-for="supplier in suppliers" :key="supplier.id" :value="supplier.id">{{ supplier.name }}</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="">Amount</label>
                        <input type="text" v-model="form.amount" class="form-control">
                    </div>

                    <div class="form-group mb-3">
                        <label for="">Reference Code (Optional)</label>
                        <input type="text" v-model="form.reference_id" class="form-control">
                    </div>

                    <span v-if="out_of_date" class="text-danger">Out of Date</span>

                    <div class="form-group mb-3">
                        <button type="button" class="btn btn-sm btn-secondary" @click.prevent="onAddExpense">Add</button>
                    </div>
                </div>
            </div>

            <!-- List -->
            <div class="col-md-8" v-if="data_expenses.length">
                <div class="border rounded shadow px-2 py-4 mb-3">
                    <h5 class="text-primary fw-bold">Expenses</h5>
                    <h6>Date : {{ form.date }}</h6>
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <thead class="">
                                <tr>
                                    <th>Sr.</th>
                                    <th style="width: 250px;">Name</th>
                                    <th>Type</th>
                                    <th>Supplier</th>
                                    <th>Rf Code</th>
                                    <th>Amount</th>
                                    <th><i class="fas fa-ellipsis-vertical"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                <expense-list v-for="(data_expense, index) in data_expenses" :key="data_expense.id" :index="index" :expense="data_expense" @on-update="onUpdate"></expense-list>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import moment from 'moment';
import ExpenseList from './List.vue';
export default {
    components: {
        'expense-list' : ExpenseList
    },
    props: {
        types: {required: true, default: () => []},
        suppliers: {required: true, default: () => []}
    },
    data() {
        return {
            data_expenses: [],
            start: moment().startOf('month').format('YYYY-MM-DD'),
            out_of_date: false,
            form: {
                date: moment().format('YYYY-MM-DD'),
                name: '',
                type_id: '',
                amount: '',
                reference_id: '',
                supplier_id: '',
            }
        }
    },
    created() {
        axios.get(`/wapi/expenses`).then(resp => this.data_expenses = resp.data);
    },
    methods: {
        showAlert() {
            toastr.options.closeButton = true;
            toastr.success("Successfully Created");
        },
        onAddExpense() {
            if(this.form.date < this.start) {
                this.out_of_date = true;
            }else {
                this.out_of_date = false;
                axios.post(`/wapi/expenses`, this.form).then(resp => {
                    this.data_expenses = resp.data;
                    this.showAlert();
                    // this.data_expenses.push(resp.data);
                });
            }
        },
        onChangeDate() {
            if(this.form.date < this.start) {
                this.out_of_date = true;
            }else {
                this.out_of_date = false;
                axios.get(`/wapi/expenses`, {params: {date: this.form.date}}).then(resp => this.data_expenses = resp.data);
            }
        },
        onUpdate(data) {
            this.data_expenses = data;
        },
    }
}
</script>
