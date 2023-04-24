<template>
    <div class="search-or-create position-relative">
        <input type="hidden" :name="name" v-model="input_id">
        <!-- <div class="input-group input-group-sm mb-3">
            <input type="text" class="form-control" placeholder="Search or Create" v-model="q" @keyup="onKeySearch">
            <button class="btn btn-primary" @click.prevent="onChangeKeyWord"><i class="fa-solid fa-plus"></i></button>
        </div> -->
        <input type="text" class="form-control form-control-sm" placeholder="Search or Create" v-model="q" @keyup="onKeySearch" @change="onChangeKeyWord">
        <div class="results absolute-box bg-white shadow rounded mt-1" v-if="results.length">
            <ul class="nav flex-column">
                <li class="nav-item" v-for="result in results" :key="result.id">
                    <a href="#" class="nav-link py-1" @click.prevent="onChooseResult(result)"><small>{{ url=='orders' ? result.order_no : result.name }}</small></a>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
import throttle from 'lodash';
export default {
    props: {
        url : {required: true},
        name: {required: true},
        input_obj: {required: true, default: () => ''}
    },
    data() {
        return {
            q: this.input_obj ? this.input_obj.name : '',
            timeout: '',
            results: [],
            input_id: this.input_obj ? this.input_obj.id : ''
        }
    },
    methods: {
        onKeySearch:throttle(function () {
            if(this.q && this.q !== ' ') {
                clearTimeout(this.timeout);
                this.timeout = setTimeout(() => {
                    axios.get(`/wapi/${this.url}`, {params: {q : this.q}}).then(resp => {
                        this.results = resp.data ? resp.data : [];
                    });
                }, 100);
            }
        },500),
        onChooseResult(data) {
            this.input_id = data.id;
            this.results = [];
            this.q = this.url == 'orders' ? data.order_no : data.name;
        },
        onChangeKeyWord:throttle(function () {
            if(this.q && this.q !== ' ') {
                axios.post(`/wapi/${this.url}/create`, {q : this.q}).then(resp => {
                    //console.log(resp.data);
                    this.input_id = resp.data ? resp.data.id : '';
                });
            }
        },500)
    }
}
</script>
