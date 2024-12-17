<script setup>
import { onMounted, ref, watch } from "vue";
import { useQuasar } from "quasar";
import { router, usePage } from "@inertiajs/vue3";
import { default_fetch_handler, default_delete_handler } from "@/helpers/client-req-handler";

const page = usePage();
const currentUser = page.props.auth.user;
const title = "Pengguna";
const $q = useQuasar();
const tableRef = ref(null);
const rows = ref([]);
const loading = ref(true);
const filter = ref("");
const pagination = ref({
  page: 1,
  rowsPerPage: 10,
  rowsNumber: 10,
  sortBy: "username",
  descending: false,
});

const columns = [{
  name: "username",
  label: "ID Pengguna",
  field: "username",
  align: "left",
  sortable: true
}, {
  name: "name",
  label: "Nama",
  field: "name",
  align: "left",
  sortable: true
}, {
  name: "email",
  label: "Email",
  field: "email",
  align: "left",
  sortable: true,
}, {
  name: "role",
  label: "Hak Akses",
  field: "role",
  align: "center",
  sortable: true,
}, {
  name: "action",
  label: "Aksi",
  align: "center"
}];

onMounted(() => {
  filter.value = localStorage.getItem("fixsync.user-list-page.filter");
  fetchItems();
});

watch(filter, (newValue) => {
  if (!newValue && newValue != "") newValue = "";
  localStorage.setItem("fixsync.user-list-page.filter", newValue);
});

const deleteItem = (row) => {
  default_delete_handler(`Hapus pelanggan ${row.name}?`, route('admin.user.delete', row.id), fetchItems, loading);
};

const fetchItems = (props = null) => {
  default_fetch_handler(pagination, filter, props, rows, route('admin.user.data'), loading);
};
</script>

<template>
  <i-head :title="title" />
  <authenticated-layout>
    <template #title>{{ title }}</template>
    <div class="q-pa-md">
      <q-table ref="tableRef" flat bordered square :dense="true || $q.screen.lt.md" color="primary" row-key="id"
        virtual-scroll title="Pengguna" v-model:pagination="pagination" :filter="filter" :loading="loading"
        :columns="columns" :rows="rows" :rows-per-page-options="[10, 25, 50]" @request="fetchItems" binary-state-sort>
        <template v-slot:loading>
          <q-inner-loading showing color="red" />
        </template>

        <template v-slot:top-left>
          <div class="q-gutter-sm">
            <q-btn color="primary" icon="add" @click="router.get(route('admin.user.add'))" label="Tambah">
              <q-tooltip>Tambah Pengguna</q-tooltip>
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
            <span>{{ message }} {{ filter ? " with term " + filter : "" }}</span>
          </div>
        </template>

        <template v-slot:body="props">
          <q-tr :props="props" :class="(!props.row.active) ? 'bg-red-1' : ''">
            <q-td key="username" :props="props">
              {{ props.row.username }}
            </q-td>
            <q-td key="name" :props="props">
              {{ props.row.name }}
            </q-td>
            <q-td key="email" :props="props">
              {{ props.row.email }}
            </q-td>
            <q-td key="role" :props="props" align="center">
              <span>{{ $CONSTANTS.USER_ROLES[props.row.role] }}</span>
            </q-td>
            <q-td key="action" class="q-gutter-x-sm" :props="props" align="center">
              <q-btn :disable="props.row.id == currentUser.id || props.row.email == 'admin@example.com'" rounded dense
                flat icon="edit" @click="router.get(route('admin.user.edit', props.row.id))">
                <q-tooltip>Edit Pengguna</q-tooltip>
              </q-btn>
              <q-btn :disable="props.row.id == currentUser.id || props.row.email == 'admin@example.com'" rounded dense
                flat icon="delete" @click="deleteItem(props.row)">
                <q-tooltip>Hapus Pengguna</q-tooltip>
              </q-btn>
            </q-td>
          </q-tr>
        </template>
      </q-table>
    </div>
  </authenticated-layout>
</template>
