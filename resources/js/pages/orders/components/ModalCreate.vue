<script setup lang="ts">

import { onMounted, ref } from 'vue'
import { Modal } from 'flowbite';
import { useForm } from '@inertiajs/vue3'
import Loader from '@/components/Loader.vue'
import InputError from '@/components/InputError.vue';


const modalEl = ref(null)
const modalInstance = ref(null)

const openModal = () => {
    modalInstance.value.show();
    init();
}
const closeModal = () => {
    modalInstance.value.hide();
}

const data = ref(null);
const customers = ref([]);
const loading = ref(true);

const emit = defineEmits(['stored']);
const form = useForm({
    customer_id: null,
});
const setCustomer = async(customer_id) => {
    form.customer_id = customer_id;
}

const init = async() => {
    loading.value = true;

    const url = route('orders.create');
    const params = {};
    try {
        const res = await axios.get( url, { params: params });
        data.value = res.data.data;
        customers.value = res.data.customers;
    } catch (error) {
        console.error('Error al obtener los datos:', error);
    }

    loading.value = false;
};


onMounted(() => {

    // options with default values
    const options = {
        backdrop: 'dynamic',
        closable: true,
    };

    // instance options object
    const instanceOptions = {
    };

    modalInstance.value = new Modal( modalEl.value, options, instanceOptions )

    init()
})


function store() {
    form.post( route('orders.store'), {
        onSuccess: (response) => {
            closeModal();
            console.log( response.props.flash.id );
            emit('stored', response.props.flash.id );
            form.reset();
        }
    });
};

defineExpose({
    openModal,
    closeModal
});

</script>

<style>
    .customer-selected {
        background-color: #d0d0d0;
        border-radius: 5px;
    }
</style>

<template>

<form @submit.prevent="store">
    <div ref="modalEl" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-lg max-h-full">
            <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Nueva Orden
                    </h3>
                    <button type="button" @click="closeModal()" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <div class="p-4 md:p-5 space-y-4">
                    <Loader :loading="loading">
                        <div class="row g-6 gap-6">
                            <div class="relative z-0 w-full mb-5 group">
                                <h3 class="text-lg fw-600 mb-4">Selecciona un cliente de la lista</h3>
                                <div v-if="customers.length" class="">
                                    <ul class="max-w-md divide-y divide-gray-200 dark:divide-gray-700">
                                        <li v-for="item in customers" :key="item.id" class="px-2 py-3 sm:py-4 cursor-pointer hover:bg-gray-100" :class="{'customer-selected': form.customer_id==item.id}" @click="setCustomer(item.id)">
                                            <div class="flex items-center space-x-4 rtl:space-x-reverse">
                                                <div class="shrink-0">
                                                    <img class="w-8 h-8 rounded-full" src="/images/customer.png">
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium text-gray-900 truncate dark:text-white">{{ item.name }}</p>
                                                    <p class="text-sm text-gray-500 truncate dark:text-gray-400">{{ item.email }}</p>
                                                </div>
                                                <div v-if="form.customer_id==item.id" class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                                                    <img class="w-8 h-8 rounded-full" src="/images/check.svg">
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <div v-else class="text-center m-6">
                                    No hay clientes registrados
                                </div>
                                <InputError :message="form.errors.customer_id" />
                            </div>
                        </div>

                    </Loader>
                </div>
                <div class="flex items-center justify-end p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                    <button type="button" @click="closeModal()" class="py-2.5 px-5 me-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Cancelar</button>
                    <button type="submit" :disabled="form.customer_id==null" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Continuar</button>
                </div>
            </div>
        </div>
    </div>
</form>

</template>
