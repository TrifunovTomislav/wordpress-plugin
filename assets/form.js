!function a(n,i,u){function c(r,e){if(!i[r]){if(!n[r]){var t="function"==typeof require&&require;if(!e&&t)return t(r,!0);if(l)return l(r,!0);var s=new Error("Cannot find module '"+r+"'");throw s.code="MODULE_NOT_FOUND",s}var o=i[r]={exports:{}};n[r][0].call(o.exports,function(e){return c(n[r][1][e]||e)},o,o.exports,a,n,i,u)}return i[r].exports}for(var l="function"==typeof require&&require,e=0;e<u.length;e++)c(u[e]);return c}({1:[function(e,r,t){"use strict";function a(){document.querySelectorAll(".field-msg").forEach(function(e){return e.classList.remove("show")})}document.addEventListener("DOMContentLoaded",function(e){var o=document.getElementById("sparky-testimonial-form");o.addEventListener("submit",function(e){e.preventDefault(),a();var r={name:o.querySelector('[name="name"]').value,email:o.querySelector('[name="email"]').value,message:o.querySelector('[name="message"]').value,nonce:o.querySelector('[name="nonce"]').value};if(r.name)if(/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(String(r.email).toLowerCase()))if(r.message){var t=o.dataset.url,s=new URLSearchParams(new FormData(o));o.querySelector(".js-form-submission").classList.add("show"),fetch(t,{method:"POST",body:s}).then(function(e){return e.json()}).catch(function(e){a(),o.querySelector(".js-form-error").classList.add("show")}).then(function(e){a(),0!==e&&"error"!==e.status?(o.querySelector(".js-form-success").classList.add("show"),o.reset()):o.querySelector(".js-form-error").classList.add("show")})}else o.querySelector('[data-error="invalidMessage"]').classList.add("show");else o.querySelector('[data-error="invalidEmail"]').classList.add("show");else o.querySelector('[data-error="invalidName"]').classList.add("show")})})},{}]},{},[1]);
//# sourceMappingURL=form.js.map
