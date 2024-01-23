<template>
     <QuasarLayout>
        <p>User Roles</p>
        <!-- {{ users }} -->
      <!-- {{ roles }} -->
  <div class="overflow-x-auto">
    <table class="w-full table-auto rounded-xl border border-gray-300 bg-white text-left shadow-sm divide-y">
      <thead>
        <tr class="bg-gray-500/5">
          <th class="px-4 py-2">ID</th>
          <th>Name</th>
          <th>Email</th>
          <th>Roles</th>
          <th>Created At</th>
          <th class="px-4"></th>
        </tr>
      </thead>

      <tbody class="whitespace-nowrap divide-y">
        <tr v-for="user in users.data" :key="user.id">
          <td class="px-4 py-3">{{ user.id }}</td>
          <td>{{ user.name }}</td>
          <td>{{ user.email }}</td>
          <td>
            <span
              v-for="role in user.roles"
              :key="role.id"
              class="rounded-xl bg-blue-300 px-2 py-1 text-xs text-blue-700"
            >
              {{ role.name }}
            </span>
          </td>
          <td>{{ user.created_at }}</td>
          <td class="px-4">
            <q-btn color="primary" class="mr-2" label="Edit"  @click="editPromptModel(user)"/>
          </td>
        </tr>
        <tr v-if="users.length === 0">
          <td class="p-4" colspan="4">No Records</td>
        </tr>
      </tbody>
    </table>

  

    <div class="flex justify-center mt-4">
                <div class="flex gap-1">
                    <Link
                        v-for="(link, index) in users.links"
                        :key="index"
                        class="px-4 py-2 rounded-md"
                        :href="link.url || ''"
                        :class="{
                            'bg-indigo-500 dark:bg-indigo-800 text-gray-300':
                                link.active,
                        }"
                        v-html="link.label"
                    />
                </div>
            </div>
  </div>

  <q-dialog v-model="editPrompt" >
      <q-card>
        <q-card-section class="q-pa-md">
          <!-- Your edit form -->
          <form class="space-y-2" >
            <div class="text-base font-medium text-gray-700">Name: <span class="font-bold">{{ selectedUser.name }}</span></div>

            <div class="space-y-2" >
                <label class="block text-base font-medium text-gray-700" >Roles</label>
                <div class="space-x-2">
                    <div v-for="role in roles" :key="role.id" class="inline-flex space-x-1">
                        <!-- <q-checkbox
                        v-model="updateForm.roles"
                        :value="role.id"
                        label-class="text-sm font-medium text-gray-700"
                        @click="handleRoleClick(role.id)"
                        >
                        {{ role }}
                        </q-checkbox>
                        <q-select
                            filled
                            v-model="multiple"
                            multiple
                            :options="options"
                            label="Multiple"
                            style="width: 250px"
                        >{{ role }}</q-select> -->
                        <q-checkbox
                            v-model="selectedRoleId"
                            :val="role.id"
                            label-class="text-sm font-medium text-gray-700"
                            @click="selectRole(role)"
                        >
                            {{ role }}
                        </q-checkbox>
                    </div>
                    <p v-if="selectedRoleId">Selected Role ID: {{ selectedRoleId }}</p>

                </div>
            
            </div>

            <button class="rounded-md bg-gray-800 px-4 py-2 text-xs font-semibold text-white hover:bg-gray-700" type="submit">
            SAVE
            </button>
        </form>
        </q-card-section>
      </q-card>
    </q-dialog>

    </QuasarLayout>

</template>

<script setup>
import QuasarLayout from "@/Layouts/QuasarLayout.vue";
import {Link, useForm} from "@inertiajs/vue3"
import {ref, computed} from 'vue';

import { useQuasar } from "quasar";
const $q = useQuasar();

const props = defineProps({
    users: Object,
    roles:Object
})
const editPrompt = ref(false);
const selectedUser = ref(null);

const updateForm = useForm({
    roles : [],
    selectedUser: "",

})
const selectedRoleId = ref(null);

const selectRole = (roleId) => {
  selectedRoleId.value = roleId;
};


// const editPromptModel = (user) => {
//     selectedUser.value = user;
//     editPrompt.value = true;
//     console.log(user.name);
//     updateForm.roles = user.roles.map((role) => role.id);
//     updateForm.name = user.name;
//     updateForm.selectedUser = selectedUser.value.id;
// };

const editPromptModel = (user) => {
  selectedUser.value = user;
//   console.log(user.roles.map((role) => role.name));
  editPrompt.value = true;
//   console.log(user.roles);
  updateForm.roles = user.roles.map((role) => role.name);
//   console.log(updateForm.roles);
  updateForm.name = user.name;
  updateForm.selectedUser = selectedUser.value.id;
};

// const editPromptModel = (user) => {
//   // Toggle the selected state of the clicked role
//   const clickedRoleId = user.roles.map((role) => role.id);
//   updateForm.roles = updateForm.roles.includes(clickedRoleId)
//     ? updateForm.roles.filter((roleId) => roleId !== clickedRoleId)
//     : [...updateForm.roles, clickedRoleId];

//   // Rest of your code...
//   selectedUser.value = user;
//   editPrompt.value = true;
// //   updateForm.name = user.name;
//   updateForm.selectedUser = selectedUser.value.id;
// };
// When a role is clicked, update the selectedRoles array

const handleRoleClick = (roleId) => {
  // Ensure that updateForm.roles is an array
  updateForm.roles = Array.isArray(updateForm.roles) ? updateForm.roles : [];

  if (updateForm.roles.includes(roleId)) {
    // Role is already selected, remove it
    updateForm.roles = updateForm.roles.filter((id) => id !== roleId);
  } else {
    // Role is not selected, add it
    updateForm.roles.push(roleId);
  }
};


const onSubmit = () => {
    if (updateForm.roles !== "") {
        console.log(selectedUser.value);
        updateForm.post(route("updateUserRole", selectedUser.value));
        // updateForm.post(route("updateRole", edit.value));
        $q.notify({
            message: "Role succesfully Updated",
            color: "purple",
            position: "bottom",
            actions: [{ label: "Dismiss", color: "white" }],
        });
    } else if (updateForm.roles === "") {
        $q.notify({
            message: "Please Enter a Role",
            color: "red",
            position: "bottom",
            actions: [{ label: "Dismiss", color: "white" }],
        });
    }
};

</script>