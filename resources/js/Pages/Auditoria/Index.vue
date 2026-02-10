<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    auditorias: Array
});

// Función para formatear la fecha de forma legible
const formatDate = (dateString) => {
    const options = { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' };
    return new Date(dateString).toLocaleDateString('es-ES', options);
};
</script>

<template>
    <Head title="Auditoría de Sistema" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Historial de Movimientos (Auditoría)</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold uppercase text-gray-500">Fecha</th>
                                <th class="px-6 py-3 text-left text-xs font-bold uppercase text-gray-500">Acción</th>
                                <th class="px-6 py-3 text-left text-xs font-bold uppercase text-gray-500">Módulo</th>
                                <th class="px-6 py-3 text-left text-xs font-bold uppercase text-gray-500">Detalles</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="log in auditorias" :key="log.id" class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ formatDate(log.created_at) }}
                                </td>
                                <td class="px-6 py-4">
                                    <span :class="{
                                        'bg-green-100 text-green-800': log.accion === 'Creación',
                                        'bg-blue-100 text-blue-800': log.accion === 'Edición',
                                        'bg-red-100 text-red-800': log.accion === 'Eliminación'
                                    }" class="px-2 py-1 rounded text-xs font-bold">
                                        {{ log.accion }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-700">
                                    {{ log.tabla}}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ log.detalles }}
                                </td>
                            </tr>
                            <tr v-if="auditorias.length === 0">
                                <td colspan="4" class="px-6 py-10 text-center text-gray-400 italic">
                                    No hay registros de actividad todavía.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
