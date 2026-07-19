require('pc-bootstrap4-datetimepicker');

let timeInputs, dateInputs;

export function initDateInputs() {
    dateInputs = $('.datepicker');
    dateInputs.datetimepicker({
        locale: 'es',
        showTodayButton: false,
        showClose: false,
        tooltips: {
            today: 'Hoy',
            clear: 'Limpiar selección',
            close: 'Cerrar',
            selectMonth: 'Seleccionar mes',
            prevMonth: 'Mes anterior',
            nextMonth: 'Siguiente mes',
            selectYear: 'Seleccionar año',
            prevYear: 'Año anterior',
            nextYear: 'Siguiente año',
            selectDecade: 'Seleccionar década',
            prevDecade: 'Década anterior',
            nextDecade: 'Siguiente década',
            prevCentury: 'Siglo anterior',
            nextCentury: 'Siguiente siglo',
            selectTime: 'Seleccionar hora',
            pickHour: 'Seleccionar hora',
            pickMinute: 'Seleccionar minuto',
            incrementHour: "Incrementar hora",
            incrementMinute: "Incrementar minuto",
            decrementHour: "Disminuir hora",
            decrementMinute: "Disminuir minuto"
        },
        icons: {
            time: 'fa fa-clock',
            date: 'fa fa-calendar',
            up: 'fa fa-arrow-up',
            down: 'fa fa-arrow-down',
            previous: 'fa fa-chevron-left',
            next: 'fa fa-chevron-right',
            today: 'fa fa-calendar-check',
            clear: 'fa fa-trash-alt',
            close: 'fa fa-times-circle'
        }
    })
}

export function initTimeInputs() {
    timeInputs = $('.time-input');

    timeInputs.each((i, el) => {
        const objEl = $(el);
        const format = objEl.data('time-format');
        objEl.blur(() => {
            objEl.val() && validateFormat(objEl, format);
        });

        objEl.keydown((ev) => {
            if (ev.keyCode === 13) {
                objEl.blur();
            }
        });
    });
}

function validateFormat(el, format) {
    let test_formats = ['hhmm', 'hmm', 'hh', 'h'];
    let a, d;

    if (!(d = moment(el.val(), format)).isValid()) {
        while ((a = test_formats.shift())) {
            if ((d = moment(el.val(), a)).isValid()) {
                break;
            }
        }
    }
    el.val(d && d.isValid() ? d.format(format) : '');
    el.trigger('change', true);
}

initDateInputs();
initTimeInputs();

export function calendarLang() {

    let monthNames = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
    let monthShort = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
    let dayNames = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
    let dayShort = ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sáb'];

    return {
        monthsLarge: function() {
            return monthNames;
        },
        monthsShort: function() {
            return monthShort;
        },
        daysLarge: function() {
            return dayNames;
        },
        daysShort: function() {
            return dayShort;
        }
    }
}