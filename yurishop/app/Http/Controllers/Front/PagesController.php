<?php

namespace App\Http\Controllers\Front;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

include 'public/assets/simple_html_dom/simple_html_dom.php';

class PagesController extends Controller
{

    public function home(){
        return view('front.pages.home');
    }
    public function product_info(Request $request){

    	$this->validate($request,['product_url'=>'required'],['required' => 'Hãy nhập link sản phẩm']);
    	$url = $request->input('product_url');
    	// Khởi tạo các thuộc tính sản phẩm
		$page = '';
		$state = false;   // Website có lấy được thông tin sản phẩm hay không (Nếu state = 0 -> không lấy được)
		$product_info = array();
		// $name = '';
		// $image = '';
		// $default_price = (strpos($url,'.taobao.com') != false || strpos($url,'.tmall.com') != false) ? 0 : array();
		// $sizes = array();
		// $colors = array();
		// $skuMap = array();  // Mảng kết hợp : size + color + price + quantity
		// $description = array();
		// $shop_name = '';
		//$first = '';  // thứ tự xuất hiện của color và size (cái nào xuất hiện trước)
    	
        //$product_url = escapeshellarg($url);
		//$response = $this->httpRequest($url); die();

		if(strpos($url,'global.rakuten.com')!= false){  // nếu sp thuộc rakuten
			$page = 'rakuten';
			$product_info = $this->get_product_rakuten($url);
		}
		elseif(strpos($url,'amazon.co.jp')!= false){
			$page = 'amazon';
			$product_info = $this->get_product_amazon($url);
		}

		//var_dump($product_info); die();
		// chuyển array sang string 
		$sizes = json_encode($product_info['sizes'], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
		$skuMap = json_encode($product_info['skuMap'], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
		
		if($product_info['image'] != '')
			$state = true;
		return view('front.pages.order')->with(["state" => $state,
												"product_url" => $url,
												"page" => $page, 
					 							"name" => $product_info['name'], 
					 							"image" => $product_info['image'], 
					 							"default_price" => $product_info['default_price'],
					 							"skuMap" => $skuMap,
					 							"sizes" => $sizes, 
					 							"colors" => $product_info['colors'], 
												"first" => $product_info['first'],
					 							"description" => $product_info['description'], 
					 							"shop_name" => $product_info['shop_name']
					 							]);
    } 

	// Lây thông tin sp ở taobao
	public function get_product_rakuten($url)
	{

		$html = file_get_html($url);
		//echo "url = ".$url; die();

		if($html->find(".b-ttl-main")){
			$name =  $html->find(".b-ttl-main",0)->plaintext;  
			//$name = mb_convert_encoding($name, 'UTF-8', 'GB2312'); // chuyển sang mã utf8
		}
		if($html->find('.b-main-image'))
			$image = $html->find('.b-main-image',0)->src;

		if($html->find(".b-text-xxlarge")){
			$default_price = $html->find(".b-text-xxlarge",0)->plaintext;		
		}

		if($html->find(".m-price-in-yen")){
			$origin_price = $html->find(".m-price-in-yen",0)->plaintext;		
		}

		$colorBox = '';
		$sizeBox = '';
		if($skuBox = $html->find('.b-control-group')){  
			foreach ($skuBox as $sb) {
				if ($sb->find('label')) {
					$str = $sb->find('label', 0)->plaintext;
					if (strpos(strtolower($str), 'size') !== FALSE) {
						$sizeBox = $sb;
					}
					if (strpos(strtolower($str), 'color') !== FALSE) {
						$colorBox = $sb;
					}
				}
			}
		}

		if ($sizeBox != ''){
			foreach ($sizeBox->find('li') as $s) {  
				$sizeName = $s->find('label', 0)->plaintext;
				//$sizeName = mb_convert_encoding($sizeName, 'UTF-8', 'GB2312'); // chuyển sang mã utf8
				$sizes[] = array('sizeName' => $sizeName);
			}
		}

		if ($colorBox != ''){
			foreach ($colorBox->find('li') as $c) {  
				$colorName = $c->find('label', 0)->plaintext;
				//$sizeName = mb_convert_encoding($sizeName, 'UTF-8', 'GB2312'); // chuyển sang mã utf8
				$colors[] = array('colorName' => $colorName);
			}
		}

		$start_pos = strpos($html, 'skuMap');  // lấy skuMap trong chuỗi string json
		if($start_pos != false){
			$state = 1;
			$sub_str = substr($html,$start_pos + 12);  // lấy chuỗi string bắt đầu từ phần tử json đầu tiên
			$end_pos = strpos($sub_str,'}}');    // lấy vị trí kết thúc mảng json
			$str_json = trim(substr($sub_str, 0, $end_pos+2));   // chuỗi string của mảng json
			$skuMap = json_decode($str_json,true);  // chuyển string thành json
		}

		if(!empty($skuMap)){
			if(!empty($colors) && !empty($sizes)){  // trường hợp sp có nhiều size và color khác nhau
				// Lấy giá và số lượng sản phẩm mặc định theo kích thước và màu sắc mặc định (màu đầu tiên trong dánh sách)
				$default_color_id = $colors[0]['colorId'];
				foreach ($sizes as $k => $size) {
					if($first=="size")
						$pvs = ";".$size['sizeId'].";".$default_color_id.";";  // Lấy pvs trong array skuList
					elseif($first=="color")
						$pvs = ";".$default_color_id.";".$size['sizeId'].";";
					foreach ($skuMap as $key => $value) {
						if($pvs == $key){
							$sizes[$k]['sizePrice'] = $value['price'];
							$sizes[$k]['sizeQuantity'] = $value['stock'];
							break;
						}
						else{
							$sizes[$k]['sizePrice'] = 0;
							$sizes[$k]['sizeQuantity'] = 0;
						}
					}
				}
			}
			elseif(!empty($colors)){  // trường hợp sản phẩm có size giống nhau, color khác nhau
				foreach ($colors as $k => $color) {
					$pvs = ";".$color['colorId'].";";  // Lấy pvs
					foreach($skuMap as $key => $value) {
						if($pvs == $key){
							$colors[$k]['colorPrice'] = $value['price'];
							$colors[$k]['colorQuantity'] = $value['stock'];
							break;
						}
						else{
							$colors[$k]['colorPrice'] = 0;
							$colors[$k]['colorQuantity'] = 0;
						}
					}
				}
			}
			elseif(!empty($sizes)){  // trường hợp sản phẩm có color giống nhau, size khác nhau
				foreach ($sizes as $k => $size) {
					$pvs = ";".$size['sizeId'].";";  // Lấy pvs
					foreach ($skuMap as $key => $value) {
						if($pvs == $key){
							$sizes[$k]['sizePrice'] = $value['price'];
							$sizes[$k]['sizeQuantity'] = $value['stock'];
							break;
						}
						else{
							$sizes[$k]['sizePrice'] = 0;
							$sizes[$k]['sizeQuantity'] = 0;
						}
					}
				}
			}
		}

	
		if($html->find('#J_SubWrap')){
			$des =  $html->find('#J_SubWrap',0)->find('li');
			if($des){
				foreach ($des as $d) {
					$description[] = mb_convert_encoding($d->plaintext, 'UTF-8', 'GB2312');
				}
			}
		}
	
		if($html->find('.tb-shop-name')){
			$shop_name =  $html->find('.tb-shop-name',0)->plaintext;
			$shop_name = mb_convert_encoding($shop_name, 'UTF-8', 'GB2312'); // chuyển sang mã utf8
		}
		elseif($html->find('#J_Pine')){
			$shop_id = "data-shopid";
			$shop_name = $html->find('#J_Pine',0)->$shop_id;
			$shop_name = mb_convert_encoding($shop_name, 'UTF-8', 'GB2312'); // chuyển sang mã utf8
		}

		$res = array('name' => isset($name) ? $name : '', 
					 'image' => isset($image) ? $image : '', 
					 'skuMap' => isset($skuMap) ? $skuMap : array(),
					 'shop_name' => isset($shop_name) ? $shop_name : '',
					 'colors' => isset($colors) ? $colors : array(), 
					 'sizes' => isset($sizes) ? $sizes : array(), 
					 'default_price' => isset($default_price) ? $default_price : 0, 
					 'description' => isset($description) ? $description : array(),
					 'first' => isset($first) ? $first : '', 
					 );
		return $res;
	}
	
	// Lấy thông tin sp ở tmall
	public function get_product_tmall($url)
	{
		$html = file_get_html($url);

		if($html->find('.tb-detail-hd')){
			if($html->find('.tb-detail-hd',0)->find('h1')){
				$name = $html->find('.tb-detail-hd',0)->find('h1',0)->plaintext; 
				$name = mb_convert_encoding($name, 'UTF-8', 'GB2312'); // chuyển sang mã utf8
			}
		}

		if($html->find('#J_ImgBooth'))
			$image = $html->find('#J_ImgBooth',0)->src;

		$colorBox = '';
		$sizeBox = '';
		if($skuBox = $html->find('.tm-sale-prop')){ 
			if(count($skuBox) == 2){  // sku có cả màu sắc và kích cỡ
				if($skuBox[0]->find('.tb-img')){
					$colorBox = $skuBox[0];
					$sizeBox = $skuBox[1];
					$first = 'color';
				}
				else{
					$sizeBox = $skuBox[0];
					$colorBox = $skuBox[1];
					$first = 'size';
				}
			}
			elseif(count($skuBox) == 1){  // sku chỉ có màu sắc hoặc kích cỡ
				if($skuBox[0]->find('.tb-img')){
					$colorBox = $skuBox[0];
				}
				else{
					$sizeBox = $skuBox[0];
				}			
			}
			elseif(count($skuBox) > 2){
				foreach ($skuBox as $s){
					if($s->find('.tb-img'))
						$colorBox = $s;
				}
			}
		}
	
		if($colorBox != ''){
			foreach($colorBox->find('li') as $c) {
				$data_value = 'data-value';
				$colorName = mb_convert_encoding($c->plaintext, 'UTF-8', 'GB2312'); // chuyển sang mã utf8
				$colorImg = '';
				// Lấy background nếu có
				if($c->find('a',0)->style){
					$bg = $c->find('a',0)->style;
					$start = strpos($bg,'(');
					$end = strpos($bg,')');
					$img_url = substr($bg, $start+1, $end-1-$start);  // Chú ý : sau "$bg,"" phải có khoảng trống
					$colorImg = $img_url;
				}
				$colors[] = array('colorName' => $colorName, 'colorImg' => $colorImg, 'colorId' => $c->$data_value);
			}
		}

		if($sizeBox != ''){
			foreach ($sizeBox->find('li') as $s) {  
				$data_value = 'data-value';
				$sizeName = mb_convert_encoding($s->plaintext, 'UTF-8', 'GB2312');
				$sizes[] = array('sizeName' => $sizeName , 'sizeId' => $s->$data_value);
			}
		}

		$start_pos = strpos($html, 'skuMap');  // lấy skuMap từ chuỗi string json
		if($start_pos != false){
			$state = 1;
			$sub_str = substr($html,$start_pos + 8);  // chuỗi string bắt đầu từ phần tử json đầu tiên
			$end_pos = strpos($sub_str,'}}');    // vị trí kết thúc mảng json
			$str_json = trim(substr($sub_str, 0, $end_pos+2));   // chuỗi string của mảng json
			$str_json = utf8_encode($str_json);
			$skuMap = json_decode($str_json,true);  // chuyển string thành json
			//var_dump($skuMap); die();
		}


		if(!empty($skuMap)){
			//echo "co skuMap"; die();
			reset($skuMap);
			$default_price = $skuMap[key($skuMap)]['price'];
			if(!empty($colors) && !empty($sizes)){  // trường hợp sp có nhiều size và color khác nhau);
				// Lấy giá và số lượng sản phẩm mặc định theo kích thước và màu sắc mặc định (màu đầu tiên trong dánh sách)
				$default_color_id = $colors[0]['colorId'];
				foreach ($sizes as $k => $size) {
					if($first=="size")
						$pvs = ";".$size['sizeId'].";".$default_color_id.";";  // Lấy pvs trong array skuList
					elseif($first=="color")
						$pvs = ";".$default_color_id.";".$size['sizeId'].";";
					foreach ($skuMap as $key => $value) {
						if($pvs == $key){
							$sizes[$k]['sizePrice'] = $value['price'];
							$sizes[$k]['sizeQuantity'] = $value['stock'];
							break;
						}
						else{
							$sizes[$k]['sizePrice'] = 0;
							$sizes[$k]['sizeQuantity'] = 0;
						}
					}
				}
			}
			elseif(!empty($colors)){  // trường hợp sản phẩm có size giống nhau, color khác nhau
				foreach ($colors as $k => $color) {
					$pvs = ";".$color['colorId'].";";  // Lấy pvs
					foreach($skuMap as $key => $value) {
						if($pvs == $key){
							$colors[$k]['colorPrice'] = $value['price'];
							$colors[$k]['colorQuantity'] = $value['stock'];
							break;
						}
						else{
							$colors[$k]['colorPrice'] = 0;
							$colors[$k]['colorQuantity'] = 0;
						}
					}
				}
				//var_dump($colors); die();
			}
			elseif(!empty($sizes)){  // trường hợp sản phẩm có color giống nhau, size khác nhau
				foreach ($sizes as $k => $size) {
					$pvs = ";".$size['sizeId'].";";  // Lấy pvs
					foreach ($skuMap as $key => $value) {
						if($pvs == $key){
							$sizes[$k]['sizePrice'] = $value['price'];
							$sizes[$k]['sizeQuantity'] = $value['stock'];
							break;
						}
						else{
							$sizes[$k]['sizePrice'] = 0;
							$sizes[$k]['sizeQuantity'] = 0;
						}
					}
				}
				
			}
		}
		
		if($html->find('#J_AttrUL')){
			$des =  $html->find('#J_AttrUL',0)->find('li');
			if(!empty($des)){
				foreach ($des as $d) {
					$description[] = mb_convert_encoding($d->plaintext, 'UTF-8', 'GB2312');
				}
			}
		}

		if($html->find(".slogo-shopname")){
			$shop_name =  $html->find(".slogo-shopname",0)->plaintext;
			$shop_name = mb_convert_encoding($shop_name, 'UTF-8', 'GB2312');
		}
		elseif($html->find(".hd-shop-name")) {
			if($html->find(".hd-shop-name",0)->find('a')){
				$shop_name = $html->find(".hd-shop-name",0)->find('a',0)->plaintext;
				$shop_name = mb_convert_encoding($shop_name, 'UTF-8', 'GB2312');
			}
		}

		$res = array('name' => isset($name) ? $name : '', 
					 'image' => isset($image) ? $image : '', 
					 'skuMap' => isset($skuMap) ? $skuMap : array(),
					 'shop_name' => isset($shop_name) ? $shop_name : '',
					 'colors' => isset($colors) ? $colors : array(), 
					 'sizes' => isset($sizes) ? $sizes : array(), 
					 'default_price' => isset($default_price) ? $default_price : 0, 
					 'description' => isset($description) ? $description : array(),
					 'first' => isset($first) ? $first : '', 
					 );
		return $res;
	}

	// Lấy thông tin sp ở 1688.com
    public function get_product_1688($product_id){
    	$exampleFacade = new \ComAlibabaProduct();
		$exampleFacade->setAppKey ( "3886928" );
		$exampleFacade->setSecKey ( "X9KFg7LBSv4X" );
		$exampleFacade->setServerHost ( "gw.open.1688.com" );
		$refreshToken ="18819abf-3f28-4949-b240-bb9b751554cf";

		$param = new \AlibabaAgentProductGetParam();
		$param->setProductID($product_id);
		$param->setWebSite('1688');
		$exampleProductGetResult = new \AlibabaAgentProductGetResult();	
		$authorizationToken = $exampleFacade->refreshToken($refreshToken);
		$access_token = $authorizationToken->getAccessToken();
		$exampleFacade->alibabaAgentProductGet( $param, $access_token, $exampleProductGetResult );
		$exampleProduct = $exampleProductGetResult->getProductInfo();
		if(!empty($exampleProduct)){
			$name = $exampleProduct->subject;  // tên sp
			$priceRanges = $exampleProduct->saleInfo->priceRanges;  // khoảng giá
			$skuInfo = $exampleProduct->skuInfos;  
			$colors = array();
			$sizes = array();
			$skuType = 0;  // sp có kiểu attribute nào ? 1-có cả 2, 2- chỉ có màu sắc, 3- chỉ có kích thước
			$shipAddress = $exampleProduct->shippingInfo->sendGoodsAddressId;
		
			if(!empty($skuInfo)){
				foreach($skuInfo as $key => $value){
					$attributes = $value->attributes->stdResult;  // mảng size + color
					if(count($attributes)==2){ 
						$skuType = 1; // sp thay đổi cả màu sắc và kích cỡ
						break;
					}
					else{
						if(array_key_exists('skuImageUrl',$attributes[0])){
							$skuType = 2;  // sp chỉ có màu sắc thay đổi
							break;
						}
						else{
							$skuType = 3;  // sp chỉ có kích cỡ thay đổi
						}
					}
				}
				foreach($skuInfo as $key => $value){
					$attributes = $value->attributes->stdResult;
					if($skuType==1){  // nếu sản phẩm có nhiều màu sắc và kích cỡ
						$colorName = $attributes[0]['attributeValue'];
						$colorImg = array_key_exists('skuImageUrl',$attributes[0]) ? "https://cbu01.alicdn.com/".$attributes[0]['skuImageUrl'] : '';
						$color_element = array("colorName" => $colorName, "colorImg" => $colorImg, "colorId" => "");
						if(!in_array($color_element, $colors))
							$colors[] = $color_element;
						$size_element= array("sizeName" => $attributes[1]['attributeValue'], "sizeId" => "");
						if(!in_array($size_element, $sizes))
							$sizes[] = $size_element;
					}
					elseif($skuType==2){  // nếu sp chỉ có màu sắc thay đổi
						$colorName = $attributes[0]['attributeValue'];
						$colorImg = array_key_exists('skuImageUrl',$attributes[0]) ? "https://cbu01.alicdn.com/".$attributes[0]['skuImageUrl'] : '';
						$colors[] = array("colorName" => $colorName, "colorImg" => $colorImg, "colorId" => "");
					}
					elseif($skuType==3){  // mặc đinh là kích thước thay đổi
						$sizes[] = array("sizeName" => $attributes[0]['attributeValue'], "sizeId" => "");
					}
				}
			}
		
			if(!empty($colors) && !empty($sizes)){
				$color = $colors[0];
				foreach($sizes as $key => $s){
					foreach($skuInfo as $k => $v){
						$attributes = $v->attributes->stdResult;
						if($attributes[0]['attributeValue']==$color['colorName'] && $attributes[1]['attributeValue']==$s['sizeName']){
							$sizes[$key]['sizeQuantity'] = $v->amountOnSale;
							$sizes[$key]['sizePrice'] = $priceRanges[0]->price;
							break;
						}
						else{
							$sizes[$key]['sizeQuantity'] = 0;
							$sizes[$key]['sizePrice'] = 0;
						}
					}
					//var_dump($sizes); die();
				}
			}
			elseif(!empty($colors)){
				foreach($colors as $key => $c){
					foreach($skuInfo as $k => $v){
						$attributes = $v->attributes->stdResult;
						if($attributes[0]['attributeValue']==$c['colorName']){
							$colors[$key]['colorQuantity'] = $v->amountOnSale;
							$colors[$key]['colorPrice'] = $priceRanges[0]->price;
							break;
						}
						else{
							$colors[$key]['colorQuantity'] = 0;
							$colors[$key]['colorPrice'] = 0;
						}
					}
				}
			}
			elseif(!empty($sizes)){
				foreach($sizes as $key => $s){
					foreach($skuInfo as $k => $v){
						$attributes = $v->attributes->stdResult;
						if($attributes[0]['attributeValue']==$s['sizeName']){
							$sizes[$key]['sizeQuantity'] = $v->amountOnSale;
							$sizes[$key]['sizePrice'] = $priceRanges[0]->price;
							break;
						}
						else{
							$sizes[$key]['sizeQuantity'] = 0;
							$sizes[$key]['sizePrice'] = 0;
						}
					}
				}
			}
		
			$image = '';
			$imageObj = $exampleProduct->image;  
			if($imageObj){
				$imageArr = $imageObj->images;
				$image = "https://cbu01.alicdn.com/".$imageArr[0];		
			}
			else{
				if(!empty($skuInfo)){
					foreach($skuInfo as $key => $value){
						$attributes = $value->attributes->stdResult;
						if(array_key_exists('skuImageUrl',$attributes[0])){
							$image = "https://cbu01.alicdn.com/".$attributes[0]['skuImageUrl'];
							break;
						}
					}
				}
			}

			$property = $exampleProduct->attributes;
			if (!empty($property)){
				foreach($property as $p){
					$description[] = $p->attributeName." : ".$p->value;
				}
			}
			
			$detail_des = $exampleProduct->description;
		}

		$res = array('name' => isset($name) ? $name : '', 
					 'image' => isset($image) ? $image : '', 
					 'skuMap' => isset($skuInfo) ? $skuInfo : array(),
					 'shop_name' => isset($shipAddress) ? $shipAddress : '',
					 'colors' => isset($colors) ? $colors : array(), 
					 'sizes' => isset($sizes) ? $sizes : array(), 
					 'default_price' => isset($priceRanges) ? $priceRanges : array(), 
					 'description' => isset($description) ? $description : array(),
					 'first' => '', 
					 );
		return $res;
    }

    // Ajax function dùng để lấy giá và số lượng sp theo kích thước và màu sắc
    function get_prop(Request $request){
    	$page = $request->page;
    	$sizes = $request->sizes;
    	$skuMap = $request->skuMap;

		$sizes = json_decode($sizes,TRUE);
		$skuMap = json_decode($skuMap,TRUE);

		if(!empty($sizes) && !empty($skuMap)){
			if($page == 'taobao' || $page == 'tmall'){
				$first = $request->first;
				$color_id = $request->colorId;
				foreach ($sizes as $k => $size) {
					if($first=="size")
			 			$pvs = ";".$size['sizeId'].";".$color_id.";";  // Lấy pvs trong array skuList
			 		elseif($first=="color")
			 			$pvs = ";".$color_id.";".$size['sizeId'].";";
			 		foreach ($skuMap as $key => $value) {
			 			if($pvs == $key){
			 				$sizes[$k]['sizePrice'] = $value['price'];
			 				$sizes[$k]['sizeQuantity'] = $value['stock'];
			 				break;
			 			}
			 			else{
			 				$sizes[$k]['sizePrice'] = 0;
			 				$sizes[$k]['sizeQuantity'] = 0;
			 			}
			 		}
			 	}
			}
			elseif($page == '1688'){
				$color_name = $request->colorName;
				$default_price = $request->default_price;
				foreach($sizes as $key => $s){
					foreach($skuMap as $k => $v){
						$attributes = $v['attributes']['stdResult'];
						if($attributes[0]['attributeValue']==$color_name && $attributes[1]['attributeValue']==$s['sizeName']){
							$sizes[$key]['sizeQuantity'] = $v['amountOnSale'];
							$sizes[$key]['sizePrice'] = $default_price;
							break;
						}
						else{
							$sizes[$key]['sizeQuantity'] = 0;
							$sizes[$key]['sizePrice'] = 0;
						}
					}
				}
			}
		}

		echo json_encode($sizes);
	}

    // cURL link sp taobao.com và tmall.com
    function httpRequest($link, $type = 'get', $postdata = ''){
 		$tmp_fname  = tempnam("/tmp", "CURLCOOKIE"); unlink($tmp_fname);
 		$srs = curl_init();
 		curl_setopt($srs, CURLOPT_URL, $link);
 		curl_setopt($srs, CURLOPT_RETURNTRANSFER,TRUE);
 		curl_setopt($srs, CURLOPT_FOLLOWLOCATION,1);
 		curl_setopt($srs, CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
 		curl_setopt($srs, CURLOPT_SSL_VERIFYPEER, false);
 		curl_setopt($srs, CURLOPT_COOKIEFILE  , dirname(__FILE__).$tmp_fname);
 		curl_setopt($srs, CURLOPT_COOKIEJAR, dirname(__FILE__).$tmp_fname);
 		if($type == 'post'){
  			curl_setopt($srs, CURLOPT_POST, true);
  			curl_setopt($srs, CURLOPT_POSTFIELDS,$postdata);
 		}
 		$result = curl_exec($srs);
 		curl_close($srs);

		$file = fopen("public/assets/simple_html_dom/page_content.txt","w");
		fwrite($file, $result);
		fclose($file); 
	}

}
