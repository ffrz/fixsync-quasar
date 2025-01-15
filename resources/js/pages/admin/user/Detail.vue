<script setup>
import { validateUsername, validateEmail } from "@/helpers/validations";
import { usePage } from "@inertiajs/vue3";

const page = usePage();
const title = "Rincian Pengguna";
</script>

<template>
  <i-head :title="title" />
  <authenticated-layout>
    <template #title>{{ title }}</template>
    <div class="row justify-center">
      <div class="col col-lg-6 q-pa-sm">
        <div class="row">
          <q-card square flat bordered class="col">
            <q-card-section>
              <div class="text-subtitle1 text-bold text-grey-9">Profil Pengguna</div>
              <table>
                <tr>
                  <td>ID Pengguna</td>
                  <td>:</td>
                  <td>{{ page.props.data.username }}</td>
                </tr>
                <tr>
                  <td>Nama Pengguna</td>
                  <td>:</td>
                  <td>{{ page.props.data.name }}</td>
                </tr>
                <tr>
                  <td>Email</td>
                  <td>:</td>
                  <td>{{ page.props.data.email }}</td>
                </tr>
                <tr>
                  <td>Hak Akses</td>
                  <td>:</td>
                  <td>{{ $CONSTANTS.USER_ROLES[page.props.data.role] }}</td>
                </tr>
              </table>
            </q-card-section>
          </q-card>
        </div>
      </div>
    </div>
  </authenticated-layout>
</template>
