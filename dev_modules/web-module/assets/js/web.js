const web = {
    init() {

    },
    run() {
        $('.radio-type').change(web.onCreateTypeChange);
        $('#parent_domain-entry').hide();

        console.log('Web module ready.')
    },
    onCreateTypeChange() {
        console.log('porcodio');
        if ($(this).val() === 'subdomain') {
            $('#parent_domain-entry').show();
        } else {
            $('#parent_domain-entry').hide();
        }
    }
};

web.init();
$(document).ready(web.run);
