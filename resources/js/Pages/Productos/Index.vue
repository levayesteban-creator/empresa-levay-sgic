<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useForm, Head } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    productos: Array,
    categorias: Array,
    proveedores: Array
});

const editMode = ref(false);
const editId = ref(null);

const form = useForm({
    nombre: '',
    descripcion: '',
    precio: '',
    stock: 0,
    id_categoria: '',
    id_proveedor: ''
});

const submit = () => {
    if (editMode.value) {
        form.put(route('productos.update', editId.value), { onSuccess: () => resetForm() });
    } else {
        form.post(route('productos.store'), { onSuccess: () => resetForm() });
    }
};

const editProducto = (p) => {
    editMode.value = true;
    editId.value = p.id;
    form.nombre = p.nombre;
    form.descripcion = p.descripcion;
    form.precio = p.precio;
    form.stock = p.stock;
    form.id_categoria = p.id_categoria;
    form.id_proveedor = p.id_proveedor;
};

const deleteProducto = (id) => {
    if (confirm('¿Eliminar este producto de Empresa Levay?')) {
        form.delete(route('productos.destroy', id));
    }
};

const resetForm = () => {
    form.reset();
    editMode.value = false;
    editId.value = null;
};
</script>

<template>
    <Head title="Inventario" />
    <AuthenticatedLayout>
        <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg border-t-4 border-blue-600">
                <h2 class="text-2xl font-bold mb-6 text-gray-800">Inventario General - Empresa Levay</h2>

                <form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8 p-4 bg-gray-50 rounded-lg border">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium">Nombre del Producto</label>
                        <input v-model="form.nombre" type="text" class="w-full border-gray-300 rounded-md shadow-sm" required />
                        <div v-if="form.errors.nombre" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.nombre }}</div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Precio ($)</label>
                        <input v-model="form.precio" type="number" step="0.01" class="w-full border-gray-300 rounded-md shadow-sm" required />
                        <div v-if="form.errors.precio" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.precio }}</div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Stock Inicial</label>
                        <input v-model="form.stock" type="number" class="w-full border-gray-300 rounded-md shadow-sm" required />
                        <div v-if="form.errors.stock" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.stock }}</div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Categoría</label>
                        <select v-model="form.id_categoria" class="w-full border-gray-300 rounded-md shadow-sm" required>
                            <option value="" disabled>Seleccione...</option>
                            <option v-for="c in categorias" :key="c.id" :value="c.id">{{ c.nombre }}</option>
                        </select>
                        <div v-if="form.errors.id_categoria" class="text-red-500 text-xs mt-1 font-semibold text-wrap">La categoría es obligatoria</div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Proveedor</label>
                        <select v-model="form.id_proveedor" class="w-full border-gray-300 rounded-md shadow-sm" required>
                            <option value="" disabled>Seleccione...</option>
                            <option v-for="p in proveedores" :key="p.id" :value="p.id">{{ p.nombre }}</option>
                        </select>
                        <div v-if="form.errors.id_proveedor" class="text-red-500 text-xs mt-1 font-semibold">El proveedor es obligatorio</div>
                    </div>

                    <div class="md:col-span-3 flex gap-2 pt-2">
                        <button type="submit" 
                            :style="{ backgroundColor: editMode ? '#ea580c' : '#2563eb' }"
                            class="text-white px-6 py-2 rounded-md hover:opacity-90 font-bold shadow-sm transition-all">
                            {{ editMode ? 'Actualizar Producto' : 'Añadir al Inventario' }}
                        </button>
                        <button v-if="editMode" @click="resetForm" type="button" class="bg-gray-500 text-white px-6 py-2 rounded-md">
                            Cancelar
                        </button>
                    </div>
                </form>

                <div class="overflow-x-auto shadow-sm border rounded-lg">
                    <table class="w-full border-collapse">
                        <thead class="bg-gray-800 text-white">
                            <tr>
                                <th class="p-3 text-left">Producto</th>
                                <th class="p-3 text-left">Categoría</th>
                                <th class="p-3 text-left">Proveedor</th>
                                <th class="p-3 text-right">Precio</th>
                                <th class="p-3 text-center">Stock</th>
                                <th class="p-3 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="prod in productos" :key="prod.id" class="border-b hover:bg-gray-50 transition-colors">
                                <td class="p-3 font-bold text-blue-700">{{ prod.nombre }}</td>
                                <td class="p-3">
                                    <span class="bg-gray-200 px-2 py-1 rounded text-xs font-medium text-gray-700">
                                        {{ prod.categoria?.nombre || 'N/A' }}
                                    </span>
                                </td>
                                <td class="p-3 text-sm text-gray-600">{{ prod.proveedor?.nombre || 'N/A' }}</td>
                                <td class="p-3 text-right font-mono">${{ Number(prod.precio).toFixed(2) }}</td>
                                <td class="p-3 text-center">
                                    <span :class="prod.stock < 5 ? 'bg-red-100 text-red-600 px-2 py-1 rounded-full font-bold' : 'text-green-600'">
                                        {{ prod.stock }}
                                    </span>
                                </td>
                                <td class="p-3 text-center">
                                    <button @click="editProducto(prod)" class="text-blue-600 hover:text-blue-800 font-medium mx-1">Editar</button>
                                    <button @click="deleteProducto(prod.id)" class="text-red-600 hover:text-red-800 font-medium mx-1">Eliminar</button>
                                </td>
                            </tr>
                            <tr v-if="productos.length === 0">
                                <td colspan="6" class="p-8 text-center text-gray-500 italic">No hay productos en el inventario.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>