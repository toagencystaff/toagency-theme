<?php
/**
 * Template Name: Casting
 * Description: Pagina casting con stile TOAgency dark theme
 */

toa_component('header');
?>

<style>
/* RESET E BASE - DARK THEME */
.casting-wrapper {
    max-width: 100%;
    margin: 0;
    padding: 140px 8px 60px;
    font-family: var(--font-body);
}

/* HEADER */
.casting-hero {
    text-align: center;
    padding: 20px 8px;
    border-bottom: 1px solid var(--gray-2);
    margin-bottom: 20px;
}
.casting-hero h1 {
    font-family: var(--font-display);
    font-size: 24px;
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 8px;
    color: var(--white);
}
.casting-hero p {
    font-size: 11px;
    color: var(--gray-4);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* SOCIAL BOX */
.social-box {
    background: var(--gray-1);
    border: 1px solid var(--gray-2);
    padding: 16px;
    margin: 20px 8px;
    text-align: center;
}
.social-box p {
    font-size: 12px;
    margin-bottom: 12px;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    color: var(--gray-5);
}
.social-buttons {
    display: flex;
    gap: 4px;
    justify-content: center;
}
.social-btn {
    flex: 1;
    max-width: 100px;
    padding: 8px 4px;
    background: var(--white);
    color: var(--black);
    text-decoration: none;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    border: 1px solid var(--white);
    transition: all 0.2s;
}
.social-btn:hover {
    background: var(--accent);
    border-color: var(--accent);
    color: var(--black);
}

/* FILTRO REGIONI */
.region-filter-container {
    text-align: center;
    margin: 30px 8px;
}
#regionFilter {
    width: 100%;
    max-width: 400px;
    padding: 12px;
    font-size: 13px;
    border: 1px solid var(--gray-2);
    background: var(--gray-1);
    color: var(--white);
    text-transform: uppercase;
    font-weight: 600;
    cursor: pointer;
    -webkit-appearance: none;
    appearance: none;
    background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='2'%3e%3cpolyline points='6 9 12 15 18 9'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 12px center;
    background-size: 16px;
    padding-right: 36px;
}
#regionFilter option { background: var(--black); color: var(--white); }
#regionFilter:focus { outline: none; border-color: var(--accent); }

/* === CASTING CARDS REDESIGN === */
.casting-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
    padding: 0 20px;
}
.casting-item {
    display: block;
    text-decoration: none;
    color: #fff;
    border-radius: 16px;
    overflow: hidden;
    background: #111;
    border: 1px solid rgba(255,255,255,0.06);
    transition: transform 0.35s cubic-bezier(.2,.8,.3,1), box-shadow 0.35s;
    position: relative;
}
.casting-item:hover {
    transform: translateY(-6px);
    box-shadow: 0 20px 50px rgba(0,0,0,0.5);
    border-color: rgba(255,255,255,0.12);
}

/* THUMB */
.casting-thumb {
    position: relative;
    aspect-ratio: 4/3;
    overflow: hidden;
}
.casting-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s cubic-bezier(.2,.8,.3,1);
}
.casting-item:hover .casting-thumb img {
    transform: scale(1.06);
}
.casting-thumb::after {
    content: "";
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 50%;
    background: linear-gradient(to top, rgba(0,0,0,0.6), transparent);
    pointer-events: none;
}
.casting-thumb-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #1a1a1a;
    color: #333;
    font-size: 14px;
    letter-spacing: 3px;
}

/* BADGE LOCATION */
.casting-badge {
    position: absolute;
    top: 14px;
    left: 14px;
    z-index: 2;
    background: rgba(0,0,0,0.55);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    padding: 5px 14px;
    border-radius: 100px;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 1.2px;
    text-transform: uppercase;
    color: #fff;
    border: 1px solid rgba(255,255,255,0.12);
}

/* INFO */
.casting-info {
    padding: 22px 20px 20px;
}
.casting-title {
    font-family: var(--font-display, Georgia, serif);
    font-size: 18px;
    font-weight: 400;
    line-height: 1.35;
    margin-bottom: 8px;
    color: #fff;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.casting-description {
    font-size: 13px;
    color: rgba(255,255,255,0.5);
    margin-bottom: 4px;
    text-transform: capitalize;
}
.casting-meta-row {
    display: flex;
    gap: 16px;
    margin-bottom: 16px;
}
.casting-meta-item {
    font-size: 12px;
    color: rgba(255,255,255,0.45);
    display: flex;
    align-items: center;
    gap: 5px;
}
.casting-meta-item strong {
    color: rgba(255,255,255,0.7);
    font-weight: 500;
}

/* FOOTER */
.casting-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 14px;
    border-top: 1px solid rgba(255,255,255,0.08);
}
.casting-date {
    font-size: 12px;
    color: rgba(255,255,255,0.4);
    letter-spacing: 0.3px;
}
.casting-link {
    font-size: 12px;
    color: #c8ff00;
    font-weight: 600;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    transition: letter-spacing 0.3s;
}
.casting-item:hover .casting-link {
    letter-spacing: 2.5px;
}

/* RESPONSIVE */
/* === PAGINATION === */
.casting-pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 8px;
    margin: 48px 0 32px;
    padding: 0 20px;
    flex-wrap: wrap;
}
.casting-pagination .page-numbers {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 44px;
    height: 44px;
    padding: 0 14px;
    border-radius: 10px;
    background: rgba(255,255,255,0.06);
    border: 1px solid rgba(255,255,255,0.1);
    color: #aaa;
    font-size: 15px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s ease;
}
.casting-pagination .page-numbers:hover {
    background: rgba(200,255,0,0.12);
    border-color: rgba(200,255,0,0.3);
    color: #c8ff00;
}
.casting-pagination .page-numbers.current {
    background: #c8ff00;
    border-color: #c8ff00;
    color: #000;
    font-weight: 700;
}
.casting-pagination .page-numbers.dots {
    background: transparent;
    border-color: transparent;
    cursor: default;
    min-width: 30px;
    padding: 0 4px;
}
.casting-pagination .next,
.casting-pagination .prev {
    padding: 0 20px;
    font-weight: 600;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    font-size: 13px;
}

@media (max-width: 900px) {
    .casting-grid { grid-template-columns: repeat(2, 1fr); gap: 16px; }
}
@media (max-width: 560px) {
    .casting-grid { grid-template-columns: 1fr; gap: 14px; }
    .casting-title { font-size: 16px; }
    .casting-info { padding: 18px 16px 16px; }
}

</style>

<div class="casting-wrapper">
    
    <!-- HEADER -->
    <div class="casting-hero">
        <h1><?php _e('Casting in Corso', 'toagency-theme'); ?></h1>
        <p><?php _e('Ultimi annunci pubblicati', 'toagency-theme'); ?></p>
    </div>

    <!-- SOCIAL -->
    <div class="social-box">
        <p class="social-box-title"><?php _e('Ricevi i casting in tempo reale', 'toagency-theme'); ?></p>
        <p class="social-box-sub"><?php _e('Iscriviti ai nostri canali per non perdere nessuna opportunità: ogni nuovo casting, direttamente sul tuo telefono.', 'toagency-theme'); ?></p>
        <div class="social-buttons">
            <a href="https://toagency.it/itacommunities/" class="social-btn" target="_blank">
                <span class="social-btn-name">WhatsApp</span>
                <span class="social-btn-desc">Entra nella Community</span>
            </a>
            <a href="https://www.instagram.com/toagency/" class="social-btn" target="_blank">
                <span class="social-btn-name">Instagram</span>
                <span class="social-btn-desc">Segui il Canale Broadcast</span>
            </a>
            <a href="https://www.facebook.com/groups/hostessmodelscastingcalls" class="social-btn" target="_blank">
                <span class="social-btn-name">Facebook</span>
                <span class="social-btn-desc">Unisciti al Gruppo</span>
            </a>
        </div>
    </div>

    <!-- FILTRO REGIONI -->
    <?php 
    $current_region = isset($_GET['regione']) ? sanitize_text_field($_GET['regione']) : '';
    $base_url = get_permalink();
    ?>
    <div class="region-filter-container">
        <select id="regionFilter" onchange="window.location.href=this.value">
            <option value="<?php echo $base_url; ?>" <?php echo !$current_region ? 'selected' : ''; ?>>Tutta Italia</option>
            
            <optgroup label="<?php echo esc_attr(__('Nord Italia', 'toagency-theme')); ?>">
                <option value="<?php echo $base_url; ?>?regione=nord-italia" <?php echo $current_region == 'nord-italia' ? 'selected' : ''; ?>><?php _e('Tutto il Nord', 'toagency-theme'); ?></option>
                <option value="<?php echo $base_url; ?>?regione=lombardia" <?php echo $current_region == 'lombardia' ? 'selected' : ''; ?>><?php _e('Lombardia', 'toagency-theme'); ?></option>
                <option value="<?php echo $base_url; ?>?regione=piemonte-liguria" <?php echo $current_region == 'piemonte-liguria' ? 'selected' : ''; ?>><?php _e('Piemonte e Liguria', 'toagency-theme'); ?></option>
                <option value="<?php echo $base_url; ?>?regione=triveneto" <?php echo $current_region == 'triveneto' ? 'selected' : ''; ?>><?php _e('Triveneto', 'toagency-theme'); ?></option>
                <option value="<?php echo $base_url; ?>?regione=emilia-romagna" <?php echo $current_region == 'emilia-romagna' ? 'selected' : ''; ?>><?php _e('Emilia Romagna', 'toagency-theme'); ?></option>
            </optgroup>

            <optgroup label="<?php echo esc_attr(__('Centro Italia', 'toagency-theme')); ?>">
                <option value="<?php echo $base_url; ?>?regione=centro-italia" <?php echo $current_region == 'centro-italia' ? 'selected' : ''; ?>><?php _e('Tutto il Centro', 'toagency-theme'); ?></option>
                <option value="<?php echo $base_url; ?>?regione=lazio" <?php echo $current_region == 'lazio' ? 'selected' : ''; ?>><?php _e('Lazio', 'toagency-theme'); ?></option>
                <option value="<?php echo $base_url; ?>?regione=toscana" <?php echo $current_region == 'toscana' ? 'selected' : ''; ?>><?php _e('Toscana', 'toagency-theme'); ?></option>
                <option value="<?php echo $base_url; ?>?regione=centro" <?php echo $current_region == 'centro' ? 'selected' : ''; ?>><?php _e('Marche, Umbria, Abruzzo', 'toagency-theme'); ?></option>
            </optgroup>

            <optgroup label="<?php echo esc_attr(__('Sud e Isole', 'toagency-theme')); ?>">
                <option value="<?php echo $base_url; ?>?regione=sud-isole" <?php echo $current_region == 'sud-isole' ? 'selected' : ''; ?>><?php _e('Tutto il Sud', 'toagency-theme'); ?></option>
                <option value="<?php echo $base_url; ?>?regione=campania" <?php echo $current_region == 'campania' ? 'selected' : ''; ?>><?php _e('Campania', 'toagency-theme'); ?></option>
                <option value="<?php echo $base_url; ?>?regione=puglia" <?php echo $current_region == 'puglia' ? 'selected' : ''; ?>><?php _e('Puglia', 'toagency-theme'); ?></option>
                <option value="<?php echo $base_url; ?>?regione=sud" <?php echo $current_region == 'sud' ? 'selected' : ''; ?>><?php _e('Molise, Basilicata, Calabria', 'toagency-theme'); ?></option>
                <option value="<?php echo $base_url; ?>?regione=sicilia" <?php echo $current_region == 'sicilia' ? 'selected' : ''; ?>><?php _e('Sicilia', 'toagency-theme'); ?></option>
                <option value="<?php echo $base_url; ?>?regione=sardegna" <?php echo $current_region == 'sardegna' ? 'selected' : ''; ?>><?php _e('Sardegna', 'toagency-theme'); ?></option>
            </optgroup>
            
            <optgroup label="<?php echo esc_attr(__('Estero', 'toagency-theme')); ?>">
                <option value="<?php echo $base_url; ?>?regione=francia" <?php echo $current_region == 'francia' ? 'selected' : ''; ?>><?php _e('Francia', 'toagency-theme'); ?></option>
                <option value="<?php echo $base_url; ?>?regione=spagna" <?php echo $current_region == 'spagna' ? 'selected' : ''; ?>><?php _e('Spagna', 'toagency-theme'); ?></option>
                <option value="<?php echo $base_url; ?>?regione=uk" <?php echo $current_region == 'uk' ? 'selected' : ''; ?>><?php _e('Regno Unito', 'toagency-theme'); ?></option>
                <option value="<?php echo $base_url; ?>?regione=internazionale" <?php echo $current_region == 'internazionale' ? 'selected' : ''; ?>><?php _e('Altri Paesi', 'toagency-theme'); ?></option>
            </optgroup>
        </select>
    </div>

    <!-- GRIGLIA CASTING -->
    <div class="casting-grid">
        <?php
        // PAGINAZIONE
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        
        // MAPPATURA FILTRI -> CATEGORIE WORDPRESS
        $region_mapping = array(
            'nord-italia' => array('lombardia', 'piemonte', 'liguria', 'valle-daosta', 'veneto', 'trentino-alto-adige', 'fvg', 'emilia-romagna', 'tutta-italia'),
            'centro-italia' => array('lazio', 'toscana', 'marche', 'umbria', 'abruzzo', 'tutta-italia'),
            'sud-isole' => array('campania', 'puglia', 'molise', 'basilicata', 'calabria', 'sicilia', 'sardegna', 'tutta-italia'),
            'lombardia' => array('lombardia', 'tutta-italia'),
            'piemonte-liguria' => array('piemonte', 'valle-daosta', 'liguria', 'tutta-italia'),
            'triveneto' => array('veneto', 'trentino-alto-adige', 'friuli-venezia-giulia', 'tutta-italia'),
            'emilia-romagna' => array('emilia-romagna', 'tutta-italia'),
            'lazio' => array('lazio', 'tutta-italia'),
            'toscana' => array('toscana', 'tutta-italia'),
            'centro' => array('marche', 'umbria', 'abruzzo', 'tutta-italia'),
            'campania' => array('campania', 'tutta-italia'),
            'puglia' => array('puglia', 'tutta-italia'),
            'sud' => array('molise', 'basilicata', 'calabria', 'tutta-italia'),
            'sicilia' => array('sicilia', 'tutta-italia'),
            'sardegna' => array('sardegna', 'tutta-italia'),
            'francia' => array('francia'),
            'spagna' => array('spagna'),
            'uk' => array('regno-unito', 'uk'),
            'internazionale' => array('internazionale', 'estero')
        );
        
        // PREPARA LA QUERY
        $args = array(
            'posts_per_page' => 12,
            'paged' => $paged,
            'orderby' => 'date',
            'order' => 'DESC'
        );
        
        // SE C'È UN FILTRO REGIONE
        if ($current_region && isset($region_mapping[$current_region])) {
            $categorie_da_cercare = $region_mapping[$current_region];
            
            $args['tax_query'] = array(
                'relation' => 'AND',
                array(
                    'taxonomy' => 'category',
                    'field'    => 'slug',
                    'terms'    => 'casting',
                ),
                array(
                    'taxonomy' => 'category',
                    'field'    => 'slug',
                    'terms'    => $categorie_da_cercare,
                    'operator' => 'IN'
                )
            );
        } else {
            $args['category_name'] = 'casting';
        }
        
        $casting_query = new WP_Query($args);
        
        // LOOP CASTING
        if ($casting_query->have_posts()) :
            while ($casting_query->have_posts()) : $casting_query->the_post();
                
                // Estrai dati dal contenuto
                $content = get_the_content();
                $titolo = get_the_title();
                
                // Estrai codice
                $codice = '';
                if (preg_match('/^([A-Z]{2,3}-[A-Z]{2}-\d{3})/i', $titolo, $matches)) {
                    $codice = $matches[1];
                }
                
                // Estrai città
                $citta = 'Italia';
                if (preg_match('/Dove:<\/strong>\s*<span[^>]*>([^<]+)<\/span>/i', $content, $matches)) {
                    $citta = trim(strip_tags($matches[1]));
                }
                
                // Estrai date
                $quando = '';
                if (preg_match('/Quando:<\/strong>\s*<span[^>]*>([^<]+)<\/span>/i', $content, $matches)) {
                    $quando = trim(strip_tags($matches[1]));
                }
                
                // Estrai budget
                $budget = '';
                if (preg_match('/budget:<\/strong>\s*<span[^>]*>€\s*([^<]+)<\/span>/i', $content, $matches)) {
                    $budget = trim($matches[1]);
                }
                
                // Estrai profilo ricercato
                $profilo = '';
                if (preg_match('/Genere:<\/strong>\s*([^<]+)/i', $content, $matches)) {
                    $profilo = trim(strip_tags($matches[1]));
                }

    // Titolo pulito (rimuovi codice es. IT-TO-001)
    $titolo_pulito = preg_replace('/^[A-Z]{2,3}-[A-Z]{2}-\\d{3}\\s*[-\xe2\x80\x93\xe2\x80\x94]?\\s*/i', '', $titolo);
    $titolo_pulito = trim($titolo_pulito) ?: $titolo;
                ?>
                
    <!-- CARD CASTING -->
    <a href="<?php the_permalink(); ?>" class="casting-item">
        <div class="casting-thumb">
            <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('medium_large', array('alt' => esc_attr($titolo_pulito), 'loading' => 'lazy')); ?>
            <?php else : ?>
                <div class="casting-thumb-placeholder">TOAGENCY</div>
            <?php endif; ?>
            <span class="casting-badge"><?php echo esc_html($citta); ?></span>
        </div>
        <div class="casting-info">
            <h3 class="casting-title"><?php echo esc_html($titolo_pulito); ?></h3>
            <?php if ($profilo) : ?>
                <div class="casting-description"><?php echo esc_html($profilo); ?></div>
            <?php endif; ?>
            <div class="casting-meta-row">
                <?php if ($quando) : ?>
                    <span class="casting-meta-item">&#128197; <?php echo esc_html($quando); ?></span>
                <?php endif; ?>
                <?php if ($budget) : ?>
                    <span class="casting-meta-item">&#128176; &euro;<?php echo esc_html($budget); ?></span>
                <?php endif; ?>
            </div>
            <div class="casting-footer">
                <span class="casting-date"><?php echo get_the_date('j M Y'); ?></span>
                <span class="casting-link"><?php _e('Scopri', 'toagency-theme'); ?> &#8594;</span>
            </div>
        </div>
    </a>
                
            <?php endwhile; ?>
            
        <?php else : ?>
            
            <!-- NESSUN CASTING -->
            <div class="no-casting">
                <p>
                    <?php 
                    if ($current_region) {
                        _e('Nessun casting attivo per questa zona.', 'toagency-theme');
                    } else {
                        _e('Nessun casting attivo al momento.', 'toagency-theme');
                    }
                    ?>
                </p>
                <?php if ($current_region) : ?>
                    <p><a href="<?php echo $base_url; ?>"><?php _e('Vedi tutti i casting', 'toagency-theme'); ?></a></p>
                <?php endif; ?>
            </div>
            
        <?php endif; 
        wp_reset_postdata(); 
        ?>
    </div>
    
    <!-- PAGINAZIONE -->
    <?php if ($casting_query->max_num_pages > 1) : ?>
        <div class="casting-pagination">
            <?php
            $pagination_args = array(
                'total' => $casting_query->max_num_pages,
                'current' => $paged,
                'prev_text' => __('Precedente', 'toagency-theme'),
                'next_text' => __('Successivo', 'toagency-theme'),
                'type' => 'plain',
                'end_size' => 2,
                'mid_size' => 2
            );
            
            if ($current_region) {
                $pagination_args['format'] = '?paged=%#%&regione=' . $current_region;
            }
            
            echo paginate_links($pagination_args);
            ?>
        </div>
    <?php endif; ?>
    
</div>

<?php toa_component('footer'); ?>