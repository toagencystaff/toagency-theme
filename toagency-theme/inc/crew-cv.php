<?php
/**
 * Blocco "bio guidata / Allega il CV" per i crew bartender, cameriere, autista (ticket #710, parte tema del #708).
 * Usato da templates/page-registrati-crew.php e templates/page-crew-self-edit.php.
 * Logica: assets/crew-cv.js. Endpoint CRM: /crm_toagency/actions/crew-cv-upload.php (non cambiare il contratto).
 * Il CV lo vede solo lo staff: nessun link di download, mai.
 */
if (!function_exists('toa_crew_cv_strings')) {
    function toa_crew_cv_strings() {
        return array(
            'guide' => array(
                'it' => 'Racconta in poche righe dove hai lavorato (che tipo di locale o evento), cosa facevi, che competenze hai (lingue, corsi, patente). Non preoccuparti di nomi e luoghi: sul sito non compariranno.',
                'en' => 'Tell us in a few lines where you have worked (the kind of venue or event), what you did and your skills (languages, courses, driving licence). No need to worry about names and places: they will not appear on the website.',
                'fr' => 'Dites-nous en quelques lignes où vous avez travaillé (type d\'établissement ou d\'événement), ce que vous faisiez et vos compétences (langues, formations, permis). Inutile de citer les noms et les lieux : ils n\'apparaîtront pas sur le site.',
                'es' => 'Cuéntanos en pocas líneas dónde has trabajado (tipo de local o evento), qué hacías y tus competencias (idiomas, cursos, carné de conducir). No te preocupes por los nombres y lugares: no aparecerán en la web.',
            ),
            'either' => array(
                'it' => 'Ne basta una: scrivi la presentazione qui sopra oppure allega il tuo CV.',
                'en' => 'One of the two is enough: write your intro above or attach your CV.',
                'fr' => 'L\'un ou l\'autre suffit : écris ta présentation ci-dessus ou joins ton CV.',
                'es' => 'Con una de las dos basta: escribe tu presentación arriba o adjunta tu CV.',
            ),
            'btn' => array(
                'it' => 'Allega il CV (PDF, DOCX o TXT, max 5 MB)',
                'en' => 'Attach your CV (PDF, DOCX or TXT, max 5 MB)',
                'fr' => 'Joins ton CV (PDF, DOCX ou TXT, 5 Mo max)',
                'es' => 'Adjunta tu CV (PDF, DOCX o TXT, máx. 5 MB)',
            ),
            'privacy' => array(
                'it' => 'Il CV lo vede solo lo staff TOAgency: non compare sul sito né ai clienti. Sul sito va solo un breve testo di presentazione, senza nomi di locali, città o contatti.',
                'en' => 'Only the TOAgency staff can see your CV: it is never shown on the website or to clients. Only a short intro goes online, with no venue names, cities or contact details.',
                'fr' => 'Seul le staff TOAgency voit ton CV : il n\'apparaît ni sur le site ni chez les clients. Seule une courte présentation est publiée, sans noms d\'établissements, villes ni coordonnées.',
                'es' => 'Solo el equipo de TOAgency ve tu CV: no aparece en la web ni ante los clientes. En la web solo se publica una breve presentación, sin nombres de locales, ciudades ni datos de contacto.',
            ),
            'consent' => array(
                'it' => 'Dichiaro che il CV è mio e che le informazioni sono veritiere.',
                'en' => 'I declare that this CV is mine and the information in it is true.',
                'fr' => 'Je déclare que ce CV est le mien et que les informations sont exactes.',
                'es' => 'Declaro que este CV es mío y que la información es veraz.',
            ),
            'need_one' => array(
                'it' => 'Scrivi una breve presentazione oppure allega il tuo CV.',
                'en' => 'Write a short intro or attach your CV.',
                'fr' => 'Écris une courte présentation ou joins ton CV.',
                'es' => 'Escribe una breve presentación o adjunta tu CV.',
            ),
            'need_consent' => array(
                'it' => 'Spunta la dichiarazione sotto il CV.',
                'en' => 'Tick the declaration below your CV.',
                'fr' => 'Coche la déclaration sous ton CV.',
                'es' => 'Marca la declaración debajo de tu CV.',
            ),
            'chosen' => array('it' => 'File scelto:', 'en' => 'Selected file:', 'fr' => 'Fichier choisi :', 'es' => 'Archivo elegido:'),
            'current' => array('it' => 'CV caricato:', 'en' => 'CV uploaded:', 'fr' => 'CV envoyé :', 'es' => 'CV subido:'),
            'replace' => array('it' => 'Sostituisci', 'en' => 'Replace', 'fr' => 'Remplacer', 'es' => 'Sustituir'),
            'remove' => array('it' => 'Togli', 'en' => 'Remove', 'fr' => 'Retirer', 'es' => 'Quitar'),
            'remove_sure' => array('it' => 'Tocca ancora per togliere', 'en' => 'Tap again to remove', 'fr' => 'Touche encore pour retirer', 'es' => 'Toca otra vez para quitar'),
            'uploading' => array('it' => 'Caricamento del CV…', 'en' => 'Uploading your CV…', 'fr' => 'Envoi du CV…', 'es' => 'Subiendo el CV…'),
            'ok' => array('it' => 'CV caricato.', 'en' => 'CV uploaded.', 'fr' => 'CV envoyé.', 'es' => 'CV subido.'),
            'removed' => array('it' => 'CV tolto.', 'en' => 'CV removed.', 'fr' => 'CV retiré.', 'es' => 'CV eliminado.'),
            'scan_note' => array(
                'it' => 'Il file è stato salvato, ma non riusciamo a leggerne il testo (sembra una scansione o una foto). Scrivi anche una breve presentazione qui sopra.',
                'en' => 'Your file is saved, but we cannot read its text (it looks like a scan or a photo). Please also write a short intro above.',
                'fr' => 'Ton fichier est enregistré, mais nous ne pouvons pas lire son texte (on dirait un scan ou une photo). Écris aussi une courte présentation ci-dessus.',
                'es' => 'Tu archivo se ha guardado, pero no podemos leer su texto (parece un escaneo o una foto). Escribe también una breve presentación arriba.',
            ),
            'after_fail' => array(
                'it' => 'Il profilo è stato creato, ma il CV non è stato caricato. Potrai allegarlo dalla pagina di modifica del profilo.',
                'en' => 'Your profile was created, but your CV was not uploaded. You can attach it from the edit-profile page.',
                'fr' => 'Ton profil est créé, mais ton CV n\'a pas été envoyé. Tu pourras le joindre depuis la page de modification du profil.',
                'es' => 'Tu perfil se ha creado, pero el CV no se ha subido. Podrás adjuntarlo desde la página de edición del perfil.',
            ),
            'err_formato_non_ammesso' => array('it' => 'Formato non ammesso: carica un PDF, DOCX o TXT.', 'en' => 'Format not accepted: please upload a PDF, DOCX or TXT file.', 'fr' => 'Format non accepté : envoie un PDF, DOCX ou TXT.', 'es' => 'Formato no admitido: sube un PDF, DOCX o TXT.'),
            'err_troppo_grande' => array('it' => 'Il file supera i 5 MB.', 'en' => 'The file is larger than 5 MB.', 'fr' => 'Le fichier dépasse 5 Mo.', 'es' => 'El archivo supera los 5 MB.'),
            'err_file_non_valido' => array('it' => 'Il file non è valido o è danneggiato: prova con un altro.', 'en' => 'The file is not valid or is damaged: please try another one.', 'fr' => 'Le fichier n\'est pas valide ou est endommagé : essaie-en un autre.', 'es' => 'El archivo no es válido o está dañado: prueba con otro.'),
            'err_file_vuoto' => array('it' => 'Il file è vuoto.', 'en' => 'The file is empty.', 'fr' => 'Le fichier est vide.', 'es' => 'El archivo está vacío.'),
            'err_no_file' => array('it' => 'Scegli un file da allegare.', 'en' => 'Choose a file to attach.', 'fr' => 'Choisis un fichier à joindre.', 'es' => 'Elige un archivo para adjuntar.'),
            'err_legal_required' => array('it' => 'Spunta la dichiarazione sotto il CV.', 'en' => 'Tick the declaration below your CV.', 'fr' => 'Coche la déclaration sous ton CV.', 'es' => 'Marca la declaración debajo de tu CV.'),
            'err_invalid_token' => array('it' => 'Il link del profilo non è valido o è scaduto.', 'en' => 'Your profile link is not valid or has expired.', 'fr' => 'Le lien de ton profil n\'est pas valide ou a expiré.', 'es' => 'El enlace de tu perfil no es válido o ha caducado.'),
            'err_deleted' => array('it' => 'Questo profilo non è più attivo.', 'en' => 'This profile is no longer active.', 'fr' => 'Ce profil n\'est plus actif.', 'es' => 'Este perfil ya no está activo.'),
            'err_storage_non_disponibile' => array('it' => 'Non riusciamo a salvare il file in questo momento: riprova tra poco.', 'en' => 'We cannot save the file right now: please try again shortly.', 'fr' => 'Impossible d\'enregistrer le fichier pour le moment : réessaie dans un instant.', 'es' => 'No podemos guardar el archivo ahora mismo: inténtalo de nuevo en unos minutos.'),
            'err_network' => array('it' => 'Connessione assente: riprova.', 'en' => 'No connection: please try again.', 'fr' => 'Pas de connexion : réessaie.', 'es' => 'Sin conexión: inténtalo de nuevo.'),
            'err_generic' => array('it' => 'Non siamo riusciti a caricare il CV: riprova.', 'en' => 'We could not upload your CV: please try again.', 'fr' => 'Impossible d\'envoyer ton CV : réessaie.', 'es' => 'No hemos podido subir tu CV: inténtalo de nuevo.'),
        );
    }
}

/**
 * Stampa il blocco. $mode: 'reg' (registrazione) | 'se' (modifica profilo). $lang: it|en|fr|es.
 * Il blocco parte nascosto: lo mostra assets/crew-cv.js solo se tra i ruoli c'è bartender, cameriere o autista.
 */
if (!function_exists('toa_crew_cv_render')) {
    function toa_crew_cv_render($mode, $lang) {
        if (!in_array($lang, array('it','en','fr','es'), true)) $lang = 'it';
        $all = toa_crew_cv_strings();
        $s = array();
        foreach ($all as $k => $v) { $s[$k] = isset($v[$lang]) ? $v[$lang] : $v['it']; }
        $errs = array();
        foreach ($s as $k => $v) { if (strpos($k, 'err_') === 0) $errs[substr($k, 4)] = $v; }
        $cfg = array('mode' => $mode, 'lang' => $lang, 's' => $s, 'err' => $errs);
        ?>
<style>
.toa-cv{margin-top:14px;padding:14px 16px;border:1px solid rgba(128,128,128,.35);border-radius:12px}
.toa-cv[hidden]{display:none}
.toa-cv p{margin:0 0 10px;font-size:.88rem;line-height:1.5}
.toa-cv-guide{opacity:.85}
.toa-cv-btn{display:inline-block;padding:12px 16px;min-height:44px;border:1px dashed rgba(128,128,128,.6);border-radius:10px;background:transparent;color:inherit;font:inherit;font-size:.92rem;cursor:pointer;text-align:left;max-width:100%}
.toa-cv-btn[hidden],.toa-cv-current[hidden]{display:none}
.toa-cv-current{display:flex;flex-wrap:wrap;gap:8px 12px;align-items:center;font-size:.9rem;margin-bottom:6px}
.toa-cv-current button{padding:8px 12px;min-height:40px;border:1px solid rgba(128,128,128,.5);border-radius:8px;background:transparent;color:inherit;font:inherit;font-size:.85rem;cursor:pointer}
.toa-cv-file{font-size:.85rem;margin-top:8px;word-break:break-all}
.toa-cv-consent{display:flex;gap:10px;align-items:flex-start;margin-top:12px;font-size:.85rem;line-height:1.45;cursor:pointer}
.toa-cv-consent input{margin-top:3px;flex:0 0 auto}
.toa-cv-privacy{margin-top:10px!important;font-size:.8rem!important;opacity:.7}
.toa-cv-status{margin-top:8px;font-size:.88rem;line-height:1.45}
.toa-cv-status.err{color:#dc2626}
.toa-cv-status.ok{color:#16a34a}
@media (max-width:480px){.toa-cv{padding:12px}.toa-cv-btn{width:100%}}
</style>
<div class="toa-cv" id="toaCv" data-mode="<?php echo esc_attr($mode); ?>" hidden>
    <p class="toa-cv-guide"><?php echo esc_html($s['guide']); ?></p>
    <p class="toa-cv-either"><?php echo esc_html($s['either']); ?></p>
    <div class="toa-cv-current" id="toaCvCurrent" hidden>
        <span><strong><?php echo esc_html($s['current']); ?></strong> <span id="toaCvCurrentTxt"></span></span>
        <button type="button" id="toaCvReplace"><?php echo esc_html($s['replace']); ?></button>
        <button type="button" id="toaCvRemove"><?php echo esc_html($s['remove']); ?></button>
    </div>
    <button type="button" class="toa-cv-btn" id="toaCvPick">📎 <?php echo esc_html($s['btn']); ?></button>
    <input type="file" id="toaCvInput" accept=".pdf,.docx,.txt,application/pdf,text/plain,application/vnd.openxmlformats-officedocument.wordprocessingml.document" style="display:none">
    <div class="toa-cv-file" id="toaCvFile"></div>
    <label class="toa-cv-consent"><input type="checkbox" id="toaCvConsent"><span><?php echo esc_html($s['consent']); ?></span></label>
    <p class="toa-cv-privacy">🔒 <?php echo esc_html($s['privacy']); ?></p>
    <div class="toa-cv-status" id="toaCvStatus" role="status" aria-live="polite"></div>
</div>
<script>window.TOA_CREW_CV = <?php echo wp_json_encode($cfg); ?>;</script>
        <?php
    }
}
