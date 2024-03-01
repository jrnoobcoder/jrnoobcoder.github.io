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
$set = $block->getRequest()->getParam('set');
$type = $block->getRequest()->getParam('type');
$skuType = $helper->getSkuType();
$skuPrefix = $helper->getSkuPrefix();
$data = $block->getPersistentData();
$weightUnit = $helper->getWeightUnit();
$allowProductType =$block->getAllowedProductType();

if (!empty($data['set'])) {   
    $set = $data['set'];
}
?>
<div style="margin-top: 20px; margin-bottom: 20px; background-color: #f88c00a3; border: solid 1px #D3D3D3; padding: 10px 5px 10px 5px;">
	<h5 style="text-align: justify"><?= $escaper->escapeHtml(__('Compila tutti i campi in modo completo e dettagliato.')) ?><br>
<?= $escaper->escapeHtml(__('Una buona descrizione deve  contenere solo i dati che riguardano l’orologio messo in vendita')) ?> <?= $escaper->escapeHtml(__('e risponda alle domande dei potenziali acquirenti invogliandoli a scegliere il tuo annuncio.')) ?><br>
<?= $escaper->escapeHtml(__('Includi anche delle informazioni aggiuntive dettagliate sullo stato attuale dell’orologio (danni, riparazioni, sostituzione di pezzi originali).')) ?></h5>      
</div>

<form action="<?= $escaper->escapeUrl($block
->getUrl('marketplace/product/save', ['_secure' => $block->getRequest()->isSecure()])) ?>"
 enctype="multipart/form-data" method="post" id="edit-product" data-form="edit-product"
  data-mage-init='{"validation":{}}'>
    <div class="wk-mp-design" id="wk-bodymain">
        <fieldset class="fieldset info wk-mp-fieldset">
            <div data-mage-init='{"formButtonAction": {}}' class="wk-mp-page-title legend">
                <span><?= $escaper->escapeHtml(__('Add Product')) ?></span>
                <button
                 type="submit" class="button wk-mp-btn" id="save-btn" style="background-color: #EF7C0A" title="<?= $escaper->escapeHtml(__('Salva caratteristiche e continua')) ?>">
                    <span><span><?= $escaper->escapeHtml(__('Salva caratteristiche e continua')) ?></span></span>
                </button>

           <?php /*				
                <button class="button wk-mp-btn" 
                title="<?= $escaper->escapeHtml(__('Save & Duplicate')) ?>" 
                type="button" id="wk-mp-save-duplicate-btn">
                    <span><span><?= $escaper->escapeHtml(__('Save & Duplicate')) ?></span></span>
                </button>    
			*/ ?>
				<?php 
		$yourLastAddedId =$helper->getIncrementdIdByProduct();
	?>
		
		<input type="hidden" id="incrementvalue" name="product-Id" value="<?= $yourLastAddedId ?>">
            
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
			$('input#name').on('change keyup',function () {
                var pname = $('input#name').val();
                var pid = $('#incrementvalue').val();
				// console.log(pname);
				// console.log(pid);
                var takedata = pname+'-'+pid;
				var sluddata =$('input#url_key').val(slug(takedata));
			   $('input#url_key').val(slug(takedata));
			   $('input#sku').val(slug(takedata))

          
			})
        });
</script>
</div>
            <?= $block->getBlockHtml('formkey')?>
            <?= $block->getBlockHtml('seller.formkey')?>
            <input id="product_type_id" name="type" type="hidden"
             value="<?= $escaper->escapeHtml($type)?>" value="<?= $escaper->escapeHtml($data['type'])?>">
            <?php if (count($helper->getAllowedSets()) > 1): ?>
                <div class="field required">
                    <label class="label"><?= $escaper->escapeHtml(__('Attribute Set')) ?>:</label>
                    <div class="control">
                        <select name="set" id="attribute-set-id" class="required-entry">
                        <?php foreach ($helper->getAllowedSets() as $setval): ?>
                            <option value="<?= $escaper->escapeHtml($setval['value']) ?>"
                             <?php if ($set==$setval['value']) { ?> selected="selected" <?php } ?>>
                                <?= $escaper->escapeHtml($setval['label'])?>
                            </option>
                        <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            <?php else: ?>
                <?php $allowedSets = $helper->getAllowedSets(); ?>
                <input type="hidden" name="set" id="attribute-set-id" 
                value="<?= $escaper->escapeHtml($allowedSets[0]['value']) ?>" />
            <?php endif; ?>

  <?php /* .... HIDDEN ................................................................................................  */ ?>
            <div class="field" hidden="true">
			<input type="file" value="image.png" name="image">
                <label class="label"><?= $escaper->escapeHtml(__('Product Category')) ?>:</label>
                <?php if ($product_hint_status && $helper->getProductHintCategory()): ?>
                    <img src="<?= $escaper->escapeUrl($block
                    ->getViewFileUrl('Webkul_Marketplace::images/quest.png')); ?>"
                     class='questimg' title="<?= $escaper->escapeHtml($helper->getProductHintCategory()) ?>"/>
                <?php endif; ?>
                <?php if ($helper->getIsAdminViewCategoryTree()) { ?>
                    <div data-bind="scope: 'sellerCategory'">
                        <!-- ko template: getTemplate() --><!-- /ko -->
                    </div>
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
                                            "options": <?= /* @noEscape */ $block->getCategoriesTree()?>,
                                            "value": <?= /* @noEscape */ json_encode($data['product']['category_ids'])?>
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
                            $categories = $data['product']['category_ids'];
                            foreach ($categories as $value) { ?>
                                <input type="hidden" name="product[category_ids][]" 
                                value="<?= $escaper->escapeHtml($value); ?>"
                                 id="wk-cat-hide<?= $escaper->escapeHtml($value); ?>"/>
                            <?php } ?>
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
                                                <?= $escaper->escapeHtml($cat_model->getName()) ?>
                                            </span>
                                            <?php
                                            if (in_array($cat_model["entity_id"], $categories)) {?>
                                                <input class="wk-elements" type="checkbox" 
                                                name="product[category_ids][]" 
                                                value=<?= $escaper->escapeHtml($cat_model['entity_id']) ?> checked />
                                                <?php
                                            } else { ?>
                                                <input class="wk-elements" type="checkbox" 
                                                name="product[category_ids][]" 
                                                value='<?= $escaper->escapeHtml($cat_model['entity_id']) ?>'/>
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
                                    ->load($_category['entity_id'])))-1 > 0) { ?>
                                        <div class="wk-cat-container" style="margin-left:0px;">
                                            <span class="wk-plus">
                                            </span><span class="wk-foldersign"></span>
                                            <span class="wk-elements wk-cat-name">
                                                <?= $escaper->escapeHtml($_category->getName()) ?>
                                            </span>
                                            <?php
                                            if (in_array($_category["entity_id"], $categories)) {?>
                                                <input class="wk-elements" type="checkbox" 
                                                name="product[category_ids][]"
                                                 value=<?= $escaper->escapeHtml($_category['entity_id']) ?> checked />
                                                <?php
                                            } else { ?>
                                                <input class="wk-elements" type="checkbox" 
                                                name="product[category_ids][]" 
                                                value='<?= $escaper->escapeHtml($_category['entity_id']) ?>'/>
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
                                            if (in_array($_category["entity_id"], $categories)) {?>
                                                <input class="wk-elements" type="checkbox" 
                                                name="product[category_ids][]" 
                                                value=<?= $escaper->escapeHtml($_category['entity_id']) ?> checked />
                                                <?php
                                            } else { ?>
                                                <input class="wk-elements" type="checkbox"
                                                 name="product[category_ids][]" 
                                                 value='<?= $escaper->escapeHtml($_category['entity_id']) ?>'/>
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
         
<div class="field field_stl">
                <label class="label"><?= $escaper->escapeHtml(__('product_label_image')) ?>:</label>
                  <div class="control"> 
                     <input name="product[product_label_image]" id="product_label_image" 
                        class="validate-length maximum-length-64 input-text" type="file" 
                        value="image.png"/>               
  
			      </div> 
			   
			   
            </div>    </div>
  <?php /* .... HIDDEN ................................................................................................  */ ?>	
            <div class="field required">
                <label class="label"><?= $escaper->escapeHtml(__('Product Name')) ?>: </label><span style="color: #EF7C0A; font-weight: 600;"><?= $escaper->escapeHtml(__(' VALORE OBBLIGATORIO')) ?></span>
                <?php
                if ($product_hint_status && $helper->getProductHintName()) {?>
                    <img src="<?= $escaper->escapeUrl($block
                    ->getViewFileUrl('Webkul_Marketplace::images/quest.png')); ?>"
                     class='questimg' title="<?= $escaper->escapeHtml($helper->getProductHintName()) ?>"/>
                    <?php
                } ?>
                <div class="control">
                    <input type="text" class="required-entry input-text" name="product[name]"
                     id="name" value="<?= $escaper->escapeHtml($data['product']['name'])?>"/>
                </div>
            </div>

            <?php if ($skuType == 'static'): ?>
                <div style="display:none;" class="field">
                    <label class="label"><?= $escaper->escapeHtml(__('SKU')) ?>:</label>
                    <?php
                    if ($skuPrefix && $skuType == 'dynamic') {
                        /* @noEscape */ echo "(Prefix - ".$skuPrefix.")";
                    }
                    ?>
                    <?php if ($product_hint_status && $helper->getProductHintSku()): ?>
                        <img src="<?= $escaper->escapeUrl($block
                        ->getViewFileUrl('Webkul_Marketplace::images/quest.png')); ?>" class='questimg' 
                        title="<?= $escaper->escapeHtml($helper->getProductHintSku()) ?>"/>
                    <?php endif; ?>
                    <div class="control">
                        <input name="product[sku]" id="sku" 
                        class="validate-length maximum-length-64 input-text" type="text" 
                        value="<?= $escaper->escapeHtml($data['product']['sku'])?>"/>
                    </div>
                    <div id="skuavail" >
                        <span class="success-msg skuavailable"><?= $escaper->escapeHtml(__('SKU Available')) ?></span>
                    </div>
                    <div id="skunotavail" >
                        <span class="error-msg skunotavailable"> 
                            <?= $escaper->escapeHtml(__('SKU Already Exist')) ?></span>
                    </div>
                </div>
            <?php endif; ?>
	
            <div class="field required">
                <label class="label"><?= $escaper->escapeHtml(__('Price')) ?><b>
                    <?= /* @noEscape */ " (".$currency_symbol.")"; ?></b>: </label><span style="color: #EF7C0A; font-weight: 600;"><?= $escaper->escapeHtml(__(' VALORE OBBLIGATORIO')) ?></span>
                <?php if ($product_hint_status && $helper->getProductHintPrice()): ?>
                    <img src="<?= $escaper->escapeUrl($block
                    ->getViewFileUrl('Webkul_Marketplace::images/quest.png')); ?>" class='questimg' 
                    title="<?= $escaper->escapeHtml($helper->getProductHintPrice()) ?>"/>
                <?php endif; ?>
                <div class="control">
                    <input type="text" class="required-entry validate-zero-or-greater input-text" 
                    name="product[price]" id="price" value="<?= /* @noEscape */ $data['product']['price']?>"/>
                </div>
            </div>
            <div style="display:none" class="field">
                <label class="label"><?= $escaper->escapeHtml(__('Special Price')) ?><b>
                    <?= /* @noEscape */ " (".$currency_symbol.")"; ?></b>:</label>
                <?php if ($product_hint_status && $helper->getProductHintSpecialPrice()): ?>
                    <img src="<?= $escaper->escapeUrl($block
                    ->getViewFileUrl('Webkul_Marketplace::images/quest.png')); ?>" class='questimg' 
                    title="<?= $escaper->escapeHtml($helper->getProductHintSpecialPrice()) ?>"/>
                <?php endif; ?>
                <div class="control">
                    <input type="hidden" class="widthinput input-text validate-zero-or-greater"
                     name="product[special_price]" id="special-price" 
                     value="<?= /* @noEscape */ $data['product']['special_price']?>"/>
                </div>
            </div>
            <div style="display:none" class="field">
                <label class="label"><?= $escaper->escapeHtml(__('Special Price From')) ?>:</label>
                <?php if ($product_hint_status && $helper->getProductHintStartDate()): ?>
                    <img src="<?= $escaper->escapeUrl($block
                    ->getViewFileUrl('Webkul_Marketplace::images/quest.png')); ?>" class='questimg' 
                    title="<?= $escaper->escapeHtml($helper->getProductHintStartDate()) ?>"/>
                <?php endif; ?>
                <div class="control">
                    <input type="hidden" name="product[special_from_date]" id="special-from-date" 
                    class="input-text" value="<?= /* @noEscape */ $data['product']['special_from_date']?>"/>
                </div>
            </div>
            <div style="display:none" class="field">
                <label class="label"><?= $escaper->escapeHtml(__('Special Price To')) ?>:</label>
                <?php if ($product_hint_status && $helper->getProductHintEndDate()): ?>
                    <img src="<?= $escaper->escapeUrl($block
                    ->getViewFileUrl('Webkul_Marketplace::images/quest.png')); ?>" class='questimg' 
                    title="<?= $escaper->escapeHtml($helper->getProductHintEndDate()) ?>"/>
                <?php endif; ?>
                <div class="control">
                    <input type="hidden" name="product[special_to_date]" id="special-to-date" 
                    class="input-text" value="<?= /* @noEscape */ $data['product']['special_to_date']?>"/>
                </div>
            </div>
            <input id="inventory_manage_stock" type="hidden" name="product[stock_data][manage_stock]" value="1">
            <input type="hidden" value="1" name="product[stock_data][use_config_manage_stock]" 
            id="inventory_use_config_manage_stock">
            <div style="display:none" class="field ">
                <label class="label"><?= $escaper->escapeHtml(__('Stock')) ?>:</label>
                <?php if ($product_hint_status && $helper->getProductHintQty()): ?>
                    <img src="<?= $escaper->escapeUrl($block
                    ->getViewFileUrl('Webkul_Marketplace::images/quest.png')); ?>" class='questimg' 
                    title="<?= $escaper->escapeHtml($helper->getProductHintQty()) ?>"/>
                <?php endif; ?>
                <div class="control">
                <input type="text" class="required-entry validate-number input-text" 
                    name="product[quantity_and_stock_status][qty]" id="qty" 
                    value="1"/>
                </div>
            </div>
            <div style="display:none" class="field ">
                <label class="label"><?= $escaper->escapeHtml(__('Stock Availability')) ?>:</label>
                <?php if ($product_hint_status && $helper->getProductHintStock()): ?>
                    <img src="<?= $escaper->escapeUrl($block
                    ->getViewFileUrl('Webkul_Marketplace::images/quest.png')); ?>" 
                    class='questimg' title="<?= $escaper->escapeHtml($helper->getProductHintStock()) ?>"/>
                <?php endif; ?>
                <div class="control">
                    <select id="" class="select" name="product[quantity_and_stock_status][is_in_stock]">
                        <option value="1"  selected="selected" <?php if ($data['product']['quantity_and_stock_status']['is_in_stock'] == 1) {
                             echo "selected='selected'";}?>><?= $escaper->escapeHtml(__("In Stock")); ?></option>
                        <option value="0" <?php if ($data['product']['quantity_and_stock_status']['is_in_stock'] == 0) {
                             echo "selected='selected'";}?>><?= $escaper->escapeHtml(__("Out of Stock")); ?></option>
                    </select>
                </div>    
            </div>
            <div style="display:none" class="field ">
                <label class="label"><?= $escaper->escapeHtml(__('Visibility')) ?>:</label>
                <div class="control">
                    <select id="visibility" class=" required-entry required-entry select" name="product[visibility]">
                        <option value=""><?= $escaper->escapeHtml(__('Please Select'))?></option>
                        <?php $product_visibility = $helper->getVisibilityOptionArray(); ?>
                        <?php foreach ($product_visibility as $key => $value): ?>
                            <option selected="selected" value="4">Catalog, Search</option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>    

<!-- accordian start -->

<div id="element">
    <div data-role="collapsible" class="accordian_collap_stl">
        <div data-role="trigger" class="accordian_stl">
            <span>DATI GENERALI</span>
        </div>
    </div>
    <div data-role="content" class="accordian_content_stl">

            <div class="field required field_stl">
                <label class="label"><?= $escaper->escapeHtml(__('BRAND')) ?>: </label><span style="color: #EF7C0A; font-weight: 600;"><?= $escaper->escapeHtml(__(' VALORE OBBLIGATORIO')) ?></span>
                <div class="control">
                    <select id="brand" class="required-entry required-entry select" name="product[brand]">
                       
                    <?php $product_brand = $helper->getBrandDropdownOptions();?>

                 <?php
                        
                         
                         
                         foreach ($product_brand as $key => $value): ?>
                            <option value="<?= /* @noEscape */ $key ?>"
                             <?php if ($key==$data['product']['brand']) {
                                    echo "selected='selected'";}?>><?=  /* @noEscape */ $value?></option>
                        <?php endforeach; ?>
                    </select>
                       
                </div>   
            </div>


            <div class="field required field_stl">
                <label class="label"><?= $escaper->escapeHtml(__('BRAND MODELLO')) ?>: </label><span style="color: #EF7C0A; font-weight: 600;"><?= $escaper->escapeHtml(__(' VALORE OBBLIGATORIO')) ?></span>
                <div class="control">
                    <select id="brand_modello" class="required-entry required-entry select" name="product[brand_modello]">
                       
                    <?php $product_attr = $helper->getAttrDropdownOptions('brand_modello');?>
                         <?php foreach ($product_attr as $key => $value): ?>
                            <option value="<?= /* @noEscape */ $key ?>"
                             <?php if ($key==$data['product']['brand_modello']) {
                                    echo "selected='selected'";}?>><?=  /* @noEscape */ $value?></option>
                        <?php endforeach; ?>
                    </select>
                       
                </div>
            </div>

            <div class="field field_stl">
                <label class="label"><?= $escaper->escapeHtml(__('MODELLO NON PRESENTE')) ?>:</label>
                  <div class="control"> 
                     <input name="product[modello_non_presente]" id="MODELLO NON PRESENTE" 
                        class="validate-length maximum-length-64 input-text" type="text" 
                        value="<?= $escaper->escapeHtml($data['product']['modello_non_presente'])?>"/>               
  
			      </div>
			   
			   
            </div> 



            <div class="field required field_stl">
                <label class="label"><?= $escaper->escapeHtml(__('NUMERO DI REFERENZA')) ?>:</label>
               <div class="control"> 
                     <input name="product[numero_di_referenza]" id="numero_di_referenza" 
                        class="validate-length maximum-length-64 input-text" type="text" 
                        value="<?= $escaper->escapeHtml($data['product']['numero_di_referenza'])?>"/>               
       
                </div>
            </div>



            
<div class="field required field_stl">
    <label class="label"><?= $escaper->escapeHtml(__('PROVENIENZA')) ?>: </label><span style="color: #EF7C0A; font-weight: 600;"><?= $escaper->escapeHtml(__(' VALORE OBBLIGATORIO')) ?></span>

	 <div class="control">
	   <select id="bandiera" class="required-entry required-entry select" name="product[bandiera]">
            <option value=""><?= $escaper->escapeHtml(__('Seleziona')) ?></option>
            <?php $product_attr = $helper->getAttrDropdownOptions('bandiera'); ?>
            <?php foreach ($product_attr as $key => $value): ?>
                <option value="<?= /* @noEscape */ $key ?>"> <?= /* @noEscape */ $value ?></option>
            <?php endforeach; ?>
        </select>
  </div>
</div>

            
<div class="field required field_stl">
    <label class="label"><?= $escaper->escapeHtml(__('GENERE')) ?>:</label>
    <div class="control">
        <select id="genere" class="select" name="product[genere]">
            <option value=""><?= $escaper->escapeHtml(__('Seleziona')) ?></option>
            <?php $product_attr = $helper->getAttrDropdownOptions('genere'); ?>
            <?php foreach ($product_attr as $key => $value): ?>
                <option value="<?= /* @noEscape */ $key ?>" <?php if ($key == $data['product']['genere']) {
                    echo "selected='selected'";
                } ?>><?= /* @noEscape */ $value ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>




            <div class="field field_stl">
                <label class="label"><?= $escaper->escapeHtml(__('ANNO DI PRODUZIONE')) ?>:</label>
                   <div class="control"> 
                     <input name="product[anno_di_produzione]" id="anno_di_produzione" 
                        class="validate-length maximum-length-64 input-text" type="text" 
                        value="<?= $escaper->escapeHtml($data['product']['anno_di_produzione'])?>"/>               
                    </div>   
			 </div> 



             <div class="field required field_stl">
                <label class="label"><?= $escaper->escapeHtml(__('CONDIZIONE')) ?>: </label><span style="color: #EF7C0A; font-weight: 600;"><?= $escaper->escapeHtml(__(' VALORE OBBLIGATORIO')) ?></span>
                <div class="control">
                    <select id="condizione" class="required-entry required-entry select" name="product[condizione]">
					<option value=""><?= $escaper->escapeHtml(__('Seleziona')) ?></option>                       
                    <?php $product_attr = $helper->getAttrDropdownOptions('condizione');?>
                         <?php foreach ($product_attr as $key => $value): ?>
                            <option value="<?= /* @noEscape */ $key ?>"
                             <?php if ($key==$data['product']['condizione']) {
                                    echo "selected='selected'";}?>><?=  /* @noEscape */ $value?></option>
                        <?php endforeach; ?>
                    </select>
                       
                </div>
            </div>



            <div class="field required field_stl">
                <label class="label"><?= $escaper->escapeHtml(__('CORREDO')) ?>: </label><span style="color: #EF7C0A; font-weight: 600;"><?= $escaper->escapeHtml(__(' VALORE OBBLIGATORIO')) ?></span>
                <div class="corredo">
                    <select id="corredo" class="required-entry required-entry select" name="product[corredo]">
					<option value=""><?= $escaper->escapeHtml(__('Seleziona')) ?></option>                       
                       
                    <?php $product_attr = $helper->getAttrDropdownOptions('corredo');?>
                         <?php foreach ($product_attr as $key => $value): ?>
                            <option value="<?= /* @noEscape */ $key ?>"
                             <?php if ($key==$data['product']['corredo']) {
                                    echo "selected='selected'";}?>><?=  /* @noEscape */ $value?></option>
                        <?php endforeach; ?>
                    </select>
                       
                </div>
            </div>


<div class="field required field_stl">
    <label class="label"><?= $escaper->escapeHtml(__('CARICA')) ?>:</label>
    <div class="carica">
        <select id="carica" class="select" name="product[carica]">
            <option value=""><?= $escaper->escapeHtml(__('Seleziona')) ?></option>
            <?php $product_attr = $helper->getAttrDropdownOptions('carica'); ?>
            <?php foreach ($product_attr as $key => $value): ?>
                <option value="<?= /* @noEscape */ $key ?>" <?php if ($key == $data['product']['carica']) {
                    echo "selected='selected'";
                } ?>><?= /* @noEscape */ $value ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>
		
		
            <div class="field field_stl">
                <label class="label"><?= $escaper->escapeHtml(__('DIAMETRO')) ?>:</label>
                     <div class="control"> 
                         <input name="product[diametro]" id="diametro" 
                        class="validate-length maximum-length-64 input-text" type="text" 
                        value="<?= $escaper->escapeHtml($data['product']['diametro'])?>"/>               
  
			        </div>
			   
			</div>



            <!-- y/n -->

           <div class="field field_stl" >     
               <label class="field-label" for="sponsorizzato"><span>
            <?= $escaper->escapeHtml(__('SPONSORIZZATO'))?></span></label>
                   <div class="field-control">
                         <div class="field-control sponsorizzato" data-role="sponsorizzato">
                             <div class="field-control-group">
                                 <div class="field field-option">
                                       <input type="radio" name="product[sponsorizzato]" value="1"
                                           class="control-radio"
                                            id="sponsorizzato1"


                              
                                          <?php if ($data['product']['sponsorizzato']): ?>
                                             checked="checked"
                                                 <?php endif; ?>
                                                          >
                                               <label class="field-label" for="sponsorizzato">
                                                <span>Yes</span>
                                               </label>
                                     </div>
                                   <div class="field field-option">
                                  <input type="radio" name="product[sponsorizzato]" value="0"
                                           class="control-radio"
                                              id="sponsorizzato0"
                                                <?php if (!$data['product']['sponsorizzato']): ?>
                                                  checked="checked"
                                                  <?php endif; ?>
                                                      >
                                                 <label class="field-label" for="sponsorizzato">
                                                    <span>No</span>
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
            <span>CASSA</span>
        </div>
    </div>
    <div data-role="content" class="accordian_content_stl">

            
<div class="field required field_stl">
    <label class="label"><?= $escaper->escapeHtml(__('MATERIALE CASSA')) ?>:</label>
    <div class="materiale_cassa">
        <select id="materiale_cassa" class="select" name="product[materiale_cassa]">
            <option value=""><?= $escaper->escapeHtml(__('Seleziona')) ?></option>
            <?php $product_attr = $helper->getAttrDropdownOptions('materiale_cassa'); ?>
            <?php foreach ($product_attr as $key => $value): ?>
                <option value="<?= /* @noEscape */ $key ?>" <?php if ($key == $data['product']['materiale_cassa']) {
                    echo "selected='selected'";
                } ?>><?= /* @noEscape */ $value ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>

            
<div class="field required field_stl">
    <label class="label"><?= $escaper->escapeHtml(__('MATERIALE LUNETTA')) ?>:</label>
    <div class="materiale_lunetta">
        <select id="materiale_lunetta" class="select" name="product[materiale_lunetta]">
            <option value=""><?= $escaper->escapeHtml(__('Seleziona')) ?></option>
            <?php $product_attr = $helper->getAttrDropdownOptions('materiale_lunetta'); ?>
            <?php foreach ($product_attr as $key => $value): ?>
                <option value="<?= /* @noEscape */ $key ?>" <?php if ($key == $data['product']['materiale_lunetta']) {
                    echo "selected='selected'";
                } ?>><?= /* @noEscape */ $value ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>

            
<div class="field required field_stl">
    <label class="label"><?= $escaper->escapeHtml(__('VETRO')) ?>:</label>
    <div class="vetro">
        <select id="vetro" class="select" name="product[vetro]">
            <option value=""><?= $escaper->escapeHtml(__('Seleziona')) ?></option>
            <?php $product_attr = $helper->getAttrDropdownOptions('vetro'); ?>
            <?php foreach ($product_attr as $key => $value): ?>
                <option value="<?= /* @noEscape */ $key ?>" <?php if ($key == $data['product']['vetro']) {
                    echo "selected='selected'";
                } ?>><?= /* @noEscape */ $value ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>

            
<div class="field required field_stl">
    <label class="label"><?= $escaper->escapeHtml(__('IMPERMEABILE')) ?>:</label>
    <div class="impermeabile">
        <select id="impermeabile" class="select" name="product[impermeabile]">
            <option value=""><?= $escaper->escapeHtml(__('Seleziona')) ?></option>
            <?php $product_attr = $helper->getAttrDropdownOptions('impermeabile'); ?>
            <?php foreach ($product_attr as $key => $value): ?>
                <option value="<?= /* @noEscape */ $key ?>" <?php if ($key == $data['product']['impermeabile']) {
                    echo "selected='selected'";
                } ?>><?= /* @noEscape */ $value ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div> 

            <div class="field ">
                <label class="label"><?= $escaper->escapeHtml(__('ALTRE CARATTERISTICHE')) ?>:</label> 
                <span style="color: #EF7C0A; font-weight: bold;">Selezioni multiple Ctrl+click</span>
<div class="altre_caratteristiche">
                  
                        <select id="altre_caratteristiche" class="  select" name="product[altre_caratteristiche][]" multiple>
                       
                    <?php $product_attr = $helper->getAttrDropdownOptions('altre_caratteristiche');?>
                         <?php foreach ($product_attr as $key => $value): ?>
                            <option value="<?= /* @noEscape */ $key ?>"
                             ><?=  /* @noEscape */ $value?></option>
                        <?php endforeach; ?>
                    </select>
                       
                </div>
            </div>


    </div>

    <div data-role="collapsible" class="accordian_collap_stl">
        <div data-role="trigger" class="accordian_stl">
            <span>QUADRANTE e LANCETTE</span>
        </div>
    </div>
    <div data-role="content" class="accordian_content_stl">
		

            
<div class="field required field_stl">
    <label class="label"><?= $escaper->escapeHtml(__('QUADRANTE')) ?>:</label>
    <div class="quadrante">
        <select id="quadrante" class="select" name="product[quadrante]">
            <option value=""><?= $escaper->escapeHtml(__('Seleziona')) ?></option>
            <?php $product_attr = $helper->getAttrDropdownOptions('quadrante'); ?>
            <?php foreach ($product_attr as $key => $value): ?>
                <option value="<?= /* @noEscape */ $key ?>" <?php if ($key == $data['product']['quadrante']) {
                    echo "selected='selected'";
                } ?>><?= /* @noEscape */ $value ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>        
            
<div class="field required field_stl">
    <label class="label"><?= $escaper->escapeHtml(__('INDICI SU QUADRANTE')) ?>:</label>
    <div class="indici_su_quadrante">
        <select id="indici_su_quadrante" class="select" name="product[indici_su_quadrante]">
            <option value=""><?= $escaper->escapeHtml(__('Seleziona')) ?></option>
            <?php $product_attr = $helper->getAttrDropdownOptions('indici_su_quadrante'); ?>
            <?php foreach ($product_attr as $key => $value): ?>
                <option value="<?= /* @noEscape */ $key ?>" <?php if ($key == $data['product']['indici_su_quadrante']) {
                    echo "selected='selected'";
                } ?>><?= /* @noEscape */ $value ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>      
            
<div class="field required field_stl">
    <label class="label"><?= $escaper->escapeHtml(__('COLORE QUADRANTE')) ?>:</label>
    <div class="colore_quadrante">
        <select id="colore_quadrante" class="select" name="product[colore_quadrante]">
            <option value=""><?= $escaper->escapeHtml(__('Seleziona')) ?></option>
            <?php $product_attr = $helper->getAttrDropdownOptions('colore_quadrante'); ?>
            <?php foreach ($product_attr as $key => $value): ?>
                <option value="<?= /* @noEscape */ $key ?>" <?php if ($key == $data['product']['colore_quadrante']) {
                    echo "selected='selected'";
                } ?>><?= /* @noEscape */ $value ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>       
            
<div class="field required field_stl">
    <label class="label"><?= $escaper->escapeHtml(__('CARATTERISTICHE LANCETTE')) ?>:</label>
    <div class="caratteristiche_lancette">
        <select id="caratteristiche_lancette" class="select" name="product[caratteristiche_lancette]">
            <option value=""><?= $escaper->escapeHtml(__('Seleziona')) ?></option>
            <?php $product_attr = $helper->getAttrDropdownOptions('caratteristiche_lancette'); ?>
            <?php foreach ($product_attr as $key => $value): ?>
                <option value="<?= /* @noEscape */ $key ?>" <?php if ($key == $data['product']['caratteristiche_lancette']) {
                    echo "selected='selected'";
                } ?>><?= /* @noEscape */ $value ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>     




    </div>

    <div data-role="collapsible" class="accordian_collap_stl">
        <div data-role="trigger" class="accordian_stl">
            <span>CINTURINO</span>
        </div>
    </div>
    <div data-role="content" class="accordian_content_stl">
		       
		      
            
<div class="field required field_stl">
    <label class="label"><?= $escaper->escapeHtml(__('MATERIALE BRACCIALE/CINTURINO')) ?>:</label>
    <div class="materiale_cinturino">
        <select id="materiale_cinturino" class="select" name="product[materiale_cinturino]">
            <option value=""><?= $escaper->escapeHtml(__('Seleziona')) ?></option>
            <?php $product_attr = $helper->getAttrDropdownOptions('materiale_cinturino'); ?>
            <?php foreach ($product_attr as $key => $value): ?>
                <option value="<?= /* @noEscape */ $key ?>" <?php if ($key == $data['product']['materiale_cinturino']) {
                    echo "selected='selected'";
                } ?>><?= /* @noEscape */ $value ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>     
		      
            
<div class="field required field_stl">
    <label class="label"><?= $escaper->escapeHtml(__('COLORE CINTURINO')) ?>:</label>
    <div class="colore_cinturino">
        <select id="colore_cinturino" class="select" name="product[colore_cinturino]">
            <option value=""><?= $escaper->escapeHtml(__('Seleziona')) ?></option>
            <?php $product_attr = $helper->getAttrDropdownOptions('colore_cinturino'); ?>
            <?php foreach ($product_attr as $key => $value): ?>
                <option value="<?= /* @noEscape */ $key ?>" <?php if ($key == $data['product']['colore_cinturino']) {
                    echo "selected='selected'";
                } ?>><?= /* @noEscape */ $value ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>      
		      
            
<div class="field required field_stl">
    <label class="label"><?= $escaper->escapeHtml(__('CHIUSURA CINTURINO')) ?>:</label>
    <div class="chiusura_cinturino">
        <select id="chiusura_cinturino" class="select" name="product[chiusura_cinturino]">
            <option value=""><?= $escaper->escapeHtml(__('Seleziona')) ?></option>
            <?php $product_attr = $helper->getAttrDropdownOptions('chiusura_cinturino'); ?>
            <?php foreach ($product_attr as $key => $value): ?>
                <option value="<?= /* @noEscape */ $key ?>" <?php if ($key == $data['product']['chiusura_cinturino']) {
                    echo "selected='selected'";
                } ?>><?= /* @noEscape */ $value ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>       
		      
            
<div class="field required field_stl">
    <label class="label"><?= $escaper->escapeHtml(__('FIBIA')) ?>:</label>
    <div class="fibia">
        <select id="fibia" class="select" name="product[fibia]">
            <option value=""><?= $escaper->escapeHtml(__('Seleziona')) ?></option>
            <?php $product_attr = $helper->getAttrDropdownOptions('fibia'); ?>
            <?php foreach ($product_attr as $key => $value): ?>
                <option value="<?= /* @noEscape */ $key ?>" <?php if ($key == $data['product']['fibia']) {
                    echo "selected='selected'";
                } ?>><?= /* @noEscape */ $value ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div> 
		
		

    </div>



    <div data-role="collapsible" class="accordian_collap_stl">
        <div data-role="trigger" class="accordian_stl">
            <span>FUNZIONI</span>
        </div>
    </div>
    <div data-role="content" class="accordian_content_stl">
     


             <div class="field required field_stl">
                <label class="label"><?= $escaper->escapeHtml(__('FUNZIONI')) ?>:</label> 
                <span style="color: #EF7C0A; font-weight: bold;">Selezioni multiple Ctrl+click</span>
                <div class="funzioni">
                   <select id="funzioni" class=" required-entry select" name="product[funzioni][]" multiple>
                       
                    <?php $product_attr = $helper->getAttrDropdownOptions('funzioni');?>
                         <?php foreach ($product_attr as $key => $value): ?>
                            <option value="<?= /* @noEscape */ $key ?>"
                             <?php if ($key==$data['product']['funzioni']) {
                                    echo "selected='selected'";}?>><?=  /* @noEscape */ $value?></option>
                        <?php endforeach; ?>
                    </select>
                       
                </div>
            </div>
          

    </div>

  <?php /* .... DESCRIZIONE ................................................................................................  */ ?>	
            <div class="field required">
                <label class="label"><?= $escaper->escapeHtml(__('Description')) ?>:</label>
                <?php
                if ($product_hint_status && $helper->getProductHintDesc()) {?>
                    <img src="<?= $escaper->escapeUrl($block
                    ->getViewFileUrl('Webkul_Marketplace::images/quest.png')); ?>" class='questimg' 
                    title="<?=  $escaper->escapeHtml($helper->getProductHintDesc()) ?>"/>
                    <?php
                } ?>
                <div class="control wk-border-box-sizing">
                    <textarea name="product[description]" class="required-entry input-text" 
                    id="description" rows="5" cols="75" >
                    <?= /* @noEscape */  $data['product']['description']?></textarea>
                    <?php if ($helper->isWysiwygEnabled()): ?>
                        <script>
                            require([
                                "jquery",
                                "mage/translate",
                                "mage/adminhtml/events",
                                "mage/adminhtml/wysiwyg/tiny_mce/setup"
                            ], function(jQuery) {
                                wysiwygDescription = new wysiwygSetup("description", {
                                    "width" : "100%",
                                    "height" : "200px",
                                    "plugins" : [{"name":"image"}],
                                    "tinymce" : {
                                        "toolbar":"formatselect | bold italic underline | "+
                                        "alignleft aligncenter alignright |" + 
                                        "bullist numlist |"+
                                        "link table charmap","plugins":"advlist "+
                                        "autolink lists link charmap media noneditable table "+
                                        "contextmenu paste code help table",
                                    },
                                    files_browser_window_url: "<?= /* @noEscape */$block->getWysiwygUrl();?>"
                                });
                                wysiwygDescription.setup("exact");
                            });
                        </script>
                    <?php endif; ?>
                </div>
            </div>

            <div class="field">
                <label class="label"><?= $escaper->escapeHtml(__('Short Description')) ?>:</label>
                <?php if ($product_hint_status && $helper->getProductHintShortDesc()) { ?>
                    <img src="<?= $escaper->escapeUrl($block
                    ->getViewFileUrl('Webkul_Marketplace::images/quest.png')); ?>"
                     class='questimg' title="<?= $escaper->escapeHtml($helper->getProductHintShortDesc()) ?>"/>
                <?php } ?>
                <div class="control wk-border-box-sizing">
                    <textarea name="product[short_description]" class="input-text"
                     id="short_description" rows="5" cols="75" >
                     <?= /* @noEscape */ $data['product']['short_description']?></textarea>
                    <?php if ($helper->isWysiwygEnabled()): ?>
                        <script>
                            require([
                                "jquery",
                                "mage/translate",
                                "mage/adminhtml/events",
                                "mage/adminhtml/wysiwyg/tiny_mce/setup"
                            ], function(jQuery) {
                                wysiwygShortDescription = new wysiwygSetup("short_description", {
                                    "width" : "100%",
                                    "height" : "200px",
                                    "plugins" : [{"name":"image"}],
                                    "tinymce" : {
                                        "toolbar":"formatselect | bold italic underline | "+
                                        "alignleft aligncenter alignright |" + 
                                        "bullist numlist |"+
                                        "link table charmap","plugins":"advlist "+
                                        "autolink lists link charmap media noneditable table "+
                                        "contextmenu paste code help table",
                                    },
                                    files_browser_window_url: "<?= /* @noEscape */$block->getWysiwygUrl();?>"
                                });
                                wysiwygShortDescription.setup("exact");
                            });
                        </script>
                    <?php endif; ?>
                </div>
            </div>
  <?php /* .... DESCRIZIONE ................................................................................................  */ ?>



</div>

<script>
    require([
        'jquery',
        'accordion'], function ($) {
        $("#element").accordion();
    });
</script>

<!-- accordian end -->
            
            
<?php /* .... HIDDEN ................................................................................................  */ ?>
            <div class="field " hidden="true">
                <label class="label"><?= $escaper->escapeHtml(__('Tax Class')) ?>:</label>
                <?php if ($product_hint_status && $helper->getProductHintTax()): ?>
                    <img src="<?= $escaper->escapeUrl($block
                    ->getViewFileUrl('Webkul_Marketplace::images/quest.png')); ?>"
                     class='questimg' title="<?= $escaper->escapeHtml($helper->getProductHintTax()) ?>"/>
                <?php endif; ?>
                <div class="control">
                    <select id="tax-class-id" class="required-entry required-entry select" 
                    name="product[tax_class_id]">
                        <option value="0"><?= $escaper->escapeHtml(__('None'))?></option>
                        <?php $taxes = $helper->getTaxClassModel(); ?>
                        <?php foreach ($taxes as $tax): ?>
                            <option value="<?= $escaper->escapeHtml($tax->getId()) ?>" 
                            <?php if ($tax->getId()==$data['product']['tax_class_id']) { echo "selected='selected'";}?>>
                            <?= $escaper->escapeHtml($tax->getClassName())?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
<?php /* .... HIDDEN ................................................................................................  */ ?>	
	
            <div style="display:none" class="field ">
                <label class="label"><?= $escaper->escapeHtml(__('Weight')) ?> (<?= $escaper
                ->escapeHtml($weightUnit)?>):</label>
                <?php if ($product_hint_status && $helper->getProductHintWeight()): ?>
                    <img src="<?= $escaper->escapeUrl($block
                    ->getViewFileUrl('Webkul_Marketplace::images/quest.png')); ?>" class='questimg' 
                    title="<?= $escaper->escapeHtml($helper->getProductHintWeight()) ?>"/>
                <?php endif; ?>
                <div data-role="weight-switcher">
                    <label data-ui-id="product-tabs-attributes-tab-element-radios-product-product-has-weight-label"
                     for="weight-switcher">
                        <span><?= $escaper->escapeHtml(__('Does this have a weight?'))?></span>
                    </label>
                    <div class="control">
                        <div class="control">
                            <input type="radio" <?php if ($type!='virtual' || $type!='downloadable' ||
                             $data['product']['product_has_weight']==1) { ?> checked="checked" <?php } ?>
                              class="weight-switcher" id="weight-switcher1" value="1" 
                              name="product[product_has_weight]">
                            <label for="weight-switcher1">
                                <span><?= $escaper->escapeHtml(__('Yes'))?></span>
                            </label>
                        </div>
                        <div class="control">
                            <input type="radio" class="weight-switcher" id="weight-switcher0" value="0" 
                            name="product[product_has_weight]" <?php if ($type=='virtual' || $type=='downloadable' ||
                             $data['product']['product_has_weight']==0) { ?> checked="checked" <?php } ?>>
                            <label for="weight-switcher0">
                                <span><?= $escaper->escapeHtml(__('No'))?></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="control">
                    <input type="text" class="validate-zero-or-greater input-text" name="product[weight]" 
                    id="weight" value='1'/>
                </div>
            </div>

            <div style="display:none;" class="field">
                <label class="label"><?= $escaper->escapeHtml(__('Url Key')) ?>:</label>
                <div class="control">
                    <input type="text" class="input-text" name="product[url_key]" id="url_key" 
                     value="<?php /* @noEscape */ $data['product']['url_key']."-".$data['product']['sku'] ?>"/>
                </div>
            </div>
            <?php if (!$helper->getCustomerSharePerWebsite()): ?>
<?php /* .... HIDDEN ................................................................................................  */ ?>	
                <div class="field required" hidden="true">
                    <label class="label"><?= $escaper->escapeHtml(__('Product in Websites')) ?>:</label>
                    <div class="control">
                        <select id="websites" class="required-entry select" name="product[website_ids][]" multiple>
                            <?php $websites = $helper->getAllWebsites(); ?>
                            <?php foreach ($websites as $website): ?>
                                <option value="<?= /* @noEscape */ $website->getWebsiteId() ?>">
                                <?=  /* @noEscape */ $website->getName()?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
<?php /* .... HIDDEN ................................................................................................  */ ?>	
            <?php endif; ?>
<?php /* .... HIDDEN ................................................................................................  */ ?>	
            <div class="field" hidden="true"> 
                <label class="label"><?= $escaper->escapeHtml(__('Meta Title')) ?>:</label>
                <div class="control">
                    <input type="text" class="input-text" name="product[meta_title]" id="meta_title" 
                     value="<?= /* @noEscape */ $data['product']['meta_title']?>"/>
                </div>
            </div>
            <div class="field" hidden="true">
                <label class="label"><?= $escaper->escapeHtml(__('Meta Keywords')) ?>:</label>
                <div class="control">
                    <textarea class="textarea" id="meta_keyword"
                     name="product[meta_keyword]"><?= /* @noEscape */ $data['product']['meta_keyword']?></textarea>
                </div>
            </div>
            <div class="field" hidden="true">
                <label class="label"><?= $escaper->escapeHtml(__('Meta Description')) ?>:</label>
                <div class="control">
                    <textarea class="textarea" id="meta_description" name="product[meta_description]">
                        <?= /* @noEscape */ $data['product']['meta_description']?></textarea>
                </div>
            </div>
<?php /* .... HIDDEN ................................................................................................  */ ?>	
            <?php //echo $block->getChildHtml(); ?>   
        </fieldset>
    </div>
</form>
<?php
$formData = [
    'countryPicSelector' => '#country-pic',
    'verifySkuAjaxUrl' => $block->getUrl('marketplace/product/verifysku', ['_secure' => $block
    ->getRequest()->isSecure()]),
    'categoryTreeAjaxUrl' => $block->getUrl('marketplace/product/categorytree/', ['_secure' => $block
    ->getRequest()->isSecure()])
];
$serializedFormData = $viewModel->getJsonHelper()->jsonEncode($formData);
?>
 
<script type="text/x-magento-init">
    {
        "*": {
            "sellerAddProduct": <?= /* @noEscape */ $serializedFormData; ?>
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
    ], function($, TypeSwitcher){
        var $form = $('[data-form=edit-product]');
        $form.data('typeSwitcher', TypeSwitcher.init());
    });
</script>
<script type="text/x-magento-init">
        "*": {
            "Webkul_Marketplace/js/product/weight-handler": {},
            "Webkul_Marketplace/catalog/apply-to-type-switcher": {}
        }
    }
</script>
<script type="text/javascript">
    require(['jquery'], function($) {
        $(document).ready(function() {
            $('#websites').find('option').prop('selected', true);
        });
    });
</script>
