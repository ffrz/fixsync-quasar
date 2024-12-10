<script setup>
import CompanyInfoForm from './partials/CompanyInfoForm.vue';
</script>

<template>
  <authenticated-layout>
    <q-page class="row justify-center">
      <i-head title="Profile" />
      <div class="col col-lg-6 q-pa-md q-gutter-md">
        <CompanyInfoForm />
      </div>
    </q-page>
  </authenticated-layout>
</template>
