<script setup>
defineProps({
  users: {
    type: Array,
    required: true,
  },
});
</script>

<template>
  <table class="users">
    <thead>
      <tr>
        <th>HR ID</th>
        <th>First name</th>
        <th>Last name</th>
        <th>Email</th>
        <th>Department</th>
        <th>Active</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="user in users" :key="user.id">
        <td>{{ user.hrId }}</td>
        <td>{{ user.firstName }}</td>
        <td>{{ user.lastName }}</td>
        <td>{{ user.email }}</td>
        <td>{{ user.department }}</td>
        <td>{{ user.isActive ? 'Yes' : 'No' }}</td>
      </tr>
      <tr v-if="!users.length">
        <td colspan="6">No users imported yet.</td>
      </tr>
    </tbody>
  </table>
</template>
