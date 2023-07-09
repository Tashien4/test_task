<Br>
<style>form{text-align: center;}</style>
<?php 

		$form=$this->beginWidget('CActiveForm'); 
			echo '<h3>Введите строку для конструктора</h3>';
			echo '<p>Например: dcciii</p>';
			echo $form->textField($model,'order');
			 echo CHtml::submitButton('Отправить');
		$this->endWidget(); 

?>