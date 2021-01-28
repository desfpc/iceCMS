<?php
/**
 * Created by Peshalov Sergey https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var \iceRender $this
 */

$template_folder=$this->settings->path.'/templates/'.$this->settings->template.'';

//подключаемые стили
$template_styles=array(
    'https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css',
    'https://fonts.googleapis.com/css?family=Rubik:300,400,700,900&amp;subset=cyrillic',
    '/css/site.css',
    '/css/500.css');

$this->styles->addStyles($template_styles);

//подключаемые js скрипты
$template_scripts=array(
    'https://code.jquery.com/jquery-3.3.1.min.js',
    'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js',
    'https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js',
    'https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js',
    '/js/404.js');

$this->jscripts->addScripts($template_scripts);

//подключение css и js от Visualijoper-а TODO подключать только при включенном дебаге
$this->styles->addStyle('/classes/visualijoper/visualijoper.css');
$this->jscripts->addScript('/classes/visualijoper/jquery.visualijoper.js');

//js document.load
$js_docload='';

?><!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="ice">
    <meta name="author" content="RedneckCode">
    <meta name="keyword" content="dashboard, message, task, manager, coloboration">

    <?= $this->styles->printStyles(); ?>

    <title><?= $this->moduleData->title ?></title>
</head>
<body class="permission_denied">
<div id="particles-js"></div>
<div class="denied__wrapper">
    <h1><?= $this->moduleData->H1 ?></h1>
    <h3><?= $this->moduleData->H3 ?><small></small></h3>
    <?php

    if($this->settings->dev && is_array($this->moduleData->errors) && count($this->moduleData->errors) > 0)
    {
        ?><div class="alert alert-danger" style="position: absolute;" role="alert"><small style="font-size: 10px;"><?php foreach ($this->moduleData->errors as $error){echo '<p>'.$error.'</p>';} ?></small></div><?php
    }

    ?>
    <svg id="astronaut" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
        <ellipse style="fill:#FFAA00;" cx="252.062" cy="330.342" rx="83.574" ry="32.815"/>
        <path style="fill:#FF7F00;" d="M252.069,297.504c46.167,0,83.566,14.675,83.566,32.8s-37.4,32.815-83.566,32.815"/>
        <path style="fill:#09B2A5;" d="M504.123,275.259c0,34.981-112.861,63.354-252.062,63.354C112.853,338.613,0,310.24,0,275.259
	s112.853-79.525,252.062-79.525C391.263,195.742,504.123,240.278,504.123,275.259z"/>
        <path style="fill:#089992;" d="M504.123,275.259c0,34.981-112.861,63.354-252.062,63.354C112.853,338.613,0,310.24,0,275.259
	s112.853-70.601,252.062-70.601C391.263,204.658,504.123,240.278,504.123,275.259z"/>
        <path style="fill:#048389;" d="M504.123,275.259c0,34.981-112.861,63.354-252.062,63.354C112.853,338.613,0,310.24,0,275.259
	s112.853-1.355,252.062-1.355C391.263,273.904,504.123,240.278,504.123,275.259z"/>
        <path style="fill:#A0EAEA;" d="M354.469,214.599c-6.451-41.511-49.782-73.633-102.408-73.633
	c-52.602,0-95.941,32.122-102.392,73.633H354.469z"/>
        <path style="fill:#79CCC8;" d="M149.669,214.599h204.8c-6.451-41.511-49.782-73.633-102.408-73.633"/>
        <g>
            <path style="fill:#66BCB5;" d="M304.301,214.599v-8.751c0-8.94-7.239-16.187-16.179-16.187h-72.105
		c-8.94,0-16.187,7.247-16.187,16.187v8.751H304.301z"/>
            <path style="fill:#66BCB5;" d="M354.469,214.599c-6.451-41.511-49.782-73.633-102.408-73.633"/>
        </g>
        <path style="fill:#09B2A5;" d="M366.931,215.906c0,0.969-0.796,1.772-1.772,1.772H141.241c-0.992,0-1.78-0.803-1.78-1.772l0,0
	c0-0.969,0.788-1.772,1.78-1.772h223.925C366.135,214.142,366.931,214.938,366.931,215.906L366.931,215.906z"/>
        <path style="fill:#048389;" d="M77.706,236c1.457,2.812-1.126,7.034-2.221,7.617l0,0c-1.119,0.567-6.042,0.244-7.499-2.568
	l-24.781-54.721c-1.449-2.796-1.733-5.553-0.614-6.12l0,0c1.111-0.567,3.198,1.229,4.647,4.033L77.706,236z"/>
        <path style="fill:#089992;" d="M43.197,186.321c-1.449-2.796-1.733-5.553-0.614-6.12l0,0c1.111-0.567,3.198,1.229,4.647,4.033
	L77.706,236c1.457,2.812-1.126,7.034-2.221,7.617l0,0"/>
        <polygon style="fill:#048389;" points="65.843,278.843 34.706,273.243 69.742,238.214 75.335,243.814 "/>
        <circle style="fill:#FF7F00;" cx="42.583" cy="179.893" r="8.751"/>
        <path style="fill:#FFAA00;" d="M36.384,173.694c3.426-3.434,8.972-3.419,12.398,0c3.411,3.419,3.434,8.972,0,12.398"/>
        <path style="fill:#048389;" d="M426.417,236c-1.449,2.812,1.126,7.034,2.229,7.617l0,0c1.118,0.567,6.034,0.244,7.491-2.568
	l24.781-54.721c1.457-2.796,1.741-5.553,0.614-6.12l0,0c-1.111-0.567-3.182,1.229-4.632,4.033L426.417,236z"/>
        <path style="fill:#089992;" d="M460.926,186.321c1.457-2.796,1.741-5.553,0.614-6.12l0,0c-1.111-0.567-3.182,1.229-4.632,4.033
	L426.417,236c-1.449,2.812,1.126,7.034,2.229,7.617l0,0"/>
        <polygon style="fill:#048389;" points="438.288,278.843 469.425,273.243 434.381,238.214 428.788,243.814 "/>
        <circle style="fill:#FF7F00;" cx="461.509" cy="179.893" r="8.759"/>
        <path style="fill:#FFAA00;" d="M467.747,173.694c-3.426-3.434-8.972-3.419-12.398,0c-3.411,3.419-3.426,8.972,0,12.398"/>
        <path style="fill:#04727A;" d="M47.002,267.169c0,4.844,0.63,13.32-4.222,13.32c-4.829,0-13.296-8.476-13.296-13.32
	c0-4.829,3.923-8.751,8.751-8.751C43.079,258.418,47.002,262.341,47.002,267.169z"/>
        <circle style="fill:#BFD7E2;" cx="38.235" cy="267.169" r="8.759"/>
        <path style="fill:#AAC7D1;" d="M32.035,260.986c3.442-3.45,8.964-3.434,12.398-0.016c3.419,3.434,3.434,8.972,0,12.398"/>
        <path style="fill:#04727A;" d="M94.515,267.169c0,4.844,0.63,13.32-4.222,13.32c-4.829,0-13.296-8.476-13.296-13.32
	c0-4.829,3.915-8.751,8.751-8.751C90.592,258.418,94.515,262.341,94.515,267.169z"/>
        <circle style="fill:#BFD7E2;" cx="85.756" cy="267.169" r="8.759"/>
        <path style="fill:#AAC7D1;" d="M79.557,260.986c3.434-3.45,8.972-3.434,12.398-0.016c3.403,3.434,3.434,8.972,0,12.398"/>
        <path style="fill:#04727A;" d="M142.021,267.169c0,4.844,0.638,13.32-4.214,13.32c-4.829,0-13.296-8.476-13.296-13.32
	c0-4.829,3.907-8.751,8.751-8.751S142.021,262.341,142.021,267.169z"/>
        <circle style="fill:#BFD7E2;" cx="133.262" cy="267.169" r="8.759"/>
        <path style="fill:#AAC7D1;" d="M127.071,260.986c3.426-3.45,8.972-3.434,12.39-0.016c3.419,3.434,3.434,8.972,0,12.398"/>
        <path style="fill:#04727A;" d="M189.535,267.169c0,4.844,0.63,13.32-4.214,13.32c-4.829,0-13.304-8.476-13.304-13.32
	c0-4.829,3.923-8.751,8.759-8.751C185.62,258.418,189.535,262.341,189.535,267.169z"/>
        <circle style="fill:#BFD7E2;" cx="180.775" cy="267.169" r="8.759"/>
        <path style="fill:#AAC7D1;" d="M174.584,260.986c3.426-3.45,8.972-3.434,12.398-0.016c3.419,3.434,3.419,8.972,0,12.398"/>
        <path style="fill:#04727A;" d="M237.056,267.169c0,4.844,0.63,13.32-4.214,13.32c-4.829,0-13.296-8.476-13.296-13.32
	c0-4.829,3.915-8.751,8.751-8.751C233.141,258.418,237.056,262.341,237.056,267.169z"/>
        <circle style="fill:#BFD7E2;" cx="228.297" cy="267.169" r="8.759"/>
        <path style="fill:#AAC7D1;" d="M222.098,260.986c3.426-3.45,8.972-3.434,12.398-0.016c3.411,3.434,3.426,8.972,0,12.398"/>
        <path style="fill:#04727A;" d="M284.57,267.169c0,4.844,0.63,13.32-4.214,13.32c-4.836,0-13.296-8.476-13.296-13.32
	c0-4.829,3.915-8.751,8.743-8.751C280.655,258.418,284.57,262.341,284.57,267.169z"/>
        <circle style="fill:#BFD7E2;" cx="275.81" cy="267.169" r="8.759"/>
        <path style="fill:#AAC7D1;" d="M269.611,260.986c3.434-3.45,8.98-3.434,12.398-0.016c3.419,3.434,3.426,8.972,0,12.398"/>
        <path style="fill:#04727A;" d="M332.083,267.169c0,4.844,0.622,13.32-4.222,13.32c-4.829,0-13.296-8.476-13.296-13.32
	c0-4.829,3.915-8.751,8.751-8.751C328.168,258.418,332.083,262.341,332.083,267.169z"/>
        <circle style="fill:#BFD7E2;" cx="323.324" cy="267.169" r="8.759"/>
        <path style="fill:#AAC7D1;" d="M317.125,260.986c3.434-3.45,8.98-3.434,12.398-0.016c3.419,3.434,3.434,8.972,0,12.398"/>
        <path style="fill:#04727A;" d="M379.597,267.169c0,4.844,0.63,13.32-4.214,13.32c-4.836,0-13.296-8.476-13.296-13.32
	c0-4.829,3.915-8.751,8.743-8.751C375.682,258.418,379.597,262.341,379.597,267.169z"/>
        <circle style="fill:#BFD7E2;" cx="370.846" cy="267.169" r="8.759"/>
        <path style="fill:#AAC7D1;" d="M364.639,260.986c3.434-3.45,8.98-3.434,12.398-0.016c3.419,3.434,3.434,8.972,0,12.398"/>
        <path style="fill:#04727A;" d="M427.11,267.169c0,4.844,0.63,13.32-4.214,13.32c-4.829,0-13.296-8.476-13.296-13.32
	c0-4.829,3.923-8.751,8.751-8.751C423.203,258.418,427.11,262.341,427.11,267.169z"/>
        <circle style="fill:#BFD7E2;" cx="418.351" cy="267.169" r="8.759"/>
        <path style="fill:#AAC7D1;" d="M412.152,260.986c3.434-3.45,8.972-3.434,12.398-0.016c3.419,3.434,3.434,8.972,0,12.398"/>
        <path style="fill:#04727A;" d="M474.632,267.169c0,4.844,0.63,13.32-4.23,13.32c-4.829,0-13.296-8.476-13.296-13.32
	c0-4.829,3.923-8.751,8.751-8.751C470.709,258.418,474.632,262.341,474.632,267.169z"/>
        <circle style="fill:#BFD7E2;" cx="465.841" cy="267.169" r="8.759"/>
        <path style="fill:#AAC7D1;" d="M459.658,260.986c3.442-3.45,8.98-3.434,12.406-0.016c3.411,3.434,3.434,8.972,0,12.398"/>
        <g>
            <path style="fill:#048389;" d="M271.525,228.171c0,0.693-0.567,1.26-1.26,1.26h-36.399c-0.693,0-1.26-0.567-1.26-1.26l0,0
		c0-0.709,0.567-1.26,1.26-1.26h36.399C270.95,226.911,271.525,227.462,271.525,228.171L271.525,228.171z"/>
            <path style="fill:#048389;" d="M271.525,237.261c0,0.709-0.567,1.26-1.26,1.26h-36.399c-0.693,0-1.26-0.551-1.26-1.26l0,0
		c0-0.693,0.567-1.26,1.26-1.26h36.399C270.95,236,271.525,236.568,271.525,237.261L271.525,237.261z"/>
            <path style="fill:#048389;" d="M271.525,246.367c0,0.693-0.567,1.26-1.26,1.26h-36.399c-0.693,0-1.26-0.567-1.26-1.26l0,0
		c0-0.709,0.567-1.276,1.26-1.276h36.399C270.95,245.09,271.525,245.658,271.525,246.367L271.525,246.367z"/>
        </g>
    </svg>
    <svg id="planet" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
        <circle class="saturn" cx="256" cy="256" r="149.536" fill="#FF4F54"/>
        <g class="saturn-2" fill="#EA4753">
            <path d="M109.388 285.56c42.515 1.428 157.943-2.613 289.462-73.807-5.11-16.448-13.02-31.882-23.322-45.604-42.716 29.386-140.403 83.922-268.457 76.27-1.354 14.666-.508 29.175 2.318 43.14zM400.734 293.587c3.976-15.31 5.422-30.68 4.614-45.672-33.75 25.31-137.237 92.367-277.65 84.876 6.507 10.874 14.383 20.93 23.472 29.88 44.354.286 137.696-6.443 245.93-57.163 1.362-3.89 2.58-7.86 3.634-11.92zM245.488 405.184c35.427 2.537 69.784-7.742 97.543-27.59-27.972 11.533-60.787 21.76-97.542 27.59zM348.02 138.097c-15.645-12.225-33.99-21.522-54.434-26.832-71.883-18.667-145.126 18.253-174.25 84.01 49.02-1.676 133.073-12.256 228.685-57.178z"/>
        </g>
        <circle class="hover" cx="319.166" cy="208.081" r="28.389" fill="#D83A4E"/>
        <path d="M331.25 189.492c6.04 1.568 11.114 4.97 14.792 9.452-2.98-8.73-10.143-15.848-19.74-18.34-15.175-3.94-30.672 5.167-34.613 20.342-2.373 9.136-.012 18.384 5.55 25.162-1.73-5.075-2.05-10.695-.602-16.273 3.94-15.177 19.438-24.284 34.613-20.343z" opacity=".1"/>
        <circle class="hover" cx="234.463" cy="273.978" r="19.358" fill="#D83A4E"/>
        <path d="M242.703 261.303c4.118 1.07 7.578 3.39 10.085 6.444-2.03-5.953-6.916-10.806-13.46-12.505-10.347-2.687-20.914 3.523-23.6 13.87-1.62 6.23-.008 12.537 3.785 17.158-1.18-3.46-1.398-7.293-.41-11.097 2.686-10.348 13.252-16.558 23.6-13.87z" opacity=".1"/>
        <circle class="hover" cx="307.493" cy="144.207" r="12.584" fill="#D83A4E"/>
        <path d="M312.85 135.967c2.678.695 4.927 2.204 6.557 4.19-1.32-3.872-4.496-7.026-8.75-8.13-6.727-1.747-13.596 2.29-15.343 9.017-1.052 4.05-.005 8.15 2.46 11.153-.767-2.25-.908-4.74-.267-7.213 1.747-6.727 8.616-10.764 15.343-9.017z" opacity=".1"/>
        <circle class="hover" cx="163.289" cy="255.495" r="19.358" fill="#D83A4E"/>
        <path d="M171.53 242.82c4.118 1.068 7.577 3.388 10.084 6.443-2.03-5.954-6.916-10.806-13.46-12.505-10.348-2.687-20.915 3.523-23.602 13.87-1.618 6.23-.008 12.536 3.785 17.158-1.18-3.46-1.398-7.293-.41-11.097 2.687-10.348 13.255-16.558 23.602-13.87z" opacity=".1"/>
        <circle class="hover" cx="230.586" cy="365.92" r="19.358" fill="#D83A4E"/>
        <path d="M238.826 353.245c4.118 1.07 7.578 3.39 10.085 6.444-2.03-5.954-6.915-10.807-13.46-12.506-10.347-2.688-20.914 3.522-23.6 13.87-1.62 6.23-.01 12.536 3.784 17.157-1.18-3.46-1.398-7.292-.41-11.096 2.688-10.346 13.255-16.556 23.602-13.87z" opacity=".1"/>
        <circle class="hover" cx="302.221" cy="353.781" r="19.357" fill="#D83A4E"/>
        <path d="M310.462 341.105c4.118 1.07 7.577 3.39 10.085 6.445-2.03-5.954-6.916-10.807-13.46-12.506-10.348-2.688-20.914 3.523-23.602 13.87-1.618 6.23-.008 12.536 3.785 17.157-1.18-3.46-1.398-7.29-.41-11.095 2.687-10.346 13.254-16.556 23.602-13.87z" opacity=".1"/>
        <circle class="hover" cx="358.827" cy="284.847" r="11.079" fill="#D83A4E"/>
        <path d="M363.542 277.593c2.357.612 4.337 1.94 5.772 3.688-1.162-3.406-3.958-6.184-7.703-7.156-5.922-1.537-11.97 2.017-13.507 7.938-.926 3.565-.005 7.175 2.166 9.82-.676-1.98-.8-4.175-.235-6.352 1.537-5.92 7.585-9.475 13.507-7.937z" opacity=".1"/>
        <circle class="hover" cx="220.465" cy="156.416" r="11.079" fill="#D83A4E"/>
        <path d="M225.18 149.162c2.358.612 4.338 1.94 5.773 3.688-1.162-3.408-3.958-6.185-7.703-7.157-5.922-1.538-11.97 2.016-13.508 7.938-.926 3.566-.004 7.175 2.167 9.82-.677-1.98-.8-4.174-.236-6.35 1.537-5.922 7.585-9.476 13.507-7.938z" opacity=".1"/>
        <circle class="hover" cx="154.027" cy="171.743" r="5.819" fill="#D83A4E"/>
        <path d="M156.504 167.933c1.238.322 2.278 1.02 3.03 1.938-.61-1.79-2.078-3.248-4.045-3.758-3.11-.808-6.288 1.06-7.095 4.17-.486 1.872-.002 3.767 1.138 5.156-.354-1.04-.42-2.192-.124-3.335.807-3.11 3.984-4.978 7.094-4.17z" opacity=".1"/>
        <circle class="hover" cx="391.387" cy="251.305" r="5.819" fill="#D83A4E"/>
        <path d="M393.864 247.495c1.237.32 2.277 1.02 3.03 1.937-.61-1.79-2.078-3.248-4.045-3.76-3.11-.807-6.288 1.06-7.096 4.17-.486 1.873-.002 3.768 1.138 5.158-.354-1.04-.42-2.192-.123-3.336.807-3.11 3.983-4.977 7.094-4.17z" opacity=".1"/>
        <circle class="hover" cx="145.077" cy="302.473" r="5.819" fill="#D83A4E"/>
        <path d="M147.554 298.662c1.238.322 2.277 1.02 3.03 1.938-.61-1.79-2.078-3.248-4.045-3.76-3.11-.807-6.288 1.06-7.096 4.17-.486 1.873-.002 3.77 1.138 5.157-.355-1.04-.42-2.19-.124-3.335.81-3.11 3.985-4.978 7.096-4.17z" opacity=".1"/>
        <circle class="hover" cx="187.342" cy="355.265" r="5.819" fill="#D83A4E"/>
        <path d="M189.818 351.455c1.238.32 2.278 1.02 3.032 1.938-.61-1.79-2.08-3.25-4.046-3.76-3.11-.808-6.287 1.06-7.095 4.17-.487 1.872-.003 3.768 1.137 5.157-.354-1.04-.42-2.192-.123-3.336.808-3.11 3.984-4.977 7.094-4.17z" opacity=".1"/>
        <path d="M351.36 140.785c10.244 27.673 12.43 58.646 4.45 89.372-20.76 79.935-102.387 127.907-182.32 107.15-21.917-5.693-41.423-15.968-57.776-29.522 16.405 44.32 53.49 80.17 102.7 92.95 79.934 20.758 161.562-27.214 182.32-107.148 15.068-58.02-6.082-116.922-49.373-152.802z" opacity=".1"/>
        <g>
            <path class="stars" fill="#FFF" d="M112.456 363.093c-.056 7.866-6.478 14.197-14.344 14.142 7.866.056 14.198 6.48 14.142 14.345.056-7.866 6.48-14.198 14.345-14.142-7.868-.057-14.2-6.48-14.144-14.345zM432.436 274.908c-.056 7.866-6.478 14.198-14.344 14.142 7.866.057 14.197 6.48 14.142 14.345.056-7.866 6.48-14.197 14.345-14.142-7.868-.056-14.2-6.48-14.144-14.345zM159.75 58.352c-.12 16.537-13.62 29.848-30.157 29.73 16.537.118 29.848 13.62 29.73 30.156.118-16.537 13.62-29.848 30.156-29.73-16.54-.117-29.85-13.62-29.73-30.156z"/>
        </g>
    </svg>
    <a href="/"><button class="denied__link"><?= $this->moduleData->buttonHome ?></button></a>
</div>
<?= $this->jscripts->printScripts(); ?>
</body>
</html>