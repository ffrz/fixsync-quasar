
<script setup>
import { defineComponent, ref, watch } from "vue";
import { router, usePage } from "@inertiajs/vue3";

// const tab = ref();
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
      <q-toolbar>
        <q-btn flat dense round aria-label="Menu" @click="toggleLeftDrawer">
          <q-icon name="menu"/>
        </q-btn>
        <q-toolbar-title>{{ $config.APP_NAME }}</q-toolbar-title>
      </q-toolbar>
    </q-header>

    <q-drawer :breakpoint="768" v-model="leftDrawerOpen" show-if-above style="border-right: 1px solid #ddd">

      <q-img class="absolute-top" style="height: 80px;border-bottom: 1px solid #ddd;background-color: #eee;">
        <div class="absolute-middle bg-transparent">
          <div class="text-black text-weight-bold">{{ page.props.auth.user.company_name }}</div>
          <div><my-link class="text-black" :href="route('admin.profile.edit')" :label="page.props.auth.user.name" /></div>
        </div>
      </q-img>
      <!-- <q-toolbar class="absolute-top" style="height: 50px;background:#202020;color:#fff;">
        <q-toolbar-title style="font-size:16px;">{{ user.company_name }}</q-toolbar-title>
      </q-toolbar> -->
      <q-scroll-area style="height: calc(100% - 80px); margin-top: 80px;">
        <q-list id="main-nav" style="margin-bottom: 50px;">
          <q-item clickable v-ripple :active="$page.url == '/admin/dashboard'" @click="router.get(route('admin.dashboard'))">
            <q-item-section avatar>
              <q-icon name="dashboard" />
            </q-item-section>
            <q-item-section>
              <q-item-label>Dashboard</q-item-label>
            </q-item-section>
          </q-item>
          <q-item clickable v-ripple :active="$page.url.startsWith('/admin/customers')" @click="router.get(route('admin.customer.index'))">
            <q-item-section avatar>
              <q-icon name="groups" />
            </q-item-section>
            <q-item-section>
              <q-item-label>Pelanggan</q-item-label>
            </q-item-section>
          </q-item>
          <!-- <q-expansion-item expand-separator icon="people" label="Customers">
            <q-expansion-item class="subnav" expand-separator icon="receipt" label="Receipts">
              <q-expansion-item class="subnav" expand-separator label="Today">
                <q-item class="subnav" clickable v-ripple>Example Link 1</q-item>
              </q-expansion-item>
              <q-expansion-item class="subnav" expand-separator label="Yesterday">
                <q-list>
                  <q-item class="subnav" clickable v-ripple>Example Link 1</q-item>
                  <q-item class="subnav" clickable v-ripple>Example Link 2</q-item>
                </q-list>
              </q-expansion-item>
            </q-expansion-item>
            <q-expansion-item expand-separator icon="schedule" label="Postponed">
              <q-card>
                <q-card-section>
                  Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quidem, eius reprehenderit eos corrupti
                  commodi magni quaerat ex numquam, dolorum officiis modi facere maiores architecto suscipit iste
                  eveniet doloribus ullam aliquid.
                </q-card-section>
              </q-card>
            </q-expansion-item>
          </q-expansion-item> -->
          <q-expansion-item expand-separator icon="settings" label="Pengaturan" :default-opened="$page.url.startsWith('/admin/settings')">
            <q-item class="subnav" clickable v-ripple :active="$page.url.startsWith('/admin/settings/users')" @click="router.get(route('admin.user.index'))">
              <q-item-section avatar>
                <q-icon name="group" />
              </q-item-section>
              <q-item-section>
                <q-item-label>Pengguna</q-item-label>
              </q-item-section>
            </q-item>
            <q-item class="subnav" clickable v-ripple :active="$page.url.startsWith('/admin/settings/profile')" @click="router.get(route('admin.profile.edit'))">
              <q-item-section avatar>
                <q-icon name="manage_accounts" />
              </q-item-section>
              <q-item-section>
                <q-item-label>Profil</q-item-label>
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
          <div class="absolute-bottom text-grey-6 q-pa-md">&copy; 2024 - {{ $config.APP_NAME + ' v' + $config.APP_VERSION_STR }}</div>
        </q-list>
      </q-scroll-area>
    </q-drawer>
    <q-page-container style="background:#f8f8f8;">
      <slot></slot>
    </q-page-container>
  </q-layout>
</template>
