<template>
    <QuasarLayout>
        <!-- {{permissions}} -->
        <!-- <p>Permission</p> -->
        <h4>Permissions</h4>
        <div class="flex mb-4">
            <!-- create a dialog -->
            <q-btn label="Create Permission" color="primary" @click="creatPrompt()" />

        </div>
        <table>
            <thead>
                <th>ID</th>
                <th>Name</th>
                <!-- <th>Roles</th> -->
                <th>Created at</th>
                <th>Actions</th>
            </thead>
            <tbody>
                <tr v-for="(permission, index) in permissions" :key="permission.id">
                <td>{{ index+1 }}</td>
                <td>{{ permission.name }}</td>
                <!-- <tr v-for="roles in permissions.roles" :key="roles.id">
                    <td>{{ roles.name }}</td>
                </tr> -->
                
                <td>{{ permission.created_at }}</td>
                <td class="divide-x-2">
                    <q-btn color="primary" label="Edit" @click="editPromptModel(permission)"></q-btn>
                    <q-btn color="primary" label="Delete" @click="destroy(permission.id)"></q-btn>
                </td>
                </tr>
            </tbody>
        </table>
        
             <!-- Create Dialog -->
        <q-dialog v-model="prompt" persistent>
            <q-card style="min-width: 350px">
                <q-card-section>
                <div class="text-h6">Create Permission</div>
                </q-card-section>
                <q-input v-model="form.name" label="Permission" />
                <label class="block text-base font-medium text-gray-700" >Roles</label>
                <q-checkbox
                v-for="role in options"
                :key="role.value"
                v-model="form.roles"
                :val="role.label"
                :label="role.label"
              />
                
                <q-card-actions align="right" class="text-primary">
                <q-btn flat label="Cancel" v-close-popup />
                <q-btn flat label="Confirm" v-close-popup  @click="onSubmit()"/>
                </q-card-actions>
            </q-card>
            </q-dialog>

         <!-- edit dialog -->
         <q-dialog v-model="editPrompt" persistent>
            <q-card style="min-width: 350px">
                <q-card-section>
                <div class="text-h6">Edit Permission</div>
                </q-card-section>
                <q-input v-model="updateForm.name" label="Standard" />
                
             
                <label class="block text-base font-medium text-gray-700" >Roles</label>
                
                <q-checkbox
                v-for="role in options"
                :key="role.value"
                v-model="updateForm.roles"
                :val="role.label"
                :label="role.label"
              />
        
                
                <q-card-actions align="right" class="text-primary">
                <q-btn flat label="Cancel" v-close-popup />
                <q-btn flat label="Confirm" v-close-popup  @click="onUpdate()"/>
                </q-card-actions>
            </q-card>
            </q-dialog>
    </QuasarLayout>
    
</template>


<script setup>
import QuasarLayout from '@/Layouts/QuasarLayout.vue';
import {ref} from 'vue';
import {useForm} from "@inertiajs/vue3"
import {useQuasar} from "quasar"
const $q = useQuasar();

const prompt = ref(false);
const editPrompt = ref(false);
const selectedPermission= ref(null);

const props = defineProps({
    permissions: Object,
    roles : Object,
})

const form = useForm({
    name:"",
    roles: [],
})
const updateForm = useForm({
    name: '',
    selectedPermission: " ",
    roles: [],
})

const options = Object.entries(props.roles).map(([roleId, roleName]) => ({
  label: roleName,
  value: roleId,
}));

const creatPrompt = () => {
    prompt.value = true
}

const editPromptModel = (permission) => {
    editPrompt.value = true;
    selectedPermission.value = permission;
    updateForm.name = permission.name;
    updateForm.selectedPermission = selectedPermission.value.id;
    updateForm.roles = permission.roles.map((role) => role.name);
}

const onSubmit = () => {
    if(form.name !== ""){
        form.post(route("createPermission"),{
            onSuccess:() =>form.reset(['name', 'roles'])
        });
        $q.notify({
            message: "Permission Created Successfully",
            color: "purple",
            position: "bottom",
            actions: [{label: "Dismiss", color: "white"}],
        });
    }else if(form.name === "")
    {
        $q.notify({
            message: "Please Enter a Permission",
            color: "red",
            position: "bottom",
            actions: [{ label: "Dismiss", color: "white" }],
        })
    }
}

const onUpdate = () => {
    if(updateForm.name !== ""){
        console.log(selectedPermission.value);
        updateForm.put(route("updatePermission", selectedPermission.value),{
            onSuccess:() =>form.reset(['name','selectedPermission', 'roles'])
        })
        $q.notify({
            message: "Permission updated Sucessfully",
            color: "positive",
            position: "bottom",
            actions: [{label:"Dismiss", color: "white"}],
        });
    }else if(updateForm.name === ""){
        $q.notify({
            message: "Please Enter a Role",
            color: "red",
            position: "bottom",
            actions: [{ label: "Dismiss", color: "white" }],
        })
    }
}

function destroy(id) {
    if(confirm("DeleteRole")) {
        form.delete(route("destroyPermission", id))
    }
}
</script>