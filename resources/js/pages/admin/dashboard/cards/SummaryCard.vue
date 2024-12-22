<template>
  <div class="row">
    <q-card class="bg-transparent no-shadow no-border col" bordered>
      <q-card-section class="q-pa-none">
        <div class="row q-col-gutter-sm ">
          <div v-for="(item, index) in items" :key="index" class="col-md-3 col-sm-12 col-xs-12">
            <q-item :style="`background-color: ${item.color1}`" class="q-pa-none" clickable
              @click="if (item.url) this.$inertia.visit(item.url);">
              <q-item-section v-if="icon_position === 'left'" side :style="`background-color: ${item.color2}`"
                class="q-pa-lg q-mr-none text-white">
                <q-icon class="material-icons" :name="item.icon" color="white" size="24px" />
              </q-item-section>
              <q-item-section class=" q-pa-md q-ml-none  text-white">
                <q-item-label class="text-white text-h6 text-weight-bolder">{{ item.value }}</q-item-label>
                <q-item-label>{{ item.title }}</q-item-label>
              </q-item-section>
              <q-item-section v-if="icon_position === 'right'" side class="q-mr-md text-white">
                <q-icon :name="item.icon" color="white" size="44px"></q-icon>
              </q-item-section>
            </q-item>
          </div>
        </div>
      </q-card-section>
    </q-card>
  </div>
</template>

<script>
import { defineComponent } from 'vue'
import { router } from '@inertiajs/vue3';
export default defineComponent({
  name: "SummaryCard",
  props: {
    icon_position: {
      required: false,
      default: "left"
    }
  },
  computed: {
    items: function () {
      return [
        {
          title: this.$t('customers'),
          icon: "groups_2",
          value: this.$page.props.data.active_customer_count,
          color1: "#888",
          color2: "#666",
          url: route('admin.customer.index')
        },
        {
          title: this.$t('active_order'),
          icon: "handyman",
          value: this.$page.props.data.active_order_count ?? 0,
          color1: "#888",
          color2: "#666",
          url: route('admin.service-order.index')
        },
        {
          title: this.$t('not_yet_checked'),
          icon: "handyman",
          value: this.$page.props.data.received_order_count ?? 0,
          color1: "#888",
          color2: "#666",
          url: route('admin.service-order.index')
        },
        {
          title: this.$t('in_progress'),
          icon: "handyman",
          value: this.$page.props.data.in_progress_order_count ?? 0,
          color1: "#888",
          color2: "#666",
          url: route('admin.service-order.index')
        }
      ]
    }
  }
})
</script>
