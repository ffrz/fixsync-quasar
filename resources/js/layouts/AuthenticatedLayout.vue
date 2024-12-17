<script setup>
import { defineComponent, ref } from "vue";
import { router, usePage } from "@inertiajs/vue3";

const page = usePage();

defineComponent({
  name: "AuthenticatedLayout",
});

const leftDrawerOpen = ref(false);

function toggleLeftDrawer() {
  leftDrawerOpen.value = !leftDrawerOpen.value;
}

</script>

<template>
  <q-layout view="lHh LpR lFf">
    <q-header>
      <q-toolbar class="bg-grey-1 text-black">
        <q-btn flat dense round aria-label="Menu" @click="toggleLeftDrawer">
          <q-icon name="menu" />
        </q-btn>
        <q-toolbar-title>
          <slot name="title">{{ $config.APP_NAME }}</slot>
        </q-toolbar-title>
      </q-toolbar>
    </q-header>
    <q-drawer :breakpoint="768" v-model="leftDrawerOpen" show-if-above style="border-right: 1px solid #ddd">
      <q-img class="absolute-top" style="height: 80px;border-bottom: 1px solid #ddd;border-right: 1px solid #ddd">
        <div class="absolute-middle bg-transparent">
          <div><my-link class="text-black text-weight-bold" :href="route('admin.company-profile.edit')"
              :label="page.props.auth.user.company_name" /></div>
          <div><my-link class="text-black" :href="route('admin.profile.edit')" :label="page.props.auth.user.name" />
          </div>
        </div>
      </q-img>
      <q-scroll-area style="height: calc(100% - 80px); margin-top: 80px;">
        <q-list id="main-nav" style="margin-bottom: 50px;">
          <q-item clickable v-ripple :active="$page.url == '/admin/dashboard'"
            @click="router.get(route('admin.dashboard'))">
            <q-item-section avatar>
              <q-icon name="dashboard" />
            </q-item-section>
            <q-item-section>
              <q-item-label>Dashboard</q-item-label>
            </q-item-section>
          </q-item>
          <q-item clickable v-ripple :active="$page.url.startsWith('/admin/service-orders')"
            @click="router.get(route('admin.service-order.index'))">
            <q-item-section avatar>
              <q-icon name="construction" />
            </q-item-section>
            <q-item-section>
              <q-item-label>Order Servis</q-item-label>
            </q-item-section>
          </q-item>
          <q-item clickable v-ripple :active="$page.url.startsWith('/admin/customers')"
            @click="router.get(route('admin.customer.index'))">
            <q-item-section avatar>
              <q-icon name="groups" />
            </q-item-section>
            <q-item-section>
              <q-item-label>Pelanggan</q-item-label>
            </q-item-section>
          </q-item>
          <q-expansion-item expand-separator icon="settings" label="Pengaturan"
            :default-opened="$page.url.startsWith('/admin/settings')">
            <q-item v-if="$page.props.auth.user.role == 'admin'" class="subnav" clickable v-ripple
              :active="$page.url.startsWith('/admin/settings/users')" @click="router.get(route('admin.user.index'))">
              <q-item-section avatar>
                <q-icon name="group" />
              </q-item-section>
              <q-item-section>
                <q-item-label>Pengguna</q-item-label>
              </q-item-section>
            </q-item>
            <q-item class="subnav" clickable v-ripple :active="$page.url.startsWith('/admin/settings/profile')"
              @click="router.get(route('admin.profile.edit'))">
              <q-item-section avatar>
                <q-icon name="manage_accounts" />
              </q-item-section>
              <q-item-section>
                <q-item-label>Profil Saya</q-item-label>
              </q-item-section>
            </q-item>
            <q-item v-if="$page.props.auth.user.role == 'admin'" class="subnav" clickable v-ripple
              :active="$page.url.startsWith('/admin/settings/company-profile')"
              @click="router.get(route('admin.company-profile.edit'))">
              <q-item-section avatar>
                <q-icon name="home_work" />
              </q-item-section>
              <q-item-section>
                <q-item-label>Profil Perusahaan</q-item-label>
              </q-item-section>
            </q-item>
          </q-expansion-item>
          <q-item clickable v-ripple @click="router.post(route('logout'))">
            <q-item-section avatar>
              <q-icon name="logout" />
            </q-item-section>
            <q-item-section>
              <q-item-label>Logout</q-item-label>
            </q-item-section>
          </q-item>
          <div class="absolute-bottom text-grey-6 q-pa-md">&copy; 2024 - {{ $config.APP_NAME + ' v' +
            $config.APP_VERSION_STR }}</div>
        </q-list>
      </q-scroll-area>
    </q-drawer>
    <q-page-container class="bg-grey-1">
      <q-page>
        <slot></slot>
      </q-page>
    </q-page-container>
    <slot name="footer"></slot>
  </q-layout>
</template>
