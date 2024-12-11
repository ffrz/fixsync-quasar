<script setup>
import { router, useForm, usePage } from "@inertiajs/vue3";
import { default_submit_handler } from "@/helpers/client-req-handler";
import { create_options } from "@/helpers/utils";

const page = usePage();
const order_statuses = create_options(window.CONSTANTS.SERVICEORDER_ORDER_STATUSES);

const form = useForm({
  id: page.props.data.id,
  order_status: page.props.data.order_status,
  created_datetime: page.props.data.created_datetime,
  created_by_uid: page.props.data.created_by_uid,
  closed_datetime: page.props.data.closed_datetime,
  closed_by_uid: page.props.data.closed_by_uid,
  updated_datetime: page.props.data.updated_datetime,
  updated_by_uid: page.props.data.updated_by_uid,

  customer_id: page.props.data.customer_id,
  customer_name: page.props.data.customer_name,
  customer_phone: page.props.data.customer_phone,
  customer_address: page.props.data.customer_address,

  device: page.props.data.device,
  equipments: page.props.data.equipments,
  device_sn: page.props.data.device_sn,

  problems: page.props.data.problems,
  actions: page.props.data.actions,

  down_payment: page.props.data.down_payment,
  estimated_cost: page.props.data.estimated_cost,
  total_cost: page.props.data.total_cost,
});

const title = !!page.props.data.id ? `Edit Order Servis #${form.id}` : 'Tambah Order Servis';

const submit = () => {
  default_submit_handler(form, route('admin.service-order.save'));
};

</script>

<template>
  <authenticated-layout>
    <q-page class="row justify-center">
      <i-head :title="title" />
      <div class="col col-lg-6 q-pa-md">
        <q-form @submit.prevent="submit" class="q-gutter-md">
          <!-- Mungkin lebih baik kita buat breadcrumb disini -->
          <div class="row">
            <div class="col">
              <div class="text-h6">{{ title }}</div>
            </div>
          </div>
          <div class="row">
            <q-card square flat bordered class="col q-pa-sm">
              <q-card-section>
                <div class="text-h6">Info Order</div>
              </q-card-section>
              <q-card-section class="q-pt-none">
                <input type="hidden" name="id" v-model="form.id" />
                <q-select v-model="form.order_status" label="Status Order" :options="order_statuses" map-options emit-value
                  :error="!!form.errors.order_status" :disable="form.processing" />
                <q-input v-model.trim="form.customer_phone" type="text" label="No HP" lazy-rules
                  :disable="form.processing" :error="!!form.errors.customer_phone"
                  :error-message="form.errors.customer_phone" :rules="[
                    (val) => (val && val.length > 0) || 'No HP harus diisi.',
                  ]" />
                <q-input v-model.trim="form.customer_address" type="textarea" autogrow counter maxlength="1000"
                  label="Alamat" lazy-rules :disable="form.processing" :error="!!form.errors.customer_address"
                  :error-message="form.errors.customer_address" :rules="[
                    (val) => (val && val.length > 0) || 'Alamat harus diisi.',
                  ]" />
              </q-card-section>
            </q-card>
          </div>
          <div class="row">
            <q-card square flat bordered class="col q-pa-sm">
              <q-card-section>
                <div class="text-h6">Info Pelanggan</div>
              </q-card-section>
              <q-card-section class="q-pt-none">
                <q-input v-model.trim="form.customer_name" label="Nama" lazy-rules
                  :error="!!form.errors.customer_name" :disable="form.processing"
                  :error-message="form.errors.customer_name" :rules="[
                    (val) => (val && val.length > 0) || 'Nama harus diisi.',
                  ]" />
                <q-input v-model.trim="form.customer_phone" type="text" label="No HP" lazy-rules
                  :disable="form.processing" :error="!!form.errors.customer_phone"
                  :error-message="form.errors.customer_phone" :rules="[
                    (val) => (val && val.length > 0) || 'No HP harus diisi.',
                  ]" />
                <q-input v-model.trim="form.customer_address" type="textarea" autogrow counter maxlength="1000"
                  label="Alamat" lazy-rules :disable="form.processing" :error="!!form.errors.customer_address"
                  :error-message="form.errors.customer_address" :rules="[
                    (val) => (val && val.length > 0) || 'Alamat harus diisi.',
                  ]" />
              </q-card-section>
            </q-card>
          </div>
          <div class="row">
            <q-card square flat bordered class="col q-pa-sm">
              <q-card-section>
                <div class="text-h6">Info Perangkat</div>
              </q-card-section>
              <q-card-section class="q-pt-none">
                <q-input v-model.trim="form.device" label="Perangkat" lazy-rules :error="!!form.errors.device"
                  :disable="form.processing" :error-message="form.errors.device" :rules="[
                    (val) => (val && val.length > 0) || 'Perangkat harus diisi.',
                  ]" />
                <q-input v-model.trim="form.equipments" type="text" label="Perlengkapan" lazy-rules
                  :disable="form.processing" :error="!!form.errors.equipments" :error-message="form.errors.equipments"
                  :rules="[
                    (val) => (val && val.length > 0) || 'Perlengkapan harus diisi.',
                  ]" />
                <q-input v-model.trim="form.device_sn" type="text" label="SN / PN / ID" lazy-rules
                  :disable="form.processing" :error="!!form.errors.device_sn" :error-message="form.errors.device_sn" />
              </q-card-section>
            </q-card>
          </div>
          <div class="row">
            <q-card square flat bordered class="col q-pa-sm">
              <q-card-section>
                <div class="text-h6">Kendala dan Tindakan</div>
              </q-card-section>
              <q-card-section class="q-pt-none">
                <q-input v-model.trim="form.problems" label="Masalah / Keluhan" lazy-rules
                  :error="!!form.errors.problems" :disable="form.processing" :error-message="form.errors.problems"
                  :rules="[
                    (val) => (val && val.length > 0) || 'Masalah / keluhan harus diisi.',
                  ]" />
                <q-input v-model.trim="form.actions" type="text" label="Tindakan" lazy-rules :disable="form.processing"
                  :error="!!form.errors.actions" :error-message="form.errors.actions" :rules="[
                    (val) => (val && val.length > 0) || 'Tindakan harus diisi.',
                  ]" />
              </q-card-section>
            </q-card>
          </div>
          <div class="row">
            <q-card square flat bordered class="col q-pa-sm">
              <q-card-section>
                <div class="text-h6">Info Biaya</div>
              </q-card-section>
              <q-card-section class="q-pt-none">
                <q-input v-model.trim="form.problems" label="Masalah / Keluhan" lazy-rules
                  :error="!!form.errors.problems" :disable="form.processing" :error-message="form.errors.problems"
                  :rules="[
                    (val) => (val && val.length > 0) || 'Masalah / keluhan harus diisi.',
                  ]" />
                <q-input v-model.trim="form.actions" type="text" label="Tindakan" lazy-rules :disable="form.processing"
                  :error="!!form.errors.actions" :error-message="form.errors.actions" :rules="[
                    (val) => (val && val.length > 0) || 'Tindakan harus diisi.',
                  ]" />
              </q-card-section>
            </q-card>
          </div>
          <div class="row">
            <div class="col q-gutter-md">
              <q-btn type="submit" label="Simpan" color="primary" icon="check" :disable="form.processing" />
              <q-btn label="Batal" v-close-popup color="grey-7" icon="close" :disable="form.processing"
                @click="router.get(route('admin.service-order.index'))" />
            </div>
          </div>
        </q-form>
      </div>
    </q-page>
  </authenticated-layout>
</template>
