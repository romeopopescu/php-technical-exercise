<script setup>
import { ref, watch } from 'vue';
import { useMutation } from '../composables/useGraphql';

const props = defineProps({
  users: {
    type: Array,
    required: true,
  },
});

const UPDATE_USER = `
  mutation UpdateUser($id: Int!, $input: UpdateUserInput!) {
    updateUser(id: $id, input: $input) {
      id hrId firstName lastName email department isActive
    }
  }
`;

const { mutate, loading: saving, error: saveError } = useMutation(UPDATE_USER);

const localUsers = ref(props.users.map(u => ({ ...u })));
const editingId  = ref(null);
const editForm   = ref({});

watch(() => props.users, (users) => {
  localUsers.value = users.map(u => ({ ...u }));
});

function startEdit(user) {
  editingId.value = user.id;
  editForm.value = {
    firstName:  user.firstName,
    lastName:   user.lastName,
    email:      user.email,
    department: user.department,
    isActive:   user.isActive,
  };
}

function cancelEdit() {
  editingId.value = null;
  editForm.value  = {};
}

async function saveEdit(user) {
  try {
    const data    = await mutate({ id: user.id, input: { ...editForm.value } });
    const updated = data.updateUser;
    const idx     = localUsers.value.findIndex(u => u.id === user.id);
    if (idx !== -1) localUsers.value[idx] = updated;
    editingId.value = null;
  } catch (_) {
    // saveError ref is populated by useMutation
  }
}
</script>

<template>
  <div>
    <p v-if="saveError" class="error">{{ saveError.message }}</p>
    <table class="users">
      <thead>
        <tr>
          <th>HR ID</th>
          <th>First name</th>
          <th>Last name</th>
          <th>Email</th>
          <th>Department</th>
          <th>Active</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <template v-for="user in localUsers" :key="user.id">
          <tr v-if="editingId !== user.id">
            <td>{{ user.hrId }}</td>
            <td>{{ user.firstName }}</td>
            <td>{{ user.lastName }}</td>
            <td>{{ user.email }}</td>
            <td>{{ user.department }}</td>
            <td>{{ user.isActive ? 'Yes' : 'No' }}</td>
            <td><button @click="startEdit(user)">Edit</button></td>
          </tr>
          <tr v-else>
            <td>{{ user.hrId }}</td>
            <td><input v-model="editForm.firstName" /></td>
            <td><input v-model="editForm.lastName" /></td>
            <td><input v-model="editForm.email" /></td>
            <td><input v-model="editForm.department" /></td>
            <td><input type="checkbox" v-model="editForm.isActive" /></td>
            <td>
              <button @click="saveEdit(user)" :disabled="saving">Save</button>
              <button @click="cancelEdit">Cancel</button>
            </td>
          </tr>
        </template>
        <tr v-if="!localUsers.length">
          <td colspan="7">No users imported yet.</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
