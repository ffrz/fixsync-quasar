<script setup>
// FIXME: Terkadang terjadi double fetch pada saat komponen di mount

import { onMounted, reactive, ref, watch } from "vue";
import { router } from "@inertiajs/vue3";
import { useQuasar } from "quasar";
import { handleDelete, handleFetchItems } from "@/helpers/client-req-handler";
import { create_options } from "@/helpers/utils";

const $q = useQuasar();
const tableRef = ref(null);
const rows = ref([]);
const loading = ref(true);
const filter = reactive({
  search: '',
  order_status: 'all',
  service_status: 'all',
  payment_status: 'all',
});
const title = 'Order Servis';

const order_statuses = [{ value: 'all', label: 'Semua' }, ...create_options(window.CONSTANTS.SERVICEORDER_ORDER_STATUSES)];
const service_statuses = [{ value: 'all', label: 'Semua' }, ...create_options(window.CONSTANTS.SERVICEORDER_SERVICE_STATUSES)];
const payment_statuses = [{ value: 'all', label: 'Semua' }, ...create_options(window.CONSTANTS.SERVICEORDER_PAYMENT_STATUSES)];

const pagination = ref({
  page: 1,
  rowsPerPage: 10,
  rowsNumber: 10,
  sortBy: "id",
  descending: true,
});

const columns = [{
  name: "id",
  label: "ID",
  field: "id",
  align: "left",
  sortable: true
}, {
  name: "received_datetime",
  label: "Tgl Masuk",
  field: "received_datetime",
  align: "left",
  sortable: true,
}, {
  name: "device",
  label: "Perangkat",
  field: "device",
  align: "left",
  sortable: true,
}, {
  name: "customer_name",
  label: "Atas Nama",
  field: "customer_name",
  align: "left",
  sortable: true,
}, {
  name: "customer_phone",
  label: "No HP",
  field: "customer_phone",
  align: "left",
  sortable: true,
}, {
  name: "customer_address",
  label: "Alamat",
  field: "customer_address",
  align: "left",
  sortable: true,
}, {
  name: "action",
  label: "Aksi",
  align: "center"
}];

onMounted(() => {
  const savedFilter = localStorage.getItem('fixsync.service-orders.filter');
  if (savedFilter) {
    Object.assign(filter, JSON.parse(savedFilter));
  }

  /**
   * ini harus dinonaktifkan karena mengakibatkan double fetch, siapa yg mentrigger fetch pertama kali selain baris ini?
   * anehnya, kode ini hampir sama dengan modul user, tapi di modul user tidak terjadi double fetch
   */
  fetchItems();
});

watch(filter, (newValue) => {
  localStorage.setItem('fixsync.service-orders.filter', JSON.stringify(newValue));
}, { deep: true });

const deleteItem = (row) =>
  handleDelete({
    message: `Hapus order ${row.id}?`,
    url: route('admin.service-order.delete', row.id),
    fetchItemsCallback: fetchItems,
    loading
  });

const fetchItems = (props = null) =>
  handleFetchItems({ pagination, filter, props, rows, url: route('admin.service-order.data'), loading });

const onFilterChange = () => {
  fetchItems();
}

</script>

<template>
  <i-head :title="title" />
  <authenticated-layout>
    <template #title>{{ title }}</template>
    <div>
      <div class="q-pa-md">
        <q-table ref="tableRef" flat bordered square :dense="true || $q.screen.lt.md" color="primary" row-key="id"
          virtual-scroll title="Order Servis" v-model:pagination="pagination" :filter="filter.search" :loading="loading"
          :columns="columns" :rows="rows" :rows-per-page-options="[10, 25, 50]" @request="fetchItems" binary-state-sort>
          <template v-slot:loading>
            <q-inner-loading showing color="red" />
          </template>

          <template #top>
            <div class="col">
              <div class="row q-my-sm items-center">
                <q-btn color="primary" icon="add" @click="router.get(route('admin.service-order.add'))" label="Tambah">
                  <q-tooltip>Tambah Order Servis</q-tooltip>
                </q-btn>
                <q-space />
                <q-input dense debounce="300" v-model="filter.search" placeholder="Cari" clearable>
                  <template v-slot:append>
                    <q-icon name="search" />
                  </template>
                </q-input>
              </div>
              <div class="row q-my-sm q-gutter-sm items-center">
                <span>Filter:</span>
                <q-select v-model="filter.order_status" :options="order_statuses" label="Status Order" dense map-options
                  style="min-width:150px;" emit-value outlined @update:model-value="onFilterChange" />
                <q-select v-model="filter.service_status" :options="service_statuses" label="Status Servis" dense
                  map-options style="min-width:150px;" emit-value outlined @update:model-value="onFilterChange" />
                <q-select v-model="filter.payment_status" :options="payment_statuses" label="Status Pembayaran" dense
                  map-options style="min-width:150px;" emit-value outlined @update:model-value="onFilterChange" />
              </div>
            </div>
          </template>

          <template v-slot:no-data="{ icon, message, filter }">
            <div class="full-width row flex-center text-grey-8 q-gutter-sm">
              <q-icon size="2em" name="sentiment_dissatisfied" />
              <span>
                {{ message }}
                {{ filter ? " with term " + filter : "" }}</span>
              <q-icon size="2em" :name="filter ? 'filter_b_and_w' : icon" />
            </div>
          </template>

          <template v-slot:body="props">
            <q-tr :props="props">
              <q-td key="id" :props="props">
                {{ props.row.id }}
              </q-td>
              <q-td key="received_datetime" :props="props">
                {{ props.row.received_datetime }}
              </q-td>
              <q-td key="device" :props="props">
                {{ props.row.device }}
              </q-td>
              <q-td key="customer_name" :props="props">
                {{ props.row.customer_name }}
              </q-td>
              <q-td key="customer_phone" :props="props">
                {{ props.row.customer_phone }}
              </q-td>
              <q-td key="customer_address" :props="props">
                {{ props.row.customer_address }}
              </q-td>
              <q-td key="action" class="q-gutter-x-sm" :props="props" align="center">
                <q-btn flat dense rounded icon="edit"
                  @click="router.get(route('admin.service-order.edit', props.row.id))">
                  <q-tooltip>Edit Pesanan</q-tooltip>
                </q-btn>
                <q-btn flat dense rounded icon="delete" @click="deleteItem(props.row)" :disable="$page.props.auth.user.role != 'admin'">
                  <q-tooltip>Hapus Pesanan</q-tooltip>
                </q-btn>
              </q-td>
            </q-tr>
          </template>
        </q-table>
      </div>
    </div>
  </authenticated-layout>
</template>
