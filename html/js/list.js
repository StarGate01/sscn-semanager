(function () {
    'use strict';

    window.tableFilterStripHtml = function (value) {
        return value.match(/<span.*>(.*)<\/span>/, '')[1].trim();
    }

    window.addEventListener('load', function () {
        setTimeout(function () {
            var result = document.querySelector("#result_message");
            if (result != null) result.style.display = "none";
        }, 5000);
    }, false);
})();
