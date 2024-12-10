<script setup>
import { router, useForm, usePage } from "@inertiajs/vue3";
import { default_submit_handler } from "@/helpers/client-req-handler";

const page = usePage();

const form = useForm({
  id: page.props.data.id,
  name: page.props.data.name,
  phone: page.props.data.phone,
  address: page.props.data.address,
});

const submit = () => {
  default_submit_handler(form, route('admin.customer.save'));
};

</script>

<template>
  <authenticated-layout>
    <q-page class="row">
      <i-head title="Tambah Pelanggan" />
      <div class="col col-lg-6 q-pa-md">
        <q-form class="row" @submit.prevent="submit">
          <q-card square flat bordered class="col q-pa-sm">
            <q-card-section>
              <div class="text-h6">
                <template v-if="!!form.id">Edit Pelanggan </template>
                <template v-else> Tambah Pelanggan </template>
              </div>
            </q-card-section>
            <q-card-section class="q-pt-none">
              <input type="hidden" name="id" v-model="form.id" />
              <q-input autofocus v-model.trim="form.name" label="Nama" lazy-rules :error="!!form.errors.name"
                :disable="form.processing" :error-message="form.errors.name" :rules="[
                  (val) => (val && val.length > 0) || 'Nama harus diisi.',
                ]" />
              <q-input v-model.trim="form.phone" type="text" label="No HP" lazy-rules :disable="form.processing"
                :error="!!form.errors.phone" :error-message="form.errors.phone" :rules="[
                  (val) => (val && val.length > 0) || 'No HP harus diisi.',
                ]" />
              <q-input v-model.trim="form.address" type="text" label="Alamat" lazy-rules :disable="form.processing"
                :error="!!form.errors.address" :error-message="form.errors.address" :rules="[
                  (val) => (val && val.length > 0) || 'Alamat harus diisi.',
                ]" />
            </q-card-section>
            <q-card-actions>
              <q-btn type="submit" label="Simpan" color="primary" icon="check" :disable="form.processing" />
              <q-btn label="Batal" v-close-popup color="grey-7" icon="close" :disable="form.processing"
                @click="router.get(route('admin.customer.index'))" />
            </q-card-actions>
          </q-card>
        </q-form>
      </div>
    </q-page>
  </authenticated-layout>
</template>
