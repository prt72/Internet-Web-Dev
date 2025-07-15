import './bootstrap';
import { createApp } from 'vue'
import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faUser, faLocationDot } from '@fortawesome/free-solid-svg-icons'

// Add icons to the library
library.add(faUser, faLocationDot)

// Create the Vue app instance
const app = createApp({})

// Register the FontAwesomeIcon component globally
app.component('font-awesome-icon', FontAwesomeIcon)

app.mount('#app') // This will mount your Vue app
