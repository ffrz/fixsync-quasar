<script setup>
// FIXME: Terkadang terjadi double fetch pada saat komponen di mount

import { onMounted, reactive, ref, watch } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import { useQuasar } from "quasar";
import { handleDelete, handleFetchItems } from "@/helpers/client-req-handler";
import { create_options, check_role } from "@/helpers/utils";

const page = usePage();
const $q = useQuasar();
const tableRef = ref(null);
const rows = ref([]);
const loading = ref(true);
const showFilter = ref(false);
const filter = reactive({
  search: "",
  order_status: page.props.filter ? page.props.filter.order_status : "all",
  service_status: "all",
  payment_status: "all",
  repair_status: "all",
});
const title = "Order Servis";

const order_statuses = [
  { value: "all", label: "Semua" },
  ...create_options(window.CONSTANTS.SERVICEORDER_ORDERSTATUSES),
];
const service_statuses = [
  { value: "all", label: "Semua" },
  ...create_options(window.CONSTANTS.SERVICEORDER_SERVICESTATUSES),
];
const payment_statuses = [
  { value: "all", label: "Semua" },
  ...create_options(window.CONSTANTS.SERVICEORDER_PAYMENTSTATUSES),
];
const repair_statuses = [
  { value: "all", label: "Semua" },
  ...create_options(window.CONSTANTS.SERVICEORDER_REPAIRSTATUSES),
];

const pagination = ref({
  page: 1,
  rowsPerPage: 10,
  rowsNumber: 10,
  sortBy: "id",
  descending: true,
});

const columns = [
  {
    name: "id",
    label: "ID",
    field: "id",
    align: "left",
    sortable: true,
  },
  {
    name: "received_datetime",
    label: "Tgl Masuk",
    field: "received_datetime",
    align: "left",
    sortable: true,
  },
  {
    name: "device",
    label: "Perangkat",
    field: "device",
    align: "left",
    sortable: true,
  },
  {
    name: "customer_name",
    label: "Atas Nama",
    field: "customer_name",
    align: "left",
    sortable: true,
  },
  {
    name: "customer_phone",
    label: "No HP",
    field: "customer_phone",
    align: "left",
    sortable: true,
  },
  {
    name: "customer_address",
    label: "Alamat",
    field: "customer_address",
    align: "left",
    sortable: true,
  },
  {
    name: "action",
    label: "Aksi",
    align: "center",
  },
];

onMounted(() => {
  const savedFilter = localStorage.getItem("fixsync.service-orders.filter");
  if (savedFilter) {
    Object.assign(filter, JSON.parse(savedFilter));
  }

  /**
   * ini harus dinonaktifkan karena mengakibatkan double fetch, siapa yg mentrigger fetch pertama kali selain baris ini?
   * anehnya, kode ini hampir sama dengan modul user, tapi di modul user tidak terjadi double fetch
   */
  fetchItems();
});

watch(
  filter,
  (newValue) => {
    localStorage.setItem(
      "fixsync.service-orders.filter",
      JSON.stringify(newValue)
    );
  },
  { deep: true }
);

const deleteItem = (row) =>
  handleDelete({
    message: `Hapus order ${row.id}?`,
    url: route("admin.service-order.delete", row.id),
    fetchItemsCallback: fetchItems,
    loading,
  });

const fetchItems = (props = null) =>
  handleFetchItems({
    pagination,
    filter,
    props,
    rows,
    url: route("admin.service-order.data"),
    loading,
  });

const onFilterChange = () => {
  fetchItems();
};
</script>

<template>
  <i-head :title="title" />
  <authenticated-layout>
    <template #right-button>
      <q-btn
        icon="add"
        dense
        color="primary"
        @click="router.get(route('admin.service-order.add'))"
      />
      <q-btn
        class="q-ml-sm"
        :icon="!showFilter ? 'filter_alt' : 'filter_alt_off'"
        color="grey"
        dense
        @click="showFilter = !showFilter"
      />
    </template>
    <template #title>{{ title }}</template>
    <template #header v-if="showFilter">
      <q-toolbar class="filter-bar">
        <div class="row q-col-gutter-xs items-center q-pa-sm full-width">
          <q-select
            v-model="filter.order_status"
            :options="order_statuses"
            label="Status Order"
            dense
            map-options
            class="custom-select col-xs-12 col-sm-2"
            emit-value
            outlined
            @update:model-value="onFilterChange"
          />
          <q-select
            v-model="filter.service_status"
            :options="service_statuses"
            label="Status Servis"
            dense
            class="custom-select col-xs-12 col-sm-2"
            map-options
            emit-value
            outlined
            @update:model-value="onFilterChange"
          />
          <q-select
            v-model="filter.repair_status"
            :options="repair_statuses"
            label="Status Selesai"
            dense
            class="custom-select col-xs-12 col-sm-2"
            map-options
            emit-value
            outlined
            @update:model-value="onFilterChange"
          />
          <q-select
            v-model="filter.payment_status"
            :options="payment_statuses"
            label="Status Pembayaran"
            dense
            class="custom-select col-xs-12 col-sm-2"
            map-options
            emit-value
            outlined
            @update:model-value="onFilterChange"
          />
          <q-input
            class="col"
            outlined
            dense
            debounce="300"
            v-model="filter.search"
            placeholder="Cari"
            clearable
          >
            <template v-slot:append>
              <q-icon name="search" />
            </template>
          </q-input>
        </div>
      </q-toolbar>
    </template>
    <q-page class="flex flex-col q-pa-sm">
      <q-table
        ref="tableRef"
        flat
        bordered
        square
        color="primary"
        class="full-height-table"
        row-key="id"
        virtual-scroll
        v-model:pagination="pagination"
        :filter="filter.search"
        :loading="loading"
        :columns="columns"
        :rows="rows"
        :rows-per-page-options="[10, 25, 50]"
        @request="fetchItems"
        binary-state-sort
      >
        <template v-slot:loading>
          <q-inner-loading showing color="red" />
        </template>

        <template v-slot:no-data="{ icon, message, filter }">
          <div class="full-width row flex-center text-grey-8 q-gutter-sm">
            <q-icon size="2em" name="sentiment_dissatisfied" />
            <span>
              {{ message }}
              {{ filter ? " with term " + filter : "" }}</span
            >
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
            <q-td
              key="action"
              class="q-gutter-x-sm"
              :props="props"
              align="center"
            >
              <q-btn
                flat
                dense
                rounded
                icon="edit"
                @click="
                  router.get(route('admin.service-order.edit', props.row.id))
                "
              >
                <q-tooltip>Edit Pesanan</q-tooltip>
              </q-btn>
              <q-btn
                flat
                dense
                rounded
                icon="delete"
                @click="deleteItem(props.row)"
                :disable="!check_role($CONSTANTS.USER_ROLE_ADMIN)"
              >
                <q-tooltip>Hapus Pesanan</q-tooltip>
              </q-btn>
            </q-td>
          </q-tr>
        </template>
      </q-table>
    </q-page>
  </authenticated-layout>
</template>
<style scoped>
.custom-select {
  min-width: 150px;
}
</style>
