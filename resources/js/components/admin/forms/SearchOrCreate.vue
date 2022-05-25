<template>
    <div class="search-or-create position-relative">
        <input type="hidden" :name="name" v-model="input_name">
        <input type="text" class="form-control form-control-sm" placeholder="Search" v-model="q" @keyup="onKeySearch" @change="onChangeKeyWord">
        <div class="results absolute-box bg-white shadow rounded mt-1" v-if="results.length">
            <ul class="nav flex-column">
                <li class="nav-item" v-for="result in results" :key="result.id">
                    <a href="#" class="nav-link py-1" @click.prevent="onChooseResult(result)"><small>{{ result.name }}</small></a>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        url : {required: true},
        name: {required: true}
    },
    data() {
        return {
            q: '',
            timeout: '',
            results: [],
            input_name: ''
        }
    },
    methods: {
        onKeySearch() {
            if(this.q && this.q !== ' ') {
                clearTimeout(this.timeout);
                this.timeout = setTimeout(() => {
                    axios.get(`/wapi/${this.url}`, {params: {q : this.q}}).then(resp => {
                        this.results = resp.data ? resp.data : [];
                    });
                }, 100);
            }
        },
        onChooseResult(data) {
            this.input_name = data.id;
            this.results = [];
            this.q = data.name;
        },
        onChangeKeyWord() {
            if(this.q && this.q !== ' ') {
                axios.post(`/wapi/${this.url}/create`, {q : this.q}).then(resp => {
                    this.input_name = resp.data ? resp.data.id : '';
                });
            }
        }
    }
}
</script>