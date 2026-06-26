/**
 * TOAgency — Form registrazione talent
 * v2.0 — 8 Maggio 2026
 * Path: /wp-content/themes/toagency-theme/assets/talent-form-v40.js
 *
 * v2.0 changes:
 * - Tipo talent forzato a 'immagine' (backstage → form crew)
 * - Nessun vincolo età sui ruoli (check nei casting, non in registrazione)
 * - 0-15: dati genitore obbligatori (genitore compila)
 * - 16-17: checkbox conferma genitore
 * - 18+: autonomo
 * - GDPR potenziato per minori
 * - Rimossa email genitore / token / attesa_genitore
 */

(function () {
    'use strict';

    var GEO_ENDPOINT = '/crm_toagency/actions/cerca-comune.php';
    var THEME_URI = (window.toaThemeUri || '/wp-content/themes/toagency-theme');

    var MAX_PHOTOS = 15;
    var MAX_PHOTO_SIZE_MB = 60; /* TASK hardening-upload STEP A 2026-06-04 marco — era 15: le foto grandi ora passano e vengono compresse client-side; resta backstop per file assurdi */

    // ─── i18n messaggi runtime (lingua WP corrente — window.toaTalentLang) ───
    var TLANG = (window.toaTalentLang || 'it');
    function tmsg(m) { return m[TLANG] || m.it; }
    var MSG = {
        required:      { it:'Campo obbligatorio', en:'Required field', es:'Campo obligatorio', fr:'Champ obligatoire' },
        reqSuffix:     { it:'è obbligatorio', en:'is required', es:'es obligatorio', fr:'est obligatoire' },
        pickGender:    { it:'Indica il sesso', en:'Please select gender', es:'Indica el género', fr:'Indique le genre' },
        reqMinor:      { it:'Campo obbligatorio per minori sotto 16 anni', en:'Required for minors under 16', es:'Obligatorio para menores de 16 años', fr:'Obligatoire pour les mineurs de moins de 16 ans' },
        parentConfirm: { it:'La conferma del genitore è obbligatoria', en:'Parent confirmation is required', es:'La confirmación del padre es obligatoria', fr:'La confirmation du parent est obligatoire' },
        pickCity:      { it:'Indica la città', en:'Please enter the city', es:'Indica la ciudad', fr:'Indique la ville' },
        pickRole:      { it:'Seleziona almeno un ruolo', en:'Select at least one role', es:'Selecciona al menos un rol', fr:'Sélectionne au moins un rôle' },
        pickEthnicity: { it:"Seleziona almeno un'etnia", en:'Select at least one ethnicity', es:'Selecciona al menos una etnia', fr:'Sélectionne au moins une origine' },
        photoReq:      { it:'Il primo piano è obbligatorio.', en:'The close-up photo is required.', es:'El primer plano es obligatorio.', fr:'Le gros plan est obligatoire.' },
        rulesReq:      { it:'Devi confermare di aver letto le regole', en:'You must confirm you have read the rules', es:'Debes confirmar que has leído las reglas', fr:'Tu dois confirmer avoir lu les règles' },
        gdprReq:       { it:'Devi accettare la privacy policy', en:'You must accept the privacy policy', es:'Debes aceptar la política de privacidad', fr:'Tu dois accepter la politique de confidentialité' },
        emailInvalid:       { it:'Email non valida', en:'Invalid email', fr:'Email invalide', es:'Email no válida' },
        parentEmailInvalid: { it:'Email genitore non valida', en:'Invalid parent email', fr:'Email du parent invalide', es:'Email del padre no válida' },
        photoMustBeImage:  { it:'La foto deve essere un\'immagine', en:'The photo must be an image', es:'La foto debe ser una imagen', fr:'La photo doit être une image' },
        photoTooBig:       { it:'Foto troppo grande', en:'Photo too large', es:'Foto demasiado grande', fr:'Photo trop grande' },
        maxPhotos:         { it:'Hai raggiunto il limite di foto', en:'You reached the photo limit', es:'Has alcanzado el límite de fotos', fr:'Limite de photos atteinte' },
        fileInvalid:       { it:'File non valido', en:'Invalid file', es:'Archivo no válido', fr:'Fichier non valide' },
        fileTooBig:        { it:'File troppo grande', en:'File too large', es:'Archivo demasiado grande', fr:'Fichier trop grand' },
        uploadPartialFail: { it:'Profilo creato, ma il caricamento di alcuni file non è riuscito: potrai aggiungerli più tardi dalla tua area personale.', en:'Profile created, but some files failed to upload — you can add them later from your area.', es:'Perfil creado, pero algunos archivos no se subieron: podrás añadirlos más tarde desde tu área.', fr:'Profil créé, mais certains fichiers n\'ont pas pu être chargés : tu pourras les ajouter plus tard depuis ton espace.' },
        networkError:      { it:'Errore di connessione. Riprova fra qualche secondo.', en:'Connection error. Please try again in a few seconds.', es:'Error de conexión. Inténtalo de nuevo en unos segundos.', fr:'Erreur de connexion. Réessaie dans quelques secondes.' },
        emailExists:       { it:'Questa email è già registrata. Recupera il tuo profilo qui sotto.', en:'This email is already registered. Recover your profile below.', es:'Este email ya está registrado. Recupera tu perfil abajo.', fr:'Cet email est déjà enregistré. Récupère ta fiche ci-dessous.' },
        // FIX 2026-06-25 marco — foto profilo non caricata: si riprova SOLO la foto (il profilo esiste già)
        photoUploadFail:   { it:'La foto profilo non è stata caricata. Premi di nuovo "Invia" per riprovare a caricarla.', en:'Your profile photo was not uploaded. Press "Submit" again to retry.', es:'La foto de perfil no se subió. Pulsa "Enviar" de nuevo para reintentar.', fr:'Ta photo de profil n\'a pas été chargée. Appuie de nouveau sur « Envoyer » pour réessayer.' }
    };
    var FLBL = {
        altezza: { it:'Altezza', en:'Height', es:'Altura', fr:'Taille' },
        scarpe:  { it:'Numero scarpe', en:'Shoe size', es:'Número de calzado', fr:'Pointure' }
    };

    var uploadState = {
        photoProfile: null,
        photos: []
    };

    // Stato globale tipo talent: sempre 'immagine' (backstage → form crew)
    var tipoTalentSelected = 'immagine';

    var form = document.getElementById('toaTalentForm');
    if (!form) return;

    var steps = form.querySelectorAll('.toa-talent-step');
    var progressDots = form.querySelectorAll('.toa-talent-progress-step');
    var genitoreBox015 = document.getElementById('toaTalentGenitore015');
    var genitoreBox1617 = document.getElementById('toaTalentGenitore1617');
    var successModal = document.getElementById('toaTalentSuccess');
    var successCloseBtn = document.getElementById('toaTalentSuccessClose');

    // Custom select containers
    var phonePrefixSelect = document.getElementById('toaTalentPhonePrefix');
    var nationSelect = document.getElementById('toaTalentNation');
    var domNationSelect = document.getElementById('toaTalentDomNation');
    var provinceSelect = document.getElementById('toaTalentProvince');
    var domProvinceSelect = document.getElementById('toaTalentDomProvince');

    // Tipo talent + sezioni condizionali
    var tipoGroup = document.getElementById('toaTalentTipoGroup');
    var ruoliImmagineBox = document.getElementById('toaTalentRuoliImmagine');
    var ruoliBackstageBox = document.getElementById('toaTalentRuoliBackstage');
    var fisicoBox = document.getElementById('toaTalentFisico');
    var misureBox = document.getElementById('toaTalentMisure');

    // ─── 1. Navigazione step ─────────────────────────────────
    function showStep(n) {
        steps.forEach(function(s) { s.classList.remove('active'); });
        progressDots.forEach(function(d) { d.classList.remove('active'); });
        var target = form.querySelector('.toa-talent-step[data-step="'+n+'"]');
        if (target) target.classList.add('active');
        progressDots.forEach(function(d) {
            if (parseInt(d.dataset.step) <= n) d.classList.add('active');
        });
        window.scrollTo({ top: form.offsetTop - 80, behavior: 'smooth' });
    }
    form.querySelectorAll('[data-go]').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var to = parseInt(this.dataset.go);
            var current = parseInt(form.querySelector('.toa-talent-step.active').dataset.step);
            if (to > current && !validateStep(current)) {
                // Scroll automatico al primo campo in errore — niente "blocco silenzioso" (2026-05-30)
                var stepEl = form.querySelector('.toa-talent-step[data-step="'+current+'"]');
                var firstErr = stepEl ? stepEl.querySelector('.error, .toa-talent-error-msg.show') : null;
                if (firstErr) firstErr.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return;
            }
            // FIX 2026-06-25 marco — uscendo dallo step 1: controlla SUBITO se l'email è già registrata
            // (così non si compila tutto per poi essere respinti all'invio finale).
            if (current === 1 && to > current) {
                var goBtn = this;
                var emailEl = form.querySelector('[name="email"]');
                var emailVal = emailEl ? emailEl.value.trim() : '';
                if (emailVal) {
                    goBtn.disabled = true;
                    checkEmailExists(emailVal).then(function(exists) {
                        goBtn.disabled = false;
                        if (exists) {
                            showInlineError('toaTalentFormError', '⚠️ ' + tmsg(MSG.emailExists));
                            showRecover();
                            if (emailEl) emailEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        } else {
                            hideRecover();
                            showStep(to);
                        }
                    }).catch(function() { goBtn.disabled = false; showStep(to); }); // errore rete → non bloccare (l'invio finale resta da backstop)
                    return;
                }
            }
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
            toggleGenitoreByAge(age);
        });
    }

    /**
     * Mostra/nasconde le sezioni genitore in base all'età.
     * 0-15: mostra box dati genitore completi
     * 16-17: mostra box checkbox conferma genitore
     * 18+: nasconde tutto
     */
    function toggleGenitoreByAge(age) {
        var show015 = (age !== null && age >= 0 && age < 16);
        var show1617 = (age !== null && age >= 16 && age < 18);

        if (genitoreBox015) {
            genitoreBox015.classList.toggle('show', show015);
            genitoreBox015.querySelectorAll('input').forEach(function(i){
                if (!show015) { i.value = ''; }
            });
        }
        if (genitoreBox1617) {
            genitoreBox1617.classList.toggle('show', show1617);
            if (!show1617) {
                var cb = genitoreBox1617.querySelector('input[name="parent_confirm"]');
                if (cb) cb.checked = false;
            }
        }
    }

    // ─── 3. Categorie chip ───────────────────────────────────
    document.querySelectorAll('.toa-talent-category-chip').forEach(function(chip){
        var cb = chip.querySelector('input');
        if(!cb) return;
        // FIX 2026-06-01 marco: rimosso doppio-toggle label/checkbox (race _toaLock).
        // Il toggle è lasciato al comportamento NATIVO della label+checkbox (1 solo toggle
        // per click fisico); reagiamo al 'change' nativo per aggiornare la classe visiva.
        // Il 'change' nativo continua ad alimentare updateMisureVisibility e il limite
        // max-2 etnie; le checkbox disabilitate dal limite etnie non vengono toggolate dal
        // nativo, quindi il max-2 resta rispettato automaticamente.
        cb.addEventListener('change', function(){ chip.classList.toggle('checked', cb.checked); });
    });

    // ─── 3b. Tipo talent (immagine vs backstage) ──────────────
    if (tipoGroup) {
        tipoGroup.querySelectorAll('.toa-talent-tipo-card').forEach(function(card) {
            card.addEventListener('click', function() {
                if (card.classList.contains('disabled')) return;
                tipoGroup.querySelectorAll('.toa-talent-tipo-card').forEach(function(c){
                    c.classList.remove('active');
                });
                card.classList.add('active');
                tipoTalentSelected = card.dataset.value || '';
                var hidden = tipoGroup.querySelector('input[name="tipo_talent"]');
                if (hidden) hidden.value = tipoTalentSelected;
                updateRuoliVisibility();
            });
        });
    }

    /**
     * Mostra il blocco ruoli + caratteristiche fisiche pertinente al tipo.
     * Resetta i checkbox dell'altro gruppo per evitare ruoli misti.
     */
    function updateRuoliVisibility() {
        var isImg = (tipoTalentSelected === 'immagine');
        var isBks = (tipoTalentSelected === 'backstage');
        if (ruoliImmagineBox) ruoliImmagineBox.style.display = isImg ? '' : 'none';
        if (ruoliBackstageBox) ruoliBackstageBox.style.display = isBks ? '' : 'none';
        if (fisicoBox) fisicoBox.style.display = isImg ? '' : 'none';

        // Reset checkbox del gruppo non visibile
        if (!isImg) {
            document.querySelectorAll('input[name="ruoli_immagine[]"]:checked').forEach(function(cb){
                cb.checked = false;
                var chip = cb.closest('.toa-talent-category-chip');
                if (chip) chip.classList.remove('checked');
            });
        }
        if (!isBks) {
            document.querySelectorAll('input[name="ruoli_backstage[]"]:checked').forEach(function(cb){
                cb.checked = false;
                var chip = cb.closest('.toa-talent-category-chip');
                if (chip) chip.classList.remove('checked');
            });
        }
    }

    // ─── 3c. Misure corpo → visibili se selezionato ruolo modello/a ───────────
    var sessoGroup = form.querySelector('.toa-talent-toggle-group input[name="sesso"]');
    var sessoToggleParent = sessoGroup ? sessoGroup.closest('.toa-talent-toggle-group') : null;
    function updateMisureVisibility() {
        if (!misureBox) return;
        var modelChecked = form.querySelector('input[name="ruoli_immagine[]"][value="model"]:checked');
        misureBox.style.display = modelChecked ? '' : 'none';
        if (!modelChecked) {
            misureBox.querySelectorAll('input').forEach(function(i){ i.value = ''; });
        }
    }
    // Ascolta click su tutti i chip ruolo per aggiornare visibilità misure
    document.querySelectorAll('#toaTalentCategoriesImmagine input[type="checkbox"]').forEach(function(cb) {
        cb.addEventListener('change', updateMisureVisibility);
    });

    // ─── 4. Custom Select ─────────────────────────────────────
    function initCustomSelect(container) {
        var trigger = container.querySelector('.toa-talent-customselect-trigger');
        var optionsBox = container.querySelector('.toa-talent-customselect-options');
        var hiddenInput = container.querySelector('input[type="hidden"]');
        var searchInput = container.querySelector('.toa-talent-customselect-search input');

        trigger.addEventListener('click', function(e) {
            e.stopPropagation();
            document.querySelectorAll('.toa-talent-customselect.open').forEach(function(c) {
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
                optionsBox.querySelectorAll('.toa-talent-customselect-option').forEach(function(opt) {
                    var txt = opt.textContent.toLowerCase();
                    opt.style.display = txt.indexOf(q) > -1 ? '' : 'none';
                });
            });
        }

        // Delegate event sui figli
        optionsBox.addEventListener('click', function(e) {
            var opt = e.target.closest('.toa-talent-customselect-option');
            if (!opt) return;
            optionsBox.querySelectorAll('.toa-talent-customselect-option').forEach(function(o){
                o.classList.remove('selected');
            });
            opt.classList.add('selected');
            hiddenInput.value = opt.dataset.value;
            trigger.querySelector('.toa-talent-customselect-label').textContent = opt.dataset.label || opt.textContent;
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
    document.querySelectorAll('.toa-talent-customselect').forEach(initCustomSelect);
    document.addEventListener('click', function() {
        document.querySelectorAll('.toa-talent-customselect.open').forEach(function(c){
            c.classList.remove('open');
        });
    });

    // ─── 5. Toggle sì/no ──────────────────────────────────────
    document.querySelectorAll('.toa-talent-toggle-group').forEach(function(group) {
        var hidden = group.querySelector('input[type="hidden"]');
        var conditional = group.parentNode.querySelector('.toa-talent-conditional');

        group.querySelectorAll('.toa-talent-toggle').forEach(function(btn) {
            btn.addEventListener('click', function() {
                group.querySelectorAll('.toa-talent-toggle').forEach(function(b){ b.classList.remove('active'); });
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
        var optionsBox = container.querySelector('.toa-talent-customselect-options');
        optionsBox.innerHTML = '';
        items.forEach(function(item) {
            var opt = document.createElement('div');
            opt.className = 'toa-talent-customselect-option';
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
                    phonePrefixSelect.querySelector('.toa-talent-customselect-label').textContent = item.flag + ' ' + item.prefix;
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
                phonePrefixSelect.querySelector('.toa-talent-customselect-label').textContent = item.flag + ' ' + item.prefix;
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
                    targetSelect.querySelector('.toa-talent-customselect-label').textContent = item.display || item.name_local;
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
        var provinceContainer = document.getElementById('toaTalent' + domSuffix + 'ProvinceWrap');
        var cityWrap = document.getElementById('toaTalent' + domSuffix + 'CityWrap');

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
        var trigger = cityWrap.querySelector('.toa-talent-customselect-label');
        if (trigger) trigger.textContent = 'Seleziona...';

        if (nationCode === 'IT') {
            // Provincia visibile
            if (provinceContainer) provinceContainer.style.display = '';
            // Carica province se non caricate
            var provSelect = provinceContainer ? provinceContainer.querySelector('.toa-talent-customselect') : null;
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
        else if (['FR','ES','CH','GB'].indexOf(nationCode) > -1) {
            // Provincia nascosta
            if (provinceContainer) provinceContainer.style.display = 'none';
            // Mostra select città
            if (citySelectBox) {
                citySelectBox.style.display = '';
                var cs = citySelectBox.querySelector('.toa-talent-customselect');
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
        var cityWrap = document.getElementById('toaTalent' + domSuffix + 'CityWrap');
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
                fetch(GEO_ENDPOINT + '?type=cities&nation=IT&q=' + encodeURIComponent(q))
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
        // Attacca typeahead UNA volta sui campi statici (sia residenza che domicilio)
        attachCityTypeaheadStatic('res');
        attachCityTypeaheadStatic('dom');
    }, 600);

    // ─── 8. Toggle domicilio ─────────────────────────────────
    var domicilioToggleGroup = document.getElementById('toaTalentDomCoincideGroup');
    var domicilioBox = document.getElementById('toaTalentDomicilioBox');
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

    // ─── 9. UPLOADER ──────────────────────────────────────────
    var profileThumb = document.getElementById('toaTalentProfileThumb');
    var photosThumbs = document.getElementById('toaTalentPhotosThumbs');
    var photosCounter = document.getElementById('toaTalentPhotosCounter');

    function updateCounters() {
        if (photosCounter) photosCounter.innerHTML = '<strong>' + uploadState.photos.length + '</strong> / ' + MAX_PHOTOS;
    }
    updateCounters();

    function setupDropzone(zoneId, inputId, mode) {
        var zone = document.getElementById(zoneId);
        var input = document.getElementById(inputId);
        if (!zone || !input) return;

        zone.addEventListener('click', function(e) {
            if (e.target === zone || e.target.closest('.toa-talent-dropzone-text, .toa-talent-dropzone-icon, .toa-talent-dropzone-hint')) {
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
    setupDropzone('toaTalentProfileDrop', 'toaTalentProfileInput', 'profile');
    setupDropzone('toaTalentPhotosDrop', 'toaTalentPhotosInput', 'photo');

    function handleFiles(files, mode) {
        if (mode === 'profile') {
            // 1 sola foto profilo
            var file = files[0];
            if (!file) return;
            if (!/^image\//i.test(file.type)) { showInlineError('toaTalentProfileError', tmsg(MSG.photoMustBeImage), true); return; }
            if (file.size > MAX_PHOTO_SIZE_MB * 1024 * 1024) { showInlineError('toaTalentProfileError', tmsg(MSG.photoTooBig) + ' (max ' + MAX_PHOTO_SIZE_MB + 'MB)', true); return; }
            uploadState.photoProfile = { file: file, id: 'profile_' + Date.now() };
            renderProfileThumb(uploadState.photoProfile);
            return;
        }

        // Solo mode='photo' (no video)
        var maxSize = MAX_PHOTO_SIZE_MB * 1024 * 1024;

        Array.prototype.forEach.call(files, function(file) {
            if (uploadState.photos.length >= MAX_PHOTOS) {
                showInlineError('toaTalentPhotosError', tmsg(MSG.maxPhotos) + ' (' + MAX_PHOTOS + ')', true);
                return;
            }
            if (!/^image\//i.test(file.type)) { showInlineError('toaTalentPhotosError', tmsg(MSG.fileInvalid) + ': ' + file.name, true); return; }
            if (file.size > maxSize) {
                showInlineError('toaTalentPhotosError', tmsg(MSG.fileTooBig) + ': ' + file.name + ' (max ' + MAX_PHOTO_SIZE_MB + 'MB)', true);
                return;
            }
            var fileObj = { file: file, id: Date.now() + '_' + Math.random().toString(36).slice(2,8) };
            uploadState.photos.push(fileObj);
            renderThumb(fileObj, photosThumbs, 'photo');
        });
        updateCounters();
    }

    function renderProfileThumb(fileObj) {
        if (!profileThumb) return;
        profileThumb.innerHTML = '';
        var reader = new FileReader();
        reader.onload = function(e) {
            profileThumb.innerHTML = '<img src="' + e.target.result + '" alt="">'
                + '<button type="button" class="toa-talent-profile-thumb-remove" id="toaTalentProfileRemove">×</button>';
            document.getElementById('toaTalentProfileRemove').addEventListener('click', function() {
                uploadState.photoProfile = null;
                profileThumb.innerHTML = '';
            });
        };
        reader.readAsDataURL(fileObj.file);
    }

    function renderThumb(fileObj, container, mode) {
        var thumb = document.createElement('div');
        thumb.className = 'toa-talent-thumb';
        thumb.dataset.id = fileObj.id;
        var reader = new FileReader();
        reader.onload = function(e) {
            thumb.innerHTML = '<img src="' + e.target.result + '" alt="">';
            var btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'toa-talent-thumb-remove';
            btn.textContent = '×';
            btn.addEventListener('click', function() { removeFile(fileObj.id); });
            thumb.appendChild(btn);
        };
        reader.readAsDataURL(fileObj.file);
        container.appendChild(thumb);
    }

    function removeFile(id) {
        uploadState.photos = uploadState.photos.filter(function(f) { return f.id !== id; });
        var thumb = photosThumbs.querySelector('[data-id="' + id + '"]');
        if (thumb) thumb.remove();
        updateCounters();
    }

    // ─── 11. Validazione step ────────────────────────────────
    function showFieldError(field, msg) {
        if (!field) return;
        field.classList.add('error');
        var err = field.parentNode.querySelector('.toa-talent-error-msg');
        if (err) { err.textContent = msg; err.classList.add('show'); }
    }
    function clearFieldErrors(scope) {
        scope.querySelectorAll('.toa-talent-input, .toa-talent-customselect').forEach(function(f){ f.classList.remove('error'); });
        scope.querySelectorAll('.toa-talent-error-msg').forEach(function(e){ e.classList.remove('show'); e.textContent = ''; });
    }
    // Mostra un messaggio inline in un .toa-talent-error-msg (sostituisce alert()).
    // el può essere un elemento o un id. auto=true → si auto-nasconde dopo 6s (errori upload transitori).
    function showInlineError(el, msg, auto) {
        if (typeof el === 'string') el = document.getElementById(el);
        if (!el) return;
        el.textContent = msg;
        el.classList.add('show');
        try { el.scrollIntoView({ behavior: 'smooth', block: 'center' }); } catch (e) {}
        if (el._hideT) clearTimeout(el._hideT);
        if (auto) el._hideT = setTimeout(function(){ el.classList.remove('show'); el.textContent = ''; }, 6000);
    }
    function validateStep(n) {
        var scope = form.querySelector('.toa-talent-step[data-step="'+n+'"]');
        if (!scope) return true;
        clearFieldErrors(scope);
        var ok = true;

        if (n === 1) {
            ['nome','cognome','email','telefono','data_nascita'].forEach(function(name) {
                var f = scope.querySelector('[name="'+name+'"]');
                if (f && !f.value.trim()) { showFieldError(f, tmsg(MSG.required)); ok = false; }
            });
            var emailF = scope.querySelector('[name="email"]');
            if (emailF && emailF.value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailF.value)) {
                showFieldError(emailF, tmsg(MSG.emailInvalid)); ok = false;
            }

            // Sesso obbligatorio
            var sessoF = scope.querySelector('input[name="sesso"]');
            if (sessoF && !sessoF.value) {
                var sessoBox = sessoF.closest('.toa-talent-field');
                if (sessoBox) {
                    var er = sessoBox.querySelector('.toa-talent-error-msg');
                    if (er) { er.textContent = tmsg(MSG.pickGender); er.classList.add('show'); }
                }
                ok = false;
            }

            // Validazione genitore in base all'età
            var age = calcAge(dob.value);

            if (age >= 0 && age < 16) {
                // 0-15: dati genitore obbligatori
                ['genitore1_nome','genitore1_email','genitore1_telefono'].forEach(function(name) {
                    var f = scope.querySelector('[name="'+name+'"]');
                    if (f && !f.value.trim()) { showFieldError(f, tmsg(MSG.reqMinor)); ok = false; }
                });
                var gEmail = scope.querySelector('[name="genitore1_email"]');
                if (gEmail && gEmail.value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(gEmail.value)) {
                    showFieldError(gEmail, tmsg(MSG.parentEmailInvalid)); ok = false;
                }
            } else if (age >= 16 && age < 18) {
                // 16-17: checkbox conferma genitore obbligatorio
                var parentConfirm = document.querySelector('input[name="parent_confirm"]');
                if (parentConfirm && !parentConfirm.checked) {
                    var pcBox = parentConfirm.closest('.toa-talent-field');
                    if (pcBox) {
                        var pcErr = pcBox.querySelector('.toa-talent-error-msg');
                        if (pcErr) { pcErr.textContent = tmsg(MSG.parentConfirm); pcErr.classList.add('show'); }
                    }
                    ok = false;
                }
            }
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
                    var provCs = document.getElementById('toaTalentProvince');
                    if (provCs) provCs.classList.add('error');
                    ok = false;
                    failReasons.push('residenza: provincia IT vuota (nat=' + natCode + ')');
                }
            }

            // Città visibile - residenza
            var cityWrap = document.getElementById('toaTalentCityWrap');
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
                    showFieldError(visibleCityInput, tmsg(MSG.pickCity));
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
                var domCityWrap = document.getElementById('toaTalentDomCityWrap');
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
                        showFieldError(visDom, tmsg(MSG.pickCity));
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
            // Ruolo "selezionato" = chip con classe .checked (verità visiva: la checkbox è display:none).
            // Sincronizziamo input.checked alla classe così il valore parte anche nel FormData. (fix 2026-05-31)
            var roleChips = scope.querySelectorAll('#toaTalentCategoriesImmagine .toa-talent-category-chip');
            var rolesSelected = 0;
            roleChips.forEach(function(c){
                var i = c.querySelector('input[name="ruoli_immagine[]"]');
                var sel = (i && i.checked) || c.classList.contains('checked');
                if (i) i.checked = !!sel; // assicura sincronizzazione
                if (sel) rolesSelected++;
            });
            if (!rolesSelected) {
                var ruoliErr = ruoliImmagineBox ? ruoliImmagineBox.querySelector('.toa-talent-error-msg') : null;
                if (ruoliErr) { ruoliErr.textContent = tmsg(MSG.pickRole); ruoliErr.classList.add('show'); }
                ok = false;
            }

            // Caratteristiche fisiche obbligatorie — messaggio multilingua "[campo] è obbligatorio"
            ['altezza','scarpe'].forEach(function(name) {
                var f = scope.querySelector('[name="'+name+'"]');
                if (f && !f.value.trim()) {
                    showFieldError(f, tmsg(FLBL[name]) + ' ' + tmsg(MSG.reqSuffix));
                    ok = false;
                }
            });
            // Custom select obbligatori
            ['taglia','occhi','capelli'].forEach(function(name) {
                var f = scope.querySelector('[name="'+name+'"]');
                if (f && !f.value) {
                    var cs = f.closest('.toa-talent-customselect');
                    if (cs) cs.classList.add('error');
                    var errDiv = f.closest('.toa-talent-field') ? f.closest('.toa-talent-field').querySelector('.toa-talent-error-msg') : null;
                    if (errDiv) { errDiv.textContent = tmsg(MSG.required); errDiv.classList.add('show'); }
                    ok = false;
                }
            });

            // Etnia: almeno 1 selezionata — match sulla classe .checked (checkbox display:none) + sync input
            var etniaChips = scope.querySelectorAll('#toaTalentEtnieList .toa-talent-category-chip');
            var etnieSelected = 0;
            etniaChips.forEach(function(c){
                var i = c.querySelector('input[name="etnia[]"]');
                var sel = (i && i.checked) || c.classList.contains('checked');
                if (i) i.checked = !!sel; // assicura sincronizzazione
                if (sel) etnieSelected++;
            });
            if (!etnieSelected) {
                var etniaField = scope.querySelector('#toaTalentEtniaField');
                var etniaErr = etniaField ? etniaField.querySelector('.toa-talent-error-msg') : null;
                if (etniaErr) { etniaErr.textContent = tmsg(MSG.pickEthnicity); etniaErr.classList.add('show'); }
                ok = false;
            }

            // Misure petto/vita/fianchi: facoltative (nessuna validazione)
        }

        if (n === 4) {
            if (!uploadState.photoProfile) {
                var photoSec = scope.querySelector('.toa-talent-upload-section');
                var photoErr = photoSec ? photoSec.querySelector('.toa-talent-error-msg') : null;
                if (photoErr) { photoErr.textContent = tmsg(MSG.photoReq); photoErr.classList.add('show'); }
                ok = false;
            }
            var disclaimer = scope.querySelector('[name="disclaimer_consent"]');
            if (disclaimer && !disclaimer.checked) {
                var b = disclaimer.closest('.toa-talent-field');
                if (b) {
                    var er = b.querySelector('.toa-talent-error-msg');
                    if (er) { er.textContent = tmsg(MSG.rulesReq); er.classList.add('show'); }
                }
                ok = false;
            }
            var gdpr = scope.querySelector('[name="gdpr_consent"]');
            if (gdpr && !gdpr.checked) {
                var b2 = gdpr.closest('.toa-talent-field');
                if (b2) {
                    var er2 = b2.querySelector('.toa-talent-error-msg');
                    if (er2) { er2.textContent = tmsg(MSG.gdprReq); er2.classList.add('show'); }
                }
                ok = false;
            }
        }

        return ok;
    }

    // ─── 12. Submit ──────────────────────────────────────────
    var REGISTER_ENDPOINT = '/crm_toagency/actions/registra-talent.php';
    var UPLOAD_ENDPOINT = '/crm_toagency/actions/upload-foto-talent.php';
    // FIX 2026-06-25 marco — controllo leggero email già registrata (fine step 1)
    var CHECK_EMAIL_ENDPOINT = '/crm_toagency/actions/check-email-talent.php';
    function checkEmailExists(email) {
        var fd = new FormData(); fd.append('email', email);
        // FIX 2026-06-26 marco — manda anche nome+cognome+data_nascita (già compilati nello step 1):
        // il check segnala doppione SOLO se coincidono tutti e 4 (gestisce genitore+figli stessa email).
        var nEl = form.querySelector('[name="nome"]');
        var cEl = form.querySelector('[name="cognome"]');
        var dEl = form.querySelector('[name="data_nascita"]');
        if (nEl && nEl.value.trim()) fd.append('nome', nEl.value.trim());
        if (cEl && cEl.value.trim()) fd.append('cognome', cEl.value.trim());
        if (dEl && dEl.value.trim()) fd.append('data_nascita', dEl.value.trim());
        return fetch(CHECK_EMAIL_ENDPOINT, { method: 'POST', body: fd, credentials: 'same-origin' })
            .then(function(r) { return r.json(); })
            .then(function(j) { return !!(j && j.exists); });
    }
    var talentIdAfterRegister = null;
    var talentUuidAfterRegister = null;
    var talentTokenAfterRegister = null;
    // FIX 2026-06-25 marco — se l'upload foto profilo fallisce, qui salviamo id+token
    // così il prossimo "Invia" riprova SOLO l'upload (niente secondo profilo / niente email_exists).
    var pendingUpload = null;

    // FIX 2026-06-25 marco — esito upload: la foto profilo (sempre 1° elemento della coda) DEVE riuscire.
    function afterUploadDone(results) {
        var prof = (results && results.length) ? results[0] : null;
        if (!prof || prof.ok !== true) { afterUploadFailed(); return; }
        pendingUpload = null;
        var portfolioFail = results.slice(1).some(function(r){ return !r || r.ok !== true; });
        if (portfolioFail) {
            var _w = document.getElementById('toaTalentUploadWarn');
            if (_w) { _w.textContent = tmsg(MSG.uploadPartialFail); _w.style.display = 'block'; }
        }
        showSuccess();
    }
    function afterUploadFailed() {
        // Profilo creato ma foto profilo NON caricata → niente "successo": si riprova solo la foto.
        pendingUpload = { talentId: talentIdAfterRegister, token: talentTokenAfterRegister };
        showStep(4);
        showInlineError('toaTalentFormError', '⚠️ ' + tmsg(MSG.photoUploadFail));
    }

    /* TASK hardening-upload STEP A 2026-06-04 marco — compressione foto client */
    function toaCompressImage(file, maxLong, quality){
      maxLong = maxLong || 1280; quality = quality || 0.78; /* TASK hardening-upload STEP A.2 2026-06-04 — 1280px q78 */
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

    // Upload sequenziale: foto profilo → foto portfolio (no video per i talent)
    function uploadOneFile(talentId, token, file, tipo) {
        /* TASK hardening-upload STEP A 2026-06-04 marco — comprimi prima di spedire, poi catena identica */
        return toaCompressImage(file, 1280, 0.78).then(function(cfile) { /* TASK hardening-upload STEP A.2 2026-06-04 — 1280px q78 */
            var fd = new FormData();
            fd.append('talent_id', talentId);
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

    function uploadAllFiles(talentId, token) {
        console.log('[uploadAll] CHIAMATA. uploadState:', uploadState);
        console.log('[uploadAll] photoProfile:', uploadState.photoProfile);
        console.log('[uploadAll] photos count:', uploadState.photos.length, uploadState.photos);

        var queue = [];
        // 1. Foto profilo
        if (uploadState.photoProfile && uploadState.photoProfile.file) {
            queue.push({ file: uploadState.photoProfile.file, tipo: 'foto_profilo' });
        }
        // 2. Foto portfolio (no video per i talent)
        uploadState.photos.forEach(function(p) {
            queue.push({ file: p.file, tipo: 'foto' });
        });

        console.log('[uploadAll] queue length:', queue.length, queue);

        if (queue.length === 0) {
            console.warn('[uploadAll] QUEUE VUOTA - nessun file da uploadare!');
            return Promise.resolve([]);
        }

        // Mostra messaggio "Upload in corso..."
        var submitBtn = document.getElementById('toaTalentSubmit');
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
                return uploadOneFile(talentId, token, item.file, item.tipo).then(function(res) {
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
        // Pulisci eventuale errore form-level (network/server) da un tentativo precedente
        var _fe = document.getElementById('toaTalentFormError');
        if (_fe) { _fe.classList.remove('show'); _fe.textContent = ''; }
        hideRecover(); // FIX 2026-06-25 marco — nascondi bottone recupero da tentativo precedente

        // FIX 2026-06-25 marco — RIPROVA SOLO FOTO: profilo già creato, ricarica solo i file (no re-register).
        if (pendingUpload) {
            if (!validateStep(4)) { showStep(4); return; }
            var rBtn = document.getElementById('toaTalentSubmit');
            if (rBtn) { rBtn.disabled = true; rBtn.textContent = 'Invio in corso...'; }
            uploadAllFiles(pendingUpload.talentId, pendingUpload.token)
                .then(afterUploadDone)
                .catch(function(err) { console.error('[upload retry] error:', err); afterUploadFailed(); })
                .finally(function() { if (rBtn) { rBtn.disabled = false; rBtn.textContent = 'Invia candidatura'; } });
            return;
        }

        for (var i = 1; i <= 4; i++) {
            if (!validateStep(i)) { showStep(i); return; }
        }

        // Disabilita bottone submit per evitare doppi invii
        var submitBtn = document.getElementById('toaTalentSubmit');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.textContent = 'Invio in corso...';
        }

        // FIX v3.3: Disabilita gli input nei container città NASCOSTI
        // Altrimenti FormData prende l'ULTIMO valore (vuoto) sovrascrivendo quello buono
        ['toaTalentCityWrap', 'toaTalentDomCityWrap'].forEach(function(wrapId) {
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
                talentIdAfterRegister = res.talent_id;
                talentUuidAfterRegister = res.uuid;
                talentTokenAfterRegister = res.token_profilo;
                var isMinore = (res.is_minore === true);
                // Avvia upload sequenziale dei file caricati.
                // NB: return → la .finally() attende il completamento upload
                // prima di riabilitare il bottone (evita doppio invio durante upload).
                return uploadAllFiles(res.talent_id, res.token_profilo)
                    .then(afterUploadDone)   // FIX 2026-06-25 marco — successo SOLO se la foto profilo è caricata
                    .catch(function(err) {
                        console.error('[upload] error:', err);
                        afterUploadFailed(); // niente "successo": si riprova solo la foto
                    });
            } else {
                handleRegisterError(res);
            }
        })
        .catch(function(err) {
            console.error('[register] errore network:', err);
            showInlineError('toaTalentFormError', tmsg(MSG.networkError));
        })
        .finally(function() {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Invia candidatura';
            }
            // Riabilita TUTTI gli input dei container città (per permettere di riprovare)
            ['toaTalentCityWrap', 'toaTalentDomCityWrap'].forEach(function(wrapId) {
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
            case 'name_exists': // FIX 2026-06-25 marco — anche nome+cognome già presente
                errorMsg = tmsg(MSG.emailExists);
                stepToGo = 1;
                showRecover(); // mostra bottone recupero profilo
                break;
            case 'missing_social': /* TASK social-ux STEP 7 2026-06-04 marco */
                stepToGo = 3;
                break;
            default:
                stepToGo = 4;
        }

        showInlineError('toaTalentFormError', '⚠️ ' + errorMsg);
        showStep(stepToGo);
        /* TASK social-ux STEP 7 2026-06-04 marco — porta l'utente sul primo campo social */
        if (stepToGo === 3) {
            setTimeout(function() {
                var ig = document.querySelector('[name="instagram"]');
                if (ig) { ig.scrollIntoView({ behavior: 'smooth', block: 'center' }); ig.focus({ preventScroll: true }); }
            }, 350);
        }
    }

    /**
     * Mostra il modale di successo (unico per tutti).
     */
    function showSuccess() {
        // FIX 2026-06-25 marco — popola la CTA "Completa il profilo" (step 2) con uuid+token del profilo creato
        var cta = document.getElementById('toaTalentCompleteCta');
        if (cta) {
            if (talentUuidAfterRegister && talentTokenAfterRegister) {
                var lg = (document.documentElement.getAttribute('lang') || 'it').substring(0, 2);
                cta.href = '/completa-profilo/?uuid=' + encodeURIComponent(talentUuidAfterRegister)
                         + '&t=' + encodeURIComponent(talentTokenAfterRegister)
                         + '&lang=' + encodeURIComponent(lg);
                cta.style.display = 'block';
            } else {
                cta.style.display = 'none';
            }
        }
        if (successModal) {
            successModal.classList.add('show');
            document.body.style.overflow = 'hidden';
        }
    }

    // FIX 2026-06-25 marco — bottone "Recupera il tuo profilo" (email già registrata) → recupera-link.php
    function showRecover() {
        var box = document.getElementById('toaTalentRecover');
        var link = document.getElementById('toaTalentRecoverLink');
        if (link) {
            var lg2 = (document.documentElement.getAttribute('lang') || 'it').substring(0, 2);
            link.href = '/crm_toagency/recupera-link.php?lang=' + encodeURIComponent(lg2);
        }
        if (box) box.style.display = 'block';
    }
    function hideRecover() {
        var box = document.getElementById('toaTalentRecover');
        if (box) box.style.display = 'none';
    }

    function resetFormAfterSuccess(modal) {
        modal.classList.remove('show');
        document.body.style.overflow = '';
        form.reset();
        uploadState = { photoProfile: null, photos: [] };
        tipoTalentSelected = 'immagine';
        if (profileThumb) profileThumb.innerHTML = '';
        if (photosThumbs) photosThumbs.innerHTML = '';
        updateCounters();
        document.querySelectorAll('.toa-talent-category-chip.checked').forEach(function(c){ c.classList.remove('checked'); });
        if (genitoreBox015) genitoreBox015.classList.remove('show');
        if (genitoreBox1617) genitoreBox1617.classList.remove('show');
        if (misureBox) misureBox.style.display = 'none';
        showStep(1);
    }

    if (successCloseBtn) {
        successCloseBtn.addEventListener('click', function() { resetFormAfterSuccess(successModal); });
    }
})();
