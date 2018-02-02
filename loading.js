$(function() {
var h = $(window).height(); //ブラウザウィンドウの高さを取得
$('#main-contents').css('display','none'); //初期状態ではメインコンテンツを非表示
$('#loader-bg ,#loader').height(h).css('display','block'); //ウィンドウの高さに合わせでローディング画面を表示
});
$(window).load(function () {
$('#loader-bg').delay(900).fadeOut(800); //$('#loader-bg').fadeOut(800);でも可
$('#loader').delay(600).fadeOut(300); //$('#loader').fadeOut(300);でも可
$('#main-contents').css('display', 'block'); // ページ読み込みが終わったらメインコンテンツを表示する
});