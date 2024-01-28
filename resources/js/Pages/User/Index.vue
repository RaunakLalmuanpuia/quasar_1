<template>
    <QuasarLayout>
        <div
            v-if="$page.props.flash.message"
            class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800"
            role="alert"
        >
            <span class="font-medium">
                {{ $page.props.flash.message }}
            </span>
        </div>

        <div class="flex justify-between mt-4">
        <h3>Create Users and edit users</h3>
        <div class="flex mb-4 mr-5">
                <!-- create a dialog -->
                <q-btn label="Create Users" color="primary" @click="creatPrompt()" />

            </div>
      </div>
       
        <div class="overflow-x-auto mt-4">
        
        <table class="w-full table-auto rounded-xl border border-gray-300 bg-white text-left shadow-sm divide-y">
        <thead>
            <tr class="bg-gray-500/5">
            <th class="px-4 py-2">ID</th>
            <th>Name</th>
            <th>Email</th>
            
            <!-- <th>Created At</th> -->
            <th class="px-4"></th>
            </tr>
        </thead>
        <!-- {{users.data  }} -->
        <tbody class="whitespace-nowrap divide-y">
            <tr v-for="(user,index) in users.data" :key="user.id">
            <td class="px-4 py-3">{{ index+1 }}</td>
            <td>{{ user.name }}</td>
            <td>{{ user.email }}</td>
            
            <td class="px-2">
                <q-btn color="primary" class="mr-2" label="Edit"  @click="editPromptModel(user)"/>
            </td>
            <td class="px-2">
                <q-btn color="primary" class="mr-2" label="Delete"  @click="onDelete(user)"/>
                <!-- <q-btn label="Delete" color="primary" @click="destroy(role.id)" /> -->
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

    <!-- Create User Dialog -->
    <q-dialog v-model="prompt"  >
        <q-card>
            <q-card-section class="q-pa-md">
            <!-- Your edit form -->
            <form class="space-y-2" @submit.prevent="onSubmit">
                <div class="text-base font-bold text-gray-700">Create New User</div>

                <div class="space-y-2" >
                    <!-- <label class="block text-base font-medium text-gray-700" >Create User</label> -->
                    <div class="space-x-2">
                        <q-input
                            filled
                            v-model="createForm.name"
                            label="User name"
                            hint="Name and surname"
                            lazy-rules
                            :rules="[ val => val && val.length > 0 || 'Please type something']"
                        />
                        <q-input
                            filled
                            v-model="createForm.email"
                            label="Email"
                            lazy-rules
                            :rules="[ (val, rules) => rules.email(val) || 'Please enter a valid email address' ]"
                        />
                        
                        <q-select
                            v-model="createForm.role"
                            filled
                            multiple
                            label="Select Roles"
                            :options="options"
                            option-value="label"
                            option-label="label"
                            class="mb-4"
                        />
                        
                        <q-input v-model="createForm.password" 
                        filled :type="isPwd ? 'password' : 'text'" 
                        hint="Password with toggle">
                            <template v-slot:append>
                            <q-icon
                                :name="isPwd ? 'visibility_off' : 'visibility'"
                                class="cursor-pointer"
                                @click="isPwd = !isPwd"
                            />
                            </template>
                        </q-input>

                    </div>
                    
                    <q-toggle
                    v-model="createForm.confirm"
                    label="Confirm"
                    />
                </div>

                <button class="rounded-md bg-gray-800 px-4 py-2 text-xs font-semibold text-white hover:bg-gray-700" type="submit">
                SAVE
                </button>
            </form>
            </q-card-section>
        </q-card>
        </q-dialog>

    <!-- Edit Dialog -->
    <q-dialog v-model="editPrompt"  >
        <q-card>
            <q-card-section class="q-pa-md">
            <!-- Your edit form -->
            <form class="space-y-2" @submit.prevent="onUpdate">
                <div class="text-base font-medium text-gray-700">Name: <span class="font-bold">{{userName}}</span></div>

                <div class="space-y-2" >
                    <label class="block text-base font-medium text-gray-700" >Update User</label>
                    <div class="space-x-2">
                        <q-input
                            filled
                            v-model="editForm.name"
                            label="User name"
                            hint="Name and surname"
                            lazy-rules
                            :rules="[ val => val && val.length > 0 || 'Please type something']"
                        />
                        <q-input
                            filled
                            v-model="editForm.email"
                            label="Email"
                            lazy-rules
                            :rules="[ (val, rules) => rules.email(val) || 'Please enter a valid email address' ]"
                        />
                        
                        <q-select
                            v-model="editForm.role"
                            filled
                            multiple
                            label="Update Roles"
                            :options="options"
                            option-value="label"
                            option-label="label"
                            class="mb-4"
                        />
                        
                        <q-input v-model="editForm.password" 
                        filled :type="isPwd ? 'password' : 'text'" 
                        hint="Password with toggle">
                            <template v-slot:append>
                            <q-icon
                                :name="isPwd ? 'visibility_off' : 'visibility'"
                                class="cursor-pointer"
                                @click="isPwd = !isPwd"
                            />
                            </template>
                        </q-input>

                    </div>
                    
                    <q-toggle
                    v-model="editForm.confirm"
                    label="Confirm"
                    />
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
import {ref} from 'vue';
import {Link, useForm, router} from "@inertiajs/vue3";
import { useQuasar } from "quasar";
const $q = useQuasar();

const props = defineProps({
    users: Object,
    roles: Object
})
// const form = useForm()

const createForm = useForm({
    name: '',
    email: '',
    password: '',
    role: [],
    confirm: false
})
// const updateForm = useForm({
//     name: '',
//     email: '',
//     password: '',
//     role: [],
//     confirm: false
// })
const editForm = useForm({
    name: '',
    email: '',
    password: '',
    role: [],
    confirm: false
})
const isPwd = ref(true);
const prompt = ref(false);
const editPrompt = ref(false);
const userName = ref("");
const selectedUser = ref(null);

const options = Object.entries(props.roles).map(([roleId, roleName]) => ({
  label: roleName,
  value: roleId,
}));

const creatPrompt = () => {
    console.log("Create Prompt")
    prompt.value = true;
};


const onSubmit = () => {
    if (createForm.confirm == true) {
        createForm.post(route("users.store"));
        prompt.value = false;
        createForm.role = [];
        $q.notify({
            message: "User succesfully Created",
            color: "purple",
            position: "bottom",
            actions: [{ label: "Dismiss", color: "white" }],
        });
    } else if (createForm.confirm == false) {
        $q.notify({
            message: "Please Confirm",
            color: "red",
            position: "bottom",
            actions: [{ label: "Dismiss", color: "white" }],
        });
    }
 
}


const editPromptModel = (user) => {
    console.log("Edit Prompt")
    editPrompt.value = true;
    selectedUser.value = user;
    userName.value = user.name;
    editForm.name = user.name;
    editForm.email = user.email;
    editForm.role = user.roles.map((role) =>role.name);
};

const onUpdate =(user) =>{
    console.log("Update User");
    console.log(user);
    if (editForm.confirm == true) {
        editForm.patch(route("users.update", selectedUser.value));
        editPrompt.value = false;
        editForm.role = [];
        $q.notify({
            message: "User succesfully Updated",
            color: "purple",
            position: "bottom",
            actions: [{ label: "Dismiss", color: "white" }],
        });
    } else if (editForm.confirm == false) {
        $q.notify({
            message: "Please Confirm",
            color: "red",
            position: "bottom",
            actions: [{ label: "Dismiss", color: "white" }],
        });
    }
}
const onDelete = (id) => {
    console.log("Delete User");
    if (confirm("Delete User")) {
        router.delete(route("users.destroy", id));
    }
}
</script>