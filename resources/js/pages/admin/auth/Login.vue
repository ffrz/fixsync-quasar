<script setup>
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";

let companyCodeInput = ref(null);
let form = useForm({
  company_code: "",
  username: "",
  password: "",
  remember: false,
});

const submit = () => {
  form.clearErrors();
  form.post(route('admin.auth.login'), {
    preserveScroll: true,
    onError: () => {
      companyCodeInput.value.focus(); // kenapa ini tidak bisa fokus???
    }
  });
};
</script>

<template>
  <guest-layout>
    <i-head title="Login" />
    <q-page class="row justify-center items-center">
      <div class="column">
        <div class="row">
          <q-form class="q-gutter-md" @submit.prevent="submit">
            <q-card square bordered class="q-pa-md shadow-1">
              <q-card-section>
                <h5 class="q-my-sm text-center">Login</h5>
              </q-card-section>
              <q-card-section>
                <q-input ref="companyCodeInput" autofocus v-model.trim="form.company_code" label="Kode Perusahaan"
                  lazy-rules :error="!!form.errors.company_code" :error-message="form.errors.company_code"
                  :disable="form.processing" :rules="[(val) => (val && val.length > 0) || 'Masukkan kode perusahaan',]">
                  <template v-slot:append>
                    <q-icon name="store" />
                  </template>
                </q-input>
                <q-input v-model.trim="form.username" label="ID Pengguna" lazy-rules :error="!!form.errors.username"
                  :error-message="form.errors.username" :disable="form.processing"
                  :rules="[(val) => (val && val.length > 0) || 'Masukkan ID Pengguna',]">
                  <template v-slot:append>
                    <q-icon name="person" />
                  </template>
                </q-input>
                <q-input v-model="form.password" type="password" label="Kata Sandi" :error="!!form.errors.password"
                  :error-message="form.errors.password" lazy-rules :disable="form.processing"
                  :rules="[(val) => (val && val.length > 0) || 'Masukkan kata sandi',]">
                  <template v-slot:append>
                    <q-icon name="key" />
                  </template>
                </q-input>
                <q-checkbox class="q-mt-sm q-pl-none" style="margin-left: -10px;" v-model="form.remember"
                  :disable="form.processing" label="Ingat saya di perangkat ini" />
              </q-card-section>
              <q-card-actions>
                <q-btn icon="login" type="submit" color="primary" class="full-width" label="Login"
                  :disable="form.processing" />
              </q-card-actions>
              <q-card-section class="text-center q-pa-none q-mt-md">
                <p class="q-my-xs text-grey-7">
                  Not reigistered?
                  <i-link :href="route('register')">Create an Account</i-link>
                </p>
                <p class="q-my-xs text-grey-7">
                  Forgot password?
                  <i-link :href="route('password.request')">Reset Password</i-link>
                </p>
                <!-- <p class="q-my-xs text-grey-7">
                  Forgot password? Please contact our system administrator.
                </p> -->
              </q-card-section>
            </q-card>
          </q-form>
        </div>
      </div>
    </q-page>
  </guest-layout>
</template>

<style>
.q-card {
  width: 360px;
}
</style>
