<script setup lang="ts">
import { ref } from 'vue';
import Checkbox from "@/Components/Checkbox.vue";
import { useForm } from '@inertiajs/vue3';

const props = defineProps<{
    items?: {
        id: number;
        name: string;
        contents: string;
        price: number;
        created_at: string;
    }[];
}>();

const checkedBox = ref<number[]>([])

const allChecked = ref(false)

const checkAll = () => {
    allChecked.value = !allChecked.value
    if (allChecked.value) {
        checkedBox.value = props.items?.map(item => item.id) || []
    } else {
        checkedBox.value = []
    }
}

const deleteItems = () => {
    if(confirm('삭제하시겠습니까?')) {
        useForm({items: checkedBox.value}).delete('/product')
    }
}

</script>

<template>
    <div class="overflow-x-auto">
        <div class="flex justify-end">
            <button class="btn btn-error" @click="deleteItems">삭제</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th><Checkbox @click="checkAll" :checked="allChecked"></Checkbox></th>
                    <th>상품명</th>
                    <th>설명</th>
                    <th>가격</th>
                    <th>날짜</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="i in props.items" :key="i.id">
                    <td>
                        <Checkbox
                            :checked="checkedBox.includes(i.id)"
                            @update:checked="(val) => val ? checkedBox.push(i.id) : checkedBox = checkedBox.filter(id => id !== i.id)"
                        />
                    </td>
                    <td>{{ i.name }}</td>
                    <td>{{ i.contents }}</td>
                    <td>{{ i.price }}</td>
                    <td>{{ i.created_at }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<style scoped>

</style>
