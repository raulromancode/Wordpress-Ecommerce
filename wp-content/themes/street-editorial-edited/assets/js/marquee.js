/**
 * Street Editorial — marquee.js
 * Duplica el contenido del marquee para scroll infinito suave
 */
(function () {
  'use strict';

  document.querySelectorAll('.marquee-track').forEach(function (track) {
    // Duplicamos el contenido para que el loop no deje espacio en blanco
    const clone = track.innerHTML;
    track.innerHTML = clone + clone;
  });

})();
