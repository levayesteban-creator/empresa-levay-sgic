<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useForm, Head } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({ proveedores: Array });
const editMode = ref(false);
const editId = ref(null);

const form = useForm({
    nombre: '',
    contacto_persona: '',
    telefono: '',
    email: '',
    direccion: ''
});

const submit = () => {
    if (editMode.value) {
        form.put(route('proveedores.update', editId.value), {
            onSuccess: () => resetForm()
        });
    } else {
        form.post(route('proveedores.store'), {
            onSuccess: () => resetForm()
        });
    }
};

const editProveedor = (p) => {
    editMode.value = true;
    editId.value = p.id;
    form.nombre = p.nombre;
    form.contacto_persona = p.contacto_persona;
    form.telefono = p.telefono;
    form.email = p.email;
    form.direccion = p.direccion;
};

const deleteProveedor = (id) => {
    if (confirm('¿Eliminar este proveedor de Empresa Levay?')) {
        form.delete(route('proveedores.destroy', id));
    }
};

const resetForm = () => {
    form.reset();
    editMode.value = false;
    editId.value = null;
};
</script>

<template>
    <Head title="Proveedores" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Proveedores - Empresa Levay</h2>
        </template>

        <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg border-t-4 border-green-500">
                <h2 class="text-2xl font-bold mb-6 text-gray-800">Directorio de Proveedores</h2>

                <form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8 p-6 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-sm font-medium text-gray-700">Nombre Empresa*</label>
                        <input v-model="form.nombre" type="text" class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500" required />
                        <div v-if="form.errors.nombre" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.nombre }}</div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Persona de Contacto</label>
                        <input v-model="form.contacto_persona" type="text" class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500" />
                        <div v-if="form.errors.contacto_persona" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.contacto_persona }}</div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Teléfono</label>
                        <input v-model="form.telefono" type="text" class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500" />
                        <div v-if="form.errors.telefono" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.telefono }}</div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input v-model="form.email" type="email" class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500" />
                        <div v-if="form.errors.email" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.email }}</div>
                    </div>

                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Dirección</label>
                        <textarea v-model="form.direccion" class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500" rows="2"></textarea>
                        <div v-if="form.errors.direccion" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.direccion }}</div>
                    </div>

                    <div class="col-span-2 flex gap-3 mt-2">
                        <button
                            type="submit"
                            :style="{ backgroundColor: editMode ? '#ea580c' : '#16a34a', color: 'white' }"
                            class="px-8 py-2 rounded-md font-bold shadow hover:opacity-90 transition-all uppercase text-sm"
                        >
                            {{ editMode ? 'Actualizar Proveedor' : 'Registrar Proveedor' }}
                        </button>

                        <button v-if="editMode" @click="resetForm" type="button" class="bg-gray-500 text-white px-6 py-2 rounded-md hover:bg-gray-600 transition-all uppercase text-sm font-bold">
                            Cancelar
                        </button>
                    </div>
                </form>

                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="w-full border-collapse shadow-sm">
                        <thead class="bg-gray-800 text-white text-sm">
                            <tr>
                                <th class="p-4 text-left">Empresa</th>
                                <th class="p-4 text-left">Contacto</th>
                                <th class="p-4 text-left">Teléfono</th>
                                <th class="p-4 text-left">Email</th>
                                <th class="p-4 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            <tr v-for="p in proveedores" :key="p.id" class="hover:bg-gray-50 transition-colors text-sm">
                                <td class="p-4 font-semibold text-gray-900">{{ p.nombre }}</td>
                                <td class="p-4 text-gray-700">{{ p.contacto_persona || '---' }}</td>
                                <td class="p-4 text-gray-700">{{ p.telefono || '---' }}</td>
                                <td class="p-4 text-gray-700 font-mono text-xs">{{ p.email || '---' }}</td>
                                <td class="p-4 text-center">
                                    <button @click="editProveedor(p)" class="text-indigo-600 font-bold hover:text-indigo-900 mx-2">Editar</button>
                                    <button @click="deleteProveedor(p.id)" class="text-red-600 font-bold hover:text-red-900 mx-2">Eliminar</button>
                                </td>
                            </tr>
                            <tr v-if="proveedores.length === 0">
                                <td colspan="5" class="p-8 text-center text-gray-500 italic">No hay proveedores registrados aún.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>