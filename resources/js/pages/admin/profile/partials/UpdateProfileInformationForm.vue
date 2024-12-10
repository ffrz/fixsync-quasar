<script setup>

import { useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

const nameInputRef = ref();
const page = usePage();
const user = page.props.auth.user;
const form = useForm({
  name: user.name,
  username: user.username,
  role: window.CONSTANTS.USER_ROLES[user.role],
  email: user.email,
});

const submit = () => {
  form.clearErrors();
  form.post(route('admin.profile.update'), {
    preserveScroll: true,
    onError: () => {
      nameInputRef.value.focus();
    }
  });
};

</script>

<template>
  <q-form class="row" @submit.prevent="submit">
    <q-card square flat bordered class="col q-pa-sm">
      <q-card-section>
        <h2 class="text-h6 q-my-xs">Informasi Profil</h2>
        <p>Perbarui profil anda.</p>
        <q-input readonly v-model="form.username" label="ID Pengguna" :disable="form.processing" />
        <q-input ref="nameInputRef" v-model.trim="form.name" label="Nama" :disable="form.processing" lazy-rules
          :error="!!form.errors.name" :error-message="form.errors.name"
          :rules="[(val) => (val && val.length > 0) || 'Name harus diisi.']" />
        <q-input v-model.trim="form.email" label="Email" :disable="form.processing" />
        <q-input readonly v-model="form.role" label="Hak Akses" :disable="form.processing" />
      </q-card-section>
      <q-card-section>
        <q-btn type="submit" color="primary" label="Perbarui Profil Saya" :disable="form.processing" icon="check" />
      </q-card-section>
    </q-card>
  </q-form>
</template>
