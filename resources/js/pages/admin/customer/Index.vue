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
const pagination = ref({
  page: 1,
  rowsPerPage: 10,
  rowsNumber: 10,
  sortBy: "name",
  descending: false,
});

const columns = [
  { name: "name", label: "Nama", field: "name", align: "left", sortable: true },
  {
    name: "phone",
    label: "No HP",
    field: "phone",
    align: "left",
    sortable: true,
  },
  {
    name: "address",
    label: "Alamat",
    field: "address",
    align: "left",
    sortable: true,
  },
  { name: "action", label: "Aksi", align: "center" },
];

onMounted(() => {
  filter.value = localStorage.getItem("fixsync.customer-list-page.filter");
  fetchItems();
});

watch(filter, (newValue) => {
  if (!newValue && newValue != "") newValue = "";
  localStorage.setItem("fixsync.customer-list-page.filter", newValue);
});

const deleteItem = (row) => {
  default_delete_handler(`Hapus pelanggan ${row.name}?`, route('admin.customer.delete', row.id), fetchItems, loading);
};

const fetchItems = (props = null) => {
  default_fetch_handler(pagination, filter, props, rows, route('admin.customer.data'), loading);
};
</script>

<template>
  <authenticated-layout>
    <i-head title="Pelanggan" />
    <q-page>
      <div class="q-pa-md">
        <q-table ref="tableRef" flat bordered square :dense="true || $q.screen.lt.md" color="primary" row-key="id"
          virtual-scroll title="Pelanggan" v-model:pagination="pagination" :filter="filter" :loading="loading"
          :columns="columns" :rows="rows" :rows-per-page-options="[10, 25, 50]" @request="fetchItems" binary-state-sort>
          <template v-slot:loading>
            <q-inner-loading showing color="red" />
          </template>

          <template v-slot:top-left>
            <div class="q-gutter-sm">
              <q-btn color="primary" icon="add" @click="router.get(route('admin.customer.add'))" label="Tambah">
                <q-tooltip>Tambah Pelanggan</q-tooltip>
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
                Well this is sad... {{ message }}
                {{ filter ? " with term " + filter : "" }}</span>
              <q-icon size="2em" :name="filter ? 'filter_b_and_w' : icon" />
            </div>
          </template>

          <template v-slot:body="props">
            <q-tr :props="props">
              <q-td key="name" :props="props">
                {{ props.row.name }}
              </q-td>
              <q-td key="phone" :props="props">
                {{ props.row.phone }}
              </q-td>
              <q-td key="address" :props="props">
                {{ props.row.address }}
              </q-td>
              <q-td key="action" class="q-gutter-x-sm" :props="props" align="center">
                <q-btn flat dense icon="edit"
                  @click="router.get(route('admin.customer.edit', props.row.id))">
                  <q-tooltip>Edit Pelanggan</q-tooltip>
                </q-btn>
                <q-btn flat dense icon="delete" @click="deleteItem(props.row)">
                  <q-tooltip>Hapus Pelanggan</q-tooltip>
                </q-btn>
              </q-td>
            </q-tr>
          </template>
        </q-table>
      </div>
    </q-page>
  </authenticated-layout>
</template>
