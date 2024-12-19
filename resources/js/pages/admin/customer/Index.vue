<script setup>
import { onMounted, reactive, ref, watch } from "vue";
import { router } from "@inertiajs/vue3";
import { useQuasar } from "quasar";
import { handleDelete, handleFetchItems } from "@/helpers/client-req-handler";

const $q = useQuasar();
const tableRef = ref(null);
const rows = ref([]);
const loading = ref(true);
const filter = reactive({
  search: "",
  status: "all",
});
const title = "Pelanggan";
const pagination = ref({
  page: 1,
  rowsPerPage: 10,
  rowsNumber: 10,
  sortBy: "name",
  descending: false,
});

const columns = [{
  name: "name",
  label: "Nama",
  field: "name",
  align: "left",
  sortable: true
}, {
  name: "phone",
  label: "No HP",
  field: "phone",
  align: "left",
  sortable: true,
}, {
  name: "address",
  label: "Alamat",
  field: "address",
  align: "left",
  sortable: true,
}, {
  name: "action",
  label: "Aksi",
  align: "center"
}];

const statuses = [
  { value: 'all', label: 'Semua' },
  { value: 'active', label: 'Aktif' },
  { value: 'inactive', label: 'Tidak Aktif' },
];

onMounted(() => {
  const savedFilter = localStorage.getItem('fixsync.customers.filter');
  if (savedFilter) {
    Object.assign(filter, JSON.parse(savedFilter));
  }
  fetchItems();
});

watch(filter, (newValue) => {
  localStorage.setItem('fixsync.customers.filter', JSON.stringify(newValue));
}, { deep: true });

const deleteItem = (row) =>
  handleDelete({
    message: `Hapus pelanggan ${row.name}?`,
    url: route('admin.customer.delete', row.id),
    fetchItemsCallback: fetchItems,
    loading
  });

const fetchItems = (props = null) =>
  handleFetchItems({ pagination, filter, props, rows, url: route('admin.customer.data'), loading });

const onFilterChange = () => fetchItems();
</script>

<template>
  <i-head :title="title" />
  <authenticated-layout>
    <template #title>{{ title }}</template>
    <q-page>
      <div class="q-pa-md">
        <q-table ref="tableRef" flat bordered square :dense="true || $q.screen.lt.md" color="primary" row-key="id"
          virtual-scroll v-model:pagination="pagination" :filter="filter.search" :loading="loading" :columns="columns"
          :rows="rows" :rows-per-page-options="[10, 25, 50]" @request="fetchItems" binary-state-sort>
          <template v-slot:loading>
            <q-inner-loading showing color="red" />
          </template>

          <template #top>
            <div class="col">
              <div class="row q-my-sm items-center">
                <q-btn color="primary" icon="add" @click="router.get(route('admin.customer.add'))" label="Tambah">
                  <q-tooltip>Tambah Pelanggan</q-tooltip>
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
                <q-select style="min-width: 150px;" v-model="filter.status" :options="statuses" label="Status" dense
                  map-options emit-value outlined @update:model-value="onFilterChange" />
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
            <q-tr :props="props" :class="!props.row.active ? 'bg-red-1' : ''">
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
                <q-btn flat dense rounded icon="edit" @click="router.get(route('admin.customer.edit', props.row.id))">
                  <q-tooltip>Edit Pelanggan</q-tooltip>
                </q-btn>
                <q-btn flat dense rounded icon="delete" @click="deleteItem(props.row)">
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
