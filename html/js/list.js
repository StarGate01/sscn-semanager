(function () {
    'use strict';

    window.tableFilterStripHtml = function (value) {
        if(/<span.*>Seit.*<\/span>/.test(value)) return "Seit";
        else return "Unbeteiligt";
    }

    window.addEventListener('load', function () {
        setTimeout(function () {
            var result = document.querySelector("#result_message");
            if (result != null) result.style.display = "none";
        }, 5000);
    }, false);
})();
