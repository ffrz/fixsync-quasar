<script setup>
import CompanyInfoForm from './partials/CompanyInfoForm.vue';
const title = "Profil Perusahaan";
</script>

<template>
  <i-head :title="title" />
  <authenticated-layout>
    <template #title>{{ title }}</template>
    <div class="row justify-center">
      <div class="col col-lg-6 q-pa-md q-gutter-md">
        <CompanyInfoForm />
      </div>
    </div>
  </authenticated-layout>
</template>
