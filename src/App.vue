<template>
    <div>
        <Login v-if="$route.name == 'login'">
            <router-view/>
        </Login>

        <ForgotPassword v-else-if="$route.name == 'forgot.password'">
            <router-view/>
        </ForgotPassword>

        <ResetPassword v-else-if="$route.name == 'reset.password'">
            <router-view/>
        </ResetPassword>

        <Dashboard v-else-if="route.name == 'dashboard'">
            <router-view/>
        </Dashboard>
    </div>
  
</template>

<script setup>
import Login from '@/site/pages/Login.vue';
import Dashboard from '@/site/pages/Dashboard.vue';
import ForgotPassword from '@/site/pages/ForgotPassword.vue';
import ResetPassword from '@/site/pages/ResetPassword.vue';
import { useRoute } from "vue-router";

const route = useRoute();

</script>

