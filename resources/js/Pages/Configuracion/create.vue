<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import SideBar from '@/Components/SideBar.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { ArrowLeftIcon } from "@heroicons/vue/outline";

const form = useForm({
    nrofactura: '',
    timbrado: '',
    ruc: '',
});

const validarNumeroFactura = () => {
  // Expresión regular para validar el formato xxx-xxx
  const regex = /^[0-9]{3}-[0-9]{3}$/;

  if (!regex.test(form.nrofactura)) {
    //
  }else{
    //
  }
};

const submit = () => {
    form.post(route('configuracion.store'));
};

</script>

<template>
    <Head title="Establecer Configuracion" />

    <SideBar />
    <AuthenticatedLayout>

        <template #header>
            <h2 class="flex uppercase font-bold text-xl text-gray-800 leading-tight">Establecer Configuracion</h2>
        </template>

        <div class="py-12">

            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
                <div class="-mt-9 mb-2">
                    <Link class="w-8 h-8 bg-black/20 hover:bg-black/30 rounded-md grid place-content-center" as="button"
                        :href="route('configuracion')">
                    <ArrowLeftIcon class="w-5 h-5" />
                    </Link>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                    <div class="px-10 pb-10 pt-5 overflow-y-auto">

                        <form @submit.prevent="submit">
                            <div>
                                <InputLabel for="nrofactura" value="Prefijo Factura" class="text-gray-600" />

                                <TextInput placeholder="Ingrese con este formato: xxx-xxx" @input="validarNumeroFactura" id="nrofactura" type="text" class="mt-1 block w-full bg-gray-200 text-gray-600"
                                    v-model="form.nrofactura" required autofocus autocomplete="off" />

                                <InputError class="mt-2" :message="form.errors.nrofactura" />
                            </div>


                            <div class="mt-4">
                                <InputLabel for="timbrado" value="Timbrado" class="text-gray-600" />

                                <TextInput id="timbrado" type="number" class="mt-1 block w-full bg-gray-200 text-gray-600"
                                    v-model="form.timbrado" required autocomplete="off" />

                                <InputError class="mt-2" :message="form.errors.timbrado" />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="ruc" value="RUC" class="text-gray-600" />

                                <TextInput id="ruc" type="text" class="mt-1 block w-full bg-gray-200 text-gray-600"
                                    v-model="form.ruc" required autocomplete="off" />

                                <InputError class="mt-2" :message="form.errors.ruc" />
                            </div>

                            <div class="flex items-center justify-end mt-6">

                                <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }"
                                    :disabled="form.processing">
                                    Guardar
                                </PrimaryButton>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
