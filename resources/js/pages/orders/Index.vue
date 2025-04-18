<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import numeral from 'numeral';

import { onMounted, onUpdated, ref } from 'vue'
import { initFlowbite } from 'flowbite'

import ModalShow from './components/ModalShow.vue'
import ModalCreate from './components/ModalCreate.vue'
import ModalCancel from './components/ModalCancel.vue'

const modalShow = ref(null)
const modalCreate = ref(null)
const modalCanceled = ref(null)

// initialize components based on data attribute selectors
onMounted(() => {
    loadData()
    initFlowbite();
})

onUpdated(() => {
    initFlowbite();
})
const data = ref(null);
const loading = ref(true);

const loadData = async() => {
    loading.value = true;

    const url = route('data.orders');
    const params = {};
    try {
        const res = await axios.get( url, { params: params });
        data.value = res.data.data;
        initFlowbite();
    } catch (error) {
        console.error('Error al obtener los datos:', error);
    }

    loading.value = false;
};


const handleStored = (id) => {
    openShowModal(id);
    loadData();
}

const handleUpdated = () => {
    loadData();
}

const handleCancelled = () => {
    loadData();
}


const openShowModal = (id) => {
    modalShow.value.openModal( id )
}
const openEditModal = (id) => {
    modalEdit.value.openModal( id )
}
const openDeleteModal = (id) => {
    modalCanceled.value.openModal( id )
}


</script>

<template>

    <AppLayout>

        <ModalShow ref="modalShow" @updated="handleUpdated"/>
        <ModalCreate ref="modalCreate" @stored="handleStored"/>
        <ModalCancel ref="modalCanceled" @cancelled="handleCancelled"/>

        <div class="flex justify-between p-6">
            <div class="">
                <h1 class="text-xl">
                    Ordenes
                </h1>
            </div>
            <div class="">
                <button @click="modalCreate.openModal()" type="button" class="mx-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Nueva orden
                </button>
            </div>
        </div>

        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="p-4">
                            ID
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Cliente
                        </th>
                        <th scope="col" class="text-end px-6 py-3">
                            Precio Total
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Estado
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Acción
                        </th>
                    </tr>
                </thead>
                <tbody>

                    <tr v-for="item in data" :key="item.id" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="w-4 p-4">
                            <div class="flex items-center">
                                {{ item.id }}
                            </div>
                        </td>
                        <th scope="row" class="flex items-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                            <img class="w-10 h-10 rounded-full" src="/images/cart-shopping-shop.svg" alt="Jese image">
                            <div class="ps-3">
                                <div class="text-base font-semibold">{{ item.customer?.name }}</div>
                                <div class="font-normal text-gray-500">{{ "Productos en la orden: " + item.items_count }}</div>
                            </div>
                        </th>
                        <td class="px-6 py-4 text-end">
                            {{ numeral(item.total_price).format('0,0') }}
                        </td>
                        <td class="px-6 py-4">
                            <div v-if="item.status=='active'" class="flex items-center">
                                <div class="h-2.5 w-2.5 rounded-full bg-green-500 me-2"></div> Activa
                            </div>
                            <div v-else-if="item.status=='cancelled'" class="flex items-center">
                                <div class="h-2.5 w-2.5 rounded-full bg-red-500 me-2"></div> Cancelada
                            </div>
                            <div v-else class="flex items-center">
                                <div class="h-2.5 w-2.5 rounded-full me-2"></div> {{ item.status }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div>
                                <button @click="openShowModal(item.id)" type="button" class="text-gray-900 bg-white hover:bg-gray-100 border border-gray-200 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-gray-600 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700 me-2 mb-2">
                                    <img src="/images/open-external.svg" class="w-4 h-4 me-1"/>
                                    Ver
                                </button>

                                <button v-if="item.status=='active'" @click="openDeleteModal(item.id)" type="button" class="text-gray-900 bg-white hover:bg-gray-100 border border-gray-200 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-gray-600 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700 me-2 mb-2">
                                    <img src="/images/trash-alt.svg" class="w-4 h-4 me-1"/>
                                    Cancelar
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </AppLayout>

</template>
