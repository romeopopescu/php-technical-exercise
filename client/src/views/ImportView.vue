<script setup>
import { ref } from 'vue';
import { useMutation } from '../composables/useGraphql';
import ResultsSummary from '../components/ResultsSummary.vue';

const IMPORT_CSV = `
  mutation ImportCsv($filename: String!, $contentBase64: String!) {
    importCsv(filename: $filename, contentBase64: $contentBase64) {
      rowsRead
      created
      updated
      skipped
      skippedRows {
        line
        errors
      }
    }
  }
`;

const { mutate, loading, error } = useMutation(IMPORT_CSV);
const summary = ref(null);
const filename = ref('');

function readAsBase64(file) {
  return new Promise((resolve, reject) => {
    const reader = new FileReader();
    reader.onload = () => resolve(String(reader.result).split(',')[1]);
    reader.onerror = () => reject(reader.error);
    reader.readAsDataURL(file);
  });
}

async function onFileChange(event) {
  const file = event.target.files[0];
  if (!file) {
    return;
  }

  filename.value = file.name;
  summary.value = null;

  try {
    const contentBase64 = await readAsBase64(file);
    const data = await mutate({ filename: file.name, contentBase64 });
    summary.value = data.importCsv;
  } catch (e) {
    // The error ref is populated by useMutation and shown in the template.
  }
}
</script>

<template>
  <section>
    <h2>Import a staff CSV</h2>
    <p>Select an HR export to import staff into the user store.</p>
    <input type="file" accept=".csv,text/csv" @change="onFileChange" />

    <p v-if="loading">Importing {{ filename }}…</p>
    <p v-if="error" class="error">Import failed: {{ error.message }}</p>

    <ResultsSummary v-if="summary" :summary="summary" />
  </section>
</template>
