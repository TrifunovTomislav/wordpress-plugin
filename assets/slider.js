!function n(o,c,u){function l(r,e){if(!c[r]){if(!o[r]){var t="function"==typeof require&&require;if(!e&&t)return t(r,!0);if(d)return d(r,!0);var i=new Error("Cannot find module '"+r+"'");throw i.code="MODULE_NOT_FOUND",i}var s=c[r]={exports:{}};o[r][0].call(s.exports,function(e){return l(o[r][1][e]||e)},s,s.exports,n,o,c,u)}return c[r].exports}for(var d="function"==typeof require&&require,e=0;e<u.length;e++)l(u[e]);return l}({1:[function(e,r,t){"use strict";var n=document.querySelector(".sparky-slider--view > ul"),o=document.querySelectorAll(".sparky-slider--view-slides"),i=document.querySelector(".sparky-slider--arrows-left"),s=document.querySelector(".sparky-slider--arrows-right"),c=o.length,u=function(e){var r,t=document.querySelector(".sparky-slider--view-slides.is-active"),i=Array.from(o).indexOf(t)+e+e,s=document.querySelector(".sparky-slider--view-slides:nth-child("+i+")");c<i&&(s=document.querySelector(".sparky-slider--view-slides:nth-child(1)")),0==i&&(s=document.querySelector(".sparky-slider--view-slides:nth-child("+c+")")),r=s,t.classList.remove("is-active"),r.classList.add("is-active"),n.setAttribute("style","transform:translateX(-"+r.offsetLeft+"px)")};s.addEventListener("click",function(){return u(1)}),i.addEventListener("click",function(){return u(0)})},{}]},{},[1]);
//# sourceMappingURL=slider.js.map
