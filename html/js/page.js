(function () {
    'use strict';

    var canvas;
    var signaturePad;

    function resizeCanvas() {
        var backup = signaturePad.toDataURL();
        const ratio = Math.max(window.devicePixelRatio || 1, 1);
        canvas.width = canvas.offsetWidth * ratio;
        canvas.height = canvas.offsetHeight * ratio;
        canvas.getContext("2d").scale(ratio, ratio);
        signaturePad.clear();
        signaturePad.fromDataURL(backup);
    }

    window.addEventListener("resize", resizeCanvas);

    window.addEventListener('load', function () {
        canvas = document.querySelector("canvas");
        signaturePad = new SignaturePad(canvas);

        var canvas_error = document.querySelector("#signature_error");
        var canvas_data = document.querySelector("#signature");

        var forms = document.getElementsByClassName('needs-validation');
        var validation = Array.prototype.filter.call(forms, function (form) {
            form.addEventListener('submit', function (event) {
                var sigsHidden = document.getElementsByClassName('signature');
                Array.prototype.filter.call(sigsHidden, function (inp) {
                    if (signaturePad.isEmpty()) {
                        inp.setCustomValidity("Die Unterschrift darf icht leer sein!");
                        canvas.classList.add("canvas_error");
                        canvas.classList.remove("canvas_success");
                        canvas_data.value = "";
                        canvas_error.style.display = "block";
                    } else {
                        canvas.classList.remove("canvas_error");
                        canvas.classList.add("canvas_success");
                        canvas_data.value = signaturePad.toDataURL();
                        canvas_error.style.display = "none";
                    }
                });

                if (!form.checkValidity()) {
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

        signaturePad.fromDataURL(canvas_data.value);

        setTimeout(function() {
            var result = document.querySelector("#result_message");
            result.style.display = "none";
        }, 5000);
    }, false);
})();
