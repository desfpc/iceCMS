<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var \iceRender $this
 */
?>
<div class="container-fluid footer navbar-fixed-bottom">
    <div class="container">
        <div class="row">

        </div>
        <div class="row">
            <div class="col-md-7">
                
            </div>
            <div class="col-md-5">

            </div>
        </div>
    </div>
</div>
<div id="toTop">▲</div>
<?php
$this->styles->printStyles();
$this->jscripts->printScripts();

$this->jsready .= "$(window).scroll(function() {
 
if($(this).scrollTop() != 0) {
 
$('#toTop').fadeIn();
 
} else {
 
$('#toTop').fadeOut();
 
}
 
});
 
$('#toTop').click(function() {
 
$('body,html').animate({scrollTop:0},800);
 
});";

?>
<script>
    $(function() {

        <?= $this->jsready ?>

    });
</script>
</body>
</html>