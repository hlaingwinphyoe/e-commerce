<template>
    <div class="waste_list-container mb-2">
        <template v-for="(waste, index) in wastes" :key="waste.id">
            <slot :waste="waste" :index="index + 1"></slot>
        </template>
    </div>
</template>

<script>
export default {
    props: {
        wastes: { required: true, default: () => [] },
    },
};
</script>
