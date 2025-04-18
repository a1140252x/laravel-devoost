<script setup lang="ts">

import { onMounted, ref } from 'vue'
import { Drawer } from 'flowbite';
import Loader from '@/components/Loader.vue'
import numeral from 'numeral'

import ModalAddItem from './ModalAddItem.vue'
import ModalRemoveItem from './ModalRemoveItem.vue'

const modalAddItem = ref(null)
const modalRemoveItem = ref(null)

const modalEl = ref(null)
const modalInstance = ref(null)

const data = ref(null);
const loading = ref(true);

const emit = defineEmits(['updated']);
const init = async( id ) => {
    loading.value = true;

    const url = route('orders.show', id);
    const params = {};
    try {
        const res = await axios.get( url, { params: params });
        data.value = res.data.data;
    } catch (error) {
        console.error('Error al obtener los datos:', error);
    }

    loading.value = false;

};


const openModal = ( id ) => {
    init( id )
    modalInstance.value.show();
}
const closeModal = () => {
    modalInstance.value.hide();
}

onMounted(() => {

    // options with default values
    const options = {
        placement: 'right',
        backdrop: true,
        bodyScrolling: false,
        edge: false,
        edgeOffset: '',
    };

    // instance options object
    const instanceOptions = {
    };

    modalInstance.value = new Drawer( modalEl.value, options, instanceOptions )
})

const openModalAddItem = () => {
    modalAddItem.value.openModal( data.value.id );
}
const openModalRemoveItem = (item_id) => {
    modalRemoveItem.value.openModal( data.value.id, item_id );
}


const handledUpdated = (id) => {
    init( data.value.id );
    emit('updated');
}

defineExpose({
    openModal,
    closeModal
});

</script>

<template>

    <ModalAddItem ref="modalAddItem" @stored="handledUpdated"/>
    <ModalRemoveItem ref="modalRemoveItem" @deleted="handledUpdated"/>

    <div ref="modalEl" class="fixed top-0 right-0 z-40 h-screen w-[600px] max-w-full p-4 overflow-y-auto transition-transform translate-x-full bg-white dark:bg-gray-800" tabindex="-1" aria-labelledby="drawer-right-label">
        <h5 id="drawer-right-label" class="inline-flex items-center mb-4 text-base font-semibold text-gray-500 dark:text-gray-400">
            REGISTRO DE ORDEN
        </h5>
        <button type="button" @click="closeModal()" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 absolute top-2.5 end-2.5 inline-flex items-center justify-center dark:hover:bg-gray-600 dark:hover:text-white" >
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
            <span class="sr-only">Close menu</span>
        </button>

        <Loader :loading="loading">
            <div>
                <table class="w-full text-sm text-left text-gray-500 border border-gray-200">
                    <tbody>
                        <tr class="bg-white border-b">
                            <td class="px-6 py-3">ID</td>
                            <td class="px-6 py-3">{{ data.id }}</td>
                        </tr>
                        <tr class="bg-gray-50 border-b">
                            <td class="px-6 py-3">Cliente</td>
                            <td class="px-6 py-3">{{ data.customer?.name }}</td>
                        </tr>
                        <tr class="bg-white border-b">
                            <td class="px-6 py-3">Cant. de Productos</td>
                            <td class="px-6 py-3">{{ data.items?.length }}</td>
                        </tr>
                        <tr class="bg-gray-50 border-b">
                            <td class="px-6 py-3">Precio Total</td>
                            <td class="px-6 py-3">{{ data.total_price }}</td>
                        </tr>
                    </tbody>
                </table>

                <div class="my-6">
                    <h3 class="">
                        PRODUCTOS DE LA ORDEN
                    </h3>

                    <table v-if="data.items.length>0" class="w-full text-sm text-left text-gray-500 border border-gray-200">
                        <thead>
                            <tr>
                                <th class="px-2 py-1">Producto</th>
                                <th class="text-end px-2 py-1">Precio</th>
                                <th class="text-end px-2 py-1">Cant.</th>
                                <th class="text-end px-2 py-1">Sub Total</th>
                                <th class="text-end px-2 py-1">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in data.items" :key="item.id">
                                <td class="px-2 py-1">{{ item.product?.name }}</td>
                                <td class="text-end px-2 py-1">{{ numeral(item.price).format('0,0') }}</td>
                                <td class="text-end px-2 py-1">{{ item.quantity }}</td>
                                <td class="text-end px-2 py-1">{{ numeral(item.total).format('0,0') }}</td>
                                <td class="text-end px-2 py-1">
                                    <button v-if="data.status=='active'" @click="openModalRemoveItem(item.id)" type="button" class="text-gray-900 bg-white hover:bg-gray-100 border border-gray-200 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-lg text-sm px-3 py-1 text-center inline-flex items-center dark:focus:ring-gray-600 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700 me-2 mb-2">
                                        <img src="/images/trash-alt.svg" class="w-4 h-4 me-1"/>
                                        Quitar
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div v-else class="my-4 text-center">
                        No hay productos en la venta
                    </div>

                </div>
                <div class="my-2 flex justify-center">
                    <a v-if="data.status=='active'" @click="openModalAddItem()" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        Agregar Producto
                        <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                        </svg>
                    </a>
                </div>

            </div>
        </Loader>

        <!--div class="grid grid-cols-2 gap-4 mt-8">
            <a href="#" class="px-4 py-2 text-sm font-medium text-center text-gray-900 bg-white border border-gray-200 rounded-lg focus:outline-none hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                Editar
            </a>
            <a href="#" class="px-4 py-2 text-sm font-medium text-center text-red-900 bg-white border border-gray-200 rounded-lg focus:outline-none hover:bg-gray-100 hover:text-red-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-red-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                Borrar
            </a>
        </div-->

    </div>

</template>
