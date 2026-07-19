import AutoNumeric from "autonumeric";
import Cleave from "cleave.js";
import swal from "sweetalert";
import Swal from "sweetalert2";

export function confirmDelete() {
    return confirmAlert("Eliminar", "¿Desea eliminar el registro?");
}

export function confirmStatus() {
    return confirmAlert("Confirmación", "¿Desea cambiar el estado?");
}

export function confirmAlert(
    title,
    text,
    confirmText = "ACEPTAR",
    cancelText = "CANCELAR"
) {
    return swal({
        title: title,
        text: text,
        icon: "warning",
        dangerMode: true,
        width: 300,
        buttons: {
            confirm: {
                text: confirmText,
                value: true,
                visible: true,
                className: "",
                closeModal: true
            },
            cancel: {
                text: cancelText,
                value: null,
                visible: true,
                className: "",
                closeModal: true
            }
        }
    });
}

export function showLoading() {
    swal({
        title: "Cargando...",
        text: "Por favor, espera un momento.",
        buttons: false,
        closeOnClickOutside: false,
        closeOnEsc: false,
        icon: "info",
    });
}

export function hideLoading() {
    swal.close();
}

export function setMaskNumeric() {
    const mask_numeric = document.querySelectorAll('.mask-numeric');

    mask_numeric.forEach(element => {
        new AutoNumeric(element, {
            digitGroupSeparator: '.',
            decimalCharacter: ',',
            decimalPlaces: 2,
            minimumValue: '0',
        });
    });
}

export function setCleaveInteger() {
    const elements = document.querySelectorAll('.cleave-integer');

    elements.forEach(element => {
        new Cleave(element, {
            numeral: true,
            numeralThousandsGroupStyle: 'none',
            numeralDecimalScale: 0,
            numeralPositiveOnly: true,
        });
    });
}

const SwalBootstrap = Swal.mixin({
    buttonsStyling: false,
    confirmButtonText: 'Aceptar',
    customClass: {
        confirmButton: 'btn btn-primary',
        cancelButton: 'btn btn-secondary'
    },
});

export function swalAlert(text = '', icon = 'info', title = 'Información') {
    SwalBootstrap.fire({
        icon,
        title,
        text,
    });
};