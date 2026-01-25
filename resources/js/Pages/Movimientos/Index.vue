<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useForm, Head } from '@inertiajs/vue3';

const props = defineProps({
    movimientos: Array,
    productos: Array
});

const form = useForm({
    id_producto: '',
    tipo: 'Entrada',
    cantidad: 1,
    motivo: ''
});

const submit = () => {
    form.post(route('movimientos.store'), {
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Movimientos de Stock" />
    <AuthenticatedLayout>
        <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg border-t-4 border-indigo-600">
                <h2 class="text-2xl font-bold mb-6 text-gray-800">Gestión de Entradas y Salidas - Empresa Levay</h2>

                <form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8 p-6 bg-indigo-50 rounded-lg border border-indigo-100">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Producto</label>
                        <select v-model="form.id_producto" class="mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
                            <option value="" disabled>Seleccionar...</option>
                            <option v-for="p in productos" :key="p.id" :value="p.id">{{ p.nombre }} (Stock: {{ p.stock }})</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tipo</label>
                        <select v-model="form.tipo" class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                            <option value="Entrada">Entrada (+)</option>
                            <option value="Salida">Salida (-)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Cantidad</label>
                        <input v-model="form.cantidad" type="number" min="1" class="mt-1 w-full border-gray-300 rounded-md shadow-sm" required />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Motivo / Referencia</label>
                        <input v-model="form.motivo" type="text" placeholder="Ej: Compra nueva" class="mt-1 w-full border-gray-300 rounded-md shadow-sm" />
                    </div>
                    <div class="md:col-span-4">
                        <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md font-bold hover:bg-indigo-700 w-full md:w-auto">
                            Registrar Movimiento
                        </button>
                        <div v-if="form.errors.cantidad" class="text-red-600 text-sm mt-2 font-bold">{{ form.errors.cantidad }}</div>
                    </div>
                </form>

                <div class="overflow-x-auto rounded-lg border">
                    <table class="w-full border-collapse">
                        <thead class="bg-gray-800 text-white">
                            <tr>
                                <th class="p-3 text-left">Fecha</th>
                                <th class="p-3 text-left">Producto</th>
                                <th class="p-3 text-center">Tipo</th>
                                <th class="p-3 text-right">Cantidad</th>
                                <th class="p-3 text-left">Motivo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="m in movimientos" :key="m.id" class="border-b hover:bg-gray-50">
                                <td class="p-3 text-sm text-gray-500">{{ new Date(m.created_at).toLocaleString() }}</td>
                                <td class="p-3 font-bold">{{ m.producto.nombre }}</td>
                                <td class="p-3 text-center">
                                    <span :class="m.tipo === 'Entrada' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" class="px-3 py-1 rounded-full text-xs font-bold uppercase">
                                        {{ m.tipo }}
                                    </span>
                                </td>
                                <td class="p-3 text-right font-mono font-bold" :class="m.tipo === 'Entrada' ? 'text-green-600' : 'text-red-600'">
                                    {{ m.tipo === 'Entrada' ? '+' : '-' }}{{ m.cantidad }}
                                </td>
                                <td class="p-3 text-sm text-gray-600">{{ m.motivo || 'Sin descripción' }}</td>
                            </tr>
                            <tr v-if="movimientos.length === 0">
                                <td colspan="5" class="p-8 text-center text-gray-400 italic">No hay movimientos registrados.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>