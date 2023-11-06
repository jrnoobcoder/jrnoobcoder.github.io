<?php

/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_Marketplace
 * @author    Webkul
 * @copyright Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
/** @var $block \Webkul\Marketplace\Block\Product\Create */

$viewModel = $block->getViewModel();
$helper = $viewModel->getHelper();
$product_hint_status = $helper->getProductHintStatus();
$currency_code = $helper->getCurrentCurrencyCode();
$currency_symbol = $helper->getCurrencySymbol();
$product_id = $block->getRequest()->getParam('id');
$product_coll = $block->getProduct($product_id);
$websiteIds = $product_coll->getWebsiteIds();
$data = $block->getPersistentData();
$attribute_set_id = $product_coll['attribute_set_id'];
if ($block->getRequest()->getParam('set')) {
    $attribute_set_id = $block->getRequest()->getParam('set');
}
$skuType = $helper->getSkuType();
$weightUnit = $helper->getWeightUnit();
$allowProductType = $block->getAllowedProductType();
?>
<div style="margin-top: 20px; margin-bottom: 20px; background-color: #ef7c0ab3; border: solid 1px #D3D3D3; padding: 10px 5px 10px 5px;">
	<h5 style="text-align: justify"><?= $escaper->escapeHtml(__('VERIFICA DI POSSESSO: ')) ?></br><br>

	  <?= $escaper->escapeHtml(__('Se sei un venditore privato devi dimostrare di possedere realmente l&rsquo;orologio in vendita.')) ?><br>
	  <?= $escaper->escapeHtml(__('Oltre alle foto da mostrare nell&rsquo;annuncio, scatta due foto facendo in modo che l&rsquo;orologio segni l&rsquo;ora indicata nell&rsquo;immagine di controllo qui sotto (oppure scrivi l&rsquo;ora indicata su un foglio e ponilo sotto l&rsquo;orologio in modo che si legga l&rsquo;ora scritta), carica tutte le immagini nella galleria come evidenziato. Una volta ricevuta la tua richiesta, la esamineremo e se sarà approvata, ti invieremo una email come conferma di avvenuta pubblicazione.')) ?>
	  </br><br>
	  <?= $escaper->escapeHtml(__('Se invece sei un venditore commerciante non devi seguire questo processo di verifica, perché hai già ottenuto la nostra certificazione al momento della tua iscrizione.')) ?>
 	  </h5>      
</div>
<form action="<?= $escaper->escapeUrl($block->getUrl('marketplace/product/save', ['_secure' => $block
                    ->getRequest()->isSecure()])) ?>" enctype="multipart/form-data" method="post" id="edit-product" data-form="edit-product" data-mage-init='{"validation":{}}'>
    <div class="wk-mp-design" id="wk-bodymain">
        <fieldset class="fieldset info wk-mp-fieldset">

            <div data-mage-init='{"formButtonAction": {}}' class="wk-mp-page-title legend">
                <span><?= $escaper->escapeHtml(__('Edit Product')) ?></span>
                <button class="button wk-mp-btn" style="background-color: #EF7C0A" title="<?= $escaper->escapeHtml(__('Richiedi pubblicazione')) ?>" type="submit" id="save-btn">
                    <span><span><?= $escaper->escapeHtml(__('Richiedi pubblicazione')) ?></span></span>
                </button>
                   <?php /*
                <button class="button wk-mp-btn" title="<?= $escaper->escapeHtml(__('Save & Duplicate')) ?>" type="button" id="wk-mp-save-duplicate-btn">
                    <span><span><?= $escaper->escapeHtml(__('Save & Duplicate')) ?></span></span>
                </button>    
				*/ ?>
            </div>
            <?= $block->getBlockHtml('formkey') ?>
            <?= $block->getBlockHtml('seller.formkey') ?>
            <input id="product_type_id" name="type" type="hidden" value="<?= /* @noEscape */ $product_coll['type_id'] ?>">
            <input type="hidden" name="id" value="<?= /* @noEscape */ $product_id; ?>" />
            <input type="hidden" id="product_id" name="product_id" value="<?= /* @noEscape */ $product_id; ?>" />

            <script>
                require(['jquery'], function($) {

                    var slug = function(str) {
                        var $slug = '';
                        var trimmed = $.trim(str);
                        $slug = trimmed.replace(/[^a-z0-9-]/gi, '-').
                        replace(/-+/g, '-').
                        replace(/^-|-$/g, '');
                        return $slug.toLowerCase();
                    }
                    $('input#name').on('change keyup', function() {
                        var pname = $('input#name').val();
                        var pid = $('#product_id').val();
                        // console.log(pname);
                        // console.log(pid);
                        var takedata = pname + '-' + pid;
                        var sluddata = $('input#url_key').val(slug(takedata));
                        $('input#url_key').val(slug(takedata));
                        $('input#sku').val(slug(takedata))


                    })
                });
            </script>
            <?php if (count($helper->getAllowedSets()) > 1) { ?>
                <div class="field required">
                    <label class="label"><?= $escaper->escapeHtml(__('Attribute Set')) ?>:</label>
                    <div class="control">
                        <select name="set" id="attribute-set-id" class="required-entry">
                            <?php foreach ($helper->getAllowedSets() as $set) { ?>
                                <option value="<?= /* @noEscape */ $set['value'] ?>" <?php if ($attribute_set_id == $set['value']) { ?> selected="selected" <?php } ?>>
                                    <?= /* @noEscape */ $set['label'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <?php
            } else {
                $allowedSets = $helper->getAllowedSets();
                if (!empty($allowedSets)) { ?>
                    <input type="hidden" name="set" id="attribute-set-id" value="<?= /* @noEscape */ $allowedSets[0]['value'] ?>" />
                <?php
                } else { ?>
                    <input type="hidden" name="set" id="attribute-set-id" value="<?= /* @noEscape */ $attribute_set_id ?>" />
            <?php
                }
            } ?>
  <?php /* .... HIDDEN ................................................................................................  */ ?>
            <div class="field" hidden="true">
                <label class="label"><?= $escaper->escapeHtml(__('Product Category')) ?>:</label>
                <?php
                if ($product_hint_status && $helper->getProductHintCategory()) { ?>
                    <img src="<?= $escaper->escapeUrl($block->getViewFileUrl('Webkul_Marketplace::images/quest.png')); ?>" class='questimg' title="<?= $escaper->escapeHtml($helper->getProductHintCategory()) ?>" />
                <?php
                } ?>

                <?php if ($helper->getIsAdminViewCategoryTree()) { ?>
                    <div data-bind="scope: 'sellerCategory'">
                        <!-- ko template: getTemplate() --><!-- /ko -->
                    </div>
                    <?php $categories = $product_coll->getCategoryIds(); ?>
                    <script type="text/x-magento-init">
                        {
                            "*": {
                                "Magento_Ui/js/core/app": {
                                    "components": {
                                        "sellerCategory": {
                                            "component": "Webkul_Marketplace/js/product/seller-category-tree",
                                            "template" : "Webkul_Marketplace/seller-category-tree",
                                            "filterOptions": true,
                                            "levelsVisibility": "1",
                                            "options": <?= /* @noEscape */ $block->getCategoriesTree() ?>,
                                            "value": <?= /* @noEscape */ json_encode($categories) ?>
                                        }
                                    }
                                }
                            }
                        }
                    </script>
                <?php } else { ?>
                    <div class="wk-field wk-category">
                        <div class="wk-for-validation">
                            <div id="wk-category-label"><?= $escaper->escapeHtml(__("CATEGORIES")); ?></div>
                            <?php
                            $categories = $product_coll->getCategoryIds();
                            $cat_ids = implode(",", $categories);
                            foreach ($categories as $value) {
                            ?>
                                <input type="hidden" name="product[category_ids][]" value="<?= /* @noEscape */ $value; ?>" id="wk-cat-hide<?= /* @noEscape */ $value; ?>" />
                            <?php
                            }
                            ?>
                            <?php
                            if ($helper->getAllowedCategoryIds()) {
                                $storeconfig_catids = explode(',', trim($helper->getAllowedCategoryIds()));
                                foreach ($storeconfig_catids as $storeconfig_catid) {
                                    $cat_model = $block->getCategory()->load($storeconfig_catid);
                                    if (isset($cat_model["entity_id"]) && $cat_model["entity_id"]) {
                            ?>
                                        <div class="wk-cat-container">
                                            </span><span class="wk-foldersign"></span>
                                            <span class="wk-elements wk-cat-name">
                                                <?= $escaper->escapeHtml($cat_model->getName()) ?></span>
                                            <?php
                                            if (in_array($cat_model["entity_id"], $categories)) { ?>
                                                <input class="wk-elements" type="checkbox" name="product[category_ids][]" value=<?= /* @noEscape */ $cat_model['entity_id'] ?> checked />
                                            <?php
                                            } else { ?>
                                                <input class="wk-elements" type="checkbox" name="product[category_ids][]" value='<?= /* @noEscape */ $cat_model['entity_id'] ?>' />
                                            <?php
                                            } ?>
                                        </div>
                                    <?php
                                    }
                                }
                            } else {
                                $count = 0;
                                $category_helper = $viewModel->getCategoryHelper();
                                $category_model = $block->getCategory();
                                $_categories = $category_helper->getStoreCategories();
                                foreach ($_categories as $_category) {
                                    $count++;
                                    if (count($category_model->getAllChildren($category_model
                                        ->load($_category['entity_id']))) - 1 > 0) { ?>
                                        <div class="wk-cat-container" style="margin-left:0px;">
                                            <span class="wk-plus">
                                            </span><span class="wk-foldersign"></span>
                                            <span class="wk-elements wk-cat-name">
                                                <?= $escaper->escapeHtml($_category->getName()) ?></span>
                                            <?php
                                            if (in_array($_category["entity_id"], $categories)) { ?>
                                                <input class="wk-elements" type="checkbox" name="product[category_ids][]" value=<?= $escaper->escapeHtml($_category['entity_id']) ?> checked />
                                            <?php
                                            } else { ?>
                                                <input class="wk-elements" type="checkbox" name="product[category_ids][]" value='<?= $escaper->escapeHtml($_category['entity_id']) ?>' />
                                            <?php
                                            } ?>
                                        </div>
                                    <?php
                                    } else { ?>
                                        <div class="wk-cat-container">
                                            </span><span class="wk-foldersign"></span>
                                            <span class="wk-elements wk-cat-name">
                                                <?= $escaper->escapeHtml($_category->getName()) ?></span>
                                            <?php
                                            if (in_array($_category["entity_id"], $categories)) { ?>
                                                <input class="wk-elements" type="checkbox" name="product[category_ids][]" value=<?= $escaper->escapeHtml($_category['entity_id']) ?> checked />
                                            <?php
                                            } else { ?>
                                                <input class="wk-elements" type="checkbox" name="product[category_ids][]" value='<?= $escaper->escapeHtml($_category['entity_id']) ?>' />
                                            <?php
                                            } ?>
                                        </div>
                            <?php
                                    }
                                }
                            } ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
 <?php /* .... HIDDEN ................................................................................................  */ ?>
	
    <?= $block->getChildHtml(); ?>	
	
	
            <div class="field required">
                <label class="label"><?= $escaper->escapeHtml(__('Product Name')) ?>:</label>
                <?php
                if ($product_hint_status && $helper->getProductHintName()) { ?>
                    <img src="<?= $escaper->escapeUrl($block
                                    ->getViewFileUrl('Webkul_Marketplace::images/quest.png')); ?>" class='questimg' title="<?= $escaper->escapeHtml($helper->getProductHintName()) ?>" />
                <?php
                } ?>
                <div class="control">
                    <input type="text" class="required-entry input-text" name="product[name]" id="name" value="<?= $escaper->escapeHtml($product_coll->getName()); ?>" />
                </div>
            </div>
	

            <?php
            $mpProStatus = 0;
            $mpProColl = $helper->getSellerProductDataByProductId($product_id);
            foreach ($mpProColl as $key => $value) {
                $mpProStatus = $value['status'];
            }
            if (!$helper->getIsProductEditApproval() && $mpProStatus == 1) { ?>
                <div class="field required">
                    <label class="label"><?= $escaper->escapeHtml(__('Status')) ?>:</label>
                    <?php
                    if ($product_hint_status && $helper->getProductHintEnable()) { ?>
                        <img src="<?= $escaper->escapeUrl($block
                                        ->getViewFileUrl('Webkul_Marketplace::images/quest.png')); ?>" class='questimg' title="<?= $escaper->escapeHtml($helper->getProductHintStatus()) ?>" />
                    <?php
                    } ?>
                    <div class="control">
                        <input type="radio" name="status" id="status1" value="1" <?php if ($product_coll->getStatus() == 1) {
                                                                                        echo 'checked="checked"';
                                                                                    } ?> /><?= $escaper->escapeHtml(__("Enable")); ?><br>
                        <input type="radio" name="status" id="status2" value="2" <?php if ($product_coll->getStatus() == 2) {
                                                                                        echo 'checked="checked"';
                                                                                    } ?> /><?= $escaper->escapeHtml(__("Disable")); ?>
                    </div>
                </div>
            <?php
            } ?>
            <?php
            if ($skuType == 'static') { ?>
                <div style="display:none;" class="field ">
                    <label class="label"><?= $escaper->escapeHtml(__('SKU')) ?>:</label>
                    <?php
                    if ($product_hint_status && $helper->getProductHintSku()) { ?>
                        <img src="<?= $escaper->escapeUrl($block
                                        ->getViewFileUrl('Webkul_Marketplace::images/quest.png')); ?>" class='questimg' title="<?= $escaper->escapeHtml($helper->getProductHintSku()) ?>" />
                    <?php
                    } ?>
                    <div class="control">
                        <input name="product[sku]" id="sku" class="validate-length maximum-length-64 input-text" type="text" value="<?= $escaper->escapeHtml($product_coll->getsku()); ?>" />
                    </div>
                    <div id="skuavail">
                        <span class="success-msg skuavailable"><?= $escaper->escapeHtml(__('SKU Available')) ?></span>
                    </div>
                    <div id="skunotavail">
                        <span class="error-msg skunotavailable">
                            <?= $escaper->escapeHtml(__('SKU Already Exist')) ?></span>
                    </div>
                </div>
            <?php
            } ?>
            <div class="field required <?php if ($product_coll['type_id'] == 'configurable') { ?> no-display <?php } ?>">
                <label class="label"><?= $escaper->escapeHtml(__('Price')) ?><b>
                        <?= /* @noEscape */ " (" . $currency_symbol . ")"; ?></b>:</label>
                <?php
                if ($product_hint_status && $helper->getProductHintPrice()) { ?>
                    <img src="<?= $escaper->escapeUrl($block
                                    ->getViewFileUrl('Webkul_Marketplace::images/quest.png')); ?>" class='questimg' title="<?= $escaper->escapeHtml($helper->getProductHintPrice()) ?>" />
                <?php
                } ?>
					 <?php  function RemoveSpecialChar($str) {
					$res1 = str_replace( '.', '.', $str);
					$res = str_replace(',', ',', $res1);
					return $res;
					}
					$unformatted = $block->getFormattedPriceWithoutSymbol($product_coll->getPrice());
					$formated = RemoveSpecialChar($unformatted);
					?>
                <div class="control">
                    <input type="text" class="required-entry validate-zero-or-greater input-text" name="product[price]" id="price" value="<?= /* @noEscape */ $formated  ?>" data-ui-id="product-tabs-attributes-tab-fieldset-element-text-product-price" />
                </div>
            </div>
            <?php if ($product_coll['type_id'] != 'configurable') { ?>
                <?php $specialPrice = $product_coll->getSpecialPrice() ? $block
                    ->getFormattedPriceWithoutSymbol($product_coll->getSpecialPrice()) : ''; ?>
                <div style="display:none" class="field">
                    <label class="label"><?= $escaper->escapeHtml(__('Special Price')) ?><b>
                            <?= /* @noEscape */ " (" . $currency_symbol . ")"; ?></b>:</label>
                    <?php
                    if ($product_hint_status && $helper->getProductHintSpecialPrice()) { ?>
                        <img src="<?= $escaper->escapeUrl($block
                                        ->getViewFileUrl('Webkul_Marketplace::images/quest.png')); ?>" class='questimg' title="<?= $escaper->escapeHtml($helper->getProductHintSpecialPrice()) ?>" />
                    <?php
                    } ?>
                    <div class="control">
                        <input type="text" class="widthinput input-text validate-zero-or-greater" name="product[special_price]" id="special-price" value="<?= /* @noEscape */ $specialPrice ?>" />
                    </div>
                </div>
                <div style="display:none" class="field">
                    <label class="label"><?= $escaper->escapeHtml(__('Special Price From')) ?>:</label>
                    <?php
                    if ($product_hint_status && $helper->getProductHintStartDate()) { ?>
                        <img src="<?= $escaper->escapeUrl($block
                                        ->getViewFileUrl('Webkul_Marketplace::images/quest.png')); ?>" class='questimg' title="<?= $escaper->escapeHtml($helper->getProductHintStartDate()) ?>" />
                    <?php
                    } ?>
                    <?php
                    if ($product_coll->getData('special_from_date')) {
                        $special_from_date = $block->formatDate($product_coll->getData('special_from_date'));
                    } else {
                        $special_from_date = '';
                    } ?>
                    <div class="control">
                        <input type="text" name="product[special_from_date]" id="special-from-date" class="input-text" value="<?= /* @noEscape */ $special_from_date; ?>" />
                    </div>
                </div>
                <div style="display:none" class="field">
                    <label class="label"><?= $escaper->escapeHtml(__('Special Price To')) ?>:</label>
                    <?php
                    if ($product_hint_status && $helper->getProductHintEndDate()) { ?>
                        <img src="<?= $escaper->escapeUrl($block
                                        ->getViewFileUrl('Webkul_Marketplace::images/quest.png')); ?>" class='questimg' title="<?= $escaper->escapeHtml($helper->getProductHintEndDate()) ?>" />
                    <?php
                    } ?>
                    <?php
                    if ($product_coll->getData('special_to_date')) {
                        $special_to_date = $block->formatDate($product_coll->getData('special_to_date'));
                    } else {
                        $special_to_date = '';
                    } ?>
                    <div class="control">
                        <input type="text" name="product[special_to_date]" id="special-to-date" class="input-text" value="<?= /* @noEscape */ $special_to_date; ?>" />
                    </div>
                </div>
            <?php
            } ?>
            <input id="inventory_manage_stock" type="hidden" name="product[stock_data][manage_stock]" value="1">
            <input type="hidden" value="1" name="product[stock_data][use_config_manage_stock]" id="inventory_use_config_manage_stock">
            <div style="display:none" class="field">
                <label class="label"><?= $escaper->escapeHtml(__('Stock')) ?>:</label>
                <?php
                if ($product_hint_status && $helper->getProductHintQty()) { ?>
                    <img src="<?= $escaper->escapeUrl($block
                                    ->getViewFileUrl('Webkul_Marketplace::images/quest.png')); ?>" class='questimg' title="<?= $escaper->escapeHtml($helper->getProductHintQty()) ?>" />
                <?php
                } ?>
                <div class="control">
                    <input type="text" class="required-entry validate-number input-text" name="product[quantity_and_stock_status][qty]" id="qty" value="<?= /* @noEscape */ $product_coll['quantity_and_stock_status']['qty'] ?>" />
                </div>
            </div>
            <div style="display:none" class="field ">
                <label class="label"><?= $escaper->escapeHtml(__('Stock Availability')) ?>:</label>
                <?php
                if ($product_hint_status && $helper->getProductHintStock()) { ?>
                    <img src="<?= $escaper->escapeUrl($block
                                    ->getViewFileUrl('Webkul_Marketplace::images/quest.png')); ?>" class='questimg' title="<?= $escaper->escapeHtml($helper->getProductHintStock()) ?>" />
                <?php
                } ?>
                <?php
                $is_in_stock = $product_coll['quantity_and_stock_status']['is_in_stock'];
                ?>
                <div class="control">
                    <select id="" class="select" name="product[quantity_and_stock_status][is_in_stock]">
                        <option <?php if ($is_in_stock == 1) {
                                    echo "selected";
                                } ?> value="1">
                            <?= $escaper->escapeHtml(__("In Stock")); ?></option>
                        <option <?php if ($is_in_stock == 0) {
                                    echo "selected";
                                } ?> value="0">
                            <?= $escaper->escapeHtml(__("Out of Stock")); ?></option>
                    </select>
                </div>
            </div>
            <div style="display:none" class="field ">
                <label class="label"><?= $escaper->escapeHtml(__('Visibility')) ?>:</label>
                <div class="control">
                    <select id="visibility" class=" required-entry required-entry select" name="product[visibility]">

                        <?php
                        $product_visibility = $helper->getVisibilityOptionArray();
                        foreach ($product_visibility as $key => $value) {
                        ?>
                            <option value="<?= $escaper->escapeHtml($key) ?>" <?php if ($key == $product_coll->getVisibility()) {
                                                                                    echo "selected='selected'";
                                                                                } ?>>
                                <?= $escaper->escapeHtml($value) ?></option>
                        <?php
                        } ?>
                    </select>
                </div>
            </div>




            <!-- --------------- accordian start ----------- -->
 
            <!-- < id="element"> -->
                <div data-role="collapsible" class="accordian_collap_stl">
                    <div data-role="trigger" class="accordian_stl">
                        <span><?= $escaper->escapeHtml(__('DATI GENERALI')) ?></span>
                    </div>
                </div>
                <div data-role="content" class="accordian_content_stl">

                    <div class="field field_stl">
                        <label class="label"><?= $escaper->escapeHtml(__('Brand')) ?>: </label><span style="color: #EF7C0A; font-weight: 600;"> *<?= $escaper->escapeHtml(__(' VALORE OBBLIGATORIO')) ?></span>
                        <div class="control">
                            <select id="brand" class=" required-entry required-entry select" name="product[brand]">


                                <?php
                                $product_brand = $helper->getBrandDropdownOptions();
                                foreach ($product_brand as $key => $value) {
                                ?>
                                    <option value="<?= $escaper->escapeHtml($key) ?>" <?php if ($key == $product_coll->getBrand()) {
                                                                                            echo "selected='selected'";
                                                                                        } ?>>
                                        <?= $escaper->escapeHtml($value) ?></option>
                                <?php
                                } ?>
                            </select>
                        </div>
                    </div>


                    <div class="field field_stl">
                        <label class="label"><?= $escaper->escapeHtml(__('Model')) ?>: </label><span style="color: #EF7C0A; font-weight: 600;"> *<?= $escaper->escapeHtml(__(' VALORE OBBLIGATORIO')) ?></span>
                        <div class="control">
                            <select id="brand_modello" class=" required-entry required-entry select" name="product[brand_modello]">


                                <?php
                                $product_attr = $helper->getAttrDropdownOptions('brand_modello');
                                foreach ($product_attr as $key => $value) {
                                ?>
                                    <option value="<?= $escaper->escapeHtml($key) ?>" <?php if ($key == $product_coll->getbrand_modello()) {
                                                                                            echo "selected='selected'";
                                                                                        } ?>>
                                        <?= $escaper->escapeHtml($value) ?></option>
                                <?php
                                } ?>
                            </select>
                        </div>
                    </div>


                    <div class="field field_stl">
                        <label class="label"><?= $escaper->escapeHtml(__('Model not present')) ?>:</label>
                        <div class="control">
                            <input name="product[modello_non_presente]" id="modello_non_presente" class="validate-length maximum-length-64 input-text" type="text" value="<?= $escaper->escapeHtml($product_coll->getModelloNonPresente()); ?>" />
                        </div>
                    </div>


                    <div class="field field_stl">
                        <label class="label"><?= $escaper->escapeHtml(__('Reference number')) ?>:</label>
                        <div class="control">
                            <div class="control">
                                <input name="product[numero_di_referenza]" id="numero_di_referenza" class="validate-length maximum-length-64 input-text" type="text" value="<?= $escaper->escapeHtml($product_coll->getNumeroDiReferenza()); ?>" />
                            </div>

                        </div>
                    </div>


            
<div class="field field_stl">
    <label class="label"><?= $escaper->escapeHtml(__('Country')) ?>: <span style="color: #EF7C0A; font-weight: 600;"> *<?= $escaper->escapeHtml(__(' VALORE OBBLIGATORIO')) ?></span></label>
	<div class="control">
                            <select id="bandiera" class=" required-entry required-entry select" name="product[bandiera]">

                                <?php
                                $product_attr = $helper->getAttrDropdownOptions('bandiera');
                                foreach ($product_attr as $key => $value) {
                                ?>
                                    <option value="<?= $escaper->escapeHtml($key) ?>" <?php if ($key == $product_coll->getBandiera()) {
                                                                                            echo "selected='selected'";
                                                                                        } ?>>
                                        <?= $escaper->escapeHtml($value) ?></option>
                                <?php
                                } ?>
                            </select>
                        </div>

</div>

   
<div class="field field_stl">
    <label class="label"><?= $escaper->escapeHtml(__('Gender')) ?>:</label>
    <div class="control">
        <select id="genere" class="select" name="product[genere]">
            <option value=""><?= $escaper->escapeHtml(__('Seleziona')) ?></option>
            <?php
            $product_attr = $helper->getAttrDropdownOptions('genere');
            foreach ($product_attr as $key => $value) {
                $selected = ($key == $product_coll->getGenere()) ? "selected='selected'" : "";
                ?>
                <option value="<?= $escaper->escapeHtml($key) ?>" <?= $selected ?>>
                    <?= $escaper->escapeHtml($value) ?></option>
            <?php
            } ?>
        </select>
    </div>
</div>




                    <div class="field field_stl">
                        <label class="label"><?= $escaper->escapeHtml(__('Year of production')) ?>:</label>
                        <div class="control">
                            <input name="product[anno_di_produzione]" id="anno_di_produzione" class="validate-length maximum-length-64 input-text" type="text" value="<?= $escaper->escapeHtml($product_coll->getAnnoDiProduzione()); ?>" />
                        </div>
                    </div>


                    <div class="field field_stl">
                        <label class="label"><?= $escaper->escapeHtml(__('Condition')) ?>:</label> <span style="color: #EF7C0A; font-weight: 600;"> *<?= $escaper->escapeHtml(__(' VALORE OBBLIGATORIO')) ?></span></label>
                        <div class="control">
                            <select id="condizione" class=" required-entry required-entry select" name="product[condizione]">

                                <?php
                                $product_attr = $helper->getAttrDropdownOptions('condizione');
                                foreach ($product_attr as $key => $value) {
                                ?>
                                    <option value="<?= $escaper->escapeHtml($key) ?>" <?php if ($key == $product_coll->getCondizione()) {
                                                                                            echo "selected='selected'";
                                                                                        } ?>>
                                        <?= $escaper->escapeHtml($value) ?></option>
                                <?php
                                } ?>
                            </select>
                        </div>
                    </div>



                    <div class="field field_stl">
                        <label class="label"><?= $escaper->escapeHtml(__('Extras')) ?>: </label> <span style="color: #EF7C0A; font-weight: 600;"> *<?= $escaper->escapeHtml(__(' VALORE OBBLIGATORIO')) ?></span></label>
                        <div class="control">
                            <select id="corredo" class=" required-entry required-entry select" name="product[corredo]">

                                <?php
                                $product_attr = $helper->getAttrDropdownOptions('corredo');
                                foreach ($product_attr as $key => $value) {
                                ?>
                                    <option value="<?= $escaper->escapeHtml($key) ?>" <?php if ($key == $product_coll->getCorredo()) {
                                                                                            echo "selected='selected'";
                                                                                        } ?>>
                                        <?= $escaper->escapeHtml($value) ?></option>
                                <?php
                                } ?>
                            </select>
                        </div>
                    </div>




<div class="field field_stl">
    <label class="label"><?= $escaper->escapeHtml(__('Winding')) ?>:</label>
    <div class="control">
        <select id="carica" class="select" name="product[carica]">
            <option value=""><?= $escaper->escapeHtml(__('Seleziona')) ?></option>
            <?php
            $product_attr = $helper->getAttrDropdownOptions('carica');
            foreach ($product_attr as $key => $value) {
                $selected = ($key == $product_coll->getCarica()) ? "selected='selected'" : "";
                ?>
                <option value="<?= $escaper->escapeHtml($key) ?>" <?= $selected ?>>
                    <?= $escaper->escapeHtml($value) ?></option>
            <?php
            } ?>
        </select>
    </div>
</div>


                    <div class="field field_stl">
                        <label class="label"><?= $escaper->escapeHtml(__('Watch diameter')) ?>:</label>
                        <div class="control">
                            <input name="product[diametro]" id="diametro" class="validate-length maximum-length-64 input-text" type="text" value="<?= $escaper->escapeHtml($product_coll->getDiametro()); ?>" />
                        </div>
                    </div>



            <!-- y/n -->

           <!-- y/n -->

           <div class="field field_stl" >     
               <label class="field-label" for="sponsorizzato"><span>
            <?= $escaper->escapeHtml(__('Sponsored'))?></span></label>                 					 
					<div class="field-control">
                         <div class="field-control sponsorizzato" data-role="sponsorizzato">
                             <div class="field-control-group">
                                 <div class="field field-option">
                                       <input type="radio" name="product[sponsorizzato]" value="1"
                                           class="control-radio"
                                            id="sponsorizzato1"


                              
                                          <?php if ($product_coll->getSponsorizzato()): ?>
                                             checked="checked"
                                                 <?php endif; ?>
                                                          >
                                               <label class="field-label" for="sponsorizzato">
                                                <span><?= $escaper->escapeHtml(__('Yes')) ?></span>
                                               </label>
                                     </div>
                                   <div class="field field-option">
                                  <input type="radio" name="product[sponsorizzato]" value="0"
                                           class="control-radio"
                                              id="sponsorizzato0"
                                                <?php if (!$product_coll->getSponsorizzato()): ?>
                                                  checked="checked"
                                                  <?php endif; ?>
                                                      >
                                                 <label class="field-label" for="sponsorizzato">
                                                    <span><?= $escaper->escapeHtml(__('No')) ?></span>
                                                 </label>
                                           </div>
                                   </div>
                           </div>
                     </div>
            </div>
                  <!-- y/n end -->

                </div>

                <div data-role="collapsible" class="accordian_collap_stl">
                    <div data-role="trigger" class="accordian_stl">
                        <span><?= $escaper->escapeHtml(__('CASSA')) ?></span>
                    </div>
                </div>
                <div data-role="content" class="accordian_content_stl">

<div class="field field_stl">
    <label class="label"><?= $escaper->escapeHtml(__('Case material')) ?>:</label>
    <div class="control">
        <select id="materiale_cassa" class="select" name="product[materiale_cassa]">
            <option value=""><?= $escaper->escapeHtml(__('Seleziona')) ?></option>
            <?php
            $product_attr = $helper->getAttrDropdownOptions('materiale_cassa');
            foreach ($product_attr as $key => $value) {
                $selected = ($key == $product_coll->getMaterialeCassa()) ? "selected='selected'" : "";
                ?>
                <option value="<?= $escaper->escapeHtml($key) ?>" <?= $selected ?>>
                    <?= $escaper->escapeHtml($value) ?></option>
            <?php
            } ?>
        </select>
    </div>
</div>

<div class="field field_stl">
    <label class="label"><?= $escaper->escapeHtml(__('Bezel material')) ?>:</label>
    <div class="control">
        <select id="materiale_lunetta" class="select" name="product[materiale_lunetta]">
            <option value=""><?= $escaper->escapeHtml(__('Seleziona')) ?></option>
            <?php
            $product_attr = $helper->getAttrDropdownOptions('materiale_lunetta');
            foreach ($product_attr as $key => $value) {
                $selected = ($key == $product_coll->getMateriale_lunetta()) ? "selected='selected'" : "";
                ?>
                <option value="<?= $escaper->escapeHtml($key) ?>" <?= $selected ?>>
                    <?= $escaper->escapeHtml($value) ?></option>
            <?php
            } ?>
        </select>
    </div>
</div>

<div class="field field_stl">
    <label class="label"><?= $escaper->escapeHtml(__('Glass')) ?>:</label>
    <div class="control">
        <select id="vetro" class="select" name="product[vetro]">
            <option value=""><?= $escaper->escapeHtml(__('Seleziona')) ?></option>
            <?php
            $product_attr = $helper->getAttrDropdownOptions('vetro');
            foreach ($product_attr as $key => $value) {
                $selected = ($key == $product_coll->getVetro()) ? "selected='selected'" : "";
                ?>
                <option value="<?= $escaper->escapeHtml($key) ?>" <?= $selected ?>>
                    <?= $escaper->escapeHtml($value) ?></option>
            <?php
            } ?>
        </select>
    </div>
</div>

<div class="field field_stl">
    <label class="label"><?= $escaper->escapeHtml(__('Waterproof')) ?>:</label>
    <div class="control">
        <select id="impermeabile" class="select" name="product[impermeabile]">
            <option value=""><?= $escaper->escapeHtml(__('Seleziona')) ?></option>
            <?php
            $product_attr = $helper->getAttrDropdownOptions('impermeabile');
            foreach ($product_attr as $key => $value) {
                $selected = ($key == $product_coll->getImpermeabile()) ? "selected='selected'" : "";
                ?>
                <option value="<?= $escaper->escapeHtml($key) ?>" <?= $selected ?>>
                    <?= $escaper->escapeHtml($value) ?></option>
            <?php
            } ?>
        </select>
    </div>
</div>
		

                    <div class="field field_stl">
                        <label class="label"><?= $escaper->escapeHtml(__('Additional settings')) ?>:</label> 
                <span style="color: #EF7C0A; font-weight: bold;"><?= $escaper->escapeHtml(__('Selezioni multiple Ctrl+click')) ?></span>
                        <div class="control">

                            <select id="altre_caratteristiche" class=" select" name="product[altre_caratteristiche][]" multiple>

                                <?php
                                $product_attr = $helper->getAttrDropdownOptions('altre_caratteristiche');
                                foreach ($product_attr as $key => $value) {
                                ?>
                                    <option value="<?= $escaper->escapeHtml($key) ?>" <?php $stringtoarray = "1,";
                                                                                        $stringtoarray .= $product_coll->getAltreCaratteristiche();
                                                                                        $selectedvalue = explode(",", $stringtoarray);
                                                                                        foreach ($selectedvalue as $selvalue) {
                                                                                            if ($key == $selvalue) {
                                                                                                echo "selected='selected'";
                                                                                            }
                                                                                        } ?>>
                                        <?= $escaper->escapeHtml($value) ?></option>
                                <?php
                                } ?>
                            </select>
                        </div>
                    </div>




                </div>

                <div data-role="collapsible" class="accordian_collap_stl">
                    <div data-role="trigger" class="accordian_stl">
                        <span><?= $escaper->escapeHtml(__('QUADRANTE e LANCETTE')) ?></span>
                    </div>
                </div>
                <div data-role="content" class="accordian_content_stl">


					

<div class="field field_stl">
    <label class="label"><?= $escaper->escapeHtml(__('Dial features')) ?>:</label>
    <div class="control">
        <select id="quadrante" class="select" name="product[quadrante]">
            <option value=""><?= $escaper->escapeHtml(__('Seleziona')) ?></option>
            <?php
            $product_attr = $helper->getAttrDropdownOptions('quadrante');
            foreach ($product_attr as $key => $value) {
                $selected = ($key == $product_coll->getQuadrante()) ? "selected='selected'" : "";
                ?>
                <option value="<?= $escaper->escapeHtml($key) ?>" <?= $selected ?>>
                    <?= $escaper->escapeHtml($value) ?></option>
            <?php
            } ?>
        </select>
    </div>
</div>

				

<div class="field field_stl">
    <label class="label"><?= $escaper->escapeHtml(__('Index or numerals on the dial')) ?>:</label>
    <div class="control">
        <select id="indici_su_quadrante" class="select" name="product[indici_su_quadrante]">
            <option value=""><?= $escaper->escapeHtml(__('Seleziona')) ?></option>
            <?php
            $product_attr = $helper->getAttrDropdownOptions('indici_su_quadrante');
            foreach ($product_attr as $key => $value) {
                $selected = ($key == $product_coll->getIndici_su_quadrante()) ? "selected='selected'" : "";
                ?>
                <option value="<?= $escaper->escapeHtml($key) ?>" <?= $selected ?>>
                    <?= $escaper->escapeHtml($value) ?></option>
            <?php
            } ?>
        </select>
    </div>
</div>
				

<div class="field field_stl">
    <label class="label"><?= $escaper->escapeHtml(__('Dial color')) ?>:</label>
    <div class="control">
        <select id="colore_quadrante" class="select" name="product[colore_quadrante]">
            <option value=""><?= $escaper->escapeHtml(__('Seleziona')) ?></option>
            <?php
            $product_attr = $helper->getAttrDropdownOptions('colore_quadrante');
            foreach ($product_attr as $key => $value) {
                $selected = ($key == $product_coll->getColore_quadrante()) ? "selected='selected'" : "";
                ?>
                <option value="<?= $escaper->escapeHtml($key) ?>" <?= $selected ?>>
                    <?= $escaper->escapeHtml($value) ?></option>
            <?php
            } ?>
        </select>
    </div>
</div>
				

<div class="field field_stl">
    <label class="label"><?= $escaper->escapeHtml(__('Hand features')) ?>:</label>
    <div class="control">
        <select id="caratteristiche_lancette" class="select" name="product[caratteristiche_lancette]">
            <option value=""><?= $escaper->escapeHtml(__('Seleziona')) ?></option>
            <?php
            $product_attr = $helper->getAttrDropdownOptions('caratteristiche_lancette');
            foreach ($product_attr as $key => $value) {
                $selected = ($key == $product_coll->getCaratteristiche_lancette()) ? "selected='selected'" : "";
                ?>
                <option value="<?= $escaper->escapeHtml($key) ?>" <?= $selected ?>>
                    <?= $escaper->escapeHtml($value) ?></option>
            <?php
            } ?>
        </select>
    </div>
</div>



                </div>

                <div data-role="collapsible" class="accordian_collap_stl">
                    <div data-role="trigger" class="accordian_stl">
                        <span><?= $escaper->escapeHtml(__('CINTURINO')) ?></span>
                    </div>
                </div>
                <div data-role="content" class="accordian_content_stl">
			

<div class="field field_stl">
    <label class="label"><?= $escaper->escapeHtml(__('Strap/Bracelet material')) ?>:</label>
    <div class="control">
        <select id="materiale_cinturino" class="select" name="product[materiale_cinturino]">
            <option value=""><?= $escaper->escapeHtml(__('Seleziona')) ?></option>
            <?php
            $product_attr = $helper->getAttrDropdownOptions('materiale_cinturino');
            foreach ($product_attr as $key => $value) {
                $selected = ($key == $product_coll->getMaterialeCinturino()) ? "selected='selected'" : "";
                ?>
                <option value="<?= $escaper->escapeHtml($key) ?>" <?= $selected ?>>
                    <?= $escaper->escapeHtml($value) ?></option>
            <?php
            } ?>
        </select>
    </div>
</div>
			

<div class="field field_stl">
    <label class="label"><?= $escaper->escapeHtml(__('Buckle')) ?>:</label>
    <div class="control">
        <select id="fibia" class="select" name="product[fibia]">
            <option value=""><?= $escaper->escapeHtml(__('Seleziona')) ?></option>
            <?php
            $product_attr = $helper->getAttrDropdownOptions('fibia');
            foreach ($product_attr as $key => $value) {
                $selected = ($key == $product_coll->getFibia()) ? "selected='selected'" : "";
                ?>
                <option value="<?= $escaper->escapeHtml($key) ?>" <?= $selected ?>>
                    <?= $escaper->escapeHtml($value) ?></option>
            <?php
            } ?>
        </select>
    </div>
</div>
			

<div class="field field_stl">
    <label class="label"><?= $escaper->escapeHtml(__('Strap color')) ?>:</label>
    <div class="control">
        <select id="colore_cinturino" class="select" name="product[colore_cinturino]">
            <option value=""><?= $escaper->escapeHtml(__('Seleziona')) ?></option>
            <?php
            $product_attr = $helper->getAttrDropdownOptions('colore_cinturino');
            foreach ($product_attr as $key => $value) {
                $selected = ($key == $product_coll->getColoreCinturino()) ? "selected='selected'" : "";
                ?>
                <option value="<?= $escaper->escapeHtml($key) ?>" <?= $selected ?>>
                    <?= $escaper->escapeHtml($value) ?></option>
            <?php
            } ?>
        </select>
    </div>
</div>
			

<div class="field field_stl">
    <label class="label"><?= $escaper->escapeHtml(__('Bracelet clasp')) ?>:</label>
    <div class="control">
        <select id="chiusura_cinturino" class="select" name="product[chiusura_cinturino]">
            <option value=""><?= $escaper->escapeHtml(__('Seleziona')) ?></option>
            <?php
            $product_attr = $helper->getAttrDropdownOptions('chiusura_cinturino');
            foreach ($product_attr as $key => $value) {
                $selected = ($key == $product_coll->getchiusura_cinturino()) ? "selected='selected'" : "";
                ?>
                <option value="<?= $escaper->escapeHtml($key) ?>" <?= $selected ?>>
                    <?= $escaper->escapeHtml($value) ?></option>
            <?php
            } ?>
        </select>
    </div>
</div>


                </div>





                <div data-role="collapsible" class="accordian_collap_stl">
                    <div data-role="trigger" class="accordian_stl">
                        <span><?= $escaper->escapeHtml(__('FUNZIONI')) ?></span>
                    </div>
                </div>
                <div data-role="content" class="accordian_content_stl">

                    <div class="field field_stl">
                        <label class="label"><?= $escaper->escapeHtml(__('Complications')) ?>:</label> 
                <span style="color: #EF7C0A; font-weight: bold;"><?= $escaper->escapeHtml(__('Selezioni multiple Ctrl+click')) ?></span>
                        <div class="control">
                            <select id="funzioni" class="select" name="product[funzioni][]" multiple>

                                <?php
                                $product_attr = $helper->getAttrDropdownOptions('funzioni');
                                foreach ($product_attr as $key => $value) {
                                ?>
                                    <option value="<?= $escaper->escapeHtml($key) ?>" <?php $stringtoarray = "1,";
                                                                                        $stringtoarray .= $product_coll->getFunzioni();
                                                                                        $selectedvalue = explode(",", $stringtoarray);
                                                                                        foreach ($selectedvalue as $selvalue) {
                                                                                            if ($key == $selvalue) {
                                                                                                echo "selected='selected'";
                                                                                            }
                                                                                        } ?>>
                                        <?= $escaper->escapeHtml($value) ?></option>
                                <?php
                                } ?>
                            </select>
                        </div>
                    </div>


                </div>


    </div>

    <script>
        require([
            'jquery',
            'accordion'
        ], function($) {
            $("#element").accordion();
        });
    </script>


    <!-- --------------- accordian end ----------- -->

            <div class="field required">
                <label class="label"><?= $escaper->escapeHtml(__('Description')) ?>:</label>
                <?php
                if ($product_hint_status && $helper->getProductHintDesc()) { ?>
                    <img src="<?= $escaper->escapeUrl($block
                                    ->getViewFileUrl('Webkul_Marketplace::images/quest.png')); ?>" class='questimg' title="<?= $escaper->escapeHtml($helper->getProductHintDesc()) ?>" />
                <?php
                } ?>
                <div class="control wk-border-box-sizing">
                    <textarea name="product[description]" class="required-entry input-text" id="description" rows="5" cols="75"><?= /* @noEscape */ $product_coll->getDescription(); ?></textarea>
                    <?php if ($helper->isWysiwygEnabled()) : ?>
                        <script>
                            require([
                                "jquery",
                                "mage/translate",
                                "mage/adminhtml/events",
                                "mage/adminhtml/wysiwyg/tiny_mce/setup"
                            ], function(jQuery) {
                                wysiwygDescription = new wysiwygSetup("description", {
                                    "width": "100%",
                                    "height": "200px",
                                    "plugins": [{
                                        "name": "image"
                                    }],
                                    "tinymce": {
                                        "toolbar": "formatselect | bold italic underline | " +
                                            "alignleft aligncenter alignright |" +
                                            "bullist numlist |" +
                                            "link table charmap",
                                        "plugins": "advlist " +
                                            "autolink lists link charmap media noneditable table " +
                                            "contextmenu paste code help table",
                                    },
                                    files_browser_window_url: "<?= /* @noEscape */ $block->getWysiwygUrl(); ?>"
                                });
                                wysiwygDescription.setup("exact");
                            });
                        </script>
                    <?php endif; ?>
                </div>
            </div>
            <div class="field">
                <label class="label"><?= $escaper->escapeHtml(__('Short Description')) ?>:</label>
                <?php
                if ($product_hint_status && $helper->getProductHintShortDesc()) { ?>
                    <img src="<?= $escaper->escapeUrl($block
                                    ->getViewFileUrl('Webkul_Marketplace::images/quest.png')); ?>" class='questimg' title="<?= /* @noEscape */ $helper->getProductHintShortDesc() ?>" />
                <?php
                } ?>
                <div class="control wk-border-box-sizing">
                    <textarea name="product[short_description]" class="input-text" id="short_description" rows="5" cols="75"><?= /* @noEscape */ $product_coll->getShortDescription(); ?></textarea>
                    <?php if ($helper->isWysiwygEnabled()) : ?>
                        <script>
                            require([
                                "jquery",
                                "mage/translate",
                                "mage/adminhtml/events",
                                "mage/adminhtml/wysiwyg/tiny_mce/setup"
                            ], function(jQuery) {
                                wysiwygShortDescription = new wysiwygSetup("short_description", {
                                    "width": "100%",
                                    "height": "200px",
                                    "plugins": [{
                                        "name": "image"
                                    }],
                                    "tinymce": {
                                        "toolbar": "formatselect | bold italic underline | " +
                                            "alignleft aligncenter alignright |" +
                                            "bullist numlist |" +
                                            "link table charmap",
                                        "plugins": "advlist " +
                                            "autolink lists link charmap media noneditable table " +
                                            "contextmenu paste code help table",
                                    },
                                    files_browser_window_url: "<?= /* @noEscape */ $block->getWysiwygUrl(); ?>"
                                });
                                wysiwygShortDescription.setup("exact");
                            });
                        </script>
                    <?php endif; ?>
                </div>
            </div>

  <?php /* .... HIDDEN ................................................................................................  */ ?>
    <div class="field required" hidden="true">
        <label class="label"><?= $escaper->escapeHtml(__('Tax Class')) ?>:</label>
        <?php
        if ($product_hint_status && $helper->getProductHintTax()) { ?>
            <img src="<?= $escaper->escapeUrl($block
                            ->getViewFileUrl('Webkul_Marketplace::images/quest.png')); ?>" class='questimg' title="<?= $escaper->escapeHtml($helper->getProductHintTax()) ?>" />
        <?php
        } ?>
        <div class="control">
            <select id="tax-class-id" class=" required-entry required-entry select" name="product[tax_class_id]">
                <option value="0"><?= $escaper->escapeHtml(__('None')) ?></option>
                <?php
                $taxid = $product_coll->getData('tax_class_id');
                $taxes = $helper->getTaxClassModel();
                foreach ($taxes as $tax) {
                ?>
                    <option <?= $taxid == $tax->getId() ? 'selected' : ''; ?> value="<?= $escaper->escapeHtml($tax->getId()) ?>">
                        <?= $escaper->escapeHtml($tax->getClassName()) ?></option>
                <?php
                } ?>
            </select>
        </div>
    </div>
  <?php /* .... HIDDEN ................................................................................................  */ ?>	
    <div style="display:none" class="field ">
        <label class="label"><?= $escaper->escapeHtml(__('Weight')) ?> (<?= $escaper
                                                                            ->escapeHtml($weightUnit) ?>):</label>
        <?php
        if ($product_hint_status && $helper->getProductHintWeight()) { ?>
            <img src="<?= $escaper->escapeUrl($block
                            ->getViewFileUrl('Webkul_Marketplace::images/quest.png')); ?>" class='questimg' title="<?= $escaper->escapeHtml($helper->getProductHintWeight()) ?>" />
        <?php
        } ?>
        <div data-role="weight-switcher">
            <label data-ui-id="product-tabs-attributes-tab-element-radios-product-product-has-weight-label" for="weight-switcher">
                <span><?= $escaper->escapeHtml(__('Does this have a weight?')) ?></span>
            </label>
            <div class="control">
                <div class="control">
                    <input type="radio" <?php if (
                                            $product_coll['type_id'] == 'simple' ||
                                            ($product_coll['type_id'] == 'configurable' && !empty($product_coll['weight']))
                                        ) { ?> checked="checked" <?php } ?> class="weight-switcher" id="weight-switcher1" value="1" name="product[product_has_weight]">
                    <label for="weight-switcher1">
                        <span><?= $escaper->escapeHtml(__('Yes')) ?></span>
                    </label>
                </div>
                <div class="control">
                    <input type="radio" <?php if (
                                            $product_coll['type_id'] == 'downloadable' ||
                                            $product_coll['type_id'] == 'virtual' || ($product_coll['type_id'] == 'configurable'
                                                && empty($product_coll['weight']))
                                        ) { ?>checked="checked" <?php } ?> class="weight-switcher" id="weight-switcher0" value="0" name="product[product_has_weight]">
                    <label for="weight-switcher0">
                        <span><?= $escaper->escapeHtml(__('No')) ?></span>
                    </label>
                </div>
            </div>
        </div>
        <div class="control">
            <?php $weight = $product_coll->getWeight(); ?>
            <input type="text" class="validate-zero-or-greater input-text" name="product[weight]" id="weight" value="<?= $escaper->escapeHtml($weight); ?>" <?php if ($product_coll['type_id'] == 'downloadable' || $product_coll['type_id'] == 'virtual') {
                                                                                                                                                            ?>disabled="disabled" <?php } ?> />
        </div>
    </div>

    <div style="display:none;" class="field">
        <label class="label"><?= $escaper->escapeHtml(__('Url Key')) ?>:</label>
        <div class="control">
            <input type="text" class="input-text" name="product[url_key]" id="url_key" value="<?= $escaper->escapeHtml($product_coll['url_key']) ?>" />
        </div>
    </div>
    <?php if (!$helper->getCustomerSharePerWebsite()) : ?>
        <div class="field required" hidden="true">
            <label class="label"><?= $escaper->escapeHtml(__('Product in Websites')) ?>:</label>
            <div class="control">
                <select id="websites" class="required-entry select" name="product[website_ids][]" multiple>
                    <?php $websites = $helper->getAllWebsites(); ?>
                    <?php foreach ($websites as $website) : ?>
                        <option value="<?= /* @noEscape */ $website->getWebsiteId() ?>" <?= in_array($website->getWebsiteId(), $websiteIds) ? 'selected' : ''; ?>>
                            <?=  /* @noEscape */ $website->getName() ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    <?php endif; ?>
	
<?php /* .... HIDDEN ................................................................................................  */ ?>		
    <div class="field" hidden="true">
        <label class="label"><?= $escaper->escapeHtml(__('Meta Title')) ?>:</label>
        <div class="control">
            <input type="text" class="input-text" name="product[meta_title]" id="meta_title" value="<?= $escaper->escapeHtml($product_coll['meta_title']) ?>" />
        </div>
    </div>
    <div class="field" hidden="true">
        <label class="label"><?= $escaper->escapeHtml(__('Meta Keywords')) ?>:</label>
        <div class="control">
            <textarea class="textarea" id="meta_keyword" name="product[meta_keyword]"><?= $escaper->escapeHtml($product_coll['meta_keyword']) ?></textarea>
        </div>
    </div>
    <div class="field" hidden="true">
        <label class="label"><?= $escaper->escapeHtml(__('Meta Description')) ?>:</label>
        <div class="control">
            <textarea class="textarea" id="meta_description" name="product[meta_description]">
                        <?= $escaper->escapeHtml($product_coll['meta_description']) ?></textarea>
        </div>
    </div>
<?php /* .... HIDDEN ................................................................................................	
	
    <?= $block->getChildHtml(); ?>  */ ?>

    </fieldset>
    </div>
</form>
<?php
$formData = [
    'productTypeId' => $product_coll['type_id'],
    'categories' => implode(',', $categories),
    'countryPicSelector' => '#country-pic',
    'verifySkuAjaxUrl' => $block->getUrl('marketplace/product/verifysku', ['_secure' => $block
        ->getRequest()->isSecure()]),
    'productid'  => $product_id,
    'categoryTreeAjaxUrl' => $block->getUrl('marketplace/product/categorytree/', ['_secure' => $block
        ->getRequest()->isSecure()])
];
$serializedFormData = $viewModel->getJsonHelper()->jsonEncode($formData);
?>

<script type="text/x-magento-init">
    {
        "*": {
            "sellerEditProduct": <?= /* @noEscape */ $serializedFormData; ?>
        }
    }
</script>
<script type='text/javascript'>
    require(['jquery', 'prototype', 'domReady!'], function($) {
        var qty = $('#qty'),
            productType = $('#product_type_id').val(),
            stockAvailabilityField = $('#quantity_and_stock_status'),
            manageStockField = $('#inventory_manage_stock'),
            useConfigManageStockField = $('#inventory_use_config_manage_stock'),
            fieldsAssociations = {
                'qty': 'inventory_qty',
                'quantity_and_stock_status': 'inventory_stock_availability'
            };

        var qtyDefaultValue = qty.val();
    })
</script>
<script>
    require([
        "jquery",
        "Webkul_Marketplace/catalog/type-events"
    ], function($, TypeSwitcher) {
        var $form = $('[data-form=edit-product]');
        $form.data('typeSwitcher', TypeSwitcher.init());
    });
</script>
<script type="text/x-magento-init">
    {
        "*": {
            "Webkul_Marketplace/js/product/weight-handler": {},
            "Webkul_Marketplace/catalog/apply-to-type-switcher": {}
        }
    }
</script>