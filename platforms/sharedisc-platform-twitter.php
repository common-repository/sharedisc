<?php


/**
 *
 * Class for Twitter Sharing
 *
 * @since      1.0.0
 * @package    ShareDisc
 * @subpackage ShareDisc/platforms
 * @author     Hilton Moore <hilton@kri8it.com>
 */
class ShareDisc_Sharing_Platform_Twitter extends ShareDisc_Sharing_Platform{

	
	
	
	
	
	
	
	
	
	
	/**
	 * __construct
	 *
	 * @since 1.0.0
	*/
	public function __construct() {

		$this->platform_name = 'twitter';
		$this->platform_title = 'Twitter';
		$this->platform_version = '1.0.0';
		$this->shared_types = array();
		
		parent::__construct();	
			
	}
	
	
	
	
	
	
	
	
	
	
	/**
	 * per_product_option
	 *
	 * @since 1.0.0
	*/
	public function per_product_option($prod_id, $cart_key, $price){
			$this->set_product_values($prod_id, $cart_key, $price, 'product');		
		
		?>
			
			
			<?php if(@!in_array($this->platform_name, $this->shared_types) && $this->product_can_share): ?>
			<a title="Share <?php echo $this->product_title; ?> on <strong><?php echo $this->platform_title;?></strong> to instantly earn <?php echo $this->product_discount;?>% off!" id="tweetButton" href="https://twitter.com/intent/tweet?url=<?=$this->share_url_short; ?>&text=<?=$this->product_keyline; ?>" class="sd_tooltip share-link share-link-<?=$this->platform_name;?> share-me-button share-box sd_table_click" data-share-base="<?=$this->product_base_after;?>"  data-share-percentage="<?=$this->product_discount;?>" data-product-id="<?=$this->product_id;?>" data-product-url="<?=$this->product_permalink;?>" data-product-key="<?=$this->share_key;?>" data-title="<?= $this->product_title; ?>" data-keyline="<?=$this->product_keyline; ?>" data-price="<?=$this->product_after_price;?>" data-image="<?= $this->product_image; ?>" data-social="<?=$this->platform_name; ?>" data-sharedproductid="<?=$this->cart_key;?>" data-shareurl="<?=$this->share_url_short; ?>"  data-sharehow="product">
				
				<div class="socialicon sd_<?=$this->platform_name;?>"></div>	
				
			</a>
			<?php endif; 
	}
	
	
	
	
	
	
	
	
	
	
	/**
	 * per_cart_option
	 *
	 * @since 1.0.0
	*/
	public function per_cart_option($prod_id, $cart_key, $price){			
				
			$this->set_product_values($prod_id, $cart_key, $price, 'cart');	
		
		?>
			
			
			<?php if(@!in_array($this->platform_name, $this->shared_carts)): ?>
			<a title="Share <?php echo $this->product_title; ?> on <strong><?php echo $this->platform_title;?></strong> to instantly earn <?php echo $this->product_discount;?>% off!" id="tweetButton" href="https://twitter.com/intent/tweet?url=<?=$this->share_url_short; ?>&text=<?=$this->product_keyline; ?>" class="sd_tooltip share-link share-link-<?=$this->platform_name;?> share-me-button share-box sd_table_click" data-share-base="<?=$this->product_base_after;?>"  data-share-percentage="<?=$this->product_discount;?>" data-product-id="<?=$this->product_id;?>" data-product-url="<?=$this->product_permalink;?>" data-product-key="<?=$this->share_key;?>" data-title="<?= $this->product_title; ?>" data-keyline="<?=$this->product_keyline; ?>" data-price="<?=$this->product_after_price;?>" data-image="<?= $this->product_image; ?>" data-social="<?=$this->platform_name; ?>" data-sharedproductid="<?=$this->cart_key;?>" data-shareurl="<?=$this->share_url_short; ?>"  data-sharehow="cart">
				
				<div class="socialicon sd_<?=$this->platform_name;?>"></div>	
				
			</a>
			<?php endif; 
	}		
	
	
	
	
	
	
	
	
	
	
	/**
	 * public_option
	 *
	 * @since 1.0.0
	*/
	public function public_option_old($prod_id, $cart_key, $price){
			$this->set_product_values($prod_id, $cart_key, $price);		
		
		?>
		
		<tr class="share-provider <?=$this->platform_name;?> share-options">
			
			<!--<div class="share-box sd_table_provider"><div class="socialicon <?=$this->platform_name;?>"></div><?php echo $this->platform_title; ?></div>-->
			<td class="share-box sd_table_discount">Share <strong><em><?php echo $this->product_title; ?></em></strong> on <div class="socialicon sd_<?=$this->platform_name;?>"></div><strong style="color:<?php SDHelper::platform_colour($this->platform_name); ?>"><?php echo $this->platform_title; ?></strong> to instantly earn <strong><?=$this->product_discount;?>%</strong> off!
			<!--<div class="share-box sd_table_value"><?= $this->currency;?><?php echo number_format($this->product_after_price, 2); ?></div>
			<div class="share-box sd_table_earned"><?= $this->currency;?><?php echo number_format($this->discount_earned, 2); ?></div>-->
						
			<?php if(@!in_array($this->platform_name, $this->shared_types)): ?>
				<?php if($this->product_can_share): ?>
					<a id="tweetButton" href="https://twitter.com/intent/tweet?url=<?=$this->share_url_short; ?>&text=<?=$this->product_keyline; ?>" class="button alt sd_table_click share-box share-link share-link-<?=$this->platform_name;?> share-me-button" data-share-base="<?=$this->product_base_after;?>"  data-share-percentage="<?=$this->product_discount;?>" data-product-id="<?=$this->product_id;?>" data-product-url="<?=$this->product_permalink;?>" data-product-key="<?=$this->share_key;?>" data-title="<?= $this->product_title; ?>" data-keyline="<?=$this->product_keyline; ?>" data-price="<?=$this->product_after_price;?>" data-image="<?= $this->product_image; ?>" data-social="<?=$this->platform_name; ?>" data-sharedproductid="<?=$this->cart_key;?>" data-shareurl="<?=$this->share_url_short; ?>" >Share</a>	
				<?php else: ?>
					<a id="sd_max_text" class="button ">Max Reached</a>
				<?php endif; ?>
			<?php else: ?>
				<a id="sd_shared_text" class="button ">Already Shared</a>
			<?php endif; ?>			
			</td>					
		</tr>
		
		<?php
	}	

	
}


?>