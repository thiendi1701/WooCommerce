<?php
include 'mycurl.php';
/** Include PHPExcel */
require_once 'PHPExcel.php';
require_once 'PHPExcel/IOFactory.php';
			// $http= new mycurl('');
			// $http->setContentType("application/x-www-form-urlencoded ");


	// https://www.lazada.vn/catalog/?_keyori=ss&from=input&page=1&q=day%20guitar
	for ($i = 1; $i <=3; $i++) {
		//
		$s=file_get_contents('https://www.lazada.vn/catalog/?_keyori=ss&from=input&page='.$i.'&q=balo');
		// $s=file_get_contents('https://www.lazada.vn/catalog/?_keyori=ss&from=input&page=1&q=day%20guitar');
		// print_r(htmlentities($s));
		preg_match_all('/url\"\:\"(.*?)\?search/',$s,$result);
		// print_r($result[1]);
		$count=count($result[1]);
		for ($j = 1; $j < $count ; $j++) {
			echo '<br>';
			print_r($result[1][$j]);
			getItem($result[1][$j]);
			ini_set('max_execution_time',300);
			echo 'Write Excel';
		}

	}
	function getItem($url)
	{
		// print_r(htmlentities($s));
		// Name
		$s=file_get_contents($url);
		preg_match('/pdp-product-title\"\>(.*?)\</',$s,$name);
		// print_r($name[1]);
		$name=str_replace(' ','-',$name[1]);// lấy name tiếng viet vì excxcel convert sang utf8 dc
		$name_img=str_split($name,20);
		print_r($name_img[0]);	
		// print_r($name);
			//Gía
		preg_match_all('/(pdp-price)+\w+\"\>(.*?)\</',$s,$prices);
		// print_r($prices[2][0]);
		// print_r($prices[2][1]);

			// Mota 
		// preg_match('/description":"(.*?)","@context"/',$s,$description);
		// print_r(htmlentities($description[1]));
		// preg_match('/\"description\"\:\"(.*?)\&nbsp\;\<\/p\>/',$s,$description);
		// $description=str_replace('\n',' ',$description[1]);
		// $description=str_replace('<p>',' ',$description);
		// print_r(htmlentities($description));

			//image
		preg_match('/gallery-preview-panel\_\_image\"\ssrc\=\"(.*?)\"\s/',$s,$image);
		$image=imagecreatefromjpeg('http:'.$image[1]);
		// set time out thieu set chay nhanh qua mo ko kip :V

		imagejpeg($image, "c:/xampp/htdocs/wordpress/wp-content/uploads/2018/05/".convert($name_img[0]).".jpg");
		// print_r($image[1]);
		// sleep(100);
		writeExcel($name,$prices[2][0],$prices[2][1],convert($name_img[0]));
	}


	

	function convert($str) {
		$str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
		$str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
		$str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
		$str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
		$str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
		$str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
		$str = preg_replace("/(đ)/", 'd', $str);
		$str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
		$str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
		$str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
		$str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
		$str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
		$str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
		$str = preg_replace("/(Đ)/", 'D', $str);
		  //$str = str_replace(" ", "-", str_replace("&*#39;","",$str));
		return $str;
	}
	function writeExcel($name,$prices,$prices_sale,$name_img)
	{
		$objPHPExcel = new PHPExcel();
		$objPHPExcel = PHPExcel_IOFactory::load("data.xls");
		$objPHPExcel->setActiveSheetIndex(0);
		$row = $objPHPExcel->getActiveSheet()->getHighestRow()+1;
		$i=$row;
		$objPHPExcel->getActiveSheet()
		->setCellValue('B'.$i,'simple')
		->setCellValue('D'.$i,$name)
		->setCellValue('E'.$i,'1')
		->setCellValue('N'.$i,'1')
		->setCellValue('G'.$i,'visible')
		->setCellValue('L'.$i,'taxable')
	// ->setCellValue('I'.$i,$description)
		->setCellValue('X'.$i,$prices)
		->setCellValue('Y'.$i,$prices_sale)
		->setCellValue('Z'.$i,'Tshirts')
		->setCellValue('AC'.$i,'http://localhost/wordpress/wp-content/uploads/2018/05/'.$name_img.".jpg");
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		$objWriter->save('data.xls');
	}
	
	
	?>	

