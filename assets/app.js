/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

// start the Stimulus application
import './bootstrap';

import $ from 'jquery'
// window.bootstrap = require('bootstrap/dist/js/bootstrap.bundle.js');
import '@popperjs/core'
// import 'bootstrap'
import 'bootswatch/dist/quartz/bootstrap.min.css';

require('open-iconic/font/css/open-iconic-bootstrap.css')

// $(function () {
//     $('[data-toggle="tooltip"]').tooltip()
// })

// const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
// const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
//     return new bootstrap.Tooltip(tooltipTriggerEl)
// })

// $(function () {
//     $('[data-toggle="popover"]').popover()
// })

// const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
// const popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
//     return new bootstrap.Popover(popoverTriggerEl)
// })
