const isAdmin = $("#is_admin");
const role = $('#role');

const validateIsAdmin = val => role.prop('disabled', (val === 1));

isAdmin.change(e => validateIsAdmin(+$(e.currentTarget).val()));