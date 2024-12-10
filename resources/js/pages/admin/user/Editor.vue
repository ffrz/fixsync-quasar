<script setup>
import { default_submit_handler } from "@/helpers/client-req-handler";
import { validateUsername, validateEmail } from "@/helpers/validations";
import { router, useForm, usePage } from "@inertiajs/vue3";

const page = usePage();

const roles = Object.entries(window.CONSTANTS.USER_ROLES)
  .map(([key, value]) => ({ 'value': key, 'label': value }));

const form = useForm({
  id: page.props.data.id,
  name: page.props.data.name,
  username: page.props.data.username,
  email: page.props.data.email,
  password: "",
  role: !!page.props.data.role ? page.props.data.role : roles[0].value,
  active: !!page.props.data.active,
});

const title = !!form.id ? 'Edit Pengguna' : 'Tambah Pengguna';

const submit = () => {
  default_submit_handler(form, route('admin.user.save'));
}

</script>

<template>
  <authenticated-layout>
    <q-page class="row justify-center">
      <i-head :title="title" />
      <div class="col col-lg-6 q-pa-md">
        <q-form class="row" @submit.prevent="submit">
          <q-card square flat bordered class="col q-pa-sm">
            <q-card-section>
              <div class="text-h6">
                {{ title }}
              </div>
            </q-card-section>
            <q-card-section class="q-pt-none">
              <input type="hidden" name="id" v-model="form.id" />
              <q-input autofocus v-model.trim="form.name" label="Nama" lazy-rules :error="!!form.errors.name"
                :disable="form.processing" :error-message="form.errors.name" :rules="[
                  (val) => (val && val.length > 0) || 'Nama harus diisi.',
                ]" />
              <q-input v-model.trim="form.username" type="text" label="ID Pengguna" lazy-rules
                :disable="form.processing" :error="!!form.errors.username" :error-message="form.errors.username" :rules="[
                  (val) => (val && val.length > 0) || 'ID Pengguna harus diisi.',
                  (val) => validateUsername(val) || 'ID Pengguna tidak valid.'
                ]" />
              <q-input v-model.trim="form.email" type="email" label="Email" lazy-rules :disable="form.processing"
                :error="!!form.errors.email" :error-message="form.errors.email"
                :rules="[(val) => validateEmail(val) || 'Email harus valid.']" />
              <q-input v-model="form.password" type="password" label="Kata Sandi" lazy-rules :disable="form.processing"
                :error="!!form.errors.password" :error-message="form.errors.password" />
              <q-select v-model="form.role" label="Hak Akses" :options="roles" map-options emit-value lazy-rules
                :disable="form.processing" transition-show="jump-up" transition-hide="jump-up"
                :error="!!form.errors.role" :error-message="form.errors.role">
              </q-select>
              <q-checkbox class="full-width q-pl-none" v-model="form.active" :disable="form.processing" label="Aktif" />
            </q-card-section>
            <q-card-actions>
              <q-btn type="submit" label="Simpan" color="primary" icon="check" :disable="form.processing" />
              <q-btn label="Batal" v-close-popup color="grey-7" icon="close" :disable="form.processing"
                @click="router.get(route('admin.user.index'))" />
            </q-card-actions>
          </q-card>
        </q-form>
      </div>
    </q-page>
  </authenticated-layout>
</template>
