<template>
    <div class="cost_list-container mb-2">
        <template v-for="(cost, index) in costs">
            <slot :cost="cost" :index="index + 1"></slot>
        </template>
    </div>
</template>

<script>
export default {
    props: {
        costs: { required: true, default: () => [] },
    },
};
</script>
