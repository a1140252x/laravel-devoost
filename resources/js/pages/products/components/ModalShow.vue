<script setup lang="ts">

import { onMounted, ref } from 'vue'
import { Drawer } from 'flowbite';
import Loader from '@/components/Loader.vue'

const modalEl = ref(null)
const modalInstance = ref(null)

const data = ref(null);
const loading = ref(true);

const init = async( id ) => {
    loading.value = true;

    const url = route('products.show', id);
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

defineExpose({
    openModal,
    closeModal
});

</script>

<template>

    <div ref="modalEl" class="fixed top-0 right-0 z-40 h-screen w-[480px] max-w-full p-4 overflow-y-auto transition-transform translate-x-full bg-white dark:bg-gray-800" tabindex="-1" aria-labelledby="drawer-right-label">
        <h5 id="drawer-right-label" class="inline-flex items-center mb-4 text-base font-semibold text-gray-500 dark:text-gray-400">
            REGISTRO DE PRODUCTO
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
                            <td class="px-6 py-3">SKU</td>
                            <td class="px-6 py-3">{{ data.sku }}</td>
                        </tr>
                        <tr class="bg-white border-b">
                            <td class="px-6 py-3">Nombre</td>
                            <td class="px-6 py-3">{{ data.name }}</td>
                        </tr>
                        <tr class="bg-gray-50 border-b">
                            <td class="px-6 py-3">Precio</td>
                            <td class="px-6 py-3">{{ data.price }}</td>
                        </tr>
                        <tr class="bg-white border-b">
                            <td class="px-6 py-3">Descripción</td>
                            <td class="px-6 py-3">{{ data.description }}</td>
                        </tr>
                    </tbody>
                </table>
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
