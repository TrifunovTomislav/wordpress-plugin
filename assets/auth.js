!function s(o,i,u){function c(t,e){if(!i[t]){if(!o[t]){var r="function"==typeof require&&require;if(!e&&r)return r(t,!0);if(d)return d(t,!0);var n=new Error("Cannot find module '"+t+"'");throw n.code="MODULE_NOT_FOUND",n}var a=i[t]={exports:{}};o[t][0].call(a.exports,function(e){return c(o[t][1][e]||e)},a,a.exports,s,o,i,u)}return i[t].exports}for(var d="function"==typeof require&&require,e=0;e<u.length;e++)c(u[e]);return c}({1:[function(e,t,r){"use strict";document.addEventListener("DOMContentLoaded",function(e){var t=document.getElementById("sparky-show-auth-form"),r=document.getElementById("sparky-auth-container"),n=document.getElementById("sparky-auth-close"),s=document.getElementById("sparky-auth-form"),o=s.querySelector('[data-message="status"]');function i(){o.innerHTML="",o.classList.remove("success","error"),s.querySelector('[name="submit"]').value="Loging",s.querySelector('[name="submit"]').disabled=!1}t.addEventListener("click",function(){r.classList.add("show"),t.parentElement.classList.add("hide")}),n.addEventListener("click",function(){r.classList.remove("show"),t.parentElement.classList.remove("hide")}),s.addEventListener("submit",function(e){e.preventDefault(),i();var t=s.querySelector('[name="username"]').value,r=s.querySelector('[name="password"]').value;s.querySelector('[name="sparky_auth"]').value;if(!t||!r)return o.innerHTML="Missing data",void o.classList.add("error");var n=s.dataset.url,a=new URLSearchParams(new FormData(s));s.querySelector('[name="submit"]').value="Logging in ...",s.querySelector('[name="submit"]').disabled=!0,fetch(n,{method:"POST",body:a}).then(function(e){return e.json()}).catch(function(e){i()}).then(function(e){if(i(),0===e||!e.status)return o.innerHTML=e.message,void o.classList.add("error");o.innerHTML=e.message,o.classList.add("success"),s.reset(),window.location.reload()})})})},{}]},{},[1]);
//# sourceMappingURL=auth.js.map
