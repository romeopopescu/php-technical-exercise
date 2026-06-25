<script setup>
defineProps({
  summary: {
    type: Object,
    required: true,
  },
});
</script>

<template>
  <div class="summary">
    <h3>Import complete</h3>
    <ul class="summary__counts">
      <li>Rows read: {{ summary.rowsRead }}</li>
      <li>Created: {{ summary.created }}</li>
      <li>Updated: {{ summary.updated }}</li>
      <li>Skipped: {{ summary.skipped }}</li>
    </ul>

    <div v-if="summary.skippedRows.length">
      <h4>Skipped rows</h4>
      <table>
        <thead>
          <tr>
            <th>Line</th>
            <th>Reasons</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="row in summary.skippedRows" :key="row.line">
            <td>{{ row.line }}</td>
            <td>{{ row.errors.join(', ') }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
