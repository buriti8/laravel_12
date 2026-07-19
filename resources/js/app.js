require("./bootstrap");
require("./datetimeutils");
require('select2');

import initChangeLog from './audit';
import * as utils from "./utils";

$(function () {
    initChangeLog();

    //Initialize input mask numeric
    utils.setMaskNumeric();

    //Initialize input mask integer
    utils.setCleaveInteger();

    //Initialize Select2 Elements
    $(".select2").select2({
        language: {
            noResults: function () {
                return "No se encontraron resultados";
            }
        }
    });

    //Initialize Select2 Elements
    $(".select2bs4").select2({
        theme: "bootstrap4",
    });
});

$(".btn-delete").click(ev => {
    utils.confirmDelete().then(result => {
        if (result) {
            ev.currentTarget.form.submit();
        }
    });
});

$(".btn-status").click(ev => {
    utils.confirmStatus().then(result => {
        if (result) {
            ev.currentTarget.form.submit();
        }
    });
});

$(".upper").bind("input", function () {
    this.value = this.value.toUpperCase();
});

$(".custom-file-input").on("change", function () {
    let fileName = $(this)
        .val()
        .split("\\")
        .pop();
    $(this)
        .next(".custom-file-label")
        .addClass("selected")
        .html(fileName);
});

$('form').submit(function () {
    $(this).find("button[type='submit']").prop('disabled', true);
});