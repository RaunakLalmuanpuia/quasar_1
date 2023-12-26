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
            <form @submit.prevent="EmployeeForm">
                <!-- Form fields for Role 1 -->
                <!-- ... -->
                <button type="submit">Submit Role Employee</button>
            </form>
        </div>

        <div v-if="selectedRole === 'employer'">
            <!-- Form for Role 2 -->
            <form @submit.prevent="EmployerForm">
                <!-- Form fields for Role 2 -->
                <!-- ... -->
                <button type="submit">Submit Role Employer</button>
            </form>
        </div>
        <div v-if="selectedRole === 'manager'">
            <!-- Form for Role 2 -->
            <form @submit.prevent="ManagerForm">
                <!-- Form fields for Role 2 -->
                <!-- ... -->
                <button type="submit">Submit Role Manager</button>
            </form>
        </div>
    </QuasarLayout>
</template>
<script setup>
import QuasarLayout from "@/Layouts/QuasarLayout.vue";
import { ref } from "vue";

const props = defineProps({
    role: Object,
});

const selectedRole = ref("");

const EmployeeForm = () => {
    // Handle form submission for Role 1
    console.log("Employee form submitted");
};

const EmployerForm = () => {
    // Handle form submission for Role 2
    console.log("Employer form submitted");
};
const ManagerForm = () => {
    // Handle form submission for Role 2
    console.log("Manager form submitted");
};
</script>
