<?php

class Api extends CFormModel
{
public $order='';

public function rules()
	{
		return array(
			array('order','default')
		);
	}
//-------------------------------------
	protected function getAllOneType($array,$max){

    	$result=[];
    	$count = count($array); //сколько нужно ингридиентов этого типа в одной выборке

		if($max>=$count) {  //если нужно вывести больше, чем существует, то берем все ингредиенты этого типа
			$result[] = implode(',',$array);
		} else {
    		for ($i=0;$i<$count;$i++) { 
				$dop=',';
				if($max>1) {
					for($k=1;$k<=$max;$k++){
						if(($i+$k)<$count) 
							$dop.=$array[$i+$k].','; //$array[$i+1],$array[$i+2]=>$array[$i+1],$array[$i+2],$array[$i+3],
						elseif(strpos($dop,$array[0])===false) 
							$dop.=$array[0].',';

						if(strlen($array[$i].$dop)-substr_count($array[$i].$dop,',')==$max)		//записываем только когда комбинация будет нужной длины
							$result[] = substr($array[$i].$dop,0,-1);
					}
				}	else $result[] = $array[$i];// если только 1 ингредиент нужен
    	}
	}
    	return array_unique($result);
}

//---------------------------------------
protected function getAllFromArray($array,$count){

	$var=1;$result=[];
	for ($i=0;$i<$count;$i++) {
		$var*=count($array[$i]); //сколько будет всего вариантов		
		$counter[$i]=0;//счетчик для каждого из масивов
	};

	for($i=0;$i<$var;$i++) { 
	 	for ($j=0;$j<$count;$j++) { //$count-кол-во массивов- введенных типов	

	   		if ($counter[$j]<count($array[$j])-1) 	 //счетчик, чтобы у массивов меньшей длины  выбор элементов начинался с начала
		 		$counter[$j]++;
	   		else $counter[$j]=0;	
				for ($k=0;$k<$count;$k++) {
						$result[]=$array[$k][$counter[$k]].',';  
	   			}
	 	} 
	} 
	$result=array_chunk($result, $count); //режем по количеству типов
	$result = array_map('unserialize', array_unique(array_map( 'serialize', $result ))); // только уникальные массивы без повторов

	return $result; 
}
//---------------------------------------
	public function create_ans($order){

		//сколько требуется вариантов каждого ингредиента в одном products
		$count=array_values(array_count_values(str_split($order)));

		//только уникальные буквы
		$letters=array_values(array_unique(str_split($order)));

		//массив с данными всех ингредиентов
		$all_ing=Yii::app()->db->createCommand()
		->select('ingredient.*,it.title as name_ing')
		->from('ingredient')
		->join('ingredient_type  as it', 'it.id=ingredient.type_id')
		->queryAll();

		foreach($all_ing as $al){

			$all[$al['id']]['type']=$al['name_ing'];
			$all[$al['id']]['value']=$al['title'];
			$all[$al['id']]['price']=$al['price'];
			//список id, сгруппированных по типу ингредиента, для дальнейшего перебора вариантов в каждом типе
			$ingredients[$al['type_id']-1][]=$al['id'];
		}

	$res=[]; 
		for($i=0;$i<count($letters);$i++) {	
			if($count[$i]>0) {
				$comb_ing[$i]=$this->getAllOneType($ingredients[$i],$count[$i]); //комбинация из всех ингредиентов одного типа по количеству введенных символов
				array_push($res,$comb_ing[$i]); //массив всех комбинаций ингредиентов всех введенных типов
			}
		}	
		$results=$this->getAllFromArray($res,count($letters)); //перебираем массивы комбинации типов между собой для итоговой выборки (1-ое тесто с 1 сыром и 1 начинкой, 1 тесто, 1 сыром 2 начинкой и т.д.) 

		foreach($results as $result){ 
			$product=[];
			$ans['product']=[]; $ans['price']=[];
			$price=0;
			foreach($result as $res) {	
				$let=explode(',',$res);//массив id ингредиентов в каждой отдельной коминации
				$item = array_diff($let, array(''));
			//вывод под json
				foreach($item as $it){
					$product[]=['type'=>$all[$it]['type'],'value'=>$all[$it]['value']];
					$price+=$all[$it]['price'];
				}
				$prices[]=$price;
			}
			$ans['product']=$product;
			$ans['price']=$price;
			$answer[]=$ans;
		};

		return $answer;
	}
}