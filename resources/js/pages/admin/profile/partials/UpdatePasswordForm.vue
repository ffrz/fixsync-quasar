<script setup>

import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
  current_password: '',
  password: '',
  password_confirmation: '',
});

const updatePassword = () => {
  form.clearErrors();
  form.post(route('admin.profile.update-password'), {
    preserveScroll: true,
    onSuccess: () => form.reset(),
    onError: () => {
      if (form.errors.password) {
        form.reset('password', 'password_confirmation');
        passwordInput.value.focus();
      }
      if (form.errors.current_password) {
        form.reset('current_password');
        currentPasswordInput.value.focus();
      }
    },
  });
};
</script>

<template>
  <q-form class="row" @submit.prevent="updatePassword">
    <q-card square flat bordered class="col q-pa-sm">
      <q-card-section>
        <h2 class="text-h6 q-my-xs">Perbarui Kata Sandi</h2>
        <p>Pastikan akun anda menggunakan kata sandi acak yang panjang agar akun tetap aman.</p>
        <q-input v-model="form.current_password" label="Kata Sandi Sekarang" type="password" lazy-rules
          :disable="form.processing" :error="!!form.errors.current_password"
          :error-message="form.errors.current_password"
          :rules="[(val) => (val && val.length > 0) || 'Kata sandi harus diisi.']" />
        <q-input v-model="form.password" label="Kata Sandi Baru" type="password" lazy-rules
          :disable="form.processing" :error="!!form.errors.password" :error-message="form.errors.password"
          :rules="[(val) => (val && val.length > 0) || 'Kata sandi harus diisi.']" />
        <q-input v-model="form.password_confirmation" label="Konfirmasi Kata Sandi" type="password" lazy-rules
          :disable="form.processing" :error="!!form.errors.password_confirmation"
          :error-message="form.errors.password_confirmation"
          :rules="[(val) => (val && val.length > 0) || 'Kata sandi harus diisi.']" />
      </q-card-section>
      <q-card-section>
        <q-btn type="submit" color="primary" label="Perbarui Kata Sandi" :disable="form.processing" icon="check" />
      </q-card-section>
    </q-card>
  </q-form>
</template>
