var BASE_URL ="https://protoselida.24media.gr";

var months = ["Ιανουάριος","Φεβρουάριος","Μάρτιος","Απρίλιος","Μάιος","Ιούνιος","Ιούλιος","Άυγουστος","Σεπτέμβριος","Οκτώβριος","Νοέμβριος","Δεκέμβριος"];
var monthsGen = ["Ιανουαρίου","Φεβρουαρίου","Μαρτίου","Απριλίου","Μαίου","Ιουνίου","Ιουλίου","Αυγούστου","Σεπτεμβρίου","Οκτωβρίου","Νοεμβρίου","Δεκεμβρίου"];

var categoriesDict = {
    0: { "name": "Όλα τα Πρωτοσέλιδα"},
    4: { "name": "Πολιτικές Εφημερίδες","slug":"politikes-efimerides" },
    5: { "name": "Οικονομικές Εφημερίδες","slug":"oikonomikes-efimerides" },
    3: { "name": "Κυριακάτικες Εφημερίδες","slug":"kuriakatikes-efimerides" },
    6: { "name": "Αθλητικές Εφημερίδες","slug":"athlitikes-efimerides" },
    7: { "name": "Εβδομαδιαίες Εφημερίδες","slug":"evdomadiaies-efimerides" },
    8: { "name": "Περιφέρεια","slug":"perifereia" },
    11: { "name": "Εβδομαδιαία Περιοδικά","slug":"evdomadiaia-periodika" },
    10: { "name": "Αθλητικά Περιοδικά","slug":"athlitika-periodika" },
    9: { "name": "Free Press","slug":"free-press" },
    12: { "name": "Μηνιαία Περιοδικά","slug":"miniaia-periodika" }
}

function navigateTo(url){
    window.location.href = url;
}

function dateStringTogenMonth(s){
    for(var i = 0;i<12;i++){
        s = s.replace(months[i],monthsGen[i])
    }
    return s;
}

function getParameterByName(name, url = window.location.href) {
    name = name.replace(/[\[\]]/g, '\\$&');
    var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, ' '));
}






