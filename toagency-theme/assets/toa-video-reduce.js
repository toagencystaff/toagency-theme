/*
 * toa-video-reduce.js — ticket #442 (02/10/2026, Marco: database con i video piu' leggeri possibile).
 * Riduce il video NEL BROWSER del talent prima di caricarlo: MP4 H.264, lato corto max 720 px, ~1,5 Mbit/s.
 * Il server non ha encoder video, quindi la riduzione si fa qui con WebCodecs via libreria Mediabunny (caricata solo al primo video).
 * Stesse regole gia' collaudate nella scheda staff del CRM (pages/scheda-talent.php: _vidMeta/_vidServeRiduzione/_vidRiduci).
 *
 * Uso: window.toaVideoRiduci(file, setStatus, testoRiduco).then(function (fileDaCaricare) { ... })
 *   - la Promise NON rifiuta mai: se il browser non puo' (niente WebCodecs, formato illeggibile, errore) restituisce l'originale
 *     e sara' il tetto di peso del server a decidere (ripiego voluto);
 *   - il file ridotto viene usato solo se e' davvero piu' leggero dell'originale;
 *   - testoRiduco = frase gia' tradotta con {p} al posto della percentuale (es. 'Riduco il video... {p}%').
 */
(function () {
    'use strict';
    var ALTEZZA = 720, BPS = 1500000, SOGLIA_MB = 30, SENZA_META_MB = 15, INGRESSO_MAX_MB = 500;
    var lib = null;

    function caricaLib() {
        if (!lib) lib = import('https://cdn.jsdelivr.net/npm/mediabunny@1.61.0/dist/bundles/mediabunny.min.mjs');
        return lib;
    }

    // Durata e dimensioni lette dal browser (null se non legge quel formato)
    function meta(f) {
        return new Promise(function (res) {
            var u, v = document.createElement('video'), fin = false;
            function end(d) { if (fin) return; fin = true; try { URL.revokeObjectURL(u); } catch (e) {} res(d); }
            try { u = URL.createObjectURL(f); } catch (e) { return res(null); }
            v.preload = 'metadata'; v.muted = true;
            v.onloadedmetadata = function () { end({ sec: isFinite(v.duration) ? v.duration : null, w: v.videoWidth || null, h: v.videoHeight || null }); };
            v.onerror = function () { end(null); };
            setTimeout(function () { end(null); }, 8000);
            v.src = u;
        });
    }

    // Vale la pena ridurlo? (peso o dimensioni oltre il necessario)
    function serve(f, m) {
        if (f.size > SOGLIA_MB * 1048576) return true;
        if (!m || !m.w || !m.h) return f.size > SENZA_META_MB * 1048576;
        var bps = m.sec ? (f.size * 8 / m.sec) : 0;
        return Math.min(m.w, m.h) > ALTEZZA || bps > BPS * 1.5;
    }

    function riduci(f, m, setSt, testo) {
        if (typeof VideoEncoder === 'undefined' || typeof VideoDecoder === 'undefined') return Promise.reject(new Error('browser_senza_webcodecs'));
        return caricaLib().then(function (M) {
            var input = new M.Input({ source: new M.BlobSource(f), formats: M.ALL_FORMATS });
            var output = new M.Output({ format: new M.Mp4OutputFormat(), target: new M.BufferTarget() }); // BufferTarget = indice (moov) all'inizio
            var vopt = { bitrate: BPS, forceTranscode: true };
            if (m && m.w && m.h && Math.min(m.w, m.h) > ALTEZZA) {
                if (m.w < m.h) vopt.width = ALTEZZA; else vopt.height = ALTEZZA; // l'altra misura la calcola da solo (proporzioni mantenute)
            }
            return M.Conversion.init({ input: input, output: output, video: vopt }).then(function (conv) {
                if (!conv.isValid) throw new Error('conversione_non_valida');
                conv.onProgress = function (p) { if (setSt) setSt(String(testo || 'Riduco il video... {p}%').replace('{p}', Math.round(p * 100))); };
                return conv.execute().then(function () {
                    var buf = output.target.buffer;
                    if (!buf || !buf.byteLength) throw new Error('uscita_vuota');
                    return new File([buf], (f.name || 'video').replace(/\.[^.]+$/, '') + '.mp4', { type: 'video/mp4' });
                });
            });
        });
    }

    window.toaVideoRiduci = function (file, setSt, testo) {
        if (!file || file.size > INGRESSO_MAX_MB * 1048576) return Promise.resolve(file);
        return meta(file).then(function (m) {
            if (!serve(file, m)) return file; // gia' leggero: lo carico com'e'
            return riduci(file, m, setSt, testo).then(function (small) {
                return (small.size < file.size) ? small : file;
            });
        }).catch(function (e) {
            if (window.console) console.warn('riduzione video non riuscita, carico l\'originale:', e);
            return file;
        });
    };
})();
