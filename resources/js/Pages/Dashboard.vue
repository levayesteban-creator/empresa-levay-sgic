<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    stats: Object
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Panel de Control - Empresa Levay</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                        <div class="text-gray-500 text-sm font-medium uppercase">Total Productos</div>
                        <div class="text-3xl font-bold">{{ stats?.total_productos || 0 }}</div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                        <div class="text-gray-500 text-sm font-medium uppercase">Proveedores</div>
                        <div class="text-3xl font-bold">{{ stats?.total_proveedores || 0 }}</div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-red-500">
                        <div class="text-gray-500 text-sm font-medium uppercase">Stock Bajo</div>
                        <div class="text-3xl font-bold text-red-600">{{ stats?.stock_bajo || 0 }}</div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold mb-4">Últimos Movimientos de Inventario</h3>
                    <div v-if="stats?.ultimos_movimientos.length > 0">
                        <p class="text-gray-600">Hay movimientos registrados.</p>
                    </div>
                    <div v-else class="text-gray-400 italic">
                        No hay movimientos recientes que mostrar.
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
