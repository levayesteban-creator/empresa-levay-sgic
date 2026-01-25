<template>
    <AuthenticatedLayout>
        <div class="py-12 px-4 max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-blue-500 text-white p-6 rounded-lg shadow-lg">
                <h3 class="text-lg font-bold">Productos</h3>
                <p class="text-4xl font-black">15</p> </div>
            <div class="bg-green-500 text-white p-6 rounded-lg shadow-lg">
                <h3 class="text-lg font-bold">Proveedores</h3>
                <p class="text-4xl font-black">5</p>
            </div>
            <div class="bg-red-500 text-white p-6 rounded-lg shadow-lg">
                <h3 class="text-lg font-bold">Stock Crítico</h3>
                <p class="text-4xl font-black">2</p>
            </div>
        </div>
    </AuthenticatedLayout>
</template>