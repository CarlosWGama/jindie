<?php

class SceneText extends Controller {


	public function index() {

		// **** SCENE  **** //
		/*$scene = $this->getGameComponent('SceneText');
		$scene->setText("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse non eros leo. Phasellus eu purus tortor. Aliquam aliquet sodales nulla in vulputate. Pellentesque accumsan porta faucibus. Pellentesque quis dui non massa venenatis porta. Aenean id augue dolor. Sed lacinia eleifend bibendum. Suspendisse potenti. Etiam vitae massa a orci consectetur volutpat eleifend ut velit. Aliquam at bibendum quam. Nunc turpis urna, egestas eu molestie vitae, dapibus mattis enim. Praesent pellentesque commodo mi, sit amet condimentum purus ornare sed. Integer ipsum arcu, vulputate eu nisl vitae, iaculis vulputate neque. Quisque viverra pretium orci, nec commodo eros fringilla nec. Quisque bibendum dui in gravida convallis. Donec volutpat dui eros, a fermentum purus convallis vitae.

Proin ut malesuada magna. Donec tincidunt pretium justo, ac sagittis tellus varius nec. Cras sed magna erat. Phasellus justo enim, convallis a elit ac, fermentum luctus metus. Nunc adipiscing, nibh in fermentum ornare, elit odio auctor sapien, vitae gravida ipsum lorem sed ligula. Cras semper egestas tortor eu sollicitudin. Morbi sit amet est sed lacus congue rutrum. Etiam ultricies, augue ac vulputate sagittis, risus lacus pharetra ligula, a ultricies arcu dolor sit amet turpis. Nulla a libero eget elit imperdiet porta sit amet sit amet magna.

Sed libero ligula, cursus non justo sit amet, posuere laoreet erat. Vestibulum interdum sed libero at ullamcorper. Duis a bibendum nibh. Curabitur vitae metus eu nunc consequat egestas. Sed hendrerit id elit in gravida. Phasellus nisl erat, congue a nunc a, cursus ullamcorper felis. Etiam aliquam arcu leo, et posuere quam varius ac. Vestibulum a libero aliquet, condimentum leo ut, auctor ipsum. Cras mollis nisi eget aliquam porttitor. Sed eget ante arcu.");
		$scene->setImage("http://www.keenthemes.com/preview/metronic/theme/assets/global/plugins/jcrop/demos/demo_files/image1.jpg");*/
		//$this->game->setScene($scene);

		// **** QUESTION  **** //
		$question = $this->getGameComponent('ShortAnswer1');		
		$question->setQuestion('O que é isso?');
		$question->setURLToSubmit('question/resposta/2');

		$this->game->getScene()->setQuestion($question);

		echo $this->game->getScene()->showScene();
	}
}