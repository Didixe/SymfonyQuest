/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';
import fondLogoPath  from './images/fond.jpg';
// let html = `<img src="${fondLogoPath }" alt="fond">`;

import murLogoPath  from './images/mur.jpg';
// let html = `<img src="${murLogoPath }" alt="mur">`;

let html;
html = `<img src="${fondLogoPath}" alt="fond">`;
console.log(html);

html = `<img src="${murLogoPath}" alt="mur">`;
console.log(html);

console.log('Hello Webpack Encore !')