<template>
    <div>
        <nav v-if="props.type == 'pagination' && props.links.length > 3">
            <ul class="flex pagination justify-content-center">
                <template v-for="(link, key) in props.links">
                    <li v-if="link.url === null" :key="key" class="py-2 page-item disabled">
                        <a class="px-3 py-1 cursor-pointer page-link" v-html="link.label" disabled></a>
                    </li>
                    <li v-else class="py-2 page-item" :class="{ 'active': link.active }">
                        <a :key="key" class="px-3 py-1 cursor-pointer page-link"
                            :class="{ 'text-primary': link.active }"
                            v-html="link.label"
                            @click="$emit('update:ModelValue', link.url)"
                        ></a>
                    </li>
                </template>
            </ul>
        </nav>
        <div v-else-if="props.type === 'load-more' && props.nextPageUrl" class="flex items-center justify-center mt-4">
            <button @click="$emit('update:ModelValue', props.nextPageUrl)" class="px-4 py-2 text-white bg-indigo-500 rounded-full">
                Load more
            </button>
        </div>
    </div>
</template>
<script setup>

const props = defineProps({
    links: {
        type: Array,
        default: () => [],
    },
    type: {
        type: String,
        default: 'pagination',
    },
    nextPageUrl: {
        type: String,
        default: '',
    },
});
</script>
 
<style scoped>
.pagination {
  font-weight: 400;
  font-size: clamp(14px, 2vw, 16px);
}

.pagination .disabled a {
  background: #ddd !important;
}

.pagination a.page-link {
  background-color: #fff;
  border-radius: 3px !important;
  margin: 0px 5px;
  color: darkgray;
}

.pagination .page-item.active .page-link,
.pagination .page-link:hover {
  z-index: 3;
  color: #fff !important;
  background: skyblue;
  border-color: indigo;
}
</style>