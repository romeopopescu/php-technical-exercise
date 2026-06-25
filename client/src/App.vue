<script setup>
import { ref } from 'vue';
import ImportView from './views/ImportView.vue';
import UsersView from './views/UsersView.vue';

const view = ref('import');
</script>

<template>
  <div class="app">
    <header class="app__header">
      <h1>Staff User Importer</h1>
      <nav class="app__nav">
        <button :class="{ 'is-active': view === 'import' }" @click="view = 'import'">
          Import
        </button>
        <button :class="{ 'is-active': view === 'users' }" @click="view = 'users'">
          Users
        </button>
      </nav>
    </header>
    <main class="app__main">
      <ImportView v-if="view === 'import'" />
      <UsersView v-else />
    </main>
  </div>
</template>
