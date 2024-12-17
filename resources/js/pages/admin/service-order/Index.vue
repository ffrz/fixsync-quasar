<script setup>
import { onMounted, ref, watch } from "vue";
import { router } from "@inertiajs/vue3";
import { useQuasar } from "quasar";
import { default_delete_handler, default_fetch_handler } from "@/helpers/client-req-handler";

const $q = useQuasar();
const tableRef = ref(null);
const rows = ref([]);
const loading = ref(true);
const filter = ref("");
const title = 'Order Servis';
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
  filter.value = localStorage.getItem("fixsync.service-order-list-page.filter");
  fetchItems();
});

watch(filter, (newValue) => {
  if (!newValue && newValue != "") newValue = "";
  localStorage.setItem("fixsync.service-order-list-page.filter", newValue);
});

const deleteItem = (row) => {
  default_delete_handler(`Hapus order ${row.id}?`, route('admin.service-order.delete', row.id), fetchItems, loading);
};

const fetchItems = (props = null) => {
  default_fetch_handler(pagination, filter, props, rows, route('admin.service-order.data'), loading);
};
</script>

<template>
  <i-head :title="title" />
  <authenticated-layout>
    <template #title>{{ title }}</template>
    <div>
      <div class="q-pa-md">
        <q-table ref="tableRef" flat bordered square :dense="true || $q.screen.lt.md" color="primary" row-key="id"
          virtual-scroll title="Order Servis" v-model:pagination="pagination" :filter="filter" :loading="loading"
          :columns="columns" :rows="rows" :rows-per-page-options="[10, 25, 50]" @request="fetchItems" binary-state-sort>
          <template v-slot:loading>
            <q-inner-loading showing color="red" />
          </template>

          <template v-slot:top-left>
            <div class="q-gutter-sm">
              <q-btn color="primary" icon="add" @click="router.get(route('admin.service-order.add'))" label="Tambah">
                <q-tooltip>Terima Servis</q-tooltip>
              </q-btn>
            </div>
          </template>

          <template v-slot:top-right>
            <q-input dense debounce="300" v-model="filter" placeholder="Cari" clearable>
              <template v-slot:append>
                <q-icon name="search" />
              </template>
            </q-input>
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
                <q-btn flat dense rounded icon="delete" @click="deleteItem(props.row)">
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
