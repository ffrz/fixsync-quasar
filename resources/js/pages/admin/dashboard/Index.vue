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
    <div class="q-ma-md">
      <summary-card icon_postition="left" />
      <chart-card />
      <div class="row q-my-sm">
        <q-card class="col" square flat bordered>
          <q-card-section>
            <div class="q-mb-md text-h6">Selamat Datang, {{ $page.props.auth.user.name }}</div>
            <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Autem soluta, accusamus placeat eius eligendi
              perferendis quisquam veritatis iste debitis laboriosam facere et aliquam quos beatae illo distinctio rerum
              eos!
              Expedita, voluptatem. Aut nihil deleniti magni fuga eius labore aliquam nulla quod ullam sunt amet eum
              asperiores, harum unde eligendi tempore sapiente. Placeat sed, dignissimos voluptatum perspiciatis saepe
              aspernatur, maxime est, ipsam quas nobis cumque sunt labore molestias? Amet in sed ullam recusandae,
              nostrum
              dicta facilis blanditiis incidunt quae commodi soluta id impedit vel fugit illo, molestias reiciendis,
              provident
              nam molestiae! Ipsam, hic. Facere aut quisquam praesentium enim illum, pariatur nam.</p>
            <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Suscipit aspernatur maxime consectetur,
              consequatur
              excepturi facilis praesentium rem placeat sapiente, maiores doloribus modi voluptate. Unde autem dolor
              officiis
              numquam incidunt quasi aspernatur nam dolores ratione quidem recusandae, nulla qui provident molestias
              odit
              nemo
              praesentium quas voluptatem aliquid perferendis, iste veritatis vitae. Dolor, eligendi quidem distinctio
              asperiores doloremque culpa corporis quibusdam earum ex laudantium iure officia ullam nostrum maxime
              voluptatum
              laboriosam impedit labore illum. Culpa, veniam dolor odio ad eius nisi, aspernatur quia veritatis
              blanditiis
              repellat id minima deserunt beatae?</p>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Eaque, cum nulla. Ut eligendi tenetur aliquam
              cum
              mollitia repellat voluptas, fugiat doloremque sunt commodi enim excepturi distinctio id sapiente incidunt
              ipsam
              asperiores cupiditate, labore libero! Ad facere magni cumque perspiciatis, adipisci possimus pariatur quo
              nobis
              minus, est enim similique quaerat vel asperiores ipsam harum et, totam fugit.</p>
            <div>
              <btn-link label="Logout" :url="route('admin.auth.logout')" method="post" color="accent" icon="logout" />
            </div>
          </q-card-section>
        </q-card>
      </div>
    </div>
  </authenticated-layout>
</template>
