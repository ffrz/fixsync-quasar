<script setup>
import { onMounted, ref, watch } from "vue";
import axios from "axios";
import { exportFile, useQuasar } from "quasar";
import { router, usePage } from "@inertiajs/vue3";

const page = usePage();
const currentUser = page.props.auth.user;
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
  { name: "name", label: "Name", field: "name", align: "left", sortable: true },
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
  { name: "action", label: "Action", align: "center" },
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
  $q.dialog({
    title: "Confirm",
    icon: "question",
    message: `Hapus pelanggan ${row.name}?`,
    focus: "cancel",
    cancel: true,
    persistent: true,
  }).onOk(() => {
    loading.value = true;
    axios
      .post(route('admin.customer.delete', row.id))
      .then((response) => {
        $q.notify(response.data.message);
        fetchItems();
      })
      .finally(() => {
        loading.value = false;
      })
      .catch((error) => {
        let message = "";
        if (error.response.data && error.response.data.message) {
          message = error.response.data.message;
        } else if (error.message) {
          message = error.message;
        }
        $q.notify({ message: message, color: "red" });
        console.log(error);
      });
  });
};

const fetchItems = (props = null) => {
  let params = {
    page: pagination.value.page,
    per_page: pagination.value.rowsPerPage,
    order_by: pagination.value.sortBy,
    order_type: pagination.value.descending ? "desc" : "asc",
    filter: filter.value,
  };

  if (props != null) {
    params.page = props.pagination.page;
    params.per_page = props.pagination.rowsPerPage;
    params.order_by = props.pagination.sortBy;
    params.order_type = props.pagination.descending ? "desc" : "asc";
    params.filter = props.filter;
    filter.value = props.filter;
  }

  loading.value = true;

  axios
    .get(route('admin.customer.data'), { params: params })
    .then((response) => {
      rows.value = response.data.data;
      pagination.value.page = response.data.current_page;
      pagination.value.rowsPerPage = response.data.per_page;
      pagination.value.rowsNumber = response.data.total;
      if (props) {
        pagination.value.sortBy = props.pagination.sortBy;
        pagination.value.descending = props.pagination.descending;
      }
    })
    .finally(() => {
      loading.value = false;
    });
};
</script>

<template>
  <authenticated-layout>
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
              <q-btn color="primary" dense icon="add" @click="router.get(route('admin.customer.add'))" label="Tambah&nbsp;&nbsp;">
                <q-tooltip>Tambah Pelanggan</q-tooltip>
              </q-btn>
              <!--<q-btn no-caps color="grey-8" icon="archive" @click="exportToCsv" />-->
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
                <q-btn rounded dense color="grey" icon="edit"
                  @click="router.get(route('admin.customer.edit', props.row.id))">
                  <q-tooltip>Edit Pelanggan</q-tooltip>
                </q-btn>
                <q-btn rounded dense color="red" icon="delete" @click="deleteItem(props.row)">
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
