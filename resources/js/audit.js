let modal;
let modalBody;

async function loadAudit(url) {
    modalBody.innerHTML = '<i>Cargando...</i>';
    // Usar Bootstrap jQuery modal
    $(modal).modal('show');

    try {
        const response = await fetch(url);

        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        const result = await response.text();
        modalBody.innerHTML = result;

        const auditContent = modalBody.querySelector('#audit-content');

        auditContent?.addEventListener('click', (e) => {
            const target = e.target.closest('.pagination a, .btn-link');
            if (target) {
                e.preventDefault();
                const url = target.getAttribute('href');
                loadAudit(url);
            }
        });
    } catch (error) {
        modalBody.innerHTML = '<p class="alert alert-danger">Ha ocurrido un error, revise su conexión y vuelva a intentarlo...</p>';
    }
}

export default function initChangeLog() {
    modal = document.querySelector('#change_modal');
    modalBody = modal?.querySelector('.modal-body');

    document.querySelectorAll('.changeLog').forEach(element => {
        element.addEventListener('click', (e) => {
            e.preventDefault();
            if (e.currentTarget.href) {
                loadAudit(e.currentTarget.href);
            }
        });
    });
}