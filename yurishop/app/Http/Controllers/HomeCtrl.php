<?php 
namespace App\Http\Controllers;
header('Content-Type: text/html; charset=utf-8');
//ini_set('memory_limit', '2000M');
include 'public/assets/simple_html_dom/simple_html_dom.php';


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//use View;
//use Illuminate\Support\Facades\DB;

class HomeCtrl extends Controller {
	public function __construct(){
       //$this->middleware('auth');
    }
    /*
    public function index(){
        return view('front/pages/home');
    }
    */
    public function order_list(){
        return view('front/pages/order_list');
    }
    public function test(){
    	$str_file = file_get_contents("public/assets/simple_html_dom/page_content.txt");
    	print_r($str_file);
    }
    public function product_info(Request $request){
    	$product_url = $request->input('product_url');
        $product_url = escapeshellarg($product_url);
		$response =  exec("phantomjs public/assets/js/main.js ".$product_url." > public/assets/simple_html_dom/page_content.txt");
		//$str_file = file_get_contents("public/assets/simple_html_dom/page_content.txt");
		$html = file_get_html("public/assets/simple_html_dom/page_content.txt");
		
		// Tình trạng website có lấy được thông tin sản phẩm từ js hay không ?
		// Nếu state = 0 -> không lấy được 
		$state = 0;  

		if(strpos($product_url,'.taobao.com')){
			$page = 'taobao';
			$name =  $html->find('span[itemprop="name"]',0)->plaintext;
			$image = $html->find('#J_ThumbView',0)->src;
			$origin_price = $html->find('span[itemprop="price"]',0)->plaintext;
			$current_price = $html->find('.tb-rmb-num',0)->plaintext;
			$amount = array();  // quantity of product to buy, not used here

			/* Get sizes and colors, assume that sizes placed before colors */
			$arraySize = array();
			if($html->find('.tb-cleafix')){
				$sizes = $html->find('.tb-cleafix',0)->find('span');
				if($sizes){
					foreach ($sizes as $s) {
						$arraySize[] = $s->plaintext;
					}
				}
			}
	
			$arrayColor = array();
			if($html->find('.tb-cleafix')){
				$colors = $html->find('.tb-cleafix',1)->find('a');
				if($colors){
					foreach($colors as $c) {
						$colorText = $c->find('span',0)->plaintext;
						$colorImg = '';
						// Get background image if has
						if($c->style){
							$bg = $c->style;
							$start = strpos($bg,'(');
							$end = strpos($bg,')');
							$img_url = substr($bg,$start+1,$end-1-$start);
							$colorImg = $img_url;
						}
						$arrayColor[] = array('text' => $colorText, 'img' => $colorImg);
					}
				}
			}
			
			$description = array();
			if($html->find('.extra-desc-info')){
				$des =  $html->find('.extra-desc-info',0)->find('li');
				if($des){
					foreach ($des as $d) {
						$description[] = $d->plaintext;
					}
				}
			}
	
			$shop_name =  $html->find('.tb-shop-name',0);
		}
		elseif(strpos($product_url,'.tmall.com')){
			$page = 'tmall';
			
			$start_pos = strpos($html, 'TShop.Setup');
			
			if($start_pos != false){
				$state = 1;
				$sub_str = substr($html,$start_pos + 12);  // chuỗi string bắt đầu từ phần tử json đầu tiên
				$end_pos = strpos($sub_str,');');    // vị trí kết thúc mảng json
				$str_json = trim(substr($sub_str, 0, $end_pos-1));   // chuỗi string của mảng json
				$prop = json_decode($str_json,true);  // chuyển string thành json
				//print_r($prop);

				// Mảng kết hợp : size + color
				$size_list = $prop['valItemInfo']['skuList'];
				// Mảng kết hợp : size + color + price + quantity
				$size_detail = $prop['valItemInfo']['skuMap'];
		
				//echo "<br>size list :<br>".$size_list;
			}
			
			
			$name = $html->find('h1',0)->plaintext;
			$image = $html->find('#J_ImgBooth',0)->src;
			$origin_price = $html->find('.tm-price',0)->plaintext;
			$current_price = $html->find('.tm-price',1)->plaintext;
			$amount = array();  // quantity of product to buy, not used here
	
			
			$colors = '';
			$sizes = '';
			if($html->find('.tm-sale-prop')){
				foreach($html->find('.tm-sale-prop') as $p){
					if($p->find('.tb-img')){
						$colors = $p;
					}
					else{
						$sizes = $p;
					}
				}
			}
			
			$arraySize = array();
			if($sizes){
				foreach ($sizes->find('li') as $s) {  
					$data_value = 'data-value';
					$arraySize[] = array('sizeName' => $s->plaintext , 'sizeId' => $s->$data_value);
				}
			}
			
			$arrayColor = array();
			if($colors){
				foreach($colors->find('li') as $c) {
					$data_value = 'data-value';
					$colorId = $c->$data_value;
					$colorName = $c->plaintext;
					$colorImg = '';

					
					// Get background image if has
					if($c->find('a',0)->style){
						
						$bg = $c->find('a',0)->style;
						$start = strpos($bg,'(');
						$end = strpos($bg,')');
						$img_url = substr($bg, $start+1, $end-1-$start);  // Chú ý : sau "$bg,"" phải có khoảng trống
						$colorImg = $img_url;
					}
					
					$arrayColor[] = array('colorName' => $colorName, 'colorImg' => $colorImg, 'colorId' => $colorId);
				}
			}
			

			$description = array();
			if($html->find('#J_AttrUL')){
				$des =  $html->find('#J_AttrUL',0)->find('li');
				if($des){
					foreach ($des as $d) {
						$description[] = $d->plaintext;
					}
				}
			}

			$shop_name =  $html->find('input[name="seller_nickname"]',0)->value;
		}
		else{
			$page = '1688';
			$name =  $html->find('h1[class="d-title"]',0)->plaintext;
			$image = '';
			if($html->find('.box-img')){
				$image = $html->find('.box-img',0)->find('img',0)->src;
			}
			/* Get current price */
			$current_price = array();
			if($html->find('div.mod-detail-price')){
				$current_price_tr = $html->find('div.mod-detail-price',0)->find('tr.price',0);
				if($current_price_tr){
					foreach($current_price_tr->find('td') as $p){
						if($p->find('span')){
							$cur_price = $p->find('span',1)->plaintext;
							$currency = $p->find('span',0)->plaintext;
							$current_price[] = $cur_price.' '.$currency;
						}
					}
				}
			}
			/* Get origin price (if has) */
			$origin_price = array();
			if($html->find('div.mod-detail-price')){
				$origin_price_tr = $html->find('div.mod-detail-price',0)->find('tr.original-price',0);
				if($origin_price_tr){
					foreach($origin_price_tr->find('td') as $p){
						$org_price = $p->find('span',1)->plaintext;
						$currency = $p->find('span',0)->plaintext;
						$origin_price[] = $org_price.' '.$currency;
					}
				}
			}
			/* Get amount products to buy */
			$amount = array();
			if($html->find('div.mod-detail-price')){
				$amount_tr = $html->find('div.mod-detail-price',0)->find('tr.amount',0);
				if($amount_tr){
					foreach($amount_tr->find('td') as $a){
						if($a->find('span')){
							$value = $a->find('span',0)->plaintext;
							$unit = $a->find('span',1)->plaintext;
							$amount[] = $value.' '.$unit;
						}
					}
				}
			}
	
			$arrayColor = array();
			if($html->find('.list-leading')){
				$colors = $html->find('.list-leading',0)->find('a');
				if($colors){
					foreach($colors as $c) {
						$colorText = $c->find('span',0)->plaintext;
						$colorImg = '';
						// Get image if has
						if($c->find('img')){
							$img_src = $c->find('img',0)->src;
							$colorImg = $img_src;
						}
						$arrayColor[] = array('text' => $colorText, 'img' => $colorImg);
					}
				}
			}
	
			/* Get size */
			$arraySize = array();
			$tableSize = $html->find('table[class="table-sku"]',0);
			if($tableSize){
				foreach($tableSize->find('tr') as $trSize){
					$sizeName = '';
					if($trSize->find('td[class="name"]'))
						$sizeName = $trSize->find('td[class="name"]',0)->find('span',0)->plaintext;
					$sizePrice = '';
					if($trSize->find('td[class="price"]'))
						$sizePrice = $trSize->find('td[class="price"]',0)->find('span',0)->innertext;
					$sizeCount = '';
					if($trSize->find('td[class="count"]'))
						$sizeCount = $trSize->find('td[class="count"]',0)->find('span',0)->innertext;
					$arraySize[] = array('name' => $sizeName, 'price' => $sizePrice, 'count' => $sizeCount);
				}
			}
	
			/* Get description features */
			$arrayFeature = array();
			if($html->find('.offerdetail_ditto_attributes')){
				$features = $html->find('.offerdetail_ditto_attributes',0)->find('td.de-feature');
				if($features){
					foreach($features as $f){
						$arrayFeature[] = $f->plaintext;		
					}
				}
			}
			/* Get description values */
			$arrayValue = array();
			if($html->find('.offerdetail_ditto_attributes')){
				$values = $html->find('.offerdetail_ditto_attributes',0)->find('td.de-value');
				if($values){
					foreach($values as $v){
						$arrayValue[] = $v->plaintext;		
					}
				}
			}
			$description = array();
			if(!empty($arrayFeature) && !empty($arrayValue) && count($arrayFeature)==count($arrayValue)){
				//$arrayDes = array_combine($arrayFeature, $arrayValue);
				foreach($arrayFeature as $key => $feature){
					$description[] = $feature .' : '. $arrayValue[$key];
				}
			}
	
			/* Get shop name */
			$shop_name = '';
			if($html->find('a.company-name')){
				$shop_name = $html->find('a.company-name',0)->innertext;
			}
		}

		return view('front.pages.order')->with(["page" => "taobao", 
					 				"name" => $name, 
					 				"image" => $image, 
					 				"origin_price" => $origin_price, 
					 				"current_price" => $current_price,
					 				"amount" => $amount,
					 				"sizes" => $arraySize, 
					 				"colors" => $arrayColor, 
					 				"description" => $description, 
					 				"shop_name" => $shop_name]);
    } 
	
	/* Ajax function to get product info */
	function get_product_info(){
        $product_url = escapeshellarg($_POST['product_url']);
		$response =  exec("phantomjs public/assets/js/main.js ".$product_url." > public/assets/simple_html_dom/page_content.txt");
		//$str_file = file_get_contents("public/assets/simple_html_dom/page_content.txt");
		$html = file_get_html("public/assets/simple_html_dom/page_content.txt");
		
		// Tình trạng website có lấy được thông tin sản phẩm từ js hay không ?
		// Nếu state = 0 -> không lấy được 
		$state = 0;  

		if(strpos($product_url,'.taobao.com')){
			$page = 'taobao';
			$name =  $html->find('span[itemprop="name"]',0)->plaintext;
			$image = $html->find('#J_ThumbView',0)->src;
			$origin_price = $html->find('span[itemprop="price"]',0)->plaintext;
			$current_price = $html->find('.tb-rmb-num',0)->plaintext;
			$amount = array();  // quantity of product to buy, not used here

			/* Get sizes and colors, assume that sizes placed before colors */
			$arraySize = array();
			if($html->find('.tb-cleafix')){
				$sizes = $html->find('.tb-cleafix',0)->find('span');
				if($sizes){
					foreach ($sizes as $s) {
						$arraySize[] = $s->plaintext;
					}
				}
			}
	
			$arrayColor = array();
			if($html->find('.tb-cleafix')){
				$colors = $html->find('.tb-cleafix',1)->find('a');
				if($colors){
					foreach($colors as $c) {
						$colorText = $c->find('span',0)->plaintext;
						$colorImg = '';
						// Get background image if has
						if($c->style){
							$bg = $c->style;
							$start = strpos($bg,'(');
							$end = strpos($bg,')');
							$img_url = substr($bg,$start+1,$end-1-$start);
							$colorImg = $img_url;
						}
						$arrayColor[] = array('text' => $colorText, 'img' => $colorImg);
					}
				}
			}
			
			$description = array();
			if($html->find('.extra-desc-info')){
				$des =  $html->find('.extra-desc-info',0)->find('li');
				if($des){
					foreach ($des as $d) {
						$description[] = $d->plaintext;
					}
				}
			}
	
			$shop_name =  $html->find('.tb-shop-name',0);
		}
		elseif(strpos($product_url,'.tmall.com')){
			$page = 'tmall';
			
			$start_pos = strpos($html, 'TShop.Setup');
			
			if($start_pos != false){
				$state = 1;
				$sub_str = substr($html,$start_pos + 12);  // chuỗi string bắt đầu từ phần tử json đầu tiên
				$end_pos = strpos($sub_str,');');    // vị trí kết thúc mảng json
				$str_json = trim(substr($sub_str, 0, $end_pos-1));   // chuỗi string của mảng json
				$prop = json_decode($str_json,true);  // chuyển string thành json
				//print_r($prop);

				// Mảng kết hợp : size + color
				$size_list = $prop['valItemInfo']['skuList'];
				// Mảng kết hợp : size + color + price + quantity
				$size_detail = $prop['valItemInfo']['skuMap'];
		
				//echo "<br>size list :<br>".$size_list;
			}
			
			
			$name = $html->find('h1',0)->plaintext;
			$image = $html->find('#J_ImgBooth',0)->src;
			$origin_price = $html->find('.tm-price',0)->plaintext;
			$current_price = $html->find('.tm-price',1)->plaintext;
			$amount = array();  // quantity of product to buy, not used here
	
			
			$colors = '';
			$sizes = '';
			if($html->find('.tm-sale-prop')){
				foreach($html->find('.tm-sale-prop') as $p){
					if($p->find('.tb-img')){
						$colors = $p;
					}
					else{
						$sizes = $p;
					}
				}
			}
			
			$arraySize = array();
			if($sizes){
				foreach ($sizes->find('li') as $s) {  
					$data_value = 'data-value';
					$arraySize[] = array('sizeName' => $s->plaintext , 'sizeId' => $s->$data_value);
				}
			}
			
			$arrayColor = array();
			if($colors){
				foreach($colors->find('li') as $c) {
					$data_value = 'data-value';
					$colorId = $c->$data_value;
					$colorName = $c->plaintext;
					$colorImg = '';

					
					// Get background image if has
					if($c->find('a',0)->style){
						
						$bg = $c->find('a',0)->style;
						$start = strpos($bg,'(');
						$end = strpos($bg,')');
						$img_url = substr($bg, $start+1, $end-1-$start);  // Chú ý : sau "$bg,"" phải có khoảng trống
						$colorImg = $img_url;
					}
					
					$arrayColor[] = array('colorName' => $colorName, 'colorImg' => $colorImg, 'colorId' => $colorId);
				}
			}
			

			$description = array();
			if($html->find('#J_AttrUL')){
				$des =  $html->find('#J_AttrUL',0)->find('li');
				if($des){
					foreach ($des as $d) {
						$description[] = $d->plaintext;
					}
				}
			}

			$shop_name =  $html->find('input[name="seller_nickname"]',0)->value;
		}
		else{
			$page = '1688';
			$name =  $html->find('h1[class="d-title"]',0)->plaintext;
			$image = '';
			if($html->find('.box-img')){
				$image = $html->find('.box-img',0)->find('img',0)->src;
			}
			/* Get current price */
			$current_price = array();
			if($html->find('div.mod-detail-price')){
				$current_price_tr = $html->find('div.mod-detail-price',0)->find('tr.price',0);
				if($current_price_tr){
					foreach($current_price_tr->find('td') as $p){
						if($p->find('span')){
							$cur_price = $p->find('span',1)->plaintext;
							$currency = $p->find('span',0)->plaintext;
							$current_price[] = $cur_price.' '.$currency;
						}
					}
				}
			}
			/* Get origin price (if has) */
			$origin_price = array();
			if($html->find('div.mod-detail-price')){
				$origin_price_tr = $html->find('div.mod-detail-price',0)->find('tr.original-price',0);
				if($origin_price_tr){
					foreach($origin_price_tr->find('td') as $p){
						$org_price = $p->find('span',1)->plaintext;
						$currency = $p->find('span',0)->plaintext;
						$origin_price[] = $org_price.' '.$currency;
					}
				}
			}
			/* Get amount products to buy */
			$amount = array();
			if($html->find('div.mod-detail-price')){
				$amount_tr = $html->find('div.mod-detail-price',0)->find('tr.amount',0);
				if($amount_tr){
					foreach($amount_tr->find('td') as $a){
						if($a->find('span')){
							$value = $a->find('span',0)->plaintext;
							$unit = $a->find('span',1)->plaintext;
							$amount[] = $value.' '.$unit;
						}
					}
				}
			}
	
			$arrayColor = array();
			if($html->find('.list-leading')){
				$colors = $html->find('.list-leading',0)->find('a');
				if($colors){
					foreach($colors as $c) {
						$colorText = $c->find('span',0)->plaintext;
						$colorImg = '';
						// Get image if has
						if($c->find('img')){
							$img_src = $c->find('img',0)->src;
							$colorImg = $img_src;
						}
						$arrayColor[] = array('text' => $colorText, 'img' => $colorImg);
					}
				}
			}
	
			/* Get size */
			$arraySize = array();
			$tableSize = $html->find('table[class="table-sku"]',0);
			if($tableSize){
				foreach($tableSize->find('tr') as $trSize){
					$sizeName = '';
					if($trSize->find('td[class="name"]'))
						$sizeName = $trSize->find('td[class="name"]',0)->find('span',0)->plaintext;
					$sizePrice = '';
					if($trSize->find('td[class="price"]'))
						$sizePrice = $trSize->find('td[class="price"]',0)->find('span',0)->innertext;
					$sizeCount = '';
					if($trSize->find('td[class="count"]'))
						$sizeCount = $trSize->find('td[class="count"]',0)->find('span',0)->innertext;
					$arraySize[] = array('name' => $sizeName, 'price' => $sizePrice, 'count' => $sizeCount);
				}
			}
	
			/* Get description features */
			$arrayFeature = array();
			if($html->find('.offerdetail_ditto_attributes')){
				$features = $html->find('.offerdetail_ditto_attributes',0)->find('td.de-feature');
				if($features){
					foreach($features as $f){
						$arrayFeature[] = $f->plaintext;		
					}
				}
			}
			/* Get description values */
			$arrayValue = array();
			if($html->find('.offerdetail_ditto_attributes')){
				$values = $html->find('.offerdetail_ditto_attributes',0)->find('td.de-value');
				if($values){
					foreach($values as $v){
						$arrayValue[] = $v->plaintext;		
					}
				}
			}
			$description = array();
			if(!empty($arrayFeature) && !empty($arrayValue) && count($arrayFeature)==count($arrayValue)){
				//$arrayDes = array_combine($arrayFeature, $arrayValue);
				foreach($arrayFeature as $key => $feature){
					$description[] = $feature .' : '. $arrayValue[$key];
				}
			}
	
			/* Get shop name */
			$shop_name = '';
			if($html->find('a.company-name')){
				$shop_name = $html->find('a.company-name',0)->innertext;
			}
		}
		
		$res = array("page" => "taobao", 
					 //"name" => $name, 
					 //"image" => $image, 
					 //"origin_price" => $origin_price, 
					 //"current_price" => $current_price,
					 //"amount" => $amount,
					 //"size" => $arraySize, 
					 //"color" => $arrayColor, 
					 "description" => $description, 
					 "shop_name" => $shop_name
					 );
		echo json_encode($res);
    } 
}
