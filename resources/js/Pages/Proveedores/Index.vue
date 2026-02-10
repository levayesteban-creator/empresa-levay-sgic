<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    proveedores: Array,
});

const editMode = ref(false);
const editId = ref(null);

const form = useForm({
    nombre: '',
    contacto_persona: '',
    telefono: '',
    email: '',
    direccion: '',
});

const submit = () => {
    if (editMode.value) {
        // CORRECCIÓN DEFINITIVA:
        // 1. Usamos router.post para que el servidor no bloquee la petición.
        // 2. Enviamos '_method: put' para que Laravel sepa que es una actualización.
        // 3. Incluimos el ID en la URL.
        router.post(`/proveedores/${editId.value}`, {
            _method: 'put',
            nombre: form.nombre,
            contacto_persona: form.contacto_persona,
            telefono: form.telefono,
            email: form.email,
            direccion: form.direccion,
        }, {
            onSuccess: () => {
                resetForm();
                alert('Proveedor actualizado con éxito');
            },
            onError: (err) => {
                console.error("Error al actualizar:", err);
                alert('Error al actualizar. Revisa la consola.');
            },
            preserveScroll: true
        });
    } else {
        // Crear proveedor nuevo (POST normal)
        form.post(route('proveedores.store'), {
            onSuccess: () => {
                resetForm();
                alert('Proveedor registrado con éxito');
            },
            onError: (err) => console.log(err)
        });
    }
};

const editProveedor = (prov) => {
    editMode.value = true;
    editId.value = prov.id;
    form.nombre = prov.nombre;
    form.contacto_persona = prov.contacto_persona || '';
    form.telefono = prov.telefono || '';
    form.email = prov.email || '';
    form.direccion = prov.direccion || '';
    window.scrollTo({ top: 0, behavior: 'smooth' });
};

const deleteProveedor = (id) => {
    if (confirm('¿Estás seguro de eliminar este proveedor?')) {
        // También usamos spoofing para eliminar por seguridad
        router.post(`/proveedores/${id}`, {
            _method: 'delete'
        }, {
            onSuccess: () => alert('Eliminado correctamente'),
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
    <Head title="Proveedores" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Gestión de Proveedores</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <div class="bg-white p-6 rounded-lg shadow-sm mb-8 border border-gray-200">
                    <h3 class="text-lg font-bold mb-4 text-gray-700">
                        {{ editMode ? 'Editar Proveedor' : 'Nuevo Proveedor' }}
                    </h3>
                    <form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nombre / Empresa</label>
                            <input v-model="form.nombre" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500" required />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Persona de Contacto</label>
                            <input v-model="form.contacto_persona" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <input v-model="form.email" type="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500" required />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Teléfono</label>
                            <input v-model="form.telefono" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500" />
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Dirección</label>
                            <input v-model="form.direccion" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500" />
                        </div>

                        <div class="flex gap-2 items-end">
                            <button type="submit" :disabled="form.processing"
                                :class="editMode ? 'bg-orange-600 hover:bg-orange-700' : 'bg-indigo-600 hover:bg-indigo-700'"
                                class="text-white px-4 py-2 rounded-md disabled:opacity-50 h-10 w-full transition font-bold uppercase text-xs">
                                {{ editMode ? 'Guardar Cambios' : 'Registrar Proveedor' }}
                            </button>
                            <button v-if="editMode" @click="resetForm" type="button" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 h-10 transition text-xs uppercase font-bold">
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Proveedor</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Contacto</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Email / Tel.</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="prov in proveedores" :key="prov.id" class="hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium text-gray-900">{{ prov.nombre }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ prov.contacto_persona || 'N/A' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ prov.email }} <br>
                                    <span class="text-xs text-gray-400">{{ prov.telefono || 'Sin tel.' }}</span>
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-medium">
                                    <button @click="editProveedor(prov)" class="text-indigo-600 hover:text-indigo-900 font-bold mr-4">Editar</button>
                                    <button @click="deleteProveedor(prov.id)" class="text-red-600 hover:text-red-900 font-bold">Eliminar</button>
                                </td>
                            </tr>
                            <tr v-if="proveedores.length === 0">
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500 italic">No hay proveedores registrados.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
