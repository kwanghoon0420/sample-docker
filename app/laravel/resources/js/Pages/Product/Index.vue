<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Table from "@/Components/Table.vue";

defineProps<{
    products?: object
}>();

const form = useForm({
  name: '',
  contents: '',
  price: 0
});

const createProduct = () => {
    form.post('/product', {
        onSuccess: () => {
            // 폼 제출 성공 후 모달 닫기
            const modal = document.getElementById('my_modal_1') as HTMLDialogElement;
            modal?.close();
            form.reset();
        }
    });
}

</script>

<template>
    <Head title="Product" />
    <AuthenticatedLayout>
        <div class="max-w-4xl mx-auto">
            <div class="flex justify-end">
                <button class="btn" onclick="my_modal_1.showModal()">생성</button>
            </div>
            <dialog id="my_modal_1" class="modal">
                <div class="modal-box">
                    <h3 class="text-lg font-bold">Hello!</h3>
                    <div class="grid gap-4 w-xs mt-10 mb-10 justify-center">
                        <label class="input">
                            상품
                            <input type="text" class="grow" v-model="form.name" placeholder="상품명" />
                        </label>
                        <label class="input">
                            설명
                            <input type="text" class="grow" v-model="form.contents" placeholder="설명" />
                        </label>
                        <label class="input">
                            가격
                            <input type="number" class="grow" v-model="form.price" placeholder="" />
                            <span class="badge badge-xs"></span>
                        </label>
                        <button class="btn btn-primary" @click="createProduct">저장</button>
                        <button class="btn" onclick="my_modal_1.close()">취소</button>
                    </div>
                </div>
            </dialog>
            <Table :items="products" />
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>
