<script setup>
import BtnLink from "@/components/BtnLink.vue";
import SummaryCard from "./cards/SummaryCard.vue";
import ChartCard from "./cards/ChartCard.vue";
import TopCard from "./cards/TopCard.vue";

const title = "Dashboard";
</script>

<template>
  <i-head :title="title" />
  <authenticated-layout>
    <template #title>{{ title }}</template>
    <div class="q-pa-sm">
      <div>
        <summary-card class="q-py-none" />
      </div>
      <div>
        <div class="row q-pt-sm q-gutter-sm">
          <top-card :items="$page.props.data.top_customers" title="Top 5 Pelanggan" route_url="admin.customer.detail"></top-card>
          <top-card :items="$page.props.data.top_technicians" title="Top 5 Teknisi" route_url="admin.technician.detail"></top-card>
        </div>
      </div>
      <div>
        <chart-card class="q-py-none q-pt-sm" />
      </div>
    </div>
  </authenticated-layout>
</template>
