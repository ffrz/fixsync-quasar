<script setup>
import BtnLink from '@/components/BtnLink.vue';
import SummaryCard from './cards/SummaryCard.vue';
import ChartCard from './cards/ChartCard.vue';
const title = 'Dashboard';

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
        <chart-card class="q-py-none q-pt-sm" />
      </div>
      <div class="row q-py-none q-pt-sm">
        <q-card class="col" square flat bordered>
          <q-card-section>
            <div class="q-mb-md text-h6">{{ $t('greeting', { name: $page.props.auth.user.name}) }}</div>
            <div>
              <btn-link :label="$t('logout')" :url="route('admin.auth.logout')" method="post" color="accent" icon="logout" />
            </div>
          </q-card-section>
        </q-card>
      </div>
    </div>
  </authenticated-layout>
</template>
