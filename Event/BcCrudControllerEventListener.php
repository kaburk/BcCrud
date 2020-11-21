<?php

class BcCrudControllerEventListener extends BcControllerEventListener {

	/**
	 * 登録イベント
	 *
	 * @var array
	 */
	public $events = [
		// 'initialize',
	];

	/**
	 * initialize
	 *
	 * @param CakeEvent $event
	 */
	public function initialize(CakeEvent $event) {
		$Controller = $event->subject();
		// $Controller->helpers[] = 'BcCrud.BcCrud';
	}

}
