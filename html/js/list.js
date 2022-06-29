(function () {
    'use strict';

    window.tableFilterStripHtml = function (value) {
        return value.match(/<span.*>(.*)<\/span>/g, '')[0].trim();
    }

    window.operateEvents = {
      'click .change_button_add': function (e, value, row, index) {
        alert('You click add action, row: ' + JSON.stringify(row))
      },
      'click .change_button_remove': function (e, value, row, index) {
        alert('You click remove action, row: ' + JSON.stringify(row))
        /* $table.bootstrapTable('remove', {
          field: 'id',
          values: [row.id]
        }) */
      }
    }

    window.addEventListener('load', function () {
        setTimeout(function () {
            var result = document.querySelector("#result_message");
            if (result != null) result.style.display = "none";
        }, 5000);
    }, false);
})();
