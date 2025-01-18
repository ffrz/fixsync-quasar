<script setup>
import { router, useForm, usePage } from "@inertiajs/vue3";
import { handleSubmit } from "@/helpers/client-req-handler";
import { scrollToFirstErrorField } from "@/helpers/utils";
import LocaleNumberInput from "@/components/LocaleNumberInput.vue";
import DatePicker from "@/components/DatePicker.vue";

const page = usePage();
const title = (!!page.props.data.id ? "Edit" : "Tambah") + " Biaya Operasional";
const form = useForm({
  id: page.props.data.id,
  date: page.props.data.date,
  description: page.props.data.description,
  notes: page.props.data.notes,
  amount: page.props.data.amount,
});

const submit = () => handleSubmit({ form, url: route('admin.operational-cost.save') });

</script>

<template>
  <i-head :title="title" />
  <authenticated-layout>
    <template #title>{{ title }}</template>
    <q-page class="row justify-center">
      <div class="col col-lg-6 q-pa-sm">
        <q-form class="row" @submit.prevent="submit" @validation-error="scrollToFirstErrorField">
          <q-card square flat bordered class="col">
            <q-card-section class="q-pt-none">
              <input type="hidden" name="id" v-model="form.id" />
              <date-picker v-model="form.date" label="Tanggal"
                    :error="!!form.errors.date" :disable="form.processing" />
              <q-input autofocus v-model.trim="form.description" label="Deskripsi" lazy-rules :error="!!form.errors.description"
                :disable="form.processing" :error-message="form.errors.description" :rules="[
                  (val) => (val && val.length > 0) || 'Deskripsi harus diisi.',
                ]" />
              <LocaleNumberInput v-model:modelValue="form.amount" label="Jumlah" lazyRules
                :disable="form.processing" :error="!!form.errors.amount"
                :errorMessage="form.errors.amount" :rules="[]" />
              <q-input v-model.trim="form.notes" type="textarea" autogrow counter maxlength="1000" label="Catatan"
                lazy-rules :disable="form.processing" :error="!!form.errors.notes"
                :error-message="form.errors.notes" />
            </q-card-section>
            <q-card-section class="q-gutter-sm">
              <q-btn icon="save" type="submit" label="Simpan" color="primary" :disable="form.processing" />
              <q-btn icon="cancel" label="Batal" :disable="form.processing"
                @click="router.get(route('admin.operational-cost.index'))" />
            </q-card-section>
          </q-card>
        </q-form>
      </div>
    </q-page>

  </authenticated-layout>
</template>
