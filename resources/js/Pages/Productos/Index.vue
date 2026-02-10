<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    productos: Array,
    categorias: Array,
    proveedores: Array,
    flash: Object
});

const editMode = ref(false);
const editId = ref(null);

const form = useForm({
    nombre: '',
    precio: '',
    stock: '',
    id_categoria: '',
    id_proveedor: '',
});

const submit = () => {
    if (editMode.value) {
        // Spoofing para PUT
        router.post(`/productos/${editId.value}`, {
            _method: 'put',
            nombre: form.nombre,
            precio: form.precio,
            stock: form.stock,
            id_categoria: form.id_categoria,
            id_proveedor: form.id_proveedor,
        }, {
            onSuccess: () => {
                resetForm();
                alert('¡Producto actualizado con éxito!');
            },
            onError: (err) => console.error(err),
            preserveScroll: true
        });
    } else {
        form.post(route('productos.store'), {
            onSuccess: () => {
                resetForm();
                alert('¡Producto registrado con éxito!');
            },
            onError: (err) => console.error(err)
        });
    }
};

const editProducto = (p) => {
    editMode.value = true;
    editId.value = p.id;
    form.nombre = p.nombre;
    form.precio = p.precio;
    form.stock = p.stock;
    form.id_categoria = p.id_categoria;
    form.id_proveedor = p.id_proveedor;
    window.scrollTo({ top: 0, behavior: 'smooth' });
};

// CORRECCIÓN DEFINITIVA: Spoofing de DELETE mediante POST
const deleteProducto = (id) => {
    if (!id) return;

    if (confirm('¿Estás seguro de eliminar este producto?')) {
        // Enviamos como POST pero le decimos a Laravel que lo trate como DELETE
        router.post(`/productos/${id}`, {
            _method: 'delete',
        }, {
            onSuccess: () => alert('Producto eliminado con éxito'),
            onError: (err) => {
                console.error("Error al eliminar:", err);
                alert('No se pudo eliminar. Verifique la consola.');
            },
            preserveScroll: true
        });
    }
};

const resetForm = () => {
    form.reset();
    form.clearErrors();
    editMode.value = false;
    editId.value = null;
};
</script>

<template>
    <Head title="Gestión de Productos" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Gestión de Inventario</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <div class="bg-white p-6 rounded-lg shadow-sm mb-8 border border-gray-200">
                    <h3 class="text-lg font-bold mb-4 text-gray-700">
                        {{ editMode ? 'Editar Producto' : 'Registrar Nuevo Producto' }}
                    </h3>
                    <form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 items-end">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nombre</label>
                            <input v-model="form.nombre" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500" required />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Precio ($)</label>
                            <input v-model="form.precio" type="number" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500" required />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Stock</label>
                            <input v-model="form.stock" type="number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500" required />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Categoría</label>
                            <select v-model="form.id_categoria" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                <option value="" disabled>Seleccionar...</option>
                                <option v-for="cat in categorias" :key="cat.id" :value="cat.id">{{ cat.nombre }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Proveedor</label>
                            <select v-model="form.id_proveedor" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                <option value="" disabled>Seleccionar...</option>
                                <option v-for="prov in proveedores" :key="prov.id" :value="prov.id">{{ prov.nombre }}</option>
                            </select>
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" :disabled="form.processing"
                                :class="editMode ? 'bg-orange-600 hover:bg-orange-700' : 'bg-indigo-600 hover:bg-indigo-700'"
                                class="text-white px-4 py-2 rounded-md font-bold w-full transition disabled:opacity-50 h-10 uppercase text-xs">
                                {{ editMode ? 'Actualizar' : 'Añadir' }}
                            </button>
                            <button v-if="editMode" @click="resetForm" type="button" class="bg-gray-500 text-white px-3 py-2 rounded-md h-10">X</button>
                        </div>
                    </form>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Producto</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Precio/Stock</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="producto in productos" :key="producto.id" class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 font-medium text-gray-900">{{ producto.nombre }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">${{ producto.precio }} | {{ producto.stock }} uds</td>
                                <td class="px-6 py-4 text-right text-sm font-medium">
                                    <button @click="editProducto(producto)" class="text-indigo-600 hover:text-indigo-900 font-bold mr-4">Editar</button>
                                    <button @click="deleteProducto(producto.id)" class="text-red-600 hover:text-red-900 font-bold">Eliminar</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
