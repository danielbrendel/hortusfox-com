/**
 * app.js
 * 
 * Put here your application specific JavaScript implementations
 */

import './../sass/app.scss';

window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import hljs from 'highlight.js';
import 'highlight.js/scss/github.scss';

window.hljs = hljs;

require('phaser');

window.vue = new Vue({
    el: '#app',

    data: {
        bShowPreviewImageModal: false,
        clsLastImagePreviewAspect: '',
        communityPhotoPaginate: null,
        communityPhotoFilterTag: null,
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

        previewNewsletter: function(subject, content, token) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = window.location.origin + '/admin/newsletter/preview?token=' + token;
            form.target = '_blank';

            const inpSubject = document.createElement('input');
            inpSubject.type = 'text';
            inpSubject.name = 'subject';
            inpSubject.value = subject.value;
            inpSubject.style.display = 'none';
            form.appendChild(inpSubject);

            const inpContent = document.createElement('textarea');
            inpContent.name = 'content';
            inpContent.value = content.value;
            inpContent.style.display = 'none';
            form.appendChild(inpContent);

            document.body.appendChild(form);
            form.submit();
        },

        fetchCommunityPhotos: function(target) {
            target.innerHTML += '<div id="spinner"><i class="fas fa-spinner fa-spin"></i></div>';

            window.vue.ajaxRequest('post', window.location.origin + '/community/fetch', { paginate: window.vue.communityPhotoPaginate, tag: window.vue.communityPhotoFilterTag }, function(response) {
                if (response.code == 200) {
                    let spinner = document.getElementById('spinner');
                    if (spinner) {
                        spinner.remove();
                    }

                    response.data.forEach(function(elem, index) {
                        let description = '';

                        if ((typeof elem.description === 'string') && (elem.description.length > 0)) {
                            description = `
                                <div class="community-item-description">
                                    ` + elem.description + `
                                </div>
                            `;
                        }

                        target.innerHTML += `
                            <div class="community-item" style="background-image: url('` + window.location.origin + '/img/photos/' + elem.thumb + `');" onmouseover="document.getElementById('community-item-info-` + elem.id + `').style.display = 'block';" onmouseout="document.getElementById('community-item-info-` + elem.id + `').style.display = 'none';" onclick="window.open('` + window.location.origin + '/p/' + elem.slug + `');">
                                <div class="community-item-info fade-in" id="community-item-info-` + elem.id + `">
                                    <div class="community-item-title">` + elem.title + `</div>

                                    ` + description + `

                                    <div class="community-item-keywords">
                                        ` + window.vue.formattedKeywords(elem.keywords) + `
                                    </div>
                                </div>
                            </div>
                        `;
                    });

                    window.vue.communityPhotoPaginate = response.data[response.data.length - 1].id; 

                    if (window.vue.communityPhotoPaginate > response.first) {
                        target.innerHTML += '<div id="loadmore"><a class="is-default-link" href="javascript:void(0);" onclick="window.vue.fetchCommunityPhotos(document.getElementById(\'' + target.id + '\')); document.getElementById(\'loadmore\').remove();">Load more</a></div>';
                    }
                } else {
                    console.error(response.msg);
                }
            });
        },

        formattedKeywords: function(keywords) {
            let result = '';

            if (!Array.isArray(keywords)) {
                return result;
            }

            keywords.forEach(function(elem, index) {
                result += `
                    <div class="community-item-keywords-tag">
                        <a href="javascript:void(0);" onclick="event.stopPropagation(); location.href = '` + window.location.origin + '/community?tag=' + elem + `';">` + elem + `</a>
                    </div>
                `;
            });

            return result;
        },

        copyToClipboard: function(text, info = 'Text was copied to clipboard') {
            const el = document.createElement('textarea');
            el.value = text;
            document.body.appendChild(el);
            el.select();
            document.execCommand('copy');
            document.body.removeChild(el);
            alert(info);
        },
    }
});