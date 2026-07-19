window._ = require('lodash');

try {
    window.Popper = require('popper.js').default;
    window.$ = window.jQuery = require('jquery');
    window.moment = require('moment');

    require('gijgo');

    require('bootstrap');
    require('jquery-ui');
    require('jquery-ui/ui/widgets/sortable');
    require('jquery-ui/ui/widgets/autocomplete');
    require('admin-lte/dist/js/adminlte');
    require('tempusdominus-bootstrap-4/src/js/tempusdominus-bootstrap-4');
    require('overlayscrollbars');
    require('multiselect-two-sides/dist/js/multiselect');

    $.fn.modal.Constructor.prototype.enforceFocus = $.noop;

    $.ajaxSetup({
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

} catch (e) {
    //
}


window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

