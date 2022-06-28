(function () {
    'use strict';

    var canvas;
    var signaturePad;

    function resizeCanvas() {
        const ratio = Math.max(window.devicePixelRatio || 1, 1);
        canvas.width = canvas.offsetWidth * ratio;
        canvas.height = canvas.offsetHeight * ratio;
        canvas.getContext("2d").scale(ratio, ratio);
        signaturePad.clear(); // otherwise isEmpty() might return incorrect value
    }

    window.addEventListener("resize", resizeCanvas);

    window.addEventListener('load', function () {
        canvas = document.querySelector("canvas");
        var canvas_error = document.querySelector("#signature_error");
        signaturePad = new SignaturePad(canvas);

        var forms = document.getElementsByClassName('needs-validation');
        var validation = Array.prototype.filter.call(forms, function (form) {
            form.addEventListener('submit', function (event) {
                var sigsHidden = document.getElementsByClassName('signature');
                Array.prototype.filter.call(sigsHidden, function (inp) {
                    if (signaturePad.isEmpty()) {
                        inp.setCustomValidity("Die Unterschrift darf icht leer sein!");
                        canvas.classList.add("canvas_error");
                        canvas.classList.remove("canvas_success");
                        canvas_error.style.display = "block";
                    } else {
                        canvas.classList.remove("canvas_error");
                        canvas.classList.add("canvas_success");
                        canvas_error.style.display = "none";
                    }
                });

                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }

                form.classList.add('was-validated');
            }, false);
        });

        var btnsClear = document.getElementsByClassName('canvas_clear');
        Array.prototype.filter.call(btnsClear, function (btn) {
            btn.addEventListener('click', function (event) {
                event.preventDefault();
                event.stopPropagation();
                signaturePad.clear();
            }, false);
        });

        resizeCanvas();
    }, false);
})();



