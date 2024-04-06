<template>
  <LayoutGuest>

    <SectionFullScreen
      v-slot="{ cardClass }"
      bg="purplePink"
    >
      <CardBox
        :class="cardClass"
        is-form
        @submit.prevent="proceedLogin"
      >

        <NotificationBarInCard 
          v-if="status"
          :color="notificationClass"
        >
          {{ status }}
        </NotificationBarInCard>

        <FormField
          label="Email"
          label-for="email"
          help="Please enter your email"
        >
          <FormControl
            v-model="user.email"
            :icon="mdiAccount"
            id="email"
            autocomplete="email"
            type="email"
            required
          />
        </FormField>

        <FormField
          label="Password"
          label-for="password"
          help="Please enter your password"
        >
          <FormControl
            v-model="user.password"
            :icon="mdiAsterisk"
            type="password"
            id="password"
            autocomplete="current-password"
            required
          />
        </FormField>

        <BaseDivider />

        <BaseLevel>
          <BaseButtons>
            <BaseButton
              type="submit"
              color="info"
              label="Login"
              :class="{ 'opacity-25': processing }"
              :disabled="processing"
            />
          </BaseButtons>
          <RouterLink :to="{ name: 'forgot.password' }" class="text-sm text-indigo-600 hover:text-indigo-500">Forgot Password?</RouterLink>
        </BaseLevel>
      </CardBox>
    </SectionFullScreen>
  </LayoutGuest>
</template>


<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import authApiRepository from "@/composables/authApiRepository";
import { mdiAccount, mdiAsterisk } from '@mdi/js';
import LayoutGuest from '@/components/LayoutGuest.vue';
import SectionFullScreen from '@/components/SectionFullScreen.vue'
import CardBox from '@/components/CardBox.vue';
import FormField from '@/components/FormField.vue';
import FormControl from '@/components/FormControl.vue';
import BaseDivider from '@/components/BaseDivider.vue';
import BaseButton from '@/components/BaseButton.vue';
import BaseButtons from '@/components/BaseButtons.vue';
import NotificationBarInCard from '@/components/NotificationBarInCard.vue'
import BaseLevel from '@/components/BaseLevel.vue';
import CommonFunction from '@/composables/CommonFunction.js';

// Imported Variables and Functions
const { login } = authApiRepository();
const router = useRouter();
const { fireToaster } = CommonFunction();

// Data Variables
const processing = ref(false);
const status = ref(null);
const notificationClass = ref('info');
const user = ref({
    email: null,
    password: null,
});

// Functions
const proceedLogin = () => {
    status.value = null;
    
    if(!user.value.email || !user.value.password) {
        notificationClass.value = 'warning';
        status.value = 'Email and password are required';
        return;
    }

    processing.value = true;
    login(user.value).then((response) => {
      if(response.status == 200) {
        fireToaster(response.data.message, 'success');
        status.value = response.data.message;
        notificationClass.value = 'success';
        localStorage.setItem("token", response.data.result.token);
        router.push({ name: 'dashboard' });
      } else {
        status.value = response.data.message;
        notificationClass.value = 'warning';
      }

    }).catch((errors) => {
        console.log(errors);
    }).finally(() => {
        processing.value = false;
    })
}

</script>