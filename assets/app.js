/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

require('bootstrap');

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

// start the Stimulus application
import './bootstrap';

const mobileMenu = document.querySelector('.mobile-menu')
const navLinks = document.querySelector('.nav-links')

mobileMenu.addEventListener('click', () => {
    navLinks.classList.toggle('toggler')
    navLinks.classList.toggle('animate__fadeIn')
})
