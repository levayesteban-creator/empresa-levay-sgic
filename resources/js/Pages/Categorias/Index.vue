<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    categorias: Array
});

const editMode = ref(false);
const editId = ref(null);

const form = useForm({
    nombre: '',
    descripcion: '',
});

const submit = () => {
    if (editMode.value) {
        // CORRECCIÓN: Usamos router.post con _method: 'put' para evitar el error 405
        router.post(`/categorias/${editId.value}`, {
            _method: 'put',
            nombre: form.nombre,
            descripcion: form.descripcion,
        }, {
            onSuccess: () => {
                resetForm();
                alert('¡Categoría actualizada!');
            },
            onError: (err) => console.error(err),
            preserveScroll: true
        });
    } else {
        form.post(route('categorias.store'), {
            onSuccess: () => {
                resetForm();
                alert('¡Categoría creada!');
            },
            onError: (err) => console.error(err)
        });
    }
};

const editCategoria = (cat) => {
    editMode.value = true;
    editId.value = cat.id;
    form.nombre = cat.nombre;
    form.descripcion = cat.descripcion || '';
    window.scrollTo({ top: 0, behavior: 'smooth' });
};

const deleteCategoria = (id) => {
    if (!id) return;

    if (confirm('¿Estás seguro de eliminar esta categoría?')) {
        // CORRECCIÓN: Usamos router.post con _method: 'delete' para mayor compatibilidad
        router.post(`/categorias/${id}`, {
            _method: 'delete',
        }, {
            onSuccess: () => alert('Categoría eliminada con éxito'),
            onError: (err) => console.error(err),
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
    <Head title="Categorías" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Gestión de Categorías</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <div class="bg-white p-6 rounded-lg shadow-sm mb-6 border border-gray-200">
                    <h3 class="text-lg font-bold mb-4 text-gray-700">
                        {{ editMode ? 'Editar Categoría' : 'Nueva Categoría' }}
                    </h3>
                    <form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nombre</label>
                            <input v-model="form.nombre" type="text" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500" required />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Descripción</label>
                            <input v-model="form.descripcion" type="text" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500" />
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" :disabled="form.processing"
                                :class="editMode ? 'bg-orange-600 hover:bg-orange-700' : 'bg-indigo-600 hover:bg-indigo-700'"
                                class="text-white px-4 py-2 rounded-md font-bold w-full transition disabled:opacity-50 h-10 uppercase text-xs">
                                {{ editMode ? 'Actualizar' : 'Crear' }}
                            </button>
                            <button v-if="editMode" @click="resetForm" type="button" class="bg-gray-500 text-white px-3 py-2 rounded-md h-10">X</button>
                        </div>
                    </form>
                </div>

                <div class="bg-white shadow-sm overflow-hidden sm:rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Descripción</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="cat in categorias" :key="cat.id" class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 font-medium text-gray-900">{{ cat.nombre }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ cat.descripcion || 'Sin descripción' }}</td>
                                <td class="px-6 py-4 text-right text-sm font-medium">
                                    <button @click="editCategoria(cat)" class="text-indigo-600 hover:text-indigo-900 font-bold mr-4">Editar</button>
                                    <button @click="deleteCategoria(cat.id)" class="text-red-600 hover:text-red-900 font-bold">Eliminar</button>
                                </td>
                            </tr>
                            <tr v-if="categorias.length === 0">
                                <td colspan="3" class="px-6 py-4 text-center text-gray-500">No hay categorías registradas.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
