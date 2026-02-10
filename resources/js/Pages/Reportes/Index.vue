<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';

const props = defineProps({
    valorTotalUsd: Number,
    conteoCategorias: Array,
    productos: Array
});

const tasaDolar = ref(null);

const consultarTasa = async () => {
    try {
        const response = await axios.get('https://api.exchangerate-api.com/v4/latest/USD');
        tasaDolar.value = response.data.rates.VES;
    } catch (error) {
        console.error("Error al obtener tasa");
    }
};

onMounted(() => {
    consultarTasa();
});

// Calculamos el valor total en Bolívares dinámicamente
const valorTotalBs = computed(() => {
    return tasaDolar.value ? (props.valorTotalUsd * tasaDolar.value) : 0;
});
</script>

<template>
    <Head title="Reportes de Inventario" />
    <AuthenticatedLayout>
        <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                <h2 class="text-3xl font-extrabold text-gray-900">Reportes Financieros - Empresa Levay</h2>

                <div class="flex justify-end w-full md:w-auto">
                    <a :href="route('reportes.pdf')"
                       class="bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-lg font-bold shadow-md transition-all flex items-center gap-2 text-sm uppercase tracking-wider">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Descargar Reporte PDF
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-white p-6 rounded-xl shadow-md border-l-8 border-blue-600">
                    <p class="text-sm text-gray-500 uppercase font-bold">Valor Total Inventario (USD)</p>
                    <p class="text-4xl font-mono font-bold text-blue-700 mt-2">
                        ${{ props.valorTotalUsd.toLocaleString('en-US', { minimumFractionDigits: 2 }) }}
                    </p>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-md border-l-8 border-green-600">
                    <p class="text-sm text-gray-500 uppercase font-bold">Valor Estimado en Bolívares</p>
                    <div v-if="tasaDolar">
                        <p class="text-4xl font-mono font-bold text-green-700 mt-2">
                            Bs. {{ valorTotalBs.toLocaleString('es-VE', { minimumFractionDigits: 2 }) }}
                        </p>
                        <p class="text-xs text-gray-400 mt-1 italic">Calculado a tasa: {{ tasaDolar }} Bs/USD</p>
                    </div>
                    <p v-else class="text-gray-400 mt-2 animate-pulse font-medium text-lg">Consultando tasa...</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-1 bg-white p-6 rounded-xl shadow-sm border">
                    <h3 class="font-bold text-lg mb-4 border-b pb-2 text-gray-800">Distribución por Categoría</h3>
                    <ul class="space-y-4">
                        <li v-for="cat in conteoCategorias" :key="cat.id" class="flex justify-between items-center hover:bg-gray-50 p-1 rounded transition-colors">
                            <span class="text-gray-700 font-medium">{{ cat.nombre }}</span>
                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-black">
                                {{ cat.productos_count }} ÍTEMS
                            </span>
                        </li>
                    </ul>
                </div>

                <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-sm border text-center flex flex-col justify-center min-h-[250px]">
                    <h3 class="text-gray-500 font-semibold uppercase tracking-widest text-sm mb-6">Estado General de Almacén</h3>
                    <div class="flex justify-around items-center">
                        <div>
                            <p class="text-6xl font-black text-gray-800">{{ productos.length }}</p>
                            <p class="text-sm text-gray-500 mt-2 font-medium">Variedad de Productos</p>
                        </div>

                        <div class="h-20 w-px bg-gray-200"></div>

                        <div>
                            <p class="text-6xl font-black text-orange-500">
                                {{ productos.filter(p => p.stock < 5).length }}
                            </p>
                            <p class="text-sm text-orange-600 mt-2 font-bold uppercase">Stock Crítico</p>
                        </div>
                    </div>
                    <div class="mt-8">
                        <p class="text-xs text-gray-400">Este reporte se actualiza en tiempo real con cada movimiento de inventario.</p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
