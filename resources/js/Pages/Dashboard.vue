<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
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
                        <div class="text-3xl font-bold">{{ stats.total_productos }}</div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                        <div class="text-gray-500 text-sm font-medium uppercase">Proveedores</div>
                        <div class="text-3xl font-bold">{{ stats.total_proveedores }}</div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-red-500">
                        <div class="text-gray-500 text-sm font-medium uppercase">Stock Crítico (< 5)</div>
                        <div class="text-3xl font-bold text-red-600">{{ stats.stock_bajo }}</div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold mb-4">Últimos Movimientos de Inventario</h3>
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-600">
                                <th class="p-3 border-b">Producto</th>
                                <th class="p-3 border-b">Tipo</th>
                                <th class="p-3 border-b">Cantidad</th>
                                <th class="p-3 border-b">Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="mov in stats.ultimos_movimientos" :key="mov.id" class="hover:bg-gray-50">
                                <td class="p-3 border-b">{{ mov.producto.nombre }}</td>
                                <td class="p-3 border-b">
                                    <span :class="mov.tipo === 'entrada' ? 'text-green-600' : 'text-red-600'" class="font-bold uppercase text-xs">
                                        {{ mov.tipo }}
                                    </span>
                                </td>
                                <td class="p-3 border-b">{{ mov.cantidad }}</td>
                                <td class="p-3 border-b text-sm text-gray-500">
                                    {{ new Date(mov.created_at).toLocaleDateString() }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
