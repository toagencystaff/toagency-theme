/**
 * TOAgency — Form registrazione crew
 * v3.0 — 28 Aprile 2026
 * Path: /wp-content/themes/toagency-theme/assets/crew-form.js
 *
 * Funzionalità nuove v3:
 * - Prefisso telefono internazionale (tendina)
 * - P.IVA con label dinamica per paese
 * - Geografia per paese: IT (province + comuni), FR/ES/CH/GB (15 città), altri (testo libero)
 * - Foto profilo separata + portfolio
 * - Domicilio diverso da residenza con avviso casting
 * - Limiti file: foto 5MB, video 50MB
 */

(function () {
    'use strict';

    var GEO_ENDPOINT = '/crm_toagency/actions/cerca-comune.php';
    var THEME_URI = (window.toaThemeUri || '/wp-content/themes/toagency-theme');

    var MAX_PHOTOS = 15;
    var MAX_VIDEOS = 5;
    var MAX_PHOTO_SIZE_MB = 60; /* TASK hardening-upload-crew 2026-06-04 marco — era 5: le foto grandi passano e vengono compresse client-side */
    var MAX_VIDEO_SIZE_MB = 50;

    // Etichette P.IVA per paese
    var PIVA_LABELS = {
        'IT': 'Partita IVA',
        'FR': 'N° SIRET / TVA',
        'ES': 'NIF / CIF',
        'GB': 'VAT Number',
        'CH': 'MwSt-Nr / N° TVA',
        'DE': 'USt-IdNr',
        'PT': 'NIF',
        'AT': 'UID-Nummer',
        'BE': 'BTW / TVA',
        'NL': 'BTW-nummer'
    };
    function getPivaLabel(code) {
        return PIVA_LABELS[code] || 'Tax ID / VAT Number';
    }

    var uploadState = {
        photoProfile: null,
        photos: [],
        videos: []
    };

    var form = document.getElementById('toaCrewForm');
    if (!form) return;

    var steps = form.querySelectorAll('.toa-crew-step');
    var progressDots = form.querySelectorAll('.toa-crew-progress-step');
    var ageBlock = document.getElementById('toaCrewAgeBlock');
    var genitoreBox = document.getElementById('toaCrewGenitore');
    var successModal = document.getElementById('toaCrewSuccess');
    var successCloseBtn = document.getElementById('toaCrewSuccessClose');

    // Custom select containers
    var phonePrefixSelect = document.getElementById('toaCrewPhonePrefix');
    var nationSelect = document.getElementById('toaCrewNation');
    var domNationSelect = document.getElementById('toaCrewDomNation');
    var provinceSelect = document.getElementById('toaCrewProvince');
    var domProvinceSelect = document.getElementById('toaCrewDomProvince');
    var citySelect = document.getElementById('toaCrewCity');
    var domCitySelect = document.getElementById('toaCrewDomCity');

    // ─── 1. Navigazione step ─────────────────────────────────
    function showStep(n) {
        steps.forEach(function(s) { s.classList.remove('active'); });
        progressDots.forEach(function(d) { d.classList.remove('active'); });
        var target = form.querySelector('.toa-crew-step[data-step="'+n+'"]');
        if (target) target.classList.add('active');
        progressDots.forEach(function(d) {
            if (parseInt(d.dataset.step) <= n) d.classList.add('active');
        });
        window.scrollTo({ top: form.offsetTop - 80, behavior: 'smooth' });
    }
    form.querySelectorAll('[data-go]').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var to = parseInt(this.dataset.go);
            var current = parseInt(form.querySelector('.toa-crew-step.active').dataset.step);
            if (to > current && !validateStep(current)) return;
            showStep(to);
        });
    });

    // ─── 2. Calcolo età ──────────────────────────────────────
    function calcAge(dateStr) {
        if (!dateStr) return 0;
        var b = new Date(dateStr);
        if (isNaN(b)) return 0;
        var today = new Date();
        var age = today.getFullYear() - b.getFullYear();
        var m = today.getMonth() - b.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < b.getDate())) age--;
        return age;
    }

    var dob = form.querySelector('input[name="data_nascita"]');
    if (dob) {
        dob.addEventListener('change', function() {
            var age = calcAge(this.value);
            if (this.value && age < 16) {
                ageBlock.classList.add('show');
                form.querySelector('[data-go="2"]').disabled = true;
            } else {
                ageBlock.classList.remove('show');
                form.querySelector('[data-go="2"]').disabled = false;
            }
            toggleCategoriesByAge(age);
            toggleGenitoreByAge(age);
        });
    }

    function toggleCategoriesByAge(age) {
        document.querySelectorAll('.toa-crew-category-chip').forEach(function(chip) {
            var minAge = parseInt(chip.dataset.minAge || 16);
            if (age && age < minAge) {
                chip.classList.add('disabled');
                var cb = chip.querySelector('input');
                if (cb.checked) {
                    cb.checked = false;
                    chip.classList.remove('checked');
                }
            } else {
                chip.classList.remove('disabled');
            }
        });
    }
    function toggleGenitoreByAge(age) {
        var require = (age >= 16 && age < 18);
        if (require) {
            genitoreBox.classList.add('show');
            genitoreBox.querySelectorAll('input').forEach(function(i){ i.required = true; });
        } else {
            genitoreBox.classList.remove('show');
            genitoreBox.querySelectorAll('input').forEach(function(i){ i.required = false; i.value = ''; });
        }
    }

    // ─── 3. Categorie chip ───────────────────────────────────
    document.querySelectorAll('.toa-crew-category-chip').forEach(function(chip) {
        chip.addEventListener('click', function() {
            if (chip.classList.contains('disabled')) return;
            setTimeout(function() {
                var cb = chip.querySelector('input');
                chip.classList.toggle('checked', cb.checked);
            }, 0);
        });
    });

    // ─── 4. Custom Select ─────────────────────────────────────
    function initCustomSelect(container) {
        var trigger = container.querySelector('.toa-crew-customselect-trigger');
        var optionsBox = container.querySelector('.toa-crew-customselect-options');
        var hiddenInput = container.querySelector('input[type="hidden"]');
        var searchInput = container.querySelector('.toa-crew-customselect-search input');

        trigger.addEventListener('click', function(e) {
            e.stopPropagation();
            document.querySelectorAll('.toa-crew-customselect.open').forEach(function(c) {
                if (c !== container) c.classList.remove('open');
            });
            container.classList.toggle('open');
            if (container.classList.contains('open') && searchInput) {
                searchInput.focus();
            }
        });

        if (searchInput) {
            searchInput.addEventListener('click', function(e) { e.stopPropagation(); });
            searchInput.addEventListener('input', function() {
                var q = this.value.toLowerCase();
                optionsBox.querySelectorAll('.toa-crew-customselect-option').forEach(function(opt) {
                    var txt = opt.textContent.toLowerCase();
                    opt.style.display = txt.indexOf(q) > -1 ? '' : 'none';
                });
            });
        }

        // Delegate event sui figli
        optionsBox.addEventListener('click', function(e) {
            var opt = e.target.closest('.toa-crew-customselect-option');
            if (!opt) return;
            optionsBox.querySelectorAll('.toa-crew-customselect-option').forEach(function(o){
                o.classList.remove('selected');
            });
            opt.classList.add('selected');
            hiddenInput.value = opt.dataset.value;
            trigger.querySelector('.toa-crew-customselect-label').textContent = opt.dataset.label || opt.textContent;
            container.classList.remove('open');

            // FIX v3.2: se il container ha 2 input hidden (city_code + city_name)
            // li popoliamo entrambi DIRETTAMENTE qui, non via event bubbling
            // (evita race condition se il listener globale non parte in tempo)
            var allHidden = container.querySelectorAll('input[type="hidden"]');
            if (allHidden.length >= 2) {
                allHidden.forEach(function(h) {
                    if (h.name && h.name.indexOf('_city_name') > -1) {
                        h.value = opt.dataset.label || opt.textContent;
                    } else if (h.name && h.name.indexOf('_city_code') > -1) {
                        h.value = opt.dataset.value;
                    }
                });
            }

            var evt = new CustomEvent('toa:select', {
                bubbles: true,
                detail: { value: opt.dataset.value, label: opt.dataset.label || opt.textContent }
            });
            container.dispatchEvent(evt);
        });
    }
    document.querySelectorAll('.toa-crew-customselect').forEach(initCustomSelect);
    document.addEventListener('click', function() {
        document.querySelectorAll('.toa-crew-customselect.open').forEach(function(c){
            c.classList.remove('open');
        });
    });

    // ─── 5. Toggle sì/no ──────────────────────────────────────
    document.querySelectorAll('.toa-crew-toggle-group').forEach(function(group) {
        var hidden = group.querySelector('input[type="hidden"]');
        var conditional = group.parentNode.querySelector('.toa-crew-conditional');

        group.querySelectorAll('.toa-crew-toggle').forEach(function(btn) {
            btn.addEventListener('click', function() {
                group.querySelectorAll('.toa-crew-toggle').forEach(function(b){ b.classList.remove('active'); });
                btn.classList.add('active');
                hidden.value = btn.dataset.value;

                if (conditional) {
                    if (btn.dataset.value === '1') {
                        conditional.classList.add('show');
                    } else {
                        conditional.classList.remove('show');
                        var inp = conditional.querySelector('input, select');
                        if (inp) inp.value = '';
                    }
                }

                // Trigger custom event (per dom_coincide e altri)
                var evt = new CustomEvent('toa:toggle', { detail: { value: btn.dataset.value, name: hidden.name } });
                group.dispatchEvent(evt);
            });
        });
    });

    // ─── 6. Caricamento dati esterni (nazioni, prefissi, province) ──
    function loadJSON(url) {
        return fetch(url).then(function(r){ return r.json(); });
    }
    function populateSelect(container, items, renderFn) {
        var optionsBox = container.querySelector('.toa-crew-customselect-options');
        optionsBox.innerHTML = '';
        items.forEach(function(item) {
            var opt = document.createElement('div');
            opt.className = 'toa-crew-customselect-option';
            renderFn(opt, item);
            optionsBox.appendChild(opt);
        });
    }

    // Prefissi telefono
    if (phonePrefixSelect) {
        loadJSON(THEME_URI + '/assets/data/phone-prefixes.json').then(function(list) {
            populateSelect(phonePrefixSelect, list, function(opt, item) {
                opt.dataset.value = item.code;
                opt.dataset.label = item.flag + ' ' + item.prefix;
                opt.innerHTML = '<span class="flag">' + item.flag + '</span><span>' + item.prefix + '</span><span class="small-info">' + item.name + '</span>';
                if (item.code === 'IT') {
                    opt.classList.add('selected');
                    phonePrefixSelect.querySelector('input[type="hidden"]').value = 'IT';
                    phonePrefixSelect.querySelector('.toa-crew-customselect-label').textContent = item.flag + ' ' + item.prefix;
                }
            });
        }).catch(function(){
            // fallback: solo IT
            populateSelect(phonePrefixSelect, [{code:'IT',prefix:'+39',name:'Italia',flag:'🇮🇹'}], function(opt,item){
                opt.dataset.value = item.code;
                opt.dataset.label = item.flag + ' ' + item.prefix;
                opt.classList.add('selected');
                opt.innerHTML = '<span class="flag">' + item.flag + '</span><span>' + item.prefix + '</span>';
                phonePrefixSelect.querySelector('input[type="hidden"]').value = 'IT';
                phonePrefixSelect.querySelector('.toa-crew-customselect-label').textContent = item.flag + ' ' + item.prefix;
            });
        });
    }

    // Nazioni (residenza + domicilio)
    function loadNations(targetSelect) {
        if (!targetSelect) return;
        loadJSON(GEO_ENDPOINT + '?type=nations').then(function(list) {
            populateSelect(targetSelect, list, function(opt, item) {
                opt.dataset.value = item.code;
                opt.dataset.label = item.display || item.name_local;
                opt.textContent = item.display || item.name_local;
                if (item.code === 'IT') {
                    opt.classList.add('selected');
                    targetSelect.querySelector('input[type="hidden"]').value = 'IT';
                    targetSelect.querySelector('.toa-crew-customselect-label').textContent = item.display || item.name_local;
                }
            });
        }).catch(function(){});
    }
    loadNations(nationSelect);

    // ─── 7. Cambio nazione → adatta UI geografica ──────────────
    // FIX v3.1: usa 3 sotto-container già presenti nel DOM invece di sostituire innerHTML
    function handleNationChange(nationCode, ctx) {
        var prefix = ctx === 'dom' ? 'dom' : 'res';
        var domSuffix = ctx === 'dom' ? 'Dom' : '';
        var provinceContainer = document.getElementById('toaCrew' + domSuffix + 'ProvinceWrap');
        var cityWrap = document.getElementById('toaCrew' + domSuffix + 'CityWrap');

        if (!cityWrap) return;

        // Trova i 3 sotto-container città (typeahead, select, free)
        var cityTypeahead = cityWrap.querySelector('.city-typeahead');
        var citySelectBox = cityWrap.querySelector('.city-select');
        var cityFree = cityWrap.querySelector('.city-free');

        // Reset visibility
        [cityTypeahead, citySelectBox, cityFree].forEach(function(el) {
            if (el) el.style.display = 'none';
        });

        // Reset valori (per evitare dati vecchi che bloccano validazione)
        cityWrap.querySelectorAll('input').forEach(function(i) { i.value = ''; });
        // Reset trigger custom select
        var trigger = cityWrap.querySelector('.toa-crew-customselect-label');
        if (trigger) trigger.textContent = 'Seleziona...';

        // FIX 2026-06-29 marco — paesi con dato pieno in geo_cities usano il typeahead (come IT)
        var TYPEAHEAD_NATIONS = ['FR'];
        if (cityTypeahead) cityTypeahead.dataset.nation = nationCode;

        if (nationCode === 'IT') {
            // Provincia visibile
            if (provinceContainer) provinceContainer.style.display = '';
            // Carica province se non caricate
            var provSelect = provinceContainer ? provinceContainer.querySelector('.toa-crew-customselect') : null;
            if (provSelect && !provSelect.dataset.loaded) {
                loadJSON(THEME_URI + '/assets/data/province-italia.json').then(function(list) {
                    populateSelect(provSelect, list, function(opt, item) {
                        opt.dataset.value = item.code;
                        opt.dataset.label = item.name + ' (' + item.code + ')';
                        opt.innerHTML = '<span>' + item.name + '</span><span class="small-info">' + item.code + '</span>';
                    });
                    provSelect.dataset.loaded = '1';
                });
            }
            // Mostra typeahead comuni
            if (cityTypeahead) cityTypeahead.style.display = '';
        }
        else if (TYPEAHEAD_NATIONS.indexOf(nationCode) > -1) {
            // Francia (e futuri paesi importati in geo_cities): typeahead come l'Italia, provincia nascosta
            if (provinceContainer) provinceContainer.style.display = 'none';
            if (cityTypeahead) cityTypeahead.style.display = '';
        }
        else if (['ES','CH','GB'].indexOf(nationCode) > -1) {
            // Provincia nascosta
            if (provinceContainer) provinceContainer.style.display = 'none';
            // Mostra select città
            if (citySelectBox) {
                citySelectBox.style.display = '';
                var cs = citySelectBox.querySelector('.toa-crew-customselect');
                // Forza ricarica città per il nuovo paese
                if (cs) {
                    cs.dataset.loaded = '';
                    loadJSON(THEME_URI + '/assets/data/cities-foreign-extended.json').then(function(byNation) {
                        var cities = byNation[nationCode] || [];
                        populateSelect(cs, cities, function(opt, item) {
                            opt.dataset.value = item.code;
                            opt.dataset.label = item.name;
                            opt.textContent = item.name;
                        });
                        cs.dataset.loaded = '1';
                    });
                }
            }
        }
        else {
            // Resto del mondo: testo libero
            if (provinceContainer) provinceContainer.style.display = 'none';
            if (cityFree) cityFree.style.display = '';
        }
    }

    // Custom select città estere salva sia code che name
    document.addEventListener('toa:select', function(e) {
        var c = e.target;
        if (!c) return;
        var codeInput = c.querySelector('input[name$="_city_code"]');
        var nameInput = c.querySelector('input[name$="_city_name"]');
        if (codeInput && nameInput) {
            codeInput.value = e.detail.value;
            nameInput.value = e.detail.label;
        }
    });

    // Typeahead comuni IT — attaccato UNA volta ai campi statici
    function attachCityTypeaheadStatic(ctx) {
        var prefix = ctx === 'dom' ? 'dom' : 'res';
        var domSuffix = ctx === 'dom' ? 'Dom' : '';
        var cityWrap = document.getElementById('toaCrew' + domSuffix + 'CityWrap');
        if (!cityWrap) return;
        var typeaheadBox = cityWrap.querySelector('.city-typeahead');
        if (!typeaheadBox) return;
        var input = typeaheadBox.querySelector('input[name="' + prefix + '_city_name"]');
        var hidden = typeaheadBox.querySelector('input[name="' + prefix + '_city_code"]');
        if (!input) return;
        var suggBox = null, t;

        input.addEventListener('input', function() {
            clearTimeout(t);
            var q = this.value.trim();
            if (q.length < 2) { hideSugg(); return; }
            t = setTimeout(function() {
                fetch(GEO_ENDPOINT + '?type=cities&nation=' + encodeURIComponent(typeaheadBox.dataset.nation || 'IT') + '&q=' + encodeURIComponent(q))
                    .then(function(r){return r.json();})
                    .then(showSugg)
                    .catch(function(){});
            }, 250);
        });
        input.addEventListener('blur', function() { setTimeout(hideSugg, 200); });

        function showSugg(list) {
            hideSugg();
            if (!list || !list.length) return;
            suggBox = document.createElement('div');
            suggBox.style.cssText = 'position:absolute; background:#0e0e0e; border:1px solid rgba(200,255,0,0.3); border-radius:8px; max-height:240px; overflow:auto; z-index:1000; box-shadow:0 6px 20px rgba(0,0,0,0.6); margin-top:4px;';
            suggBox.style.width = input.offsetWidth + 'px';
            list.slice(0,12).forEach(function(c) {
                var item = document.createElement('div');
                item.style.cssText = 'padding:10px 14px; cursor:pointer; color:#fff; font-size:0.92rem;';
                item.textContent = c.display || c.name_local;
                item.addEventListener('mouseenter', function(){ item.style.background='rgba(200,255,0,0.12)'; });
                item.addEventListener('mouseleave', function(){ item.style.background='transparent'; });
                item.addEventListener('mousedown', function(e){
                    e.preventDefault();
                    input.value = c.name_local;
                    if (hidden) hidden.value = c.code;
                    hideSugg();
                });
                suggBox.appendChild(item);
            });
            var rect = input.getBoundingClientRect();
            suggBox.style.left = (rect.left + window.scrollX) + 'px';
            suggBox.style.top = (rect.bottom + window.scrollY) + 'px';
            document.body.appendChild(suggBox);
        }
        function hideSugg() {
            if (suggBox && suggBox.parentNode) suggBox.parentNode.removeChild(suggBox);
            suggBox = null;
        }
    }

    // Listener cambio nazione
    if (nationSelect) {
        nationSelect.addEventListener('toa:select', function(e) {
            handleNationChange(e.detail.value, 'res');
            // Aggiorna anche label P.IVA
            updatePivaLabel(e.detail.value);
        });
    }
    if (domNationSelect) {
        domNationSelect.addEventListener('toa:select', function(e) {
            handleNationChange(e.detail.value, 'dom');
        });
    }

    // Init iniziale (default IT)
    setTimeout(function() {
        handleNationChange('IT', 'res');
        updatePivaLabel('IT');
        // Attacca typeahead UNA volta sui campi statici (sia residenza che domicilio)
        attachCityTypeaheadStatic('res');
        attachCityTypeaheadStatic('dom');
    }, 600);

    // ─── 8. Toggle domicilio ─────────────────────────────────
    var domicilioToggleGroup = document.getElementById('toaCrewDomCoincideGroup');
    var domicilioBox = document.getElementById('toaCrewDomicilioBox');
    if (domicilioToggleGroup) {
        domicilioToggleGroup.addEventListener('toa:toggle', function(e) {
            // value '1' = coincide → nascondi box, '0' = diverso → mostra
            if (e.detail.value === '0') {
                domicilioBox.style.display = '';
                if (domNationSelect && !domNationSelect.dataset.loaded) {
                    loadNations(domNationSelect);
                    domNationSelect.dataset.loaded = '1';
                    // FIX: dopo aver caricato le nations, inizializza UI per IT (default)
                    setTimeout(function() {
                        handleNationChange('IT', 'dom');
                    }, 700);
                }
            } else {
                domicilioBox.style.display = 'none';
            }
        });
    }

    // ─── 9. Etichetta P.IVA dinamica ─────────────────────────
    function updatePivaLabel(nationCode) {
        var pivaLabel = document.getElementById('toaCrewPivaLabel');
        if (pivaLabel) {
            pivaLabel.textContent = 'Hai ' + getPivaLabel(nationCode) + '?';
        }
        var pivaInput = document.querySelector('input[name="partita_iva"]');
        if (pivaInput) {
            pivaInput.placeholder = getPivaLabel(nationCode);
        }
    }

    // ─── 10. UPLOADER ─────────────────────────────────────────
    var profileThumb = document.getElementById('toaCrewProfileThumb');
    var photosThumbs = document.getElementById('toaCrewPhotosThumbs');
    var videosThumbs = document.getElementById('toaCrewVideosThumbs');
    var photosCounter = document.getElementById('toaCrewPhotosCounter');
    var videosCounter = document.getElementById('toaCrewVideosCounter');

    function updateCounters() {
        if (photosCounter) photosCounter.innerHTML = '<strong>' + uploadState.photos.length + '</strong> / ' + MAX_PHOTOS;
        if (videosCounter) videosCounter.innerHTML = '<strong>' + uploadState.videos.length + '</strong> / ' + MAX_VIDEOS;
    }
    updateCounters();

    function setupDropzone(zoneId, inputId, mode) {
        var zone = document.getElementById(zoneId);
        var input = document.getElementById(inputId);
        if (!zone || !input) return;

        zone.addEventListener('click', function(e) {
            if (e.target === zone || e.target.closest('.toa-crew-dropzone-text, .toa-crew-dropzone-icon, .toa-crew-dropzone-hint')) {
                input.click();
            }
        });
        zone.addEventListener('dragover', function(e) { e.preventDefault(); zone.classList.add('dragover'); });
        zone.addEventListener('dragleave', function() { zone.classList.remove('dragover'); });
        zone.addEventListener('drop', function(e) {
            e.preventDefault();
            zone.classList.remove('dragover');
            handleFiles(e.dataTransfer.files, mode);
        });
        input.addEventListener('change', function() {
            handleFiles(input.files, mode);
            input.value = '';
        });
    }
    setupDropzone('toaCrewProfileDrop', 'toaCrewProfileInput', 'profile');
    setupDropzone('toaCrewPhotosDrop', 'toaCrewPhotosInput', 'photo');
    setupDropzone('toaCrewVideosDrop', 'toaCrewVideosInput', 'video');

    function handleFiles(files, mode) {
        if (mode === 'profile') {
            // 1 sola foto profilo
            var file = files[0];
            if (!file) return;
            if (!/^image\//i.test(file.type)) { alert('La foto profilo deve essere un\'immagine'); return; }
            if (file.size > MAX_PHOTO_SIZE_MB * 1024 * 1024) { alert('Foto profilo troppo grande (max ' + MAX_PHOTO_SIZE_MB + 'MB)'); return; }
            uploadState.photoProfile = { file: file, id: 'profile_' + Date.now() };
            renderProfileThumb(uploadState.photoProfile);
            return;
        }

        var maxCount = mode === 'photo' ? MAX_PHOTOS : MAX_VIDEOS;
        var maxSize = (mode === 'photo' ? MAX_PHOTO_SIZE_MB : MAX_VIDEO_SIZE_MB) * 1024 * 1024;
        var current = uploadState[mode === 'photo' ? 'photos' : 'videos'];
        var thumbsContainer = mode === 'photo' ? photosThumbs : videosThumbs;
        var typePattern = mode === 'photo' ? /^image\//i : /^video\//i;

        Array.prototype.forEach.call(files, function(file) {
            if (current.length >= maxCount) {
                alert('Hai raggiunto il limite di ' + maxCount + ' file.');
                return;
            }
            if (!typePattern.test(file.type)) { alert('File non valido: ' + file.name); return; }
            if (file.size > maxSize) {
                alert('File troppo grande: ' + file.name + ' (max ' + (maxSize/1024/1024) + ' MB)');
                return;
            }
            var fileObj = { file: file, id: Date.now() + '_' + Math.random().toString(36).slice(2,8) };
            current.push(fileObj);
            renderThumb(fileObj, thumbsContainer, mode);
        });
        updateCounters();
    }

    function renderProfileThumb(fileObj) {
        if (!profileThumb) return;
        profileThumb.innerHTML = '';
        var reader = new FileReader();
        reader.onload = function(e) {
            profileThumb.innerHTML = '<img src="' + e.target.result + '" alt="">'
                + '<button type="button" class="toa-crew-profile-thumb-remove" id="toaCrewProfileRemove">×</button>';
            document.getElementById('toaCrewProfileRemove').addEventListener('click', function() {
                uploadState.photoProfile = null;
                profileThumb.innerHTML = '';
            });
        };
        reader.readAsDataURL(fileObj.file);
    }

    function renderThumb(fileObj, container, mode) {
        var thumb = document.createElement('div');
        thumb.className = 'toa-crew-thumb';
        thumb.dataset.id = fileObj.id;
        var reader = new FileReader();
        reader.onload = function(e) {
            if (mode === 'photo') {
                thumb.innerHTML = '<img src="' + e.target.result + '" alt="">';
            } else {
                thumb.innerHTML = '<video src="' + e.target.result + '" muted></video>';
            }
            var btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'toa-crew-thumb-remove';
            btn.textContent = '×';
            btn.addEventListener('click', function() { removeFile(fileObj.id, mode); });
            thumb.appendChild(btn);
        };
        reader.readAsDataURL(fileObj.file);
        container.appendChild(thumb);
    }

    function removeFile(id, mode) {
        var key = mode === 'photo' ? 'photos' : 'videos';
        uploadState[key] = uploadState[key].filter(function(f) { return f.id !== id; });
        var container = mode === 'photo' ? photosThumbs : videosThumbs;
        var thumb = container.querySelector('[data-id="' + id + '"]');
        if (thumb) thumb.remove();
        updateCounters();
    }

    // ─── 11. Validazione step ────────────────────────────────
    function showFieldError(field, msg) {
        if (!field) return;
        field.classList.add('error');
        var err = field.parentNode.querySelector('.toa-crew-error-msg');
        if (err) { err.textContent = msg; err.classList.add('show'); }
    }
    function clearFieldErrors(scope) {
        scope.querySelectorAll('.toa-crew-input, .toa-crew-customselect').forEach(function(f){ f.classList.remove('error'); });
        scope.querySelectorAll('.toa-crew-error-msg').forEach(function(e){ e.classList.remove('show'); e.textContent = ''; });
    }
    function validateStep(n) {
        var scope = form.querySelector('.toa-crew-step[data-step="'+n+'"]');
        if (!scope) return true;
        clearFieldErrors(scope);
        var ok = true;

        if (n === 1) {
            ['nome','cognome','email','telefono','data_nascita'].forEach(function(name) {
                var f = scope.querySelector('[name="'+name+'"]');
                if (f && !f.value.trim()) { showFieldError(f, 'Campo obbligatorio'); ok = false; }
            });
            var emailF = scope.querySelector('[name="email"]');
            if (emailF && emailF.value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailF.value)) {
                showFieldError(emailF, 'Email non valida'); ok = false;
            }
            var age = calcAge(dob.value);
            if (dob.value && age < 16) { showFieldError(dob, 'Devi avere almeno 16 anni'); ok = false; }
        }

        if (n === 2) {
            var failReasons = [];

            // Nazione obbligatoria
            var nationVal = scope.querySelector('[name="res_nation"]');
            if (!nationVal || !nationVal.value) {
                if (nationSelect) nationSelect.classList.add('error');
                ok = false;
                failReasons.push('residenza: nazione vuota');
            }

            var natCode = nationVal ? nationVal.value : '';

            if (natCode === 'IT') {
                var provVal = scope.querySelector('[name="res_provincia"]');
                if (!provVal || !provVal.value) {
                    var provCs = document.getElementById('toaCrewProvince');
                    if (provCs) provCs.classList.add('error');
                    ok = false;
                    failReasons.push('residenza: provincia IT vuota (nat=' + natCode + ')');
                }
            }

            // Città visibile - residenza
            var cityWrap = document.getElementById('toaCrewCityWrap');
            if (cityWrap) {
                var visibleCityInput = null;
                var debugCityList = [];
                cityWrap.querySelectorAll('input[name="res_city_name"]').forEach(function(inp, idx) {
                    var parent = inp.closest('.city-typeahead, .city-select, .city-free');
                    var disp = parent ? parent.style.display : '?';
                    debugCityList.push('  #' + idx + ' parent=' + (parent?parent.className.replace(/[^a-z\-]/g,''):'?') + ' display="' + disp + '" value="' + inp.value + '"');
                    if (parent && parent.style.display !== 'none') {
                        visibleCityInput = inp;
                    }
                });
                if (!visibleCityInput) {
                    ok = false;
                    failReasons.push('residenza: nessun campo città visibile\n' + debugCityList.join('\n'));
                } else if (!visibleCityInput.value.trim()) {
                    showFieldError(visibleCityInput, 'Indica la città');
                    ok = false;
                    failReasons.push('residenza: città vuota (input visibile=' + (visibleCityInput.closest('.city-typeahead, .city-select, .city-free')||{}).className + ')');
                }
            } else {
                ok = false;
                failReasons.push('residenza: cityWrap non trovato');
            }

            // Domicilio diverso
            var domCoincide = scope.querySelector('[name="dom_coincide"]');
            if (domCoincide && domCoincide.value === '0') {
                var domNationVal = scope.querySelector('[name="dom_nation"]');
                if (!domNationVal || !domNationVal.value) {
                    if (domNationSelect) domNationSelect.classList.add('error');
                    ok = false;
                    failReasons.push('domicilio: nazione vuota');
                }
                var domNatCode = domNationVal ? domNationVal.value : '';
                if (domNatCode === 'IT') {
                    var domProvVal = scope.querySelector('[name="dom_provincia"]');
                    if (!domProvVal || !domProvVal.value) {
                        if (domProvinceSelect) domProvinceSelect.classList.add('error');
                        ok = false;
                        failReasons.push('domicilio: provincia IT vuota');
                    }
                }
                var domCityWrap = document.getElementById('toaCrewDomCityWrap');
                if (domCityWrap) {
                    var visDom = null;
                    var debugDomList = [];
                    domCityWrap.querySelectorAll('input[name="dom_city_name"]').forEach(function(inp, idx) {
                        var parent = inp.closest('.city-typeahead, .city-select, .city-free');
                        var disp = parent ? parent.style.display : '?';
                        debugDomList.push('  #' + idx + ' parent=' + (parent?parent.className.replace(/[^a-z\-]/g,''):'?') + ' display="' + disp + '" value="' + inp.value + '"');
                        if (parent && parent.style.display !== 'none') {
                            visDom = inp;
                        }
                    });
                    if (!visDom) {
                        ok = false;
                        failReasons.push('domicilio: nessun campo città visibile\n' + debugDomList.join('\n'));
                    } else if (!visDom.value.trim()) {
                        showFieldError(visDom, 'Indica la città');
                        ok = false;
                        failReasons.push('domicilio: città vuota');
                    }
                }
            }

            // Se fallisce, mostra solo console.warn (no alert disturbo)
            if (!ok && failReasons.length) {
                console.warn('[validateStep 2] FAILURES:\n' + failReasons.join('\n'));
            }
        }

        if (n === 3) {
            var checked = scope.querySelectorAll('input[name="categorie[]"]:checked');
            if (!checked.length) {
                var catContainer = document.getElementById('toaCrewCategories');
                if (catContainer) {
                    var err = catContainer.parentNode.querySelector('.toa-crew-error-msg');
                    if (err) { err.textContent = 'Seleziona almeno una categoria'; err.classList.add('show'); }
                }
                ok = false;
            }
        }

        if (n === 4) {
            if (!uploadState.photoProfile) {
                alert('La foto profilo è obbligatoria.');
                ok = false;
            }
            var disclaimer = scope.querySelector('[name="disclaimer_consent"]');
            if (disclaimer && !disclaimer.checked) {
                var b = disclaimer.closest('.toa-crew-field');
                if (b) {
                    var er = b.querySelector('.toa-crew-error-msg');
                    if (er) { er.textContent = 'Devi confermare di aver letto le regole'; er.classList.add('show'); }
                }
                ok = false;
            }
            var gdpr = scope.querySelector('[name="gdpr_consent"]');
            if (gdpr && !gdpr.checked) {
                var b2 = gdpr.closest('.toa-crew-field');
                if (b2) {
                    var er2 = b2.querySelector('.toa-crew-error-msg');
                    if (er2) { er2.textContent = 'Devi accettare la privacy policy'; er2.classList.add('show'); }
                }
                ok = false;
            }
        }

        return ok;
    }

    // ─── 12. Submit ──────────────────────────────────────────
    var REGISTER_ENDPOINT = '/crm_toagency/actions/registra-crew.php';
    var UPLOAD_ENDPOINT = '/crm_toagency/actions/upload-portfolio-crew.php';
    var crewIdAfterRegister = null;
    var crewUuidAfterRegister = null;
    var crewTokenAfterRegister = null;

    /* TASK hardening-upload-crew 2026-06-04 marco — compressione foto client (identica a talent-form-v40.js) */
    function toaCompressImage(file, maxLong, quality){
      maxLong = maxLong || 1280; quality = quality || 0.78;
      return new Promise(function(resolve){
        if (!file || !/^image\//i.test(file.type||'')) { resolve(file); return; }
        var url = URL.createObjectURL(file), img = new Image();
        img.onload = function(){
          try {
            var w=img.naturalWidth, h=img.naturalHeight;
            if(!w||!h){ URL.revokeObjectURL(url); resolve(file); return; }
            var scale = Math.min(1, maxLong/Math.max(w,h));
            var nw=Math.round(w*scale), nh=Math.round(h*scale);
            var c=document.createElement('canvas'); c.width=nw; c.height=nh;
            c.getContext('2d').drawImage(img,0,0,nw,nh);
            URL.revokeObjectURL(url);
            c.toBlob(function(blob){
              if(!blob){ resolve(file); return; }
              if(scale===1 && blob.size>=file.size){ resolve(file); return; }
              var name=(file.name||'foto').replace(/\.(heic|heif|png|webp|gif|jpe?g)$/i,'')+'.jpg';
              resolve(new File([blob], name, {type:'image/jpeg', lastModified:Date.now()}));
            }, 'image/jpeg', quality);
          } catch(e){ URL.revokeObjectURL(url); resolve(file); }
        };
        img.onerror = function(){ URL.revokeObjectURL(url); resolve(file); }; // HEIC non decodificabile → originale, lo gestisce il server/cron
        img.src = url;
      });
    }

    // Upload sequenziale: foto profilo → foto portfolio → video
    function uploadOneFile(crewId, token, file, tipo) {
        /* TASK hardening-upload-crew 2026-06-04 marco — comprimi prima di spedire (video passano intatti), poi catena identica */
        return toaCompressImage(file, 1280, 0.78).then(function(cfile) {
            var fd = new FormData();
            fd.append('crew_id', crewId);
            fd.append('token_profilo', token);
            fd.append('tipo', tipo);
            fd.append('file', cfile);
            return fetch(UPLOAD_ENDPOINT, {
                method: 'POST',
                body: fd,
                credentials: 'same-origin'
            }).then(function(r) {
                return r.text().then(function(t) {
                    try { return JSON.parse(t); }
                    catch (e) {
                        console.error('[upload] non-JSON:', t.substring(0, 200));
                        return { ok: false, error: 'invalid_response' };
                    }
                });
            });
        });
    }

    function uploadAllFiles(crewId, token) {
        console.log('[uploadAll] CHIAMATA. uploadState:', uploadState);
        console.log('[uploadAll] photoProfile:', uploadState.photoProfile);
        console.log('[uploadAll] photos count:', uploadState.photos.length, uploadState.photos);
        console.log('[uploadAll] videos count:', uploadState.videos.length, uploadState.videos);

        var queue = [];
        // 1. Foto profilo
        if (uploadState.photoProfile && uploadState.photoProfile.file) {
            queue.push({ file: uploadState.photoProfile.file, tipo: 'foto_profilo' });
        }
        // 2. Foto portfolio
        uploadState.photos.forEach(function(p) {
            queue.push({ file: p.file, tipo: 'foto' });
        });
        // 3. Video
        uploadState.videos.forEach(function(v) {
            queue.push({ file: v.file, tipo: 'video' });
        });

        console.log('[uploadAll] queue length:', queue.length, queue);

        if (queue.length === 0) {
            console.warn('[uploadAll] QUEUE VUOTA - nessun file da uploadare!');
            return Promise.resolve([]);
        }

        // Mostra messaggio "Upload in corso..."
        var submitBtn = document.getElementById('toaCrewSubmit');
        var totalCount = queue.length;
        var doneCount = 0;
        if (submitBtn) {
            submitBtn.textContent = 'Upload file 0/' + totalCount + '...';
        }

        // Upload sequenziale per non saturare il server
        var promise = Promise.resolve();
        var results = [];
        queue.forEach(function(item) {
            promise = promise.then(function() {
                return uploadOneFile(crewId, token, item.file, item.tipo).then(function(res) {
                    doneCount++;
                    if (submitBtn) {
                        submitBtn.textContent = 'Upload file ' + doneCount + '/' + totalCount + '...';
                    }
                    results.push(res);
                    return res;
                });
            });
        });
        return promise.then(function() { return results; });
    }

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        for (var i = 1; i <= 4; i++) {
            if (!validateStep(i)) { showStep(i); return; }
        }

        // Disabilita bottone submit per evitare doppi invii
        var submitBtn = document.getElementById('toaCrewSubmit');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.textContent = 'Invio in corso...';
        }

        // FIX v3.3: Disabilita gli input nei container città NASCOSTI
        // Altrimenti FormData prende l'ULTIMO valore (vuoto) sovrascrivendo quello buono
        ['toaCrewCityWrap', 'toaCrewDomCityWrap'].forEach(function(wrapId) {
            var wrap = document.getElementById(wrapId);
            if (!wrap) return;
            wrap.querySelectorAll('.city-typeahead, .city-select, .city-free').forEach(function(box) {
                var hidden = box.style.display === 'none';
                box.querySelectorAll('input').forEach(function(inp) {
                    inp.disabled = hidden;
                });
            });
        });

        // Prepara FormData con TUTTI i campi del form
        var fd = new FormData(form);
        // Aggiungi lingua corrente (per contenuti email future)
        var htmlLang = document.documentElement.getAttribute('lang') || 'it';
        fd.append('lang', htmlLang.substring(0, 2));

        fetch(REGISTER_ENDPOINT, {
            method: 'POST',
            body: fd,
            credentials: 'same-origin'
        })
        .then(function(r) {
            return r.text().then(function(txt) {
                try { return JSON.parse(txt); }
                catch (e) {
                    console.error('[register] risposta non JSON:', txt);
                    throw new Error('Risposta server non valida');
                }
            });
        })
        .then(function(res) {
            console.log('[register] response:', res);
            if (res.ok) {
                crewIdAfterRegister = res.crew_id;
                crewUuidAfterRegister = res.uuid;
                crewTokenAfterRegister = res.token_profilo;
                // Avvia upload sequenziale dei file caricati
                uploadAllFiles(res.crew_id, res.token_profilo)
                    .then(function() { showSuccess(); })
                    .catch(function(err) {
                        console.error('[upload] error:', err);
                        // Profilo creato ma upload fallito → mostra success con avviso
                        showSuccess();
                    });
            } else {
                handleRegisterError(res);
            }
        })
        .catch(function(err) {
            console.error('[register] errore network:', err);
            alert('Errore di connessione. Riprova fra qualche secondo.');
        })
        .finally(function() {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Invia candidatura';
            }
            // Riabilita TUTTI gli input dei container città (per permettere di riprovare)
            ['toaCrewCityWrap', 'toaCrewDomCityWrap'].forEach(function(wrapId) {
                var wrap = document.getElementById(wrapId);
                if (!wrap) return;
                wrap.querySelectorAll('input').forEach(function(inp) { inp.disabled = false; });
            });
        });
    });

    function handleRegisterError(res) {
        var errorMsg = res.message || 'Errore sconosciuto';
        var stepToGo = 4;

        // Mappa errori → step a cui tornare
        switch (res.error) {
            case 'missing_name':
            case 'invalid_email':
            case 'missing_phone':
            case 'missing_dob':
            case 'age_too_low':
            case 'missing_parent_data':
                stepToGo = 1;
                break;
            case 'missing_nation':
            case 'missing_city':
            case 'missing_province':
            case 'missing_domicilio':
                stepToGo = 2;
                break;
            case 'missing_category':
            case 'age_restricted_category':
                stepToGo = 3;
                break;
            case 'email_exists':
                errorMsg = 'Questa email è già registrata. Se è la tua, contattaci.';
                stepToGo = 1;
                break;
            default:
                stepToGo = 4;
        }

        alert('⚠️ ' + errorMsg);
        showStep(stepToGo);
    }

    function showSuccess() {
        successModal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
    if (successCloseBtn) {
        successCloseBtn.addEventListener('click', function() {
            successModal.classList.remove('show');
            document.body.style.overflow = '';
            form.reset();
            uploadState = { photoProfile: null, photos: [], videos: [] };
            if (profileThumb) profileThumb.innerHTML = '';
            if (photosThumbs) photosThumbs.innerHTML = '';
            if (videosThumbs) videosThumbs.innerHTML = '';
            updateCounters();
            document.querySelectorAll('.toa-crew-category-chip.checked').forEach(function(c){ c.classList.remove('checked'); });
            showStep(1);
        });
    }
})();
