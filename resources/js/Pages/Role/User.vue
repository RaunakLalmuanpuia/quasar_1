<template>
     <QuasarLayout>
      <div class="flex justify-between mt-4">
        <h3>User Roles</h3>
        <input v-model="search" type="text" placeholder="Search..." class="px-2 mr-2 border rounded-lg">
      </div>
       
        
      {{ roles }}
  <div class="mt-4 overflow-x-auto">
    
    <table class="w-full text-left bg-white border border-gray-300 divide-y shadow-sm table-auto rounded-xl">
      <thead>
        <tr class="bg-gray-500/5">
          <th class="px-4 py-2">ID</th>
          <th>Name</th>
          <th>Email</th>
          <th>Roles</th>
          <th>Permission</th>
          <!-- <th>Created At</th> -->
          <th class="px-4"></th>
        </tr>
      </thead>
      <!-- {{users.data  }} -->
      <tbody class="divide-y whitespace-nowrap">
        <tr v-for="user in users.data" :key="user.id">
          <td class="px-4 py-3">{{ user.id }}</td>
          <td>{{ user.name }}</td>
          <td>{{ user.email }}</td>
          <td>
            <span
              v-for="role in user.roles"
              :key="role.id"
              class="px-2 py-1 text-xs text-blue-700 bg-blue-300 rounded-xl"
            >
              {{ role.name }}
            </span>
          </td>
          <td>
            <span
              v-for="roles in user.roles"
              :key="roles.id"
              class="px-2 py-1 text-xs text-blue-700 bg-blue-300 rounded-xl"
            >
              {{ roles.permissions.name }}
            </span>
          </td>
          <!-- <td>
            <span
              v-for="role in user.roles"
              :key="role.id"
            >
              {{ role.created_at }}
            </span>
          </td> -->
          <!-- <td>{{ user.created_at }}</td> -->
          <td class="px-2">
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
                        :preserve-state="true"
                    />
                </div>
            </div>
  </div>

  <q-dialog v-model="editPrompt"  >
      <q-card>
        <q-card-section class="q-pa-md">
          <!-- Your edit form -->
          <form class="space-y-2" @submit.prevent="onSubmit">
            <div class="text-base font-medium text-gray-700">Name: <span class="font-bold">{{ selectedUser.name }}</span></div>

            <div class="space-y-2" >
                <label class="block text-base font-medium text-gray-700" >Roles</label>
                <div class="space-x-2">
                <q-checkbox
                v-for="role in options"
                :key="role.value"
                v-model="updateForm.roles"
                :val="role.label"
                :label="role.label"
              />
              <!-- <p>{{ updateForm.roles }}</p> -->
                </div>
                <!-- <q-checkbox v-model="updateForm.confirm" /> -->
                <q-toggle
                v-model="updateForm.confirm"
                  label="Confirm"
                />
              </div>

            <button class="px-4 py-2 text-xs font-semibold text-white bg-gray-800 rounded-md hover:bg-gray-700" type="submit">
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
import {Link, useForm, router} from "@inertiajs/vue3";
import { throttle, debounce } from "quasar";

import {ref, watch} from 'vue';


import { useQuasar } from "quasar";
const $q = useQuasar();

const props = defineProps({
    users: Object,
    roles:Object,
    filters: Object
})

let search = ref(props.filters.search);

const editPrompt = ref(false);
const selectedUser = ref(null);

const deleteUser = ref(false);

const options = Object.entries(props.roles).map(([roleId, roleName]) => ({
  label: roleName,
  value: roleId,
}));

const updateForm = useForm({
    roles : [],
    selectedUser: "",
    confirm: false

})

// For search filter
watch(search, debounce(function (value){
  console.log('triggerd');
  router.get('/usersRole', {search: value},{
    preserveState:true,
    replace: true
  });
}, 300));

const editPromptModel = (user) => {
    updateForm.roles = user.roles.map((role) =>role.name);
    selectedUser.value = user;
    editPrompt.value = true;
    updateForm.selectedUser = selectedUser.value.id;
};

const onSubmit = () => {
  console.log(updateForm.confirm);
    if (updateForm.confirm == true) {
        updateForm.post(route("updateUserRole", selectedUser.value));
        editPrompt.value = false;
        updateForm.roles = [];
        updateForm.selectedUser = "",
        updateForm.confirm == false;
        $q.notify({
            message: "Role succesfully Updated",
            color: "purple",
            position: "bottom",
            actions: [{ label: "Dismiss", color: "white" }],
        });
    } else if (updateForm.confirm == false) {
        $q.notify({
            message: "Please Confirm",
            color: "red",
            position: "bottom",
            actions: [{ label: "Dismiss", color: "white" }],
        });
    }
};
// const onSubmit = async () => {
//   try {
//     console.log(updateForm.confirm);

//     if (updateForm.confirm) {
//       await updateForm.post(route("updateUserRole", selectedUser.value));
//       editPrompt.value = false;
//       updateForm.roles = [];
//       updateForm.selectedUser = "";
//       updateForm.confirm = false;

//       $q.notify({
//         message: "Role successfully Updated",
//         color: "purple",
//         position: "bottom",
//         actions: [{ label: "Dismiss", color: "white" }],
//       });
//     } else {
//       $q.notify({
//         message: "Please Confirm",
//         color: "red",
//         position: "bottom",
//         actions: [{ label: "Dismiss", color: "white" }],
//       });
//     }
//   } catch (error) {
//     console.error("Error updating role:", error);

//     $q.notify({
//       message: "An error occurred while updating the role",
//       color: "red",
//       position: "bottom",
//       actions: [{ label: "Dismiss", color: "white" }],
//     });
//   }
// };

</script>