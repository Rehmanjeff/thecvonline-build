import { createApp } from 'vue'
import './style.css'
import App from './App.vue'
import router from './routes/index.js'
import axios from './plugins/axios.js';
import { library } from "@fortawesome/fontawesome-svg-core";
import { faHouse } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { faMagnifyingGlass } from "@fortawesome/free-solid-svg-icons";
import { faArrowUp19 } from "@fortawesome/free-solid-svg-icons";
import { faFilter } from "@fortawesome/free-solid-svg-icons";
import { faFingerprint } from "@fortawesome/free-solid-svg-icons";
import { faMapLocationDot } from "@fortawesome/free-solid-svg-icons";
import { faUser } from "@fortawesome/free-solid-svg-icons";
import { faBuilding } from "@fortawesome/free-solid-svg-icons";

library.add(
    faHouse,
    faMagnifyingGlass,
    faArrowUp19,
    faFilter,
    faFingerprint,
    faMapLocationDot,
    faBuilding,
    faUser
  );


const app = createApp(App)
app.component("font-awesome-icon", FontAwesomeIcon);
app.use(router)
app.use(axios);
app.mount('#app')