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
<style>
  .reassurance-items {
    display: flex;
    flex-wrap: wrap;
  }

  .reassurance-item-container {
    flex: 1 1 200px; /* Each item will have a minimum width of 200px and can grow */
    margin: 10px;
  }

  .reassurance-item {
    border: 1px solid #ccc;
    background-color: #fff;
    padding: 10px;
    height: 100%;
    width: 100%;
    box-sizing: border-box;
    display: flex;
    flex-direction: column;
  }

  .reassurance-item img {
    width: 30px;
    max-height: 100px;
    float: left;
    margin-right: 10px;
  }

  .reassurance-item h3 {
    margin-top: 0;
  }
</style>

{if $reassuranceItems}
  <div class="container">
    <div class="row reassurance-items">
      {foreach from=$reassuranceItems item=item}
        <div class="col-xl-2 col-md-6 col-sm-12 reassurance-item-container">
          <div class="reassurance-item">
            <img src="{$urls.base_url|escape:'htmlall':'UTF-8'}modules/reassuranceplus/views/img/images/{$item.image|escape:'htmlall':'UTF-8'}" alt="{$item.title|escape:'htmlall':'UTF-8'}" class="img-responsive">
            <h3>{$item.title|escape:'htmlall':'UTF-8'}</h3>
            <p>{$item.description|escape:'htmlall':'UTF-8'}</p>
          </div>
        </div>
      {/foreach}
    </div>
  </div>
{/if}