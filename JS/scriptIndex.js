// Funzione per aggiungere ai preferiti
function aggiungi(id) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4) {
            if (xmlhttp.status == 200) {
                document.getElementById("bottone_colore" + id).innerHTML = xmlhttp.responseText;
            } else {
                alert('C\'è stato un errore');
            }
        }
    }
    xmlhttp.open('GET', 'api/favorite.php?action=add&Id=' + id, true);
    xmlhttp.send();
}

// Funzione per rimuovere dai preferiti
function elimina(id) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4) {
            if (xmlhttp.status == 200) {
                document.getElementById("bottone_colore" + id).innerHTML = xmlhttp.responseText;
            } else {
                alert('C\'è stato un errore');
            }
        }
    }
    xmlhttp.open('GET', 'api/favorite.php?action=remove&Id=' + id, true);
    xmlhttp.send();
}

// Toggle search box
document.addEventListener('DOMContentLoaded', function() {
    let box = document.getElementById('box');
    let btn = document.getElementById('ricerca');

    if (btn && box) {
        btn.addEventListener('click', function() {
            if (box.classList.contains('hidden')) {
                box.classList.remove('hidden');
                setTimeout(function() {
                    box.classList.remove('visuallyhidden');
                }, 20);
            } else {
                box.classList.add('visuallyhidden');
                box.addEventListener('transitionend', function(e) {
                    box.classList.add('hidden');
                }, {
                    capture: false,
                    once: true,
                    passive: false
                });
            }
        }, false);
    }

    // Ricerca live con AJAX
    $(document).ready(function() {
        $('.search-box input[type="text"]').on("keyup input", function() {
            var inputVal = $(this).val();
            var resultDropdown = $(this).siblings(".result");
            if (inputVal.length) {
                $.get("api/search.php", {term: inputVal}).done(function(data) {
                    resultDropdown.html(data);
                });
            } else {
                resultDropdown.empty();
            }
        });
    });
});
