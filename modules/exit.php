<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var ice\iceRender $this
 *
 * Module for User de-authorization
 *
 */

use ice\iceHeaderBuilder;

$this->headers = new iceHeaderBuilder();
$this->headers->addHeader('Location: /');

$this->authorize->deAuthorize();
