<?php
global $product; ?>


<div class="woocommerce-variation-add-to-cart variations_button">
  <?php do_action('woocommerce_before_add_to_cart_button'); ?>
  <div class="add_to_cart_with_size">
    <div class="tamanhos">
      <div class="tamanhos_select">


        <div class="flex justify-start items-center w-full relative">
          <div
            class="variation-alert bg-red-500 rounded-3xl px-2 py-1 text-white min-w-[160px] uppercase text-xs font-roboto absolute -top-[32px] transition-all duration-200 ease-in-out opacity-0 z-10 pointer-events-none">
            ⚠️ Selecione um tamanho
          </div>
          <?php
          $available_variations = $product->get_available_variations();
          $tempArray = [];
          foreach ($available_variations as $variation) {
            foreach ($variation['attributes'] as $key => $attribute) {
              $terms = get_terms(array('taxonomy' => str_replace('attribute_', '', $key), 'hide_empty' => false));
              $attribute_terms[$key] = $terms;
            }
          }
          foreach ($available_variations as $variation) {
            foreach ($variation['attributes'] as $key => $attribute) {
              $found_key = array_search($attribute, array_column($attribute_terms[$key], 'slug'));
              $variation_obj = new WC_Product_variation($variation['variation_id']);
              $vo = new stdClass();
              $vo->id = $variation_obj->get_id();
              $vo->name = $attribute_terms[$key][$found_key]->name;
              $vo->stock = $variation_obj->get_stock_quantity();
              $vo->manage_stock = $variation_obj->get_manage_stock();
              $vo->stock_status = $variation_obj->get_stock_status();
              $vo->order = $found_key;
              array_push($tempArray, $vo);
            }
          }
          usort($tempArray, fn($a, $b) => strcmp($a->order, $b->order));
          ?>
          <div class="flex gap-1 items-center justify-start flex-wrap relative">
            <?php foreach ($tempArray as $variation) { ?>
              <button
                class="tamanho_option  <?php echo ($variation->stock > 0 || $variation->stock_status === "instock") ? 'btn-quad' : 'btn-quad-disabled' ?> "
                data-value="<?php echo $variation->id ?>" data-name="<?php echo $variation->name ?>"
                data-stock="<?php echo $variation->stock ?>">
                <?php echo $variation->name ?>
              </button>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="flex justify-start items-center mt-6">
    <a href="/guia-de-tamanhos"
      class="uppercase font-roboto text-xs text-gray-500 text-left underline cursor-pointer hover:text-gray-900 transition-all duration-200">
      Guia de Tamanhos
    </a>
  </div>
  <?php do_action('woocommerce_after_add_to_cart_button'); ?>
  <input type="hidden" name="add-to-cart" value="<?php echo absint($product->get_id()); ?>" />
  <input type="hidden" name="product_id" value="<?php echo absint($product->get_id()); ?>" />
  <input type="hidden" name="variation_id" class="variation_id" value="0" />
</div>