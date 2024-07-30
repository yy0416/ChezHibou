// assets/app.js
/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

$(document).ready(function () {
    $('.navbar-collapse a').click(function () {
        $(".navbar-collapse").collapse('hide');
    });

    /* Initialize the video plugin */
    $('.video-container').videoBackground();
});

// start the Stimulus application
//import './bootstrap';
// assets/app.js

// returns the final, public path to this file
// path is relative to this file - e.g. assets/images/logo.png
//import logoPath from '../images/logo.png';

//let html = `<img src="${logoPath}" alt="ACME logo">`;