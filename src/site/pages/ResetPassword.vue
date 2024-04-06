<template>
    <LayoutGuest>
        <SectionFullScreen
            v-slot="{ cardClass }"
            bg="purplePink"
            >
            <CardBox
                :class="cardClass"
                is-form
                @submit.prevent="resetUserPassword"
            >
                <NotificationBarInCard 
                    v-if="status"
                    :color="notificationClass"
                    >
                    {{ status }}
                </NotificationBarInCard>

                <FormField
                    label="Password"
                    label-for="password"
                    help="Please enter the new pasword"
                >
                    <FormControl
                        v-model="user.password"
                        :icon="mdiAsterisk"
                        id="password"
                        type="password"
                        required
                    />
                </FormField>

                <FormField
                    label="Confirm Password"
                    label-for="password_confirmation"
                    help="Please re-enter the password"
                >
                    <FormControl
                        v-model="user.password_confirmation"
                        :icon="mdiAsterisk"
                        type="password"
                        id="password_confirmation"
                        required
                    />
                </FormField>

                <BaseDivider />

                <BaseLevel>
                    <BaseButtons>
                        <BaseButton
                        type="submit"
                        color="info"
                        label="Reset"
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
import { onMounted, ref, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import authApiRepository from "@/composables/authApiRepository"
import { mdiAccount, mdiAsterisk } from '@mdi/js'
import LayoutGuest from '@/components/LayoutGuest.vue'
import SectionFullScreen from '@/components/SectionFullScreen.vue'
import CardBox from '@/components/CardBox.vue'
import FormField from '@/components/FormField.vue'
import FormControl from '@/components/FormControl.vue'
import BaseDivider from '@/components/BaseDivider.vue'
import BaseButton from '@/components/BaseButton.vue'
import BaseButtons from '@/components/BaseButtons.vue'
import NotificationBarInCard from '@/components/NotificationBarInCard.vue'
import BaseLevel from '@/components/BaseLevel.vue'

// Imported Variables and Functions
const { resetPassword } = authApiRepository();
const route = useRoute();
const router = useRouter();

// Data Variables
const processing = ref(false);
const status = ref(null);
const notificationClass = ref('info');
const passwordRegex = /^(?=.*[A-Z])(?=.*\d).{6,}$/;
const user = ref({
    token: route.query.token ?? '',
    email: route.query.email ?? '',
    password: null,
    password_confirmation: null,
});

// Computed Properties
const isPasswordStrong = computed(() => {
    return passwordRegex.test(user.value.password);
});
const passwordLabel = computed(() => {
    return isPasswordStrong.value ? 'Good to go' : 'The password must have minimum six characters, at least one uppercase letter and one number';
});

// Functions
const resetUserPassword = () => {
    status.value = null;
    
    if(!isPasswordStrong.value) {
        notificationClass.value = 'warning';
        status.value = passwordLabel.value;
        return;
    }

    processing.value = true;
    resetPassword(user.value).then((response) => {
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