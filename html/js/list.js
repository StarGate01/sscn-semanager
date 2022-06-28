(function () {
    'use strict';

    var table = $("#reg_rable");
    var filter1 = document.querySelector("#filter1");
    var filter2 = document.querySelector("#filter2");
    var filter3 = document.querySelector("#filter3");

    window.addEventListener('load', function () {
        filter1.addEventListener('click', function (event) {
            table.bootstrapTable('filterBy', { })
            filter1.classList.add("btn-primary");
            filter1.classList.remove("btn-secondary");
           
            filter2.classList.add("btn-secondary");
            filter2.classList.remove("btn-primary");
            
            filter3.classList.add("btn-secondary");
            filter3.classList.remove("btn-primary");
        }, false);

        filter2.addEventListener('click', function (event) {
            table.bootstrapTable('filterBy', {
                surf: ["Angemeldet"]
            })
            filter2.classList.add("btn-primary");
            filter2.classList.remove("btn-secondary");
           
            filter1.classList.add("btn-secondary");
            filter1.classList.remove("btn-primary");
            
            filter3.classList.add("btn-secondary");
            filter3.classList.remove("btn-primary");
        }, false);

        filter3.addEventListener('click', function (event) {
            table.bootstrapTable('filterBy', {
                sup: ["Angemeldet"]
            })
            filter3.classList.add("btn-primary");
            filter3.classList.remove("btn-secondary");
           
            filter2.classList.add("btn-secondary");
            filter2.classList.remove("btn-primary");
            
            filter1.classList.add("btn-secondary");
            filter1.classList.remove("btn-primary");
        }, false);

        setTimeout(function () {
            var result = document.querySelector("#result_message");
            if (result != null) result.style.display = "none";
        }, 5000);
    }, false);
})();

/* 
var $table = $('#table')
  var $button = $('#button')
  var $customButton = $('#custom')

  $(function() {
    $button.click(function () {
      
    })

    $customButton.click(function () {
      $table.bootstrapTable('filterBy', {
        id: 4
      }, {
        'filterAlgorithm': (row, filters) => {
          return row.id % filters.id === 0
        }
      })
    })
  }) */