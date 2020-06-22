<?php
/**
 * WooCommerce Korea - Shipment Tracking
 *
 * @package WC_Korea
 * @author  @jgreys
 */

defined( 'ABSPATH' ) || exit;

/**
 * WC_Korea_Shipment_Tracking class.
 */
class WC_Korea_Shipment_Tracking_Compat {

	/**
	 * Class constructor
	 */
	public function __construct() {
		add_action( 'wc_shipment_tracking_get_providers', array( $this, 'add_providers' ) );
	}

	/**
	 * Add korean providers
	 *
	 * @param array $providers Array of providers.
	 * @return array
	 */
	public function add_providers( $providers ) {
		$providers['South Korea']['CJ Korea Express'] = 'https://www.doortodoor.co.kr/parcel/doortodoor.do?fsp_action=PARC_ACT_002&fsp_cmd=retrieveInvNoACT&invc_no=%1$s'; /* CJ대한통운 */
		$providers['South Korea']['Korea Post']       = 'http://service.epost.go.kr/trace.RetrieveRegiPrclDeliv.postal?sid1=%1$s'; /* 우체국택배 */
		$providers['South Korea']['Hanjin Parcel']    = 'http://www.hanjin.co.kr/Delivery_html/inquiry/result_waybill.jsp?wbl_num=%1$s'; /* 한진택배 */
		$providers['South Korea']['Lotte Parcel']     = 'https://www.lotteglogis.com/home/reservation/tracking/linkView?InvNo=%1$s'; /* 롯데택배 */
		$providers['South Korea']['Logen Parcel']     = 'http://d2d.ilogen.com/d2d/delivery/invoice_tracesearch_quick.jsp?slipno=%1$s'; /* 로젠택배 */
		$providers['South Korea']['HomePick Parcel']  = 'http://www.hanjin.co.kr/Delivery_html/inquiry/result_waybill.jsp?wbl_num=%1$s'; /* 홈픽택배 */
		$providers['South Korea']['CVSnet']           = 'https://www.cvsnet.co.kr/reservation-inquiry/delivery/index.do?dlvry_type=domestic&invoice_no=%1$s&srch_type=01'; /* CVSnet 편의점택배 */
		$providers['South Korea']['CU Post']          = 'https://www.cupost.co.kr/postbox/delivery/localResult.cupost?invoice_no=%1$s'; /* CU 편의점택배 */
		$providers['South Korea']['Gyeongdong']       = 'http://kdexp.com/basicNewDelivery.kd?barcode=%1$s'; /* 경동택배 */
		$providers['South Korea']['Daesin Parcel']    = 'https://www.ds3211.co.kr/freight/internalFreightSearch.ht?billno=%1$s'; /* 대신택배 */
		$providers['South Korea']['ILANG Logis']      = 'http://www.ilyanglogis.com/functionality/tracking_result.asp?hawb_no=%1$s'; /* 일양로지스 */
		$providers['South Korea']['Hapdong Parcel']   = 'http://www.hdexp.co.kr/basic_delivery.hd?barcode=%1$s'; /* 합동택배 */
		$providers['South Korea']['Geonyeong Parcel'] = 'http://www.kunyoung.com/goods/goods_01.php?mulno=%1$s'; /* 건영택배 */
		$providers['South Korea']['Cheonil Parcel']   = 'http://www.chunil.co.kr/HTrace/HTrace.jsp?transNo=%1$s'; /* 천일택배 */
		$providers['South Korea']['Handekseu']        = 'https://www.hanjin.co.kr/Delivery_html/inquiry/result_waybill_international.jsp?wbl_num=%1$s'; /* 한덱스 */
		$providers['South Korea']['HANIPS Parcel']    = 'http://www.hanips.com/html/sub03_03_1.html?logicnum=%1$s'; /* 한의사랑택배 */
		$providers['South Korea']['EMS']              = 'http://service.epost.go.kr/trace.RetrieveEmsTrace.postal?ems_gubun=E&POST_CODE=%1$s';
		$providers['South Korea']['DHL']              = 'http://www.dhl.co.kr/content/kr/ko/express/tracking.shtml?brand=DHL&AWB=%1$s';
		$providers['South Korea']['TNT Express']      = 'http://www.tnt.com/webtracker/tracking.do?respCountry=kr&respLang=ko&searchType=CON&cons=%1$s';
		$providers['South Korea']['UPS']              = 'https://wwwapps.ups.com/WebTracking/track?track=yes&loc=ko_kr&trackNums=%1$s';
		$providers['South Korea']['Fedex']            = 'http://www.fedex.com/Tracking?ascend_header=1&clienttype=dotcomreg&cntry_code=kr&language=korean&tracknumbers=%1$s';
		$providers['South Korea']['USPS']             = 'https://tools.usps.com/go/TrackConfirmAction?tLabels=%1$s';
		$providers['South Korea']['i-Parcel']         = 'https://tracking.i-parcel.com/Home/Index?trackingnumber=%1$s';
		$providers['South Korea']['DHL Global Mail']  = 'http://webtrack.dhlglobalmail.com/?trackingnumber=%1$s';
		$providers['South Korea']['Pantos Logistics'] = 'http://totprd.pantos.com/jsp/gsi/vm/popup/notLoginTrackingListExpressPoPup.jsp?quickType=HBL_NO&quickNo=%1$s'; /* 범한판토스 */
		$providers['South Korea']['GSMNtoN']          = 'http://www.gsmnton.com/gsm/handler/Tracking-OrderList?searchType=TrackNo&trackNo=%1$s';
		$providers['South Korea']['KGL Networks']     = 'http://www.hydex.net/ehydex/jsp/home/distribution/tracking/tracingView.jsp?InvNo=%1$s'; /* KGL네트웍스 */
		$providers['South Korea']['Honam Logis']      = 'http://honamlogis.co.kr/page/index.php?pid=tracking_number&SLIP_BARCD=%1$s'; /* 호남택배 */
		$providers['South Korea']['GSI Express']      = 'http://www.gsiexpress.com/track_pop.php?track_type=ship_num&query_num=%1$s';
		$providers['South Korea']['SLX']              = 'http://slx.co.kr/delivery/delivery_number.php?param1=%1$s'; /* 우리한방택배 */
		$providers['South Korea']['Woori Parcel']     = 'http://www.realsystem.co.kr/wooritb/search/s_search.asp?f_slipno=%1$s';
		$providers['South Korea']['Sebang']           = 'https://delivery.sebang.com/sdelivery/guest/trace/trace.xhtml?DISPATCH_NOTE_NO=%1$s'; /* 세방 */
		$providers['South Korea']['KGB Parcel']       = 'http://www.kgbps.com/delivery/delivery_result.jsp?item_no=%1$s'; /* KGB택배 */
		$providers['South Korea']['Cway Express']     = 'http://service.cwaycorp.com/where/tracking?hbl=%1$s';

		return $providers;
	}

}

return new WC_Korea_Shipment_Tracking_Compat();
