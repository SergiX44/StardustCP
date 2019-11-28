/**
 * First, we will load all of this project's Javascript utilities and other
 * dependencies. Then, we will be ready to develop a robust and powerful
 * application frontend using useful Laravel and JavaScript libraries.
 */

require('./bootstrap');

const app = {
    init: () => {
    
    },
    run: () => {
        
        console.log('Application ready.')
    }
};

app.init();
$(document).ready(app.run);
