import Places from 'places.js';

/* ------------------------
--------- SCRIPT ---------
------------------------ */

let inputAddress = document.querySelector('#annonce_address');

if(inputAddress !== null) {
    let place = Places({
        container: inputAddress
    });
    place.on('change', e => {
        document.querySelector('#annonce_city').value = e.suggestion.city;
        document.querySelector('#annonce_zipCode').value = e.postcode;
        document.querySelector('#annonce_lat').value = e.suggestion.latlng.lat;
        document.querySelector('#annonce_lng').value = e.suggestion.latlng.lng;
    })
}