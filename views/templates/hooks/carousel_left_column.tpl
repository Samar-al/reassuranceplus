{**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 *}
<!-- reassuranceplus/views/templates/hook/carousel_left_column.tpl -->
<style>
  /* Carousel styles */
  #reassurance-carousel {
    max-width: 200px; /* Adjust the width as needed */
    margin: 0 auto; 
    background-color: #ffffff; 
    border: 1px solid #ccc; 
    overflow: hidden; 
  }

  #carousel-content {
    text-align: center;
    padding: 10px;
  }

  #carousel-content h3 {
    margin: 0;
    padding-bottom: 10px;
  }

  #carousel-content img {
    width: 30px; /* Set the image width to 30px */
    height: auto;
    display: block;
    margin: 0 auto 10px;
  }
</style>


<!-- reassuranceplus/views/templates/hook/carousel_left_column.tpl -->
{if $reassuranceItems}
<div id="reassurance-carousel" class="carousel slide" data-ride="carousel">
    <div class="carousel-inner">
        {foreach from=$reassuranceItems item=reassurance key=index}
            <div class="carousel-item {if $index == 0}active{/if}">
                <div id="carousel-content" class="carousel-content">
                    <h3>{$reassurance.title|escape:'htmlall':'UTF-8'}</h3>
                    <p>{$reassurance.description|escape:'htmlall':'UTF-8'}</p>
                    <img src="{$urls.base_url|escape:'htmlall':'UTF-8'}modules/reassuranceplus/views/img/images/{$reassurance.image|escape:'htmlall':'UTF-8'}" alt="{$reassurance.title|escape:'htmlall':'UTF-8'}">
                </div>
            </div>
        {/foreach}
    </div>
    <a class="carousel-control-prev" href="#reassurance-carousel" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#reassurance-carousel" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>
{/if}


<script>
    // Activate the carousel
    $(document).ready(function () {
        $('.reassurance-carousel').carousel();
    });
</script>