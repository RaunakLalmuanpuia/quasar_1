<template>
    <QuasarLayout>
        <h5>Apply for roles</h5>

        <!-- <h5>Roles:</h5>
        <div v-for="role in props.role" :key="role.id">
            {{ role.name }}
        </div> -->

        <label for="selectRole">Select a role:</label>
        <select v-model="selectedRole" id="selectRole">
            <option
                v-for="role in props.role"
                :key="role.id"
                :value="role.name"
            >
                {{ role.name }}
            </option>
        </select>

        <div v-if="selectedRole === 'employee'">
            <!-- Form for Role 1 -->
            <form @submit.prevent="submitForm">
                <!-- Form fields for Role 1 -->
                <!-- ... -->
                <button type="submit">Submit Role Employee</button>
            </form>
        </div>

        <div v-if="selectedRole === 'employer'">
            <!-- Form for Role 2 -->
            <form @submit.prevent="submitForm">
                <!-- Form fields for Role 2 -->
                <!-- ... -->
                <button type="submit">Submit Role Employer</button>
            </form>
        </div>
        <div v-if="selectedRole === 'manager'">
            <!-- Form for Role 2 -->
            <form @submit.prevent="submitForm">
                <!-- Form fields for Role 2 -->
                <!-- ... -->
                <button type="submit">Submit Role Manager</button>
            </form>
        </div>
    </QuasarLayout>
</template>
<script setup>
import QuasarLayout from "@/Layouts/QuasarLayout.vue";
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps({
    role: Object,
});
const form = useForm({
    name: "",
    post: "",
    id: "",
    post_name: "",
})
const selectedRole = ref("");

const submitForm = () => {
    form.post_name = selectedRole.value;
    form.post(route('users.store'));
    console.log("Employee form submitted");
};
</script>
