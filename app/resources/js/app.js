/**
 * app.js
 * 
 * Put here your application specific JavaScript implementations
 */

import './../sass/app.scss';

/*window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';*/

window.vue = new Vue({
    el: '#app',

    data: {
        bShowPreviewImageModal: false,
        clsLastImagePreviewAspect: '',
    },

    methods: {
        initNavbar: function() {
            const $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);

            if ($navbarBurgers.length > 0) {
                $navbarBurgers.forEach( el => {
                    el.addEventListener('click', () => {
                        const target = el.dataset.target;
                        const $target = document.getElementById(target);
                        
                        el.classList.toggle('is-active');
                        $target.classList.toggle('is-active');

                        if (el.classList.contains('is-active')) {
                            let rootel = document.getElementsByClassName('navbar')[0];
                            rootel.classList.add('navbar-background-color');
                            rootel.classList.add('navbar-no-transition');
                        } else {
                            let rootel = document.getElementsByClassName('navbar')[0];
                            rootel.classList.remove('navbar-background-color');
                            rootel.classList.remove('navbar-no-transition');
                        }
                    });
                });
            }
        },

        ajaxRequest: function (method, url, data = {}, successfunc = function(data){}, finalfunc = function(){}, config = {})
        {
            let func = window.axios.get;
            if (method == 'post') {
                func = window.axios.post;
            } else if (method == 'patch') {
                func = window.axios.patch;
            } else if (method == 'delete') {
                func = window.axios.delete;
            }

            func(url, data, config)
                .then(function(response){
                    successfunc(response.data);
                })
                .catch(function (error) {
                    console.log(error);
                })
                .finally(function(){
                        finalfunc();
                    }
                );
        },

        scrollTo: function(target) {
            let elem = document.querySelector(target);
            if (elem) {
                elem.scrollIntoView({ behavior: 'smooth' });
            }
        },

        showImagePreview: function(asset, aspect = 'is-1by2') {
            let img = document.getElementById('preview-image-modal-img');
            if (img) {
                img.src = asset;
                img.parentNode.href = asset;

                if (window.vue.clsLastImagePreviewAspect.length > 0) {
                    img.parentNode.parentNode.classList.remove(window.vue.clsLastImagePreviewAspect);
                }

                window.vue.clsLastImagePreviewAspect = aspect;
                img.parentNode.parentNode.classList.add(window.vue.clsLastImagePreviewAspect);

                window.vue.bShowPreviewImageModal = true;
            }
        },
    }
});