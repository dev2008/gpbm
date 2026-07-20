/* 
The MIT License (MIT)

Copyright (c) 2017 Gregory Petrosyan

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/
(function (global, factory) {
  typeof exports === 'object' && typeof module !== 'undefined' ? factory(exports) :
  typeof define === 'function' && define.amd ? define(['exports'], factory) :
  (global = typeof globalThis !== 'undefined' ? globalThis : global || self, factory(global.et = {}));
}(this, (function (exports) { 'use strict';

  var fp = typeof window !== "undefined" && window.flatpickr !== undefined
      ? window.flatpickr
      : {
          l10ns: {},
      };
  var Estonian = {
      weekdays: {
          shorthand: ["P", "E", "T", "K", "N", "R", "L"],
          longhand: [
              "Pühapäev",
              "Esmaspäev",
              "Teisipäev",
              "Kolmapäev",
              "Neljapäev",
              "Reede",
              "Laupäev",
          ],
      },
      months: {
          shorthand: [
              "Jaan",
              "Veebr",
              "Märts",
              "Apr",
              "Mai",
              "Juuni",
              "Juuli",
              "Aug",
              "Sept",
              "Okt",
              "Nov",
              "Dets",
          ],
          longhand: [
              "Jaanuar",
              "Veebruar",
              "Märts",
              "Aprill",
              "Mai",
              "Juuni",
              "Juuli",
              "August",
              "September",
              "Oktoober",
              "November",
              "Detsember",
          ],
      },
      firstDayOfWeek: 1,
      ordinal: function () {
          return ".";
      },
      weekAbbreviation: "Näd",
      rangeSeparator: " kuni ",
      scrollTitle: "Keri, et suurendada",
      toggleTitle: "Klõpsa, et vahetada",
      time_24hr: true,
  };
  fp.l10ns.et = Estonian;
  var et = fp.l10ns;

  exports.Estonian = Estonian;
  exports.default = et;

  Object.defineProperty(exports, '__esModule', { value: true });

})));