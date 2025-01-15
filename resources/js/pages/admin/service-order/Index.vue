<script setup>

import { onMounted, reactive, ref } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import { useQuasar } from "quasar";
import { handleDelete, handleFetchItems } from "@/helpers/client-req-handler";
import { create_options, check_role } from "@/helpers/utils";

const title = "Order Servis";
const page = usePage();
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
    name: "order",
    label: "Order",
    field: "order",
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
    name: "customer",
    label: "Pelanggan",
    field: "customer",
    align: "left",
    sortable: true,
  },
  {
    name: "action",
    align: "right",
  },
];

onMounted(() => {
  // const savedFilter = localStorage.getItem("fixsync.service-orders.filter");
  // if (savedFilter) {
  //   Object.assign(filter, JSON.parse(savedFilter));
  // }

  // /**
  //  * ini harus dinonaktifkan karena mengakibatkan double fetch, siapa yg mentrigger fetch pertama kali selain baris ini?
  //  * anehnya, kode ini hampir sama dengan modul user, tapi di modul user tidak terjadi double fetch
  //  */
  fetchItems();
});

// watch(
//   filter,
//   (newValue) => {
//     localStorage.setItem(
//       "fixsync.service-orders.filter",
//       JSON.stringify(newValue)
//     );
//   },
//   { deep: true }
// );

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

const onRowClicked = (row) => {
  router.get(route("admin.service-order.detail", row.id));
}
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
        class="full-height-table va-top"
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
          <q-tr :props="props" @click="onRowClicked(props.row)" class="cursor-pointer">
            <q-td key="order" :props="props">
              <b>#{{ props.row.id }}</b><br>
              {{ $dayjs(new Date(props.row.received_datetime)).format('DD/MM/YYYY HH:mm') }}<br>
              <q-chip
                dense
                :color="props.row.order_status === 'open' ? 'green' : (props.row.order_status === 'closed' ? 'grey' : 'red')"
                :icon="props.row.order_status === 'open' ? 'question_mark' : (props.row.order_status === 'closed' ? 'check' : 'asterisk')">
                {{ $CONSTANTS.SERVICEORDER_ORDERSTATUSES[props.row.order_status] }}
              </q-chip>
            </q-td>
            <q-td key="device" :props="props">
              <b>{{ props.row.device }}</b><br>
              {{ props.row.problems }}<br>
              {{ props.row.actions }}<br>
              <q-chip dense icon="handyman">{{ $CONSTANTS.SERVICEORDER_SERVICESTATUSES[props.row.service_status] }}</q-chip>
              <q-chip dense icon="task_alt">{{ $CONSTANTS.SERVICEORDER_REPAIRSTATUSES[props.row.repair_status] }}</q-chip>
              <q-chip dense icon="payments">{{ $CONSTANTS.SERVICEORDER_PAYMENTSTATUSES[props.row.payment_status] }}</q-chip>
            </q-td>
            <q-td key="customer" :props="props">
              <b>{{ props.row.customer_name }}</b><br>
              {{ props.row.customer_phone }}<br>
              {{ props.row.customer_address }}
            </q-td>
            <q-td key="status" :props="props">
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
                @click.stop="
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
                @click.stop="deleteItem(props.row)"
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
