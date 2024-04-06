<template>
    <LayoutGuest>
        <SectionFullScreen
            v-slot="{ cardClass }"
            bg="purplePink"
        >
            <CardBox
            :class="cardClass"
            is-form
            @submit.prevent="forgotPasswordLink"
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
    
                <BaseDivider />
    
                <BaseLevel>
                    <BaseButtons>
                        <BaseButton
                            type="submit"
                            color="info"
                            label="Reset Password"
                            :class="{ 'opacity-25': processing }"
                            :disabled="processing"
                        />
                    </BaseButtons>
                    <RouterLink :to="{ name: 'login' }" class="text-sm text-indigo-600 hover:text-indigo-500">Go Back to Login</RouterLink>
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
import SectionFullScreen from '@/components/SectionFullScreen.vue';
import CardBox from '@/components/CardBox.vue';
import FormField from '@/components/FormField.vue';
import FormControl from '@/components/FormControl.vue';
import BaseDivider from '@/components/BaseDivider.vue';
import BaseButton from '@/components/BaseButton.vue';
import BaseButtons from '@/components/BaseButtons.vue';
import NotificationBarInCard from '@/components/NotificationBarInCard.vue';
import BaseLevel from '@/components/BaseLevel.vue';



// Imported Variables and Functions
const { forgotPassword } = authApiRepository();
const router = useRouter();

// Data Variables
const processing = ref(false);
const status = ref(null);
const notificationClass = ref('info');
const user = ref({
    email: null,
});

// Functions
const forgotPasswordLink = () => {
    status.value = null;

    processing.value = true;
    forgotPassword(user.value).then((response) => {
        if(response.status == 200) {
            status.value = response.data.message;
            notificationClass.value = 'success';
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