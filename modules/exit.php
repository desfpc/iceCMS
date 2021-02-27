<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var ice\Web\Render $this
 *
 * Module for User de-authorization
 *
 */

use ice\Web\HeaderBuilder;

$this->headers = new HeaderBuilder();
$this->headers->addHeader('Location: /');

$this->authorize->deAuthorize();
