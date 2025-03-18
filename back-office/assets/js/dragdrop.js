document.addEventListener("DOMContentLoaded", function () {
    var dropZone = document.getElementById("drop-zone");
    var fileInput = document.querySelector(".file-input");

    dropZone.addEventListener("dragover", function (e) {
        e.preventDefault();
        dropZone.style.borderColor = "#000";
    });

    dropZone.addEventListener("dragleave", function () {
        dropZone.style.borderColor = "#ccc";
    });

    dropZone.addEventListener("drop", function (e) {
        e.preventDefault();
        dropZone.style.borderColor = "#ccc";
        var files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
        }
    });
});
