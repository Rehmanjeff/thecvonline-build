<template>
    <LayoutAuthenticated>
        <div class="flex flex-col items-center justify-center p-4 h-44">
            <input ref="fileInput" type="file" @change="handleFileChange" accept=".xlsx, .xls, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" class="hidden">
            <button @click="$refs.fileInput.click()" class="px-4 py-2 text-sm text-white bg-indigo-600 rounded-md hover:bg-indigo-900">Upload CSV</button>
            <span v-if="fileName" class="mt-2 text-sm text-slate-800">{{ fileName }}</span>
        </div>
        <div class="flex justify-end mb-2 space-x-4 me-2">
            <input v-model="filters.requirement" type="text" placeholder="Filter by Requirement" class="px-4 py-2 border border-gray-300 rounded-md text-slate-800 focus:outline-none focus:border-blue-500">
            <input v-model="filters.city" type="text" placeholder="Filter by City" class="px-4 py-2 border border-gray-300 rounded-md text-slate-800 focus:outline-none focus:border-blue-500">
        </div>
        <hr class="w-full border-t-2 border-blue-500"/>
        <div class="flex flex-row justify-center p-10 rounded-lg shadow-md gap-44">
            <div class="flex flex-col items-center justify-center gap-2 mx-10">
                <div class="text-4xl font-bold text-blue-500">{{ displayedEmails }}</div>
                <hr class="w-full border-t-2 border-blue-500"/>
                <div class="text-xl text-blue-500">Total Emails</div>
            </div>
            <div class="flex flex-col items-center justify-center gap-2 mx-10">
                <div class="text-4xl font-bold text-blue-500">{{ displayedCities }}</div>
                <hr class="w-full border-t-2 border-blue-500"/>
                <div class="text-xl text-blue-500">Total Cities</div>
            </div>
        </div>
        <hr class="w-full border-t-2 border-blue-500"/>
        
        <div class="overflow-x-auto">
            <!-- <div class="flex justify-between row">
                <div class="p-4">
                    <button @click="showPopUp = true;" class="px-4 py-2 text-sm text-white bg-indigo-600 rounded-md hover:bg-indigo-900">Add New Email</button>
                </div>

                <div class="p-4">
                    <button class="px-4 py-2 text-sm text-white bg-indigo-600 rounded-md hover:bg-indigo-900">Upload CSV</button>
                </div>
            </div> -->
            <!-- change this to modal -->
            <div v-if="showPopUp" class="fixed inset-0 z-10 overflow-y-auto">
                <div class="flex items-center justify-center min-h-screen">
                    <div class="w-1/2 p-6 mt-12 bg-white rounded-lg shadow-lg">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-xl font-semibold text-slate-900">Add Email</h2>
                            <button @click="showPopUp = false;" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <form @submit.prevent="addEmail">
                            <div class="mb-4">
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" id="email" v-model="email.email" class="block w-full px-4 py-2 mt-1 border border-gray-300 rounded-lg text-slate-800 focus:outline-none focus:border-blue-500">
                            </div>

                            <div class="mb-4">
                                <label for="requirements" class="block text-sm font-medium text-gray-700">Requirements</label>
                                <textarea id="requirements" v-model="email.requirements" class="block w-full px-4 py-2 mt-1 border border-gray-300 rounded-lg text-slate-800 focus:outline-none focus:border-blue-500" rows="4"></textarea>
                            </div>

                            <div class="mb-4">
                                <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                                <input type="text" id="city" v-model="email.city" class="block w-full px-4 py-2 mt-1 border border-gray-300 rounded-lg text-slate-800 focus:outline-none focus:border-blue-500">
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" class="px-4 py-2 font-bold text-white bg-blue-500 rounded hover:bg-blue-700">Add</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- <table class="min-w-full border border-collapse border-gray-200 table-auto">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left uppercase text-slate-900 bg-gray-50">ID</th>
                        <th class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-900 uppercase bg-gray-50">Email</th>
                        <th class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-900 uppercase bg-gray-50">Requirements</th>
                        <th class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-900 uppercase bg-gray-50">City</th>
                        <th colspan="3" class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-900 uppercase bg-gray-50">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="(item, index) in emails" :key="index">
                        <td class="px-6 py-4 text-sm whitespace-no-wrap text-slate-800">{{ item.id }}</td>
                        <td class="px-6 py-4 text-sm whitespace-no-wrap text-slate-800">
                            <span v-if="email.id == item.id">
                                <input class="px-2 py-1 text-sm rounded-lg text-slate-800 focus:outline-none focus:border-gray-400" type="text" v-model="email.email" required>
                            </span>
                            <span v-else>
                                {{ item.email }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm whitespace-no-wrap text-slate-800">
                            <span v-if="email.id == item.id">
                                <input class="px-2 py-1 text-sm rounded-lg text-slate-800 focus:outline-none focus:border-gray-400" type="text" v-model="email.requirements" required>
                            </span>
                            <span v-else>
                                {{ truncateDescription(item.requirements) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm whitespace-no-wrap text-slate-800">
                            <span v-if="email.id == item.id">
                                <input class="px-2 py-1 text-sm rounded-lg text-slate-800 focus:outline-none focus:border-gray-400" type="text" v-model="email.city" required>
                            </span>
                            <span v-else>
                                {{ item.city }}
                            </span>
                        </td>
                        <td>
                            <button v-if="email.id == item.id" @click="editEmail()" class="text-sm text-indigo-600 hover:text-indigo-900">Update</button>
                        </td>
                        <td class="px-2 py-4 whitespace-no-wrap">
                            <button @click="email = item;" class="text-sm text-indigo-600 hover:text-indigo-900">Edit</button>
                        </td>
                        <td class="px-2 py-4 whitespace-no-wrap">
                            <button @click="removeEmail(item.id)" class="text-sm text-red-600 hover:text-red-900">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table> 
            <Paginate :next-page-url="nextPageUrl" type="load-more" @update:ModelValue="getEmails($event)" /> -->
        </div>
    </LayoutAuthenticated>
</template>

<script setup>
import { onMounted, ref, watch } from 'vue';
import LayoutAuthenticated from '@/components/LayoutAuthenticated.vue';
import emailApiRepository from "@/composables/emailApiRepository";
import CommonFunction from "@/composables/CommonFunction";
import Paginate from "@/components/Paginate.vue";
import BaseDivider from '@/components/BaseDivider.vue';

const { fetchEmails, deleteEmail, updateEmail, createEmail, uploadEmails, getCount } = emailApiRepository();
const { fireToaster, truncateDescription, debounce } = CommonFunction();
// Data Variables
const totalEmails = ref(0);
const totalCities = ref(0);
const displayedEmails = ref(0);
const displayedCities = ref(0);
const filters = ref({
    city: null,
    requirement: null,
});
const emails = ref([]);
const fileName = ref('');
const email = ref({
    id: null,
    email: null,
    requirements: null,
    city: null,
});
const showPopUp = ref(false);
const nextPageUrl = ref(null);
const emailInterval = ref(false);
const cityInterval = ref(false);
// Watchers

watch (() => filters.value, (newObj) => {
  if(newObj) {
    getCounts();
  }
}, { deep: true});

watch (() => totalEmails.value, (newVal) => {

    clearInterval(emailInterval.value);
    if(newVal == displayedEmails.value) {
        return;
    }

    emailInterval.value = window.setInterval(() => {
        if(displayedEmails.value != newVal) {
            var change = (newVal - displayedEmails.value) / 10;
            change = change >= 0 ? Math.ceil(change) : Math.floor(change);
            displayedEmails.value = displayedEmails.value + change;
        }
    }, 40);
  
}, { deep: true});

watch (() => totalCities.value, (newVal) => {

    clearInterval(cityInterval.value);
    if(newVal == displayedCities.value) {
        return;
    }

    cityInterval.value = window.setInterval(() => {
        if(displayedCities.value != newVal) {
            var change = (newVal - displayedCities.value) / 10;
            change = change >= 0 ? Math.ceil(change) : Math.floor(change);
            displayedCities.value = displayedCities.value + change;
        }
    }, 40);

}, { deep: true});

// Functions

const handleFileChange = (event) => {
const file = event.target.files[0];
    if (file) {
        fileName.value = file.name;
        uploadFile(file);
    } else {
        fileName.value = '';
    }
}

const uploadFile = (file) => {
    let fd = new FormData();
    fd.append('file', file);
    uploadEmails('/upload', fd).then(response => {
        if(response.status == 200) {
            fireToaster('File uploaded successfully', 'success');   
        }
    }).catch(error => {
        console.error('Error uploading file:', error);
    });
}

const getCounts = () => {
    debounce(() => {
        getCount(filters.value).then((response) => {

            if(response.status == 200) {
                const { data: { result: result} } = response;
                totalEmails.value = result.item.emails;
                totalCities.value = result.item.cities;
            }
        })
    }, 500);
}

const getEmails = (url = null) => {
    fetchEmails({ page_size: 10 }, url).then((response) => {
        const { data: { result: result} } = response;
        emails.value.push(...result.paginated_items.data);
        nextPageUrl.value = result.paginated_items.next_page_url;
    }).catch((errors) => {
        console.log(errors);
    });
};

const removeEmail = (email_id) => {
    deleteEmail(email_id).then((response) => {
        if(response.status == 200) {
            emails.value = emails.value.filter((item) => item.id != email_id);
            fireToaster('Email removed successfully', 'success');
        }
    }).catch((errors) => {
        console.log(errors);
    });
}

const addEmail = () => {
    createEmail(email.value).then((response) => {
        if(response.status ==  200) {
            showPopUp.value = false;
            fireToaster('Email added successfully', 'success');
        }
    }).catch((errors) => {
        console.log(errors);
    });
}

const editEmail = () => {
    updateEmail(email.value.id, email.value).then((response) => {
        if(response.status == 200) {
            fireToaster('Email updated successfully', 'success');
            emails.value.forEach((item) => {
                if(item.id == email.value.id) {
                    item.email = email.value.email;
                    item.requirements = email.value.requirements;
                    item.city = email.value.city;
                }
            })
            email.value = {
                id: null,
                email: null,
                requirements: null,
                city: null,
            };
        }
        // emails.value = emails.value.filter((item) => item.id != email_id);
    }).catch((errors) => {
        console.log(errors);
    });
}

// LifeCycle hooks
onMounted(() =>{
    getCounts();
})
</script>