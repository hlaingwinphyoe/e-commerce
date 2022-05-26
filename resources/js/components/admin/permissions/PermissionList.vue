<template>
    <div class="pb-2 mb-3 border-bottom" v-show=isShow>
        <div class="form-check">
            <input
                type="checkbox"
                class="form-check-input"
                :id="type"
                @click="onToggle($event)"
            />
            <label
                :for="type"
                class="form-check-label text-capitalize text-primary"
                >{{ type }}</label
            >
        </div>
        <div
            :id="type"
            class="form-check form-check-inline"
            v-for="permis in permissions"
            :key="permis.id"
        >
            <div v-if="!permis.disabled">
                <input
                    type="checkbox"
                    class="form-check-input"
                    :value="permis.id"
                    :id="`permis-${permis.id}`"
                    name="permis[]"
                />
                <label :for="`permis-${permis.id}`" class="form-check-label"
                    >{{ permis.name }}</label
                >
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        type: { required: true },
        permissions: { required: true, default: () => [] },
        selected_permissions: { required: true, default: () => [] }
    },
    mounted() {
        this.permissions.map(permis => {
            this.selected_permissions.some(id => {
                if (id == permis.id) {
                    $(`#permis-${permis.id}`).prop("checked", true);
                }
            });
        });
    },
    data() {
        return {
            //
        };
    },
    methods: {
        onToggle(event) {
            if (event.target.checked) {
                $(`#${this.type}>div>input[type='checkbox']`).prop("checked", true);
                //check-all
            } else {
                //uncheck-all
                $(`#${this.type}>div>input[type='checkbox']`).prop(
                    "checked",
                    false
                );
            }
        }
    },
    computed: {
        isShow() {
            let boo = false;
            this.permissions.map(x => {
                boo = !x.disabled ? true : boo;
            });
            return boo;
        }
    }
};
</script>
