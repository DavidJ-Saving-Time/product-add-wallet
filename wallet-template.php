<?php
/* Template Name: Wallet Configurator */
if (!defined('ABSPATH')) { exit; }

// Allow product id to be managed from a Customizer setting or page meta (_wallet_config_product_id).
$wallet_product_id = absint(get_theme_mod('wallet_configurator_product_id', 1516));
if (!$wallet_product_id && isset($post)) {
    $maybe_id = get_post_meta(get_the_ID(), '_wallet_config_product_id', true);
    $wallet_product_id = absint($maybe_id);
}

get_header();


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add-to-cart'])) {
  if (function_exists('WC') && WC()->cart) {
    echo '<div class="container py-3"><div class="alert alert-info">';
    echo 'POST received. Cart count now: ' . (int) WC()->cart->get_cart_contents_count();
    echo '</div></div>';
  } else {
    echo '<div class="container py-3"><div class="alert alert-warning">';
    echo 'POST received but WC()->cart not available yet.';
    echo '</div></div>';
  }
}



?>
<style>

    body { background-color: #f8f9fa; }
    .gallery-trigger { border: 0; padding: 0; background: transparent; width: 100%; height: 100%; cursor: pointer; display: block; }
    .swatch-option {
      background-repeat: no-repeat;
      background-position: 8px center;
      background-size: 100px 100px;
      padding-left: 120px;
      min-height: 116px;
      line-height: 116px;
    }
    /* Custom swatch dropdown built with jQuery */
    .swatch-select { position: relative; }
    .swatch-toggle { background-color: #fff; border: 1px solid #ced4da; min-height: 64px; }
    .swatch-label { gap: 12px; }
    .swatch-thumb { width: 72px; height: 48px; border-radius: 6px; background-size: cover; background-position: center; border: 1px solid #dee2e6; }
    .swatch-menu { position: absolute; z-index: 10; width: 100%; background: #fff; border: 1px solid #ced4da; border-radius: 8px; margin-top: 6px; box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1); max-height: 320px; overflow-y: auto; display: none; }
    .swatch-item { padding: 10px 12px; display: flex; align-items: center; gap: 12px; cursor: pointer; }
    .swatch-item:hover { background-color: #f1f3f5; }
    .swatch-name { flex: 1; }
    .swatch-value { font-size: 0.85rem; color: #6c757d; }
    .swatch-thumb.color-only { background-image: none !important; border: 1px solid #ced4da; }
    .swatch-preview-backdrop {
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.5);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 1050;
    }
    .swatch-preview-card {
      max-width: 520px;
      width: 92%;
    }
    .leather-picker { cursor: pointer; }
    .leather-picker .picker-caret {
      margin-left: auto;
      color: #6c757d;
      font-size: 1.25rem;
      line-height: 1;
    }
    .leather-picker:hover,
    .leather-picker:focus-within {
      border-color: #86b7fe;
      box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
    }
    .ostrich-toggle {
      background: #fff3cd;
      border: 1px solid #f3d9a4;
      border-radius: 8px;
      padding: 8px 12px;
      gap: 8px;
    }
    .ostrich-toggle-label {
      font-weight: 600;
      color: #7c5a00;
    }
    .leather-modal-options {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 16px;
    }
    .leather-modal-option {
      border: 1px solid #ced4da;
      border-radius: 10px;
      padding: 12px;
      background: #fff;
      display: flex;
      flex-direction: column;
      gap: 10px;
      align-items: center;
      text-align: center;
    }
    .leather-modal-option:hover,
    .leather-modal-option:focus-visible {
      border-color: #86b7fe;
      box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.15);
      outline: none;
    }
    .leather-swatch-thumb {
      width: 300px;
      height: 300px;
      border-radius: 12px;
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      border: 1px solid #dee2e6;
    }
    .leather-modal-label {
      font-weight: 600;
    }
    .leather-modal-value {
      color: #6c757d;
      font-size: 0.95rem;
    }
    #svg-wrapper {
      max-width: 720px;
      border: 1px solid #dee2e6;
      padding: 16px;
      border-radius: 8px;
      background-color: #fff;
      position: relative;
    }
    svg { width: 100%; height: auto; }
    #svg-overlay-buttons {
      position: absolute;
      inset: 0;
      pointer-events: none;
    }
    .svg-overlay-btn {
      position: absolute;
      transform: translate(-50%, -50%);
      pointer-events: auto;
    }

    .object-fit-cover {
      object-fit: cover;
    }

    .step-guide-card {
      display: block;
      height: 100%;
      padding: 16px;
      border-radius: 12px;
      border: 1px solid #dee2e6;
      background: linear-gradient(145deg, #ffffff, #f8f9fa);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
      transition: transform 0.15s ease, box-shadow 0.15s ease, border-color 0.15s ease;
      text-decoration: none;
      color: inherit;
    }

    .step-guide-card:hover,
    .step-guide-card:focus-visible {
      transform: translateY(-2px);
      border-color: #0d6efd;
      box-shadow: 0 10px 24px rgba(13, 110, 253, 0.12);
      outline: none;
      text-decoration: none;
    }

    .step-guide-number {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background-color: #0d6efd;
      color: #fff;
      font-weight: 700;
      margin-right: 12px;
    }

    .step-guide-title {
      margin: 0;
      font-weight: 700;
      font-size: 1rem;
    }

    .step-section {
      border: 1px solid #dee2e6;
      border-radius: 12px;
      background: #fff;
      box-shadow: 0 6px 18px rgba(0, 0, 0, 0.05);
    }

    .step-heading {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 12px;
    }

    .step-heading .badge {
      font-size: 0.85rem;
    }

</style>


<form
  id="wallet-configurator-form"
  class="wallet-configurator-form"
action="<?php echo esc_url( add_query_arg('add-to-cart', $wallet_product_id, wc_get_cart_url()) ); ?>"

method="post"
>


<?php woocommerce_output_all_notices(); ?>

  <?php wp_nonce_field('wallet_configurator_add_to_cart', 'wallet_configurator_nonce'); ?>
  <input type="hidden" name="add-to-cart" value="<?php echo esc_attr($wallet_product_id); ?>">
  <input type="hidden" name="wallet_options_json" id="wallet-options-field" value="">
<input type="hidden" name="quantity" value="1">
    <div class="container py-4">
      <div class="text-center mb-4">
        <h1 class="h3 mb-2">Wallet Colour Test (Static Palette)</h1>
        <p class="text-muted mb-0">Pick a colour for each part of the wallet and preview it instantly.</p>
      </div>

      <div class="row g-4">
      <div class="col-12 col-lg-6">
        <div class="step-section card mb-4" id="step-tannery">
          <div class="card-body">
            <div class="step-heading">
              <span class="badge bg-primary">Step 1</span>
              <h2 class="h5 mb-0">Choose a leather tannery</h2>
            </div>
            <p class="text-muted">Start by picking the leather house whose colours and finish you want to explore.</p>
            <label class="form-label" for="leather-collection">Leather collection</label>
            <select class="form-select" id="leather-collection">
              <option value="buttero" selected>Buttero</option>
              <option value="badalassi">Badalassi Carlo Wax</option>
            </select>
            <div class="form-text">Swap between Buttero and Badalassi Carlo Wax leather swatches.</div>
            <div class="form-text leather-description" data-collection="buttero">
              Buttero is one of the leading products of the Tuscan Walpier Tannery with unique characteristics. As it is firmer than a vachetta leather, watch straps made from Buttero have a nice rigidity to them. They will also patina very well over time. The leather is full grain and aniline finished meaning dyed with no colour correction which ensures a natural look and feel. Due to the material’s natural qualities, any irregularities and imperfections on the surface are considered an added value rather than a defect.
            </div>
            <div class="form-text leather-description d-none" data-collection="badalassi">
              The Badalassi Wax leather stands out through its dry-milled waxed finish, also known as pull-up or lightening effect, which gives it a distressed look when it is pulled or folded. It may show signs of scratches and scuffs, but it is durable and ages gracefully to serve as a canvas for memories.
            </div>
          </div>
        </div>

        <div class="step-section card mb-4" id="step-leather">
          <div class="card-body">
            <div class="step-heading">
              <span class="badge bg-primary">Step 2</span>
              <h2 class="h5 mb-0">Choose leather or exotic skin</h2>
            </div>
            <p class="text-muted">Pick the main leathers for the outside and pockets, then toggle ostrich if you want an exotic texture.</p>
            <div class="row g-3">
              <div class="col-sm-12">
                <label class="form-label" for="color-outer">Outer leather</label>
                <select class="form-select" id="color-outer">
                  <option value="#8b5a2b" data-color="#8b5a2b" class="swatch-option">Dark Brown</option>
                  <option value="#c28e5a" data-color="#c28e5a" class="swatch-option">Tan</option>
                  <option value="#3b2a1a" data-color="#3b2a1a" class="swatch-option">Chocolate</option>
                  <option value="#000000" data-color="#000000" class="swatch-option">Black</option>
                  <option value="#4a2f2f" data-color="#4a2f2f" class="swatch-option">Oxblood</option>
                  <option value="#6b4f3a" data-color="#6b4f3a" class="swatch-option">Walnut</option>
                  <option value="#d2a679" data-color="#d2a679" class="swatch-option">Sand</option>
                  <option value="#3a4f6b" data-color="#3a4f6b" class="swatch-option">Navy</option>
                  <option value="#374331" data-color="#374331" class="swatch-option">Olive</option>
                  <option value="#7b3f61" data-color="#7b3f61" class="swatch-option">Burgundy</option>
                </select>
              </div>

              <div class="col-sm-12">
                <label class="form-label" for="color-interior">Top Pcoket</label>
                <select class="form-select" id="color-interior">
                  <option value="#c28e5a" data-color="#c28e5a" class="swatch-option">Tan</option>
                  <option value="#8b5a2b" data-color="#8b5a2b" class="swatch-option">Dark Brown</option>
                  <option value="#3b2a1a" data-color="#3b2a1a" class="swatch-option">Chocolate</option>
                  <option value="#d2a679" data-color="#d2a679" class="swatch-option">Sand</option>
                  <option value="#6b4f3a" data-color="#6b4f3a" class="swatch-option">Walnut</option>
                  <option value="#374331" data-color="#374331" class="swatch-option">Olive</option>
                  <option value="#3a4f6b" data-color="#3a4f6b" class="swatch-option">Navy</option>
                  <option value="#000000" data-color="#000000" class="swatch-option">Black</option>
                  <option value="#7b3f61" data-color="#7b3f61" class="swatch-option">Burgundy</option>
                  <option value="#4a2f2f" data-color="#4a2f2f" class="swatch-option">Oxblood</option>
                </select>
              </div>

              <div class="col-sm-12">
                <label class="form-label mb-0" for="color-pockets">Bottom Pocket</label>
                <select class="form-select" id="color-pockets">
                  <option value="#3b2a1a" data-color="#3b2a1a" class="swatch-option">Chocolate</option>
                  <option value="#8b5a2b" data-color="#8b5a2b" class="swatch-option">Dark Brown</option>
                  <option value="#c28e5a" data-color="#c28e5a" class="swatch-option">Tan</option>
                  <option value="#000000" data-color="#000000" class="swatch-option">Black</option>
                  <option value="#3a4f6b" data-color="#3a4f6b" class="swatch-option">Navy</option>
                  <option value="#374331" data-color="#374331" class="swatch-option">Olive</option>
                  <option value="#6b4f3a" data-color="#6b4f3a" class="swatch-option">Walnut</option>
                  <option value="#7b3f61" data-color="#7b3f61" class="swatch-option">Burgundy</option>
                  <option value="#d2a679" data-color="#d2a679" class="swatch-option">Sand</option>
                  <option value="#4a2f2f" data-color="#4a2f2f" class="swatch-option">Oxblood</option>
                </select>
                <div class="form-check form-switch d-flex align-items-center ostrich-toggle mt-2 mb-0">
                  <input class="form-check-input" type="checkbox" role="switch" id="bottom-ostrich-toggle">
                  <label class="form-check-label ostrich-toggle-label" for="bottom-ostrich-toggle">Use Ostrich (adds texture)</label>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="step-section card mb-4" id="step-stitching">
          <div class="card-body">
            <div class="step-heading">
              <span class="badge bg-primary">Step 3</span>
              <h2 class="h5 mb-0">Choose stitching colours</h2>
            </div>
            <p class="text-muted">Dial in the thread colours that bring the outside and inside together.</p>
            <div class="row g-3">
              <div class="col-sm-6">
                <label class="form-label" for="color-stitching">Stitching</label>
                <select class="form-select" id="color-stitching">
                  <option value="#ffffff" data-color="#ffffff" class="swatch-option">White</option>
                  <option value="#f5e6c8" data-color="#f5e6c8" class="swatch-option">Cream</option>
                  <option value="#d2a679" data-color="#d2a679" class="swatch-option">Beige</option>
                  <option value="#000000" data-color="#000000" class="swatch-option">Black</option>
                  <option value="#8b5a2b" data-color="#8b5a2b" class="swatch-option">Brown</option>
                  <option value="#c28e5a" data-color="#c28e5a" class="swatch-option">Tan</option>
                  <option value="#ffdd33" data-color="#ffdd33" class="swatch-option">Yellow</option>
                  <option value="#3a4f6b" data-color="#3a4f6b" class="swatch-option">Navy</option>
                  <option value="#7b3f61" data-color="#7b3f61" class="swatch-option">Burgundy</option>
                  <option value="#374331" data-color="#374331" class="swatch-option">Olive</option>
                </select>
              </div>

              <div class="col-sm-6">
                <label class="form-label" for="color-stitching2">Stitching 2</label>
                <select class="form-select" id="color-stitching2">
                  <option value="#ffffff" data-color="#ffffff" class="swatch-option">White</option>
                  <option value="#f5e6c8" data-color="#f5e6c8" class="swatch-option">Cream</option>
                  <option value="#d2a679" data-color="#d2a679" class="swatch-option">Beige</option>
                  <option value="#000000" data-color="#000000" class="swatch-option">Black</option>
                  <option value="#8b5a2b" data-color="#8b5a2b" class="swatch-option">Brown</option>
                  <option value="#c28e5a" data-color="#c28e5a" class="swatch-option">Tan</option>
                  <option value="#ffdd33" data-color="#ffdd33" class="swatch-option">Yellow</option>
                  <option value="#3a4f6b" data-color="#3a4f6b" class="swatch-option">Navy</option>
                  <option value="#7b3f61" data-color="#7b3f61" class="swatch-option">Burgundy</option>
                  <option value="#374331" data-color="#374331" class="swatch-option">Olive</option>
                </select>
              </div>
            </div>
          </div>
        </div>

        <div class="step-section card" id="step-extras">
          <div class="card-body">
            <div class="step-heading">
              <span class="badge bg-primary">Step 4</span>
              <h2 class="h5 mb-0">Optional extras &amp; lining</h2>
            </div>
            <p class="text-muted">Finish with lining, branding, edge work, and metal hardware.</p>
            <div class="row g-3">
              <div class="col-sm-12">
                <label class="form-label" for="lining-leather">Lining leather</label>
                <select class="form-select" id="lining-leather"></select>
                <div class="form-text">Nappa lambskin lining selection (not shown on the SVG preview).</div>
                <div class="form-check mt-2">
                  <input class="form-check-input" type="radio" name="lining-coverage" value="body" id="lining-main-body" checked>
                  <label class="form-check-label" for="lining-main-body">Add lining to the main body</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="lining-coverage" value="all" id="lining-all-pockets">
                  <label class="form-check-label" for="lining-all-pockets">Use a liner for all the pockets and the main body</label>
                </div>
              </div>

              <div class="col-sm-12">
                <label class="form-label d-block">Debossing</label>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="deboss-choice" id="deboss-no" value="no" checked>
                  <label class="form-check-label" for="deboss-no">No debossing</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="deboss-choice" id="deboss-atelier" value="atelier">
                  <label class="form-check-label" for="deboss-atelier">Deboss Atelier D. James into the wallet</label>
                </div>
              </div>

              <div class="col-sm-12">
                <label class="form-label" for="edge-style">Edge style</label>
                <select class="form-select" id="edge-style">
                  <option value="burnished" selected>Burnished</option>
                  <option value="painted">Painted</option>
                </select>
                <div class="form-text">Choose a burnished or painted edge finish.</div>
              </div>

              <div class="col-sm-12 d-none" id="painted-edge-options">
                <label class="form-label d-block">Painted edge colour</label>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="edge-paint-choice" id="edge-paint-lining" value="lining" checked>
                  <label class="form-check-label" for="edge-paint-lining">Same colour as lining leather</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="edge-paint-choice" id="edge-paint-custom" value="custom">
                  <label class="form-check-label" for="edge-paint-custom">Pick a colour</label>
                </div>
                <div class="mt-3 ms-3 d-none" id="edge-colour-picker-wrapper">
                  <label class="form-label" for="edge-colour">Edge paint colour</label>
                  <select class="form-select" id="edge-colour">
                    <option value="#000000" data-color="#000000">Black</option>
                    <option value="#ffffff" data-color="#ffffff">White</option>
                    <option value="#f5e6c8" data-color="#f5e6c8">Cream</option>
                    <option value="#c28e5a" data-color="#c28e5a">Tan</option>
                    <option value="#8b5a2b" data-color="#8b5a2b">Dark Brown</option>
                    <option value="#3b2a1a" data-color="#3b2a1a">Chocolate</option>
                    <option value="#6b4f3a" data-color="#6b4f3a">Walnut</option>
                    <option value="#d2a679" data-color="#d2a679">Sand</option>
                    <option value="#3a4f6b" data-color="#3a4f6b">Navy</option>
                    <option value="#7b3f61" data-color="#7b3f61">Burgundy</option>
                    <option value="#374331" data-color="#374331">Olive</option>
                    <option value="#2f5a32" data-color="#2f5a32">Forest Green</option>
                    <option value="#1f4aa5" data-color="#1f4aa5">Royal Blue</option>
                    <option value="#b22222" data-color="#b22222">Red</option>
                    <option value="#f4801f" data-color="#f4801f">Orange</option>
                    <option value="#ffdd33" data-color="#ffdd33">Yellow</option>
                    <option value="#ffc0cb" data-color="#ffc0cb">Pink</option>
                    <option value="#6a0dad" data-color="#6a0dad">Purple</option>
                    <option value="#7b7b7b" data-color="#7b7b7b">Grey</option>
                    <option value="#2f8886" data-color="#2f8886">Teal</option>
                  </select>
                </div>
              </div>

              <div class="col-sm-12">
                <label class="form-label" for="metal-corners">Metal corners</label>
                <select class="form-select" id="metal-corners">
                  <option value="none" selected>No metal corners</option>
                  <option value="brass">Brass</option>
                  <option value="silver">Silver</option>
                  <option value="gunmetal-grey">Gunmetal Grey</option>
                  <option value="black">Black</option>
                  <option value="gold">Gold</option>
                  <option value="rose-gold">Rose Gold</option>
                </select>
                <div class="form-text">Add optional metal corner finishes.</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-12 col-lg-6">
        <p class="mb-3">I’ve used a simple colour illustration here to help you visualise the piece. Please refer to the photos below for real examples. If you order the ostrich option, I’ll send you an image of the leather with the cutting template so you can approve it before I begin.</p>
        <div id="svg-wrapper" class="shadow-sm">
    <!-- DEMO SVG – replace with your own later, keep group ids or update JS -->




<svg
   width="100.26486mm"
   height="85.264595mm"
   viewBox="0 0 100.26486 85.264595"
   version="1.1"
   id="svg1"
   xml:space="preserve"
   xmlns="http://www.w3.org/2000/svg"
   xmlns:svg="http://www.w3.org/2000/svg"><defs
     id="defs1" /><path
     id="bottompocket"
     style="fill:none;fill-opacity:1;stroke:#000000;stroke-width:0.264583;stroke-opacity:1"
     d="m 65.132244,38.009224 c 11.703392,-0.442225 23.381481,-1.40216 34.999946,-2.877397 v 45.000174 c 6e-5,2.761694 -2.238517,5.000281 -4.999913,5.000308 H 5.1322099 C 2.3708145,85.132282 0.1322358,82.893695 0.13229401,80.132001 V 35.131377 C 11.750749,36.606482 23.428838,37.566682 35.132244,38.009224 c 8.833933,6.829557 21.166067,6.829557 30,0 z" /><g
     id="g46"
     transform="translate(-54.378055,-105.53437)"><path
       id="path234"
       style="fill:none;stroke:#000000"
       d="m 963.77928,968.47719 c 10.4346,-10e-5 132.28512,-0.43888 132.28362,-10.87678 V 880.63 H 718.11 l 3e-5,76.97041 c 9e-5,10.4379 121.84645,10.87668 132.28342,10.87678 36.92811,25.80955 74.72339,25.80955 113.38583,0 z"
       transform="matrix(0.26458333,0,0,0.26458333,-135.48935,-127.33336)" /></g><g
     id="g45"
     transform="translate(-54.378055,-105.53437)"><path
       id="path70-5"
       style="fill:none;stroke:#000000"
       d="m -51.471652,785.98929 c -46.912319,-1.89461 -91.006748,-5.52025 -132.283278,-10.8769 l 9.2e-4,-55.3096 c 6.85424,10.17299 86.181258,9.43612 132.283421,10.87678 33.38805,25.81259 79.99778,25.81259 113.38583,0 53.010999,-0.82002 126.712219,-1.57786 132.283619,-10.87678 l -0.001,55.3113 c -23.59055,2.2032 -74.74619,9.82091 -132.283276,10.8752 -34.445656,25.88548 -76.88023,25.806 -113.385826,0 z"
       transform="matrix(0.26458333,0,0,0.26458333,103.12884,-64.416075)" /></g><g
     id="g354"><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="M 4.2433631,3.8600088 3.0217819,6.1544755"
       id="path1" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="M 4.2433631,9.3599025 3.0217819,11.654634"
       id="path2" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="M 4.2015589,6.7008401 3.0638506,8.8138025"
       id="path3" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="M 4.222461,12.155358 3.0638506,14.313696"
       id="path4" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="M 4.2433631,14.860061 3.0638506,17.063775"
       id="path5" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="M 4.2015589,20.450706 3.0635993,22.822537"
       id="path6" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="M 4.2015589,17.700627 3.0638506,19.813854"
       id="path7" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 4.2433763,26.118716 -1.179777,2.20398"
       id="path8" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="M 4.222342,23.414278 3.0635993,25.572616"
       id="path9" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="M 4.2013076,28.959548 3.0635993,31.072775"
       id="path10" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 4.2433763,31.617816 -1.179777,2.20398"
       id="path11" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="M 4.2433763,34.369483 3.0634035,37.44119"
       id="path12" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="M 4.2431805,37.988877 3.0423691,40.237835"
       id="path13" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="M 4.2011118,40.830502 3.0634035,42.941877"
       id="path14" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="M 4.2221462,43.534544 3.0423691,45.738523"
       id="path15" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="M 4.2011118,46.328544 3.0634035,48.442565"
       id="path16" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="M 4.2011118,49.08021 3.0213347,51.284189"
       id="path17" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="M 4.2431805,51.739273 3.0213347,54.03321"
       id="path18" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="M 4.2011118,54.578252 3.0213347,56.782231"
       id="path19" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="M 4.2221462,57.283617 3.0634035,59.441294"
       id="path20" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="M 4.2431805,59.988981 3.0213347,62.282919"
       id="path21" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="M 4.2011118,62.830606 3.0634035,64.941981"
       id="path22" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 4.2221462,65.534648 -1.1587427,2.159"
       id="path23" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 4.2221462,68.283668 -1.1587427,2.159"
       id="path24" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="M 4.2221462,71.034012 3.0423691,73.237991"
       id="path25" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 4.2431805,73.739377 -1.179777,2.203979"
       id="path26" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="M 4.2011118,76.578356 3.0634035,78.692377"
       id="path27" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="M 4.2431805,79.237418 4.071589,81.192927 5.9044992,82.243085"
       id="path28" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 6.450599,81.063043 2.1132271,1.137709"
       id="path29" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 9.109926,81.02071 2.203979,1.180042"
       id="path30" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 11.860005,81.02071 2.203715,1.180042"
       id="path31" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 14.700572,81.063043 2.113227,1.137709"
       id="path32" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 17.450651,81.063043 2.113227,1.137709"
       id="path33" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 20.20073,81.063043 2.158339,1.158875"
       id="path34" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 22.950809,81.063043 2.112989,1.137709"
       id="path35" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 25.700703,81.063043 2.158472,1.158875"
       id="path36" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 28.450704,81.063043 2.113094,1.137709"
       id="path37" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 31.200703,81.063043 2.113095,1.137709"
       id="path38" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 33.950703,81.063043 2.113095,1.137709"
       id="path39" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 36.700703,81.063043 2.113095,1.137709"
       id="path40" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 39.450703,81.063043 2.113094,1.137709"
       id="path229" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 42.10995,81.02071 2.294599,1.222375"
       id="path230" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 44.950702,81.063043 2.113095,1.137709"
       id="path231" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 47.700705,81.063043 2.113089,1.137709"
       id="path232" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 50.450704,81.063043 2.203837,1.180042"
       id="path233" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 53.200701,81.063043 2.113095,1.137709"
       id="path311" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 55.950701,81.063043 2.113095,1.137709"
       id="path312" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 58.700701,81.063043 2.203847,1.180042"
       id="path313" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 61.450701,81.063043 2.203847,1.180042"
       id="path314" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 64.200701,81.063043 2.113094,1.137709"
       id="path315" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 66.9507,81.063043 2.113095,1.137709"
       id="path316" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 69.7007,81.063043 2.113095,1.137709"
       id="path317" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 72.4507,81.063043 2.113095,1.137709"
       id="path318" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 75.200697,81.063043 2.203741,1.180042"
       id="path319" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 77.950802,81.063043 2.112963,1.137709"
       id="path320" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 80.700617,81.063043 2.113227,1.137709"
       id="path321" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 83.450696,81.063043 2.113227,1.137709"
       id="path322" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 86.200775,81.063043 2.112963,1.137709"
       id="path323" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 88.95059,81.063043 2.113227,1.137709"
       id="path324" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 91.700669,81.063043 2.113227,1.137709"
       id="path325" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 94.359996,81.02071 1.582909,0.172217"
       id="path326" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 96.442907,81.192927 0.836237,-2.10103"
       id="path327" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="M 95.985331,78.930502 97.2593,76.438127"
       id="path328" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 95.995253,76.13121 1.283891,-2.54"
       id="path329" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="M 96.005175,73.331918 97.2593,70.940085"
       id="path330" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="M 95.985331,70.680793 97.2593,68.188418"
       id="path331" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 96.005175,67.831231 1.273969,-2.489729"
       id="path332" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="M 95.985331,65.180106 97.2593,62.690377"
       id="path333" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 95.995253,62.382137 1.283891,-2.541323"
       id="path334" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="M 96.005175,59.581523 97.2593,57.189689"
       id="path335" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="M 95.985331,56.930398 97.2593,54.440669"
       id="path336" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 95.985331,54.181377 1.293813,-2.590271"
       id="path337" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="M 96.005175,51.331814 97.2593,48.939981"
       id="path338" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 96.005175,48.582794 1.273969,-2.492375"
       id="path339" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="M 95.985331,45.929023 97.2593,43.439294"
       id="path340" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 96.005175,43.082106 1.273969,-2.489729"
       id="path341" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 95.985331,40.430981 1.283891,-2.541323"
       id="path342" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 95.995253,37.630367 1.262655,-3.00953"
       id="path343" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 96.006958,34.253066 1.271853,-2.55852"
       id="path344" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 95.986321,31.522566 1.29249,-2.655622"
       id="path345" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 96.006958,28.597069 1.271853,-2.557728"
       id="path346" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 96.006958,25.769731 1.25095,-2.460096"
       id="path347" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 95.986321,23.040025 1.213895,-2.589319"
       id="path348" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 96.062508,19.813854 1.137708,-2.113227"
       id="path349" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 96.02282,17.154527 1.198563,-2.24909"
       id="path350" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 96.062508,14.313696 1.137708,-2.112962"
       id="path351" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="M 96.062508,11.563882 97.200216,9.4506546"
       id="path352" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="M 96.042664,8.8591786 97.200216,6.7008401"
       id="path353" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="M 96.02282,6.1544755 97.200216,3.9507609"
       id="path354" /></g><g
     id="g386"><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="M 6.4056635,3.04244 8.564002,4.2010504"
       id="path355" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="M 9.1103666,3.0215379 11.314081,4.2010504"
       id="path356" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="M 11.950933,3.0633421 14.06416,4.2010504"
       id="path357" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 14.701012,3.0633421 2.113227,1.1377083"
       id="path358" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 17.451091,3.0633421 2.112963,1.1377083"
       id="path359" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 20.20117,3.0633421 2.112963,1.1377083"
       id="path360" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 22.860233,3.0215379 2.294731,1.2215813"
       id="path361" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 25.610312,3.0215379 2.203714,1.1795125"
       id="path362" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 28.405767,3.04244 2.203714,1.1796448"
       id="path363" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 31.110205,3.0215379 2.203979,1.1795125"
       id="path364" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 33.951037,3.0633421 2.113227,1.1377083"
       id="path365" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 36.610364,3.0215379 2.203714,1.1795125"
       id="path366" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 39.45093,3.0633421 2.113227,1.1377083"
       id="path367" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 42.201009,3.0633421 2.113227,1.1377083"
       id="path368" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 44.860336,3.0215379 2.203715,1.1795125"
       id="path369" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="M 47.701168,3.0633421 49.81413,4.2010504"
       id="path370" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 50.450982,3.0633421 2.203979,1.1797771"
       id="path371" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 53.201061,3.0633421 2.112963,1.1377083"
       id="path372" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 55.860388,3.0215379 2.203715,1.1795125"
       id="path373" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 58.700955,3.0633421 2.113227,1.1377083"
       id="path374" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 61.360282,3.0215379 2.203979,1.1795125"
       id="path375" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 64.110361,3.0215379 2.203715,1.1795125"
       id="path376" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 66.950928,3.0633421 2.113227,1.1377083"
       id="path377" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 69.610255,3.0215379 2.203979,1.1795125"
       id="path378" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 72.451086,3.0633421 2.112963,1.1377083"
       id="path379" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 75.200107,3.0633421 2.114021,1.1377083"
       id="path380" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 77.859169,3.0215379 2.296584,1.2215813"
       id="path381" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 80.700794,3.0633421 2.114021,1.1377083"
       id="path382" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 83.449815,3.0633421 2.114021,1.1377083"
       id="path383" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 86.201482,3.0633421 2.111375,1.1377083"
       id="path384" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 88.950503,3.0633421 2.11402,1.1377083"
       id="path385" /><path
       style="fill:none;stroke:#ff0000;stroke-width:0.2;stroke-dashoffset:0.871181"
       d="m 91.702169,3.0633421 2.111375,1.1377083"
       id="path386" /></g></svg>




          <div id="svg-overlay-buttons"></div>

          </div>
          <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-between gap-3 mt-4">
            <div>
              <div class="fw-semibold">Live price</div>
              <div
                class="fs-3"
                id="wallet-price-display"
                data-currency="<?php echo esc_attr(get_woocommerce_currency_symbol()); ?>"
              >&mdash;</div>
            </div>
            <div class="text-md-end">
              <button type="submit" class="btn btn-primary btn-lg" <?php echo $wallet_product_id ? '' : 'disabled'; ?>>
                Add to cart
              </button>
              <?php if (!$wallet_product_id) : ?>
                <div class="form-text text-danger">Set a product ID to enable cart submissions.</div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>

</div>

</form>

  <section class="mt-5">
    <div class="container">
      <h2 class="h4 text-center mb-4">Some examples of previous work</h2>
      <div class="row g-3 g-md-4">
        <div class="col-6 col-md-4 col-lg-3">
          <div class="ratio ratio-4x3 bg-white border rounded shadow-sm overflow-hidden">
            <button
              type="button"
              class="gallery-trigger"
              data-bs-toggle="modal"
              data-bs-target="#galleryModal"
              data-image-src="/wp-content/themes/bootscore-main-child/images/wallet-images/20251016_0009-.jpg"
              data-image-alt="Wallet example 1"
            >
              <img src="/wp-content/themes/bootscore-main-child/images/wallet-images/20251016_0009-.jpg" class="w-100 h-100 object-fit-cover" alt="Wallet example 1">
            </button>
          </div>
        </div>
        <div class="col-6 col-md-4 col-lg-3">
          <div class="ratio ratio-4x3 bg-white border rounded shadow-sm overflow-hidden">
            <button
              type="button"
              class="gallery-trigger"
              data-bs-toggle="modal"
              data-bs-target="#galleryModal"
              data-image-src="/wp-content/themes/bootscore-main-child/images/wallet-images/20251027_0005-.jpg"
              data-image-alt="Wallet example 2"
            >
              <img src="/wp-content/themes/bootscore-main-child/images/wallet-images/20251027_0005-.jpg" class="w-100 h-100 object-fit-cover" alt="Wallet example 2">
            </button>
          </div>
        </div>
        <div class="col-6 col-md-4 col-lg-3">
          <div class="ratio ratio-4x3 bg-white border rounded shadow-sm overflow-hidden">
            <button
              type="button"
              class="gallery-trigger"
              data-bs-toggle="modal"
              data-bs-target="#galleryModal"
              data-image-src="/wp-content/themes/bootscore-main-child/images/wallet-images/20251204_0009-.jpg"
              data-image-alt="Wallet example 3"
            >
              <img src="/wp-content/themes/bootscore-main-child/images/wallet-images/20251204_0009-.jpg" class="w-100 h-100 object-fit-cover" alt="Wallet example 3">
            </button>
          </div>
        </div>
        <div class="col-6 col-md-4 col-lg-3">
          <div class="ratio ratio-4x3 bg-white border rounded shadow-sm overflow-hidden">
            <button
              type="button"
              class="gallery-trigger"
              data-bs-toggle="modal"
              data-bs-target="#galleryModal"
              data-image-src="/wp-content/themes/bootscore-main-child/images/wallet-images/20251204_0026-.jpg"
              data-image-alt="Wallet example 4"
            >
              <img src="/wp-content/themes/bootscore-main-child/images/wallet-images/20251204_0026-.jpg" class="w-100 h-100 object-fit-cover" alt="Wallet example 4">
            </button>
          </div>
        </div>
        <div class="col-6 col-md-4 col-lg-3">
          <div class="ratio ratio-4x3 bg-white border rounded shadow-sm overflow-hidden">
            <button
              type="button"
              class="gallery-trigger"
              data-bs-toggle="modal"
              data-bs-target="#galleryModal"
              data-image-src="/wp-content/themes/bootscore-main-child/images/wallet-images/20251207_0006-.jpg"
              data-image-alt="Wallet example 5"
            >
              <img src="/wp-content/themes/bootscore-main-child/images/wallet-images/20251207_0006-.jpg" class="w-100 h-100 object-fit-cover" alt="Wallet example 5">
            </button>
          </div>
        </div>
        <div class="col-6 col-md-4 col-lg-3">
          <div class="ratio ratio-4x3 bg-white border rounded shadow-sm overflow-hidden">
            <button
              type="button"
              class="gallery-trigger"
              data-bs-toggle="modal"
              data-bs-target="#galleryModal"
              data-image-src="/wp-content/themes/bootscore-main-child/images/wallet-images/20251207_0008-.jpg"
              data-image-alt="Wallet example 6"
            >
              <img src="/wp-content/themes/bootscore-main-child/images/wallet-images/20251207_0008-.jpg" class="w-100 h-100 object-fit-cover" alt="Wallet example 6">
            </button>
          </div>
        </div>
        <div class="col-6 col-md-4 col-lg-3">
          <div class="ratio ratio-4x3 bg-white border rounded shadow-sm overflow-hidden">
            <button
              type="button"
              class="gallery-trigger"
              data-bs-toggle="modal"
              data-bs-target="#galleryModal"
              data-image-src="/wp-content/themes/bootscore-main-child/images/wallet-images/20251207_0013-.jpg"
              data-image-alt="Wallet example 7"
            >
              <img src="/wp-content/themes/bootscore-main-child/images/wallet-images/20251207_0013-.jpg" class="w-100 h-100 object-fit-cover" alt="Wallet example 7">
            </button>
          </div>
        </div>
      </div>
    </div>
  </section>

  <div class="modal fade" id="galleryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Gallery image</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <img src="" alt="" class="img-fluid w-100 rounded">
        </div>
      </div>
    </div>
  </div>

  <script>
    const $ = window.jQuery;
    const currencySymbol = document.getElementById('wallet-price-display')?.dataset.currency || '£';
    const pricingRules = {
      basePrice: 185,
      ostrichPremium: 45,
      liningAllPremium: 30,
      debossPremium: 10,
      paintedEdgePremium: 15,
      customEdgePaintPremium: 5,
      metalCorners: {
        none: 0,
        brass: 20,
        silver: 20,
        'gunmetal-grey': 18,
        black: 18,
        gold: 22,
        'rose-gold': 22,
      },
    };

    const leatherCollections = {
      buttero: {
        label: "Buttero",
        swatches: [
          { label: "Tan", color: "#c28e5a", image: "/wp-content/themes/bootscore-main-child/images/buttero/WALPIER-BUTTERO-023-TAN-2-WM_b3c3cd0a-0a97-48cf-9a0a-172ad1b32709.webp" },
          { label: "Dark Brown", color: "#8b5a2b", image: "/wp-content/themes/bootscore-main-child/images/buttero/WALPIER-BUTTERO-07-MEDUM-BROWN-2-WM.webp" },
          { label: "Chocolate", color: "#3b2a1a", image: "/wp-content/themes/bootscore-main-child/images/buttero/WALPIER-BUTTERO-02-WHISKEY-2-WM.webp" },
          { label: "Black", color: "#000000", image: "/wp-content/themes/bootscore-main-child/images/buttero/WALPIER-BUTTERO-04-NAVY-2-WM.webp" },
          { label: "Oxblood", color: "#4a2f2f", image: "/wp-content/themes/bootscore-main-child/images/buttero/WALPIER-BUTTERO-05-BURGUNDY-2-WM.webp" },
          { label: "Walnut", color: "#6b4f3a", image: "/wp-content/themes/bootscore-main-child/images/buttero/WALPIER-BUTTERO-03-BISCUIT-2-WM.webp" },
          { label: "Sand", color: "#d2a679", image: "/wp-content/themes/bootscore-main-child/images/buttero/WALPIER-BUTTERO-016-YELLOW-2-WM_b3ef4c01-42f2-462c-ab4e-c282e573c76c.webp" },
          { label: "Navy", color: "#3a4f6b", image: "/wp-content/themes/bootscore-main-child/images/buttero/WALPIER-BUTTERO-04-NAVY-2-WM.webp" },
          { label: "Olive", color: "#374331", image: "/wp-content/themes/bootscore-main-child/images/buttero/WALPIER-BUTTERO-19-OLIVE-2-WM.webp" },
          { label: "Burgundy", color: "#7b3f61", image: "/wp-content/themes/bootscore-main-child/images/buttero/WALPIER-BUTTERO-05-BURGUNDY-2-WM.webp" },
          { label: "Yellow", color: "#ffdd33", image: "/wp-content/themes/bootscore-main-child/images/buttero/WALPIER-BUTTERO-016-YELLOW-2-WM_b3ef4c01-42f2-462c-ab4e-c282e573c76c.webp" },
          { label: "Pink", color: "#ffc0cb", image: "/wp-content/themes/bootscore-main-child/images/buttero/WALPIER-BUTTERO-101-PINK-2-WM.jpg" },
          { label: "Blue", color: "#102f6b", image: "/wp-content/themes/bootscore-main-child/images/buttero/WALPIER-BUTTERO-102-BLUE-2-WM.webp" },
          { label: "Green", color: "#2f5a32", image: "/wp-content/themes/bootscore-main-child/images/buttero/WALPIER-BUTTERO-11-GREEN-2-WM_3dc4f045-eebd-490c-918f-e13e9d29e822.webp" },
          { label: "Orange", color: "#f4801f", image: "/wp-content/themes/bootscore-main-child/images/buttero/WALPIER-BUTTERO-12-ORANGE-2-WM.webp" },
          { label: "Foresta", color: "#37523d", image: "/wp-content/themes/bootscore-main-child/images/buttero/WALPIER-BUTTERO-FORESTA-2-WM_eca494e3-598d-4966-814d-4b236efe786e.webp" },
        ],
      },
      badalassi: {
        label: "Badalassi Carlo Wax",
        swatches: [
          { label: "Cognac", color: "#b36a3c", image: "/wp-content/themes/bootscore-main-child/images/badalassi-carlo-wax/badalassi-carlo-wax-cognac1.webp" },
          { label: "Grigio", color: "#7b7b7b", image: "/wp-content/themes/bootscore-main-child/images/badalassi-carlo-wax/badalassi-carlo-wax-grigio1.jpeg" },
          { label: "Napoli", color: "#d8a23f", image: "/wp-content/themes/bootscore-main-child/images/badalassi-carlo-wax/badalassi-carlo-wax-napoli1.webp" },
          { label: "Oliva", color: "#566d3b", image: "/wp-content/themes/bootscore-main-child/images/badalassi-carlo-wax/badalassi-carlo-wax-oliva1.webp" },
          { label: "Olmo", color: "#8b5a2b", image: "/wp-content/themes/bootscore-main-child/images/badalassi-carlo-wax/badalassi-carlo-wax-olmo1.webp" },
          { label: "Ortensia", color: "#2f4f9f", image: "/wp-content/themes/bootscore-main-child/images/badalassi-carlo-wax/badalassi-carlo-wax-ortensia1.webp" },
          { label: "Papavero", color: "#b22222", image: "/wp-content/themes/bootscore-main-child/images/badalassi-carlo-wax/badalassi-carlo-wax-papavero1.webp" },
          { label: "Prugna", color: "#70304a", image: "/wp-content/themes/bootscore-main-child/images/badalassi-carlo-wax/badalassi-carlo-wax-prugna1.webp" },
          { label: "Tabacco", color: "#7b4b26", image: "/wp-content/themes/bootscore-main-child/images/badalassi-carlo-wax/badalassi-carlo-wax-tabacco1.webp" },
        ],
      },
    };

    const ostrichSwatches = [
      { label: "Blue Ostrich", color: "#2f3983", image: "/wp-content/themes/bootscore-main-child/images/Ostrich/blue.webp" },
      { label: "Dark Brown Ostrich", color: "#955f3a", image: "/wp-content/themes/bootscore-main-child/images/Ostrich/dark_brown.webp" },
      { label: "Dark Grey Ostrich", color: "#645e5c", image: "/wp-content/themes/bootscore-main-child/images/Ostrich/dark_grey.webp" },
      { label: "Green Ostrich", color: "#77854d", image: "/wp-content/themes/bootscore-main-child/images/Ostrich/green.webp" },
      { label: "Light Brown Ostrich", color: "#b3693c", image: "/wp-content/themes/bootscore-main-child/images/Ostrich/light_brown.webp" },
      { label: "Light Grey Ostrich", color: "#6e7170", image: "/wp-content/themes/bootscore-main-child/images/Ostrich/light_grey.webp" },
      { label: "Medium Brown Ostrich", color: "#c05a27", image: "/wp-content/themes/bootscore-main-child/images/Ostrich/med_brown.webp" },
      { label: "Red Brown Ostrich", color: "#c34d30", image: "/wp-content/themes/bootscore-main-child/images/Ostrich/red_brown.webp" },
      { label: "Yellow Ostrich", color: "#e1980b", image: "/wp-content/themes/bootscore-main-child/images/Ostrich/yellow.webp" },
    ];

    const liningLeatherSwatches = [
      { label: "Lambskin Black", color: "#0c0c0c", image: "/wp-content/themes/bootscore-main-child/images/lambskin/nappa-aniline-lambskin-leather-black.jpg" },
      { label: "Lambskin Blue", color: "#2c5ea5", image: "/wp-content/themes/bootscore-main-child/images/lambskin/nappa-aniline-lambskin-leather-blue.jpg" },
      { label: "Lambskin Navy", color: "#1e2f4f", image: "/wp-content/themes/bootscore-main-child/images/lambskin/nappa-aniline-lambskin-leather-navy.jpg" },
      { label: "Lambskin Dark Brown", color: "#5a3a24", image: "/wp-content/themes/bootscore-main-child/images/lambskin/nappa-aniline-lambskin-leather-dark-brown.jpg" },
      { label: "Lambskin Light Brown", color: "#b27a4f", image: "/wp-content/themes/bootscore-main-child/images/lambskin/nappa-aniline-lambskin-leather-light-brown.jpg" },
      { label: "Lambskin Oxblood", color: "#5b1b1f", image: "/wp-content/themes/bootscore-main-child/images/lambskin/nappa-aniline-lambskin-leather-oxblood.jpg" },
      { label: "Lambskin Red", color: "#b0202f", image: "/wp-content/themes/bootscore-main-child/images/lambskin/nappa-aniline-lambskin-leather-red.jpg" },
      { label: "Lambskin Orange", color: "#d86f1f", image: "/wp-content/themes/bootscore-main-child/images/lambskin/nappa-aniline-lambskin-leather-orange.jpg" },
      { label: "Lambskin Yellow", color: "#d8b023", image: "/wp-content/themes/bootscore-main-child/images/lambskin/nappa-aniline-lambskin-leather-yellow.jpg" },
      { label: "Lambskin Dark Green", color: "#1f3f2f", image: "/wp-content/themes/bootscore-main-child/images/lambskin/nappa-aniline-lambskin-leather-dark-green.jpg" },
      { label: "Lambskin Green", color: "#2f6b3c", image: "/wp-content/themes/bootscore-main-child/images/lambskin/nappa-aniline-lambskin-leather-green.jpg" },
    ];

    const leatherSelectIds = ["color-outer", "color-interior", "color-pockets"];
    let currentLeatherCollection = "buttero";
    let useOstrichBottom = false;
    const fallbackSwatch = "leather.jpg";

    function buildSwatchImageMap(collectionKey) {
      return leatherCollections[collectionKey].swatches.reduce((map, swatch) => {
        map[swatch.label] = swatch.image;
        return map;
      }, {});
    }

    let swatchImages = buildSwatchImageMap(currentLeatherCollection);

    function buildSwatchSelect($select) {
      const isColorOnly = $select.is('#color-stitching, #color-stitching2, #edge-colour');
      const $wrapper = $('<div class="swatch-select"></div>');
      const $toggle = $(
        '<button type="button" class="swatch-toggle w-100 d-flex align-items-center justify-content-between" aria-haspopup="listbox" aria-expanded="false"></button>'
      );
      const $selected = $('<span class="swatch-label d-flex align-items-center"></span>');
      const $thumb = $('<span class="swatch-thumb flex-shrink-0"></span>');
      const $textWrap = $('<span class="d-flex flex-column flex-grow-1 text-start"></span>');
      const $name = $('<span class="fw-semibold"></span>');
      const $value = $('<span class="swatch-value"></span>');
      $textWrap.append($name, $value);
      $selected.append($thumb, $textWrap);
      const $previewBtn = isColorOnly
        ? null
        : $('<button type="button" class="btn btn-link btn-sm text-decoration-none">View leather</button>');
      const $chevron = $('<span class="ms-2">▼</span>');
      const $rightStack = $('<span class="d-flex align-items-center gap-2"></span>');

      if ($previewBtn) $rightStack.append($previewBtn);
      $rightStack.append($chevron);

      $toggle.append($selected, $rightStack);

      const $menu = $('<ul class="swatch-menu" role="listbox"></ul>');
      let currentImage = null;
      let currentLabel = '';

      function syncFromSelect() {
        const $selectedOption = $select.find(':selected');
        const label = $selectedOption.text().trim();
        const colorHex = $selectedOption.data('color') || $selectedOption.val();
        const explicitImage = $selectedOption.data('image');
        const image = isColorOnly
          ? null
          : explicitImage !== undefined
            ? explicitImage || null
            : swatchImages[label] || fallbackSwatch;

        updateSelection($thumb, $name, $value, image, label, colorHex, isColorOnly);
        currentImage = image;
        currentLabel = label;

        $menu.find('[aria-selected]')?.removeAttr('aria-selected');
        const $matchedItem = $menu.find(`[data-value="${$selectedOption.val()}"]`);
        if ($matchedItem.length) {
          $matchedItem.attr('aria-selected', 'true');
        }
      }

      $select.find('option').each(function () {
        const $option = $(this);
        const label = $option.text().trim();
        const colorHex = $option.data('color') || $option.val();
        const explicitImage = $option.data('image');
        const image = isColorOnly
          ? null
          : explicitImage !== undefined
            ? explicitImage || null
            : swatchImages[label] || fallbackSwatch;
        if (image !== undefined) {
          $option.attr('data-image', image || '');
        }

        const $item = $('<li class="swatch-item" role="option"></li>');
        const $itemThumb = $('<span class="swatch-thumb"></span>');
        if (isColorOnly) {
          $itemThumb.addClass('color-only').css('background-color', colorHex);
        } else {
          if (image) {
            $itemThumb.css('background-image', `url("${image}")`);
          } else {
            $itemThumb.addClass('color-only').css('background-color', colorHex);
          }
        }
        const $itemText = $('<span class="swatch-name fw-semibold"></span>').text(label);
        const $itemValue = $('<span class="swatch-value"></span>').text(colorHex);
        const $textGroup = $('<span class="d-flex flex-column flex-grow-1"></span>').append($itemText, $itemValue);

        $item.append($itemThumb, $textGroup).attr('data-value', $option.val());

        if ($option.is(':selected')) {
          $item.attr('aria-selected', 'true');
          updateSelection($thumb, $name, $value, image, label, colorHex, isColorOnly);
          currentImage = image;
          currentLabel = label;
        }

        $item.on('click', function () {
          $select.val($option.val()).trigger('change');
          updateSelection($thumb, $name, $value, image, label, colorHex, isColorOnly);
          currentImage = image;
          currentLabel = label;
          $menu.find('[aria-selected]')?.removeAttr('aria-selected');
          $item.attr('aria-selected', 'true');
          $menu.hide();
          $toggle.attr('aria-expanded', 'false');
        });

        $menu.append($item);
      });

      $toggle.on('click', function () {
        const isOpen = $menu.is(':visible');
        $('.swatch-menu').hide();
        $('.swatch-toggle').attr('aria-expanded', 'false');
        if (!isOpen) {
          $menu.show();
          $toggle.attr('aria-expanded', 'true');
        }
      });

      if ($previewBtn) {
        $previewBtn.on('click', function (event) {
          event.stopPropagation();
          if (currentImage) {
            showSwatchPreview(currentImage, currentLabel);
          }
        });
      }

      $select.off('change.swatch').on('change.swatch', syncFromSelect);
      syncFromSelect();

      const docEventNamespace = `click.swatch-${$select.attr('id') || Math.random().toString(36).slice(2)}`;
      $(document)
        .off(docEventNamespace)
        .on(docEventNamespace, function (e) {
          if (!$.contains($wrapper[0], e.target)) {
            $menu.hide();
            $toggle.attr('aria-expanded', 'false');
          }
        });

      $wrapper.append($toggle, $menu);
      $select.after($wrapper).addClass('visually-hidden');
    }

    function buildLeatherPicker($select) {
      const $existing = $select.next('.swatch-select, .leather-picker');
      if ($existing.length) {
        $existing.remove();
      }

      const $wrapper = $('<div class="leather-picker d-flex align-items-center gap-3 p-3 border rounded bg-white shadow-sm"></div>');
      const $thumb = $('<span class="swatch-thumb flex-shrink-0"></span>');
      const $textWrap = $('<div class="d-flex flex-column flex-grow-1"></div>');
      const $name = $('<span class="fw-semibold"></span>');
      const $value = $('<span class="swatch-value"></span>');
      const $caret = $('<span class="picker-caret" aria-hidden="true">▾</span>');
      let currentImage = null;
      let currentLabel = '';

      function syncFromSelect() {
        const $selected = $select.find(':selected');
        const label = $selected.text().trim();
        const colorHex = $selected.data('color') || $selected.val();
        const explicitImage = $selected.data('image');
        const image = explicitImage !== undefined
          ? explicitImage || null
          : swatchImages[label] || fallbackSwatch;

        updateSelection($thumb, $name, $value, image, label, colorHex, false);
        currentImage = image;
        currentLabel = label;
      }

      $textWrap.append($name, $value);
      $wrapper.append($thumb, $textWrap, $caret);

      $wrapper.on('click', function () {
        openLeatherModal($select, syncFromSelect);
      });

      $select.addClass('visually-hidden');
      $select.after($wrapper);

      $select.off('change.leather').on('change.leather', syncFromSelect);
      syncFromSelect();
    }

    function populateSelectWithSwatches($select, swatches) {
      const previousValue = $select.val();
      $select.empty();

      swatches.forEach(({ label, color, image }) => {
        const $option = $('<option></option>')
          .text(label)
          .val(color)
          .attr('data-color', color)
          .attr('data-image', image);
        $select.append($option);
      });

      const fallbackValue = swatches[0]?.color;
      const nextValue = swatches.some((swatch) => swatch.color === previousValue) ? previousValue : fallbackValue;
      $select.val(nextValue);

      $select.next('.swatch-select, .leather-picker').remove();
      $select.removeClass('visually-hidden');

      if (leatherSelectIds.includes($select.attr('id'))) {
        buildLeatherPicker($select);
      } else {
        buildSwatchSelect($select);
      }
      $select.trigger('change');
    }

    function rebuildLeatherSelects(collectionKey) {
      swatchImages = buildSwatchImageMap(collectionKey);
      leatherSelectIds.forEach((id) => {
        const $select = $('#' + id);
        if (!$select.length) return;

        const swatches = id === 'color-pockets' && useOstrichBottom
          ? ostrichSwatches
          : leatherCollections[collectionKey].swatches;

        populateSelectWithSwatches($select, swatches);
      });

      buildSvgOverlayButtons();
    }

    function updateLeatherDescription(collectionKey) {
      $('.leather-description').addClass('d-none');
      $(`.leather-description[data-collection="${collectionKey}"]`).removeClass('d-none');
    }

    function updateSelection($thumb, $name, $value, image, label, colorHex, isColorOnly) {
      const safeColor = colorHex || '#f8f9fa';

      if (isColorOnly || !image) {
        $thumb.addClass('color-only').css({ backgroundColor: safeColor, backgroundImage: 'none' });
      } else {
        $thumb.removeClass('color-only').css({ backgroundImage: `url("${image}")`, backgroundColor: '' });
      }
      $name.text(label);
      $value.text(colorHex);
    }

    function ensurePreviewShell() {
      let $preview = $('#swatch-preview');
      if ($preview.length) return $preview;

      $preview = $(
        '<div id="swatch-preview" class="swatch-preview-backdrop d-none" role="dialog" aria-label="Leather preview">\
          <div class="card swatch-preview-card">\
            <div class="card-body">\
              <div class="d-flex justify-content-end">\
                <button type="button" class="btn-close" aria-label="Close preview"></button>\
              </div>\
              <img src="" alt="Leather preview" class="img-fluid rounded mb-2">\
              <div class="fw-semibold" id="swatch-preview-label"></div>\
            </div>\
          </div>\
        </div>'
      );

      $preview.on('click', function (event) {
        if (event.target === this || $(event.target).hasClass('btn-close')) {
          hideSwatchPreview();
        }
      });

      $('body').append($preview);
      return $preview;
    }

    function showSwatchPreview(imageSrc, label) {
      const $preview = ensurePreviewShell();
      $preview.find('img').attr('src', imageSrc).attr('alt', `${label} leather preview`);
      $preview.find('#swatch-preview-label').text(label);
      $preview.removeClass('d-none');
    }

    function hideSwatchPreview() {
      $('#swatch-preview').addClass('d-none');
    }

    function ensureLeatherPickerModal() {
      let $modal = $('#leather-picker-modal');
      if ($modal.length) return $modal;

      $modal = $(
        '<div class="modal fade" id="leather-picker-modal" tabindex="-1" aria-hidden="true">\
          <div class="modal-dialog modal-dialog-centered modal-xl">\
            <div class="modal-content">\
              <div class="modal-header">\
                <h5 class="modal-title leather-modal-title">Choose leather</h5>\
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>\
              </div>\
              <div class="modal-body">\
                <div class="leather-modal-options"></div>\
              </div>\
            </div>\
          </div>\
        </div>'
      );

      $('body').append($modal);
      return $modal;
    }

    function openLeatherModal($select, onSelect) {
      const $modal = ensureLeatherPickerModal();
      const modalInstance = bootstrap.Modal.getOrCreateInstance($modal[0]);
      const $optionsList = $modal.find('.leather-modal-options');
      const titleLabel = $(`label[for="${$select.attr('id')}"]`).text().trim() || 'Leather';

      $modal.find('.leather-modal-title').text(`${titleLabel} options`);
      $optionsList.empty();

      const currentValue = $select.val();

      $select.find('option').each(function () {
        const $option = $(this);
        const optionLabel = $option.text().trim();
        const value = $option.val();
        const colorHex = $option.data('color') || value;
        const explicitImage = $option.data('image');
        const image = explicitImage !== undefined
          ? explicitImage || null
          : swatchImages[optionLabel] || fallbackSwatch;

        const $item = $('<button type="button" class="leather-modal-option"></button>');
        const $thumb = $('<span class="leather-swatch-thumb"></span>');
        if (image) {
          $thumb.css('background-image', `url("${image}")`);
        } else {
          $thumb.addClass('color-only').css('background-color', colorHex || '#f8f9fa');
        }
        const $itemName = $('<span class="leather-modal-label"></span>').text(optionLabel);
        const $itemValue = $('<span class="leather-modal-value"></span>').text(colorHex);

        if (value === currentValue) {
          $item.addClass('active');
        }

        $item.on('click', function () {
          $select.val(value).trigger('change');
          onSelect?.();
          modalInstance.hide();
        });

        $item.append($thumb, $itemName, $itemValue);
        $optionsList.append($item);
      });

      modalInstance.show();
    }

    function buildSvgOverlayButtons() {
      const svg = document.querySelector('#svg-wrapper svg');
      const $wrapper = $('#svg-wrapper');
      if (!svg || !$wrapper.length) return;

      let $overlay = $('#svg-overlay-buttons');
      if (!$overlay.length) {
        $overlay = $('<div id="svg-overlay-buttons"></div>');
        $wrapper.append($overlay);
      }

      $overlay.empty();

      const overlayMappings = [
        { selectId: 'color-outer', groupSelector: '#g46', label: 'Outer leather', customTop: 80 },
        { selectId: 'color-interior', groupSelector: '#g45', label: 'Top pocket' },
        { selectId: 'color-pockets', groupSelector: '#bottompocket', label: 'Bottom pocket' },
      ];

      const wrapperRect = $wrapper[0].getBoundingClientRect();

      overlayMappings.forEach((mapping) => {
        const target = svg.querySelector(mapping.groupSelector);
        const $select = $('#' + mapping.selectId);
        if (!target || !$select.length) return;

        const rect = target.getBoundingClientRect();
        const left = rect.left - wrapperRect.left + rect.width / 2;
        const top = mapping.customTop ?? rect.top - wrapperRect.top + rect.height / 2;

        const $buttonContainer = $('<div class="svg-overlay-btn d-flex gap-2"></div>');
        $buttonContainer.css({ top: `${top}px`, left: `${left}px` });

        const $viewButton = $('<button type="button" class="btn btn-light btn-sm shadow">View leather</button>');
        $viewButton.on('click', function (event) {
          event.stopPropagation();
          const $selected = $select.find(':selected');
          const image = $selected.data('image') || fallbackSwatch;
          const label = `${mapping.label}: ${$selected.text().trim()}`;
          showSwatchPreview(image, label);
        });

        $buttonContainer.append($viewButton);

        $overlay.append($buttonContainer);
      });
    }



    function debugShowTexture(svg, imageId, imageUrl) {
  if (!svg || !imageUrl) return null;

  const svgns = 'http://www.w3.org/2000/svg';
  const xlinkns = 'http://www.w3.org/1999/xlink';

  if (!svg.getAttribute('xmlns:xlink')) {
    svg.setAttribute('xmlns:xlink', xlinkns);
  }

  let imageEl = svg.querySelector('#' + imageId);
  if (!imageEl) {
    imageEl = document.createElementNS(svgns, 'image');
    imageEl.setAttribute('id', imageId);
    imageEl.setAttribute('preserveAspectRatio', 'xMidYMid slice');
    const stitching = svg.querySelector('#g354'); // or whatever must be on top
stitching.parentNode.insertBefore(imageEl, stitching);
  }

  const vb = svg.viewBox && svg.viewBox.baseVal;
  const w = vb ? vb.width : svg.clientWidth || 500;
  const h = vb ? vb.height : svg.clientHeight || 500;

  imageEl.removeAttribute('clip-path');      // IMPORTANT: no clipping
  imageEl.setAttribute('x', 0);
  imageEl.setAttribute('y', 0);
  imageEl.setAttribute('width', w);
  imageEl.setAttribute('height', h);
  imageEl.style.opacity = 0.7;               // should visibly tint everything

  imageEl.setAttributeNS(xlinkns, 'xlink:href', imageUrl);
  imageEl.setAttribute('href', imageUrl);

  return imageEl;
}




    function bindSvgColoring() {
      const mappings = [
        { inputId: 'color-outer', inputGroup: '#g46', mode: 'fill' },
        { inputId: 'color-interior', inputGroup: '#g45', mode: 'fill' },
        { inputId: 'color-pockets', inputGroup: '#bottompocket', mode: 'fill' },
        { inputId: 'color-stitching', inputGroup: '#g43', mode: 'stroke' },
        { inputId: 'color-stitching2', inputGroup: '#g354', mode: 'stroke' },
      ];



function ensureClippedTexture(svgFallback, imageId, clipId, imageUrl, targetShape) {
  if (!imageUrl || !targetShape) return null;

  const svgns   = 'http://www.w3.org/2000/svg';
  const xlinkns = 'http://www.w3.org/1999/xlink';

  const svg = targetShape.ownerSVGElement || svgFallback;
  if (!svg) return null;

  if (!svg.getAttribute('xmlns:xlink')) {
    svg.setAttribute('xmlns:xlink', xlinkns);
  }

  let defs = svg.querySelector('defs');
  if (!defs) {
    defs = document.createElementNS(svgns, 'defs');
    svg.prepend(defs);
  }

  // clipPath based on the actual shape (path), not the group
  let clipPath = svg.querySelector('#' + clipId);
  if (!clipPath) {
    clipPath = document.createElementNS(svgns, 'clipPath');
    clipPath.setAttribute('id', clipId);
    clipPath.setAttribute('clipPathUnits', 'userSpaceOnUse');
    defs.appendChild(clipPath);
  }

  while (clipPath.firstChild) clipPath.firstChild.remove();

  const clone = targetShape.cloneNode(true);
  clone.removeAttribute('id');
  clipPath.appendChild(clone);

  // image
  let imageEl = svg.querySelector('#' + imageId);
  if (!imageEl) {
    imageEl = document.createElementNS(svgns, 'image');
    imageEl.setAttribute('id', imageId);
    imageEl.setAttribute('preserveAspectRatio', 'xMidYMid slice');
    imageEl.style.pointerEvents = 'none';

    // insert before stitching overlay so stitches stay on top
    const parent = targetShape.parentNode;
    const stitching = svg.querySelector('#g354');

    if (stitching && stitching.parentNode === parent) {
      parent.insertBefore(imageEl, stitching);
    } else {
      parent.insertBefore(imageEl, targetShape.nextSibling);
    }
  }

  const bbox = targetShape.getBBox();
  if (!bbox.width || !bbox.height) return null;

  imageEl.setAttribute('clip-path', 'url(#' + clipId + ')');
  imageEl.setAttribute('x', bbox.x);
  imageEl.setAttribute('y', bbox.y);
  imageEl.setAttribute('width', bbox.width);
  imageEl.setAttribute('height', bbox.height);

  imageEl.setAttributeNS(xlinkns, 'xlink:href', imageUrl);
  imageEl.setAttribute('href', imageUrl);

  return imageEl;
}




      function removeClippedTexture(svg, imageId) {
        const existingImage = svg?.querySelector(`#${imageId}`);
        if (existingImage) {
          existingImage.remove();
        }
      }

      mappings.forEach(({ inputId, inputGroup, mode }) => {
        const $select = $('#' + inputId);
        const $group = $(inputGroup);
        if (!$select.length || !$group.length) return;

        function applyColor() {
          const color = $select.val();
          const image = $select.find(':selected').data('image');
          const svg = document.querySelector('#svg-wrapper svg');
          const isPocketSelector = inputId === 'color-pockets';
          const shouldUseOstrichTexture = isPocketSelector && useOstrichBottom;

          if (isPocketSelector) {
            if (shouldUseOstrichTexture) {
              ensureClippedTexture(svg, 'pocket-ostrich-texture', 'pocket-ostrich-clip', image, $group[0]);
            } else {
              removeClippedTexture(svg, 'pocket-ostrich-texture');
            }
          }

          // Apply the style to the target element and any descendants (important for lone paths)
          $group.find('*').addBack().each(function () {
            if (mode === 'fill') {
              this.style.fill = color;
            } else if (mode === 'stroke') {
              this.style.stroke = color;
            }
          });
        }

        $select.on('change', applyColor);
        applyColor();
      });
    }

    function gatherSelections() {
      return {
        leather_collection: $('#leather-collection').val(),
        outer_leather: $('#color-outer').val(),
        top_pocket: $('#color-interior').val(),
        bottom_pocket: $('#color-pockets').val(),
        bottom_ostrich: $('#bottom-ostrich-toggle').is(':checked'),
        stitching: $('#color-stitching').val(),
        stitching_secondary: $('#color-stitching2').val(),
        lining: $('#lining-leather').val(),
        lining_coverage: $('input[name="lining-coverage"]:checked').val(),
        debossing: $('input[name="deboss-choice"]:checked').val(),
        edge_style: $('#edge-style').val(),
        edge_paint_choice: $('input[name="edge-paint-choice"]:checked').val() || 'lining',
        edge_colour: $('#edge-style').val() === 'painted' ? $('#edge-colour').val() : '',
        metal_corners: $('#metal-corners').val(),
      };
    }

    function calculatePrice() {
      let total = pricingRules.basePrice;

      if ($('#bottom-ostrich-toggle').is(':checked')) {
        total += pricingRules.ostrichPremium;
      }

      if ($('input[name="lining-coverage"]:checked').val() === 'all') {
        total += pricingRules.liningAllPremium;
      }

      if ($('input[name="deboss-choice"]:checked').val() === 'atelier') {
        total += pricingRules.debossPremium;
      }

      if ($('#edge-style').val() === 'painted') {
        total += pricingRules.paintedEdgePremium;
        if ($('input[name="edge-paint-choice"]:checked').val() === 'custom') {
          total += pricingRules.customEdgePaintPremium;
        }
      }

      const metalChoice = $('#metal-corners').val();
      total += pricingRules.metalCorners[metalChoice] ?? 0;

      return total;
    }

    function syncWooCommerceFields() {
      const selections = gatherSelections();
      const total = calculatePrice();

      const optionsField = document.getElementById('wallet-options-field');
      const priceDisplay = document.getElementById('wallet-price-display');

      if (optionsField) optionsField.value = JSON.stringify(selections);
      if (priceDisplay) priceDisplay.textContent = `${currencySymbol}${total.toFixed(2)}`;
    }

      $(function () {
        const galleryModal = document.getElementById('galleryModal');
        const galleryModalImage = galleryModal?.querySelector('.modal-body img');
        const galleryModalTitle = galleryModal?.querySelector('.modal-title');

        $('.gallery-trigger').on('click', function () {
          const imageSrc = $(this).data('image-src');
          const imageAlt = $(this).data('image-alt') || 'Gallery image';
          if (galleryModalImage) {
            galleryModalImage.src = imageSrc;
            galleryModalImage.alt = imageAlt;
          }
          if (galleryModalTitle) {
            galleryModalTitle.textContent = imageAlt;
          }
        });

        rebuildLeatherSelects(currentLeatherCollection);
        updateLeatherDescription(currentLeatherCollection);

        $('#wallet-configurator-form').on('change input', 'select, input', syncWooCommerceFields);
        syncWooCommerceFields();

      populateSelectWithSwatches($('#lining-leather'), liningLeatherSwatches);

      const metalCornerImages = {
        none: '',
        brass: 'images/metal-corners/brass.webp',
        silver: 'images/metal-corners/silver.webp',
        'gunmetal-grey': 'images/metal-corners/grey.webp',
        black: 'images/metal-corners/black.webp',
        gold: 'images/metal-corners/gold.webp',
        'rose-gold': 'images/metal-corners/rosegold.webp',
      };

      $('#metal-corners option').each(function () {
        const value = $(this).val();
        const colorValue = value === 'none' ? '#f8f9fa' : value;
        $(this)
          .attr('data-color', colorValue)
          .attr('data-image', metalCornerImages[value] ?? '');
      });
      buildSwatchSelect($('#metal-corners'));

      $('#color-stitching, #color-stitching2').each(function () {
        buildSwatchSelect($(this));
      });

      buildSwatchSelect($('#edge-colour'));

      $('#edge-style').on('change', function () {
        const isPainted = $(this).val() === 'painted';
        $('#painted-edge-options').toggleClass('d-none', !isPainted);
        if (!isPainted) {
          $('#edge-colour-picker-wrapper').addClass('d-none');
          $('#edge-paint-lining').prop('checked', true);
        } else {
          $('input[name="edge-paint-choice"]:checked').trigger('change');
        }
      });

      $('input[name="edge-paint-choice"]').on('change', function () {
        const useCustom = $(this).val() === 'custom';
        $('#edge-colour-picker-wrapper').toggleClass('d-none', !useCustom);
      });

      $('#edge-style').trigger('change');

      $('#leather-collection').on('change', function () {
        currentLeatherCollection = $(this).val();
        rebuildLeatherSelects(currentLeatherCollection);
        updateLeatherDescription(currentLeatherCollection);
      });

      $('#bottom-ostrich-toggle').on('change', function () {
        useOstrichBottom = $(this).is(':checked');
        rebuildLeatherSelects(currentLeatherCollection);
      });

      bindSvgColoring();
      buildSvgOverlayButtons();
      $(window).on('resize', buildSvgOverlayButtons);
      syncWooCommerceFields();
    });
  </script>


<?php get_footer(); ?>
