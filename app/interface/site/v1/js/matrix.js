$(function() {
  if($(window).width() >= 1260) {
    startMatrix('.PrologueUniComponent__matrix-top');
    startMatrix('.PrologueUniComponent__matrix-bottom');
  }
});

function startMatrix(className) {
  setInterval(function() {
    var $lines = $(className).find('.js-matrix-line').not('.PrologueUniRu__matrix-line--ready');

    var max = $lines.length;
    var randomNum = randomInteger(1, max);
    var randomNum2 = randomInteger(1, max);
    var $activeLine = $lines.eq(randomNum - 1);
    var $activeLine2 = $lines.eq(randomNum2 - 1);

    new lineAnima().animaLinesForward($activeLine);
    new lineAnima().animaLinesForward($activeLine2);
  }, 2000);

  setTimeout(function() {
    setInterval(function() {
      var $lines = $(className).find('.js-matrix-line.PrologueUniRu__matrix-line--ready');
      var max = $lines.length;

      var randomNum = randomInteger(1, max);
      var randomNum2 = randomInteger(1, max);

      var $activeLine = $lines.eq(randomNum - 1);
      var $activeLine2 = $lines.eq(randomNum2 - 1);

      new lineAnima().animaLinesBackward($activeLine);
      new lineAnima().animaLinesBackward($activeLine2);
    }, 2000);
  }, 3500);
}

function lineAnima() {
  this.countAnima = 0;
  this.maxCount = 0;

  this.animaLinesForward = function($line) {
    $line.addClass('PrologueUniRu__matrix-line--active');
    $line.addClass('PrologueUniRu__matrix-line--anima-forward-progress');

    var $animaLines = $line.find('.PrologueUniRu__matrix-line-anima');
    var that = this;

    that.maxCount = $animaLines.length - 1;

    $animaLines.eq(that.countAnima).animate({
      height: '100%'
    }, 200, 'linear', function() {
      that.countAnima++;

      // Есть еще линии для анимации
      if(that.countAnima <= that.maxCount) {
        that.animaLinesForward($line);
      }
      // Анимация текущего элемента завершена
      else {
        $line.addClass('PrologueUniRu__matrix-line--ready');

        $line.removeClass('PrologueUniRu__matrix-line--anima-forward-progress');
      }
    });
  };

  this.animaLinesBackward = function($line) {
    $line.addClass('PrologueUniRu__matrix-line--anima-backward-progress');

    var $animaLines = $line.find('.PrologueUniRu__matrix-line-anima');
    var that = this;

    that.maxCount = $animaLines.length - 1;

    $animaLines.eq(that.countAnima).animate({
      height: '0'
    }, 200, 'linear', function() {
      that.countAnima++;

      // Есть еще линии для анимации
      if(that.countAnima <= that.maxCount) {
        that.animaLinesBackward($line);
      }
      // Анимация текущего элемента завершена
      else {
        $line.removeClass('PrologueUniRu__matrix-line--ready');
        $line.removeClass('PrologueUniRu__matrix-line--active');

        $line.removeClass('PrologueUniRu__matrix-line--anima-backward-progress');
      }
    });
  }
}

function randomInteger(min, max) {
  let rand = min - 0.5 + Math.random() * (max - min + 1);

  return Math.round(rand);
}
