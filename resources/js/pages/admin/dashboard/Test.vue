<script setup>

import DateTimePicker from '@/components/DateTimePicker.vue';

import { ref } from 'vue';

const date = ref('');
const time = ref('');
const datetime = ref('');
const openPopup = ref(false);

const syncDateTime = () => {
  if (date.value && time.value) {
    datetime.value = `${date.value}T${time.value}`;
  }
};

const handleSubmit = () => {
  console.log('Selected DateTime:', datetime.value);
  // Send `datetime.value` to a backend or process further
};
</script>


<template>
  <authenticated-layout>
    <div class="row">
      <div class="q-pa-md">
        <date-time-picker />
        <q-btn label="Submit" color="primary" @click="handleSubmit" />
      </div>
    </div>
  </authenticated-layout>
</template>
