// import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';
// https://symfony.com/doc/current/frontend/asset_mapper.html#mapping-and-referencing-assets
// https://www.npmjs.com/package/canvas-confetti
import confetti from 'canvas-confetti';
document.getElementById('logo').addEventListener('click', () => confetti())

console.log('Hello')