<?php

class ApiController extends CController
{
public function actionGet(){

	$model=new Api;
	if(isset($_GET['order'])){
		$result=$model->create_ans($_GET['order']);
		$this->_sendResponse(200, json_encode($result,JSON_UNESCAPED_UNICODE));
	}
}

//---------------------------------------
private function _sendResponse($status = 200, $body = '', $contentType = 'application/json')
{
	header('HTTP/1.1 ' . $status . ' ' . $this->_getStatusCodeMessage($status));
	header('Content-type: ' . $contentType);
 		echo $body;
	Yii::app()->end();
}
//---------------------------------------
private function _getStatusCodeMessage($status)
{

    $codes = Array(
        200 => 'OK',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
    );
    return (isset($codes[$status])) ? $codes[$status] : '';
}
//---------------------------------------
public function actionCreate(){
	$model=new Api;
	if(isset($_POST['Api']['order'])){
		$this->redirect('get?order='.$_POST['Api']['order']);
	}
	$this->render('create',array('model'=>$model));
}
}
?>