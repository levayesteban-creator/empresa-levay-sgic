<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useForm, Head } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({ categorias: Array });
const editMode = ref(false);
const editId = ref(null);

const form = useForm({
    nombre: '',
});

// Función para enviar (Crear o Editar)
const submit = () => {
    if (editMode.value) {
        form.put(route('categorias.update', editId.value), {
            onSuccess: () => resetForm(),
        });
    } else {
        form.post(route('categorias.store'), {
            onSuccess: () => resetForm(),
        });
    }
};

// Preparar para editar
const editCategoria = (cat) => {
    editMode.value = true;
    editId.value = cat.id;
    form.nombre = cat.nombre;
};

// Eliminar
const deleteCategoria = (id) => {
    if (confirm('¿Estás seguro de eliminar esta categoría?')) {
        form.delete(route('categorias.destroy', id));
    }
};

const resetForm = () => {
    form.reset();
    editMode.value = false;
    editId.value = null;
};
</script>

<template>
    <Head title="Categorías" />
    <AuthenticatedLayout>
        <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-2">Gestión de Categorías - Empresa Levay</h2>

                <form @submit.prevent="submit" class="mb-8 p-4 bg-gray-50 rounded-lg border flex items-end gap-4">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700">Nombre de la Categoría</label>
                        <input v-model="form.nombre" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required />
                    </div>
                    <button type="submit" :disabled="form.processing"
                        :class="editMode ? 'bg-orange-600 hover:bg-orange-700' : 'bg-blue-600 hover:bg-blue-700'"
                        class="text-white px-6 py-2 rounded-md transition duration-200">
                        {{ editMode ? 'Actualizar' : 'Guardar' }}
                    </button>
                    <button v-if="editMode" @click="resetForm" type="button" class="bg-gray-500 text-white px-6 py-2 rounded-md hover:bg-gray-600">
                        Cancelar
                    </button>
                </form>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-800 text-white">
                                <th class="p-3 border">ID</th>
                                <th class="p-3 border">Nombre</th>
                                <th class="p-3 border text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="cat in categorias" :key="cat.id" class="hover:bg-gray-50 transition">
                                <td class="p-3 border text-center font-mono">{{ cat.id }}</td>
                                <td class="p-3 border">{{ cat.nombre }}</td>
                                <td class="p-3 border text-center">
                                    <button @click="editCategoria(cat)" class="text-blue-600 hover:text-blue-900 mx-2 font-medium">Editar</button>
                                    <button @click="deleteCategoria(cat.id)" class="text-red-600 hover:text-red-900 mx-2 font-medium">Eliminar</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
