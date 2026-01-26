<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({ logs: Object });
</script>

<template>
    <Head title="Auditoría" />
    <AuthenticatedLayout>
        <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg border-t-4 border-gray-800">
                <h2 class="text-2xl font-bold mb-6">Bitácora de Actividades (Auditoría)</h2>
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="p-3 border">Fecha</th>
                            <th class="p-3 border">Usuario</th>
                            <th class="p-3 border">Acción</th>
                            <th class="p-3 border">Módulo</th>
                            <th class="p-3 border">Detalles</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="log in logs.data" :key="log.id" class="hover:bg-gray-50">
                            <td class="p-3 border text-sm">{{ new Date(log.created_at).toLocaleString() }}</td>
                            <td class="p-3 border font-semibold">{{ log.usuario }}</td>
                            <td class="p-3 border">
                                <span class="px-2 py-1 rounded text-xs font-bold uppercase"
                                    :class="{
                                        'bg-green-100 text-green-700': log.accion === 'Creación' || log.accion === 'Crear',
                                        'bg-blue-100 text-blue-700': log.accion === 'Edición' || log.accion === 'Editar',
                                        'bg-red-100 text-red-700': log.accion === 'Eliminación' || log.accion === 'Eliminar'
                                    }">
                                    {{ log.accion }}
                                </span>
                            </td>
                            <td class="p-3 border">{{ log.modulo }}</td>
                            <td class="p-3 border text-sm italic">{{ log.detalles }}</td>
                        </tr>
                    </tbody>
                </table>
                <div v-if="logs.links.length > 3" class="mt-6 flex justify-center">
                    <template v-for="(link, k) in logs.links" :key="k">
                        <div v-if="link.url === null" class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border rounded" v-html="link.label" />
                        <Link v-else class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded hover:bg-white focus:border-indigo-500 focus:text-indigo-500" :class="{ 'bg-blue-700 text-white': link.active }" :href="link.url" v-html="link.label" />
                    </template>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
