<script setup>
import UpdatePasswordForm from './partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './partials/UpdateProfileInformationForm.vue';
</script>

<template>
  <authenticated-layout>
    <q-page class="row">
      <i-head title="Profile" />
      <div class="col col-lg-6 q-pa-md q-gutter-md">
        <UpdateProfileInformationForm />
        <UpdatePasswordForm />
      </div>
    </q-page>
  </authenticated-layout>
</template>
